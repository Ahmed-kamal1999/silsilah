@php
    use App\Models\ServiceCategory;
    use App\Support\Locale;
    $cats = ServiceCategory::active()->with(['services' => fn ($q) => $q->active()])->get()->keyBy('slug');
    $links = [
        ['home', __('site.nav.home'), route('home')],
        ['about', __('site.nav.about'), route('about')],
    ];
    foreach (['financial-protection' => 'protection', 'fintech-development' => 'development'] as $slug => $key) {
        if ($cats->has($slug)) {
            $links[] = ['solutions', __('site.nav.'.$key), route('solutions', $slug), $slug, $cats[$slug]->services];
        }
    }
    $links[] = ['blog*', __('site.nav.blog'), route('blog')];
    $isActive = fn ($l) => isset($l[3]) ? (request()->route('category')?->slug ?? null) === $l[3] : request()->routeIs($l[0]);
@endphp
<header x-data="header" class="fixed inset-x-0 top-0 z-50 transition-colors duration-300"
        :class="solid || open ? 'bg-navy-950/95 backdrop-blur border-b border-gold-700/40' : 'bg-transparent'">
    <div class="container-x flex h-20 items-center justify-between gap-4">
        <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0" aria-label="{{ __('site.brand.name') }}">
            <x-logo :size="46" />
            <span class="hidden sm:block leading-tight">
                <span class="block font-bold text-gold-500 text-sm">{{ __('site.brand.name') }}</span>
                <span class="block text-[10px] tracking-[0.2em] text-ink-400 uppercase font-latin">Silsilah FinTech</span>
            </span>
        </a>

        <nav class="hidden xl:flex items-center gap-1" aria-label="Main">
            @foreach ($links as $l)
                @if (! empty($l[4]) && $l[4]->isNotEmpty())
                    <div class="relative" x-data="{ sub: false }" @mouseenter="sub = true" @mouseleave="sub = false" @keydown.escape="sub = false">
                        <a href="{{ $l[2] }}" @if($isActive($l)) aria-current="page" @endif :aria-expanded="sub" aria-haspopup="true"
                           class="inline-flex items-center gap-1 px-3 py-2 rounded-md text-sm font-medium transition-colors hover:text-gold-300 {{ $isActive($l) ? 'text-gold-500' : 'text-ink-50' }}">
                            {{ $l[1] }}
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
                        </a>
                        <div x-show="sub" x-cloak x-transition.opacity class="absolute start-0 top-full w-72 pt-2">
                            <ul class="rounded-xl border border-gold-700/50 bg-navy-900 p-2 shadow-2xl">
                                @foreach ($l[4] as $svc)
                                    <li><a href="{{ route('services.show', $svc->slug) }}" class="block rounded-lg px-3 py-2 text-sm text-ink-50 hover:bg-gold-500/10 hover:text-gold-300">{{ $svc->title }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @else
                    <a href="{{ $l[2] }}" @if($isActive($l)) aria-current="page" @endif
                       class="px-3 py-2 rounded-md text-sm font-medium transition-colors hover:text-gold-300 {{ $isActive($l) ? 'text-gold-500' : 'text-ink-50' }}">{{ $l[1] }}</a>
                @endif
            @endforeach
        </nav>

        <div class="flex items-center gap-3">
            <a href="{{ App\Support\Locale::urlFor(Locale::other()) }}" hreflang="{{ Locale::other() }}" lang="{{ Locale::other() }}"
               class="rounded-md border border-gold-700/60 px-3 py-1.5 text-sm font-semibold text-gold-500 hover:border-gold-500 hover:text-gold-300"
               aria-label="{{ __('site.nav.switch_label') }}">{{ __('site.nav.switch_to') }}</a>
            <button type="button" x-data="themeToggle" @click="toggle()" class="rounded-md border border-gold-700/60 p-2 text-gold-500 hover:border-gold-500 hover:text-gold-300" aria-label="{{ __('site.nav.theme') }}">
                <svg x-show="dark" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
                <svg x-show="!dark" x-cloak width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.8A9 9 0 1111.2 3a7 7 0 009.8 9.8z"/></svg>
            </button>
            <a href="{{ route('contact') }}" class="btn btn-primary hidden md:inline-flex !py-2 text-sm">{{ __('site.cta.contact') }}</a>
            <button type="button" class="xl:hidden p-2 text-gold-500" @click="open = !open" :aria-expanded="open" aria-controls="mobile-nav" aria-label="{{ __('site.nav.menu') }}">
                <svg x-show="!open" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                <svg x-show="open" x-cloak width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg>
            </button>
        </div>
    </div>

    <nav id="mobile-nav" x-show="open" x-cloak x-transition.opacity class="xl:hidden border-t border-gold-700/30 bg-navy-950/98" aria-label="Mobile">
        <div class="container-x py-4 flex flex-col">
            @foreach ($links as $l)
                <a href="{{ $l[2] }}" class="py-3 border-b border-gold-700/15 {{ $isActive($l) ? 'text-gold-500' : 'text-ink-50' }}">{{ $l[1] }}</a>
                @foreach (($l[4] ?? []) as $svc)
                    <a href="{{ route('services.show', $svc->slug) }}" class="py-2 ps-5 text-sm text-ink-400 border-b border-gold-700/10 hover:text-gold-300">{{ $svc->title }}</a>
                @endforeach
            @endforeach
            <a href="{{ route('contact') }}" class="btn btn-primary mt-4">{{ __('site.cta.contact') }}</a>
        </div>
    </nav>
</header>
<style>[x-cloak]{display:none!important}</style>
