@php $a = __('site.about_page'); @endphp
<x-layouts.app :title="$page->title" :description="$page->seo['description'] ?? __('site.about_teaser')">
    <x-page-hero :title="$page->title" :eyebrow="__('site.about.eyebrow')" />
    @if ($page->body)
        <section class="pb-8"><div class="container-x max-w-3xl prose-silsilah" data-reveal>{!! $page->body !!}</div></section>
    @endif

    <section class="section">
        <div class="container-x grid gap-6 md:grid-cols-2">
            @foreach (['vision', 'mission'] as $k)
                <div class="card-gold p-8" data-reveal>
                    <h2 class="text-2xl">{{ __('site.about.'.$k) }}</h2>
                    <x-gold-divider class="my-4 justify-start" />
                    <p class="leading-9">{{ $a[$k] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section class="section">
        <div class="container-x">
            <x-section-heading :title="__('site.about.values')" />
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($a['values'] as $v)
                    <div class="card-gold p-6 text-center" data-reveal>
                        <h3 class="text-xl">{{ $v['title'] }}</h3>
                        <p class="mt-3 text-sm leading-7 text-ink-400">{{ $v['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container-x max-w-3xl">
            <x-section-heading :title="__('site.about.timeline')" />
            <ol class="relative ms-3 border-s border-gold-700/60">
                @foreach ($a['timeline'] as $t)
                    <li class="mb-10 ms-8" data-reveal>
                        <span class="absolute -start-[7px] mt-2 h-3 w-3 rotate-45 bg-gold-500"></span>
                        <span class="font-latin text-2xl font-bold text-gold-500">{{ $t['year'] }}</span>
                        <p class="mt-1 leading-8 text-ink-50/90">{{ $t['text'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    <section class="section">
        <div class="container-x">
            <div class="frame-gold bg-navy-900/60 p-8 sm:p-12" data-reveal>
                <h2 class="text-2xl md:text-3xl">{{ $a['v2030_title'] }}</h2>
                <x-gold-divider class="my-5 justify-start" />
                <p class="max-w-3xl leading-9">{{ $a['v2030'] }}</p>
            </div>
        </div>
    </section>

    <x-stats :stats="$stats" />
    <x-cta-band />
</x-layouts.app>
