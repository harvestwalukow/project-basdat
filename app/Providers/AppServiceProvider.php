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
        \App\Models\Penitipan::observe(\App\Observers\DW\PenitipanObserver::class);
        \App\Models\Pembayaran::observe(\App\Observers\DW\PembayaranObserver::class);
        \App\Models\Pengguna::observe(\App\Observers\DW\PenggunaObserver::class);
        \App\Models\Hewan::observe(\App\Observers\DW\HewanObserver::class);
    }
}
