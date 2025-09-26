@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ $tenant->name }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.tenants.edit', $tenant) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil me-2"></i>
                Edit
            </a>
            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="bi bi-trash me-2"></i>
                Delete
            </button>
        </div>
        <a href="{{ route('admin.tenants.index') }}" class="btn btn-outline-secondary">
            <i class="fa fa-arrow-left me-2"></i>
            Back to Tenants
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Full Name</h6>
                        <div class="h4 mb-0">{{ $tenant->name }}</div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Email Address</h6>
                        <div class="h5 mb-0">
                            <a href="mailto:{{ $tenant->email }}">{{ $tenant->email }}</a>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Phone Number</h6>
                        <div class="h5 mb-0">
                            @if($tenant->phone)
                                <a href="tel:{{ $tenant->phone }}">{{ $tenant->phone }}</a>
                            @else
                                <span class="text-muted">Not provided</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Status</h6>
                        <div class="h5 mb-0">
                            @if($tenant->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <h6 class="text-muted">Address</h6>
                        <div class="h5 mb-0">
                            @if($tenant->address)
                                {{ $tenant->address }}
                            @else
                                <span class="text-muted">Not provided</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Security Deposit</h6>
                        <div class="h5 mb-0">BDT {{ number_format($tenant->security_deposit, 2) }}</div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Created</h6>
                        <div class="h6 mb-0 text-muted">{{ $tenant->created_at->format('M d, Y') }}</div>
                    </div>
                </div>

                @if($tenant->lease_start_date || $tenant->lease_end_date)
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Lease Start Date</h6>
                        <div class="h6 mb-0">
                            @if($tenant->lease_start_date)
                                {{ $tenant->lease_start_date->format('M d, Y') }}
                            @else
                                <span class="text-muted">Not set</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Lease End Date</h6>
                        <div class="h6 mb-0">
                            @if($tenant->lease_end_date)
                                {{ $tenant->lease_end_date->format('M d, Y') }}
                            @else
                                <span class="text-muted">Not set</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Property Information -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Property Information</h5>
            </div>
            <div class="card-body">
                @if($tenant->building && $tenant->flat)
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Building</h6>
                        <div class="h6 mb-3">
                            <a href="{{ route('admin.buildings.show', $tenant->building) }}" class="text-decoration-none">
                                {{ $tenant->building->name }}
                            </a>
                        </div>
                        <div class="text-muted small">
                            {{ $tenant->building->address }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Flat</h6>
                        <div class="h6 mb-3">
                            <a href="{{ route('admin.flats.show', $tenant->flat) }}" class="text-decoration-none">
                                Flat {{ $tenant->flat->flat_number }}
                            </a>
                        </div>
                        <div class="text-muted small">
                            {{ $tenant->flat->type }} • Floor {{ $tenant->flat->floor }}
                            @if($tenant->flat->area_sqft)
                                • {{ $tenant->flat->area_sqft }} sq ft
                            @endif
                        </div>
                    </div>
                </div>

                @if($tenant->houseOwner)
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">House Owner</h6>
                        <div class="h6 mb-0">
                            <a href="{{ route('admin.house-owners.show', $tenant->houseOwner) }}" class="text-decoration-none">
                                {{ $tenant->houseOwner->name }}
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                @else
                <div class="text-muted">No property assigned</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.tenants.edit', $tenant) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-pencil me-2"></i>
                        Edit Tenant
                    </a>
                    @if($tenant->flat)
                    <a href="{{ route('admin.flats.show', $tenant->flat) }}" class="btn btn-outline-info btn-sm">
                        <i class="fa fa-home me-2"></i>
                        View Flat Details
                    </a>
                    @endif
                    @if($tenant->building)
                    <a href="{{ route('admin.buildings.show', $tenant->building) }}" class="btn btn-outline-info btn-sm">
                        <i class="fa fa-building me-2"></i>
                        View Building
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Assigned By -->
        @if($tenant->assignedBy)
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title mb-0">Assignment Info</h6>
            </div>
            <div class="card-body">
                <div class="small text-muted">Assigned by</div>
                <div class="fw-bold">{{ $tenant->assignedBy->name }}</div>
                <div class="small text-muted">{{ $tenant->created_at->format('M d, Y g:i A') }}</div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Tenant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong>{{ $tenant->name }}</strong>?</p>
                <p class="text-danger small">This action will also free up the assigned flat and cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.tenants.destroy', $tenant) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Tenant</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection