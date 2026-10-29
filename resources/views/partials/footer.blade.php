@php
    use App\Models\Setting;
    $socials = Setting::get('socials', []);
    $footerText = Setting::get('footer_text', null, true) ?: __('site.footer.about');
@endphp
<footer class="mt-20 border-t border-gold-700/40 bg-navy-950/80">
    <div class="container-x py-14 grid gap-10 md:grid-cols-4">
        <div class="md:col-span-2">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                <x-logo :size="52" />
                <span class="font-bold text-gold-500">{{ __('site.brand.name') }}</span>
            </a>
            <p class="mt-4 max-w-md text-ink-400 leading-8">{{ $footerText }}</p>
            @if (! empty($socials))
                <ul class="mt-5 flex gap-3">
                    @foreach ($socials as $s)
                        <li><a href="{{ $s['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex h-10 min-w-10 items-center justify-center rounded-full border border-gold-700/60 px-3 text-xs font-semibold text-gold-500 hover:border-gold-500 hover:text-gold-300">{{ $s['platform'] ?? '' }}</a></li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div>
            <h3 class="text-base mb-4">{{ __('site.footer.links') }}</h3>
            <ul class="space-y-2 text-ink-400">
                <li><a class="hover:text-gold-300" href="{{ route('home') }}">{{ __('site.nav.home') }}</a></li>
                <li><a class="hover:text-gold-300" href="{{ route('about') }}">{{ __('site.nav.about') }}</a></li>
                <li><a class="hover:text-gold-300" href="{{ route('blog') }}">{{ __('site.nav.blog') }}</a></li>
                <li><a class="hover:text-gold-300" href="{{ route('contact') }}">{{ __('site.nav.contact') }}</a></li>
            </ul>
        </div>
        <div>
            <h3 class="text-base mb-4">{{ __('site.footer.legal') }}</h3>
            <ul class="space-y-2 text-ink-400">
                <li><a class="hover:text-gold-300" href="{{ route('privacy') }}">{{ __('site.footer.privacy') }}</a></li>
                <li><a class="hover:text-gold-300" href="{{ route('terms') }}">{{ __('site.footer.terms') }}</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-gold-700/25 py-5 text-center text-sm text-ink-400">
        © {{ date('Y') }} {{ __('site.brand.name') }}. {{ __('site.footer.rights') }}
    </div>
</footer>
