<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class BhashiniTranslationTest extends TestCase
{
    public function test_bhashini_status_endpoint_returns_languages_and_clusters()
    {
        $response = $this->getJson('/api/bhashini/status');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'configured',
            'languages' => [
                '*' => ['code', 'name', 'native', 'cluster', 'is_cluster']
            ],
            'info'
        ]);

        $languages = $response->json('languages');
        $codes = array_column($languages, 'code');
        
        $this->assertContains('en', $codes);
        $this->assertContains('hi', $codes);
        $this->assertContains('gu', $codes);
        $this->assertContains('ta', $codes);
        $this->assertContains('mr', $codes);
        $this->assertContains('pa', $codes);
    }

    public function test_bhashini_translate_same_language_returns_noop()
    {
        $response = $this->postJson('/api/bhashini/translate', [
            'text' => 'Textile Export Facilitation Centre',
            'source_lang' => 'en',
            'target_lang' => 'en',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'source_lang' => 'en',
            'target_lang' => 'en',
            'single_translation' => 'Textile Export Facilitation Centre',
            'provider' => 'noop'
        ]);
    }

    public function test_bhashini_translate_with_mocked_dhruva_api()
    {
        Config::set('services.bhashini.api_key', 'test-api-key');
        Config::set('services.bhashini.inference_api_key', 'test-inference-key');

        Http::fake([
            'https://dhruva-api.bhashini.gov.in/services/inference/pipeline*' => Http::response([
                'pipelineResponse' => [
                    [
                        'taskType' => 'translation',
                        'output' => [
                            [
                                'source' => 'Welcome to TEFC',
                                'target' => 'टीईएफसी में आपका स्वागत है'
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $response = $this->postJson('/api/bhashini/translate', [
            'text' => 'Welcome to TEFC',
            'source_lang' => 'en',
            'target_lang' => 'hi',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'source_lang' => 'en',
            'target_lang' => 'hi',
            'single_translation' => 'टीईएफसी में आपका स्वागत है',
            'provider' => 'bhashini_dhruva'
        ]);
    }
}
