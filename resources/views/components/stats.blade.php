@props(['stats'])
<section class="section">
    <div class="container-x">
        <div class="frame-gold bg-navy-900/60 px-6 py-12 sm:px-12">
            <div class="grid grid-cols-2 gap-8 lg:grid-cols-4">
                @foreach ($stats as $stat)
                    <div class="text-center" data-reveal x-data="counter({{ (int) $stat->value }})">
                        <div class="font-latin text-4xl font-bold text-gold-500 sm:text-5xl" dir="ltr">
                            <span x-text="shown.toLocaleString('en')">{{ number_format($stat->value) }}</span><span>{{ $stat->suffix }}</span>
                        </div>
                        <p class="mt-2 text-ink-400">{{ $stat->label }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
