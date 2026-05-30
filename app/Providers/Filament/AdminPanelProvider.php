<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color; // ត្រូវប្រាកដថាមានបន្ទាត់នេះ
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            // 🛠️ បន្ថែមបន្ទាត់នេះ ដើម្បីប្តូរឈ្មោះប្រព័ន្ធពី Laravel ទៅជា Food
            ->brandName('Food Management System')
            ->login()
            // ... កូដផ្សេងៗទៀតរក្សាទុកដដែល

            // -------------------------------------------------------------
            // 🎨 ផ្នែកកំណត់ពណ៌ និងរចនាប័ទ្មឱ្យចេញពណ៌សស្អាត (White Theme)
            // -------------------------------------------------------------
            ->colors([
                // ប្តូរពណ៌ចម្បង (Primary) ទៅជាពណ៌ប្រផេះស្រាល ឬទឹកប៊ិចក្រម៉ៅ ដើម្បីឱ្យស៊ីគ្នានឹងផ្ទៃស
                'primary' => Color::Blue,
                'gray' => Color::Slate, // ប្តូរពណ៌ប្រផេះ (Gray) ទៅជាពណ៌ស្រាលដូចគ្នា
            ])
            // 🚫 បិទមិនឱ្យមានការប្តូរទៅកាន់ Dark Mode (បង្ខំឱ្យចេញតែពណ៌សរហូត)
            ->darkMode(false)

            // 顶部 Topbar ឱ្យរត់តាមនៅពេលអូសចុះក្រោម (មើលទៅទំនើប)
            ->topbar(true)
            // -------------------------------------------------------------

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
