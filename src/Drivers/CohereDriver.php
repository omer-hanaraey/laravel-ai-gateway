<?php

namespace LaravelAiGateway\Ai\Drivers;

use LaravelAiGateway\Ai\Contracts\AiProviderInterface;
use LaravelAiGateway\Ai\Contracts\AiResponseInterface;
use LaravelAiGateway\Ai\Responses\StandardizedResponse;
use GuzzleHttp\Client as HttpClient;

class CohereDriver implements AiProviderInterface
{
    protected HttpClient $client;
    protected array $config;
    
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->client = new HttpClient([
            'base_uri' => 'https://api.cohere.com/v2',
            'headers' => [
                'Authorization' => 'Bearer ' . $this->config['api_key'],
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Client-Name' => $this->config['client_name'] ?? 'laravel-ai-gateway',
            ],
            'timeout' => $this->config['timeout'] ?? 30,
        ]);
    }
    
    public function chat(array $messages): AiResponseInterface
    {
        // Convert OpenAI-style messages to Cohere format
        $formattedMessages = $this->formatMessages($messages);
        $lastMessage = array_pop($formattedMessages);
        
        $response = $this->client->post('chat', [
            'json' => array_merge([
                'model' => $this->config['model'] ?? 'command-a-03-2025',
                'message' => $lastMessage['message'],
                'chat_history' => $formattedMessages,
                'temperature' => $this->config['temperature'] ?? 0.3,
                'max_tokens' => $this->config['max_tokens'] ?? 2000,
                'preamble' => $this->config['preamble'] ?? null,
                'stream' => false,
            ], $this->config['options'] ?? [])
        ]);
        
        $responseData = json_decode($response->getBody()->getContents(), true);
        
        return new StandardizedResponse(
            $responseData['text'],
            [
                'input_tokens' => $responseData['meta']['billed_units']['input_tokens'] ?? 0,
                'output_tokens' => $responseData['meta']['billed_units']['output_tokens'] ?? 0,
                'total_tokens' => ($responseData['meta']['billed_units']['input_tokens'] ?? 0) + 
                                 ($responseData['meta']['billed_units']['output_tokens'] ?? 0),
            ],
            true
        );
    }

    protected function formatMessages(array $messages): array
    {
        $formatted = [];
        
        foreach ($messages as $message) {
            $formatted[] = [
                'role' => $message['role'] === 'assistant' ? 'CHATBOT' : 'USER',
                'message' => $message['content']
            ];
        }
        
        return $formatted;
    }
}