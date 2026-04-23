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
        if (config('app.env') !== 'local') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
        
        view()->composer('*', function ($view) {
            try {
                $cartQuantity = auth()->check()
                    ? \App\Models\Cart::where('user_id', auth()->id())->sum('quantity')
                    : 0;
            } catch (\Exception $e) {
                $cartQuantity = 0;
            }
            $view->with('cartQuantity', $cartQuantity);
        });
    }
}
