@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-building"></i>
                        Buildings for {{ $houseOwner->name }}
                    </h3>
                    <div class="btn-group">
                        <a href="{{ route('admin.house-owners.show', $houseOwner) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to House Owner
                        </a>
                        <a href="{{ route('admin.house-owners.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list"></i> All House Owners
                        </a>
                    </div>
                </div>

                <!-- Search and Filters -->
                <div class="card-body border-bottom">
                    <form method="GET" action="{{ route('admin.house-owners.buildings.index', $houseOwner) }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="search">Search Buildings</label>
                                    <input type="text" 
                                           id="search" 
                                           name="search" 
                                           class="form-control" 
                                           placeholder="Name, address, city..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select id="status" name="status" class="form-control">
                                        <option value="">All Status</option>
                                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sort">Sort By</label>
                                    <select id="sort" name="sort" class="form-control">
                                        <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date Created</option>
                                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name</option>
                                        <option value="city" {{ request('sort') === 'city' ? 'selected' : '' }}>City</option>
                                        <option value="updated_at" {{ request('sort') === 'updated_at' ? 'selected' : '' }}>Last Updated</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="btn-group d-block">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-search"></i> Search
                                        </button>
                                        <a href="{{ route('admin.house-owners.buildings.index', $houseOwner) }}" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-times"></i> Clear
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body p-0">
                    @if($buildings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Building Details</th>
                                        <th>Location</th>
                                        <th>Statistics</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($buildings as $building)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="ml-3">
                                                        <h6 class="mb-0">{{ $building->name }}</h6>
                                                        <small class="text-muted">ID: #{{ $building->id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $building->city }}</strong><br>
                                                    <small class="text-muted">{{ Str::limit($building->address, 40) }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $totalFlats = $building->flats->count();
                                                    $occupiedFlats = $building->flats->where('is_occupied', true)->count();
                                                @endphp
                                                <div class="text-center">
                                                    <span class="badge badge-info">{{ $totalFlats }} Flats</span><br>
                                                    <small class="text-success">{{ $occupiedFlats }} Occupied</small><br>
                                                    <small class="text-warning">{{ $totalFlats - $occupiedFlats }} Vacant</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $building->is_active ? 'success' : 'danger' }}">
                                                    {{ $building->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <small>
                                                    {{ $building->created_at->format('M d, Y') }}<br>
                                                    <span class="text-muted">{{ $building->created_at->diffForHumans() }}</span>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.buildings.show', $building) }}" 
                                                       class="btn btn-outline-info btn-sm" 
                                                       title="View Details">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.buildings.edit', $building) }}" 
                                                       class="btn btn-outline-primary btn-sm" 
                                                       title="Edit Building">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-outline-danger btn-sm" 
                                                            title="Delete Building"
                                                            onclick="confirmDelete({{ $building->id }}, '{{ $building->name }}')">
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
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Showing {{ $buildings->firstItem() }} to {{ $buildings->lastItem() }} of {{ $buildings->total() }} results
                            </div>
                            {{ $buildings->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-building display-1 text-muted"></i>
                            <h3 class="mt-3">No Buildings Found</h3>
                            <p class="text-muted">
                                @if(request()->filled('search') || request()->filled('status'))
                                    No buildings match your search criteria.
                                @else
                                    {{ $houseOwner->name }} hasn't added any buildings yet.
                                @endif
                            </p>
                            <div class="mt-3">
                                @if(request()->filled('search') || request()->filled('status'))
                                    <a href="{{ route('admin.house-owners.buildings.index', $houseOwner) }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Clear Filters
                                    </a>
                                @endif
                                <a href="{{ route('admin.buildings.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add Building
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- House Owner Summary Card -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user"></i>
                    House Owner Summary
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="text-primary">{{ $buildings->total() }}</h4>
                            <small class="text-muted">Total Buildings</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            @php
                                $totalFlats = $buildings->sum(function($building) {
                                    return $building->flats->count();
                                });
                            @endphp
                            <h4 class="text-info">{{ $totalFlats }}</h4>
                            <small class="text-muted">Total Flats</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            @php
                                $occupiedFlats = $buildings->sum(function($building) {
                                    return $building->flats->where('is_occupied', true)->count();
                                });
                            @endphp
                            <h4 class="text-success">{{ $occupiedFlats }}</h4>
                            <small class="text-muted">Occupied Flats</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            @php
                                $vacantFlats = $totalFlats - $occupiedFlats;
                            @endphp
                            <h4 class="text-warning">{{ $vacantFlats }}</h4>
                            <small class="text-muted">Vacant Flats</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete building <strong id="buildingName"></strong>?</p>
                <p class="text-danger"><small>This action cannot be undone!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(buildingId, buildingName) {
    document.getElementById('buildingName').textContent = buildingName;
    document.getElementById('deleteForm').action = `/admin/buildings/${buildingId}`;
    $('#deleteModal').modal('show');
}
</script>
@endpush
@endsection