<x-layouts.app :description="__('site.hero.sub')">
    @include('home.hero')
    @include('home.about')
    @include('home.services')
    @include('home.why')
    <x-stats :stats="$stats" />
    <x-logo-marquee :items="$partners" :title="__('site.partners.title')" />
    <x-logo-marquee :items="$clients" :title="__('site.clients.title')" />
    @include('home.blog')
    @include('home.contact')
</x-layouts.app>
