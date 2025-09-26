@extends('house_owner.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-users"></i>
                        My Tenants
                    </h3>
                    <div class="btn-group">
                        <a href="{{ route('house_owner.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>

                <!-- Search and Filters -->
                <div class="card-body border-bottom">
                    <form method="GET" action="{{ route('house_owner.tenants.index') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="search">Search Tenants</label>
                                    <input type="text" 
                                           id="search" 
                                           name="search" 
                                           class="form-control" 
                                           placeholder="Name, email, phone..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="building_id">Building</label>
                                    <select id="building_id" name="building_id" class="form-control">
                                        <option value="">All Buildings</option>
                                        @foreach($buildings as $building)
                                            <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                                                {{ $building->name }}
                                            </option>
                                        @endforeach
                                    </select>
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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="btn-group d-block">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-search"></i> Search
                                        </button>
                                        <a href="{{ route('house_owner.tenants.index') }}" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-times"></i> Clear
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body p-0">
                    @if($tenants->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Tenant Details</th>
                                        <th>Property</th>
                                        <th>Contact</th>
                                        <th>Lease Period</th>
                                        <th>Status</th>
                                        <th>Bills</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tenants as $tenant)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $tenant->name }}</strong><br>
                                                    <small class="text-muted">{{ $tenant->email }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $tenant->flat->building->name ?? 'N/A' }}</strong><br>
                                                    <small class="text-muted">
                                                        @if($tenant->flat)
                                                            Flat {{ $tenant->flat->flat_number }}
                                                        @else
                                                            No flat assigned
                                                        @endif
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <small>
                                                        <i class="fas fa-phone"></i> {{ $tenant->phone }}<br>
                                                        @if($tenant->address)
                                                            <i class="fas fa-map-marker-alt"></i> {{ Str::limit($tenant->address, 30) }}
                                                        @endif
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    @if($tenant->lease_start_date)
                                                        <small>
                                                            <strong>Start:</strong> {{ $tenant->lease_start_date->format('M d, Y') }}<br>
                                                            @if($tenant->lease_end_date)
                                                                <strong>End:</strong> {{ $tenant->lease_end_date->format('M d, Y') }}
                                                            @endif
                                                        </small>
                                                    @else
                                                        <span class="text-muted">Not set</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $tenant->is_active ? 'success' : 'secondary' }}">
                                                    {{ $tenant->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $totalBills = $tenant->bills->count();
                                                    $paidBills = $tenant->bills->where('status', 'paid')->count();
                                                    $unpaidBills = $tenant->bills->where('status', 'unpaid')->count();
                                                    $overdueBills = $tenant->bills->where('status', 'overdue')->count();
                                                @endphp
                                                <div class="text-center">
                                                    <small>
                                                        <span class="badge badge-info">{{ $totalBills }} Total</span><br>
                                                        @if($unpaidBills > 0)
                                                            <span class="badge badge-warning">{{ $unpaidBills }} Unpaid</span><br>
                                                        @endif
                                                        @if($overdueBills > 0)
                                                            <span class="badge badge-danger">{{ $overdueBills }} Overdue</span>
                                                        @endif
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('house_owner.tenants.show', $tenant) }}" 
                                                       class="btn btn-outline-info btn-sm" 
                                                       title="View Details">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    @if($tenant->phone)
                                                        <a href="tel:{{ $tenant->phone }}" 
                                                           class="btn btn-outline-success btn-sm" 
                                                           title="Call Tenant">
                                                            <i class="fas fa-phone"></i>
                                                        </a>
                                                    @endif
                                                    @if($tenant->email)
                                                        <a href="mailto:{{ $tenant->email }}" 
                                                           class="btn btn-outline-primary btn-sm" 
                                                           title="Email Tenant">
                                                            <i class="fas fa-envelope"></i>
                                                        </a>
                                                    @endif
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
                                Showing {{ $tenants->firstItem() }} to {{ $tenants->lastItem() }} of {{ $tenants->total() }} results
                            </div>
                            {{ $tenants->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users" style="font-size: 4rem; color: #6c757d;"></i>
                            <h3 class="mt-3">No Tenants Found</h3>
                            <p class="text-muted">
                                @if(request()->filled('search') || request()->filled('building_id') || request()->filled('status'))
                                    No tenants match your search criteria.
                                @else
                                    You don't have any tenants yet.
                                @endif
                            </p>
                            <div class="mt-3">
                                @if(request()->filled('search') || request()->filled('building_id') || request()->filled('status'))
                                    <a href="{{ route('house_owner.tenants.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Clear Filters
                                    </a>
                                @endif
                                <a href="{{ route('house_owner.flats.index') }}" class="btn btn-primary">
                                    <i class="fas fa-home"></i> Manage Flats
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    @if($tenants->count() > 0)
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-primary">{{ $tenants->total() }}</h4>
                    <small class="text-muted">Total Tenants</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    @php
                        $activeTenants = $tenants->where('is_active', true)->count();
                    @endphp
                    <h4 class="text-success">{{ $activeTenants }}</h4>
                    <small class="text-muted">Active Tenants</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    @php
                        $totalBills = $tenants->sum(function($tenant) { return $tenant->bills->count(); });
                    @endphp
                    <h4 class="text-info">{{ $totalBills }}</h4>
                    <small class="text-muted">Total Bills</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    @php
                        $overdueBills = $tenants->sum(function($tenant) { 
                            return $tenant->bills->where('status', 'overdue')->count(); 
                        });
                    @endphp
                    <h4 class="text-{{ $overdueBills > 0 ? 'danger' : 'success' }}">{{ $overdueBills }}</h4>
                    <small class="text-muted">Overdue Bills</small>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection