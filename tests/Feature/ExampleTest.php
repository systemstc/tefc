<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\CmsSeeder;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $this->seed(CmsSeeder::class);

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_new_pages_return_successful_response(): void
    {
        $this->seed(CmsSeeder::class);

        $response = $this->get('/p/fta-benefits');
        $response->assertStatus(200);
        $response->assertSee('Free Trade Agreements');

        $response2 = $this->get('/p/online-services');
        $response2->assertStatus(200);
        $response2->assertSee('TEFC Digital Services');

        // Check sub-services pages
        $subServices = [
            'coo' => 'Certificate of Origin',
            'ecgc' => 'ECGC',
            'letter-of-credit' => 'Letter of Credit',
            'hs-code' => 'HS Code',
            'iec' => 'Import Export Code'
        ];

        foreach ($subServices as $slug => $text) {
            $res = $this->get("/p/{$slug}");
            $res->assertStatus(200);
            $res->assertSee($text);
        }
    }
}
