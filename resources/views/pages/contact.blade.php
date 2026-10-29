<x-layouts.app :title="__('site.nav.contact')" :description="__('site.contact.subtitle')">
    <x-page-hero :title="__('site.contact.title')" :eyebrow="__('site.contact.eyebrow')" :sub="__('site.contact.subtitle')" />
    <section class="pb-12">
        <div class="container-x grid gap-8 lg:grid-cols-5">
            <div class="lg:col-span-2" data-reveal="start"><x-contact-info /></div>
            <div class="lg:col-span-3 card-gold !transform-none p-6 sm:p-8" data-reveal="end"><livewire:contact-form /></div>
        </div>
        @php $map = \App\Models\Setting::get('map_embed'); @endphp
        @if ($map && str_starts_with($map, 'https://www.google.com/maps/embed'))
            <div class="container-x mt-10"><iframe src="{{ $map }}" title="Map" loading="lazy" class="frame-gold h-80 w-full" referrerpolicy="no-referrer-when-downgrade"></iframe></div>
        @endif
    </section>
</x-layouts.app>
