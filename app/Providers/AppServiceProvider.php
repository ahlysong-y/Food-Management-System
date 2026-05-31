<?php

namespace App\Providers;

use App\Models\Order;
use App\Observers\OrderObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Force HTTPS on production or Vercel
        if (app()->environment('production') || env('VERCEL') || env('VERCEL_ENV') || isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            URL::forceScheme('https');
        }

        // ចុះឈ្មោះប្រព័ន្ធតាមដានការ Update លើ Table Order
        Order::observe(OrderObserver::class);
    }
}
