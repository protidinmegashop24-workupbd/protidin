<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

use Config;

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
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();
        
        $data = [
                    'driver'            => website_info()->mail_transport,
                    'host'              => website_info()->mail_host,
                    'port'              => website_info()->mail_port,
                    'encryption'        => website_info()->mail_encryption,
                    'username'          => website_info()->mail_user_name,
                    'password'          => website_info()->pail_password,
                    'from'              => [
                        'address'=> website_info()->mail_from,
                        'name'   => website_info()->title
                    ]
                ];
        Config::set('mail',$data);
    }
}
