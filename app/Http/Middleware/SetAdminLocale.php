<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetAdminLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->cookie('admin_locale');
        app()->setLocale(in_array($locale, SetLocale::LOCALES, true) ? $locale : 'ar');

        return $next($request);
    }
}
