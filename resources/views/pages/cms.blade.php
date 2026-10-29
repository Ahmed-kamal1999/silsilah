<x-layouts.app :title="$page->title" :description="$page->seo['description'] ?? null">
    <x-page-hero :title="$page->title" />
    <article class="container-x max-w-3xl pb-16"><div class="prose-silsilah" data-reveal>{!! $page->body !!}</div></article>
</x-layouts.app>
