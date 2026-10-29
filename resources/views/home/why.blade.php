<section class="section">
    <div class="container-x">
        <x-section-heading :eyebrow="__('site.why.eyebrow')" :title="__('site.why.title')" />
        <div class="space-y-16 lg:space-y-24">
            @foreach (__('site.why_items') as $i => $item)
                <div class="grid items-center gap-10 lg:grid-cols-2">
                    <div class="{{ $i % 2 ? 'lg:order-2' : '' }}" data-reveal="{{ $i % 2 ? 'end' : 'start' }}">
                        <div class="mx-auto max-w-md"><x-illustration :type="$item['icon']" /></div>
                    </div>
                    <div data-reveal="{{ $i % 2 ? 'start' : 'end' }}">
                        <span class="font-latin text-5xl font-bold text-gold-700/60">{{ sprintf('%02d', $i + 1) }}</span>
                        <h3 class="mt-2 text-2xl md:text-3xl">{{ $item['title'] }}</h3>
                        <x-gold-divider class="mt-4 justify-start" />
                        <p class="mt-5 leading-9 text-ink-50/90">{{ $item['text'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
