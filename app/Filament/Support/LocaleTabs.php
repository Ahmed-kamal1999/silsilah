<?php

namespace App\Filament\Support;

use Closure;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

class LocaleTabs
{
    public const LOCALES = ['ar', 'en'];

    /**
     * One tab per locale. $fields receives the locale and returns components named "<field>.<locale>".
     */
    public static function make(Closure $fields): Tabs
    {
        return Tabs::make('locales')
            ->tabs(array_map(
                fn (string $locale) => Tab::make(__('filament.labels.'.($locale === 'ar' ? 'arabic' : 'english')))
                    ->schema($fields($locale)),
                self::LOCALES,
            ))
            ->columnSpanFull();
    }
}
