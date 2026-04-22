<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        view()->composer('*', function ($view) {
            if (auth()->check()) {
                $cartQuantity = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
                $view->with('cartQuantity', $cartQuantity);
            } else {
                $view->with('cartQuantity', 0);
            }
        });
    }
}
