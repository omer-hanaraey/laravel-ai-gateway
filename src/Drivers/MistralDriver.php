<?php

namespace LaravelAiGateway\Ai\Drivers;

use LaravelAiGateway\Ai\Contracts\AiProviderInterface;
use LaravelAiGateway\Ai\Contracts\AiResponseInterface;
use LaravelAiGateway\Ai\Responses\StandardizedResponse;
use GuzzleHttp\Client as HttpClient;

class MistralDriver implements AiProviderInterface
{
    protected HttpClient $client;
    protected array $config;
    
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->client = new HttpClient([
            'base_uri' => 'https://api.mistral.ai/',
            'headers' => [
                'Authorization' => 'Bearer ' . $this->config['api_key'],
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'timeout' => $this->config['timeout'] ?? 30,
        ]);
    }
    
    public function chat(array $messages): AiResponseInterface
    {
        $response = $this->client->post('v1/chat/completions', [
            'json' => [
                'model' => $this->config['model'] ?? 'mistral-large-latest',
                'messages' => $messages,
            ],
        ]);
        
        $responseData = json_decode($response->getBody()->getContents(), true);
        
        return new StandardizedResponse(
            $responseData['choices'][0]['message']['content'],
            [
                'input_tokens' => $responseData['usage']['prompt_tokens'] ?? 0,
                'output_tokens' => $responseData['usage']['completion_tokens'] ?? 0,
                'total_tokens' => $responseData['usage']['total_tokens'] ?? 0,
            ],
            true
        );
    }
}