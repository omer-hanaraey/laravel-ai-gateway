<?php

namespace LaravelAiGateway\Ai;

use InvalidArgumentException;

class AiManager
{
    protected $app;
    protected $drivers = [];
    
    public function __construct($app)
    {
        $this->app = $app;
    }
    
    public function driver($name = null)
    {
        $name = $name ?: $this->getDefaultDriver();
        
        if (!isset($this->drivers[$name])) {
            $this->drivers[$name] = $this->createDriver($name);
        }
        
        return $this->drivers[$name];
    }
    
    protected function createDriver($name)
    {
        $config = $this->getConfig($name);
        
        $driverMethod = 'create'.ucfirst($name).'Driver';
        
        if (method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}($config);
        }
        
        throw new InvalidArgumentException("Driver [$name] not supported.");
    }
    
    protected function createOpenaiDriver(array $config)
    {
        return new Drivers\OpenAiDriver($config);
    }
    
    protected function createGeminiDriver(array $config)
    {
        return new Drivers\GeminiDriver($config);
    }
    
    protected function createClaudeDriver(array $config)
    {
        return new Drivers\ClaudeDriver($config);
    }
    
    public function getDefaultDriver()
    {
        return $this->app['config']['ai-bridge.default'];
    }
    
    protected function getConfig($name)
    {
        return $this->app['config']["ai-bridge.drivers.{$name}"];
    }
    
    public function __call($method, $parameters)
    {
        return $this->driver()->$method(...$parameters);
    }
}