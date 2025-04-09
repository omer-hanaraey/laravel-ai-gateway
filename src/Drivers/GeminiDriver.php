<?php
namespace LaravelAiGateway\Ai\Drivers;

use LaravelAiGateway\Ai\Contracts\AiProviderInterface;
use LaravelAiGateway\Ai\Contracts\AiResponseInterface;
use LaravelAiGateway\Ai\Responses\StandardizedResponse;
use GuzzleHttp\Client as HttpClient;

class GeminiDriver implements AiProviderInterface
{
    protected $client;
    protected $config;
    
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->client = new HttpClient([
            'base_uri' => 'https://generativelanguage.googleapis.com/',
            'query' => ['key' => $config['api_key']]
        ]);
    }
    
    public function chat(array $messages): AiResponseInterface
    {
        $lastMessage = end($messages);
        
        $response = $this->client->post('v1beta/models/' . ($this->config['model'] ?? 'gemini-2.0-flash') . ':generateContent', [
            'json' => [
                'contents' => [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $lastMessage['content']]
                    ]
                ]
            ]
        ]);
        
        $data = json_decode($response->getBody(), true);
        
        return new StandardizedResponse(
            $data['candidates'][0]['content']['parts'][0]['text'] ?? '',
            ['tokens' => 0],
            true
        );
    }
}