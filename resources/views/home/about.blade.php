<section class="section relative">
    <div class="container-x">
        <div class="frame-gold relative overflow-hidden bg-navy-900/60 p-8 sm:p-12 lg:p-16" data-reveal>
            <svg class="pointer-events-none absolute -bottom-10 -end-10 h-64 w-64 opacity-[0.12]" viewBox="0 0 100 100" fill="none" stroke="#E9C46A" aria-hidden="true">
                @for ($r = 10; $r <= 50; $r += 10)<circle cx="50" cy="50" r="{{ $r }}"/>@endfor
            </svg>
            <div class="relative max-w-3xl">
                <p class="eyebrow mb-3">{{ __('site.about.eyebrow') }}</p>
                <h2 class="text-3xl md:text-4xl">{{ __('site.about.title') }}</h2>
                <x-gold-divider class="mt-5 justify-start" />
                <p class="mt-6 leading-9 text-ink-50/90">{{ __('site.about_teaser') }}</p>
                <a href="{{ route('about') }}" class="btn btn-outline mt-8">{{ __('site.about.more') }}</a>
            </div>
        </div>
    </div>
</section>
