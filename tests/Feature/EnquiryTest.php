<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Role;
use App\Models\ProductInquiry;
use Tests\TestCase;

class EnquiryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles
        Role::firstOrCreate(['slug' => 'buyer', 'name' => 'Buyer']);
        Role::firstOrCreate(['slug' => 'seller', 'name' => 'Seller']);
    }

    protected function createBuyer(): User
    {
        $buyer = User::create([
            'name' => 'John Buyer',
            'email' => 'buyer@test.com',
            'password' => bcrypt('password123'),
        ]);
        $buyer->roles()->attach(Role::where('slug', 'buyer')->first());
        return $buyer;
    }

    protected function createSeller(): User
    {
        $seller = User::create([
            'name' => 'Exporter Agent',
            'email' => 'exporter@test.com',
            'password' => bcrypt('password123'),
        ]);
        $seller->roles()->attach(Role::where('slug', 'seller')->first());
        return $seller;
    }

    public function test_it_submits_general_inquiry_via_post_form()
    {
        $buyer = $this->createBuyer();
        
        $response = $this->actingAs($buyer)->post('/catalogue/enquiry', [
            'buyer_name' => 'John Buyer',
            'buyer_email' => 'buyer@test.com',
            'buyer_phone' => '+91 9999988888',
            'quantity_required' => '2000 Meters',
            'category' => 'Home Textiles',
            'material' => 'Cotton',
            'pattern' => 'Dyed Twill',
            'width_size' => '60 inches',
            'additional_requirements' => 'Need samples first.',
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('product_inquiries', [
            'product_id' => null,
            'buyer_name' => 'John Buyer',
            'buyer_email' => 'buyer@test.com',
            'quantity_required' => '2000 Meters',
            'category' => 'Home Textiles',
            'material' => 'Cotton',
            'pattern' => 'Dyed Twill',
            'width_size' => '60 inches',
        ]);
    }

    public function test_it_submits_general_inquiry_via_ajax()
    {
        $buyer = $this->createBuyer();

        $response = $this->actingAs($buyer)->postJson('/catalogue/enquiry', [
            'buyer_name' => 'Jane Buyer',
            'buyer_email' => 'jane@buyer.com',
            'buyer_phone' => '+91 9999977777',
            'quantity_required' => '500 Kilograms',
            'category' => 'Yarn',
            'material' => 'Polyester',
            'pattern' => 'Combed Ring Spun',
            'width_size' => '30s Count',
            'additional_requirements' => 'Immediate supply.',
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);
        $response->assertJsonStructure([
            'success',
            'message',
            'inquiry' => [
                'id',
                'buyer_name',
                'buyer_email',
                'buyer_phone',
                'quantity_required',
                'category',
                'material',
                'pattern',
                'width_size',
                'additional_requirements'
            ]
        ]);
    }

    public function test_it_validates_required_fields_for_general_inquiry()
    {
        $buyer = $this->createBuyer();

        // Submit empty inquiry
        $response = $this->actingAs($buyer)->postJson('/catalogue/enquiry', []);
        
        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'buyer_name',
            'buyer_email',
            'quantity_required',
            'category',
            'material',
            'pattern',
            'width_size'
        ]);
    }

    public function test_it_submits_product_specific_inquiry_successfully()
    {
        $buyer = $this->createBuyer();
        $seller = $this->createSeller();

        $product = \App\Models\Product::create([
            'user_id' => $seller->id,
            'name' => 'Premium Silk Scarf',
            'category' => ['Garments'],
            'status' => 'Approved',
            'material_composition' => '100% Silk',
        ]);

        $response = $this->actingAs($buyer)->post("/catalogue/{$product->id}/inquiry", [
            'buyer_name' => 'Specific Buyer',
            'buyer_email' => 'specific@buyer.com',
            'buyer_phone' => '+91 9999900000',
            'quantity_required' => '100 Pieces',
            'category' => 'Garments',
            'material' => 'Silk',
            'pattern' => 'Embroidered',
            'width_size' => '30x150 cm',
            'additional_requirements' => 'Custom packaging.',
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('product_inquiries', [
            'product_id' => $product->id,
            'buyer_name' => 'Specific Buyer',
            'buyer_email' => 'specific@buyer.com',
            'quantity_required' => '100 Pieces',
            'category' => 'Garments',
            'material' => 'Silk',
        ]);
    }

    public function test_product_specific_inquiries_are_private_and_not_on_homepage()
    {
        $this->seed(\Database\Seeders\CmsSeeder::class);

        // 1. General inquiry (should be displayed on home page)
        ProductInquiry::create([
            'product_id' => null,
            'buyer_name' => 'Ticker Show Name',
            'buyer_email' => 'ticker@buyer.com',
            'quantity_required' => '500 Meters',
            'category' => 'Fabrics',
            'material' => 'Linen',
            'pattern' => 'Plain Weave',
            'width_size' => '60 inches',
            'additional_requirements' => 'Required for ticker test.',
        ]);

        // 2. Product specific inquiry (should NOT be displayed on home page)
        $seller = $this->createSeller();

        $product = \App\Models\Product::create([
            'user_id' => $seller->id,
            'name' => 'Secret Silk Gown',
            'category' => ['Garments'],
            'status' => 'Approved',
            'material_composition' => '100% Silk',
        ]);

        ProductInquiry::create([
            'product_id' => $product->id,
            'buyer_name' => 'Hidden Buyer Name',
            'buyer_email' => 'hidden@buyer.com',
            'quantity_required' => '10 Pieces',
            'category' => 'Garments',
            'material' => 'Silk',
            'pattern' => 'Floral print',
            'width_size' => 'S / M',
            'additional_requirements' => 'Keep this request private.',
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);

        // General inquiry details must be present in the HTML output
        $response->assertSee('Required for ticker test.');
        $response->assertSee('Fabrics');
        $response->assertSee('Linen');

        // Product specific details must NOT be present in the HTML output
        $response->assertDontSee('Keep this request private.');
        $response->assertDontSee('Secret Silk Gown');
    }

    public function test_guests_cannot_submit_general_inquiry_and_are_redirected()
    {
        $response = $this->post('/catalogue/enquiry', [
            'buyer_name' => 'Guest Buyer',
            'buyer_email' => 'guest@buyer.com',
            'quantity_required' => '10 Meters',
            'category' => 'Fabrics',
            'material' => 'Cotton',
            'pattern' => 'Plain',
            'width_size' => '10 inches',
        ]);
        
        $response->assertRedirect('/login');
    }

    public function test_sellers_cannot_submit_general_inquiry_and_are_blocked()
    {
        $seller = $this->createSeller();

        $response = $this->actingAs($seller)->post('/catalogue/enquiry', [
            'buyer_name' => 'Seller Buyer',
            'buyer_email' => 'seller@buyer.com',
            'quantity_required' => '10 Meters',
            'category' => 'Fabrics',
            'material' => 'Cotton',
            'pattern' => 'Plain',
            'width_size' => '10 inches',
        ]);
        
        $response->assertStatus(403);
    }

    public function test_guests_cannot_submit_product_inquiry_and_are_redirected()
    {
        $seller = $this->createSeller();
        $product = \App\Models\Product::create([
            'user_id' => $seller->id,
            'name' => 'Any Product',
            'category' => ['Garments'],
            'status' => 'Approved',
            'material_composition' => '100% Silk',
        ]);

        $response = $this->post("/catalogue/{$product->id}/inquiry", [
            'buyer_name' => 'Guest Buyer',
            'buyer_email' => 'guest@buyer.com',
            'quantity_required' => '10 Meters',
            'category' => 'Fabrics',
            'material' => 'Cotton',
            'pattern' => 'Plain',
            'width_size' => '10 inches',
        ]);
        
        $response->assertRedirect('/login');
    }

    public function test_sellers_cannot_submit_product_inquiry_and_are_blocked()
    {
        $seller = $this->createSeller();
        $product = \App\Models\Product::create([
            'user_id' => $seller->id,
            'name' => 'Any Product',
            'category' => ['Garments'],
            'status' => 'Approved',
            'material_composition' => '100% Silk',
        ]);

        $response = $this->actingAs($seller)->post("/catalogue/{$product->id}/inquiry", [
            'buyer_name' => 'Seller Buyer',
            'buyer_email' => 'seller@buyer.com',
            'quantity_required' => '10 Meters',
            'category' => 'Fabrics',
            'material' => 'Cotton',
            'pattern' => 'Plain',
            'width_size' => '10 inches',
        ]);
        
        $response->assertStatus(403);
    }
}
