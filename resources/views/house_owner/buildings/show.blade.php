@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-building"></i>
                        {{ $building->name }}
                    </h3>
                    <div class="btn-group">
                        <a href="{{ route('house_owner.buildings.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Buildings
                        </a>
                        <a href="{{ route('house_owner.buildings.edit', $building) }}" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Edit Building
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Building Information -->
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3">Building Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $building->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Address:</strong></td>
                                    <td>{{ $building->address }}</td>
                                </tr>
                                <tr>
                                    <td><strong>City:</strong></td>
                                    <td>{{ $building->city }}</td>
                                </tr>
                                <tr>
                                    <td><strong>State:</strong></td>
                                    <td>{{ $building->state }}</td>
                                </tr>
                                <tr>
                                    <td><strong>ZIP Code:</strong></td>
                                    <td>{{ $building->zip_code }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Floors:</strong></td>
                                    <td>{{ $building->total_floors }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Units:</strong></td>
                                    <td>{{ $building->total_flats }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $building->is_active ? 'success' : 'danger' }}">
                                            {{ $building->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $building->created_at->format('M d, Y') }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Statistics -->
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3">Statistics</h5>
                            <div class="row">
                                <div class="col-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info">
                                            <i class="fas fa-home"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Total Flats</span>
                                            <span class="info-box-number">{{ $building->flats->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-success">
                                            <i class="fas fa-users"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Occupied</span>
                                            <span class="info-box-number">{{ $building->flats->where('is_occupied', true)->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-warning">
                                            <i class="fas fa-key"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Vacant</span>
                                            <span class="info-box-number">{{ $building->flats->where('is_occupied', false)->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-primary">
                                            <i class="fas fa-user-friends"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Tenants</span>
                                            <span class="info-box-number">{{ $building->tenants->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($building->description)
                        <div class="col-12 mt-3">
                            <h5 class="border-bottom pb-2 mb-3">Description</h5>
                            <p class="text-muted">{{ $building->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Flats Overview -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-home"></i>
                        Flats in {{ $building->name }}
                    </h5>
                    <a href="{{ route('house_owner.flats.create', ['building_id' => $building->id]) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Flat
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($building->flats->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Flat Number</th>
                                        <th>Floor</th>
                                        <th>Type</th>
                                        <th>Rent</th>
                                        <th>Tenant</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($building->flats as $flat)
                                        <tr>
                                            <td>
                                                <strong>{{ $flat->flat_number }}</strong>
                                            </td>
                                            <td>{{ $flat->floor }}</td>
                                            <td>{{ $flat->flat_type ?? 'N/A' }}</td>
                                            <td>
                                                @if($flat->rent_amount)
                                                    ₹{{ number_format($flat->rent_amount, 2) }}
                                                @else
                                                    <span class="text-muted">Not set</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($flat->currentTenant)
                                                    <a href="{{ route('house_owner.tenants.show', $flat->currentTenant) }}" class="text-primary">
                                                        {{ $flat->currentTenant->name }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">Vacant</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $flat->is_occupied ? 'success' : 'warning' }}">
                                                    {{ $flat->is_occupied ? 'Occupied' : 'Vacant' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('house_owner.flats.show', $flat) }}" 
                                                       class="btn btn-outline-info btn-sm" 
                                                       title="View Details">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('house_owner.flats.edit', $flat) }}" 
                                                       class="btn btn-outline-primary btn-sm" 
                                                       title="Edit Flat">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-home" style="font-size: 4rem; color: #6c757d;"></i>
                            <h3 class="mt-3">No Flats Added</h3>
                            <p class="text-muted">This building doesn't have any flats yet.</p>
                            <a href="{{ route('house_owner.flats.create', ['building_id' => $building->id]) }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add First Flat
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tenants -->
    @if($building->tenants->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-friends"></i>
                        Recent Tenants
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Tenant</th>
                                    <th>Flat</th>
                                    <th>Contact</th>
                                    <th>Move In Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($building->tenants->take(5) as $tenant)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $tenant->name }}</strong><br>
                                                <small class="text-muted">{{ $tenant->email }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($tenant->flat)
                                                {{ $tenant->flat->flat_number }}
                                            @else
                                                <span class="text-muted">Not assigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $tenant->phone }}</small>
                                        </td>
                                        <td>
                                            @if($tenant->move_in_date)
                                                {{ $tenant->move_in_date->format('M d, Y') }}
                                            @else
                                                <span class="text-muted">Not set</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $tenant->status === 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($tenant->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('house_owner.tenants.show', $tenant) }}" 
                                               class="btn btn-outline-info btn-sm" 
                                               title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($building->tenants->count() > 5)
                    <div class="card-footer text-center">
                        <a href="{{ route('house_owner.tenants.index', ['building_id' => $building->id]) }}" class="btn btn-sm btn-outline-primary">
                            View All {{ $building->tenants->count() }} Tenants
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection