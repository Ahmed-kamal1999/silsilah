@props(['title', 'eyebrow' => null, 'sub' => null])
<section class="relative pt-36 pb-12 lg:pt-44 lg:pb-16">
    <div class="container-x text-center" data-reveal>
        @if ($eyebrow)<p class="eyebrow mb-3">{{ $eyebrow }}</p>@endif
        <h1 class="mx-auto max-w-3xl text-3xl sm:text-4xl lg:text-5xl">{{ $title }}</h1>
        <x-gold-divider class="mt-6" />
        @if ($sub)<p class="mx-auto mt-6 max-w-2xl leading-8 text-ink-400">{{ $sub }}</p>@endif
        {{ $slot }}
    </div>
</section>
