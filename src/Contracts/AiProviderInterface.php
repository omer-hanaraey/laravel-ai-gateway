<?php

namespace LaravelAiGateway\Ai\Contracts;

interface AiProviderInterface
{
    public function chat(array $messages): AiResponseInterface;
}