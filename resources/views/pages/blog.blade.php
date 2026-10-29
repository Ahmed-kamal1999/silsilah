<x-layouts.app :title="__('site.nav.blog')" :description="__('site.blog.title')">
    <x-page-hero :title="__('site.blog.title')" :eyebrow="__('site.blog.eyebrow')" />
    <section class="pb-8">
        <div class="container-x">
            <div class="mb-10 flex flex-col gap-4 md:flex-row md:items-center md:justify-between" data-reveal>
                <nav class="flex flex-wrap gap-2" aria-label="{{ __('site.nav.blog') }}">
                    @php $cur = request('category'); @endphp
                    <a href="{{ route('blog', array_filter(['q' => request('q')])) }}" class="rounded-full border px-4 py-1.5 text-sm {{ ! $cur ? 'border-gold-500 bg-gold-500 text-navy-950' : 'border-gold-700/50 text-gold-500 hover:border-gold-500' }}">{{ __('site.blog.all') }}</a>
                    @foreach ($categories as $c)
                        <a href="{{ route('blog', array_filter(['category' => $c->slug, 'q' => request('q')])) }}" class="rounded-full border px-4 py-1.5 text-sm {{ $cur === $c->slug ? 'border-gold-500 bg-gold-500 text-navy-950' : 'border-gold-700/50 text-gold-500 hover:border-gold-500' }}">{{ $c->name }}</a>
                    @endforeach
                </nav>
                <form method="GET" action="{{ route('blog') }}" role="search" class="flex gap-2">
                    @if ($cur)<input type="hidden" name="category" value="{{ $cur }}">@endif
                    <label for="blog-q" class="sr-only">{{ __('site.blog.search') }}</label>
                    <input id="blog-q" name="q" value="{{ request('q') }}" placeholder="{{ __('site.blog.search') }}" class="field !py-2 md:w-72">
                    <button class="btn btn-primary !py-2" type="submit">⌕</button>
                </form>
            </div>

            @if ($posts->isEmpty())
                <p class="py-16 text-center text-ink-400">{{ __('site.blog.empty') }}</p>
            @else
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($posts as $post)<x-post-card :post="$post" />@endforeach
                </div>
                <div class="mt-10">{{ $posts->links('partials.pagination') }}</div>
            @endif
        </div>
    </section>
</x-layouts.app>
