<?php

namespace App\Providers;

use App\Models\Post;
use App\Services\BrandContext;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(BrandContext::class);
    }

    public function boot(): void
    {
        // Share brand/nav context with the sidebar + topbar on every page.
        View::composer('partials.*', function ($view) {
            $ctx = app(BrandContext::class);
            $current = $ctx->current();
            $view->with([
                'brands' => $ctx->brands(),
                'activeBrand' => $current,
                'postCount' => $current ? Post::where('brand_id', $current->id)->count() : 0,
            ]);
        });
    }
}
