<?php 
namespace Sunarc\ChatSystem;

use Illuminate\Support\ServiceProvider;

class ChatSystemServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/resources/views','ChatSystem');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->publishes([
            __DIR__.'/assets' => public_path('vendor/chatsystem'),
          ], 'assets');
        $this->publishes([
            __DIR__.'/resources' => resource_path(''),
          ], 'resources');
        $this->publishes([
            __DIR__.'/Events' => app_path('Events'),
          ], 'events');
    }

    public function register()
    {
    
    }
}