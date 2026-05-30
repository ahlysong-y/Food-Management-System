<?php

namespace App\Providers;

use App\Models\Order;
use App\Observers\OrderObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ចុះឈ្មោះប្រព័ន្ធតាមដានការ Update លើ Table Order
        Order::observe(OrderObserver::class);
    }
}
