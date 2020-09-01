<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use App\User;

class BladeExtrasServiceProvider extends ServiceProvider
{
    /**
     * INSTALACION
     * run
     * php artisan make:provider BladeExtrasServiceProvider
     * Copiar "App\Providers\BladeExtrasServiceProvider::class,"
     * en config/App.php "Application Service Providers..."
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::if('isAdmin', function(){
            if(Auth::User()){
                if(Auth::User()->is_admin == true){
                    return true;
                }
            }

            return false;
        });
    }
}
