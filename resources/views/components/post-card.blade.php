@props(['post'])
@php $cover = $post->getFirstMediaUrl('cover', 'webp') ?: $post->getFirstMediaUrl('cover'); @endphp
<article class="card-gold flex flex-col overflow-hidden" data-reveal>
    <a href="{{ route('blog.show', $post->slug) }}" class="block aspect-[16/9] overflow-hidden bg-navy-800">
        @if ($cover)
            <img src="{{ $cover }}" alt="" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 hover:scale-105">
        @else
            <div class="flex h-full items-center justify-center"><x-logo :size="64" class="opacity-60" /></div>
        @endif
    </a>
    <div class="flex flex-1 flex-col p-5">
        <div class="mb-3 flex items-center gap-3 text-xs text-ink-400">
            <span class="rounded-full border border-gold-700/50 px-2.5 py-0.5 text-gold-500">{{ $post->category?->name ?? __('site.blog.uncategorized') }}</span>
            <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->locale(app()->getLocale())->translatedFormat('j F Y') }}</time>
        </div>
        <h3 class="!text-lg !text-ink-50" style="text-shadow:none"><a href="{{ route('blog.show', $post->slug) }}" class="hover:text-gold-300">{{ $post->title }}</a></h3>
        <p class="mt-2 flex-1 text-sm leading-7 text-ink-400">{{ $post->excerpt }}</p>
        <a href="{{ route('blog.show', $post->slug) }}" class="mt-4 text-sm font-semibold text-gold-500 hover:text-gold-300">{{ __('site.cta.read') }}</a>
    </div>
</article>
