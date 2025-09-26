@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ $houseOwner->name }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.house-owners.edit', $houseOwner) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil me-2"></i>
                Edit
            </a>
            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="bi bi-trash me-2"></i>
                Delete
            </button>
        </div>
        <a href="{{ route('admin.house-owners.index') }}" class="btn btn-outline-secondary">
            <i class="fa fa-arrow-left me-2"></i>
            Back to House Owners
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
                        <div class="h4 mb-0">{{ $houseOwner->name }}</div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Email Address</h6>
                        <div class="h5 mb-0">
                            <a href="mailto:{{ $houseOwner->email }}">{{ $houseOwner->email }}</a>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Phone Number</h6>
                        <div class="h5 mb-0">
                            @if($houseOwner->phone)
                                <a href="tel:{{ $houseOwner->phone }}">{{ $houseOwner->phone }}</a>
                            @else
                                <span class="text-muted">Not provided</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Account Status</h6>
                        <span class="badge {{ $houseOwner->is_active ? 'bg-success' : 'bg-secondary' }} fs-6">
                            {{ $houseOwner->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                @if($houseOwner->address || $houseOwner->city || $houseOwner->state || $houseOwner->country)
                    <div class="mb-4">
                        <h6 class="text-muted">Address Information</h6>
                        <div class="address-block">
                            @if($houseOwner->address)
                                <div>{{ $houseOwner->address }}</div>
                            @endif
                            <div>
                                @if($houseOwner->city){{ $houseOwner->city }}@endif
                                @if($houseOwner->city && $houseOwner->state), @endif
                                @if($houseOwner->state){{ $houseOwner->state }}@endif
                                @if($houseOwner->postal_code) {{ $houseOwner->postal_code }}@endif
                            </div>
                            @if($houseOwner->country)
                                <div>{{ $houseOwner->country }}</div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Account Created</h6>
                        <div>{{ $houseOwner->created_at->format('F d, Y \a\t g:i A') }}</div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Last Updated</h6>
                        <div>{{ $houseOwner->updated_at->format('F d, Y \a\t g:i A') }}</div>
                    </div>
                </div>

                @if($houseOwner->email_verified_at)
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Email Verified</h6>
                            <div class="d-flex align-items-center">
                                <i class="fa fa-check-circle text-success me-2"></i>
                                {{ $houseOwner->email_verified_at->format('F d, Y \a\t g:i A') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Buildings Overview -->
        @if($buildings->count() > 0)
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">Buildings ({{ $buildings->count() }})</h6>
                    <a href="{{ route('admin.buildings.index', ['house_owner' => $houseOwner->id]) }}" class="btn btn-sm btn-outline-primary">
                        View All Buildings
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Building Name</th>
                                    <th>Address</th>
                                    <th>Flats</th>
                                    <th>Tenants</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($buildings as $building)
                                    <tr>
                                        <td>
                                            <strong>{{ $building->name }}</strong>
                                        </td>
                                        <td>{{ Str::limit($building->address, 40) }}</td>
                                        <td>{{ $building->flats_count ?? $building->flats->count() }}</td>
                                        <td>{{ $building->active_tenants_count ?? 0 }}</td>
                                        <td>
                                            <a href="{{ route('admin.buildings.show', $building) }}" class="btn btn-sm btn-outline-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Recent Activity -->
        @if($recentBills->count() > 0)
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">Recent Bills ({{ $recentBills->count() }})</h6>
                    <a href="{{ route('admin.bills.index', ['house_owner' => $houseOwner->id]) }}" class="btn btn-sm btn-outline-primary">
                        View All Bills
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Bill ID</th>
                                    <th>Building</th>
                                    <th>Flat</th>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBills as $bill)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.bills.show', $bill) }}" class="text-decoration-none">
                                                #{{ $bill->id }}
                                            </a>
                                        </td>
                                        <td>{{ $bill->building->name }}</td>
                                        <td>{{ $bill->flat->flat_number }}</td>
                                        <td>{{ $bill->billCategory->name }}</td>
                                        <td>BDT {{ number_format($bill->amount, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $bill->status === 'paid' ? 'success' : ($bill->status === 'overdue' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($bill->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $bill->due_date->format('M d, Y') }}</td>
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
                    <i class="fa fa-chart-bar me-2"></i>
                    Portfolio Statistics
                </h6>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="h3 mb-0 text-primary">{{ $totalBuildings }}</div>
                            <small class="text-muted">Buildings</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="h3 mb-0 text-info">{{ $totalFlats }}</div>
                            <small class="text-muted">Flats</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <div class="h4 mb-0 text-success">{{ $activeTenants }}</div>
                            <small class="text-muted">Active Tenants</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <div class="h4 mb-0 text-warning">{{ $availableFlats }}</div>
                            <small class="text-muted">Available Flats</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fa fa-dollar-sign me-2"></i>
                    Financial Overview
                </h6>
                <div class="row g-2">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small">Total Revenue:</span>
                            <span class="h5 mb-0 text-success">BDT {{ number_format($totalRevenue, 2) }}</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small">Pending Bills:</span>
                            <span class="h5 mb-0 text-warning">BDT {{ number_format($pendingAmount, 2) }}</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small">Overdue Bills:</span>
                            <span class="h5 mb-0 text-danger">BDT {{ number_format($overdueAmount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fa fa-tasks me-2"></i>
                    Bill Status Breakdown
                </h6>
                <div class="row g-2">
                    <div class="col-4">
                        <div class="text-center">
                            <div class="h4 mb-0 text-success">{{ $paidBills }}</div>
                            <small class="text-muted">Paid</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center">
                            <div class="h4 mb-0 text-warning">{{ $unpaidBills }}</div>
                            <small class="text-muted">Unpaid</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center">
                            <div class="h4 mb-0 text-danger">{{ $overdueBills }}</div>
                            <small class="text-muted">Overdue</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fa fa-tools me-2"></i>
                    Quick Actions
                </h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.buildings.create', ['house_owner_id' => $houseOwner->id]) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fa fa-plus me-2"></i>
                        Add Building
                    </a>
                    <a href="{{ route('admin.tenants.create', ['house_owner_id' => $houseOwner->id]) }}" class="btn btn-outline-success btn-sm">
                        <i class="fa fa-user-plus me-2"></i>
                        Add Tenant
                    </a>
                    <a href="{{ route('admin.bills.create', ['house_owner_id' => $houseOwner->id]) }}" class="btn btn-outline-warning btn-sm">
                        <i class="fa fa-file-invoice me-2"></i>
                        Create Bill
                    </a>
                    <a href="{{ route('admin.house-owners.edit', $houseOwner) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-pencil me-2"></i>
                        Edit Account
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
                <h5 class="modal-title" id="deleteModalLabel">Delete House Owner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete house owner "<strong>{{ $houseOwner->name }}</strong>"?</p>
                @if($totalBuildings > 0 || $totalFlats > 0 || $activeTenants > 0)
                    <div class="alert alert-warning">
                        <i class="fa fa-exclamation-triangle me-2"></i>
                        This house owner has associated data:
                        <ul class="mb-0 mt-2">
                            @if($totalBuildings > 0)<li>{{ $totalBuildings }} buildings</li>@endif
                            @if($totalFlats > 0)<li>{{ $totalFlats }} flats</li>@endif
                            @if($activeTenants > 0)<li>{{ $activeTenants }} active tenants</li>@endif
                        </ul>
                        Deleting this account will also remove all associated data.
                    </div>
                @endif
                <p class="text-muted">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.house-owners.destroy', $houseOwner) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete House Owner</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection