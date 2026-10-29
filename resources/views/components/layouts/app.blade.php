@props(['title' => null, 'description' => null, 'image' => null, 'type' => 'website', 'schema' => null, 'noindex' => false])
@php
    use App\Models\Setting;
    use App\Support\Locale;

    $locale = app()->getLocale();
    $brand = __('site.brand.name');
    $fullTitle = $title ? $title.' | '.__('site.brand.short') : $brand.' — '.__('site.brand.tagline');
    $description = $description ?: __('site.brand.tagline');
    $canonical = url()->current();
    $image = $image ?: asset('images/og-default.svg');
    $analytics = Setting::get('ga_id');
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ Locale::dir() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>try{var t=localStorage.getItem('theme');if(t)document.documentElement.dataset.theme=t}catch(e){}</script>
    <title>{{ $fullTitle }}</title>
    <meta name="description" content="{{ Str::limit(strip_tags($description), 160) }}">
    <meta name="theme-color" content="#0A1640">
    <link rel="canonical" href="{{ $canonical }}">
    @if ($noindex)<meta name="robots" content="noindex, nofollow">@endif
    @if (Locale::hasAlternates() && ! $noindex)
        <link rel="alternate" hreflang="ar" href="{{ Locale::urlFor('ar') }}">
        <link rel="alternate" hreflang="en" href="{{ Locale::urlFor('en') }}">
        <link rel="alternate" hreflang="x-default" href="{{ Locale::urlFor('ar') }}">
    @endif

    <meta property="og:site_name" content="{{ $brand }}">
    <meta property="og:type" content="{{ $type }}">
    <meta property="og:title" content="{{ $title ?: $brand }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($description), 200) }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $image }}">
    <meta property="og:locale" content="{{ $locale === 'ar' ? 'ar_SA' : 'en_US' }}">
    <meta property="og:locale:alternate" content="{{ $locale === 'ar' ? 'en_US' : 'ar_SA' }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?: $brand }}">
    <meta name="twitter:description" content="{{ Str::limit(strip_tags($description), 200) }}">
    <meta name="twitter:image" content="{{ $image }}">

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $brand,
        'url' => url('/'.$locale),
        'logo' => asset('favicon.svg'),
        'email' => Setting::get('email'),
        'telephone' => Setting::get('phone'),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
    @if ($schema)
        <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
    @endif
    @if ($analytics)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $analytics }}"></script>
        <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag('js',new Date());gtag('config','{{ $analytics }}');</script>
    @endif
</head>
<body class="bg-pinstripe min-h-screen flex flex-col antialiased">
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:start-2 focus:z-[100] btn btn-primary">{{ __('site.nav.skip') }}</a>
    @include('partials.header')
    <main id="main" class="flex-1">{{ $slot }}</main>
    @include('partials.footer')
    @livewireScripts
</body>
</html>
