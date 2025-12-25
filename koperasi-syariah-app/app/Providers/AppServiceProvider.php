<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
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
        // Fix PHP 8.5+ PDO deprecation warnings by overriding constants early
        if (PHP_VERSION_ID >= 80500) {
            // Define the new constants to prevent deprecation warnings
            if (!defined('Pdo\Mysql::ATTR_SSL_CA') && defined('PDO::MYSQL_ATTR_SSL_CA')) {
                // Create namespace and constants if they don't exist
                if (!class_exists('Pdo\Mysql')) {
                    class_alias('PDO', 'Pdo\Mysql');
                }
            }
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Use Tailwind CSS for pagination
        Paginator::defaultView('pagination::tailwind');

        // Share koperasi data to all views
        View::composer('*', function ($view) {
            $koperasi = Koperasi::first();
            $view->with('koperasi', $koperasi);
        });
    }
}
