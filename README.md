# Laravel AI Bridge

A unified interface for multiple AI providers in Laravel applications.

## Features

- Support for multiple AI providers (OpenAI, Gemini, Claude, DeepSeek, Mistral, and Cohere)
- Unified API interface
- Easy driver switching
- Standardized response format
- Facade access

## Installation

1. Install via Composer:

```bash
composer require omer-hanaraey/laravel-ai-gateway
```

2. Publish the configuration file:

```bash
php artisan vendor:publish --tag=ai-bridge-config
```

## Configuration
Add your API keys to your .env file:

```ini
AI_DEFAULT_DRIVER=openai

OPENAI_API_KEY=your_openai_key
OPENAI_MODEL=gpt-3.5-turbo

GEMINI_API_KEY=your_gemini_key
GEMINI_MODEL=gemini-pro

CLAUDE_API_KEY=your_claude_key
CLAUDE_MODEL=claude-2

DEEPSEEK_API_KEY=your_deepseek_key
DEEPSEEK_MODEL=deepseek-chat

MISTRAL_API_KEY=your_mistral_key
MISTRAL_MODEL=mistral-large-latest

COHERE_API_KEY=your_cohere_key
COHERE_MODEL=command-a-03-2025
COHERE_CLIENT_NAME=your_cohere_client
```

## Usage
Usage Examples 🧑‍💻

```php
use LaravelAiGateway\Ai\Facades\Ai;

$response = Ai::chat([
    ['role' => 'system', 'content' => 'You are a helpful assistant'],
    ['role' => 'user', 'content' => 'Tell me a joke about Laravel']
]);

echo $response->getContent();
```

Using Specific Drivers

```php
$response = Ai::driver('claude')
    ->withModel('claude-3-haiku-20240307')
    ->chat([...]);
```
With Fallback Handling

```php
try {
    $response = Ai::chat([...]);
} catch (\LaravelAiGateway\Ai\Exceptions\AiException $e) {
    // Log error
    Log::error("AI request failed: " . $e->getMessage());
    
    // Try next provider automatically
    $response = Ai::fallback()->chat([...]);
}
```

Embeddings

```php
$embedding = Ai::embed('Some text to embed');
```

Moderation

```php
$result = Ai::moderate('Inappropriate text to check');
```

### Available Methods
- `chat(array $messages)`: Send chat completion
- `driver(string $name)`: Switch between providers

### Response Format 📦

All responses implement `AiResponseInterface` with these methods:

- `getContent()`: string - Get the response content
- `getUsage()`: array - Get usage statistics
- `isSuccessful()`: bool - Check if request succeeded

### Testing  🧪

Run the tests with:

```bash
composer test tests
```

### Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

### License 📄
MIT License - Free for commercial and personal use.