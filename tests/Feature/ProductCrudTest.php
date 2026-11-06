<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Role;
use App\Models\Product;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles
        Role::firstOrCreate(['slug' => 'seller', 'name' => 'Seller']);
        Role::firstOrCreate(['slug' => 'buyer', 'name' => 'Buyer']);
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

    public function test_seller_can_create_product_with_new_fields()
    {
        $seller = $this->createSeller();

        $response = $this->actingAs($seller)->post('/seller/products', [
            'name' => 'Cotton Bedsheet',
            'category' => ['Home Textiles'],
            'cluster' => 'Panipat',
            'hs_code' => '6302',
            'production_capacity' => '10000 units/month',
            'width_size' => 'King Size (275 x 275 cm)',
            'pattern' => 'Jacquard Floral',
            'color' => 'Classic Ivory',
            'material_composition' => '100% Organic Cotton',
            'technical_specifications' => 'Thread count 400',
            'certifications' => 'GOTS Certified',
        ]);

        $response->assertRedirect(route('seller.products.index'));

        $this->assertDatabaseHas('products', [
            'user_id' => $seller->id,
            'name' => 'Cotton Bedsheet',
            'cluster' => 'Panipat',
            'hs_code' => '6302',
            'production_capacity' => '10000 units/month',
            'width_size' => 'King Size (275 x 275 cm)',
            'pattern' => 'Jacquard Floral',
            'color' => 'Classic Ivory',
            'material_composition' => '100% Organic Cotton',
            'technical_specifications' => 'Thread count 400',
            'certifications' => 'GOTS Certified',
            'status' => 'Approved',
        ]);
    }

    public function test_seller_can_edit_product_with_new_fields()
    {
        $seller = $this->createSeller();

        $product = Product::create([
            'user_id' => $seller->id,
            'name' => 'Old Bed Sheet',
            'category' => ['Home Textiles'],
            'cluster' => 'Surat',
            'width_size' => 'Single',
            'pattern' => 'Striped',
            'color' => 'Blue',
            'status' => 'Approved',
        ]);

        $response = $this->actingAs($seller)->put("/seller/products/{$product->id}", [
            'name' => 'Updated Bed Sheet',
            'category' => ['Home Textiles'],
            'cluster' => 'Surat',
            'width_size' => 'Double',
            'pattern' => 'Checkered',
            'color' => 'Green',
        ]);

        $response->assertRedirect(route('seller.products.index'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Bed Sheet',
            'width_size' => 'Double',
            'pattern' => 'Checkered',
            'color' => 'Green',
            'status' => 'Approved',
        ]);
    }

    public function test_login_redirects_to_intended_url()
    {
        $user = User::create([
            'name' => 'Test Buyer',
            'email' => 'buyer@test.com',
            'password' => bcrypt('password123'),
            'status' => 'active',
        ]);
        $user->roles()->attach(Role::where('slug', 'buyer')->first());

        // Simulate visiting login page with redirect=back parameter and HTTP referer set to /catalogue
        $response = $this->from('/catalogue')->get('/login?redirect=back');
        
        $response->assertStatus(200);
        $this->assertEquals(url('/catalogue'), session('url.intended'));

        // Post login request
        $response = $this->post('/login', [
            'email' => 'buyer@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(url('/catalogue'));
    }

    public function test_login_without_redirect_parameter_goes_to_dashboard()
    {
        $user = User::create([
            'name' => 'Test Buyer',
            'email' => 'buyer@test.com',
            'password' => bcrypt('password123'),
            'status' => 'active',
        ]);
        $user->roles()->attach(Role::where('slug', 'buyer')->first());

        // Simulate visiting login page from header (no redirect parameter)
        $response = $this->from('/catalogue')->get('/login');
        
        $response->assertStatus(200);
        $this->assertNull(session('url.intended'));

        // Post login request
        $response = $this->post('/login', [
            'email' => 'buyer@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(url('/exporter-portal'));
    }

    public function test_register_redirects_to_intended_url()
    {
        // Simulate visiting register page with redirect=back parameter and HTTP referer set to /catalogue
        $response = $this->from('/catalogue')->get('/register?redirect=back');

        $response->assertStatus(200);
        $this->assertEquals(url('/catalogue'), session('url.intended'));

        // Post register request
        $response = $this->post('/register', [
            'name' => 'New Buyer',
            'email' => 'newbuyer@test.com',
            'role' => 'buyer',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(url('/catalogue'));
    }
}
