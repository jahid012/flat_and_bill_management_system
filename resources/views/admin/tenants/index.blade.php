@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Resident/Tenent Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.tenants.create') }}" class="btn btn-primary">
            <i class="fa fa-plus me-2"></i>
            Add New Resident
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.tenants.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" name="search" id="search" class="form-control" 
                           value="{{ request('search') }}" placeholder="Name, email, or phone">
                </div>
                <div class="col-md-2">
                    <label for="house_owner" class="form-label">House Owner</label>
                    <select name="house_owner" id="house_owner" class="form-select">
                        <option value="">All Owners</option>
                        @foreach($houseOwners as $owner)
                            <option value="{{ $owner->id }}" {{ request('house_owner') == $owner->id ? 'selected' : '' }}>
                                {{ $owner->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="building" class="form-label">Building</label>
                    <select name="building" id="building" class="form-select">
                        <option value="">All Buildings</option>
                        @foreach($buildings as $building)
                            <option value="{{ $building->id }}" {{ request('building') == $building->id ? 'selected' : '' }}>
                                {{ $building->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search me-2"></i>Filter
                        </button>
                        <a href="{{ route('admin.tenants.index') }}" class="btn btn-outline-secondary">Clear</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <div class="h3 text-primary mb-1">{{ $stats['total'] }}</div>
                <div class="text-muted">Total Residents</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <div class="h3 text-success mb-1">{{ $stats['active'] }}</div>
                <div class="text-muted">Active Residents</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <div class="h3 text-secondary mb-1">{{ $stats['inactive'] }}</div>
                <div class="text-muted">Inactive Residents</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <div class="h3 text-info mb-1">{{ $stats['new_this_month'] }}</div>
                <div class="text-muted">New This Month</div>
            </div>
        </div>
    </div>
</div>

<!-- Residents Table -->
<div class="card">
    <div class="card-body">
        @if($tenants->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                <a href="{{ route('admin.tenants.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="text-decoration-none text-dark">
                                    Resident Name
                                    @if(request('sort') === 'name')
                                        <i class="fa fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Contact</th>
                            <th>House Owner</th>
                            <th>Building</th>
                            <th>Flat</th>
                            <th>
                                <a href="{{ route('admin.tenants.index', array_merge(request()->query(), ['sort' => 'move_in_date', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="text-decoration-none text-dark">
                                    Move-in Date
                                    @if(request('sort') === 'move_in_date')
                                        <i class="fa fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tenants as $tenant)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <div class="fw-bold">{{ $tenant->name }}</div>
                                            <small class="text-muted">ID: #{{ $tenant->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <a href="mailto:{{ $tenant->email }}" class="text-decoration-none">{{ $tenant->email }}</a>
                                    </div>
                                    @if($tenant->phone)
                                        <div>
                                            <a href="tel:{{ $tenant->phone }}" class="text-decoration-none small">{{ $tenant->phone }}</a>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.house-owners.show', $tenant->houseOwner) }}" class="text-decoration-none">
                                        {{ $tenant->houseOwner->name }}
                                    </a>
                                </td>
                                <td>
                                    @if($tenant->flat && $tenant->flat->building)
                                        <a href="{{ route('admin.buildings.show', $tenant->flat->building) }}" class="text-decoration-none">
                                            {{ $tenant->flat->building->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </td>
                                <td>
                                    @if($tenant->flat)
                                        <a href="{{ route('admin.flats.show', $tenant->flat) }}" class="text-decoration-none">
                                            {{ $tenant->flat->flat_number }}
                                        </a>
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </td>
                                <td>
                                    @if($tenant->move_in_date)
                                        {{ $tenant->move_in_date->format('M d, Y') }}
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $tenant->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($tenant->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.tenants.show', $tenant) }}" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.tenants.edit', $tenant) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $tenant->id }}">
                                            <i class="bi bi-trash me-2"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $tenants->firstItem() }} to {{ $tenants->lastItem() }} of {{ $tenants->total() }} results
                </div>
                {{ $tenants->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fa fa-users fa-3x text-muted mb-3"></i>
                <h5>No Residents Found</h5>
                <p class="text-muted">No tenants match your current filter criteria.</p>
                <a href="{{ route('admin.tenants.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus me-2"></i>Add First Resident
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Delete Modals -->
@foreach($tenants as $tenant)
    <div class="modal fade" id="deleteModal{{ $tenant->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $tenant->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel{{ $tenant->id }}">Delete Resident</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete tenant "<strong>{{ $tenant->name }}</strong>"?</p>
                    <p class="text-muted">This action cannot be undone and will also remove all associated bills.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.tenants.destroy', $tenant) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Resident</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection