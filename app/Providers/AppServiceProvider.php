<?php

namespace App\Providers;

use App\Models\Category;
// use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // require_once base_path('config/constants.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // URL::forceScheme('https');
        View::composer('web.layouts.sidebar', function ($view) {
            $view->with('categories', Category::orderBy('name', 'asc')->get());
        });
    }
}
