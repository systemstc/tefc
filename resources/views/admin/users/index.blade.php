@extends('layouts.portal')

@section('title', 'User Management — Admin Portal')

@section('sidebar')
    <li><a href="{{ route('dashboard') }}"><i class="fas fa-th-large"></i> Dashboard</a></li>
    <li><a href="{{ route('admin.users') }}" class="active"><i class="fas fa-users"></i> User Management</a></li>
    <li><a href="{{ route('admin.products.index') }}"><i class="fas fa-boxes"></i> Product Moderation</a></li>
    <li><a href="{{ route('admin.cms') }}"><i class="fas fa-edit"></i> CMS & Pages</a></li>
    <li><a href="{{ route('admin.resources') }}"><i class="fas fa-file-pdf"></i> Media & Resources</a></li>
    <li><a href="{{ route('admin.settings') }}"><i class="fas fa-cog"></i> Site Settings</a></li>
@endsection

@section('content')
<div class="portal-card">
    <div class="portal-card-header">
        <h3>User Approvals & Management</h3>
        <span class="badge bg-primary rounded-pill">{{ $users->count() }} Total Users</span>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success m-4">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>User / Contact</th>
                    <th>Role / Business</th>
                    <th>Status</th>
                    <th>Onboarded</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>
                        <div class="user-info">
                            <div class="user-avatar">{{ substr($user->name, 0, 1) }}</div>
                            <div>
                                <div class="fw-bold">{{ $user->name }}</div>
                                <div class="text-muted small d-flex align-items-center">
                                    {{ $user->email }}
                                    <button class="btn btn-sm btn-outline-secondary toggle-details-btn ms-2" data-target="details-{{ $user->id }}" style="border-radius: 6px; padding: 1px 6px; font-size: 0.75rem; font-weight: 700; line-height: 1.2;"><i class="fas fa-chevron-down"></i> Details</button>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold" style="font-size: 0.8rem; text-transform: uppercase; color: var(--primary);">
                            {{ $user->roles->first()->name ?? 'No Role' }}
                        </div>
                        <div class="small text-muted">
                            @if($user->hasRole('seller'))
                                {{ $user->exporterProfile->company_name ?? 'N/A' }}
                            @elseif($user->hasRole('buyer'))
                                {{ $user->buyerProfile->company_name ?? 'N/A' }}
                            @else
                                System Administration
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($user->status === 'pending')
                            <span class="status-badge status-pending"><i class="fas fa-clock"></i> Pending</span>
                        @elseif($user->status === 'active')
                            <span class="status-badge status-active"><i class="fas fa-check-circle"></i> Active</span>
                        @else
                            <span class="status-badge status-rejected"><i class="fas fa-times-circle"></i> Rejected</span>
                        @endif
                    </td>
                    <td class="small text-muted">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td class="text-end">
                        @if(!$user->hasRole('admin'))
                            <form method="POST" action="{{ route('admin.users.status', $user->id) }}" class="d-inline-block">
                                @csrf
                                <select name="status" onchange="this.form.submit()" class="form-select form-select-sm" style="min-width: 120px;">
                                    <option value="pending" {{ $user->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Activate</option>
                                    <option value="rejected" {{ $user->status === 'rejected' ? 'selected' : '' }}>Reject</option>
                                </select>
                            </form>
                        @else
                            <span class="text-muted small">N/A</span>
                        @endif
                    </td>
                </tr>
                <tr id="details-{{ $user->id }}" class="details-row" style="display: none; background-color: var(--sky2);">
                    <td colspan="5" class="p-4" style="border-top: none;">
                        @if($user->hasRole('seller'))
                            @php $profile = $user->exporterProfile; @endphp
                            @if($profile)
                                <div class="card border-0 shadow-sm p-4" style="background: #fff; border-radius: 12px;">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <span class="text-muted small d-block">Manufacturer Name</span>
                                            <span class="fw-bold">{{ $profile->company_name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted small d-block">Contact Phone / Mobile No</span>
                                            <span class="fw-bold">{{ $profile->contact_phone ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted small d-block">IEC Code</span>
                                            <span class="fw-bold">{{ $profile->iec_code ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted small d-block">GST No</span>
                                            <span class="fw-bold">{{ $profile->gst_no ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted small d-block">Year of Establishment</span>
                                            <span class="fw-bold">{{ $profile->year_of_establishment ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted small d-block">Corporate Website</span>
                                            @if($profile->website)
                                                <a href="{{ $profile->website }}" target="_blank" class="fw-bold text-primary">{{ $profile->website }}</a>
                                            @else
                                                <span class="fw-bold text-muted">N/A</span>
                                            @endif
                                        </div>
                                        
                                        <div class="col-12 border-top pt-2"></div>

                                        <div class="col-md-6">
                                            <span class="text-muted small d-block">Factory Address</span>
                                            <span class="fw-bold" style="white-space: pre-line;">{{ $profile->factory_address ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-3">
                                            <span class="text-muted small d-block">District</span>
                                            <span class="fw-bold">{{ $profile->district ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-3">
                                            <span class="text-muted small d-block">State</span>
                                            <span class="fw-bold">{{ $profile->state ?? 'N/A' }}</span>
                                        </div>

                                        <div class="col-12 border-top pt-2"></div>

                                        <div class="col-md-4">
                                            <span class="text-muted small d-block">Textile Segment</span>
                                            <span class="fw-bold">{{ $profile->textile_segment ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted small d-block">Main Product HSN</span>
                                            <span class="fw-bold">{{ $profile->main_product_hsn ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted small d-block">Capacity (Installed)</span>
                                            <span class="fw-bold">{{ $profile->installed_capacity ?? 'N/A' }} {{ $profile->capacity_unit ?? '' }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted small d-block">Monthly Production</span>
                                            <span class="fw-bold">{{ $profile->monthly_production ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted small d-block">Annual Turnover</span>
                                            <span class="fw-bold">{{ $profile->annual_turnover ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted small d-block">Exporter Status</span>
                                            <span class="badge {{ $profile->is_exporter === 'Y' ? 'bg-success' : 'bg-secondary' }}">{{ $profile->is_exporter === 'Y' ? 'Yes' : 'No' }}</span>
                                        </div>

                                        @if($profile->is_exporter === 'Y')
                                            <div class="col-md-6">
                                                <span class="text-muted small d-block">Export Turnover</span>
                                                <span class="fw-bold">{{ $profile->export_turnover ?? 'N/A' }}</span>
                                            </div>
                                            <div class="col-md-6">
                                                <span class="text-muted small d-block">Major Exporting Countries</span>
                                                <span class="fw-bold">{{ $profile->major_exporting_countries ?? 'N/A' }}</span>
                                            </div>
                                        @endif

                                        <div class="col-12 border-top pt-2"></div>

                                        <div class="col-12">
                                            <span class="text-muted small d-block">Major Machineries / Technology used</span>
                                            <p class="mb-0 fw-bold" style="white-space: pre-line;">{{ $profile->major_machineries ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="text-muted small d-block">Export Potential</span>
                                            <p class="mb-0 fw-bold" style="white-space: pre-line;">{{ $profile->export_potential ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="text-muted small d-block">Certifications</span>
                                            <p class="mb-0 fw-bold">{{ $profile->certifications ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-12">
                                            <span class="text-muted small d-block">Remarks</span>
                                            <p class="mb-0 fw-bold" style="white-space: pre-line;">{{ $profile->remarks ?? 'N/A' }}</p>
                                        </div>

                                        <div class="col-12 border-top pt-2"></div>

                                        <div class="col-md-6">
                                            <span class="text-muted small d-block mb-1">Business Registration Certificate</span>
                                            @if($profile->certificate_path)
                                                <a href="{{ asset('storage/' . $profile->certificate_path) }}" target="_blank" class="btn btn-sm btn-primary fw-bold text-white"><i class="fas fa-file-download me-1"></i> View Certificate</a>
                                            @else
                                                <span class="text-muted">No Certificate Uploaded</span>
                                            @endif
                                        </div>

                                        <div class="col-md-6">
                                            <span class="text-muted small d-block mb-1">Product Images</span>
                                            @if($profile->product_images && count($profile->product_images) > 0)
                                                <div class="d-flex flex-wrap gap-2 mt-1">
                                                    @foreach($profile->product_images as $path)
                                                        <a href="{{ asset('storage/' . $path) }}" target="_blank">
                                                            <img src="{{ asset('storage/' . $path) }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border);" title="Click to view full size">
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted small">No product images uploaded</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning mb-0">Exporter Profile not initialized.</div>
                            @endif
                        @elseif($user->hasRole('buyer'))
                            @php $profile = $user->buyerProfile; @endphp
                            @if($profile)
                                <div class="card border-0 shadow-sm p-4" style="background: #fff; border-radius: 12px;">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <span class="text-muted small d-block">Company / Organisation</span>
                                            <span class="fw-bold">{{ $profile->company_name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted small d-block">Contact Person</span>
                                            <span class="fw-bold">{{ $user->name }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted small d-block">Email ID</span>
                                            <span class="fw-bold">{{ $user->email }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted small d-block">Contact Phone / Mobile</span>
                                            <span class="fw-bold">{{ $profile->contact_phone ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted small d-block">Country</span>
                                            <span class="fw-bold">{{ $profile->country ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted small d-block">Corporate Website</span>
                                            @if($profile->website)
                                                <a href="{{ $profile->website }}" target="_blank" class="fw-bold text-primary">{{ $profile->website }}</a>
                                            @else
                                                <span class="fw-bold text-muted">N/A</span>
                                            @endif
                                        </div>

                                        <div class="col-12 border-top pt-2"></div>

                                        <div class="col-12">
                                            <span class="text-muted small d-block">Business Address</span>
                                            <p class="mb-0 fw-bold" style="white-space: pre-line;">{{ $profile->address ?? 'N/A' }}</p>
                                        </div>

                                        <div class="col-12 border-top pt-2"></div>

                                        <div class="col-12">
                                            <span class="text-muted small d-block">Sourcing / Procurement Requirements</span>
                                            <p class="mb-0 fw-bold" style="white-space: pre-line;">{{ $profile->sourcing_requirements ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-12">
                                            <span class="text-muted small d-block">Business Description</span>
                                            <p class="mb-0 fw-bold">{{ $profile->profile_description ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning mb-0">Buyer Profile not initialized.</div>
                            @endif
                        @else
                            <div class="alert alert-info mb-0">System administrator account. No specific profile details.</div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtns = document.querySelectorAll('.toggle-details-btn');
    toggleBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('data-target');
            const detailsRow = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (detailsRow.style.display === 'none') {
                detailsRow.style.display = 'table-row';
                icon.className = 'fas fa-chevron-up';
                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-secondary');
            } else {
                detailsRow.style.display = 'none';
                icon.className = 'fas fa-chevron-down';
                this.classList.remove('btn-secondary');
                this.classList.add('btn-outline-secondary');
            }
        });
    });
});
</script>
@endsection
@endsection
