@php
    $siteKey = config('services.turnstile.site_key');
    $inputs = [
        ['name', 'text', 'name', 'name'],
        ['email', 'email', 'email', 'email'],
        ['phone', 'tel', 'tel', 'phone'],
    ];
@endphp
<div x-data="{ toast: false }" x-on:contact-sent.window="toast = true; setTimeout(() => toast = false, 7000); window.turnstile && window.turnstile.reset()">
    <div x-show="toast" x-cloak x-transition role="status"
         class="fixed bottom-6 inset-x-4 z-[90] mx-auto max-w-md rounded-xl border border-gold-500 bg-navy-900 p-4 text-center shadow-2xl">
        {{ __('site.contact.form.success') }}
    </div>

    <form wire:submit="submit" novalidate class="space-y-5">
        {{-- Honeypot: hidden from humans and assistive tech --}}
        <div class="absolute -start-[9999px]" aria-hidden="true">
            <label>Website <input type="text" wire:model="website" tabindex="-1" autocomplete="off"></label>
        </div>

        @foreach ($inputs as [$field, $type, $auto, $label])
            <div>
                <label for="cf-{{ $field }}" class="mb-2 block text-sm font-semibold">{{ __('site.contact.form.'.$label) }} <span class="text-gold-500" aria-hidden="true">*</span></label>
                <input id="cf-{{ $field }}" type="{{ $type }}" wire:model.blur="{{ $field }}" autocomplete="{{ $auto }}"
                       class="field" @if($field === 'phone') dir="ltr" inputmode="tel" placeholder="05XXXXXXXX" @endif
                       @error($field) aria-invalid="true" aria-describedby="cf-{{ $field }}-err" @enderror required>
                @if ($field === 'phone')<p class="mt-1 text-xs text-ink-400">{{ __('site.contact.form.phone_hint') }}</p>@endif
                @error($field)<p id="cf-{{ $field }}-err" class="mt-1 text-sm text-red-300" role="alert">{{ $message }}</p>@enderror
            </div>
        @endforeach

        <div x-data="{ n: $wire.entangle('message').live }">
            <label for="cf-message" class="mb-2 block text-sm font-semibold">{{ __('site.contact.form.message') }}</label>
            <textarea id="cf-message" rows="4" x-model="n" maxlength="{{ \App\Livewire\ContactForm::MAX_MESSAGE }}" class="field resize-y"
                      @error('message') aria-invalid="true" @enderror></textarea>
            <p class="mt-1 text-end text-xs text-ink-400" dir="ltr"><span x-text="(n ?? '').length"></span>/{{ \App\Livewire\ContactForm::MAX_MESSAGE }}</p>
            @error('message')<p class="text-sm text-red-300" role="alert">{{ $message }}</p>@enderror
        </div>

        @if ($siteKey)
            <div wire:ignore x-data x-init="window.silsilahTs = (t) => $wire.set('turnstileToken', t)">
                <div class="cf-turnstile" data-sitekey="{{ $siteKey }}" data-callback="silsilahTs" data-theme="dark"></div>
            </div>
            <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        @endif

        <button type="submit" class="btn btn-primary w-full" wire:loading.attr="disabled" wire:target="submit">
            <span wire:loading.remove wire:target="submit">{{ __('site.contact.form.send') }}</span>
            <span wire:loading wire:target="submit">{{ __('site.contact.form.sending') }}</span>
        </button>
    </form>
</div>
