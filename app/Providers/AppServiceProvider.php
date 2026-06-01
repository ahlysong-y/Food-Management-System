<?php

namespace App\Providers;

use App\Models\Order;
use App\Observers\OrderObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
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
        if (
            app()->environment('production') ||
            env('VERCEL') ||
            env('VERCEL_ENV')
        ) {
            URL::forceScheme('https');
        }

        Order::observe(OrderObserver::class);

        // ២. ចុះឈ្មោះប្រព័ន្ធតាមដានការ Update លើ Table Order
        Order::observe(OrderObserver::class);
    }
}
