@extends('layouts.portal')

@section('title', 'Global Settings — Admin Portal')

@section('sidebar')
    <li><a href="{{ route('dashboard') }}"><i class="fas fa-th-large"></i> Dashboard</a></li>
    <li><a href="{{ route('admin.users') }}"><i class="fas fa-users"></i> User Management</a></li>
    <li><a href="{{ route('admin.products.index') }}"><i class="fas fa-boxes"></i> Product Moderation</a></li>
    <li><a href="{{ route('admin.cms') }}"><i class="fas fa-edit"></i> CMS & Pages</a></li>
    <li><a href="{{ route('admin.resources') }}"><i class="fas fa-file-pdf"></i> Media & Resources</a></li>
    <li><a href="{{ route('admin.settings') }}" class="active"><i class="fas fa-cog"></i> Site Settings</a></li>
@endsection

@section('content')
<div class="portal-card">
    <div class="portal-card-header">
        <h3>Global Site Settings</h3>
        <p class="text-muted small mb-0">Control identity, contact info, and portal branding.</p>
    </div>
    
    <div class="p-4">
        @if(session('success'))
            <div class="alert alert-success mb-4">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            <div class="row g-5">
                <div class="col-md-6">
                    <h5 class="text-primary fw-bold mb-4 border-bottom pb-2"><i class="fas fa-fingerprint me-2"></i> Branding & Identity</h5>
                    @foreach($settings->whereIn('key', ['site_name', 'logo_text_rich', 'logo_subtitle']) as $setting)
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase">{{ ucwords(str_replace('_', ' ', $setting->key)) }}</label>
                            @if($setting->key == 'logo_text_rich')
                                <textarea name="{{ $setting->key }}" class="form-control" rows="3">{{ $setting->value }}</textarea>
                            @else
                                <input type="text" name="{{ $setting->key }}" class="form-control" value="{{ $setting->value }}">
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="col-md-6">
                    <h5 class="text-primary fw-bold mb-4 border-bottom pb-2"><i class="fas fa-headset me-2"></i> Contact & Footer</h5>
                    @foreach($settings->whereIn('key', ['contact_email', 'contact_phone', 'footer_about_text', 'footer_copyright']) as $setting)
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase">{{ ucwords(str_replace('_', ' ', $setting->key)) }}</label>
                            @if(str_contains($setting->key, 'text') || str_contains($setting->key, 'copyright'))
                                <textarea name="{{ $setting->key }}" class="form-control" rows="3">{{ $setting->value }}</textarea>
                            @else
                                <input type="text" name="{{ $setting->key }}" class="form-control" value="{{ $setting->value }}">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-5 pt-4 border-top text-end">
                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold">Update Global Settings</button>
            </div>
        </form>
    </div>
</div>
@endsection
