<section class="relative overflow-hidden pt-36 pb-20 lg:pt-44 lg:pb-28"
         x-data="heroSlider({{ 1 + count($groups ?? []) }})" @mouseenter="paused = true" @mouseleave="paused = false"
         @focusin="paused = true" @focusout="paused = false" aria-roledescription="carousel">
    <div class="container-x grid items-center gap-12 lg:grid-cols-2">
        <div>
            <div class="grid">
                <div class="col-start-1 row-start-1 transition-all duration-700" :class="i === 0 ? 'opacity-100 translate-y-0' : 'pointer-events-none opacity-0 translate-y-4'" :aria-hidden="i !== 0">
                    <p class="eyebrow mb-4 font-latin !tracking-[0.22em] uppercase">{{ __('site.brand.line') }}</p>
                    <h1 class="text-4xl font-extrabold sm:text-5xl lg:text-6xl">{{ __('site.hero.title') }}</h1>
                    <p class="mt-6 max-w-xl text-lg leading-9 text-ink-50/90">{{ __('site.brand.tagline') }}</p>
                    <p class="mt-3 max-w-xl text-ink-400 leading-8">{{ __('site.hero.sub') }}</p>
                    <div class="mt-9 flex flex-wrap gap-4">
                        <a href="{{ route('contact') }}" class="btn btn-primary">{{ __('site.hero.cta_primary') }}</a>
                        <a href="#solutions" class="btn btn-outline">{{ __('site.hero.cta_secondary') }}</a>
                    </div>
                </div>
                @foreach (($groups ?? []) as $g)
                    <div class="col-start-1 row-start-1 transition-all duration-700" :class="i === {{ $loop->iteration }} ? 'opacity-100 translate-y-0' : 'pointer-events-none opacity-0 translate-y-4'" :aria-hidden="i !== {{ $loop->iteration }}">
                        <p class="eyebrow mb-4">{{ __('site.groups.eyebrow') }} · {{ sprintf('%02d', $loop->iteration) }}</p>
                        <h2 class="text-4xl font-extrabold sm:text-5xl lg:text-6xl">{{ $g->name }}</h2>
                        <p class="mt-6 max-w-xl text-lg leading-9 text-ink-50/90">{{ $g->description ?: __('site.groups_intro.'.$g->slug) }}</p>
                        <div class="mt-9 flex flex-wrap gap-4">
                            <a href="{{ route('solutions', $g->slug) }}" class="btn btn-primary" :tabindex="i === {{ $loop->iteration }} ? 0 : -1">{{ __('site.groups.view_all') }}</a>
                            <a href="{{ route('contact') }}" class="btn btn-outline" :tabindex="i === {{ $loop->iteration }} ? 0 : -1">{{ __('site.cta.contact') }}</a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-10 flex items-center gap-2" role="tablist">
                @for ($n = 0; $n <= count($groups ?? []); $n++)
                    <button type="button" role="tab" @click="go({{ $n }})" :aria-selected="i === {{ $n }}" aria-label="{{ $n + 1 }}"
                            class="h-2 rounded-full transition-all duration-300" :class="i === {{ $n }} ? 'w-8 bg-gold-500' : 'w-2 bg-gold-700/50 hover:bg-gold-500'"></button>
                @endfor
            </div>
        </div>
        <div data-reveal="end" class="mx-auto w-full max-w-md lg:max-w-none"><x-hero-art /></div>
    </div>
</section>
