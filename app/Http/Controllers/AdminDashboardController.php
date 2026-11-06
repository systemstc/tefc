<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Page;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\TefcCentre;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'pages' => Page::count(),
            'buyers' => User::whereHas('roles', fn($q) => $q->where('slug', 'buyer'))->count(),
            'sellers' => User::whereHas('roles', fn($q) => $q->whereIn('slug', ['seller', 'exporter']))->count(),
            'products' => \App\Models\Product::count(),
            'inquiries' => \App\Models\ProductInquiry::count(),
            'total_views' => \App\Models\ProductAnalytic::where('type', 'view')->count(),
            'total_interactions' => \App\Models\ProductAnalytic::where('type', 'interaction')->count(),
        ];

        // Prepare chart data (e.g., views over last 7 days)
        $chartData = [
            'labels' => [],
            'views' => []
        ];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartData['labels'][] = now()->subDays($i)->format('M d');
            $chartData['views'][] = \App\Models\ProductAnalytic::where('type', 'view')
                ->whereDate('created_at', $date)
                ->count();
        }

        // Aggregate stats across all 6 TEFC Centres for HO Master View
        $centreStats = [];
        foreach (TefcCentre::all() as $code => $centre) {
            $baseQ = User::where('tefc_centre', $code)
                ->whereHas('roles', fn($q) => $q->whereIn('slug', ['seller', 'exporter']));

            $centreStats[$code] = [
                'centre' => $centre,
                'total_applications' => (clone $baseQ)->count(),
                'pending' => (clone $baseQ)->where('status', 'pending')->count(),
                'approved' => (clone $baseQ)->where('status', 'active')->count(),
                'rejected' => (clone $baseQ)->where('status', 'rejected')->count(),
                'products' => \App\Models\Product::whereHas('user', fn($q) => $q->where('tefc_centre', $code))->count(),
            ];
        }

        return view('dashboard', compact('stats', 'chartData', 'centreStats'));
    }

    /**
     * Allow HO Admin to directly switch/login into a TEFC Centre view.
     */
    public function switchToCentre(Request $request, string $centreCode)
    {
        $centre = TefcCentre::find($centreCode);
        if (!$centre) {
            return back()->with('error', "TEFC Centre [{$centreCode}] is not valid.");
        }

        session(['viewing_centre' => $centreCode]);

        return redirect()->route('center.dashboard')->with('success', "Logged into {$centre['name']} TEFC Centre view as Head Office.");
    }

    /**
     * Exit Centre view and return to HO Master Dashboard.
     */
    public function exitCentreSwitch()
    {
        session()->forget('viewing_centre');
        return redirect()->route('dashboard')->with('success', 'Returned to Head Office Master Dashboard.');
    }

    public function users()
    {
        $users = User::with(['roles', 'exporterProfile', 'buyerProfile'])->get();
        return view('admin.users.index', compact('users'));
    }

    public function updateUserStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:pending,active,rejected'
        ]);

        $user->update(['status' => $request->status]);

        return redirect()->back()->with('success', "User status updated to {$request->status} successfully.");
    }

    public function cms()
    {
        $pages = Page::with('sections')->get();
        return view('admin.cms.index', compact('pages'));
    }

    public function editSection(Section $section)
    {
        return view('admin.cms.edit_section', compact('section'));
    }

    public function updateSection(Request $request, Section $section)
    {
        $content = $section->content;

        // Handle Banner Upload (Single)
        if ($request->hasFile('background_image')) {
            $file = $request->file('background_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $filename);
            $content['background_image'] = 'images/' . $filename;
        }

        // Handle Multiple Slider Images
        if ($request->hasFile('slider_images')) {
            $uploadedImages = [];
            foreach ($request->file('slider_images') as $file) {
                if ($file && $file->isValid()) {
                    $filename = time() . '_' . rand(100, 999) . '_' . $file->getClientOriginalName();
                    $file->move(public_path('images/slider'), $filename);
                    $uploadedImages[] = 'images/slider/' . $filename;
                }
            }
            if (!empty($uploadedImages)) {
                $existing = $request->input('existing_slider_images', []);
                $content['images'] = array_values(array_filter(array_merge(is_array($existing) ? $existing : [], $uploadedImages)));
            }
        } elseif ($request->has('existing_slider_images')) {
            $content['images'] = array_values(array_filter((array) $request->input('existing_slider_images')));
        }

        // Handle Gallery Image Uploads
        if ($request->hasFile('gallery_images')) {
            $targetDir = public_path('images/gallery');
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $existingItems = $content['items'] ?? [];
            foreach ($request->file('gallery_images') as $gFile) {
                if ($gFile && $gFile->isValid()) {
                    $filename = 'gallery_' . time() . '_' . rand(100, 999) . '.' . $gFile->getClientOriginalExtension();
                    $gFile->move($targetDir, $filename);
                    $existingItems[] = [
                        'image' => 'images/gallery/' . $filename,
                        'title' => 'Cluster Exhibition & Workshop',
                        'category' => 'Clusters',
                        'caption' => 'Facilitation showcase and capacity building session by TEFC.',
                    ];
                }
            }
            $content['items'] = $existingItems;
        }

        // Handle Key People Uploads for about_intro section
        if ($section->type === 'about_intro' && $request->has('people_name')) {
            $peopleNames = $request->input('people_name', []);
            $peopleDesignations = $request->input('people_designation', []);
            $existingImages = $request->input('people_existing_image', []);
            $peopleLinks = $request->input('people_link', []);

            $people = [];
            $targetDir = public_path('images/people');
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            foreach ($peopleNames as $index => $name) {
                if (trim($name) === '' && empty($peopleDesignations[$index])) {
                    continue;
                }

                $imagePath = $existingImages[$index] ?? 'images/people/neelam_shami_rao.png';

                // Check for uploaded file: people_image_{$index} or people_image.{$index}
                if ($request->hasFile("people_image_{$index}")) {
                    $pFile = $request->file("people_image_{$index}");
                    if ($pFile && $pFile->isValid()) {
                        $pFilename = 'person_' . time() . '_' . $index . '_' . rand(100, 999) . '.' . $pFile->getClientOriginalExtension();
                        $pFile->move($targetDir, $pFilename);
                        $imagePath = 'images/people/' . $pFilename;
                    }
                } elseif ($request->hasFile("people_image.{$index}")) {
                    $pFile = $request->file("people_image.{$index}");
                    if ($pFile && $pFile->isValid()) {
                        $pFilename = 'person_' . time() . '_' . $index . '_' . rand(100, 999) . '.' . $pFile->getClientOriginalExtension();
                        $pFile->move($targetDir, $pFilename);
                        $imagePath = 'images/people/' . $pFilename;
                    }
                }

                $people[] = [
                    'name' => $name,
                    'designation' => $peopleDesignations[$index] ?? '',
                    'image' => $imagePath,
                    'link' => $peopleLinks[$index] ?? '',
                ];
            }

            $content['people'] = $people;
        }

        // Handle ALL JSON array updates (prefixed with json_)
        foreach ($request->all() as $paramKey => $val) {
            if (str_starts_with($paramKey, 'json_')) {
                $targetKey = substr($paramKey, 5);
                if (is_string($val)) {
                    $decoded = json_decode($val, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $content[$targetKey] = $decoded;
                    }
                } elseif (is_array($val)) {
                    $content[$targetKey] = $val;
                }
            }
        }

        // Exclude files, tokens, and already-handled keys
        $excludedKeys = [
            '_token', 'background_image', 'slider_images', 'existing_slider_images', 'gallery_images', 'title',
            'people_name', 'people_designation', 'people_existing_image', 'people_link', 'people_image',
            'show_map_card_submitted', 'show_map_card'
        ];

        // Handle other text or array fields safely
        foreach ($request->except($excludedKeys) as $key => $value) {
            if (str_starts_with($key, 'json_') || str_starts_with($key, 'people_image_')) {
                continue;
            }
            if (is_array($value)) {
                $content[$key] = $value;
            } elseif (is_string($value)) {
                $trimmed = trim($value);
                if ((str_starts_with($trimmed, '{') && str_ends_with($trimmed, '}')) ||
                    (str_starts_with($trimmed, '[') && str_ends_with($trimmed, ']'))) {
                    $decoded = json_decode($value, true);
                    if (is_array($decoded) && json_last_error() === JSON_ERROR_NONE) {
                        $content[$key] = $decoded;
                        continue;
                    }
                }
                $content[$key] = $value;
            } else {
                $content[$key] = $value;
            }
        }

        if ($section->type === 'clusters_grid') {
            if ($request->has('show_map_card_submitted')) {
                $content['show_map_card'] = $request->boolean('show_map_card');
            } elseif ($request->has('show_map_card')) {
                $content['show_map_card'] = $request->boolean('show_map_card');
            }
        }

        $updateData = [
            'title' => $request->filled('title') ? $request->title : null,
            'content' => $content
        ];

        if ($request->has('is_active_submitted')) {
            $updateData['is_active'] = $request->boolean('is_active');
        } elseif ($request->has('is_active')) {
            $updateData['is_active'] = $request->boolean('is_active');
        }

        $section->update($updateData);

        Cache::forget('page_' . $section->page->slug);

        return redirect()->route('admin.cms')->with('success', 'Section updated successfully');
    }

    public function toggleSectionStatus(Section $section)
    {
        $section->is_active = !$section->is_active;
        $section->save();

        Cache::forget('page_' . $section->page->slug);

        $stateText = $section->is_active ? 'Activated (Visible on live portal)' : 'Inactivated (Hidden from live portal)';
        $title = $section->title ?: ucwords(str_replace('_', ' ', $section->type));

        return redirect()->back()->with('success', "Section '{$title}' is now {$stateText}.");
    }

    public function toggleMapCardStatus(Request $request, Section $section)
    {
        $content = $section->content ?? [];

        if ($request->has('state')) {
            $content['show_map_card'] = $request->boolean('state');
        } else {
            $content['show_map_card'] = empty($content['show_map_card']);
        }

        $section->content = $content;
        $section->save();

        Cache::forget('page_' . $section->page->slug);
        Cache::flush();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'show_map_card' => (bool)$content['show_map_card'],
                'message' => !empty($content['show_map_card'])
                    ? 'Interactive India Map Card is now ACTIVE and visible on the homepage.'
                    : 'Interactive India Map Card is now INACTIVE and hidden from the homepage.'
            ]);
        }

        $statusLabel = !empty($content['show_map_card']) ? 'Activated (Visible on homepage)' : 'Inactivated (Hidden from homepage)';
        return redirect()->back()->with('success', "Interactive India Map Card is now {$statusLabel}.");
    }

    public function uploadClusterImage(Request $request)
    {
        return $this->uploadCmsFile($request);
    }

    public function uploadCmsFile(Request $request)
    {
        $request->validate([
            'file' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,svg,pdf,doc,docx,xls,xlsx,mp4,webm|max:20480',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:10240',
        ]);

        $uploadedFile = $request->file('file') ?? $request->file('image');
        if (!$uploadedFile) {
            return response()->json(['success' => false, 'message' => 'No file was provided for upload.'], 422);
        }

        $extension = strtolower($uploadedFile->getClientOriginalExtension());
        $isImage = in_array($extension, ['jpeg', 'jpg', 'png', 'gif', 'webp', 'svg']);

        $subfolder = $isImage ? 'images/cms' : 'uploads/documents';
        $targetDir = public_path($subfolder);
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $cleanOrigName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $uploadedFile->getClientOriginalName());
        $filename = time() . '_' . uniqid() . '_' . $cleanOrigName;
        $uploadedFile->move($targetDir, $filename);

        $relPath = $subfolder . '/' . $filename;
        $url = asset($relPath);

        return response()->json([
            'success' => true,
            'url' => $url,
            'path' => $relPath,
            'filename' => $filename,
            'is_image' => $isImage,
            'extension' => $extension,
            'size' => file_exists(public_path($relPath)) ? filesize(public_path($relPath)) : null,
        ]);
    }

    public function settings()
    {
        $settings = Setting::all();
        return view('admin.settings.index', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }
        
        Cache::flush(); // Clear all CMS related caches

        return redirect()->back()->with('success', 'Global settings updated successfully.');
    }

    public function menus()
    {
        $menus = Menu::with('items')->get();
        $pages = Page::all();
        return view('admin.menus.index', compact('menus', 'pages'));
    }

    public function updateMenu(Request $request, Menu $menu)
    {
        // For simplicity, we'll allow updating labels and orders
        // A more advanced builder would handle nested items and adding/deleting
        foreach ($request->items as $id => $data) {
            MenuItem::where('id', $id)->update([
                'label' => $data['label'],
                'url' => $data['url'],
                'order' => $data['order']
            ]);
        }

        Cache::flush();

        return redirect()->back()->with('success', 'Navigation menu updated successfully.');
    }
}
