<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\ProductMedia;
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
        $query = Product::with(['user', 'media'])->where('status', 'Approved');

        if ($request->has('category') && $request->category != '') {
            $query->whereJsonContains('category', $request->category);
        }
        if ($request->has('cluster') && $request->cluster != '') {
            $query->where('cluster', $request->cluster);
        }
        if ($request->has('q') && $request->q != '') {
            $q = $request->q;
            $query->where(function($query) use ($q) {
                $query->where('name', 'like', '%' . $q . '%')
                      ->orWhere('material_composition', 'like', '%' . $q . '%')
                      ->orWhere('technical_specifications', 'like', '%' . $q . '%')
                      ->orWhere('hs_code', 'like', '%' . $q . '%')
                      ->orWhereJsonContains('category', $q);
            });
        }

        $products = $query->latest()->paginate(12);
        return view('catalogue.index', compact('products'));
    }

    public function showCatalogue(Product $product)
    {
        if ($product->status !== 'Approved') {
            abort(404);
        }
        $product->load(['user.exporterProfile', 'media']);

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
        return view('seller.products.create', compact('user', 'companyUser'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|array',
            'category.*' => 'string|max:255',
            'cluster' => 'nullable|string|max:255',
            'contact_information' => 'nullable|string',
            'hs_code' => 'nullable|string|max:255',
            'material_composition' => 'nullable|string',
            'production_capacity' => 'nullable|string|max:255',
            'width_size' => 'nullable|string|max:255',
            'pattern' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'technical_specifications' => 'nullable|string',
            'certifications' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:10000',
            'brochures.*' => 'mimes:pdf,docx,xlsx|max:15000',
            'videos.*' => 'mimes:mp4|max:10240',
        ]);

        $user = auth()->user();
        $companyUser = $user->getCompanyUser();

        $data = $request->only([
            'name', 'category', 'cluster', 'contact_information', 'hs_code', 'material_composition', 
            'production_capacity', 'width_size', 'pattern', 'color', 'technical_specifications', 'certifications'
        ]);

        $data['user_id'] = $companyUser->id;
        $data['created_by_user_id'] = $user->id;

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
        $this->uploadMedia($request, $product);

        return redirect()->route('seller.products.index')->with('success', $msg);
    }

    public function edit(Product $product)
    {
        $user = auth()->user();
        $companyUser = $user->getCompanyUser();
        if ($product->user_id !== $companyUser->id) abort(403);
        return view('seller.products.edit', compact('product', 'user', 'companyUser'));
    }

    public function update(Request $request, Product $product)
    {
        $user = auth()->user();
        $companyUser = $user->getCompanyUser();
        if ($product->user_id !== $companyUser->id) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|array',
            'category.*' => 'string|max:255',
            'cluster' => 'nullable|string|max:255',
            'contact_information' => 'nullable|string',
            'hs_code' => 'nullable|string|max:255',
            'material_composition' => 'nullable|string',
            'production_capacity' => 'nullable|string|max:255',
            'width_size' => 'nullable|string|max:255',
            'pattern' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'technical_specifications' => 'nullable|string',
            'certifications' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
            'brochures.*' => 'mimes:pdf,docx,xlsx|max:5120',
            'videos.*' => 'mimes:mp4|max:10240',
        ]);

        $data = $request->only([
            'name', 'category', 'cluster', 'contact_information', 'hs_code', 'material_composition', 
            'production_capacity', 'width_size', 'pattern', 'color', 'technical_specifications', 'certifications'
        ]);

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

    protected function uploadMedia(Request $request, Product $product)
    {
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('product_media', 'public');
                $product->media()->create(['file_path' => $path, 'media_type' => 'image']);
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
