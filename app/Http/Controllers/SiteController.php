<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Stat;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SiteController extends Controller
{
    public function home()
    {
        return view('pages.home', [
            'groups' => ServiceCategory::active()->with(['services' => fn ($q) => $q->active()])->get(),
            'stats' => Stat::orderBy('sort')->get(),
            'partners' => Partner::active()->with('media')->get(),
            'clients' => Client::active()->with('media')->get(),
            'posts' => Post::published()->with(['category', 'media'])->take(3)->get(),
            'about' => Page::where('slug', 'about')->first(),
        ]);
    }

    public function about()
    {
        return view('pages.about', [
            'page' => Page::where('slug', 'about')->firstOrFail(),
            'stats' => Stat::orderBy('sort')->get(),
        ]);
    }

    public function solutions(ServiceCategory $category)
    {
        abort_unless($category->is_active, 404);

        return view('pages.solutions', [
            'category' => $category,
            'services' => $category->services()->where('is_active', true)->with('media')->get(),
        ]);
    }

    public function service(Service $service)
    {
        abort_unless($service->is_active, 404);

        return view('pages.service', [
            'service' => $service->load('category', 'media'),
            'related' => Service::active()->where('category_id', $service->category_id)
                ->whereKeyNot($service->id)->take(3)->get(),
        ]);
    }

    public function blog(Request $request)
    {
        $locale = app()->getLocale();
        $posts = Post::published()->with(['category', 'media'])
            ->when($request->query('category'), fn ($q, $slug) => $q->whereHas('category', fn ($c) => $c->where('slug', $slug)))
            ->when(trim((string) $request->query('q')), function ($q, $term) use ($locale) {
                $like = '%'.addcslashes($term, '%_\\').'%';
                $q->where(fn ($w) => $w->where("title->$locale", 'like', $like)->orWhere("excerpt->$locale", 'like', $like));
            })
            ->paginate(9)->withQueryString();

        return view('pages.blog', [
            'posts' => $posts,
            'categories' => PostCategory::all(),
        ]);
    }

    public function post(Post $post)
    {
        abort_unless($post->is_published && $post->published_at?->isPast(), 404);

        return view('pages.post', [
            'post' => $post->load('category', 'author', 'media'),
            'related' => Post::published()->whereKeyNot($post->id)->where('category_id', $post->category_id)->take(3)->get(),
        ]);
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function page(string $slug)
    {
        return view('pages.cms', ['page' => Page::where('slug', $slug)->firstOrFail()]);
    }

    public function sitemap(): Response
    {
        $sitemap = Sitemap::create();
        $add = function (string $name, array $params = [], $updated = null) use ($sitemap) {
            foreach (['ar', 'en'] as $loc) {
                $url = Url::create(route($name, ['locale' => $loc] + $params))->setPriority(0.7);
                foreach (['ar', 'en'] as $alt) {
                    $url->addAlternate(route($name, ['locale' => $alt] + $params), $alt);
                }
                if ($updated) {
                    $url->setLastModificationDate($updated);
                }
                $sitemap->add($url);
            }
        };

        foreach (['home', 'about', 'blog', 'contact', 'privacy', 'terms'] as $name) {
            $add($name);
        }
        foreach (ServiceCategory::active()->get() as $c) {
            $add('solutions', ['category' => $c->slug], $c->updated_at);
        }
        foreach (Service::active()->get() as $s) {
            $add('services.show', ['service' => $s->slug], $s->updated_at);
        }
        foreach (Post::published()->get() as $p) {
            $add('blog.show', ['post' => $p->slug], $p->updated_at);
        }

        return response($sitemap->render(), 200, ['Content-Type' => 'application/xml']);
    }

    public function robots(): Response
    {
        $body = "User-agent: *\nDisallow: /admin\nSitemap: ".route('sitemap')."\n";

        return response($body, 200, ['Content-Type' => 'text/plain']);
    }
}
