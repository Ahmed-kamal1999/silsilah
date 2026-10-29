@props(['items', 'title'])
@if ($items->isNotEmpty())
<section class="py-10">
    <div class="container-x"><h2 class="mb-6 text-center text-xl" data-reveal>{{ $title }}</h2></div>
    <div class="marquee" role="list" aria-label="{{ $title }}">
        <div class="marquee-track">
            @foreach ([1, 2] as $copy)
                @foreach ($items as $item)
                    @php $logo = $item->getFirstMediaUrl('logo', 'webp') ?: $item->getFirstMediaUrl('logo'); @endphp
                    <a @if($copy === 1) role="listitem" @else aria-hidden="true" tabindex="-1" @endif
                       @if($item->url) href="{{ $item->url }}" target="_blank" rel="noopener noreferrer" @endif
                       class="logo-chip mx-3 flex h-20 w-44 shrink-0 items-center justify-center rounded-xl border border-gold-700/30 bg-navy-900 px-5">
                        @if ($logo)
                            <img src="{{ $logo }}" alt="{{ $copy === 1 ? $item->name : '' }}" loading="lazy" class="max-h-12 w-auto object-contain">
                        @else
                            <span class="text-sm font-semibold text-ink-400">{{ $item->name }}</span>
                        @endif
                    </a>
                @endforeach
            @endforeach
        </div>
    </div>
</section>
@endif
