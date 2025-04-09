<?php
namespace LaravelAiGateway\Ai\Drivers;

use LaravelAiGateway\Ai\Contracts\AiProviderInterface;
use LaravelAiGateway\Ai\Contracts\AiResponseInterface;
use LaravelAiGateway\Ai\Responses\StandardizedResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ClaudeDriver implements AiProviderInterface
{
    protected Client $client;
    protected array $config;
    protected $baseUrl = 'https://api.anthropic.com/v1/messages';
    
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->client = new Client([
            'headers' => [
                'x-api-key' => $this->config['api_key'],
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
                'accept' => 'application/json',
            ]
        ]);
    }
    
    public function chat(array $messages): AiResponseInterface
    {
        try {
            $response = $this->client->post($this->baseUrl, [
                'json' => [
                    'model' => 'claude-3-sonnet-20240229',
                    'messages' => $messages,
                    'max_tokens' => 1024,
                ]
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            return new StandardizedResponse(
                $responseData['content'][0]['text'],
                [
                    'tokens' => $responseData['usage']['input_tokens'] + $responseData['usage']['output_tokens'],
                    'response_data' => $responseData
                ],
                true
            );

        } catch (RequestException $e) {
            $errorResponse = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            throw new \Exception("Claude API request failed: " . $errorResponse);
        }
    }
}