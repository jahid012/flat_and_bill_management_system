@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-calendar-event"></i>
                {{ now()->format('M d, Y') }}
            </button>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">My Buildings</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $buildingsCount }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-building fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Flats</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $flatsCount }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-door-open fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Active Tenants</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $tenantsCount }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-person-badge fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Overdue Bills</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $overdueBillsCount }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity & Quick Actions -->
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Recent Bills</h6>
                <a href="{{ route('house_owner.bills.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                @if($recentBills->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Flat</th>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBills as $bill)
                                    <tr>
                                        <td>{{ $bill->flat->flat_number }}</td>
                                        <td>{{ $bill->billCategory->name }}</td>
                                        <td>BDT {{ number_format($bill->amount, 2) }}</td>
                                        <td>{{ $bill->due_date->format('M d, Y') }}</td>
                                        <td>
                                            @if($bill->status === 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @elseif($bill->status === 'overdue')
                                                <span class="badge bg-danger">Overdue</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-receipt display-4 text-muted"></i>
                        <p class="text-muted mt-2">No bills created yet.</p>
                        <a href="{{ route('house_owner.bills.create') }}" class="btn btn-primary">Create First Bill</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Monthly Revenue Chart -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Monthly Revenue Overview</h6>
            </div>
            <div class="card-body">
                @if($monthlyRevenue->count() > 0)
                    <canvas id="revenueChart"></canvas>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-bar-chart display-4 text-muted"></i>
                        <p class="text-muted mt-2">No revenue data available yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('house_owner.bills.create') }}" class="btn btn-success btn-sm">
                        <i class="bi bi-plus-circle me-2"></i>
                        Create New Bill
                    </a>
                    <a href="{{ route('house_owner.buildings.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-building me-2"></i>
                        Add Building
                    </a>
                    <a href="{{ route('house_owner.flats.create') }}" class="btn btn-info btn-sm">
                        <i class="bi bi-door-open me-2"></i>
                        Add Flat
                    </a>
                    <a href="{{ route('house_owner.bills.overdue') }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        View Overdue Bills
                    </a>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">This Month Summary</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small">Bills Created</span>
                    <span class="badge bg-primary">{{ $thisMonthBills }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small">Bills Paid</span>
                    <span class="badge bg-success">{{ $thisMonthPaidBills }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small">Total Revenue</span>
                    <span class="badge bg-info">BDT {{ number_format($thisMonthRevenue, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small">Collection Rate</span>
                    <span class="badge bg-{{ $collectionRate > 80 ? 'success' : ($collectionRate > 60 ? 'warning' : 'danger') }}">
                        {{ number_format($collectionRate, 1) }}%
                    </span>
                </div>
            </div>
        </div>

        @if($overdueBillsCount > 0)
            <div class="card shadow mb-4 border-left-warning">
                <div class="card-header bg-warning text-white py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Attention Required
                    </h6>
                </div>
                <div class="card-body">
                    <p class="small mb-2">You have {{ $overdueBillsCount }} overdue bills that need attention.</p>
                    <a href="{{ route('house_owner.bills.overdue') }}" class="btn btn-warning btn-sm">
                        Review Overdue Bills
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    .text-xs {
        font-size: 0.7rem;
    }
</style>
@endpush

@push('scripts')
@if($monthlyRevenue->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyRevenue->pluck('month')) !!},
            datasets: [{
                label: 'Monthly Revenue',
                data: {!! json_encode($monthlyRevenue->pluck('revenue')) !!},
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value, index, values) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endif
@endpush
@endsection