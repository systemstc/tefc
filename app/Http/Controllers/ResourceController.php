<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::orderBy('created_at', 'desc')->get();
        $mediaPage = \App\Models\Page::with('sections')->where('slug', 'media')->first();
        $gallerySection = \App\Models\Section::where('type', 'gallery')->first();
        return view('admin.resources.index', compact('resources', 'mediaPage', 'gallerySection'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'upload_type' => 'required|in:document,photo,video',
            'title' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'file' => 'nullable|file|max:51200', // Max 50MB
            'video_url' => 'nullable|string|max:1000',
            'video_section' => 'nullable|string|max:100',
            'video_source' => 'nullable|in:file,youtube',
        ]);

        $type = $request->upload_type;
        $file = $request->file('file');

        // Automatically derive fallback title if title is omitted
        $fallbackTitle = '';
        if ($file) {
            $fallbackTitle = ucwords(trim(preg_replace('/[_\-\.]+/', ' ', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))));
        }
        $title = $request->filled('title') ? $request->title : ($fallbackTitle ?: 'Gallery Media');
        $category = $request->filled('category') ? $request->category : 'General';

        if ($type === 'document') {
            if (!$request->hasFile('file')) {
                return redirect()->back()->with('error', 'Please select a document file to upload.');
            }
            $targetDir = public_path('uploads/resources');
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $fileExtension = $file->getClientOriginalExtension();
            $fileSize = $file->getSize();
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move($targetDir, $fileName);
            $filePath = 'uploads/resources/' . $fileName;

            if (empty($fileSize) && file_exists(public_path($filePath))) {
                $fileSize = filesize(public_path($filePath));
            }

            Resource::create([
                'title' => $title,
                'category' => $category,
                'file_path' => $filePath,
                'file_type' => $fileExtension,
                'file_size' => $fileSize,
            ]);

            return redirect()->back()->with('success', 'Document uploaded successfully.');
        }

        if ($type === 'photo') {
            if (!$request->hasFile('file')) {
                return redirect()->back()->with('error', 'Please select an image file to upload.');
            }
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'])) {
                return redirect()->back()->with('error', 'Invalid image format. Supported formats: JPG, JPEG, PNG, WEBP, GIF, SVG.');
            }
            $targetDir = public_path('uploads/media/photos');
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move($targetDir, $fileName);
            $filePath = 'uploads/media/photos/' . $fileName;

            // 1. Add to Home Page Gallery section (type: 'gallery')
            $gallerySection = \App\Models\Section::where('type', 'gallery')->first();
            if ($gallerySection) {
                $content = $gallerySection->content ?? [];
                $items = $content['items'] ?? [];
                $items[] = [
                    'title' => $title,
                    'category' => $category,
                    'image' => $filePath,
                    'type' => 'photo',
                    'published' => now()->format('M Y'),
                ];
                $content['items'] = $items;
                $gallerySection->update(['content' => $content]);
            }

            // 2. Also keep Media page in sync if exists
            $mediaPage = \App\Models\Page::where('slug', 'media')->first();
            if ($mediaPage) {
                $section = $mediaPage->sections()->where('type', 'media_photos')->first()
                    ?? $mediaPage->sections()->where('type', 'media_center')->first();
                if ($section) {
                    $content = $section->content ?? [];
                    $items = $content['items'] ?? [];
                    $items[] = [
                        'title' => $title,
                        'category' => $category,
                        'image' => $filePath,
                        'label' => $title,
                        'published' => now()->format('M Y')
                    ];
                    $content['items'] = $items;
                    $section->update(['content' => $content]);
                }
            }

            return redirect()->back()->with('success', 'Photo uploaded and added to Gallery successfully.');
        }

        if ($type === 'video') {
            $videoUrl = '';
            $videoSource = $request->video_source ?? ($request->hasFile('file') ? 'file' : 'youtube');

            if ($videoSource === 'file') {
                if (!$request->hasFile('file')) {
                    return redirect()->back()->with('error', 'Please select a video file to upload.');
                }
                $ext = strtolower($file->getClientOriginalExtension());
                if (!in_array($ext, ['mp4', 'webm', 'ogg', 'mov'])) {
                    return redirect()->back()->with('error', 'Invalid video format. Supported formats: MP4, WEBM, OGG, MOV.');
                }
                $targetDir = public_path('uploads/media/videos');
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move($targetDir, $fileName);
                $videoUrl = 'uploads/media/videos/' . $fileName;
            } else {
                if (!$request->video_url) {
                    return redirect()->back()->with('error', 'Please provide a YouTube video URL or select a video file.');
                }
                $videoUrl = $request->video_url;
            }

            // 1. Add to Home Page Gallery section (type: 'gallery')
            $gallerySection = \App\Models\Section::where('type', 'gallery')->first();
            if ($gallerySection) {
                $content = $gallerySection->content ?? [];
                $items = $content['items'] ?? [];
                $items[] = [
                    'title' => $title,
                    'category' => $category,
                    'video' => $videoUrl,
                    'url' => $videoUrl,
                    'image' => 'images/gallery/gallery_1.jpg',
                    'type' => 'video',
                    'duration' => $videoSource === 'file' ? 'Video' : 'YouTube',
                    'published' => now()->format('M Y'),
                ];
                $content['items'] = $items;
                $gallerySection->update(['content' => $content]);
            }

            // 2. Also keep Media page in sync if exists
            $targetSection = $request->video_section ?? 'media_other';
            $mediaPage = \App\Models\Page::where('slug', 'media')->first();
            if ($mediaPage) {
                $section = $mediaPage->sections()->where('type', $targetSection)->first()
                    ?? $mediaPage->sections()->where('type', 'media_center')->first();
                if ($section) {
                    $content = $section->content ?? [];
                    $items = $content['items'] ?? [];
                    $items[] = [
                        'title' => $title,
                        'category' => $category,
                        'published' => now()->format('M Y'),
                        'duration' => $videoSource === 'file' ? 'Video' : 'YouTube',
                        'url' => $videoUrl,
                        'video' => $videoUrl,
                    ];
                    $content['items'] = $items;
                    $section->update(['content' => $content]);
                }
            }

            return redirect()->back()->with('success', 'Video added to Gallery successfully.');
        }

        return redirect()->back()->with('error', 'Invalid upload operation.');
    }

    public function destroy(Resource $resource)
    {
        if (file_exists(public_path($resource->file_path))) {
            unlink(public_path($resource->file_path));
        }
        $resource->delete();

        return redirect()->back()->with('success', 'Resource deleted successfully.');
    }

    public function deleteMediaItem($sectionType, $index)
    {
        $section = null;
        if ($sectionType === 'gallery') {
            $section = \App\Models\Section::where('type', 'gallery')->first();
        } else {
            $section = \App\Models\Section::where('type', $sectionType)->first();
            if (!$section) {
                $mediaPage = \App\Models\Page::where('slug', 'media')->first();
                if ($mediaPage) {
                    $section = $mediaPage->sections()->where('type', $sectionType)->first();
                }
            }
        }

        if (!$section) {
            return redirect()->back()->with('error', 'Section not found.');
        }

        $content = $section->content;
        $items = $content['items'] ?? [];

        if (isset($items[$index])) {
            $item = $items[$index];
            if (!empty($item['image']) && str_starts_with($item['image'], 'uploads/') && file_exists(public_path($item['image']))) {
                @unlink(public_path($item['image']));
            }
            if (!empty($item['video']) && str_starts_with($item['video'], 'uploads/') && file_exists(public_path($item['video']))) {
                @unlink(public_path($item['video']));
            }
            if (!empty($item['url']) && str_starts_with($item['url'], 'uploads/') && file_exists(public_path($item['url']))) {
                @unlink(public_path($item['url']));
            }

            unset($items[$index]);
            $content['items'] = array_values($items);
            $section->update(['content' => $content]);

            return redirect()->back()->with('success', 'Media item deleted successfully.');
        }

        return redirect()->back()->with('error', 'Media item not found.');
    }

    // Frontend view
    public function list()
    {
        $page = \App\Models\Page::with('sections')->where('slug', 'resources')->where('is_active', true)->first();
        $categories = Resource::where('is_active', true)->select('category')->distinct()->get();
        $resources = Resource::where('is_active', true)->get();
        return view('resources', compact('resources', 'categories', 'page'));
    }
}
