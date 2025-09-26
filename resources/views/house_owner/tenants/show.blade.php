@extends('layouts.house_owner')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ $tenant->name }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('house_owner.flats.show', $tenant->flat) }}" class="btn btn-outline-secondary">
            <i class="fa fa-arrow-left me-2"></i>
            Back to Flat
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
                        <h6 class="text-muted">Move-in Date</h6>
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
                                @if($tenant->lease_end_date->isPast())
                                    <span class="badge bg-warning ms-2">Expired</span>
                                @elseif($tenant->lease_end_date->diffInDays() <= 30)
                                    <span class="badge bg-info ms-2">Expiring Soon</span>
                                @endif
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
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Building</h6>
                        <div class="h6 mb-3">
                            <a href="{{ route('house_owner.buildings.show', $tenant->building) }}" class="text-decoration-none">
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
                            <a href="{{ route('house_owner.flats.show', $tenant->flat) }}" class="text-decoration-none">
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
            </div>
        </div>

        <!-- Recent Bills -->
        @if($recentBills->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Bills</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentBills as $bill)
                            <tr>
                                <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $bill->bill_month)->format('M Y') }}</td>
                                <td>{{ $bill->billCategory->name }}</td>
                                <td>BDT {{ number_format($bill->amount, 2) }}</td>
                                <td>
                                    @if($bill->status === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($bill->status === 'unpaid')
                                        <span class="badge bg-warning">Unpaid</span>
                                    @elseif($bill->status === 'overdue')
                                        <span class="badge bg-danger">Overdue</span>
                                    @elseif($bill->status === 'partially_paid')
                                        <span class="badge bg-info">Partial</span>
                                    @endif
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
        <!-- Bill Statistics -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Bill Statistics</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="h4 mb-0 text-primary">{{ $totalBills }}</div>
                        <div class="small text-muted">Total Bills</div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="h4 mb-0 text-success">BDT {{ number_format($totalRevenue, 2) }}</div>
                        <div class="small text-muted">Total Paid</div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="h4 mb-0 text-info">{{ $paidBills }}</div>
                        <div class="small text-muted">Paid Bills</div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="h4 mb-0 text-warning">{{ $unpaidBills }}</div>
                        <div class="small text-muted">Unpaid Bills</div>
                    </div>
                </div>
                
                @if($overdueBills > 0)
                <div class="alert alert-danger small mb-0">
                    <strong>{{ $overdueBills }}</strong> overdue bills totaling 
                    <strong>BDT {{ number_format($overdueAmount, 2) }}</strong>
                </div>
                @endif
                
                @if($unpaidBills > 0)
                <div class="alert alert-warning small mt-2 mb-0">
                    <strong>{{ $unpaidBills }}</strong> unpaid bills totaling 
                    <strong>BDT {{ number_format($pendingAmount, 2) }}</strong>
                </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('house_owner.flats.show', $tenant->flat) }}" class="btn btn-outline-info btn-sm">
                        <i class="fa fa-home me-2"></i>
                        View Flat Details
                    </a>
                    <a href="{{ route('house_owner.buildings.show', $tenant->building) }}" class="btn btn-outline-info btn-sm">
                        <i class="fa fa-building me-2"></i>
                        View Building
                    </a>
                    <a href="{{ route('house_owner.bills.index', ['flat_id' => $tenant->flat_id]) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fa fa-receipt me-2"></i>
                        View All Bills
                    </a>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title mb-0">Contact Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($tenant->email)
                    <a href="mailto:{{ $tenant->email }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-envelope me-2"></i>
                        Send Email
                    </a>
                    @endif
                    @if($tenant->phone)
                    <a href="tel:{{ $tenant->phone }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-phone me-2"></i>
                        Call Tenant
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection