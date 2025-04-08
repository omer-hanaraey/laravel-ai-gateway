<?php

namespace LaravelAiBridge\Ai\Contracts;

interface AiResponseInterface
{
    public function getContent(): string;
    public function getUsage(): array;
    public function isSuccessful(): bool;
}