@props(['code', 'title', 'text'])
@php
    $seg = request()->segment(1);
    app()->setLocale(in_array($seg, ['ar', 'en'], true) ? $seg : 'ar');
    $loc = app()->getLocale();
@endphp
<!DOCTYPE html>
<html lang="{{ $loc }}" dir="{{ $loc === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ $code }} | {{ __('site.brand.short') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;700&family=Inter:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-pinstripe min-h-screen grid place-items-center px-5 text-center">
    <div>
        <x-logo :size="72" class="mx-auto" />
        <p class="mt-6 font-latin text-8xl font-bold text-gold-500" style="text-shadow:0 4px 20px rgb(0 0 0/.4)">{{ $code }}</p>
        <x-gold-divider class="my-5" />
        <h1 class="text-2xl">{{ $title }}</h1>
        <p class="mx-auto mt-3 max-w-md text-ink-400">{{ $text }}</p>
        <a href="{{ url('/'.$loc) }}" class="btn btn-primary mt-8">{{ __('site.errors.home') }}</a>
    </div>
</body>
</html>
