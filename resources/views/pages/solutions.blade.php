<x-layouts.app :title="$category->name" :description="$category->description ?: __('site.groups_intro.'.$category->slug)">
    <x-page-hero :title="$category->name" :eyebrow="__('site.groups.eyebrow')" :sub="$category->description ?: __('site.groups_intro.'.$category->slug)" />
    <section class="pb-8">
        <div class="container-x grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($services as $s)
                <a href="{{ route('services.show', $s->slug) }}" class="card-gold group block p-6" data-reveal>
                    <span class="font-latin text-3xl font-bold text-gold-700 group-hover:text-gold-500">{{ sprintf('%02d', $loop->iteration) }}</span>
                    <h2 class="mt-3 !text-lg">{{ $s->title }}</h2>
                    <p class="mt-3 text-sm leading-7 text-ink-400">{{ $s->excerpt }}</p>
                    <span class="mt-4 inline-block text-sm font-semibold text-gold-500">{{ __('site.cta.details') }} ←</span>
                </a>
            @endforeach
        </div>
    </section>
    <x-cta-band />
</x-layouts.app>
