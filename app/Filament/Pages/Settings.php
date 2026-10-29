<?php

namespace App\Filament\Pages;

use App\Filament\Support\LocaleTabs;
use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class Settings extends Page
{
    protected string $view = 'filament.pages.settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?int $navigationSort = 99;

    public ?array $data = [];

    public static function getNavigationLabel(): string
    {
        return __('filament.NavBar.settings');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.NavBar.group_site');
    }

    public function getTitle(): string
    {
        return __('filament.NavBar.settings');
    }

    public function mount(): void
    {
        $this->form->fill([
            'phone' => Setting::get('phone'),
            'email' => Setting::get('email'),
            'address' => Setting::get('address', []),
            'footer_text' => Setting::get('footer_text', []),
            'socials' => Setting::get('socials', []),
            'ga_id' => Setting::get('ga_id'),
            'map_embed' => Setting::get('map_embed'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->statePath('data')->components([
            Section::make(__('filament.sections.contact_info'))->description(__('filament.sections.contact_info_desc'))->schema([
                TextInput::make('phone')->label(__('filament.labels.phone'))->tel()->maxLength(32),
                TextInput::make('email')->label(__('filament.labels.email'))->email()->maxLength(190),
                LocaleTabs::make(fn (string $l) => [
                    TextInput::make("address.$l")->label(__('filament.labels.address'))->maxLength(255),
                    TextInput::make("footer_text.$l")->label(__('filament.labels.footer_text'))->maxLength(300),
                ]),
            ])->columns(2)->columnSpanFull(),
            Section::make(__('filament.sections.socials'))->description(__('filament.sections.socials_desc'))->schema([
                Repeater::make('socials')->label(__('filament.labels.socials'))->schema([
                    TextInput::make('platform')->label(__('filament.labels.platform'))->required()->maxLength(40),
                    TextInput::make('url')->label(__('filament.labels.url'))->url()->required(),
                ])->columns(2)->reorderable()->defaultItems(0),
            ])->columnSpanFull(),
            Section::make(__('filament.sections.integrations'))->description(__('filament.sections.integrations_desc'))->schema([
                TextInput::make('ga_id')->label(__('filament.labels.ga_id'))->placeholder('G-XXXXXXXXXX')->maxLength(40),
                TextInput::make('map_embed')->label(__('filament.labels.map_embed'))->url()
                    ->helperText(__('filament.labels.map_embed_help'))->maxLength(600),
            ])->columns(2)->columnSpanFull(),
        ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();

        foreach (['phone', 'email', 'address', 'footer_text', 'socials', 'ga_id', 'map_embed'] as $key) {
            Setting::put($key, $state[$key] ?? null);
        }

        Notification::make()->title(__('filament.actions.settings_saved'))->success()->send();
    }
}
