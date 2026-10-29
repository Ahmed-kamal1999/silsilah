@if ($posts->isNotEmpty())
<section class="section">
    <div class="container-x">
        <x-section-heading :eyebrow="__('site.blog.eyebrow')" :title="__('site.blog.title')" />
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($posts as $post)<x-post-card :post="$post" />@endforeach
        </div>
        <div class="mt-10 text-center"><a href="{{ route('blog') }}" class="btn btn-outline">{{ __('site.cta.all_posts') }}</a></div>
    </div>
</section>
@endif
