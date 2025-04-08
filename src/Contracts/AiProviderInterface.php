<?php

namespace LaravelAiBridge\Ai\Contracts;

interface AiProviderInterface
{
    public function chat(array $messages): AiResponseInterface;
}