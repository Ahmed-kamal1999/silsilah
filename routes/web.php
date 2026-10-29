<?php

use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/ar');

Route::get('/admin-locale/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['ar', 'en'], true), 404);

    return back()->withCookie(cookie('admin_locale', $locale, 60 * 24 * 365));
})->name('admin.locale');

Route::get('/sitemap.xml', [SiteController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SiteController::class, 'robots'])->name('robots');

Route::prefix('{locale}')->middleware(['locale'])->where(['locale' => 'ar|en'])->group(function () {
    Route::get('/', [SiteController::class, 'home'])->name('home');
    Route::get('/about', [SiteController::class, 'about'])->name('about');
    Route::get('/solutions/{category:slug}', [SiteController::class, 'solutions'])->name('solutions');
    Route::get('/services/{service:slug}', [SiteController::class, 'service'])->name('services.show');
    Route::get('/blog', [SiteController::class, 'blog'])->name('blog');
    Route::get('/blog/{post:slug}', [SiteController::class, 'post'])->name('blog.show');
    Route::get('/contact', [SiteController::class, 'contact'])->name('contact');
    Route::get('/privacy', [SiteController::class, 'page'])->defaults('slug', 'privacy')->name('privacy');
    Route::get('/terms', [SiteController::class, 'page'])->defaults('slug', 'terms')->name('terms');
});
