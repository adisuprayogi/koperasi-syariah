<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Koperasi;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Suppress PHP 8.5+ deprecation warnings for PDO constants
        if (PHP_VERSION_ID >= 80500) {
            error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
        }

        // Share koperasi data to all views
        View::composer('*', function ($view) {
            $koperasi = Koperasi::first();
            $view->with('koperasi', $koperasi);
        });
    }
}
