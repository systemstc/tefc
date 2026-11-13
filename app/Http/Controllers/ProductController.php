<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\ProductMedia;
use App\Models\ProductAttributeValue;
use App\Models\TextileCategory;
use App\Models\TextileSubcategory;
use App\Models\TextileProduct;
use App\Models\FibreMaster;
use App\Models\YarnMaster;
use App\Models\FabricMaster;
use App\Models\ProcessMaster;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $companyUser = $user->getCompanyUser();
        $products = Product::where('user_id', $companyUser->id)
            ->with(['creator', 'acknowledger', 'media'])
            ->latest()
            ->get();
        $isMainExporter = $user->isMainExporter();
        $pendingAcknowledgmentCount = $products->where('status', 'Pending Exporter Review')->count();

        return view('seller.products.index', compact('products', 'isMainExporter', 'pendingAcknowledgmentCount', 'companyUser'));
    }

    public function catalogue(Request $request)
    {
        $query = Product::with(['user.exporterProfile', 'media', 'textileProduct.subcategory.category', 'attributeValues'])
            ->where('status', 'Approved')
            ->where('is_published', true);

        // 1. Hierarchical Textile Category Filters
        if ($request->filled('category_id')) {
            $query->where('textile_category_id', $request->category_id);
        }
        if ($request->filled('subcategory_id')) {
            $query->where('textile_subcategory_id', $request->subcategory_id);
        }
        if ($request->filled('textile_product_id')) {
            $query->where('textile_product_id', $request->textile_product_id);
        }

        // 2. Technical Attribute Filters
        if ($request->filled('fibre')) {
            $fibre = $request->fibre;
            $query->whereHas('fibres', function($q) use ($fibre) {
                $q->where('attribute_name', 'like', "%{$fibre}%");
            });
        }
        if ($request->filled('fabric')) {
            $fabric = $request->fabric;
            $query->whereHas('fabrics', function($q) use ($fabric) {
                $q->where('attribute_name', 'like', "%{$fabric}%");
            });
        }
        if ($request->filled('process')) {
            $process = $request->process;
            $query->whereHas('processes', function($q) use ($process) {
                $q->where('attribute_name', 'like', "%{$process}%");
            });
        }

        // 3. Legacy Category filter (with taxonomy fallback)
        if ($request->filled('category')) {
            $cat = $request->category;
            $query->where(function($q) use ($cat) {
                $q->whereJsonContains('category', $cat)
                  ->orWhereHas('textileCategory', fn($tc) => $tc->where('name', $cat))
                  ->orWhereHas('textileSubcategory', fn($ts) => $ts->where('name', $cat))
                  ->orWhereHas('textileProduct', fn($tp) => $tp->where('name', $cat));
            });
        }

        // 4. Cluster filter
        if ($request->filled('cluster')) {
            $query->where('cluster', $request->cluster);
        }

        // 5. Keyword search across name, material, specs, HS code, tags & taxonomy
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('material_composition', 'like', "%{$q}%")
                      ->orWhere('technical_specifications', 'like', "%{$q}%")
                      ->orWhere('hs_code', 'like', "%{$q}%")
                      ->orWhere('tags', 'like', "%{$q}%")
                      ->orWhereJsonContains('category', $q)
                      ->orWhereHas('textileProduct', fn($tp) => $tp->where('name', 'like', "%{$q}%"))
                      ->orWhereHas('textileSubcategory', fn($ts) => $ts->where('name', 'like', "%{$q}%"))
                      ->orWhereHas('textileCategory', fn($tc) => $tc->where('name', 'like', "%{$q}%"))
                      ->orWhereHas('attributeValues', fn($av) => $av->where('attribute_name', 'like', "%{$q}%"));
            });
        }

        $products = $query->latest()->paginate(12);

        // Sidebar filters dataset
        $textileCategories = TextileCategory::with(['subcategories' => fn($q) => $q->where('is_active', true)])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $fibreFilterOptions = FibreMaster::where('is_active', true)->orderBy('name')->pluck('name')->unique();
        $fabricFilterOptions = FabricMaster::where('is_active', true)->orderBy('name')->pluck('name')->unique();
        $processFilterOptions = ProcessMaster::where('is_active', true)->orderBy('name')->pluck('name')->unique();

        return view('catalogue.index', compact('products', 'textileCategories', 'fibreFilterOptions', 'fabricFilterOptions', 'processFilterOptions'));
    }

    public function showCatalogue(Product $product)
    {
        if ($product->status !== 'Approved') {
            abort(404);
        }
        $product->load(['user.exporterProfile', 'media', 'textileProduct.subcategory.category', 'attributeValues']);

        // Record View Analytic
        \App\Models\ProductAnalytic::create([
            'product_id' => $product->id,
            'session_id' => session()->getId(),
            'user_id' => auth()->id(),
            'type' => 'view',
        ]);

        // Fetch related products (matching at least one category)
        $relatedProducts = Product::with(['user.exporterProfile', 'media'])
            ->where('status', 'Approved')
            ->where('id', '!=', $product->id)
            ->where(function($q) use ($product) {
                if (is_array($product->category)) {
                    foreach ($product->category as $cat) {
                        $q->orWhereJsonContains('category', $cat);
                    }
                }
            })
            ->limit(4)
            ->get();

        return view('catalogue.show', compact('product', 'relatedProducts'));
    }

    public function recordInteraction(Request $request, Product $product)
    {
        $request->validate([
            'interaction_type' => 'required|string',
        ]);

        \App\Models\ProductAnalytic::create([
            'product_id' => $product->id,
            'session_id' => session()->getId(),
            'user_id' => auth()->id(),
            'type' => 'interaction',
            'interaction_type' => $request->interaction_type,
            'metadata' => $request->metadata ?? [],
        ]);

        return response()->json(['status' => 'success']);
    }

    public function storeInquiry(Request $request, Product $product)
    {
        $request->validate([
            'buyer_name' => 'required|string|max:255',
            'buyer_email' => 'required|email|max:255',
            'buyer_phone' => 'nullable|string|max:20',
            'quantity_required' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'material' => 'required|string|max:255',
            'pattern' => 'required|string|max:255',
            'width_size' => 'required|string|max:255',
            'additional_requirements' => 'nullable|string',
        ]);

        $inquiry = \App\Models\ProductInquiry::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'buyer_name' => $request->buyer_name,
            'buyer_email' => $request->buyer_email,
            'buyer_phone' => $request->buyer_phone,
            'quantity_required' => $request->quantity_required,
            'category' => $request->category,
            'material' => $request->material,
            'pattern' => $request->pattern,
            'width_size' => $request->width_size,
            'additional_requirements' => $request->additional_requirements,
            'message' => $request->additional_requirements ?? 'Product-specific inquiry.',
        ]);

        // Here you would typically send an email notification to the seller
        // Mail::to($product->user->email)->send(new ProductInquiryNotification($product, $request->all()));

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Your inquiry has been sent to the exporter successfully.',
                'inquiry' => $inquiry
            ]);
        }

        return back()->with('success', 'Your inquiry has been sent to the exporter successfully.');
    }

    public function storeGeneralInquiry(Request $request)
    {
        $request->validate([
            'buyer_name' => 'required|string|max:255',
            'buyer_email' => 'required|email|max:255',
            'buyer_phone' => 'nullable|string|max:20',
            'quantity_required' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'material' => 'required|string|max:255',
            'pattern' => 'required|string|max:255',
            'width_size' => 'required|string|max:255',
            'additional_requirements' => 'nullable|string',
        ]);

        $inquiry = \App\Models\ProductInquiry::create([
            'product_id' => null, // General / Global enquiry
            'user_id' => auth()->id(),
            'buyer_name' => $request->buyer_name,
            'buyer_email' => $request->buyer_email,
            'buyer_phone' => $request->buyer_phone,
            'quantity_required' => $request->quantity_required,
            'category' => $request->category,
            'material' => $request->material,
            'pattern' => $request->pattern,
            'width_size' => $request->width_size,
            'additional_requirements' => $request->additional_requirements,
            'message' => $request->additional_requirements ?? 'General trade inquiry.',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Your trade enquiry has been submitted successfully.',
                'inquiry' => $inquiry
            ]);
        }

        return back()->with('success', 'Your trade enquiry has been submitted successfully.');
    }

    public function sellerInquiries()
    {
        $user = auth()->user();
        if (!$user->hasRole('seller')) abort(403);

        $productIds = $user->products->pluck('id');
        $inquiries = \App\Models\ProductInquiry::whereIn('product_id', $productIds)
            ->with('product')
            ->latest()
            ->paginate(15);

        return view('seller.inquiries.index', compact('inquiries'));
    }

    public function create()
    {
        $user = auth()->user();
        $companyUser = $user->getCompanyUser();
        $textileCategories = TextileCategory::where('is_active', true)->orderBy('sort_order')->get();
        return view('seller.products.create', compact('user', 'companyUser', 'textileCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'textile_product_id' => 'nullable|exists:textile_products,id',
            'category' => 'nullable|array',
            'category.*' => 'string|max:255',
            'cluster' => 'nullable|string|max:255',
            'contact_information' => 'nullable|string',
            'hs_code' => 'nullable|string|max:255',
            'material_composition' => 'nullable|string',
            'production_capacity' => 'nullable|string|max:255',
            'width_size' => 'nullable|string|max:255',
            'moq' => 'nullable|string|max:255',
            'unit_of_measurement' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'pattern' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'technical_specifications' => 'nullable|string',
            'certifications' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10000',
            'brochures.*' => 'mimes:pdf,docx,xlsx|max:15000',
            'videos.*' => 'mimes:mp4|max:10240',
        ]);

        $user = auth()->user();
        $companyUser = $user->getCompanyUser();

        $data = $request->only([
            'name', 'cluster', 'contact_information', 'hs_code', 'material_composition', 
            'production_capacity', 'width_size', 'moq', 'unit_of_measurement', 'tags',
            'pattern', 'color', 'technical_specifications', 'certifications'
        ]);

        $data['user_id'] = $companyUser->id;
        $data['created_by_user_id'] = $user->id;
        $data['is_published'] = $request->boolean('is_published', true);

        // Resolve Textile Classification Hierarchy
        if ($request->filled('textile_product_id')) {
            $tp = TextileProduct::with('subcategory.category')->find($request->textile_product_id);
            if ($tp) {
                $data['textile_product_id'] = $tp->id;
                $data['textile_subcategory_id'] = $tp->subcategory_id;
                $data['textile_category_id'] = $tp->subcategory?->category_id;

                // Sync category array for legacy backward compatibility
                $legacyCats = $request->input('category', []);
                if (empty($legacyCats)) {
                    $legacyCats = [
                        $tp->subcategory?->category?->name ?? 'Textiles',
                        $tp->subcategory?->name ?? '',
                        $tp->name
                    ];
                }
                $data['category'] = array_values(array_filter($legacyCats));

                if (empty($data['hs_code']) && !empty($tp->default_hs_code)) {
                    $data['hs_code'] = $tp->default_hs_code;
                }
            }
        } else {
            $data['category'] = $request->input('category', ['Textiles']);
        }

        if ($user->isOperator()) {
            // Operator data entry: requires Exporter Higher Authority acknowledgment
            $data['status'] = 'Pending Exporter Review';
            $data['acknowledged_at'] = null;
            $data['acknowledged_by'] = null;
            $msg = 'Product draft submitted by Data Entry Operator. It is awaiting review and acknowledgment by the Exporter Higher Authority before being published.';
        } else {
            // Main Exporter higher authority: auto-acknowledges and publishes directly
            $data['status'] = 'Approved';
            $data['acknowledged_at'] = now();
            $data['acknowledged_by'] = $user->id;
            $msg = 'Product uploaded and published directly to the TEFC Public Catalogue!';
        }

        $product = Product::create($data);
        $this->syncAttributeValues($request, $product);
        $this->uploadMedia($request, $product);

        return redirect()->route('seller.products.index')->with('success', $msg);
    }

    public function edit(Product $product)
    {
        $user = auth()->user();
        $companyUser = $user->getCompanyUser();
        if ($product->user_id !== $companyUser->id) abort(403);
        $product->load(['textileProduct.subcategory.category', 'attributeValues', 'media']);
        $textileCategories = TextileCategory::where('is_active', true)->orderBy('sort_order')->get();
        return view('seller.products.edit', compact('product', 'user', 'companyUser', 'textileCategories'));
    }

    public function update(Request $request, Product $product)
    {
        $user = auth()->user();
        $companyUser = $user->getCompanyUser();
        if ($product->user_id !== $companyUser->id) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'textile_product_id' => 'nullable|exists:textile_products,id',
            'category' => 'nullable|array',
            'category.*' => 'string|max:255',
            'cluster' => 'nullable|string|max:255',
            'contact_information' => 'nullable|string',
            'hs_code' => 'nullable|string|max:255',
            'material_composition' => 'nullable|string',
            'production_capacity' => 'nullable|string|max:255',
            'width_size' => 'nullable|string|max:255',
            'moq' => 'nullable|string|max:255',
            'unit_of_measurement' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'pattern' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'technical_specifications' => 'nullable|string',
            'certifications' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10000',
            'brochures.*' => 'mimes:pdf,docx,xlsx|max:15000',
            'videos.*' => 'mimes:mp4|max:10240',
        ]);

        $data = $request->only([
            'name', 'cluster', 'contact_information', 'hs_code', 'material_composition', 
            'production_capacity', 'width_size', 'moq', 'unit_of_measurement', 'tags',
            'pattern', 'color', 'technical_specifications', 'certifications'
        ]);

        $data['is_published'] = $request->boolean('is_published', true);

        // Resolve Textile Classification Hierarchy
        if ($request->filled('textile_product_id')) {
            $tp = TextileProduct::with('subcategory.category')->find($request->textile_product_id);
            if ($tp) {
                $data['textile_product_id'] = $tp->id;
                $data['textile_subcategory_id'] = $tp->subcategory_id;
                $data['textile_category_id'] = $tp->subcategory?->category_id;

                $legacyCats = $request->input('category', []);
                if (empty($legacyCats)) {
                    $legacyCats = [
                        $tp->subcategory?->category?->name ?? 'Textiles',
                        $tp->subcategory?->name ?? '',
                        $tp->name
                    ];
                }
                $data['category'] = array_values(array_filter($legacyCats));

                if (empty($data['hs_code']) && !empty($tp->default_hs_code)) {
                    $data['hs_code'] = $tp->default_hs_code;
                }
            }
        } elseif ($request->has('category')) {
            $data['category'] = $request->input('category');
        }

        if ($user->isOperator()) {
            $data['status'] = 'Pending Exporter Review';
            $data['acknowledged_at'] = null;
            $data['acknowledged_by'] = null;
            $msg = 'Product updated and re-submitted for Exporter Higher Authority review & acknowledgment.';
        } else {
            $data['status'] = 'Approved';
            $data['acknowledged_at'] = now();
            $data['acknowledged_by'] = $user->id;
            $msg = 'Product updated and published live on the TEFC Public Catalogue.';
        }

        $product->update($data);
        $this->syncAttributeValues($request, $product);
        $this->uploadMedia($request, $product);

        return redirect()->route('seller.products.index')->with('success', $msg);
    }
    
    public function destroy(Product $product)
    {
        $user = auth()->user();
        $companyUser = $user->getCompanyUser();
        if ($product->user_id !== $companyUser->id) abort(403);
        $product->delete();
        return redirect()->route('seller.products.index')->with('success', 'Product deleted.');
    }

    public function acknowledge(Product $product)
    {
        $user = auth()->user();
        if (!$user->isMainExporter() || $product->user_id !== $user->id) {
            abort(403, 'Unauthorized. Only the verified Exporter Higher Authority can acknowledge and publish products.');
        }

        $product->update([
            'status' => 'Approved',
            'acknowledged_at' => now(),
            'acknowledged_by' => $user->id,
            'moderation_notes' => null,
        ]);

        return redirect()->route('seller.products.index')->with('success', "Product \"{$product->name}\" verified and acknowledged! It is now published live on the TEFC Public Catalogue without needing HO approval.");
    }

    public function requestRevision(Request $request, Product $product)
    {
        $user = auth()->user();
        if (!$user->isMainExporter() || $product->user_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'moderation_notes' => 'required|string|max:500',
        ]);

        $product->update([
            'status' => 'Revision Requested',
            'moderation_notes' => $request->moderation_notes,
        ]);

        return redirect()->route('seller.products.index')->with('info', "Revision request sent for \"{$product->name}\". Operator can now update specifications.");
    }

    public function destroyMedia(Product $product, \App\Models\ProductMedia $media)
    {
        $user = auth()->user();
        $companyUser = $user->getCompanyUser();
        if ($product->user_id !== $companyUser->id || $media->product_id !== $product->id) {
            abort(403);
        }

        $wasPrimary = $media->is_primary;
        \Illuminate\Support\Facades\Storage::disk('public')->delete($media->file_path);
        $media->delete();

        if ($wasPrimary) {
            $first = $product->media()->where('media_type', 'image')->first();
            if ($first) {
                $first->update(['is_primary' => true]);
            }
        }

        return back()->with('success', 'Media file removed successfully.');
    }

    public function setPrimaryMedia(Product $product, \App\Models\ProductMedia $media)
    {
        $user = auth()->user();
        $companyUser = $user->getCompanyUser();
        if ($product->user_id !== $companyUser->id || $media->product_id !== $product->id) {
            abort(403);
        }

        $product->media()->where('media_type', 'image')->update(['is_primary' => false]);
        $media->update(['is_primary' => true]);

        return back()->with('success', 'Primary product image updated.');
    }

    protected function syncAttributeValues(Request $request, Product $product): void
    {
        // Delete previous attribute values
        $product->attributeValues()->delete();

        $sort = 0;

        // 1. Fibres
        if ($request->has('fibres') && is_array($request->fibres)) {
            foreach ($request->fibres as $f) {
                if (empty($f['name']) && empty($f['master_id'])) continue;
                $product->attributeValues()->create([
                    'attribute_type' => 'fibre',
                    'master_id' => !empty($f['master_id']) ? (int)$f['master_id'] : null,
                    'attribute_name' => $f['name'] ?? 'Fibre',
                    'attribute_value' => !empty($f['percentage']) ? ($f['percentage'] . '%') : ($f['value'] ?? null),
                    'percentage' => !empty($f['percentage']) ? (float)$f['percentage'] : null,
                    'sort_order' => $sort++,
                ]);
            }
        }

        // 2. Yarns
        if ($request->has('yarns') && is_array($request->yarns)) {
            foreach ($request->yarns as $y) {
                if (empty($y['name']) && empty($y['master_id'])) continue;
                $product->attributeValues()->create([
                    'attribute_type' => 'yarn',
                    'master_id' => !empty($y['master_id']) ? (int)$y['master_id'] : null,
                    'attribute_name' => $y['name'] ?? 'Yarn',
                    'attribute_value' => $y['value'] ?? null,
                    'percentage' => null,
                    'sort_order' => $sort++,
                ]);
            }
        }

        // 3. Fabrics
        if ($request->has('fabrics') && is_array($request->fabrics)) {
            foreach ($request->fabrics as $fb) {
                if (empty($fb['name']) && empty($fb['master_id'])) continue;
                $product->attributeValues()->create([
                    'attribute_type' => 'fabric',
                    'master_id' => !empty($fb['master_id']) ? (int)$fb['master_id'] : null,
                    'attribute_name' => $fb['name'] ?? 'Fabric',
                    'attribute_value' => $fb['value'] ?? null,
                    'percentage' => null,
                    'sort_order' => $sort++,
                ]);
            }
        }

        // 4. Processes
        if ($request->has('processes') && is_array($request->processes)) {
            foreach ($request->processes as $p) {
                if (empty($p['name']) && empty($p['master_id'])) continue;
                $product->attributeValues()->create([
                    'attribute_type' => 'process',
                    'master_id' => !empty($p['master_id']) ? (int)$p['master_id'] : null,
                    'attribute_name' => $p['name'] ?? 'Process',
                    'attribute_value' => $p['value'] ?? null,
                    'percentage' => null,
                    'sort_order' => $sort++,
                ]);
            }
        }
    }

    protected function uploadMedia(Request $request, Product $product)
    {
        $hasPrimary = $product->media()->where('is_primary', true)->exists();

        if ($request->hasFile('images')) {
            $isFirst = !$hasPrimary;
            foreach ($request->file('images') as $file) {
                $path = $file->store('product_media', 'public');
                $product->media()->create([
                    'file_path' => $path,
                    'media_type' => 'image',
                    'is_primary' => $isFirst,
                ]);
                $isFirst = false;
            }
        }
        if ($request->hasFile('brochures')) {
            foreach ($request->file('brochures') as $file) {
                $path = $file->store('product_media', 'public');
                $product->media()->create(['file_path' => $path, 'media_type' => 'brochure']);
            }
        }
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $file) {
                $path = $file->store('product_media', 'public');
                $product->media()->create(['file_path' => $path, 'media_type' => 'video']);
            }
        }
    }
}
