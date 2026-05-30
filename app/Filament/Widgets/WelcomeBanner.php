<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class WelcomeBanner extends Widget
{
    protected static string $view = 'filament.widgets.welcome-banner';

    // កំណត់ឱ្យវាឡើងទៅនៅខាងលើគេបង្អស់ (ធំជាងគេ)
    protected static ?int $sort = -2;

    // កំណត់ឱ្យវាពង្រីកពេញទទឹងអេក្រង់ (Full Width)
    protected static bool $isLazy = false;
}
