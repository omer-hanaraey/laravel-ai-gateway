<?php

namespace LaravelAiGateway\Ai\Drivers;

use LaravelAiGateway\Ai\Contracts\AiProviderInterface;
use LaravelAiGateway\Ai\Contracts\AiResponseInterface;
use LaravelAiGateway\Ai\Responses\StandardizedResponse;
use GuzzleHttp\Client;

class DeepSeekDriver implements AiProviderInterface
{
    protected Client $client;
    protected array $config;
    
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->client = new Client([
            'base_uri' => 'https://api.deepseek.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . $this->config['api_key'],
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }
    
    public function chat(array $messages): AiResponseInterface
    {
        $response = $this->client->post('chat/completions', [
            'json' => [
                'model' => $this->config['model'] ?? 'deepseek-chat',
                'messages' => $messages,
            ],
        ]);
        
        $responseData = json_decode($response->getBody()->getContents(), true);
        
        return new StandardizedResponse(
            $responseData['choices'][0]['message']['content'],
            [
                'input_tokens' => $responseData['usage']['prompt_tokens'],
                'output_tokens' => $responseData['usage']['completion_tokens'],
                'total_tokens' => $responseData['usage']['total_tokens'],
            ],
            true
        );
    }
}