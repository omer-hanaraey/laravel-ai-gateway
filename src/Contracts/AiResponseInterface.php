<?php

namespace LaravelAiGateway\Ai\Contracts;

interface AiResponseInterface
{
    public function getContent(): string;
    public function getUsage(): array;
    public function isSuccessful(): bool;
}