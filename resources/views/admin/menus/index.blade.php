@extends('layouts.portal')

@section('title', 'Navigation Management — Admin Portal')

@section('sidebar')
    <li><a href="{{ route('dashboard') }}"><i class="fas fa-th-large"></i> Dashboard</a></li>
    <li><a href="{{ route('admin.users') }}"><i class="fas fa-users"></i> User Management</a></li>
    <li><a href="{{ route('admin.products.index') }}"><i class="fas fa-boxes"></i> Product Moderation</a></li>
    <li><a href="{{ route('admin.cms') }}"><i class="fas fa-edit"></i> CMS & Pages</a></li>
    <li><a href="{{ route('admin.resources') }}"><i class="fas fa-file-pdf"></i> Media & Resources</a></li>
    <li><a href="{{ route('admin.settings') }}"><i class="fas fa-cog"></i> Site Settings</a></li>
@endsection

@section('content')
<div class="row g-4">
    @foreach($menus as $menu)
        <div class="col-12">
            <div class="portal-card mb-4">
                <div class="portal-card-header">
                    <h3>{{ $menu->name }} <small class="text-muted ms-2" style="font-size: 0.8rem;">(Location: {{ $menu->location }})</small></h3>
                </div>
                
                <div class="p-4">
                    <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST">
                        @csrf
                        <div class="table-responsive">
                            <table class="custom-table border">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">Pos.</th>
                                        <th>Menu Label</th>
                                        <th>Target URL / Path</th>
                                        <th style="width: 100px;">Sort Order</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($menu->items->sortBy('order') as $item)
                                        <tr>
                                            <td class="text-center text-muted fw-bold small">{{ $loop->iteration }}</td>
                                            <td>
                                                <input type="text" name="items[{{ $item->id }}][label]" class="form-control form-control-sm" value="{{ $item->label }}">
                                            </td>
                                            <td>
                                                <input type="text" name="items[{{ $item->id }}][url]" class="form-control form-control-sm" value="{{ $item->url }}">
                                            </td>
                                            <td>
                                                <input type="number" name="items[{{ $item->id }}][order]" class="form-control form-control-sm text-center" value="{{ $item->order }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-4">Save {{ $menu->name }} Structure</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
