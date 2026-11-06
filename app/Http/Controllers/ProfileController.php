<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $inquiries = collect();
        $analytics = [
            'total_views' => 0,
            'total_interactions' => 0
        ];

        $companyUser = $user->getCompanyUser();
        $pendingReviewCount = 0;

        if ($user->hasRole('seller') || $user->hasRole('exporter') || $user->isOperator()) {
            $productIds = $companyUser->products->pluck('id');
            $pendingReviewCount = \App\Models\Product::where('user_id', $companyUser->id)
                ->where('status', 'Pending Exporter Review')
                ->count();
            
            $inquiries = \App\Models\ProductInquiry::whereIn('product_id', $productIds)
                ->with('product')
                ->latest()
                ->take(5) // Just recent on dashboard
                ->get();
            
            $analytics['total_views'] = \App\Models\ProductAnalytic::whereIn('product_id', $productIds)
                ->where('type', 'view')
                ->count();
            
            $analytics['total_interactions'] = \App\Models\ProductAnalytic::whereIn('product_id', $productIds)
                ->where('type', 'interaction')
                ->count();

            // Chart data
            $analytics['chartLabels'] = [];
            $analytics['chartViews'] = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $analytics['chartLabels'][] = now()->subDays($i)->format('M d');
                $analytics['chartViews'][] = \App\Models\ProductAnalytic::whereIn('product_id', $productIds)
                    ->where('type', 'view')
                    ->whereDate('created_at', $date)
                    ->count();
            }
        }

        return view('exporter-portal', compact('user', 'companyUser', 'inquiries', 'analytics', 'pendingReviewCount'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('seller')) {
            $request->validate([
                'company_name' => 'required|string|max:255',
                'iec_code' => 'required|string|regex:/^[0-9]{10}$/',
                'gst_no' => ['required', 'string', 'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/'],
                'contact_phone' => 'required|string|max:20',
                'website' => 'nullable|url|max:255',
                'factory_address' => 'required|string',
                'district' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'year_of_establishment' => 'required|integer|min:1800|max:' . date('Y'),
                'textile_segment' => 'required|string|max:255',
                'main_product_hsn' => 'required|string|max:255',
                'installed_capacity' => 'required|string|max:255',
                'capacity_unit' => 'required|string|max:255',
                'monthly_production' => 'required|string|max:255',
                'annual_turnover' => 'required|string|max:255',
                'is_exporter' => 'required|in:Y,N',
                'export_turnover' => $request->is_exporter === 'Y' ? 'required|string|max:255' : 'nullable|string|max:255',
                'major_exporting_countries' => $request->is_exporter === 'Y' ? 'required|string|max:255' : 'nullable|string|max:255',
                'certifications' => 'nullable|string|max:255',
                'export_potential' => 'nullable|string',
                'remarks' => 'nullable|string',
                'profile_description' => 'nullable|string',
                'product_images' => 'nullable|array',
                'product_images.*' => 'file|mimes:jpg,jpeg,png|max:10240',
            ]);

            $profile = $user->exporterProfile;
            
            // Handle multiple product images (append to existing ones if uploaded)
            $productImagesPaths = $profile->product_images ?? [];
            if ($request->hasFile('product_images')) {
                foreach ($request->file('product_images') as $file) {
                    $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                    $productImagesPaths[] = $file->storeAs('product_images', $filename, 'public');
                }
            }

            // If user deletes any product images
            if ($request->has('delete_images')) {
                $deleteImages = $request->input('delete_images');
                $productImagesPaths = array_values(array_filter($productImagesPaths, function($path) use ($deleteImages) {
                    return !in_array($path, $deleteImages);
                }));
                // Optionally delete files from storage
                foreach ($deleteImages as $path) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                }
            }

            $profile->update([
                'company_name' => $request->company_name,
                'iec_code' => $request->iec_code,
                'gst_no' => $request->gst_no,
                'contact_phone' => $request->contact_phone,
                'website' => $request->website,
                'factory_address' => $request->factory_address,
                'district' => $request->district,
                'state' => $request->state,
                'year_of_establishment' => $request->year_of_establishment,
                'textile_segment' => $request->textile_segment,
                'main_product_hsn' => $request->main_product_hsn,
                'installed_capacity' => $request->installed_capacity,
                'capacity_unit' => $request->capacity_unit,
                'monthly_production' => $request->monthly_production,
                'annual_turnover' => $request->annual_turnover,
                'is_exporter' => $request->is_exporter,
                'export_turnover' => $request->is_exporter === 'Y' ? $request->export_turnover : null,
                'major_exporting_countries' => $request->is_exporter === 'Y' ? $request->major_exporting_countries : null,
                'certifications' => $request->certifications,
                'export_potential' => $request->export_potential,
                'remarks' => $request->remarks,
                'profile_description' => $request->profile_description,
                'product_images' => $productImagesPaths,
            ]);
        } elseif ($user->hasRole('buyer')) {
            $request->validate([
                'company_name'       => 'required|string|max:255',
                'contact_phone'      => 'required|string|max:20',
                'address'            => 'required|string',
                'country'            => 'nullable|string|max:255',
                'website'            => 'nullable|url|max:255',
                'profile_description'=> 'nullable|string',
                'sourcing_requirements' => 'nullable|string',
            ]);

            $user->buyerProfile()->update([
                'company_name'          => $request->company_name,
                'contact_phone'         => $request->contact_phone,
                'address'               => $request->address,
                'country'               => $request->country,
                'website'               => $request->website,
                'profile_description'   => $request->profile_description,
                'sourcing_requirements' => $request->sourcing_requirements,
            ]);
        }

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
