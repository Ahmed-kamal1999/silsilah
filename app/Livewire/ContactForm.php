<?php

namespace App\Livewire;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class ContactForm extends Component
{
    public const MAX_MESSAGE = 180;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $message = '';
    public string $website = ''; // honeypot, must stay empty
    public string $turnstileToken = '';
    public bool $sent = false;

    /** Saudi mobile: 05XXXXXXXX, 5XXXXXXXX, 9665XXXXXXXX, +9665XXXXXXXX, 009665XXXXXXXX. */
    public const PHONE_REGEX = '/^(?:\+?966|00966|0)?5\d{8}$/';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'phone' => ['required', 'regex:'.self::PHONE_REGEX],
            'message' => ['nullable', 'string', 'max:'.self::MAX_MESSAGE],
        ];
    }

    protected function messages(): array
    {
        return [
            '*.required' => __('site.contact.validation.required'),
            'email.email' => __('site.contact.validation.email'),
            'phone.regex' => __('site.contact.validation.phone'),
            '*.max' => __('site.contact.validation.max'),
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'name' => __('site.contact.form.name'),
            'email' => __('site.contact.form.email'),
            'phone' => __('site.contact.form.phone'),
            'message' => __('site.contact.form.message'),
        ];
    }

    public function updated(string $property): void
    {
        if (in_array($property, ['name', 'email', 'phone', 'message'], true)) {
            $this->validateOnly($property);
        }
    }

    public function submit(): void
    {
        // Bots fill the hidden field; pretend success without storing anything.
        if ($this->website !== '') {
            $this->sent = true;

            return;
        }

        $key = 'contact:'.request()->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('name', __('site.contact.form.throttle', ['seconds' => RateLimiter::availableIn($key)]));

            return;
        }

        $this->validate();

        if (! $this->passesTurnstile()) {
            $this->addError('name', __('site.contact.form.captcha'));

            return;
        }

        RateLimiter::hit($key, 60);

        $record = ContactMessage::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->normalizedPhone(),
            'message' => $this->message ?: null,
            'ip' => request()->ip(),
            'user_agent' => substr((string) request()->userAgent(), 0, 500),
        ]);

        $to = Setting::get('email') ?: config('mail.admin_address');
        if ($to) {
            Mail::to($to)->queue(new ContactMessageReceived($record));
        }

        $this->reset(['name', 'email', 'phone', 'message', 'turnstileToken']);
        $this->sent = true;
        $this->dispatch('contact-sent');
    }

    private function normalizedPhone(): string
    {
        return '+966'.substr(preg_replace('/\D/', '', $this->phone), -9);
    }

    private function passesTurnstile(): bool
    {
        $secret = config('services.turnstile.secret_key');
        if (! $secret) {
            return true; // Turnstile is optional
        }

        $res = Http::asForm()->timeout(5)->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret' => $secret,
            'response' => $this->turnstileToken,
            'remoteip' => request()->ip(),
        ]);

        return $res->ok() && $res->json('success') === true;
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
