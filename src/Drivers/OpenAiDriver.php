<?php

namespace LaravelAiBridge\Ai\Drivers;

use LaravelAiBridge\Ai\Contracts\AiProviderInterface;
use LaravelAiBridge\Ai\Contracts\AiResponseInterface;
use LaravelAiBridge\Ai\Responses\StandardizedResponse;
use OpenAI;
use OpenAI\Client;

class OpenAiDriver implements AiProviderInterface
{
    protected Client $client;
    protected array $config;
    
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->client = OpenAI::client($config['api_key'], $config['organization'] ?? null);
    }
    
    public function chat(array $messages): AiResponseInterface
    {
        $response = $this->client->chat()->create([
            'model' => $this->config['model'] ?? 'gpt-3.5-turbo',
            'messages' => $messages,
        ]);
        
        return new StandardizedResponse(
            $response->choices[0]->message->content,
            [
                'input_tokens' => $response->usage->promptTokens,
                'output_tokens' => $response->usage->completionTokens,
                'total_tokens' => $response->usage->totalTokens
            ],
            true
        );
    }
}