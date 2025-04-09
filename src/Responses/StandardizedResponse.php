<?php

// src/Responses/StandardizedResponse.php
namespace LaravelAiGateway\Ai\Responses;

use LaravelAiGateway\Ai\Contracts\AiResponseInterface;

class StandardizedResponse implements AiResponseInterface
{
    protected $content;
    protected $usage;
    protected $success;
    
    public function __construct(string $content, array $usage, bool $success)
    {
        $this->content = $content;
        $this->usage = $usage;
        $this->success = $success;
    }
    
    public function getContent(): string
    {
        return $this->content;
    }
    
    public function getUsage(): array
    {
        return $this->usage;
    }
    
    public function isSuccessful(): bool
    {
        return $this->success;
    }
}