<?php

namespace LaravelAiGateway\Tests;

use LaravelAiGateway\Ai\AiManager;
use LaravelAiGateway\Ai\Drivers\OpenAiDriver;
use LaravelAiGateway\Ai\Facades\Ai;
use Orchestra\Testbench\TestCase;

class AiBridgeTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return ['LaravelAiGateway\Ai\AiServiceProvider'];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Ai' => 'LaravelAiGateway\Ai\Facades\Ai',
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('ai-bridge', [
            'default' => 'openai',
            'drivers' => [
                'openai' => [
                    'api_key' => 'test-key',
                    'model' => 'gpt-3.5-turbo-test',
                ],
            ],
        ]);
    }

    public function testManagerInitialization()
    {
        $manager = $this->app->make('ai-bridge');
        $this->assertInstanceOf(AiManager::class, $manager);
    }

    public function testFacadeWorks()
    {
        $this->assertInstanceOf(AiManager::class, Ai::getFacadeRoot());
    }

    public function testDriverCreation()
    {
        $drivers = ['openai', 'gemini', 'claude'];
        foreach ($drivers as $driver) {
            $driver = Ai::driver('openai');
            $this->assertInstanceOf(OpenAiDriver::class, $driver);
        }
    }

    public function testDefaultDriver()
    {
        $defaultDriver = Ai::getDefaultDriver();
        $this->assertEquals('openai', $defaultDriver);
    }

    public function testInvalidDriverThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Ai::driver('invalid');
    }
}