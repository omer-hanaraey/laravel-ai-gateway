<?php
namespace LaravelAiBridge\Ai\Drivers;

use LaravelAiBridge\Ai\Contracts\AiProviderInterface;
use LaravelAiBridge\Ai\Contracts\AiResponseInterface;
use LaravelAiBridge\Ai\Responses\StandardizedResponse;
use Google\Client as GoogleClient;

class GeminiDriver implements AiProviderInterface
{
    protected $client;
    protected $config;
    
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->client = new GoogleClient([
            'api_key' => $config['api_key']
        ]);
    }
    
    public function chat(array $messages): AiResponseInterface
    {
        $lastMessage = end($messages);
        
        $response = $this->client->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent', [
            'json' => [
                'contents' => [
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