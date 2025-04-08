<?php
namespace LaravelAiBridge\Ai\Drivers;

use LaravelAiBridge\Ai\Contracts\AiProviderInterface;
use LaravelAiBridge\Ai\Contracts\AiResponseInterface;
use LaravelAiBridge\Ai\Responses\StandardizedResponse;
use Anthropic\Client as AnthropicClient;

class ClaudeDriver implements AiProviderInterface
{
    protected $client;
    protected $config;
    
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->client = new AnthropicClient([
            'api_key' => $config['api_key']
        ]);
    }
    
    public function chat(array $messages): AiResponseInterface
    {
        $response = $this->client->messages()->create([
            'model' => $this->config['model'],
            'messages' => $messages,
            'max_tokens' => 1024
        ]);
        
        return new StandardizedResponse(
            $response['content'][0]['text'],
            ['tokens' => $response['usage']['input_tokens'] + $response['usage']['output_tokens']],
            true
        );
    }
}