@props(['title' => null, 'text' => null])
<section class="section">
    <div class="container-x">
        <div class="frame-gold bg-navy-900/70 px-6 py-12 text-center sm:px-12" data-reveal>
            <h2 class="text-2xl md:text-3xl">{{ $title ?? __('site.service.cta_title') }}</h2>
            <p class="mx-auto mt-4 max-w-xl text-ink-400">{{ $text ?? __('site.service.cta_text') }}</p>
            <a href="{{ route('contact') }}" class="btn btn-primary mt-8">{{ __('site.cta.contact') }}</a>
        </div>
    </div>
</section>
