<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TefcCentre;
use App\Models\Product;
use App\Models\TefcStaff;
use App\Models\TefcAdvisory;
use App\Models\TefcTraining;
use App\Models\TefcPublication;
use App\Models\TefcQuery;
use App\Models\TefcClusterProfile;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TefcCentreController extends Controller
{
    /**
     * Resolve the TEFC Centre code and details for the current user or HO session.
     */
    private function resolveCentre(Request $request): array
    {
        $user = Auth::user();
        $centreCode = null;

        if ($user->hasRole('admin')) {
            // HO Admin mode: read switched centre from session, or default to user's centre or 'surat'
            $centreCode = session('viewing_centre', $user->tefc_centre ?: 'surat');
        } else {
            // Centre Officer user
            $centreCode = $user->tefc_centre;
        }

        if (!$centreCode) {
            abort(403, 'Unauthorized. No TEFC Centre is assigned to your account.');
        }

        $centre = TefcCentre::find($centreCode);
        if (!$centre) {
            abort(404, "TEFC Centre [{$centreCode}] not recognized.");
        }

        return [$centreCode, $centre];
    }

    /**
     * Display TEFC Centre Dashboard with metrics and latest applications.
     */
    public function dashboard(Request $request)
    {
        [$centreCode, $centre] = $this->resolveCentre($request);

        $baseQuery = User::where('tefc_centre', $centreCode)
            ->whereHas('roles', fn($q) => $q->whereIn('slug', ['seller', 'exporter']));

        $stats = [
            'total_applications' => (clone $baseQuery)->count(),
            'pending_applications' => (clone $baseQuery)->where('status', 'pending')->count(),
            'approved_applications' => (clone $baseQuery)->where('status', 'active')->count(),
            'rejected_applications' => (clone $baseQuery)->where('status', 'rejected')->count(),
            'total_products' => Product::whereHas('user', fn($q) => $q->where('tefc_centre', $centreCode))->count(),
        ];

        $recentApplications = (clone $baseQuery)
            ->with('exporterProfile')
            ->latest()
            ->take(8)
            ->get();

        $isHoMode = Auth::user()->hasRole('admin') && session()->has('viewing_centre');

        return view('center.dashboard', compact('centre', 'centreCode', 'stats', 'recentApplications', 'isHoMode'));
    }

    /**
     * Display list of Exporter applications for this Centre with filter & search.
     */
    public function applications(Request $request)
    {
        [$centreCode, $centre] = $this->resolveCentre($request);

        $query = User::where('tefc_centre', $centreCode)
            ->whereHas('roles', fn($q) => $q->whereIn('slug', ['seller', 'exporter']))
            ->with('exporterProfile');

        if ($request->filled('status') && in_array($request->status, ['pending', 'active', 'rejected'])) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('exporterProfile', function($ep) use ($search) {
                      $ep->where('company_name', 'like', "%{$search}%")
                         ->orWhere('gst_no', 'like', "%{$search}%")
                         ->orWhere('iec_code', 'like', "%{$search}%")
                         ->orWhere('district', 'like', "%{$search}%")
                         ->orWhere('textile_segment', 'like', "%{$search}%");
                  });
            });
        }

        $applications = $query->latest()->paginate(15)->withQueryString();
        $isHoMode = Auth::user()->hasRole('admin') && session()->has('viewing_centre');

        return view('center.applications', compact('centre', 'centreCode', 'applications', 'isHoMode'));
    }

    /**
     * Accept (Approve) or Reject an Exporter registration application.
     */
    public function updateStatus(Request $request, User $user)
    {
        [$centreCode, $centre] = $this->resolveCentre($request);

        // Verify the user belongs to this Centre (unless HO admin override)
        if (!Auth::user()->hasRole('admin') && $user->tefc_centre !== $centreCode) {
            abort(403, 'You do not have jurisdiction to validate applications from other centres.');
        }

        $request->validate([
            'status' => 'required|in:active,rejected,pending',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:500',
        ]);

        $user->update(['status' => $request->status]);

        if ($user->exporterProfile) {
            $user->exporterProfile->update([
                'rejection_reason' => $request->status === 'rejected' ? $request->rejection_reason : null,
            ]);
        }

        $companyName = $user->exporterProfile->company_name ?? $user->name;
        $statusVerb = match($request->status) {
            'active' => 'Approved & Activated',
            'rejected' => 'Rejected',
            default => 'Set to Pending',
        };

        return back()->with('success', "Application for \"{$companyName}\" has been {$statusVerb} successfully.");
    }

    /**
     * KPI Management Control Room for Centre Officer
     */
    public function kpis(Request $request)
    {
        [$centreCode, $centre] = $this->resolveCentre($request);

        $staff = TefcStaff::where('centre_code', $centreCode)->latest()->get();
        $advisories = TefcAdvisory::where('centre_code', $centreCode)->latest()->paginate(15, ['*'], 'advisory_page');
        $trainings = TefcTraining::where('centre_code', $centreCode)->latest()->get();
        $publications = TefcPublication::where('centre_code', $centreCode)->latest()->get();
        $queries = TefcQuery::where('centre_code', $centreCode)->latest()->paginate(15, ['*'], 'query_page');
        $profile = TefcClusterProfile::firstOrCreate(
            ['centre_code' => $centreCode],
            [
                'cluster_name' => $centre['name'],
                'state' => $centre['state'],
                'covered_districts' => $centre['districts_label'],
                'total_target_msmes' => 500,
            ]
        );

        // Registered Exporters assigned to this centre
        $assignedExporters = User::where('tefc_centre', $centreCode)
            ->whereHas('roles', fn($q) => $q->whereIn('slug', ['seller', 'exporter']))
            ->with('exporterProfile')
            ->get();

        // Summary KPIs for this centre
        $kpiSummary = [
            'active_staff' => $staff->where('status', 'active')->count(),
            'unique_advised' => TefcAdvisory::where('centre_code', $centreCode)->distinct('company_name')->count('company_name'),
            'trainings_count' => $trainings->where('type', 'training_programme')->count(),
            'exporters_trained' => $trainings->sum('participants_count'),
            'publications_count' => $publications->count(),
            'queries_received' => TefcQuery::where('centre_code', $centreCode)->count(),
            'queries_resolved' => TefcQuery::where('centre_code', $centreCode)->where('status', 'resolved')->count(),
            'sla_percent' => TefcQuery::where('centre_code', $centreCode)->where('status', 'resolved')->count() > 0
                ? round((TefcQuery::where('centre_code', $centreCode)->where('status', 'resolved')->where('is_within_sla', true)->count() / TefcQuery::where('centre_code', $centreCode)->where('status', 'resolved')->count()) * 100, 2)
                : 100.00,
            'coverage_percent' => $profile->total_target_msmes > 0
                ? round((TefcAdvisory::where('centre_code', $centreCode)->distinct('company_name')->count('company_name') / $profile->total_target_msmes) * 100, 1)
                : 0,
        ];

        $isHoMode = Auth::user()->hasRole('admin') && session()->has('viewing_centre');

        return view('center.kpis.index', compact('centre', 'centreCode', 'staff', 'advisories', 'trainings', 'publications', 'queries', 'profile', 'assignedExporters', 'kpiSummary', 'isHoMode'));
    }

    // KPI 1: Staff
    public function storeStaff(Request $request)
    {
        [$centreCode] = $this->resolveCentre($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'nullable|integer|min:18|max:80',
            'designation' => 'required|string|max:255',
            'education' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0|max:60',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'status' => 'required|in:active,inactive',
            'joining_date' => 'nullable|date',
        ]);

        $validated['centre_code'] = $centreCode;
        TefcStaff::create($validated);

        return back()->with('success', "Staff member \"{$validated['name']}\" added successfully.");
    }

    public function toggleStaff(Request $request, TefcStaff $staff)
    {
        [$centreCode] = $this->resolveCentre($request);
        if (!Auth::user()->hasRole('admin') && $staff->centre_code !== $centreCode) {
            abort(403);
        }

        $staff->update(['status' => $staff->status === 'active' ? 'inactive' : 'active']);
        return back()->with('success', "Status for \"{$staff->name}\" updated to {$staff->status}.");
    }

    public function deleteStaff(Request $request, TefcStaff $staff)
    {
        [$centreCode] = $this->resolveCentre($request);
        if (!Auth::user()->hasRole('admin') && $staff->centre_code !== $centreCode) {
            abort(403);
        }

        $staff->delete();
        return back()->with('success', "Staff record removed.");
    }

    // KPI 2: Advisory
    public function storeAdvisory(Request $request)
    {
        [$centreCode] = $this->resolveCentre($request);

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'user_id' => 'nullable|exists:users,id',
            'iec_status' => 'required|in:no_iec,iec_no_export,inactive_iec,new_iec,regular',
            'session_date' => 'required|date',
            'advisory_topic' => 'required|string|max:255',
            'outcome_notes' => 'nullable|string',
        ]);

        $validated['centre_code'] = $centreCode;
        TefcAdvisory::create($validated);

        return back()->with('success', "Advisory session for \"{$validated['company_name']}\" logged.");
    }

    public function deleteAdvisory(Request $request, TefcAdvisory $advisory)
    {
        [$centreCode] = $this->resolveCentre($request);
        if (!Auth::user()->hasRole('admin') && $advisory->centre_code !== $centreCode) {
            abort(403);
        }

        $advisory->delete();
        return back()->with('success', "Advisory record deleted.");
    }

    // KPI 3: Training
    public function storeTraining(Request $request)
    {
        [$centreCode] = $this->resolveCentre($request);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:training_programme,buyer_seller_meet',
            'event_date' => 'required|date',
            'venue' => 'nullable|string|max:255',
            'participants_count' => 'required|integer|min:0',
            'topics_covered' => 'nullable|string',
        ]);

        $validated['centre_code'] = $centreCode;
        TefcTraining::create($validated);

        return back()->with('success', "Training session \"{$validated['title']}\" recorded.");
    }

    public function deleteTraining(Request $request, TefcTraining $training)
    {
        [$centreCode] = $this->resolveCentre($request);
        if (!Auth::user()->hasRole('admin') && $training->centre_code !== $centreCode) {
            abort(403);
        }

        $training->delete();
        return back()->with('success', "Training record deleted.");
    }

    // KPI 4: Publications
    public function storePublication(Request $request)
    {
        [$centreCode] = $this->resolveCentre($request);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:newsletter,trade_advisory,market_report',
            'issue_date' => 'required|date',
            'summary' => 'nullable|string',
        ]);

        $validated['centre_code'] = $centreCode;
        TefcPublication::create($validated);

        return back()->with('success', "Publication \"{$validated['title']}\" added.");
    }

    public function deletePublication(Request $request, TefcPublication $publication)
    {
        [$centreCode] = $this->resolveCentre($request);
        if (!Auth::user()->hasRole('admin') && $publication->centre_code !== $centreCode) {
            abort(403);
        }

        $publication->delete();
        return back()->with('success', "Publication deleted.");
    }

    // KPI 5: Queries
    public function storeQuery(Request $request)
    {
        [$centreCode] = $this->resolveCentre($request);

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'category' => 'required|in:hsn_classification,compliance,documentation,incentives,other',
            'received_date' => 'required|date',
            'resolved_date' => 'nullable|date',
            'status' => 'required|in:pending,resolved',
            'is_within_sla' => 'nullable|boolean',
            'resolution_notes' => 'nullable|string',
        ]);

        $validated['centre_code'] = $centreCode;
        $validated['is_within_sla'] = $request->has('is_within_sla') && $request->is_within_sla;
        TefcQuery::create($validated);

        return back()->with('success', "Query from \"{$validated['company_name']}\" logged.");
    }

    public function resolveQuery(Request $request, TefcQuery $queryRecord)
    {
        [$centreCode] = $this->resolveCentre($request);
        if (!Auth::user()->hasRole('admin') && $queryRecord->centre_code !== $centreCode) {
            abort(403);
        }

        $validated = $request->validate([
            'resolved_date' => 'required|date',
            'is_within_sla' => 'nullable|boolean',
            'resolution_notes' => 'nullable|string',
        ]);

        $queryRecord->update([
            'status' => 'resolved',
            'resolved_date' => $validated['resolved_date'],
            'is_within_sla' => $request->has('is_within_sla') && $request->is_within_sla,
            'resolution_notes' => $validated['resolution_notes'] ?? null,
        ]);

        return back()->with('success', "Query marked as resolved.");
    }

    public function deleteQuery(Request $request, TefcQuery $queryRecord)
    {
        [$centreCode] = $this->resolveCentre($request);
        if (!Auth::user()->hasRole('admin') && $queryRecord->centre_code !== $centreCode) {
            abort(403);
        }

        $queryRecord->delete();
        return back()->with('success', "Query deleted.");
    }

    // KPI 6: Cluster Profile
    public function updateClusterProfile(Request $request)
    {
        [$centreCode] = $this->resolveCentre($request);

        $validated = $request->validate([
            'total_target_msmes' => 'required|integer|min:1',
            'specialization' => 'nullable|string|max:500',
        ]);

        $profile = TefcClusterProfile::firstOrCreate(['centre_code' => $centreCode]);
        $profile->update($validated);

        return back()->with('success', "Cluster target profile updated.");
    }

    /**
     * Show cluster portal content editor for the current center officer.
     */
    public function clusterContent(Request $request)
    {
        [$centreCode, $centre] = $this->resolveCentre($request);

        $section = Section::where('type', 'clusters_data')->first();
        if (!$section) {
            abort(404, 'Cluster CMS data section not found.');
        }

        $items = $section->content['items'] ?? [];

        // Match cluster by key or name
        $clusterKey = null;
        $clusterData = null;
        foreach ($items as $k => $data) {
            if (strtolower($k) === strtolower($centreCode) || strtolower($data['name'] ?? '') === strtolower($centre['name'])) {
                $clusterKey = $k;
                $clusterData = $data;
                break;
            }
        }

        if (!$clusterData) {
            $clusterKey = ucfirst($centreCode);
            $clusterData = [
                'name' => $centre['name'],
                'state' => $centre['state'],
                'spec' => 'Textiles & Garments',
                'era' => '20th Century',
                'desc' => "{$centre['name']} is a major textile manufacturing and export cluster in {$centre['state']}.",
                'units' => '1,000+',
                'exportVal' => 'Rs.1,000 Cr+',
                'workforce' => '50,000+',
                'dests' => '25+',
                'highlights' => ['Woven Fabric', 'Yarn', 'Apparel'],
                'chartCurr' => [20, 25, 28, 30, 35, 38, 40, 42, 45, 48, 50, 55],
                'chartPrev' => [15, 18, 20, 24, 28, 30, 32, 35, 38, 40, 42, 45],
                'exporters' => [],
                'facilities' => [],
                'events' => [],
                'sections_active' => [
                    'stats' => true,
                    'overview' => true,
                    'highlights' => true,
                    'chart' => true,
                    'exporters' => true,
                    'facilities' => true,
                    'events' => true,
                    'office' => true
                ],
                'address' => $centre['districts_label'] ?? '',
                'phone' => '+91 0000 000000',
                'email' => $centre['email'] ?? '',
                'mapUrl' => 'https://maps.google.com/?q=' . urlencode($centre['name'])
            ];
        }

        // Ensure default section active flags
        $defaultActive = [
            'stats' => true,
            'overview' => true,
            'highlights' => true,
            'chart' => true,
            'exporters' => true,
            'facilities' => true,
            'events' => true,
            'office' => true
        ];
        $clusterData['sections_active'] = array_merge($defaultActive, $clusterData['sections_active'] ?? []);

        $isHoMode = Auth::user()->hasRole('admin') && session()->has('viewing_centre');

        return view('center.cluster', compact('centre', 'centreCode', 'clusterKey', 'clusterData', 'isHoMode'));
    }

    /**
     * Update cluster portal content and active/inactive section visibility for the current center.
     */
    public function updateClusterContent(Request $request)
    {
        [$centreCode, $centre] = $this->resolveCentre($request);

        $section = Section::where('type', 'clusters_data')->firstOrFail();
        $content = $section->content;
        $items = $content['items'] ?? [];

        // Match cluster key
        $clusterKey = null;
        foreach ($items as $k => $data) {
            if (strtolower($k) === strtolower($centreCode) || strtolower($data['name'] ?? '') === strtolower($centre['name'])) {
                $clusterKey = $k;
                break;
            }
        }
        if (!$clusterKey) {
            $clusterKey = ucfirst($centreCode);
        }

        $clusterData = $items[$clusterKey] ?? [];

        // If json_cluster is supplied, decode it
        if ($request->filled('json_cluster')) {
            $decoded = json_decode($request->input('json_cluster'), true);
            if (is_array($decoded) && json_last_error() === JSON_ERROR_NONE) {
                $clusterData = $decoded;
            }
        } else {
            // Read form values
            $clusterData['name'] = $request->input('name', $clusterData['name'] ?? $centre['name']);
            $clusterData['state'] = $request->input('state', $clusterData['state'] ?? $centre['state']);
            $clusterData['spec'] = $request->input('spec', $clusterData['spec'] ?? '');
            $clusterData['era'] = $request->input('era', $clusterData['era'] ?? '');
            $clusterData['desc'] = $request->input('desc', $clusterData['desc'] ?? '');
            $clusterData['units'] = $request->input('units', $clusterData['units'] ?? '');
            $clusterData['exportVal'] = $request->input('exportVal', $clusterData['exportVal'] ?? '');
            $clusterData['workforce'] = $request->input('workforce', $clusterData['workforce'] ?? '');
            $clusterData['dests'] = $request->input('dests', $clusterData['dests'] ?? '');
            $clusterData['address'] = $request->input('address', $clusterData['address'] ?? '');
            $clusterData['phone'] = $request->input('phone', $clusterData['phone'] ?? '');
            $clusterData['email'] = $request->input('email', $clusterData['email'] ?? '');
            $clusterData['mapUrl'] = $request->input('mapUrl', $clusterData['mapUrl'] ?? '');

            // Active/Inactive section toggles
            $clusterData['sections_active'] = [
                'stats' => $request->boolean('sec_stats'),
                'overview' => $request->boolean('sec_overview'),
                'highlights' => $request->boolean('sec_highlights'),
                'chart' => $request->boolean('sec_chart'),
                'exporters' => $request->boolean('sec_exporters'),
                'facilities' => $request->boolean('sec_facilities'),
                'events' => $request->boolean('sec_events'),
                'office' => $request->boolean('sec_office'),
            ];

            if ($request->filled('json_events')) {
                $dEvt = json_decode($request->input('json_events'), true);
                if (is_array($dEvt)) $clusterData['events'] = $dEvt;
            }

            if ($request->filled('json_exporters')) {
                $dExp = json_decode($request->input('json_exporters'), true);
                if (is_array($dExp)) $clusterData['exporters'] = $dExp;
            }

            if ($request->filled('json_highlights')) {
                $dHigh = json_decode($request->input('json_highlights'), true);
                if (is_array($dHigh)) $clusterData['highlights'] = $dHigh;
            }
        }

        $items[$clusterKey] = $clusterData;
        $content['items'] = $items;
        $section->content = $content;
        $section->save();

        Cache::forget('page_' . ($section->page->slug ?? 'clusters'));

        return redirect()->back()->with('success', "{$clusterData['name']} Cluster content and section active/inactive display statuses updated successfully.");
    }
}
