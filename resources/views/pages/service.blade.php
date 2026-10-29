@php
    $image = $service->getFirstMediaUrl('image', 'webp') ?: $service->getFirstMediaUrl('image');
    $deliverables = collect($service->deliverables ? preg_split('/\R/u', (string) $service->deliverables, -1, PREG_SPLIT_NO_EMPTY) : []);
    $steps = collect($service->methodology ? preg_split('/\R/u', (string) $service->methodology, -1, PREG_SPLIT_NO_EMPTY) : []);
@endphp
<x-layouts.app :title="$service->seo['title'] ?? $service->title" :description="($service->seo['description'] ?? null) ?: $service->excerpt" :image="$image ?: null"
    :schema="['@context' => 'https://schema.org', '@type' => 'Service', 'name' => $service->title, 'description' => $service->excerpt, 'provider' => ['@type' => 'Organization', 'name' => __('site.brand.name')]]">
    <x-page-hero :title="$service->title" :eyebrow="$service->category->name" :sub="$service->excerpt">
        <a href="{{ route('solutions', $service->category->slug) }}" class="mt-6 inline-block text-sm text-gold-500 hover:text-gold-300">← {{ __('site.service.back') }}</a>
    </x-page-hero>

    <section class="section !pt-4">
        <div class="container-x grid gap-10 lg:grid-cols-3">
            <div class="lg:col-span-2" data-reveal>
                <h2 class="text-2xl">{{ __('site.service.overview') }}</h2>
                <x-gold-divider class="my-4 justify-start" />
                <div class="prose-silsilah">{!! $service->body !!}</div>
            </div>
            <aside class="space-y-6" data-reveal="end">
                @if ($image)<img src="{{ $image }}" alt="" class="frame-gold w-full object-cover" loading="lazy">@endif
                @if ($deliverables->isNotEmpty())
                    <div class="card-gold !transform-none p-6">
                        <h2 class="text-xl">{{ __('site.service.deliverables') }}</h2>
                        <ul class="mt-4 space-y-3">
                            @foreach ($deliverables as $d)
                                <li class="flex gap-3 leading-7"><span class="mt-2 h-2 w-2 shrink-0 rotate-45 bg-gold-500"></span>{{ $d }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </aside>
        </div>
    </section>

    @if ($steps->isNotEmpty())
    <section class="section">
        <div class="container-x">
            <x-section-heading :title="__('site.service.methodology')" />
            <ol class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($steps as $step)
                    <li class="card-gold p-6" data-reveal>
                        <span class="font-latin text-3xl font-bold text-gold-500">{{ sprintf('%02d', $loop->iteration) }}</span>
                        <p class="mt-3 leading-8">{{ $step }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>
    @endif

    @if ($related->isNotEmpty())
    <section class="section">
        <div class="container-x">
            <x-section-heading :title="__('site.service.related')" />
            <div class="grid gap-6 md:grid-cols-3">
                @foreach ($related as $r)
                    <a href="{{ route('services.show', $r->slug) }}" class="card-gold block p-6" data-reveal>
                        <h3 class="!text-lg">{{ $r->title }}</h3>
                        <p class="mt-3 text-sm leading-7 text-ink-400">{{ Str::limit($r->excerpt, 120) }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    <x-cta-band />
</x-layouts.app>
