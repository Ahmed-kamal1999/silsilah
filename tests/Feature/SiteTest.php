<?php

namespace Tests\Feature;

use App\Livewire\ContactForm;
use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Models\Post;
use Database\Seeders\SilsilahSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SilsilahSeeder::class);
    }

    public function test_root_redirects_to_arabic(): void
    {
        $this->get('/')->assertRedirect('/ar');
    }

    public function test_pages_render_in_both_locales_with_correct_direction(): void
    {
        $slugs = ['about', 'blog', 'contact', 'privacy', 'terms', 'solutions/financial-protection', 'solutions/fintech-development', 'services/pentest', 'blog/pentest-payment-gateways'];

        foreach ($slugs as $slug) {
            $this->get("/ar/$slug")->assertOk()->assertSee('dir="rtl"', false);
            $this->get("/en/$slug")->assertOk()->assertSee('dir="ltr"', false);
        }
        $this->get('/ar')->assertOk()->assertSee('أمن وتطور قطاعك المالي أولويتنا');
        $this->get('/en')->assertOk()->assertSee('is our priority');
    }

    public function test_hreflang_and_canonical_are_emitted(): void
    {
        $this->get('/en/about')
            ->assertSee('hreflang="ar" href="'.url('/ar/about').'"', false)
            ->assertSee('hreflang="en" href="'.url('/en/about').'"', false)
            ->assertSee('<link rel="canonical" href="'.url('/en/about').'"', false);
    }

    public function test_unknown_locale_and_missing_records_404(): void
    {
        $this->get('/fr')->assertNotFound();
        $this->get('/ar/services/nope')->assertNotFound();
        $this->get('/ar/nope')->assertNotFound()->assertSee('404');
    }

    public function test_unpublished_or_future_posts_are_hidden(): void
    {
        $post = Post::first();
        $post->update(['is_published' => false]);
        $this->get('/ar/blog/'.$post->slug)->assertNotFound();

        $post->update(['is_published' => true, 'published_at' => now()->addDay()]);
        $this->get('/ar/blog/'.$post->slug)->assertNotFound();
    }

    public function test_blog_search_and_category_filter(): void
    {
        $this->get('/en/blog?q=Open+Banking')->assertOk()->assertSee('Open Banking in Saudi Arabia')->assertDontSee('PCI DSS 4.0');
        $this->get('/en/blog?category=compliance')->assertOk()->assertSee('PCI DSS 4.0')->assertDontSee('Open Banking in Saudi Arabia');
        $this->get('/en/blog?q=zzzzzz')->assertOk()->assertSee('No matching articles.');
    }

    public function test_sitemap_and_robots(): void
    {
        $this->get('/sitemap.xml')->assertOk()->assertSee('/en/services/pentest', false);
        $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /admin');
    }

    public function test_contact_form_stores_message_and_queues_mail(): void
    {
        Mail::fake();

        Livewire::test(ContactForm::class)
            ->set('name', 'Ahmed Ali')->set('email', 'a@example.com')->set('phone', '0501234567')->set('message', 'Hello')
            ->call('submit')
            ->assertHasNoErrors()->assertSet('sent', true);

        $this->assertDatabaseHas('contact_messages', ['name' => 'Ahmed Ali', 'phone' => '+966501234567']);
        Mail::assertQueued(ContactMessageReceived::class);
    }

    public function test_contact_form_validation(): void
    {
        Livewire::test(ContactForm::class)
            ->set('email', 'bad')->set('phone', '12345')->set('message', str_repeat('x', 181))
            ->call('submit')
            ->assertHasErrors(['name' => 'required', 'email', 'phone', 'message' => 'max']);

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_saudi_phone_formats(): void
    {
        foreach (['0501234567', '501234567', '966501234567', '+966501234567', '00966501234567'] as $ok) {
            $this->assertSame(1, preg_match(ContactForm::PHONE_REGEX, $ok), $ok);
        }
        foreach (['0401234567', '05012345', '+971501234567', 'abc'] as $bad) {
            $this->assertSame(0, preg_match(ContactForm::PHONE_REGEX, $bad), $bad);
        }
    }

    public function test_honeypot_discards_silently(): void
    {
        Livewire::test(ContactForm::class)
            ->set('name', 'Bot')->set('email', 'b@example.com')->set('phone', '0501234567')->set('website', 'http://spam')
            ->call('submit')->assertSet('sent', true);

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_rate_limit_blocks_sixth_submission(): void
    {
        Mail::fake();
        RateLimiter::clear('contact:127.0.0.1');

        for ($i = 0; $i < 5; $i++) {
            Livewire::test(ContactForm::class)
                ->set('name', 'A')->set('email', 'a@example.com')->set('phone', '0501234567')->call('submit');
        }
        Livewire::test(ContactForm::class)
            ->set('name', 'A')->set('email', 'a@example.com')->set('phone', '0501234567')
            ->call('submit')->assertHasErrors('name');

        $this->assertSame(5, ContactMessage::count());
    }

    public function test_validation_messages_follow_locale(): void
    {
        app()->setLocale('en');
        Livewire::test(ContactForm::class)->call('submit')->assertHasErrors('name');
        $this->assertStringContainsString('required', Livewire::test(ContactForm::class)->call('submit')->errors()->first('name'));
    }
}
