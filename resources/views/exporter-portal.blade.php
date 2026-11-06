@extends('layouts.portal')

@section('title', 'My Dashboard — TEFC Portal')

@section('sidebar')
    <li><a href="{{ route('exporter-portal') }}" class="{{ request()->routeIs('exporter-portal') ? 'active' : '' }}"><i class="fas fa-th-large"></i> Dashboard</a></li>
    @if(Auth::user()->hasRole('seller') || Auth::user()->hasRole('exporter') || Auth::user()->isOperator())
        <li><a href="{{ route('seller.products.index') }}"><i class="fas fa-boxes"></i> My Catalogue</a></li>
        @if(Auth::user()->isMainExporter())
            <li><a href="{{ route('exporter.operators.index') }}"><i class="fas fa-users-cog"></i> Data Entry Operators</a></li>
        @endif
        <li><a href="{{ route('seller.inquiries') }}"><i class="fas fa-envelope-open-text"></i> Buyer Leads</a></li>
    @else
        <li><a href="{{ route('buyer.sellers') }}"><i class="fas fa-store"></i> Seller Directory</a></li>
        <li><a href="{{ route('buyer.inquiries') }}"><i class="fas fa-paper-plane"></i> Sent Requests</a></li>
    @endif
    <li><a href="#"><i class="fas fa-user-circle"></i> Profile Settings</a></li>
@endsection

@section('content')
@if(isset($pendingReviewCount) && $pendingReviewCount > 0)
    <div class="alert alert-warning border-0 rounded-4 shadow-sm p-4 mb-4 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <span class="fs-2">⚡</span>
            <div>
                @if(Auth::user()->isMainExporter())
                    <h5 class="fw-bold mb-1 text-dark">{{ $pendingReviewCount }} Product(s) Pending Your Higher Authority Acknowledgment</h5>
                    <p class="mb-0 text-muted small">Your Data Entry Operator has submitted products for review. Once you acknowledge them, they are published directly to the public catalogue without needing HO admin approval.</p>
                @else
                    <h5 class="fw-bold mb-1 text-dark">{{ $pendingReviewCount }} Product(s) Under Higher Authority Review</h5>
                    <p class="mb-0 text-muted small">Your product submissions are currently awaiting review and acknowledgment by your Exporter Higher Authority.</p>
                @endif
            </div>
        </div>
        <a href="{{ route('seller.products.index') }}" class="btn btn-warning fw-bold px-4 py-2">
            Review Catalogue <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>
@endif

<div class="row g-4 mb-5">
    @if(Auth::user()->hasRole('seller') || Auth::user()->hasRole('exporter') || Auth::user()->isOperator())
        <div class="col-md-6">
            <div class="portal-card p-4 text-center">
                <div class="text-muted small text-uppercase fw-bold mb-2">Total Product Views</div>
                <div class="h2 fw-bold text-primary">{{ $analytics['total_views'] ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="portal-card p-4 text-center">
                <div class="text-muted small text-uppercase fw-bold mb-2">Media Interactions</div>
                <div class="h2 fw-bold text-success">{{ $analytics['total_interactions'] ?? 0 }}</div>
            </div>
        </div>
    @else
        <div class="col-md-6">
            <div class="portal-card p-4 text-center">
                <div class="text-muted small text-uppercase fw-bold mb-2">Sent Inquiries</div>
                <div class="h2 fw-bold text-primary">{{ \App\Models\ProductInquiry::where('user_id', Auth::id())->count() }}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="portal-card p-4 text-center">
                <div class="text-muted small text-uppercase fw-bold mb-2">Saved Items</div>
                <div class="h2 fw-bold text-success">{{ Auth::user()->savedSellers->count() ?? 0 }}</div>
            </div>
        </div>
    @endif
</div>

@if((Auth::user()->hasRole('seller') || Auth::user()->hasRole('exporter') || Auth::user()->isOperator()) && Auth::user()->status === 'active')
<div class="portal-card mb-5">
    <div class="portal-card-header">
        <h3>Product Traffic Overview</h3>
        <span class="text-muted small">Last 7 Days</span>
    </div>
    <div class="p-4">
        <canvas id="sellerChart" height="80"></canvas>
    </div>
</div>

<div class="portal-card">
    <div class="portal-card-header">
        <h3>Recent Lead Inquiries</h3>
        <a href="{{ route('seller.inquiries') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Product</th>
                    <th>Buyer</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inquiries as $inquiry)
                <tr>
                    <td class="small text-muted">{{ $inquiry->created_at->format('d M, Y') }}</td>
                    <td><strong>{{ $inquiry->product->name }}</strong></td>
                    <td>{{ $inquiry->buyer_name }}</td>
                    <td class="text-end">
                        <a href="{{ route('seller.inquiries') }}" class="action-btn"><i class="fas fa-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">No recent inquiries.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif

@if(Auth::user()->status === 'pending')
    <div class="alert alert-warning p-4 border-0 rounded-4 shadow-sm">
        <div class="d-flex gap-3">
            <i class="fas fa-exclamation-circle fa-2x"></i>
            <div>
                <h5 class="fw-bold">Account Verification Pending</h5>
                <p class="mb-0">Your profile is currently being reviewed by the TEFC administration. You will gain full access once approved.</p>
            </div>
        </div>
    </div>
@endif

<div class="portal-card mt-5">
    <div class="portal-card-header">
        <h3>Business Profile Information</h3>
    </div>
    <div class="p-4">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profileUpdateForm">
            @csrf
            
            @if(Auth::user()->hasRole('seller'))
                @php $profile = Auth::user()->exporterProfile; @endphp
                <div class="row g-3">
                    <div class="col-12 mb-2">
                        <h5 class="text-primary border-bottom pb-2 fw-bold"><i class="fas fa-building me-2"></i>Company & Contact Info</h5>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Manufacturer / Company Name</label>
                        <input type="text" name="company_name" class="form-control" value="{{ $profile->company_name ?? '' }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Contact Phone / Mobile No</label>
                        <input type="text" name="contact_phone" class="form-control" value="{{ $profile->contact_phone ?? '' }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">GST No</label>
                        <input type="text" name="gst_no" class="form-control" value="{{ $profile->gst_no ?? '' }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">IEC Code</label>
                        <input type="text" name="iec_code" class="form-control" value="{{ $profile->iec_code ?? '' }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Year of Establishment</label>
                        <input type="number" name="year_of_establishment" class="form-control" value="{{ $profile->year_of_establishment ?? '' }}" required min="1800" max="{{ date('Y') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Corporate Website</label>
                        <input type="url" name="website" class="form-control" value="{{ $profile->website ?? '' }}">
                    </div>

                    <div class="col-12 mt-4 mb-2">
                        <h5 class="text-primary border-bottom pb-2 fw-bold"><i class="fas fa-map-marker-alt me-2"></i>Location Details</h5>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Factory Address</label>
                        <textarea name="factory_address" class="form-control" rows="2" required>{{ $profile->factory_address ?? '' }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">District</label>
                        <input type="text" name="district" class="form-control" value="{{ $profile->district ?? '' }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">State</label>
                        <input type="text" name="state" class="form-control" value="{{ $profile->state ?? '' }}" required>
                    </div>

                    <div class="col-12 mt-4 mb-2">
                        <h5 class="text-primary border-bottom pb-2 fw-bold"><i class="fas fa-industry me-2"></i>Production & Capacity</h5>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Textile Segment</label>
                        <select name="textile_segment" class="form-control" required>
                            <option value="">Select Segment</option>
                            @php
                                $standardSegments = ['Spinning', 'Weaving', 'Garmenting', 'Knitting', 'Processing', 'Technical Textiles', 'Composite', 'Non-Woven'];
                                $currentSegment = $profile->textile_segment ?? '';
                                $isCustomSegment = !empty($currentSegment) && !in_array($currentSegment, $standardSegments);
                            @endphp
                            @foreach($standardSegments as $segment)
                                <option value="{{ $segment }}" {{ $currentSegment === $segment ? 'selected' : '' }}>{{ $segment }}</option>
                            @endforeach
                            @if($isCustomSegment)
                                <option value="{{ $currentSegment }}" selected>{{ $currentSegment }} (Other)</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Main Product (Existing) HSN Code</label>
                        <input type="text" name="main_product_hsn" class="form-control" value="{{ $profile->main_product_hsn ?? '' }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Installed Capacity</label>
                        <input type="text" name="installed_capacity" class="form-control" value="{{ $profile->installed_capacity ?? '' }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Capacity Unit</label>
                        <select name="capacity_unit" class="form-control" required>
                            <option value="">Select Unit</option>
                            @foreach(['Meters/Month', 'Tons/Month', 'Pieces/Month', 'Kgs/Month'] as $unit)
                                <option value="{{ $unit }}" {{ ($profile->capacity_unit ?? '') === $unit ? 'selected' : '' }}>{{ $unit }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Monthly Production</label>
                        <input type="text" name="monthly_production" class="form-control" value="{{ $profile->monthly_production ?? '' }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Annual Turnover</label>
                        <select name="annual_turnover" class="form-control" required>
                            <option value="">Select Range</option>
                            @foreach(['Upto 1 Crore', '1 to 5 Crores', '5 to 25 Crores', '25 to 100 Crores', 'Above 100 Crores'] as $range)
                                <option value="{{ $range }}" {{ ($profile->annual_turnover ?? '') === $range ? 'selected' : '' }}>{{ $range }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Major Machineries / Technology used for manufacturing</label>
                        <textarea name="major_machineries" class="form-control" rows="3" required>{{ $profile->major_machineries ?? '' }}</textarea>
                    </div>

                    <div class="col-12 mt-4 mb-2">
                        <h5 class="text-primary border-bottom pb-2 fw-bold"><i class="fas fa-ship me-2"></i>Trade & Export Details</h5>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Are you an Exporter?</label>
                        <select name="is_exporter" id="dashboardIsExporter" class="form-control" required>
                            <option value="N" {{ ($profile->is_exporter ?? 'N') === 'N' ? 'selected' : '' }}>No</option>
                            <option value="Y" {{ ($profile->is_exporter ?? 'N') === 'Y' ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                    <div class="col-md-6 db-export-field" style="display: {{ ($profile->is_exporter ?? 'N') === 'Y' ? 'block' : 'none' }};">
                        <label class="form-label fw-bold">Export Turnover</label>
                        <input type="text" name="export_turnover" id="dashboardExportTurnover" class="form-control" value="{{ $profile->export_turnover ?? '' }}">
                    </div>
                    <div class="col-md-6 db-export-field" style="display: {{ ($profile->is_exporter ?? 'N') === 'Y' ? 'block' : 'none' }};">
                        <label class="form-label fw-bold">Major Exporting Countries</label>
                        <input type="text" name="major_exporting_countries" id="dashboardExportCountries" class="form-control" value="{{ $profile->major_exporting_countries ?? '' }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Export Potential</label>
                        <textarea name="export_potential" class="form-control" rows="2">{{ $profile->export_potential ?? '' }}</textarea>
                    </div>

                    <div class="col-12 mt-4 mb-2">
                        <h5 class="text-primary border-bottom pb-2 fw-bold"><i class="fas fa-award me-2"></i>Certifications, Media & Additional Info</h5>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Certifications</label>
                        <input type="text" name="certifications" class="form-control" value="{{ $profile->certifications ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Business Description (Public Profile)</label>
                        <textarea name="profile_description" class="form-control" rows="2">{{ $profile->profile_description ?? '' }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="2">{{ $profile->remarks ?? '' }}</textarea>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-bold d-block">Existing Product Images</label>
                        @if($profile->product_images && count($profile->product_images) > 0)
                            <div class="d-flex flex-wrap gap-3 mb-3">
                                @foreach($profile->product_images as $path)
                                    <div class="position-relative" style="width: 100px; height: 100px; border-radius: 8px; overflow: hidden; border: 1px solid var(--border);">
                                        <img src="{{ asset('storage/' . $path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        <div class="position-absolute top-0 end-0 bg-white rounded-circle p-1 shadow-sm d-flex align-items-center justify-content-center" style="margin: 4px; width: 22px; height: 22px; line-height: 1;">
                                            <input type="checkbox" name="delete_images[]" value="{{ $path }}" title="Delete image" style="cursor: pointer; width: 14px; height: 14px; margin: 0;">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <small class="text-muted d-block mb-3">Check the box in the corner of any image above to delete it when saving.</small>
                        @else
                            <p class="text-muted small">No product images uploaded yet.</p>
                        @endif
                        
                        <label class="form-label fw-bold">Upload New Product Images</label>
                        <input type="file" name="product_images[]" class="form-control" accept=".jpg,.jpeg,.png" multiple>
                    </div>
                </div>
            @else
                @php $buyerProfile = Auth::user()->buyerProfile; @endphp
                <div class="row g-3">
                    <div class="col-12 mb-2">
                        <h5 class="text-primary border-bottom pb-2 fw-bold"><i class="fas fa-building me-2"></i>Organisation & Contact Info</h5>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Company / Organisation Name <span class="text-danger">*</span></label>
                        <input type="text" name="company_name" class="form-control" value="{{ $buyerProfile->company_name ?? '' }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Contact Phone / Mobile No <span class="text-danger">*</span></label>
                        <input type="text" name="contact_phone" class="form-control" value="{{ $buyerProfile->contact_phone ?? '' }}" required placeholder="+1 212 555 0199">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Country</label>
                        <input type="text" name="country" class="form-control" value="{{ $buyerProfile->country ?? '' }}" placeholder="e.g. United States">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Corporate Website</label>
                        <input type="url" name="website" class="form-control" value="{{ $buyerProfile->website ?? '' }}" placeholder="https://www.mycompany.com">
                    </div>

                    <div class="col-12 mt-3 mb-2">
                        <h5 class="text-primary border-bottom pb-2 fw-bold"><i class="fas fa-map-marker-alt me-2"></i>Business Address</h5>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Business Address <span class="text-danger">*</span></label>
                        <textarea name="address" class="form-control" rows="3" required placeholder="Street, City, State / Province, PIN / ZIP">{{ $buyerProfile->address ?? '' }}</textarea>
                    </div>

                    <div class="col-12 mt-3 mb-2">
                        <h5 class="text-primary border-bottom pb-2 fw-bold"><i class="fas fa-clipboard-list me-2"></i>Sourcing & Profile</h5>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Sourcing / Procurement Requirements</label>
                        <textarea name="sourcing_requirements" class="form-control" rows="3" placeholder="Describe the types of textiles, quantities, or product categories you are sourcing...">{{ $buyerProfile->sourcing_requirements ?? '' }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Business Description (Public Profile)</label>
                        <textarea name="profile_description" class="form-control" rows="3" placeholder="Tell sellers about your business...">{{ $buyerProfile->profile_description ?? '' }}</textarea>
                    </div>
                </div>
            @endif

            <button type="submit" class="btn btn-primary mt-4 px-4 py-2 fw-bold">Save Changes</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@if(Auth::user()->hasRole('seller') && Auth::user()->status === 'active')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('sellerChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($analytics['chartLabels']) !!},
                datasets: [{
                    label: 'Views',
                    data: {!! json_encode($analytics['chartViews']) !!},
                    borderColor: '#6c1340',
                    backgroundColor: 'rgba(108, 21, 63, 0.05)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
@endif

@if(Auth::user()->hasRole('seller'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dbIsExporter = document.getElementById('dashboardIsExporter');
    const dbExportFields = document.querySelectorAll('.db-export-field');
    const dbExportTurnover = document.getElementById('dashboardExportTurnover');
    const dbExportCountries = document.getElementById('dashboardExportCountries');

    function toggleDbExportFields() {
        if (dbIsExporter.value === 'Y') {
            dbExportFields.forEach(el => el.style.display = 'block');
            dbExportTurnover.required = true;
            dbExportCountries.required = true;
        } else {
            dbExportFields.forEach(el => el.style.display = 'none');
            dbExportTurnover.required = false;
            dbExportCountries.required = false;
        }
    }

    dbIsExporter.addEventListener('change', toggleDbExportFields);
    toggleDbExportFields();
});
</script>
@endif
@endsection
