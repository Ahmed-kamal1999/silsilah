<?php

namespace App\Providers;

use App\Http\Middleware\SetLocale;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Livewire update requests hit /livewire/update, so re-run the locale middleware for them.
        Livewire::addPersistentMiddleware([SetLocale::class]);
        //
    }
}
