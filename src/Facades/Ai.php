<?php

namespace LaravelAiBridge\Ai\Facades;

use Illuminate\Support\Facades\Facade;

class Ai extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ai-bridge';
    }
}