@props(['eyebrow' => null, 'title', 'sub' => null, 'align' => 'center'])
<div {{ $attributes->merge(['class' => 'mb-12 '.($align === 'center' ? 'text-center mx-auto max-w-2xl' : '')]) }} data-reveal>
    @if ($eyebrow)<p class="eyebrow mb-3">{{ $eyebrow }}</p>@endif
    <h2 class="text-3xl md:text-4xl">{{ $title }}</h2>
    <x-gold-divider class="mt-5 {{ $align === 'center' ? '' : 'justify-start' }}" />
    @if ($sub)<p class="mt-5 text-ink-400 leading-8">{{ $sub }}</p>@endif
</div>
