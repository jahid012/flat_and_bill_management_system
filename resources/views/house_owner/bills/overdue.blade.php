@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Overdue Bills</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('house_owner.bills.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i>
                All Bills
            </a>
            <a href="{{ route('house_owner.bills.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>
                Create New Bill
            </a>
        </div>
    </div>
</div>

@if($overdueBills->count() > 0)
    <!-- Alert Box -->
    <div class="alert alert-warning" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>{{ $overdueBills->total() }} overdue bills</strong> require immediate attention. 
        Contact tenants to arrange payment as soon as possible.
    </div>

    <!-- Overdue Bills Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Bill Details</th>
                            <th>Tenant</th>
                            <th>Amount</th>
                            <th>Due Date</th>
                            <th>Days Overdue</th>
                            <th>Contact</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($overdueBills as $bill)
                            @php
                                $daysOverdue = now()->diffInDays($bill->due_date);
                                $urgencyClass = $daysOverdue > 30 ? 'table-danger' : ($daysOverdue > 15 ? 'table-warning' : '');
                            @endphp
                            <tr class="{{ $urgencyClass }}">
                                <td>
                                    <div class="fw-bold">{{ $bill->billCategory->name }}</div>
                                    <div class="small text-muted">
                                        {{ $bill->building->name }} - Flat {{ $bill->flat->flat_number }}
                                    </div>
                                    <div class="small text-muted">{{ $bill->bill_month }}</div>
                                </td>
                                <td>
                                    @if($bill->flat->currentTenant)
                                        <div class="fw-bold">{{ $bill->flat->currentTenant->name }}</div>
                                        <div class="small text-muted">{{ $bill->flat->currentTenant->email }}</div>
                                    @else
                                        <span class="text-muted">No tenant assigned</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">BDT {{ number_format($bill->total_amount, 2) }}</div>
                                    @if($bill->paid_amount > 0)
                                        <div class="small text-success">
                                            Paid: BDT {{ number_format($bill->paid_amount, 2) }}
                                        </div>
                                        <div class="small text-danger">
                                            Due: BDT {{ number_format($bill->total_amount - $bill->paid_amount, 2) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-danger">{{ $bill->due_date->format('M d, Y') }}</div>
                                    <div class="small text-muted">{{ $bill->due_date->format('l') }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-danger">{{ round($daysOverdue) }} days</span>
                                    @if($daysOverdue > 30)
                                        <div class="small text-danger mt-1">Critical</div>
                                    @elseif($daysOverdue > 15)
                                        <div class="small text-warning mt-1">High Priority</div>
                                    @endif
                                </td>
                                <td>
                                    @if($bill->flat->currentTenant)
                                        <div class="btn-group-vertical btn-group-sm">
                                            <a href="tel:{{ $bill->flat->currentTenant->phone }}" 
                                               class="btn btn-outline-success btn-sm" title="Call Tenant">
                                                <i class="bi bi-telephone"></i>
                                            </a>
                                            <a href="mailto:{{ $bill->flat->currentTenant->email }}" 
                                               class="btn btn-outline-primary btn-sm" title="Email Tenant">
                                                <i class="bi bi-envelope"></i>
                                            </a>
                                        </div>
                                    @else
                                        <span class="text-muted small">No contact</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('house_owner.bills.show', $bill) }}" 
                                           class="btn btn-outline-info" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('house_owner.bills.edit', $bill) }}" 
                                           class="btn btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('house_owner.bills.pay', $bill) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="button" class="btn btn-outline-success" title="Mark as Paid"
                                                    data-bs-toggle="modal" data-bs-target="#payModal{{ $bill->id }}">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if($overdueBills->hasPages())
            <div class="card-footer">
                {{ $overdueBills->links() }}
            </div>
        @endif
    </div>

    <!-- Payment Modals -->
    @foreach($overdueBills as $bill)
    <div class="modal fade" id="payModal{{ $bill->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mark Bill as Paid</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('house_owner.bills.pay', $bill) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Bill Details</label>
                            <div class="form-control-plaintext">
                                <strong>{{ $bill->billCategory->name }}</strong><br>
                                {{ $bill->building->name }} - Flat {{ $bill->flat->flat_number }}<br>
                                Due: BDT {{ number_format($bill->total_amount, 2) }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="paid_amount{{ $bill->id }}" class="form-label">Amount Paid <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="paid_amount" id="paid_amount{{ $bill->id }}" 
                                       class="form-control" step="0.01" min="0" 
                                       max="{{ $bill->total_amount - $bill->paid_amount }}" 
                                       value="{{ $bill->total_amount - $bill->paid_amount }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="payment_method{{ $bill->id }}" class="form-label">Payment Method</label>
                            <select name="payment_method" id="payment_method{{ $bill->id }}" class="form-select">
                                <option value="cash">Cash</option>
                                <option value="check">Check</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="online">Online Payment</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="payment_notes{{ $bill->id }}" class="form-label">Payment Notes</label>
                            <textarea name="payment_notes" id="payment_notes{{ $bill->id }}" 
                                      class="form-control" rows="2" 
                                      placeholder="Transaction ID, check number, or other details..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Mark as Paid</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

@else
    <!-- No Overdue Bills -->
    <div class="card">
        <div class="card-body text-center py-5">
            <div class="display-4 text-success mb-3">
                <i class="bi bi-check-circle"></i>
            </div>
            <h3 class="text-success">No Overdue Bills</h3>
            <p class="text-muted">All your bills are up to date. Keep up the good work!</p>
            <div class="mt-4">
                <a href="{{ route('house_owner.bills.index') }}" class="btn btn-primary me-2">
                    <i class="bi bi-list me-2"></i>
                    View All Bills
                </a>
                <a href="{{ route('house_owner.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-house me-2"></i>
                    Dashboard
                </a>
            </div>
        </div>
    </div>
@endif
@endsection