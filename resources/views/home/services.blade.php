<div id="solutions">
@foreach ($groups as $group)
    <section class="section {{ $loop->even ? 'bg-navy-950/40' : '' }}">
        <div class="container-x">
            <div class="mb-10 flex flex-wrap items-end justify-between gap-6" data-reveal>
                <div class="max-w-2xl">
                    <p class="eyebrow mb-3">{{ __('site.groups.eyebrow') }} · {{ sprintf('%02d', $loop->iteration) }}</p>
                    <h2 class="text-3xl font-extrabold md:text-4xl">{{ $group->name }}</h2>
                    <x-gold-divider class="mt-5 justify-start" />
                    <p class="mt-6 leading-8 text-ink-400">{{ $group->description ?: __('site.groups_intro.'.$group->slug) }}</p>
                </div>
                <a href="{{ route('solutions', $group->slug) }}" class="btn btn-outline">{{ __('site.groups.view_all') }}</a>
            </div>
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($group->services as $i => $service)
                    <a href="{{ route('services.show', $service->slug) }}" class="card-gold group flex flex-col p-6" data-reveal>
                        <span class="font-latin text-3xl font-bold text-gold-700 tabular-nums transition-colors group-hover:text-gold-500">{{ sprintf('%02d', $i + 1) }}</span>
                        <h3 class="mt-4 text-lg font-bold !text-ink-50" style="text-shadow:none">{{ $service->title }}</h3>
                        <p class="mt-3 flex-1 text-sm leading-7 text-ink-400">{{ \Illuminate\Support\Str::limit($service->excerpt, 140) }}</p>
                        <span class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-gold-500 group-hover:text-gold-300">
                            {{ __('site.cta.details') }}
                            <svg class="h-4 w-4 transition-transform group-hover:translate-x-1 rtl:rotate-180 rtl:group-hover:-translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endforeach
</div>
