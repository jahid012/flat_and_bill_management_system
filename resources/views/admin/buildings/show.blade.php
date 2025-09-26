@extends('layouts.app')

@section('title', 'Building Details')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $building->name }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.buildings.index') }}">Buildings</a></li>
                        <li class="breadcrumb-item active">{{ $building->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Building Actions -->
            <div class="row mb-3">
                <div class="col-12">
                    <a href="{{ route('admin.buildings.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Buildings
                    </a>
                    <a href="{{ route('admin.buildings.edit', $building) }}" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Edit Building
                    </a>
                    <form method="POST" action="{{ route('admin.buildings.destroy', $building) }}" class="d-inline" 
                          onsubmit="return confirm('Are you sure you want to delete this building? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete Building
                        </button>
                    </form>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box p-2 bg-info">
                        <div class="inner">
                            <h3>{{ $totalFlats }}</h3>
                            <p>Total Flats</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-building"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box p-2 bg-success">
                        <div class="inner">
                            <h3>{{ $occupiedFlats }}</h3>
                            <p>Occupied Flats</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box p-2 bg-warning">
                        <div class="inner">
                            <h3>{{ $vacantFlats }}</h3>
                            <p>Vacant Flats</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-door-open"></i>
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
                <!-- Basic Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Basic Information</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th style="width: 40%;">Name:</th>
                                    <td>{{ $building->name }}</td>
                                </tr>
                                <tr>
                                    <th>Address:</th>
                                    <td>{{ $building->address }}</td>
                                </tr>
                                <tr>
                                    <th>City:</th>
                                    <td>{{ $building->city }}</td>
                                </tr>
                                <tr>
                                    <th>State:</th>
                                    <td>{{ $building->state }}</td>
                                </tr>
                                <tr>
                                    <th>ZIP Code:</th>
                                    <td>{{ $building->zip_code }}</td>
                                </tr>
                                <tr>
                                    <th>Total Floors:</th>
                                    <td>{{ $building->total_floors }}</td>
                                </tr>
                                <tr>
                                    <th>Total Flats:</th>
                                    <td>{{ $building->total_flats }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($building->is_active)
                                            <span class="badge-success">Active</span>
                                        @else
                                            <span class="badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $building->created_at->format('M d, Y h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- House Owner Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">House Owner</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th style="width: 40%;">Name:</th>
                                    <td>{{ $building->houseOwner->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $building->houseOwner->email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $building->houseOwner->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Company:</th>
                                    <td>{{ $building->houseOwner->company ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($building->houseOwner->is_active)
                                            <span class="badge-success">Active</span>
                                        @else
                                            <span class="badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                            <div class="mt-3">
                                <a href="{{ route('admin.house-owners.show', $building->houseOwner) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> View House Owner
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flats List -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Flats ({{ $building->flats->count() }})</h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.flats.create', ['building_id' => $building->id]) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Add Flat
                                </a>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            @if($building->flats->count() > 0)
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Flat Number</th>
                                            <th>Floor</th>
                                            <th>Type</th>
                                            <th>Rent</th>
                                            <th>Status</th>
                                            <th>Current Tenant</th>
                                            <th>Pending Bills</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($building->flats as $flat)
                                            <tr>
                                                <td>{{ $flat->flat_number }}</td>
                                                <td>{{ $flat->floor_number }}</td>
                                                <td>{{ $flat->flat_type }}</td>
                                                <td>BDT {{ number_format($flat->rent_amount, 2) }}</td>
                                                <td>
                                                    @if($flat->is_occupied)
                                                        <span class="badge-success">Occupied</span>
                                                    @else
                                                        <span class="badge-warning">Vacant</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($flat->currentTenant)
                                                        <a href="{{ route('admin.tenants.show', $flat->currentTenant) }}">
                                                            {{ $flat->currentTenant->name }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">None</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $pendingBills = $flat->bills->where('status', 'unpaid')->sum('amount');
                                                    @endphp
                                                    @if($pendingBills > 0)
                                                        <span class="badge-danger">BDT {{ number_format($pendingBills, 2) }}</span>
                                                    @else
                                                        <span class="badge-success">$0.00</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.flats.show', $flat) }}" class="btn btn-info btn-xs">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.flats.edit', $flat) }}" class="btn btn-primary btn-xs">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="text-center py-4">
                                    <p class="text-muted">No flats found for this building.</p>
                                    <a href="{{ route('admin.flats.create', ['building_id' => $building->id]) }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add First Flat
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bill Categories -->
            @if($building->billCategories->count() > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Bill Categories ({{ $building->billCategories->count() }})</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Category Name</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($building->billCategories as $category)
                                        <tr>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->description ?? 'N/A' }}</td>
                                            <td>
                                                @if($category->is_active)
                                                    <span class="badge-success">Active</span>
                                                @else
                                                    <span class="badge-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $category->created_at->format('M d, Y') }}</td>
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