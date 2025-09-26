@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Flat {{ $flat->flat_number }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('house_owner.flats.edit', $flat) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil me-2"></i>
                Edit
            </a>
            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="bi bi-trash me-2"></i>
                Delete
            </button>
        </div>
        <a href="{{ route('house_owner.flats.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>
            Back to Flats
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Flat Number</h6>
                        <div class="h4 mb-0">{{ $flat->flat_number }}</div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Building</h6>
                        <div class="h5 mb-0">{{ $flat->building->name }}</div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-3">
                        <h6 class="text-muted">Floor</h6>
                        <div class="h5 mb-0">
                            @if($flat->floor !== null)
                                {{ $flat->floor }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted">Bedrooms</h6>
                        <div class="h5 mb-0">
                            @if($flat->bedrooms)
                                {{ $flat->bedrooms }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted">Bathrooms</h6>
                        <div class="h5 mb-0">
                            @if($flat->bathrooms)
                                {{ $flat->bathrooms }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted">Size</h6>
                        <div class="h5 mb-0">
                            @if($flat->size_sqft)
                                {{ number_format($flat->size_sqft) }} sq ft
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Monthly Rent</h6>
                        <div class="h4 mb-0 text-success">
                            @if($flat->rent_amount)
                                BDT {{ number_format($flat->rent_amount, 2) }}
                            @else
                                <span class="text-muted">Not set</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Availability</h6>
                        <span class="badge {{ $flat->is_available ? 'bg-success' : 'bg-secondary' }} fs-6">
                            {{ $flat->is_available ? 'Available for Rent' : 'Not Available' }}
                        </span>
                    </div>
                </div>

                @if($flat->description)
                    <div class="mb-4">
                        <h6 class="text-muted">Description</h6>
                        <p class="mb-0">{{ $flat->description }}</p>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Created Date</h6>
                        <div>{{ $flat->created_at->format('F d, Y \a\t g:i A') }}</div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Last Updated</h6>
                        <div>{{ $flat->updated_at->format('F d, Y \a\t g:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Resident Information -->
        @if($flat->currentTenant)
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-person me-2"></i>
                        Current Tenant
                    </h6>
                    <div class="btn-group">
                        <a href="{{ route('house_owner.tenants.show', $flat->currentTenant) }}" class="btn btn-sm btn-outline-primary">
                            View Details
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#removeTenantModal">
                            Remove Tenant
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Name</h6>
                            <div class="mb-3">{{ $flat->currentTenant->name }}</div>
                            
                            <h6 class="text-muted">Email</h6>
                            <div class="mb-3">
                                <a href="mailto:{{ $flat->currentTenant->email }}">{{ $flat->currentTenant->email }}</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Phone</h6>
                            <div class="mb-3">
                                @if($flat->currentTenant->phone)
                                    <a href="tel:{{ $flat->currentTenant->phone }}">{{ $flat->currentTenant->phone }}</a>
                                @else
                                    <span class="text-muted">Not provided</span>
                                @endif
                            </div>
                            
                            <h6 class="text-muted">Status</h6>
                            <div class="mb-3">
                                <span class="badge bg-{{ $flat->currentTenant->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($flat->currentTenant->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Move-in Date</h6>
                            <div>{{ $flat->currentTenant->move_in_date?->format('F d, Y') ?? 'Not specified' }}</div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Lease End Date</h6>
                            <div>{{ $flat->currentTenant->lease_end_date?->format('F d, Y') ?? 'Not specified' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="card mt-4">
                <div class="card-body text-center">
                    <i class="bi bi-person-x display-1 text-muted mb-3"></i>
                    <h5>No Current Tenant</h5>
                    <p class="text-muted">This flat is currently unoccupied.</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignTenantModal">
                        <i class="bi bi-person-plus me-2"></i>
                        Assign Tenant
                    </button>
                </div>
            </div>
        @endif

        <!-- Recent Bills -->
        @if($recentBills->count() > 0)
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">Recent Bills</h6>
                    <a href="{{ route('house_owner.bills.index', ['flat' => $flat->id]) }}" class="btn btn-sm btn-outline-primary">
                        View All Bills
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Bill ID</th>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBills as $bill)
                                    <tr>
                                        <td>
                                            <a href="{{ route('house_owner.bills.show', $bill) }}" class="text-decoration-none">
                                                #{{ $bill->id }}
                                            </a>
                                        </td>
                                        <td>{{ $bill->billCategory->name }}</td>
                                        <td>BDT {{ number_format($bill->amount, 2) }}</td>
                                        <td>{{ $bill->due_date->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $bill->status === 'paid' ? 'success' : ($bill->status === 'overdue' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($bill->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="bi bi-graph-up me-2"></i>
                    Flat Statistics
                </h6>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-center">
                            <div class="h4 mb-0 text-primary">{{ $totalBills }}</div>
                            <small class="text-muted">Total Bills</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <div class="h4 mb-0 text-success">BDT {{ number_format($totalRevenue, 2) }}</div>
                            <small class="text-muted">Total Revenue</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <div class="h4 mb-0 text-info">{{ $paidBills }}</div>
                            <small class="text-muted">Paid Bills</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <div class="h4 mb-0 text-warning">{{ $unpaidBills }}</div>
                            <small class="text-muted">Unpaid Bills</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="bi bi-tools me-2"></i>
                    Quick Actions
                </h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('house_owner.bills.create', ['flat_id' => $flat->id]) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-plus-circle me-2"></i>
                        Create Bill
                    </a>
                    @if($flat->currentTenant)
                        <a href="{{ route('house_owner.tenants.show', $flat->currentTenant) }}" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-person me-2"></i>
                            View Tenant
                        </a>
                    @else
                        <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#assignTenantModal">
                            <i class="bi bi-person-plus me-2"></i>
                            Assign Tenant
                        </button>
                    @endif
                    <a href="{{ route('house_owner.flats.edit', $flat) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-pencil me-2"></i>
                        Edit Flat
                    </a>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="bi bi-building me-2"></i>
                    Building Info
                </h6>
                <div class="small">
                    <div class="mb-2">
                        <strong>Name:</strong> {{ $flat->building->name }}
                    </div>
                    <div class="mb-2">
                        <strong>Address:</strong> {{ $flat->building->address }}
                    </div>
                    <div class="mb-2">
                        <strong>Total Flats:</strong> {{ $flat->building->flats_count ?? $flat->building->flats->count() }}
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('house_owner.buildings.show', $flat->building) }}" class="btn btn-outline-primary btn-sm">
                        View Building Details
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Flat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete flat "<strong>{{ $flat->flat_number }}</strong>"?</p>
                @if($flat->currentTenant)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        This flat has a current tenant. Please remove the tenant before deleting the flat.
                    </div>
                @endif
                @if($totalBills > 0)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        This flat has {{ $totalBills }} associated bills. Deleting this flat will also remove all associated bills.
                    </div>
                @endif
                <p class="text-muted">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('house_owner.flats.destroy', $flat) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" {{ $flat->currentTenant ? 'disabled' : '' }}>
                        Delete Flat
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Remove Resident Modal -->
@if($flat->currentTenant)
<div class="modal fade" id="removeTenantModal" tabindex="-1" aria-labelledby="removeTenantModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeTenantModalLabel">Remove Tenant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove "<strong>{{ $flat->currentTenant->name }}</strong>" from this flat?</p>
                <p class="text-muted">The tenant record will be marked as inactive and the flat will become available for rent.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('house_owner.flats.remove-tenant', $flat) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-warning">Remove Tenant</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Assign Resident Modal -->
@if(!$flat->currentTenant)
<div class="modal fade" id="assignTenantModal" tabindex="-1" aria-labelledby="assignTenantModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignTenantModalLabel">Assign Resident to {{ $flat->flat_number }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('house_owner.flats.assign-tenant', $flat) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tenant_id" class="form-label">Select Tenant</label>
                        <select class="form-select" id="tenant_id" name="tenant_id" required>
                            <option value="">Choose a tenant...</option>
                            @foreach(auth('house_owner')->user()->tenants()->whereNull('flat_id')->get() as $tenant)
                                <option value="{{ $tenant->id }}">
                                    {{ $tenant->name }} - {{ $tenant->email }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Only unassigned tenants are shown.</div>
                    </div>
                    
                    @if(auth('house_owner')->user()->tenants()->whereNull('flat_id')->count() == 0)
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            No unassigned tenants available. <a href="{{ route('house_owner.tenants.create') }}">Add a new tenant</a> first.
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" @if(auth('house_owner')->user()->tenants()->whereNull('flat_id')->count() == 0) disabled @endif>
                        <i class="bi bi-person-plus me-2"></i>
                        Assign Tenant
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection