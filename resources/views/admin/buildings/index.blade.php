@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Buildings Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.buildings.create') }}" class="btn btn-primary">
                <i class="fa fa-plus me-2"></i>
                Add Building
            </a>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.buildings.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" name="search" id="search" class="form-control" 
                           placeholder="Building name, address, city..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label for="house_owner" class="form-label">House Owner</label>
                    <select name="house_owner" id="house_owner" class="form-select">
                        <option value="">All House Owners</option>
                        @foreach($houseOwners as $owner)
                            <option value="{{ $owner->id }}" {{ request('house_owner') == $owner->id ? 'selected' : '' }}>
                                {{ $owner->name }}
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
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fa fa-search me-1"></i>
                            Filter
                        </button>
                        <a href="{{ route('admin.buildings.index') }}" class="btn btn-outline-secondary">
                            <i class="fa fa-times me-1"></i>
                            Clear
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Buildings Table -->
<div class="card">
    <div class="card-body">
        @if($buildings->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                                   class="text-decoration-none text-dark">
                                    Building Name
                                    @if(request('sort') === 'name')
                                        <i class="fa fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </a>
                            </th>
                            <th>House Owner</th>
                            <th>Address</th>
                            <th>Flats</th>
                            <th>Occupancy</th>
                            <th>Status</th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                                   class="text-decoration-none text-dark">
                                    Created
                                    @if(request('sort') === 'created_at')
                                        <i class="fa fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($buildings as $building)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $building->name }}</div>
                                    @if($building->description)
                                        <div class="small text-muted">{{ Str::limit($building->description, 50) }}</div>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.house-owners.show', $building->houseOwner) }}" class="text-decoration-none">
                                        {{ $building->houseOwner->name }}
                                    </a>
                                    <div class="small text-muted">{{ $building->houseOwner->email }}</div>
                                </td>
                                <td>
                                    <div>{{ $building->address }}</div>
                                    @if($building->city || $building->state || $building->zip_code)
                                        <div class="small text-muted">
                                            {{ $building->city }}{{ $building->state ? ', ' . $building->state : '' }}{{ $building->zip_code ? ' ' . $building->zip_code : '' }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $building->flats_count ?? $building->flats->count() }}</div>
                                    <div class="small text-muted">{{ $building->total_floors }} floors</div>
                                </td>
                                <td>
                                    @php
                                        $occupied = $building->flats->where('is_occupied', true)->count();
                                        $total = $building->flats->count();
                                        $occupancy = $total > 0 ? round(($occupied / $total) * 100) : 0;
                                    @endphp
                                    <div class="fw-bold">{{ $occupied }}/{{ $total }}</div>
                                    <div class="small text-muted">{{ $occupancy }}% occupied</div>
                                </td>
                                <td>
                                    @if($building->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="small text-muted">{{ $building->created_at->format('M d, Y') }}</div>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.buildings.show', $building) }}" class="btn btn-outline-info" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.buildings.edit', $building) }}" class="btn btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" title="Delete" 
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $building->id }}">
                                            <i class="bi bi-trash"></i>
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
                <div class="small text-muted">
                    Showing {{ $buildings->firstItem() }} to {{ $buildings->lastItem() }} of {{ $buildings->total() }} buildings
                </div>
                <div>
                    {{ $buildings->appends(request()->query())->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <div class="display-4 text-muted mb-3">
                    <i class="fa fa-building"></i>
                </div>
                <h5>No buildings found</h5>
                <p class="text-muted">
                    @if(request()->hasAny(['search', 'house_owner', 'status']))
                        No buildings match your current filters.
                        <a href="{{ route('admin.buildings.index') }}" class="text-decoration-none">Clear filters</a>
                    @else
                        Start by adding your first building to the system.
                    @endif
                </p>
                @if(!request()->hasAny(['search', 'house_owner', 'status']))
                <a href="{{ route('admin.buildings.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus me-2"></i>
                    Add First Building
                </a>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- Delete Modals -->
@foreach($buildings as $building)
<div class="modal fade" id="deleteModal{{ $building->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Building</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong>{{ $building->name }}</strong>?</p>
                <p class="text-danger small">This will also delete all flats, tenants, and bills associated with this building. This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.buildings.destroy', $building) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Building</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection