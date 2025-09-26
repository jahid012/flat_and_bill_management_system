@extends('layouts.app')

@section('title', 'Bill Details')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Bill Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.bills.index') }}">Bills</a></li>
                        <li class="breadcrumb-item active">{{ $bill->bill_number }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Bill Actions -->
            <div class="row mb-3">
                <div class="col-12">
                    <a href="{{ route('admin.bills.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Bills
                    </a>
                    <a href="{{ route('admin.flats.show', $bill->flat) }}" class="btn btn-info">
                        <i class="fas fa-home"></i> View Flat
                    </a>
                    @if($bill->status !== 'paid')
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#markPaidModal">
                            <i class="fas fa-check-circle"></i> Mark as Paid
                        </button>
                        <a href="{{ route('admin.bills.edit', $bill) }}" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Edit Bill
                        </a>
                    @endif
                    @if($bill->status !== 'paid')
                        <form method="POST" action="{{ route('admin.bills.destroy', $bill) }}" class="d-inline" 
                              onsubmit="return confirm('Are you sure you want to delete this bill? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Delete Bill
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Bill Status Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card card-outline 
                        @if($bill->status === 'paid') card-success 
                        @elseif($bill->status === 'partially_paid') card-info
                        @elseif($bill->status === 'overdue') card-danger 
                        @else card-warning @endif">
                        <div class="card-header">
                            <h3 class="card-title">Bill {{ $bill->bill_number }}</h3>
                            <div class="card-tools">
                                <span class="badge 
                                    @if($bill->status === 'paid') badge-success 
                                    @elseif($bill->status === 'partially_paid') badge-info
                                    @elseif($bill->status === 'overdue') badge-danger 
                                    @else badge-warning @endif badge-lg">
                                    {{ ucfirst(str_replace('_', ' ', $bill->status)) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="text-muted">Bill Amount</div>
                                        <div class="h3">BDT {{ number_format($bill->amount, 2) }}</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="text-muted">Previous Due</div>
                                        <div class="h3">BDT {{ number_format($bill->previous_due, 2) }}</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="text-muted">Total Amount</div>
                                        <div class="h3 text-primary">BDT {{ number_format($bill->total_amount, 2) }}</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="text-muted">Paid Amount</div>
                                        <div class="h3 text-success">BDT {{ number_format($bill->paid_amount ?? 0, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                            @if($bill->total_amount > ($bill->paid_amount ?? 0))
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="alert alert-warning">
                                            <strong>Outstanding Balance:</strong> 
                                            BDT {{ number_format($bill->total_amount - ($bill->paid_amount ?? 0), 2) }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Bill Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Bill Information</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th style="width: 40%;">Bill Number:</th>
                                    <td>{{ $bill->bill_number }}</td>
                                </tr>
                                <tr>
                                    <th>Category:</th>
                                    <td>{{ $bill->billCategory->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Bill Month:</th>
                                    <td>{{ \Carbon\Carbon::parse($bill->bill_month)->format('F Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Due Date:</th>
                                    <td>
                                        {{ $bill->due_date->format('M d, Y') }}
                                        @if($bill->due_date->isPast() && $bill->status !== 'paid')
                                            <span class="badge badge-danger ml-2">Overdue</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $bill->created_at->format('M d, Y h:i A') }}</td>
                                </tr>
                                @if($bill->payment_date)
                                    <tr>
                                        <th>Payment Date:</th>
                                        <td>{{ \Carbon\Carbon::parse($bill->payment_date)->format('M d, Y') }}</td>
                                    </tr>
                                @endif
                                @if($bill->payment_method)
                                    <tr>
                                        <th>Payment Method:</th>
                                        <td>{{ $bill->payment_method }}</td>
                                    </tr>
                                @endif
                            </table>
                            @if($bill->notes)
                                <div class="mt-3">
                                    <strong>Notes:</strong>
                                    <p class="text-muted">{{ $bill->notes }}</p>
                                </div>
                            @endif
                            @if($bill->payment_notes)
                                <div class="mt-3">
                                    <strong>Payment Notes:</strong>
                                    <p class="text-muted">{{ $bill->payment_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Flat & Tenant Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Flat & Tenant Information</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th style="width: 40%;">Flat Number:</th>
                                    <td>
                                        <a href="{{ route('admin.flats.show', $bill->flat) }}">
                                            {{ $bill->flat->flat_number }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Building:</th>
                                    <td>
                                        <a href="{{ route('admin.buildings.show', $bill->flat->building) }}">
                                            {{ $bill->flat->building->name }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Address:</th>
                                    <td>{{ $bill->flat->building->address }}, {{ $bill->flat->building->city }}</td>
                                </tr>
                                <tr>
                                    <th>House Owner:</th>
                                    <td>
                                        <a href="{{ route('admin.house-owners.show', $bill->flat->building->houseOwner) }}">
                                            {{ $bill->flat->building->houseOwner->name }}
                                        </a>
                                    </td>
                                </tr>
                                @if($bill->flat->currentTenant)
                                    <tr>
                                        <th>Current Tenant:</th>
                                        <td>
                                            <a href="{{ route('admin.tenants.show', $bill->flat->currentTenant) }}">
                                                {{ $bill->flat->currentTenant->name }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tenant Email:</th>
                                        <td>{{ $bill->flat->currentTenant->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tenant Phone:</th>
                                        <td>{{ $bill->flat->currentTenant->phone ?? 'N/A' }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <th>Current Tenant:</th>
                                        <td><span class="text-muted">No tenant assigned</span></td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Mark as Paid Modal -->
@if($bill->status !== 'paid')
<div class="modal fade" id="markPaidModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.bills.mark-paid', $bill) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Mark Bill as Paid</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="paid_amount">Payment Amount <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="number" step="0.01" class="form-control" id="paid_amount" name="paid_amount" 
                                   value="{{ $bill->total_amount - ($bill->paid_amount ?? 0) }}" 
                                   max="{{ $bill->total_amount }}" min="0" required>
                        </div>
                        <small class="text-muted">
                            Outstanding amount: BDT {{ number_format($bill->total_amount - ($bill->paid_amount ?? 0), 2) }}
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="payment_date">Payment Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="payment_date" name="payment_date" 
                               value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="payment_method">Payment Method</label>
                        <select class="form-control" id="payment_method" name="payment_method">
                            <option value="">Select Method</option>
                            <option value="Cash">Cash</option>
                            <option value="Check">Check</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Online Payment">Online Payment</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="payment_notes">Payment Notes</label>
                        <textarea class="form-control" id="payment_notes" name="payment_notes" rows="3" 
                                  placeholder="Any additional notes about the payment..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Record Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    
    $('#paid_amount').on('input', function() {
        const amount = parseFloat($(this).val()) || 0;
        const maxAmount = {{ $bill->total_amount - ($bill->paid_amount ?? 0) }};
        
        if (amount > maxAmount) {
            $(this).val(maxAmount.toFixed(2));
            toastr.warning('Payment amount cannot exceed outstanding balance.');
        }
    });

    
    @if(session('success') && strpos(session('success'), 'paid') !== false)
        setTimeout(function() {
            $('.card-outline').addClass('animate__animated animate__pulse');
        }, 500);
    @endif
});
</script>
@endpush