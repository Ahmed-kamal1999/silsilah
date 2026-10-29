<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public const LOCALES = ['ar', 'en'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale');
        abort_unless(in_array($locale, self::LOCALES, true), 404);

        app()->setLocale($locale);
        URL::defaults(['locale' => $locale]);
        // Locale is consumed here so controllers don't receive it as an argument.
        $request->route()->forgetParameter('locale');

        return $next($request);
    }
}
