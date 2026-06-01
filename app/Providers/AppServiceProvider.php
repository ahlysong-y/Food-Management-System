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
        // ១. បង្ខំឱ្យប្រើប្រាស់ប្រព័ន្ធ HTTPS ជានិច្ចនៅលើ Vercel / Production
        if (app()->environment('production') || env('VERCEL') || env('VERCEL_ENV') || isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            URL::forceScheme('https');

            // 💡 បន្ថែមចំណុចនេះ៖ បង្ខំឱ្យ Livewire Update Endpoint ដើរតាម HTTPS ដែរ
            // ការពារដាច់ខាតមិនឱ្យចេញ Error MethodNotAllowedHttpException ពេលចុចប៊ូតុង Login
            Livewire::setUpdateRoute(function ($handle) {
                return \Illuminate\Support\Facades\Route::post('/livewire/update', $handle)
                    ->middleware(['web']);
            });
        }

        // ២. ចុះឈ្មោះប្រព័ន្ធតាមដានការ Update លើ Table Order
        Order::observe(OrderObserver::class);
    }
}
