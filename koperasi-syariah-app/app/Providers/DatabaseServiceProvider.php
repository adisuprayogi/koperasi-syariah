<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Handle PDO deprecation warnings specifically
        if (PHP_VERSION_ID >= 80500) {
            // Custom error handler to suppress PDO deprecation warnings
            set_error_handler(function ($severity, $message, $file, $line) {
                // Check if this is the specific PDO warning we want to suppress
                if ($severity === E_DEPRECATED &&
                    strpos($message, 'PDO::MYSQL_ATTR_SSL_CA') !== false &&
                    strpos($file, 'vendor/laravel/framework/config/database.php') !== false) {
                    // Suppress this specific warning
                    return true;
                }

                // Handle other errors normally
                return false;
            }, E_DEPRECATED);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}