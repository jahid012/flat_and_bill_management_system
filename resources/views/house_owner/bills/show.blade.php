@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-file-invoice"></i>
                        Bill Details - {{ $bill->billCategory->name }}
                    </h3>
                    <div class="btn-group">
                        <a href="{{ route('house_owner.bills.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Bills
                        </a>
                        @if($bill->status !== 'paid')
                        <a href="{{ route('house_owner.bills.edit', $bill) }}" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Bill Information -->
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3">Bill Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Bill ID:</strong></td>
                                    <td>#{{ $bill->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Category:</strong></td>
                                    <td>{{ $bill->billCategory->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Amount:</strong></td>
                                    <td class="text-success font-weight-bold">
                                        ₹{{ number_format($bill->amount, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Due Date:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $bill->due_date < now() && $bill->status !== 'paid' ? 'danger' : 'info' }}">
                                            {{ $bill->due_date->format('M d, Y') }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @php
                                            $statusClass = match($bill->status) {
                                                'paid' => 'success',
                                                'partially_paid' => 'warning',
                                                'overdue' => 'danger',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge badge-{{ $statusClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $bill->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                @if($bill->paid_amount > 0)
                                <tr>
                                    <td><strong>Paid Amount:</strong></td>
                                    <td class="text-success">₹{{ number_format($bill->paid_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Remaining:</strong></td>
                                    <td class="text-danger">₹{{ number_format($bill->amount - $bill->paid_amount, 2) }}</td>
                                </tr>
                                @endif
                                @if($bill->paid_date)
                                <tr>
                                    <td><strong>Paid Date:</strong></td>
                                    <td>{{ $bill->paid_date->format('M d, Y') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>

                        <!-- Property Information -->
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3">Property Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Building:</strong></td>
                                    <td>{{ $bill->building->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Address:</strong></td>
                                    <td>{{ $bill->building->address }}</td>
                                </tr>
                                @if($bill->flat)
                                <tr>
                                    <td><strong>Flat:</strong></td>
                                    <td>{{ $bill->flat->flat_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Floor:</strong></td>
                                    <td>{{ $bill->flat->floor }}</td>
                                </tr>
                                @if($bill->flat->currentTenant)
                                <tr>
                                    <td><strong>Tenant:</strong></td>
                                    <td>
                                        <a href="{{ route('house_owner.tenants.show', $bill->flat->currentTenant) }}" class="text-primary">
                                            {{ $bill->flat->currentTenant->name }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Contact:</strong></td>
                                    <td>{{ $bill->flat->currentTenant->phone }}</td>
                                </tr>
                                @endif
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($bill->description)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3">Description</h5>
                            <p class="text-muted">{{ $bill->description }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Actions -->
                    @if($bill->status !== 'paid')
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Actions</h6>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#markPaidModal">
                                            <i class="bi bi-check"></i> Mark as Paid
                                        </button>
                                        @if($bill->flat && $bill->flat->currentTenant)
                                        <a href="tel:{{ $bill->flat->currentTenant->phone }}" class="btn btn-info">
                                            <i class="fas fa-phone"></i> Call Tenant
                                        </a>
                                        <a href="mailto:{{ $bill->flat->currentTenant->email }}" class="btn btn-warning">
                                            <i class="fas fa-envelope"></i> Email Tenant
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mark as Paid Modal -->
@if($bill->status !== 'paid')
<div class="modal fade" id="markPaidModal" tabindex="-1" role="dialog" aria-labelledby="markPaidModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('house_owner.bills.mark-paid', $bill) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="markPaidModalLabel">Mark Bill as Paid</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="paid_amount">Paid Amount</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">₹</span>
                            </div>
                            <input type="number" 
                                   class="form-control" 
                                   id="paid_amount" 
                                   name="paid_amount" 
                                   step="0.01" 
                                   min="0" 
                                   max="{{ $bill->amount - $bill->paid_amount }}" 
                                   value="{{ $bill->amount - $bill->paid_amount }}" 
                                   required>
                        </div>
                        <small class="form-text text-muted">
                            Outstanding amount: ₹{{ number_format($bill->amount - $bill->paid_amount, 2) }}
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="payment_date">Payment Date</label>
                        <input type="date" 
                               class="form-control" 
                               id="payment_date" 
                               name="payment_date" 
                               value="{{ date('Y-m-d') }}" 
                               required>
                    </div>
                    <div class="form-group">
                        <label for="payment_notes">Payment Notes (Optional)</label>
                        <textarea class="form-control" id="payment_notes" name="payment_notes" rows="3" placeholder="Enter any payment notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check"></i> Mark as Paid
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection