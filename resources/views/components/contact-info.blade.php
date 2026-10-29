@php
    use App\Models\Setting;
    $socials = Setting::get('socials', []);
    $rows = array_filter([
        [__('site.contact.address'), Setting::get('address', null, true), null],
        [__('site.contact.email'), Setting::get('email'), 'mailto:'.Setting::get('email')],
        [__('site.contact.phone'), Setting::get('phone'), 'tel:'.preg_replace('/\s+/', '', (string) Setting::get('phone'))],
    ], fn ($r) => $r[1]);
@endphp
<div class="space-y-6">
    @foreach ($rows as [$label, $value, $href])
        <div class="card-gold p-5">
            <p class="eyebrow mb-1">{{ $label }}</p>
            @if ($href)<a href="{{ $href }}" class="font-semibold hover:text-gold-300" dir="auto">{{ $value }}</a>
            @else<p class="font-semibold">{{ $value }}</p>@endif
        </div>
    @endforeach
    @if (! empty($socials))
        <div>
            <p class="eyebrow mb-3">{{ __('site.contact.follow') }}</p>
            <ul class="flex flex-wrap gap-3">
                @foreach ($socials as $s)
                    <li><a href="{{ $s['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer" class="inline-flex min-w-10 items-center justify-center rounded-full border border-gold-700/60 px-3 py-2 text-xs font-semibold text-gold-500 hover:border-gold-500">{{ $s['platform'] ?? '' }}</a></li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
