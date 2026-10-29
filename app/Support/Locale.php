<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

class Locale
{
    public static function other(): string
    {
        return app()->getLocale() === 'ar' ? 'en' : 'ar';
    }

    public static function dir(?string $locale = null): string
    {
        return ($locale ?? app()->getLocale()) === 'ar' ? 'rtl' : 'ltr';
    }

    /** URL of the current page in another locale (falls back to that locale's home). */
    public static function urlFor(string $locale): string
    {
        $route = request()->route();
        if ($route && $route->getName() && $route->getName() !== 'admin.locale' && Route::has($route->getName())) {
            $params = array_merge($route->parameters(), ['locale' => $locale]);
            $query = request()->query();

            return route($route->getName(), $params).($query ? '?'.http_build_query($query) : '');
        }

        return url('/'.$locale);
    }

    /** Whether the current request is a localized public page that can emit hreflang. */
    public static function hasAlternates(): bool
    {
        return str_contains((string) request()->route()?->uri(), '{locale}');
    }
}
