<?php


namespace LaravelClass\LaraAuth\Providers;


use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use LaravelClass\LaraAuth\Commands\LaraAuthUp;
use LaravelClass\LaraAuth\Events\RegisterUser;
use LaravelClass\LaraAuth\Listeners\RegisterListener;
use LaravelClass\LaraAuth\Service\Implementation\Core;

class LaraAuthProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('LaraAuth',function (){return new Core();});

        $this->mergeConfigFrom(__DIR__.'/../config/laraAuth.php','laraAuth');

    }

    public function boot()
    {
        Event::listen(RegisterUser::class,[
            RegisterListener::class,'handle'
        ]);

        $this->publishes([
            __DIR__.'/../AjaxAbstract/laraAuthAjax.js' => public_path("laraAuthAjax.js")
        ],'laraAuth-ajax');

        $this->publishes([
            __DIR__.'/../config/laraAuth.php' => config_path('laraAuth.php')
        ],'laraAuth-config');

        $this->commands([
            LaraAuthUp::class
        ]);
    }
}
