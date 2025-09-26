@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ $billCategory->name }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('house_owner.bill-categories.edit', $billCategory) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil me-2"></i>
                Edit
            </a>
            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="bi bi-trash me-2"></i>
                Delete
            </button>
        </div>
        <a href="{{ route('house_owner.bill-categories.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>
            Back to Categories
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Category Name</h6>
                        <div class="d-flex align-items-center">
                            @if($billCategory->icon)
                                <i class="{{ $billCategory->icon }} me-2 text-primary fs-5"></i>
                            @endif
                            <span class="h5 mb-0">{{ $billCategory->name }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Building</h6>
                        <div class="h5 mb-0">{{ $billCategory->building->name }}</div>
                    </div>
                </div>

                @if($billCategory->description)
                    <div class="mb-4">
                        <h6 class="text-muted">Description</h6>
                        <p class="mb-0">{{ $billCategory->description }}</p>
                    </div>
                @endif

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Default Amount</h6>
                        <div class="h5 mb-0">
                            @if($billCategory->default_amount)
                                BDT {{ number_format($billCategory->default_amount, 2) }}
                            @else
                                <span class="text-muted">Not set</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Status</h6>
                        <span class="badge {{ $billCategory->is_active ? 'bg-success' : 'bg-secondary' }} fs-6">
                            {{ $billCategory->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Created Date</h6>
                        <div>{{ $billCategory->created_at->format('F d, Y \a\t g:i A') }}</div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Last Updated</h6>
                        <div>{{ $billCategory->updated_at->format('F d, Y \a\t g:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bills using this category -->
        @if($bills->count() > 0)
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">Recent Bills ({{ $bills->count() }})</h6>
                    <a href="{{ route('house_owner.bills.index', ['category' => $billCategory->id]) }}" class="btn btn-sm btn-outline-primary">
                        View All Bills
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Bill ID</th>
                                    <th>Flat</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bills as $bill)
                                    <tr>
                                        <td>
                                            <a href="{{ route('house_owner.bills.show', $bill) }}" class="text-decoration-none">
                                                #{{ $bill->id }}
                                            </a>
                                        </td>
                                        <td>{{ $bill->flat->flat_number }}</td>
                                        <td>BDT {{ number_format($bill->amount, 2) }}</td>
                                        <td>{{ $bill->due_date->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $bill->status === 'paid' ? 'success' : ($bill->status === 'overdue' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($bill->status) }}
                                            </span>
                                        </td>
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
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="bi bi-graph-up me-2"></i>
                    Category Statistics
                </h6>
                <div class="row g-3">
                    <div class="col-12">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="h3 mb-0 text-primary">{{ $totalBills }}</div>
                            <small class="text-muted">Total Bills</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <div class="h5 mb-0 text-success">BDT {{ number_format($totalAmount, 2) }}</div>
                            <small class="text-muted">Total Amount</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <div class="h5 mb-0 text-info">BDT {{ number_format($averageAmount, 2) }}</div>
                            <small class="text-muted">Average Amount</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="bi bi-pie-chart me-2"></i>
                    Bill Status Breakdown
                </h6>
                <div class="row g-2">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small">Paid</span>
                            <span class="badge bg-success">{{ $paidBills }}</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small">Unpaid</span>
                            <span class="badge bg-warning">{{ $unpaidBills }}</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small">Overdue</span>
                            <span class="badge bg-danger">{{ $overdueBills }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="bi bi-tools me-2"></i>
                    Quick Actions
                </h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('house_owner.bills.create', ['category_id' => $billCategory->id]) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-plus-circle me-2"></i>
                        Create Bill
                    </a>
                    <a href="{{ route('house_owner.bill-categories.edit', $billCategory) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-pencil me-2"></i>
                        Edit Category
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Bill Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the bill category "<strong>{{ $billCategory->name }}</strong>"?</p>
                @if($totalBills > 0)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        This category has {{ $totalBills }} associated bills. Deleting this category will also remove all associated bills.
                    </div>
                @endif
                <p class="text-muted">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('house_owner.bill-categories.destroy', $billCategory) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Category</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection