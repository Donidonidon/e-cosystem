<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;

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
        FilamentColor::register([
            'darkBlue' => Color::hex('#1D4ED8'),
            'orange' => Color::hex('#F97316'),
            'lightOrange' => Color::hex('#FFD2A0'),
            'yellow' => Color::hex('#FBBF24'),
        ]);
    }
}
