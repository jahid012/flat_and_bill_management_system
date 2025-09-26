@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">House Owners Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.house-owners.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            Add New House Owner
        </a>
    </div>
</div>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.house-owners.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" name="search" id="search" class="form-control" 
                           value="{{ request('search') }}" placeholder="Name, email, or phone">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="sort" class="form-label">Sort By</label>
                    <select name="sort" id="sort" class="form-select">
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="email" {{ request('sort') == 'email' ? 'selected' : '' }}>Email</option>
                        <option value="created_at" {{ request('sort', 'created_at') == 'created_at' ? 'selected' : '' }}>Registration Date</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi bi-search"></i>
                        </button>
                        <a href="{{ route('admin.house-owners.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@if($houseOwners->count() > 0)
    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h6 class="m-0 font-weight-bold text-primary">House Owners List</h6>
                </div>
                <div class="col-auto">
                    <span class="badge bg-info">{{ $houseOwners->total() }} total owners</span>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>House Owner</th>
                            <th>Contact</th>
                            <th>Properties</th>
                            <th>Status</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($houseOwners as $owner)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                            
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $owner->name }}</h6>
                                            <small class="text-muted">ID: #{{ $owner->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <i class="bi bi-envelope text-muted me-1"></i>
                                        <a href="mailto:{{ $owner->email }}" class="text-decoration-none">{{ $owner->email }}</a>
                                    </div>
                                    @if($owner->phone)
                                        <div class="mt-1">
                                            <i class="bi bi-phone text-muted me-1"></i>
                                            <a href="tel:{{ $owner->phone }}" class="text-decoration-none">{{ $owner->phone }}</a>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <span class="badge bg-primary">{{ $owner->buildings_count ?? 0 }} Buildings</span>
                                        </div>
                                        <div>
                                            <span class="badge bg-info">{{ $owner->flats_count ?? 0 }} Flats</span>
                                        </div>
                                    </div>
                                    @if($owner->total_tenants > 0)
                                        <small class="text-muted">{{ $owner->total_tenants }} tenants</small>
                                    @endif
                                </td>
                                <td>
                                    @if($owner->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                    @if($owner->email_verified_at)
                                        <br><small class="text-success">
                                            <i class="bi bi-check-circle me-1"></i>Verified
                                        </small>
                                    @else
                                        <br><small class="text-warning">
                                            <i class="bi bi-exclamation-circle me-1"></i>Unverified
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    {{ $owner->created_at->format('M d, Y') }}
                                    <br>
                                    <small class="text-muted">{{ $owner->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.house-owners.show', $owner) }}" 
                                           class="btn btn-sm btn-outline-primary" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.house-owners.edit', $owner) }}" 
                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($owner->is_active)
                                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                    onclick="toggleStatus({{ $owner->id }}, 'deactivate')" title="Deactivate">
                                                <i class="bi bi-pause-circle"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-outline-success" 
                                                    onclick="toggleStatus({{ $owner->id }}, 'activate')" title="Activate">
                                                <i class="bi bi-play-circle"></i>
                                            </button>
                                        @endif
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                    data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="mailto:{{ $owner->email }}">
                                                        <i class="bi bi-envelope me-2"></i>Send Email
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="viewBuildings({{ $owner->id }})">
                                                        <i class="bi bi-building me-2"></i>View Buildings
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="#" 
                                                       onclick="deleteOwner({{ $owner->id }})">
                                                        <i class="bi bi-trash me-2"></i>Delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if($houseOwners->hasPages())
            <div class="card-footer">
                {{ $houseOwners->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@else
    <div class="text-center py-5">
        <i class="bi bi-people display-1 text-muted"></i>
        <h3 class="mt-3 text-muted">No House Owners Found</h3>
        <p class="text-muted">
            @if(request()->has('search'))
                No house owners match your search criteria.
            @else
                No house owners have been registered yet.
            @endif
        </p>
        <div class="mt-3">
            @if(request()->has('search'))
                <a href="{{ route('admin.house-owners.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-clockwise me-2"></i>Clear Search
                </a>
            @endif
            <a href="{{ route('admin.house-owners.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Add First House Owner
            </a>
        </div>
    </div>
@endif

@push('styles')
<style>
    .avatar-sm {
        width: 40px;
        height: 40px;
        font-size: 16px;
        font-weight: bold;
    }
</style>
@endpush

@push('scripts')
<script>
    function toggleStatus(ownerId, action) {
        const actionText = action === 'activate' ? 'activate' : 'deactivate';
        if (confirm(`Are you sure you want to ${actionText} this house owner?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/house-owners/${ownerId}/${action}`;
            form.innerHTML = `
                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    function deleteOwner(ownerId) {
        if (confirm('Are you sure you want to delete this house owner? This action cannot be undone.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/house-owners/${ownerId}`;
            form.innerHTML = `
                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    function viewBuildings(ownerId) {
        
        window.location.href = `/admin/house-owners/${ownerId}/buildings`;
    }
</script>
@endpush
@endsection