<?php

namespace App\Providers;

use App\Services\SeoService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        $this->app->bind('App\Services\SeoService',function ($app){
            return new SeoService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $_variable = request()->all(); unset($_variable['_token']);
        View::share($_variable);

//        $seo = new SeoService();
//        View::share('seo', $seo->getSeo());
    }
}
