@extends('layouts.app')

@section('title', 'Flat Details')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Flat {{ $flat->flat_number }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.flats.index') }}">Flats</a></li>
                        <li class="breadcrumb-item active">Flat {{ $flat->flat_number }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Flat Actions -->
            <div class="row mb-3">
                <div class="col-12">
                    <a href="{{ route('admin.flats.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Flats
                    </a>
                    <a href="{{ route('admin.buildings.show', $flat->building) }}" class="btn btn-info">
                        <i class="fas fa-building"></i> View Building
                    </a>
                    <a href="{{ route('admin.flats.edit', $flat) }}" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Edit Flat
                    </a>
                    @if($flat->currentTenant)
                        <a href="{{ route('admin.tenants.show', $flat->currentTenant) }}" class="btn btn-success">
                            <i class="fas fa-user"></i> View Tenant
                        </a>
                    @endif
                    <form method="POST" action="{{ route('admin.flats.destroy', $flat) }}" class="d-inline" 
                          onsubmit="return confirm('Are you sure you want to delete this flat? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete Flat
                        </button>
                    </form>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box p-2 bg-info">
                        <div class="inner">
                            <h3>{{ $totalBills }}</h3>
                            <p>Total Bills</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box p-2 bg-success">
                        <div class="inner">
                            <h3>{{ $paidBills }}</h3>
                            <p>Paid Bills</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box p-2 bg-warning">
                        <div class="inner">
                            <h3>{{ $unpaidBills }}</h3>
                            <p>Unpaid Bills</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box p-2 bg-danger">
                        <div class="inner">
                            <h3>BDT {{ number_format($totalRevenue, 2) }}</h3>
                            <p>Total Revenue</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Flat Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Flat Information</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th style="width: 40%;">Flat Number:</th>
                                    <td>{{ $flat->flat_number }}</td>
                                </tr>
                                <tr>
                                    <th>Floor:</th>
                                    <td>{{ $flat->floor }}</td>
                                </tr>
                                <tr>
                                    <th>Type:</th>
                                    <td>{{ $flat->type }}</td>
                                </tr>
                                <tr>
                                    <th>Area:</th>
                                    <td>{{ $flat->area_sqft }} sq ft</td>
                                </tr>
                                <tr>
                                    <th>Rent Amount:</th>
                                    <td>BDT {{ number_format($flat->rent_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($flat->is_occupied)
                                            <span class="badge badge-success">Occupied</span>
                                        @else
                                            <span class="badge badge-warning">Vacant</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Active:</th>
                                    <td>
                                        @if($flat->is_active)
                                            <span class="badge badge-success">Yes</span>
                                        @else
                                            <span class="badge badge-danger">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $flat->created_at->format('M d, Y h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Building Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Building Information</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th style="width: 40%;">Building:</th>
                                    <td>
                                        <a href="{{ route('admin.buildings.show', $flat->building) }}">
                                            {{ $flat->building->name }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Address:</th>
                                    <td>{{ $flat->building->address }}</td>
                                </tr>
                                <tr>
                                    <th>City:</th>
                                    <td>{{ $flat->building->city }}, {{ $flat->building->state }}</td>
                                </tr>
                                <tr>
                                    <th>House Owner:</th>
                                    <td>
                                        <a href="{{ route('admin.house-owners.show', $flat->building->houseOwner) }}">
                                            {{ $flat->building->houseOwner->name }}
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flat Owner Information -->
            @if($flat->flat_owner_name)
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Flat Owner Information</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th style="width: 40%;">Name:</th>
                                    <td>{{ $flat->flat_owner_name }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $flat->flat_owner_phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $flat->flat_owner_email ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Current Tenant Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Current Tenant</h3>
                        </div>
                        <div class="card-body">
                            @if($flat->currentTenant)
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 40%;">Name:</th>
                                        <td>
                                            <a href="{{ route('admin.tenants.show', $flat->currentTenant) }}">
                                                {{ $flat->currentTenant->name }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ $flat->currentTenant->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone:</th>
                                        <td>{{ $flat->currentTenant->phone ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Move In Date:</th>
                                        <td>{{ $flat->currentTenant->lease_start_date ? $flat->currentTenant->lease_start_date->format('M d, Y') : 'N/A' }}</td>
                                    </tr>
                                </table>
                            @else
                                <div class="text-center py-4">
                                    <p class="text-muted">No current tenant</p>
                                    <a href="{{ route('admin.tenants.create', ['flat_id' => $flat->id]) }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add Tenant
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Bills List -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Bills ({{ $flat->bills->count() }})</h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.bills.create', ['flat_id' => $flat->id]) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Create Bill
                                </a>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            @if($flat->bills->count() > 0)
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Bill #</th>
                                            <th>Category</th>
                                            <th>Amount</th>
                                            <th>Due Date</th>
                                            <th>Status</th>
                                            <th>Paid Amount</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($flat->bills->sortByDesc('created_at') as $bill)
                                            <tr>
                                                <td>{{ $bill->bill_number }}</td>
                                                <td>{{ $bill->billCategory->name ?? 'N/A' }}</td>
                                                <td>BDT {{ number_format($bill->amount, 2) }}</td>
                                                <td>{{ $bill->due_date->format('M d, Y') }}</td>
                                                <td>
                                                    @if($bill->status === 'paid')
                                                        <span class="badge badge-success">Paid</span>
                                                    @elseif($bill->status === 'unpaid')
                                                        <span class="badge badge-warning">Unpaid</span>
                                                    @elseif($bill->status === 'overdue')
                                                        <span class="badge badge-danger">Overdue</span>
                                                    @elseif($bill->status === 'partially_paid')
                                                        <span class="badge badge-info">Partially Paid</span>
                                                    @endif
                                                </td>
                                                <td>BDT {{ number_format($bill->paid_amount ?? 0, 2) }}</td>
                                                <td>
                                                    <a href="{{ route('admin.bills.show', $bill) }}" class="btn btn-info btn-xs">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    @if($bill->status !== 'paid')
                                                        <a href="{{ route('admin.bills.edit', $bill) }}" class="btn btn-primary btn-xs">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="text-center py-4">
                                    <p class="text-muted">No bills found for this flat.</p>
                                    <a href="{{ route('admin.bills.create', ['flat_id' => $flat->id]) }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Create First Bill
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- All Tenants History -->
            @if($flat->tenants->count() > 1)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Tenant History</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Lease Start</th>
                                        <th>Lease End</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($flat->tenants->sortByDesc('lease_start_date') as $tenant)
                                        <tr>
                                            <td>{{ $tenant->name }}</td>
                                            <td>{{ $tenant->email }}</td>
                                            <td>{{ $tenant->phone ?? 'N/A' }}</td>
                                            <td>{{ $tenant->lease_start_date ? $tenant->lease_start_date->format('M d, Y') : 'N/A' }}</td>
                                            <td>{{ $tenant->lease_end_date ? $tenant->lease_end_date->format('M d, Y') : 'N/A' }}</td>
                                            <td>
                                                @if($tenant->id === $flat->current_tenant_id)
                                                    <span class="badge badge-success">Current</span>
                                                @else
                                                    <span class="badge badge-secondary">Previous</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.tenants.show', $tenant) }}" class="btn btn-info btn-xs">
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
            </div>
            @endif
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    
});
</script>
@endpush