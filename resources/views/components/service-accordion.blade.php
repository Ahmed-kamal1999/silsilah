@props(['group'])
<div x-data="accordion" class="space-y-3" data-reveal>
    @foreach ($group->services as $i => $service)
        <div class="card-gold overflow-hidden" :class="active === {{ $i }} && '!border-gold-500'">
            <h3 class="!text-base !text-ink-50 !text-shadow-none" style="text-shadow:none">
                <button type="button" class="flex w-full items-center gap-4 p-4 sm:p-5 text-start"
                        @click="toggle({{ $i }})" :aria-expanded="active === {{ $i }}"
                        aria-controls="svc-{{ $group->id }}-{{ $i }}">
                    <span class="font-latin text-2xl font-bold text-gold-700 tabular-nums w-10 shrink-0" :class="active === {{ $i }} && '!text-gold-500'">{{ sprintf('%02d', $i + 1) }}</span>
                    <span class="flex-1 font-bold" :class="active === {{ $i }} && 'text-gold-500'">{{ $service->title }}</span>
                    <svg class="h-5 w-5 shrink-0 text-gold-500 transition-transform duration-300" :class="active === {{ $i }} && 'rotate-180'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 9l6 6 6-6"/></svg>
                </button>
            </h3>
            <div id="svc-{{ $group->id }}-{{ $i }}" x-show="active === {{ $i }}" x-collapse x-cloak>
                <div class="ps-[4.5rem] pe-5 pb-5 -mt-1">
                    <p class="text-ink-400 leading-8">{{ $service->excerpt }}</p>
                    <a href="{{ route('services.show', $service->slug) }}" class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-gold-500 hover:text-gold-300">
                        {{ __('site.cta.details') }}
                        <svg class="h-4 w-4 rtl:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>
