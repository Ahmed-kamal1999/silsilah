<section class="section" id="contact">
    <div class="container-x">
        <x-section-heading :eyebrow="__('site.contact.eyebrow')" :title="__('site.contact.title')" :sub="__('site.contact.subtitle')" />
        <div class="grid gap-8 lg:grid-cols-5">
            <div class="lg:col-span-2" data-reveal="start"><x-contact-info /></div>
            <div class="lg:col-span-3 card-gold !transform-none p-6 sm:p-8" data-reveal="end"><livewire:contact-form /></div>
        </div>
    </div>
</section>
