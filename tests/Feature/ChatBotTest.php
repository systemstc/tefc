<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use App\Models\User;
use App\Models\ChatMessage;

class ChatBotTest extends TestCase
{
    use RefreshDatabase;

    public function test_chat_api_requires_message_and_session_id(): void
    {
        $response = $this->postJson('/api/chat', []);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['message', 'session_id']);
    }

    public function test_chat_api_returns_offline_warning_when_openai_key_is_missing(): void
    {
        // Set provider to openai
        $originalProvider = env('CHAT_PROVIDER');
        putenv('CHAT_PROVIDER=openai');
        $_ENV['CHAT_PROVIDER'] = 'openai';
        $_SERVER['CHAT_PROVIDER'] = 'openai';

        // Force OpenAI key to be empty
        $originalOpenAIKey = env('OPENAI_API_KEY');
        putenv('OPENAI_API_KEY=');
        $_ENV['OPENAI_API_KEY'] = '';
        $_SERVER['OPENAI_API_KEY'] = '';

        $response = $this->postJson('/api/chat', [
            'message' => 'Hello export assistant',
            'session_id' => 'session_123456'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => false,
        ]);
        $response->assertJsonFragment([
            'message' => 'The Chatbot is currently offline because the OpenAI API Key is missing. Please configure the `OPENAI_API_KEY` in the server `.env` file to enable the assistant.'
        ]);

        // Assert database logging
        $this->assertDatabaseHas('chat_messages', [
            'session_id' => 'session_123456',
            'sender' => 'user',
            'message' => 'Hello export assistant',
        ]);

        $this->assertDatabaseHas('chat_messages', [
            'session_id' => 'session_123456',
            'sender' => 'assistant',
            'message' => 'The Chatbot is currently offline because the OpenAI API Key is missing. Please configure the `OPENAI_API_KEY` in the server `.env` file to enable the assistant.',
        ]);

        // Restore keys & config
        if ($originalOpenAIKey) {
            putenv("OPENAI_API_KEY={$originalOpenAIKey}");
            $_ENV['OPENAI_API_KEY'] = $originalOpenAIKey;
            $_SERVER['OPENAI_API_KEY'] = $originalOpenAIKey;
        }
        if ($originalProvider) {
            putenv("CHAT_PROVIDER={$originalProvider}");
            $_ENV['CHAT_PROVIDER'] = $originalProvider;
            $_SERVER['CHAT_PROVIDER'] = $originalProvider;
        } else {
            putenv('CHAT_PROVIDER=');
            unset($_ENV['CHAT_PROVIDER']);
            unset($_SERVER['CHAT_PROVIDER']);
        }
    }

    public function test_chat_api_returns_offline_warning_when_gemini_key_is_missing(): void
    {
        // Set provider to gemini
        $originalProvider = env('CHAT_PROVIDER');
        putenv('CHAT_PROVIDER=gemini');
        $_ENV['CHAT_PROVIDER'] = 'gemini';
        $_SERVER['CHAT_PROVIDER'] = 'gemini';

        // Force Gemini key to be empty
        $originalGeminiKey = env('GEMINI_API_KEY');
        putenv('GEMINI_API_KEY=');
        $_ENV['GEMINI_API_KEY'] = '';
        $_SERVER['GEMINI_API_KEY'] = '';

        $response = $this->postJson('/api/chat', [
            'message' => 'Hello export assistant',
            'session_id' => 'session_789012'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => false,
        ]);
        $response->assertJsonFragment([
            'message' => 'The Chatbot is currently offline because the Gemini API Key is missing. Please configure the `GEMINI_API_KEY` in the server `.env` file to enable the assistant.'
        ]);

        // Assert database logging
        $this->assertDatabaseHas('chat_messages', [
            'session_id' => 'session_789012',
            'sender' => 'user',
            'message' => 'Hello export assistant',
        ]);

        // Restore keys & config
        if ($originalGeminiKey) {
            putenv("GEMINI_API_KEY={$originalGeminiKey}");
            $_ENV['GEMINI_API_KEY'] = $originalGeminiKey;
            $_SERVER['GEMINI_API_KEY'] = $originalGeminiKey;
        }
        if ($originalProvider) {
            putenv("CHAT_PROVIDER={$originalProvider}");
            $_ENV['CHAT_PROVIDER'] = $originalProvider;
            $_SERVER['CHAT_PROVIDER'] = $originalProvider;
        } else {
            putenv('CHAT_PROVIDER=');
            unset($_ENV['CHAT_PROVIDER']);
            unset($_SERVER['CHAT_PROVIDER']);
        }
    }

    public function test_chat_api_calls_openai_successfully_when_key_is_present(): void
    {
        // Set provider to openai
        $originalProvider = env('CHAT_PROVIDER');
        putenv('CHAT_PROVIDER=openai');
        $_ENV['CHAT_PROVIDER'] = 'openai';
        $_SERVER['CHAT_PROVIDER'] = 'openai';

        // Mock the OpenAI response
        Http::fake([
            'https://api.openai.com/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'Yes, you can register as a seller at [/register?role=seller](/register?role=seller).'
                        ]
                    ]
                ]
            ], 200)
        ]);

        $originalKey = env('OPENAI_API_KEY');
        putenv('OPENAI_API_KEY=sk-mockkey12345');
        $_ENV['OPENAI_API_KEY'] = 'sk-mockkey12345';
        $_SERVER['OPENAI_API_KEY'] = 'sk-mockkey12345';

        // Log in a user to test user logging
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/chat', [
            'message' => 'How can I register as a seller?',
            'session_id' => 'session_auth_789'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Yes, you can register as a seller at [/register?role=seller](/register?role=seller).'
        ]);

        // Assert database records
        $this->assertDatabaseHas('chat_messages', [
            'session_id' => 'session_auth_789',
            'user_id' => $user->id,
            'sender' => 'user',
            'message' => 'How can I register as a seller?',
        ]);

        $this->assertDatabaseHas('chat_messages', [
            'session_id' => 'session_auth_789',
            'user_id' => $user->id,
            'sender' => 'assistant',
            'message' => 'Yes, you can register as a seller at [/register?role=seller](/register?role=seller).',
        ]);

        // Restore keys & config
        if ($originalKey) {
            putenv("OPENAI_API_KEY={$originalKey}");
            $_ENV['OPENAI_API_KEY'] = $originalKey;
            $_SERVER['OPENAI_API_KEY'] = $originalKey;
        } else {
            putenv('OPENAI_API_KEY=');
            unset($_ENV['OPENAI_API_KEY']);
            unset($_SERVER['OPENAI_API_KEY']);
        }
        if ($originalProvider) {
            putenv("CHAT_PROVIDER={$originalProvider}");
            $_ENV['CHAT_PROVIDER'] = $originalProvider;
            $_SERVER['CHAT_PROVIDER'] = $originalProvider;
        } else {
            putenv('CHAT_PROVIDER=');
            unset($_ENV['CHAT_PROVIDER']);
            unset($_SERVER['CHAT_PROVIDER']);
        }
    }

    public function test_chat_api_calls_gemini_successfully_when_key_is_present(): void
    {
        // Set provider to gemini
        $originalProvider = env('CHAT_PROVIDER');
        putenv('CHAT_PROVIDER=gemini');
        $_ENV['CHAT_PROVIDER'] = 'gemini';
        $_SERVER['CHAT_PROVIDER'] = 'gemini';

        // Mock the Gemini response
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Namaste! Please visit [/p/fta-benefits](/p/fta-benefits) to learn about ECTA scheme.']
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $originalKey = env('GEMINI_API_KEY');
        putenv('GEMINI_API_KEY=gemini-mockkey12345');
        $_ENV['GEMINI_API_KEY'] = 'gemini-mockkey12345';
        $_SERVER['GEMINI_API_KEY'] = 'gemini-mockkey12345';

        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/chat', [
            'message' => 'Tell me about ECTA benefits.',
            'session_id' => 'session_gemini_999'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Namaste! Please visit [/p/fta-benefits](/p/fta-benefits) to learn about ECTA scheme.'
        ]);

        // Assert database records
        $this->assertDatabaseHas('chat_messages', [
            'session_id' => 'session_gemini_999',
            'user_id' => $user->id,
            'sender' => 'user',
            'message' => 'Tell me about ECTA benefits.',
        ]);

        // Restore keys & config
        if ($originalKey) {
            putenv("GEMINI_API_KEY={$originalKey}");
            $_ENV['GEMINI_API_KEY'] = $originalKey;
            $_SERVER['GEMINI_API_KEY'] = $originalKey;
        } else {
            putenv('GEMINI_API_KEY=');
            unset($_ENV['GEMINI_API_KEY']);
            unset($_SERVER['GEMINI_API_KEY']);
        }
        if ($originalProvider) {
            putenv("CHAT_PROVIDER={$originalProvider}");
            $_ENV['CHAT_PROVIDER'] = $originalProvider;
            $_SERVER['CHAT_PROVIDER'] = $originalProvider;
        } else {
            putenv('CHAT_PROVIDER=');
            unset($_ENV['CHAT_PROVIDER']);
            unset($_SERVER['CHAT_PROVIDER']);
        }
    }
}
