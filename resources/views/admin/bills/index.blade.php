@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Bills Management</h1>
        <a href="{{ route('admin.bills.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Create New Bill
        </a>
    </div>

    <!-- Filter Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Bills</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.bills.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="search">Search</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Bill month, flat number...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="building_id">Building</label>
                            <select class="form-control" id="building_id" name="building_id">
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
                            <select class="form-control" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                <option value="partially_paid" {{ request('status') == 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="month">Month</label>
                            <input type="month" class="form-control" id="month" name="month" value="{{ request('month') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.bills.index') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-refresh"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bills Table -->
    @if($bills->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="m-0 font-weight-bold text-primary">Bills List</h6>
                    </div>
                    <div class="col-auto">
                        <span class="badge badge-info">{{ $bills->total() }} total bills</span>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Bill Details</th>
                                <th>Building & Flat</th>
                                <th>House Owner</th>
                                <th>Tenant</th>
                                <th>Amount</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bills as $bill)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold">#{{ $bill->bill_number ?? 'N/A' }}</div>
                                        <div class="small text-muted">{{ $bill->billCategory->name ?? 'N/A' }}</div>
                                        <div class="small text-muted">{{ $bill->bill_month }}</div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">{{ $bill->flat->building->name ?? 'N/A' }}</div>
                                        <div class="small text-muted">Flat {{ $bill->flat->flat_number ?? 'N/A' }}</div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">{{ $bill->flat->building->houseOwner->name ?? 'N/A' }}</div>
                                        <div class="small text-muted">{{ $bill->flat->building->houseOwner->email ?? 'N/A' }}</div>
                                    </td>
                                    <td>
                                        @if($bill->flat->currentTenant)
                                            <div class="font-weight-bold">{{ $bill->flat->currentTenant->name }}</div>
                                            <div class="small text-muted">{{ $bill->flat->currentTenant->email }}</div>
                                        @else
                                            <span class="text-muted small">No tenant assigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">BDT {{ number_format($bill->total_amount ?? $bill->amount, 2) }}</div>
                                        @if(($bill->paid_amount ?? 0) > 0)
                                            <div class="small text-success">Paid: BDT {{ number_format($bill->paid_amount, 2) }}</div>
                                        @endif
                                        @if(($bill->previous_due ?? 0) > 0)
                                            <div class="small text-warning">Previous: BDT {{ number_format($bill->previous_due, 2) }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">{{ $bill->due_date->format('M d, Y') }}</div>
                                        <div class="small text-muted">{{ $bill->due_date->format('l') }}</div>
                                        @if($bill->due_date->isPast() && $bill->status !== 'paid')
                                            <div class="small text-danger">{{ $bill->due_date->diffForHumans() }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($bill->status === 'paid')
                                            <span class=" badge-success badge-pill">
                                                <i class="bi bi-check-circle"></i> Paid
                                            </span>
                                            @if($bill->paid_at)
                                                <div class="small text-muted">{{ $bill->paid_at->format('M d, Y') }}</div>
                                            @endif
                                        @elseif($bill->status === 'overdue')
                                            <span class=" badge-danger badge-pill">
                                                <i class="bi bi-exclamation-triangle"></i> Overdue
                                            </span>
                                        @elseif($bill->status === 'partially_paid')
                                            <span class=" badge-warning badge-pill">
                                                <i class="bi bi-clock"></i> Partial
                                            </span>
                                        @else
                                            <span class="badge-info badge-pill">
                                                <i class="bi bi-clock"></i> Unpaid
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.bills.show', $bill) }}" 
                                               class="btn btn-sm btn-outline-info" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if($bill->status !== 'paid')
                                                <button type="button" class="btn btn-sm btn-outline-success" 
                                                        onclick="markAsPaid({{ $bill->id }})" title="Mark as Paid">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    onclick="editBill({{ $bill->id }})" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteBill({{ $bill->id }})" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if($bills->hasPages())
                <div class="card-footer">
                    {{ $bills->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="card shadow mb-4">
            <div class="card-body text-center py-5">
                <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                <h3 class="text-muted">No Bills Found</h3>
                <p class="text-muted">
                    @if(request()->hasAny(['search', 'building_id', 'status', 'month']))
                        No bills match your current filters. Try adjusting your search criteria.
                    @else
                        No bills have been created yet. Start by creating your first bill.
                    @endif
                </p>
                <div class="mt-3">
                    @if(request()->hasAny(['search', 'building_id', 'status', 'month']))
                        <a href="{{ route('admin.bills.index') }}" class="btn btn-secondary mr-2">
                            <i class="fas fa-refresh"></i> Clear Filters
                        </a>
                    @endif
                    <a href="{{ route('admin.bills.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Your First Bill
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Bills</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $bills->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-receipt fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Paid Bills</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $bills->where('status', 'paid')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Unpaid Bills</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $bills->whereIn('status', ['unpaid', 'partially_paid'])->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Overdue Bills</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $bills->where('status', 'overdue')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function markAsPaid(billId) {
    if (confirm('Are you sure you want to mark this bill as paid?')) {
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/bills/${billId}/mark-paid`;
        
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function editBill(billId) {
    window.location.href = `/admin/bills/${billId}/edit`;
}

function deleteBill(billId) {
    if (confirm('Are you sure you want to delete this bill? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/bills/${billId}`;
        
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection