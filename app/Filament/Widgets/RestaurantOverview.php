<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\DiningTable;
use App\Models\Employee;
// 🛠️ បានលុបបន្ទាត់ use Filament\Support\Colors\Color; ចេញដើម្បីបាត់ការព្រមាន (Warning)
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RestaurantOverview extends BaseWidget
{
    // កំណត់ឱ្យវា Update ទិន្នន័យស្វ័យប្រវត្តិនរាល់ ៥ វិនាទីម្តង (Real-time)
    protected static ?string $pollingInterval = '5s';

    protected function getStats(): array
    {
        // ១. គណនាលុយដែលលក់បានសរុបពី Order ដែល Completed
        $totalSales = Order::where('status', 'Completed')->sum('total_amount');

        // ២. រាប់ចំនួនតុដែលភ្ញៀវកំពុងអង្គុយ (Occupied)
        $occupiedTables = DiningTable::where('status', 'Occupied')->count();

        // ៣. រាប់ចំនួនបុគ្គលិកសកម្មក្នុងហាង
        $activeEmployees = Employee::where('status', true)->count();

        return [
            Stat::make('ចំណូលសរុប (Total Sales)', '$' . number_format($totalSales, 2))
                ->description('ទឹកប្រាក់ទទួលបានពីការលក់ជាក់ស្តែង')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'), // ចេញពណ៌បៃតងតំណាងឱ្យលុយចូល

            Stat::make('តុដែលមានភ្ញៀវ (Occupied Tables)', $occupiedTables)
                ->description('ចំនួនតុដែលកំពុងមានសកម្មភាពបច្ចុប្បន្ន')
                ->descriptionIcon('heroicon-m-table-cells')
                ->color('warning'), // ចេញពណ៌លឿងដាស់តឿន

            Stat::make('បុគ្គលិកកំពុងធ្វើការ (Active Staff)', $activeEmployees)
                ->description('បុគ្គលិកសរុបគ្រប់សាខាទាំងអស់')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'), // ចេញពណ៌ខៀវព័ត៌មាន
        ];
    }
}
