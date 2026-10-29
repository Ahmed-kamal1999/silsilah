<?php

namespace App\Filament\Support;

/** Resolves nav/model labels from lang/{locale}/filament.php (NavBar.* and labels.*). */
trait ResourceLabels
{
    /** @return string key under NavBar.* (plural) — singular uses labels.singular_<key> */
    abstract protected static function labelKey(): string;

    public static function getNavigationLabel(): string
    {
        return __('filament.NavBar.'.static::labelKey());
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.NavBar.'.static::labelKey());
    }

    public static function getModelLabel(): string
    {
        return __('filament.labels.singular_'.static::labelKey());
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.NavBar.'.static::navGroup());
    }

    protected static function navGroup(): string
    {
        return 'group_content';
    }
}
