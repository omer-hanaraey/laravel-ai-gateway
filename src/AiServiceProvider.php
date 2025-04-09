<?php

namespace LaravelAiGateway\Ai;

use Illuminate\Support\ServiceProvider;

class AiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ai-bridge.php', 'ai-bridge');
        
        $this->app->singleton('ai-bridge', function ($app) {
            return new AiManager($app);
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/ai-bridge.php' => config_path('ai-bridge.php'),
        ]);
    }
}