<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Role;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaCenterUploadTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $mediaPage;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        $adminRole = Role::firstOrCreate(['slug' => 'admin', 'name' => 'Administrator']);
        Role::firstOrCreate(['slug' => 'seller', 'name' => 'Seller']);
        Role::firstOrCreate(['slug' => 'buyer', 'name' => 'Buyer']);

        // Create Admin user
        $this->admin = User::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
        ]);
        $this->admin->roles()->attach($adminRole);

        // Create Media page and sections
        $this->mediaPage = Page::create(['slug' => 'media', 'title' => 'Media Center — TEFC']);
        Section::create([
            'page_id' => $this->mediaPage->id,
            'type' => 'media_photos',
            'title' => 'Photo Gallery',
            'order' => 1,
            'content' => ['items' => []]
        ]);
        Section::create([
            'page_id' => $this->mediaPage->id,
            'type' => 'media_training',
            'title' => 'Training Videos',
            'order' => 2,
            'content' => ['items' => []]
        ]);
    }

    public function test_admin_can_upload_photo()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('gallery_photo.jpg');

        $response = $this->actingAs($this->admin)->post('/admin/resources', [
            'upload_type' => 'photo',
            'title' => 'Test Gallery Photo',
            'category' => 'Exhibitions',
            'file' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Check if section content was updated in the DB
        $section = Section::where('type', 'media_photos')->first();
        $items = $section->content['items'];

        $this->assertCount(1, $items);
        $this->assertEquals('Test Gallery Photo', $items[0]['title']);
        $this->assertEquals('Exhibitions', $items[0]['category']);
        $this->assertStringContainsString('uploads/media/photos/', $items[0]['image']);

        // Check if file exists on public path
        $filePath = public_path($items[0]['image']);
        $this->assertTrue(file_exists($filePath));

        // Cleanup uploaded file from disk
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    public function test_admin_can_add_youtube_video()
    {
        $response = $this->actingAs($this->admin)->post('/admin/resources', [
            'upload_type' => 'video',
            'title' => 'FTA Tutorial Video',
            'category' => 'FTA Benefits',
            'video_source' => 'youtube',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'video_section' => 'media_training',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $section = Section::where('type', 'media_training')->first();
        $items = $section->content['items'];

        $this->assertCount(1, $items);
        $this->assertEquals('FTA Tutorial Video', $items[0]['title']);
        $this->assertEquals('https://www.youtube.com/watch?v=dQw4w9WgXcQ', $items[0]['url']);
    }

    public function test_admin_can_delete_media_item()
    {
        // Add fake item to section
        $section = Section::where('type', 'media_photos')->first();
        
        // Write a fake image file to clean up
        $fakeImgPath = 'uploads/media/photos/fake_test_image.jpg';
        $fullPath = public_path($fakeImgPath);
        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }
        file_put_contents($fullPath, 'fake content');

        $content = $section->content;
        $content['items'] = [
            [
                'title' => 'Photo to Delete',
                'category' => 'General',
                'image' => $fakeImgPath
            ]
        ];
        $section->update(['content' => $content]);

        // Send delete request
        $response = $this->actingAs($this->admin)->delete('/admin/resources/media/media_photos/0');

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Check DB has empty items
        $section->refresh();
        $this->assertCount(0, $section->content['items']);

        // Check file is deleted from disk
        $this->assertFalse(file_exists($fullPath));
    }

    public function test_admin_can_upload_photo_with_only_file_mandatory()
    {
        // Setup home page and gallery section
        $homePage = Page::create(['slug' => 'home', 'title' => 'Home']);
        $gallery = Section::create([
            'page_id' => $homePage->id,
            'type' => 'gallery',
            'title' => 'Our Gallery',
            'order' => 1,
            'content' => ['items' => []]
        ]);

        $file = UploadedFile::fake()->image('handloom_exhibition_2026.png', 800, 600);

        // Upload with ONLY the file (leaving title and category blank/omitted)
        $response = $this->actingAs($this->admin)->post('/admin/resources', [
            'upload_type' => 'photo',
            'file' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Check Gallery section
        $gallery->refresh();
        $items = $gallery->content['items'];
        $this->assertCount(1, $items);
        $this->assertEquals('Handloom Exhibition 2026', $items[0]['title']);
        $this->assertEquals('General', $items[0]['category']);
        $this->assertEquals('photo', $items[0]['type']);

        $filePath = public_path($items[0]['image']);
        $this->assertTrue(file_exists($filePath));

        // Cleanup
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
    }

    public function test_admin_can_upload_document_with_only_file_mandatory()
    {
        $file = UploadedFile::fake()->create('textile_policy_circular.pdf', 512, 'application/pdf');

        // Upload with ONLY the file
        $response = $this->actingAs($this->admin)->post('/admin/resources', [
            'upload_type' => 'document',
            'file' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $resource = \App\Models\Resource::latest()->first();
        $this->assertNotNull($resource);
        $this->assertEquals('Textile Policy Circular', $resource->title);
        $this->assertEquals('General', $resource->category);
        $this->assertGreaterThan(0, $resource->file_size);

        if (file_exists(public_path($resource->file_path))) {
            @unlink(public_path($resource->file_path));
        }
    }

    public function test_admin_can_delete_item_from_gallery_section()
    {
        $homePage = Page::firstOrCreate(['slug' => 'home'], ['title' => 'Home']);
        $fakeImg = 'uploads/media/photos/temp_gal_delete.jpg';
        $fullPath = public_path($fakeImg);
        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }
        file_put_contents($fullPath, 'dummy');

        $gallery = Section::firstOrCreate(
            ['type' => 'gallery'],
            [
                'page_id' => $homePage->id,
                'title' => 'Our Gallery',
                'order' => 1,
                'content' => [
                    'items' => [
                        ['title' => 'Item 1', 'category' => 'General', 'image' => $fakeImg, 'type' => 'photo']
                    ]
                ]
            ]
        );
        $gallery->update([
            'content' => [
                'items' => [
                    ['title' => 'Item 1', 'category' => 'General', 'image' => $fakeImg, 'type' => 'photo']
                ]
            ]
        ]);

        $response = $this->actingAs($this->admin)->delete('/admin/resources/media/gallery/0');

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $gallery->refresh();
        $this->assertCount(0, $gallery->content['items']);
        $this->assertFalse(file_exists($fullPath));
    }
}
