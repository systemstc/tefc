<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\ExporterProfile;
use App\Models\BuyerProfile;
use App\Services\SwipeGstService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm(Request $request)
    {
        $previous = url()->previous();
        if ($request->query('redirect') === 'back' && $previous && !str_contains($previous, '/register') && !str_contains($previous, '/login') && !str_contains($previous, '/admin-login')) {
            session(['url.intended' => $previous]);
        }
        $eligibleDistricts = \App\Models\TefcCentre::getEligibleDistrictsGrouped();
        $centres = \App\Models\TefcCentre::all();
        return view('auth.register', compact('eligibleDistricts', 'centres'));
    }

    public function gstLookup(Request $request, SwipeGstService $gstService)
    {
        $gstNo = strtoupper(trim($request->query('gst_no') ?? ''));
        $applicantName = trim($request->query('applicant_name') ?? '');
        $applicantEmail = trim($request->query('applicant_email') ?? '');
        $applicantDesignation = trim($request->query('applicant_designation') ?? '');

        if (empty($gstNo)) {
            return response()->json([
                'success' => false,
                'message' => 'GSTIN number is required.'
            ], 422);
        }

        $result = $gstService->fetchGstinDetails($gstNo);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Invalid GSTIN or verification failed.'
            ], 422);
        }

        $gstData = $result['data'];

        // Perform cross-authentication against Higher Authority credentials
        $ownership = null;
        if (!empty($applicantName) || !empty($applicantEmail) || !empty($applicantDesignation)) {
            $ownership = $gstService->authenticateOwnership($applicantName, $gstData, $applicantEmail, $applicantDesignation);
        }

        return response()->json([
            'success' => true,
            'is_live' => $result['is_live'] ?? false,
            'simulated_notice' => $result['simulated_notice'] ?? null,
            'data' => $gstData,
            'ownership' => $ownership,
        ]);
    }

    public function register(Request $request, SwipeGstService $gstService)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:buyer,seller,exporter',
        ];

        $isExporter = in_array($request->role, ['seller', 'exporter']);

        if ($isExporter) {
            $rules['designation'] = 'required|string|max:255'; // Higher Authority / Authorized Signatory designation
            $rules['gst_no'] = ['required', 'string', 'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[0-9A-Z]{3}$/'];
            $rules['iec_no'] = ['nullable', 'string', 'regex:/^[0-9]{10}$/'];
            $rules['company_name'] = 'required|string|max:255';
            $rules['certificate'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:10240';
            $rules['authority_declaration'] = $request->has('authority_declaration') ? 'accepted' : 'nullable';
            
            // Location & cluster validation
            $rules['contact_phone'] = 'required|string|max:20';
            $rules['factory_address'] = 'required|string';
            $rules['district'] = 'required|string|max:255';
            $rules['state'] = 'required|string|max:255';
            $rules['year_of_establishment'] = 'required|integer|min:1800|max:' . date('Y');
            $rules['textile_segment'] = 'required|string|max:255';
            $rules['textile_segment_other'] = $request->textile_segment === 'Other' ? 'required|string|max:255' : 'nullable|string|max:255';
            $rules['main_product_hsn'] = 'required|string|max:255';
            $rules['installed_capacity'] = 'required|string|max:255';
            $rules['major_machineries'] = 'required|string';
            $rules['capacity_unit'] = 'required|string|max:255';
            $rules['monthly_production'] = 'required|string|max:255';
            $rules['annual_turnover'] = 'required|string|max:255';
            $rules['is_exporter'] = 'required|in:Y,N';
            
            if ($request->is_exporter === 'Y') {
                $rules['export_turnover'] = 'required|string|max:255';
                $rules['major_exporting_countries'] = 'required|string|max:255';
            } else {
                $rules['export_turnover'] = 'nullable|string|max:255';
                $rules['major_exporting_countries'] = 'nullable|string|max:255';
            }
            
            $rules['certifications'] = 'nullable|string|max:255';
            $rules['product_images'] = 'nullable|array';
            $rules['product_images.*'] = 'file|mimes:jpg,jpeg,png|max:10240';
        }

        if ($request->role === 'buyer') {
            $rules['company_name']   = 'required|string|max:255';
            $rules['contact_phone']  = 'required|string|max:20';
            $rules['address']        = 'required|string';
            $rules['website']        = 'nullable|url|max:255';
            $rules['country']        = 'nullable|string|max:255';
            $rules['export_potential'] = 'nullable|string';
            $rules['remarks'] = 'nullable|string';
        }

        if ($request->filled('website')) {
            $website = trim($request->website);
            if (!preg_match('~^https?://~i', $website)) {
                $website = 'https://' . $website;
            }
            $request->merge(['website' => $website]);
        }

        $request->validate($rules);

        // Strict TEFC Centre jurisdiction check for Exporter registrations
        $assignedCentre = null;
        $gstData = null;
        $ownership = null;

        if ($isExporter) {
            $centre = \App\Models\TefcCentre::findCentreByLocation($request->state, $request->district);
            if (!$centre) {
                return back()->withInput()->withErrors([
                    'district' => 'TEFC Centre facilitation is currently not available for ' . $request->district . ' (' . $request->state . '). Exporter registration is limited to designated cluster districts.'
                ]);
            }
            $assignedCentre = $centre['code'];

            // Server-side GST Verification via SwipeGstService
            $gstResult = $gstService->fetchGstinDetails($request->gst_no);
            if ($gstResult['success']) {
                $gstData = $gstResult['data'];
                if (isset($gstData['status']) && strtolower($gstData['status']) === 'cancelled') {
                    return back()->withInput()->withErrors([
                        'gst_no' => "GSTIN {$request->gst_no} is marked as CANCELLED in GST records. Only active registrations can be accepted."
                    ]);
                }
                // Verify applicant ownership vs GST promoters/signatories/constitution/domain
                $ownership = $gstService->authenticateOwnership($request->name, $gstData, $request->email, $request->designation);
            } else {
                if ($gstService->hasToken()) {
                    return back()->withInput()->withErrors([
                        'gst_no' => $gstResult['message'] ?? 'GSTIN could not be verified in official records.'
                    ]);
                }
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'pending', // Enforce approval workflow
            'tefc_centre' => $assignedCentre,
            'designation' => $isExporter ? $request->designation : ($request->role === 'buyer' ? 'Buyer Representative' : 'Authorized Signatory'),
            'phone' => $request->contact_phone,
        ]);

        // Assign Role
        $roleSlug = $request->role;
        if ($roleSlug === 'seller') {
            $roleSlug = 'exporter';
        }
        $role = Role::where('slug', $roleSlug)->first() ?: Role::where('slug', 'seller')->first();
        if ($role) {
            $user->roles()->attach($role);
        }

        // Create specific profiles
        if ($isExporter) {
            $certificatePath = null;
            if ($request->hasFile('certificate')) {
                $file = $request->file('certificate');
                $filename = time() . '_' . $file->getClientOriginalName();
                $certificatePath = $file->storeAs('certificates', $filename, 'public');
            }

            // Handle multiple product images
            $productImagesPaths = [];
            if ($request->hasFile('product_images')) {
                foreach ($request->file('product_images') as $file) {
                    $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                    $productImagesPaths[] = $file->storeAs('product_images', $filename, 'public');
                }
            }

            ExporterProfile::create([
                'user_id' => $user->id,
                'tefc_centre' => $assignedCentre,
                'company_name' => $request->company_name,
                'iec_code' => $request->iec_no,
                'gst_no' => $request->gst_no,
                'contact_phone' => $request->contact_phone,
                'website' => $request->website,
                'certificate_path' => $certificatePath,
                
                'factory_address' => $request->factory_address,
                'district' => $request->district,
                'state' => $request->state,
                'year_of_establishment' => $request->year_of_establishment,
                'textile_segment' => ($request->textile_segment === 'Other' && $request->filled('textile_segment_other'))
                    ? trim($request->textile_segment_other)
                    : $request->textile_segment,
                'main_product_hsn' => $request->main_product_hsn,
                'installed_capacity' => $request->installed_capacity,
                'major_machineries' => $request->major_machineries,
                'capacity_unit' => $request->capacity_unit,
                'monthly_production' => $request->monthly_production,
                'annual_turnover' => $request->annual_turnover,
                'is_exporter' => $request->is_exporter,
                'export_turnover' => $request->is_exporter === 'Y' ? $request->export_turnover : null,
                'major_exporting_countries' => $request->is_exporter === 'Y' ? $request->major_exporting_countries : null,
                'certifications' => $request->certifications,
                'product_images' => $productImagesPaths,
                'export_potential' => $request->export_potential,
                'remarks' => $request->remarks,

                // GST API & Ownership verification fields
                'gst_legal_name' => $gstData['legal_name'] ?? null,
                'gst_trade_name' => $gstData['trade_name'] ?? null,
                'gst_constitution' => $gstData['constitution'] ?? null,
                'gst_status' => $gstData['status'] ?? 'Active',
                'gst_signatories' => $gstData['signatories'] ?? [],
                'gst_ownership_verified' => $ownership['verified'] ?? false,
                'gst_matched_signatory' => $ownership['matched_signatory'] ?? null,
            ]);
        } elseif ($request->role === 'buyer') {
            BuyerProfile::create([
                'user_id'       => $user->id,
                'company_name'  => $request->company_name,
                'contact_phone' => $request->contact_phone,
                'address'       => $request->address,
                'country'       => $request->country,
                'website'       => $request->website,
            ]);
        }

        $centreName = $assignedCentre ? ucfirst($assignedCentre) : 'Regional';

        if ($isExporter) {
            // Exporter account remains pending verification by regional TEFC Centre
            return redirect('/login')->with('success', "Registration application submitted successfully! Your account has been registered with higher authority credentials and routed to the {$centreName} TEFC Centre for review & approval. Once verified and activated, you will be able to log in to create Data Entry Operators and manage your catalogue.");
        }

        Auth::login($user);
        return redirect()->intended('/exporter-portal')->with('success', "Registration submitted successfully!");
    }
}
