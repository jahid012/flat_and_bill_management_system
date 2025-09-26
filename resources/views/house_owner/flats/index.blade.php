@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Flats Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('house_owner.flats.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            Add New Flat
        </a>
    </div>
</div>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('house_owner.flats.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" name="search" id="search" class="form-control" 
                           value="{{ request('search') }}" placeholder="Flat number or type">
                </div>
                <div class="col-md-3">
                    <label for="building_id" class="form-label">Building</label>
                    <select name="building_id" id="building_id" class="form-select">
                        <option value="">All Buildings</option>
                        @foreach($buildings as $building)
                            <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                                {{ $building->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                        <option value="vacant" {{ request('status') == 'vacant' ? 'selected' : '' }}>Vacant</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="type" class="form-label">Type</label>
                    <select name="type" id="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="1BHK" {{ request('type') == '1BHK' ? 'selected' : '' }}>1BHK</option>
                        <option value="2BHK" {{ request('type') == '2BHK' ? 'selected' : '' }}>2BHK</option>
                        <option value="3BHK" {{ request('type') == '3BHK' ? 'selected' : '' }}>3BHK</option>
                        <option value="4BHK" {{ request('type') == '4BHK' ? 'selected' : '' }}>4BHK</option>
                        <option value="Studio" {{ request('type') == 'Studio' ? 'selected' : '' }}>Studio</option>
                        <option value="Other" {{ request('type') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi bi-search"></i>
                        </button>
                        <a href="{{ route('house_owner.flats.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Flats Table -->
<div class="card">
    <div class="card-body">
        @if($flats->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Flat Number</th>
                            <th>Building</th>
                            <th>Floor</th>
                            <th>Type</th>
                            <th>Area (sqft)</th>
                            <th>Rent Amount</th>
                            <th>Status</th>
                            <th>Current Tenant</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($flats as $flat)
                            <tr>
                                <td>
                                    <strong>{{ $flat->flat_number }}</strong>
                                </td>
                                <td>{{ $flat->building->name }}</td>
                                <td>{{ $flat->floor }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $flat->type }}</span>
                                </td>
                                <td>{{ $flat->area_sqft ? number_format($flat->area_sqft, 0) : 'N/A' }}</td>
                                <td>BDT {{ number_format($flat->rent_amount, 2) }}</td>
                                <td>
                                    @if($flat->is_occupied)
                                        <span class="badge bg-success">Occupied</span>
                                    @else
                                        <span class="badge bg-warning">Vacant</span>
                                    @endif
                                </td>
                                <td>
                                    @if($flat->currentTenant)
                                        <div>
                                            <strong>{{ $flat->currentTenant->name }}</strong><br>
                                            <small class="text-muted">{{ $flat->currentTenant->phone }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">No tenant</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('house_owner.flats.show', $flat) }}" class="btn btn-outline-info" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('house_owner.flats.edit', $flat) }}" class="btn btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('house_owner.flats.destroy', $flat) }}" method="POST" class="d-inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this flat?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $flats->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-house-door" style="font-size: 4rem; color: #6c757d;"></i>
                <h3 class="mt-3">No Flats Found</h3>
                <p class="text-muted">You haven't added any flats yet or none match your search criteria.</p>
                <a href="{{ route('house_owner.flats.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>
                    Add Your First Flat
                </a>
            </div>
        @endif
    </div>
</div>
@endsection