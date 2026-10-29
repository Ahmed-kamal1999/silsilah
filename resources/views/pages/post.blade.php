@php
    $cover = $post->getFirstMediaUrl('cover', 'webp') ?: $post->getFirstMediaUrl('cover');
    $url = url()->current();
    $date = $post->published_at->locale(app()->getLocale())->translatedFormat('j F Y');
    $schema = [
        '@context' => 'https://schema.org', '@type' => 'Article',
        'headline' => $post->title, 'description' => $post->excerpt,
        'datePublished' => $post->published_at->toIso8601String(), 'dateModified' => $post->updated_at->toIso8601String(),
        'inLanguage' => app()->getLocale(), 'mainEntityOfPage' => $url,
        'author' => ['@type' => 'Person', 'name' => $post->author?->name ?? __('site.brand.short')],
        'publisher' => ['@type' => 'Organization', 'name' => __('site.brand.name'), 'logo' => ['@type' => 'ImageObject', 'url' => asset('favicon.svg')]],
    ];
    if ($cover) { $schema['image'] = $cover; }
    $share = [
        ['X', 'https://twitter.com/intent/tweet?url='.urlencode($url).'&text='.urlencode($post->title)],
        ['LinkedIn', 'https://www.linkedin.com/sharing/share-offsite/?url='.urlencode($url)],
        ['WhatsApp', 'https://wa.me/?text='.urlencode($post->title.' '.$url)],
    ];
@endphp
<x-layouts.app :title="$post->seo['title'] ?? $post->title" :description="($post->seo['description'] ?? null) ?: $post->excerpt" :image="$cover ?: null" type="article" :schema="$schema">
    <x-page-hero :title="$post->title" :eyebrow="$post->category?->name ?? __('site.blog.uncategorized')">
        <p class="mt-6 text-sm text-ink-400">
            @if ($post->author){{ __('site.blog.by') }} {{ $post->author->name }} · @endif
            <time datetime="{{ $post->published_at->toDateString() }}">{{ $date }}</time> · {{ __('site.blog.reading_time', ['min' => $post->readingTime()]) }}
        </p>
    </x-page-hero>
    <article class="container-x max-w-3xl pb-12">
        @if ($cover)<img src="{{ $cover }}" alt="" class="frame-gold mb-10 w-full object-cover" data-reveal>@endif
        <div class="prose-silsilah" data-reveal>{!! $post->body !!}</div>
        <div class="mt-10 flex flex-wrap items-center gap-3 border-t border-gold-700/30 pt-6">
            <span class="text-sm text-ink-400">{{ __('site.blog.share') }}</span>
            @foreach ($share as [$label, $href])
                <a href="{{ $href }}" target="_blank" rel="noopener noreferrer" class="rounded-full border border-gold-700/60 px-4 py-1.5 text-sm text-gold-500 hover:border-gold-500">{{ $label }}</a>
            @endforeach
        </div>
    </article>
    @if ($related->isNotEmpty())
    <section class="section">
        <div class="container-x">
            <x-section-heading :title="__('site.blog.related')" />
            <div class="grid gap-6 md:grid-cols-3">@foreach ($related as $r)<x-post-card :post="$r" />@endforeach</div>
        </div>
    </section>
    @endif
</x-layouts.app>
