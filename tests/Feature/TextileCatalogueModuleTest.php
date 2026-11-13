<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Product;
use App\Models\TextileCategory;
use App\Models\TextileProduct;
use App\Models\FibreMaster;
use Database\Seeders\TextileClassificationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TextileCatalogueModuleTest extends TestCase
{
    use RefreshDatabase;

    protected $seller;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        $sellerRole = Role::firstOrCreate(['slug' => 'seller', 'name' => 'Seller']);
        Role::firstOrCreate(['slug' => 'buyer', 'name' => 'Buyer']);

        // Seed textile classification taxonomy & masters
        $this->seed(TextileClassificationSeeder::class);

        $this->seller = User::create([
            'name' => 'Exporter Agent',
            'email' => 'exporter_textile@test.com',
            'password' => bcrypt('password123'),
            'status' => 'active',
            'is_main_exporter' => true,
        ]);
        $this->seller->roles()->attach($sellerRole);
    }

    public function test_classification_search_api_returns_expected_results()
    {
        $response = $this->getJson(route('api.classifications.search', ['q' => 'cotton']));
        $response->assertStatus(200);
        $data = $response->json();
        $this->assertIsArray($data);
        $this->assertNotEmpty($data);
        $this->assertArrayHasKey('name', $data[0]);
        $this->assertArrayHasKey('category_name', $data[0]);
        $this->assertArrayHasKey('subcategory_name', $data[0]);
    }

    public function test_category_attributes_api_returns_masters_and_rules()
    {
        $category = TextileCategory::first();
        $this->assertNotNull($category);

        $response = $this->getJson(route('api.classifications.category-attributes', ['category_id' => $category->id]));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'category_id',
            'rules',
            'fibres',
            'yarns',
            'fabrics',
            'processes'
        ]);
    }

    public function test_seller_can_create_product_with_textile_classification_and_attributes()
    {
        $textileProduct = TextileProduct::with('subcategory.category')->first();
        $this->assertNotNull($textileProduct);

        $cottonFibre = FibreMaster::where('name', 'Cotton')->first() ?? FibreMaster::first();

        $payload = [
            'name' => 'Automated Test Luxury Cotton Shirting',
            'textile_product_id' => $textileProduct->id,
            'cluster' => 'Surat (Gujarat)',
            'moq' => '1000',
            'unit_of_measurement' => 'Meters',
            'hs_code' => '5208.32',
            'production_capacity' => '25,000 meters / month',
            'width_size' => '58 inches',
            'pattern' => 'Oxford Weave',
            'color' => 'Classic Navy',
            'tags' => 'combed, organic, gots',
            'fibres' => [
                [
                    'master_id' => $cottonFibre->id,
                    'name' => $cottonFibre->name,
                    'percentage' => 100,
                ]
            ],
            'is_published' => 1,
        ];

        $response = $this->actingAs($this->seller)
            ->post(route('seller.products.store'), $payload);

        $response->assertRedirect(route('seller.products.index'));

        $product = Product::where('name', 'Automated Test Luxury Cotton Shirting')->first();
        $this->assertNotNull($product);
        $this->assertEquals($textileProduct->id, $product->textile_product_id);
        $this->assertEquals($textileProduct->subcategory_id, $product->textile_subcategory_id);
        $this->assertEquals($textileProduct->subcategory->category_id, $product->textile_category_id);
        $this->assertEquals('1000', $product->moq);
        $this->assertEquals('Approved', $product->status);

        // Assert attribute values
        $this->assertDatabaseHas('product_attribute_values', [
            'product_id' => $product->id,
            'attribute_type' => 'fibre',
            'attribute_name' => $cottonFibre->name,
            'percentage' => 100.00,
        ]);
    }

    public function test_public_catalogue_filters_by_category_and_fibre()
    {
        $textileProduct = TextileProduct::with('subcategory.category')->first();

        $product = Product::create([
            'user_id' => $this->seller->id,
            'name' => 'Published Organic Satin Sheeting',
            'status' => 'Approved',
            'is_published' => true,
            'textile_category_id' => $textileProduct->subcategory->category_id,
            'textile_subcategory_id' => $textileProduct->subcategory_id,
            'textile_product_id' => $textileProduct->id,
            'category' => ['Home Textiles'],
            'moq' => '500',
            'unit_of_measurement' => 'Pieces',
            'cluster' => 'Karur (Tamil Nadu)',
        ]);

        $product->attributeValues()->create([
            'attribute_type' => 'fibre',
            'attribute_name' => 'Organic Cotton',
            'percentage' => 100,
            'attribute_value' => '100%',
        ]);

        // Filter by category_id
        $response = $this->get(route('catalogue.index', ['category_id' => $textileProduct->subcategory->category_id]));
        $response->assertStatus(200);
        $response->assertSee('Published Organic Satin Sheeting');

        // Filter by fibre
        $fibreResponse = $this->get(route('catalogue.index', ['fibre' => 'Cotton']));
        $fibreResponse->assertStatus(200);
        $fibreResponse->assertSee('Published Organic Satin Sheeting');

        // Test show page
        $showResponse = $this->get(route('catalogue.show', $product));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Published Organic Satin Sheeting');
        $showResponse->assertSee('Organic Cotton');
        $showResponse->assertSee('Karur (Tamil Nadu)');
    }
}
