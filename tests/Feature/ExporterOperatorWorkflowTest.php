<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Role;
use App\Models\Product;
use App\Models\ExporterProfile;
use Tests\TestCase;

class ExporterOperatorWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected $exporterRole;
    protected $operatorRole;
    protected $buyerRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->exporterRole = Role::firstOrCreate(['slug' => 'exporter'], ['name' => 'Exporter']);
        Role::firstOrCreate(['slug' => 'seller'], ['name' => 'Seller']);
        $this->operatorRole = Role::firstOrCreate(['slug' => 'operator'], ['name' => 'Data Entry Operator']);
        $this->buyerRole = Role::firstOrCreate(['slug' => 'buyer'], ['name' => 'Buyer']);
    }

    public function test_inactive_exporter_cannot_login_until_verified_by_centre()
    {
        $exporter = User::create([
            'name' => 'Rajesh Sharma',
            'email' => 'rajesh@sharma-textiles.com',
            'password' => bcrypt('password123'),
            'status' => 'pending',
            'tefc_centre' => 'surat',
            'designation' => 'Managing Director',
            'phone' => '+91 9876543210',
        ]);
        $exporter->roles()->attach($this->exporterRole);

        // Attempt login
        $response = $this->post('/login', [
            'email' => 'rajesh@sharma-textiles.com',
            'password' => 'password123',
        ]);

        // Should not be authenticated
        $this->assertGuest();
        $response->assertSessionHasErrors(['email']);
    }

    public function test_verified_exporter_can_login_and_create_data_entry_operator()
    {
        $exporter = User::create([
            'name' => 'Rajesh Sharma',
            'email' => 'rajesh@sharma-textiles.com',
            'password' => bcrypt('password123'),
            'status' => 'active', // Approved by TEFC Centre
            'tefc_centre' => 'surat',
            'designation' => 'Managing Director',
            'phone' => '+91 9876543210',
        ]);
        $exporter->roles()->attach($this->exporterRole);

        ExporterProfile::create([
            'user_id' => $exporter->id,
            'company_name' => 'Sharma Textiles Surat',
            'tefc_centre' => 'surat',
            'contact_phone' => '+91 9876543210',
        ]);

        // Login as exporter
        $loginRes = $this->post('/login', [
            'email' => 'rajesh@sharma-textiles.com',
            'password' => 'password123',
        ]);
        $loginRes->assertRedirect('/exporter-portal');
        $this->assertAuthenticatedAs($exporter);

        // Exporter creates Data Entry Operator
        $opRes = $this->actingAs($exporter)->post('/exporter/operators', [
            'name' => 'Kishore DEO',
            'email' => 'kishore.deo@sharma-textiles.com',
            'phone' => '+91 9123456780',
            'designation' => 'Data Entry Operator',
            'password' => 'operatorPass123',
            'password_confirmation' => 'operatorPass123',
        ]);

        $opRes->assertRedirect(route('exporter.operators.index'));
        $opRes->assertSessionHas('success');

        $operator = User::where('email', 'kishore.deo@sharma-textiles.com')->first();
        $this->assertNotNull($operator);
        $this->assertEquals($exporter->id, $operator->parent_id);
        $this->assertEquals('active', $operator->status);
        $this->assertTrue($operator->isOperator());
        $this->assertTrue($operator->hasRole('operator'));
    }

    public function test_operator_product_submission_requires_higher_authority_acknowledgment_and_is_not_published_initially()
    {
        $exporter = User::create([
            'name' => 'Rajesh Sharma',
            'email' => 'rajesh@sharma-textiles.com',
            'password' => bcrypt('password123'),
            'status' => 'active',
            'tefc_centre' => 'surat',
            'designation' => 'Managing Director',
        ]);
        $exporter->roles()->attach($this->exporterRole);

        $operator = User::create([
            'name' => 'Kishore DEO',
            'email' => 'kishore.deo@sharma-textiles.com',
            'password' => bcrypt('operatorPass123'),
            'status' => 'active',
            'parent_id' => $exporter->id,
            'tefc_centre' => 'surat',
            'designation' => 'Data Entry Operator',
        ]);
        $operator->roles()->attach($this->operatorRole);

        // Operator logs in and submits a product
        $prodRes = $this->actingAs($operator)->post('/seller/products', [
            'name' => 'Surat Jacquard Silk Saree',
            'category' => ['Saree', 'Silk'],
            'cluster' => 'Surat',
            'hs_code' => '5007',
            'material_composition' => '100% Pure Mulberry Silk',
            'production_capacity' => '5000 Pieces/Month',
            'width_size' => '44 inches',
            'color' => 'Royal Maroon',
        ]);

        $prodRes->assertRedirect(route('seller.products.index'));

        $product = Product::where('name', 'Surat Jacquard Silk Saree')->first();
        $this->assertNotNull($product);
        $this->assertEquals($exporter->id, $product->user_id, 'Product must belong to the company user');
        $this->assertEquals($operator->id, $product->created_by_user_id, 'Created by must be the operator');
        $this->assertEquals('Pending Exporter Review', $product->status);
        $this->assertNull($product->acknowledged_at);

        // Verify product is NOT in public catalogue
        $catRes = $this->get('/catalogue');
        $catRes->assertDontSee('Surat Jacquard Silk Saree');

        // Cannot view unapproved product on catalogue page
        $showRes = $this->get('/catalogue/' . $product->id);
        $showRes->assertStatus(404);
    }

    public function test_exporter_acknowledges_operator_product_and_it_publishes_directly_without_ho_approval()
    {
        $exporter = User::create([
            'name' => 'Rajesh Sharma',
            'email' => 'rajesh@sharma-textiles.com',
            'password' => bcrypt('password123'),
            'status' => 'active',
            'tefc_centre' => 'surat',
            'designation' => 'Managing Director',
        ]);
        $exporter->roles()->attach($this->exporterRole);

        $operator = User::create([
            'name' => 'Kishore DEO',
            'email' => 'kishore.deo@sharma-textiles.com',
            'password' => bcrypt('operatorPass123'),
            'status' => 'active',
            'parent_id' => $exporter->id,
            'designation' => 'Data Entry Operator',
        ]);
        $operator->roles()->attach($this->operatorRole);

        $product = Product::create([
            'user_id' => $exporter->id,
            'created_by_user_id' => $operator->id,
            'name' => 'Export Grade Surat Brocade',
            'category' => ['Brocade'],
            'cluster' => 'Surat',
            'hs_code' => '5208',
            'material_composition' => 'Silk Blend',
            'status' => 'Pending Exporter Review',
        ]);

        // Main exporter acknowledges product
        $ackRes = $this->actingAs($exporter)->post('/seller/products/' . $product->id . '/acknowledge');
        $ackRes->assertRedirect(route('seller.products.index'));

        $product->refresh();
        $this->assertEquals('Approved', $product->status);
        $this->assertNotNull($product->acknowledged_at);
        $this->assertEquals($exporter->id, $product->acknowledged_by);

        // Product is now immediately published on public catalogue (no HO approval required)
        $catRes = $this->get('/catalogue');
        $catRes->assertStatus(200);
        $catRes->assertSee('Export Grade Surat Brocade');

        $showRes = $this->get('/catalogue/' . $product->id);
        $showRes->assertStatus(200);
        $showRes->assertSee('Export Grade Surat Brocade');
    }

    public function test_operator_cannot_acknowledge_products()
    {
        $exporter = User::create([
            'name' => 'Rajesh Sharma',
            'email' => 'rajesh@sharma-textiles.com',
            'password' => bcrypt('password123'),
            'status' => 'active',
            'tefc_centre' => 'surat',
            'designation' => 'Managing Director',
        ]);
        $exporter->roles()->attach($this->exporterRole);

        $operator = User::create([
            'name' => 'Kishore DEO',
            'email' => 'kishore.deo@sharma-textiles.com',
            'password' => bcrypt('operatorPass123'),
            'status' => 'active',
            'parent_id' => $exporter->id,
            'designation' => 'Data Entry Operator',
        ]);
        $operator->roles()->attach($this->operatorRole);

        $product = Product::create([
            'user_id' => $exporter->id,
            'created_by_user_id' => $operator->id,
            'name' => 'Test Product',
            'category' => ['Fabric'],
            'status' => 'Pending Exporter Review',
        ]);

        // Operator attempts to acknowledge (should be forbidden 403)
        $ackRes = $this->actingAs($operator)->post('/seller/products/' . $product->id . '/acknowledge');
        $ackRes->assertStatus(403);
    }
}
