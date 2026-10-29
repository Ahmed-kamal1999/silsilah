<?php

namespace Tests\Feature;

use App\Filament\Pages\Settings;
use App\Filament\Resources\ContactMessageResource;
use App\Filament\Resources\ServiceResource;
use App\Filament\Resources\ServiceResource\Pages\CreateService;
use App\Filament\Resources\ServiceResource\Pages\EditService;
use App\Models\ContactMessage;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\SilsilahSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SilsilahSeeder::class);
        $this->actingAs(User::first());
    }

    public function test_guests_are_redirected_to_login(): void
    {
        auth()->logout();
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_every_admin_page_renders_in_both_locales(): void
    {
        $urls = ['/admin', '/admin/settings'];
        foreach (['service-categories', 'services', 'post-categories', 'posts', 'clients', 'partners', 'stats', 'pages', 'contact-messages'] as $r) {
            $urls[] = "/admin/$r";
        }
        foreach (['service-categories', 'services', 'post-categories', 'posts', 'clients', 'partners', 'stats'] as $r) {
            $urls[] = "/admin/$r/create";
            $urls[] = "/admin/$r/1/edit";
        }
        $urls[] = '/admin/pages/1/edit';

        foreach (['ar', 'en'] as $loc) {
            foreach ($urls as $url) {
                $this->withUnencryptedCookie('admin_locale', $loc)->get($url)->assertOk();
            }
        }
    }

    public function test_service_edit_form_is_filled_with_both_translations(): void
    {
        $service = Service::where('slug', 'pentest')->first();

        Livewire::test(EditService::class, ['record' => $service->getRouteKey()])
            ->assertSchemaStateSet(['title' => $service->getTranslations('title')]);
    }

    public function test_service_edit_saves_both_translations(): void
    {
        $service = Service::where('slug', 'pentest')->first();

        Livewire::test(EditService::class, ['record' => $service->getRouteKey()])
            ->fillForm(['title' => ['ar' => 'عنوان جديد', 'en' => 'New title']])
            ->call('save')->assertHasNoFormErrors();

        $service->refresh();
        $this->assertSame('عنوان جديد', $service->getTranslation('title', 'ar'));
        $this->assertSame('New title', $service->getTranslation('title', 'en'));
    }

    public function test_service_can_be_created_with_arabic_only(): void
    {
        Livewire::test(CreateService::class)
            ->fillForm([
                'title' => ['ar' => 'خدمة تجريبية', 'en' => null],
                'slug' => 'test-service',
                'category_id' => ServiceCategory::first()->id,
            ])
            ->call('create')->assertHasNoFormErrors();

        $svc = Service::where('slug', 'test-service')->first();
        $this->assertNotNull($svc);
        $this->assertGreaterThan(0, $svc->sort);
        // English falls back to Arabic on the public site
        app()->setLocale('en');
        $this->assertSame('خدمة تجريبية', $svc->title);
    }

    public function test_message_is_marked_read_when_opened(): void
    {
        $m = ContactMessage::create(['name' => 'N', 'email' => 'n@example.com', 'phone' => '+966501234567', 'message' => 'hi']);
        $this->get(ContactMessageResource::getUrl('view', ['record' => $m]))->assertOk();
        $this->assertNotNull($m->fresh()->read_at);
    }

    public function test_settings_page_saves(): void
    {
        Livewire::test(Settings::class)
            ->fillForm(['phone' => '+966 11 111 1111', 'email' => 'x@example.com', 'address' => ['ar' => 'الرياض', 'en' => 'Riyadh']])
            ->call('save')->assertHasNoFormErrors();

        $this->assertSame('+966 11 111 1111', Setting::get('phone'));
        $this->assertSame('Riyadh', Setting::get('address')['en']);
    }
}
