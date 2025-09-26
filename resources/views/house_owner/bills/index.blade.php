@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Bills Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('house_owner.bills.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>
                Create New Bill
            </a>
            <a href="{{ route('house_owner.bills.overdue') }}" class="btn btn-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Overdue Bills
            </a>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('house_owner.bills.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="building" class="form-label">Building</label>
                    <select name="building" id="building" class="form-select">
                        <option value="">All Buildings</option>
                        @foreach($buildings as $building)
                            <option value="{{ $building->id }}" {{ request('building') == $building->id ? 'selected' : '' }}>
                                {{ $building->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="month" class="form-label">Month</label>
                    <input type="month" name="month" id="month" class="form-control" value="{{ request('month') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi bi-funnel me-2"></i>Filter
                        </button>
                        <a href="{{ route('house_owner.bills.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-2"></i>Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@if($bills->count() > 0)
    <!-- Bills Table -->
    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h6 class="m-0 font-weight-bold text-primary">Bills List</h6>
                </div>
                <div class="col-auto">
                    <span class="badge bg-info">{{ $bills->total() }} total bills</span>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Bill #</th>
                            <th>Building</th>
                            <th>Flat</th>
                            <th>Category</th>
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
                                    <strong>#{{ $bill->bill_number }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $bill->created_at->format('M d, Y') }}</small>
                                </td>
                                <td>{{ $bill->flat->building->name }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $bill->flat->flat_number }}</span>
                                    @if($bill->flat->tenant)
                                        <br><small class="text-muted">{{ $bill->flat->tenant->name }}</small>
                                    @endif
                                </td>
                                <td>{{ $bill->billCategory->name }}</td>
                                <td>
                                    <strong>BDT {{ number_format($bill->amount, 2) }}</strong>
                                    @if($bill->previous_due > 0)
                                        <br><small class="text-danger">+ BDT {{ number_format($bill->previous_due, 2) }} due</small>
                                    @endif
                                </td>
                                <td>
                                    {{ $bill->due_date->format('M d, Y') }}
                                    @if($bill->due_date->isPast() && $bill->status !== 'paid')
                                        <br><small class="text-danger">{{ $bill->due_date->diffForHumans() }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($bill->status === 'paid')
                                        <span class="badge bg-success badge-status">
                                            <i class="bi bi-check-circle me-1"></i>Paid
                                        </span>
                                        @if($bill->paid_at)
                                            <br><small class="text-muted">{{ $bill->paid_at->format('M d, Y') }}</small>
                                        @endif
                                    @elseif($bill->status === 'overdue')
                                        <span class="badge bg-danger badge-status">
                                            <i class="bi bi-exclamation-triangle me-1"></i>Overdue
                                        </span>
                                    @else
                                        <span class="badge bg-warning badge-status">
                                            <i class="bi bi-clock me-1"></i>Pending
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('house_owner.bills.show', $bill) }}" 
                                           class="btn btn-sm btn-outline-primary" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($bill->status !== 'paid')
                                            <button type="button" class="btn btn-sm btn-outline-success" 
                                                    onclick="markAsPaid({{ $bill->id }})" title="Mark as Paid">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        @endif
                                        <a href="{{ route('house_owner.bills.edit', $bill) }}" 
                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
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
    <div class="text-center py-5">
        <i class="bi bi-receipt display-1 text-muted"></i>
        <h3 class="mt-3 text-muted">No Bills Found</h3>
        <p class="text-muted">
            @if(request()->hasAny(['building', 'status', 'month']))
                No bills match your current filters. Try adjusting your search criteria.
            @else
                You haven't created any bills yet. Start by creating your first bill.
            @endif
        </p>
        <div class="mt-3">
            @if(request()->hasAny(['building', 'status', 'month']))
                <a href="{{ route('house_owner.bills.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-clockwise me-2"></i>Clear Filters
                </a>
            @endif
            <a href="{{ route('house_owner.bills.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Create Your First Bill
            </a>
        </div>
    </div>
@endif

<!-- Mark as Paid Modal -->
<div class="modal fade" id="markPaidModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark Bill as Paid</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="markPaidForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="">Select Payment Method</option>
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cheque">Cheque</option>
                            <option value="online">Online Payment</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="payment_reference" class="form-label">Payment Reference (Optional)</label>
                        <input type="text" class="form-control" id="payment_reference" name="payment_reference" 
                               placeholder="Transaction ID, Cheque Number, etc.">
                    </div>
                    <div class="mb-3">
                        <label for="payment_notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="payment_notes" name="payment_notes" rows="3" 
                                  placeholder="Any additional notes about the payment..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-2"></i>Mark as Paid
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function markAsPaid(billId) {
        const form = document.getElementById('markPaidForm');
        form.action = `/house-owner/bills/${billId}/mark-paid`;
        
        const modal = new bootstrap.Modal(document.getElementById('markPaidModal'));
        modal.show();
    }
</script>
@endpush
@endsection