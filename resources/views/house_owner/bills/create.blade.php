@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Create New Bill</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('house_owner.bills.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>
            Back to Bills
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-receipt me-2"></i>
                    Bill Details
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('house_owner.bills.store') }}" id="billForm">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="building_id" class="form-label">Building <span class="text-danger">*</span></label>
                            <select class="form-select @error('building_id') is-invalid @enderror" 
                                    id="building_id" name="building_id" required onchange="loadFlats()">
                                <option value="">Select Building</option>
                                @foreach($buildings as $building)
                                    <option value="{{ $building->id }}" {{ old('building_id') == $building->id ? 'selected' : '' }}>
                                        {{ $building->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('building_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="flat_id" class="form-label">Flat <span class="text-danger">*</span></label>
                            <select class="form-select @error('flat_id') is-invalid @enderror" 
                                    id="flat_id" name="flat_id" required onchange="checkPreviousDues()">
                                <option value="">Select Flat</option>
                            </select>
                            @error('flat_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bill_category_id" class="form-label">Bill Category <span class="text-danger">*</span></label>
                            <select class="form-select @error('bill_category_id') is-invalid @enderror" 
                                    id="bill_category_id" name="bill_category_id" required>
                                <option value="">Select Category</option>
                                @foreach($billCategories as $category)
                                    <option value="{{ $category->id }}" {{ old('bill_category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bill_category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                       id="amount" name="amount" value="{{ old('amount') }}" 
                                       step="0.01" min="0" required onchange="calculateTotal()">
                            </div>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="due_date" class="form-label">Due Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                   id="due_date" name="due_date" value="{{ old('due_date', now()->addDays(30)->format('Y-m-d')) }}" required>
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="bill_month" class="form-label">Bill Month <span class="text-danger">*</span></label>
                            <input type="month" class="form-control @error('bill_month') is-invalid @enderror" 
                                   id="bill_month" name="bill_month" value="{{ old('bill_month', now()->format('Y-m')) }}" required>
                            @error('bill_month')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div id="previousDueSection" class="mb-3" style="display: none;">
                        <div class="alert alert-warning">
                            <h6><i class="bi bi-exclamation-triangle me-2"></i>Previous Dues Found</h6>
                            <p class="mb-2">This flat has unpaid bills totaling: <strong id="previousDueAmount">$0.00</strong></p>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="include_previous_due" name="include_previous_due" value="1">
                                <label class="form-check-label" for="include_previous_due">
                                    Include previous dues in this bill
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Additional details about this bill...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('house_owner.bills.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>
                            Create Bill
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="bi bi-calculator me-2"></i>
                    Bill Summary
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Base Amount:</span>
                    <span id="baseAmount">$0.00</span>
                </div>
                <div class="d-flex justify-content-between mb-2" id="previousDueRow" style="display: none;">
                    <span>Previous Due:</span>
                    <span id="previousDueDisplay">$0.00</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Total Amount:</strong>
                    <strong id="totalAmount">$0.00</strong>
                </div>
            </div>
        </div>

        <div class="card shadow mt-3">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Tips
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0 small">
                    <li class="mb-2">
                        <i class="bi bi-check text-success me-2"></i>
                        Bills are automatically sent to tenants via email
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check text-success me-2"></i>
                        Previous dues are automatically carried forward
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check text-success me-2"></i>
                        Overdue notifications are sent automatically
                    </li>
                    <li>
                        <i class="bi bi-check text-success me-2"></i>
                        You can edit bills until they're paid
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let previousDue = 0;

    function loadFlats() {
        const buildingId = document.getElementById('building_id').value;
        const flatSelect = document.getElementById('flat_id');
        
        flatSelect.innerHTML = '<option value="">Select Flat</option>';
        
        if (buildingId) {
            fetch(`/house-owner/buildings/${buildingId}/flats`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    data.forEach(flat => {
                        const option = document.createElement('option');
                        option.value = flat.id;
                        option.textContent = `${flat.flat_number} - ${flat.tenant ? flat.tenant.name : 'Vacant'}`;
                        flatSelect.appendChild(option);
                    });
                });
        }
    }

    function checkPreviousDues() {
        const flatId = document.getElementById('flat_id').value;
        const previousDueSection = document.getElementById('previousDueSection');
        
        if (flatId) {
            fetch(`/house-owner/flats/${flatId}/previous-dues`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    previousDue = data.total_due;
                    if (previousDue > 0) {
                        document.getElementById('previousDueAmount').textContent = `$${previousDue.toFixed(2)}`;
                        previousDueSection.style.display = 'block';
                    } else {
                        previousDueSection.style.display = 'none';
                    }
                    calculateTotal();
                });
        } else {
            previousDueSection.style.display = 'none';
            previousDue = 0;
            calculateTotal();
        }
    }

    function calculateTotal() {
        const amount = parseFloat(document.getElementById('amount').value) || 0;
        const includePreviousDue = document.getElementById('include_previous_due').checked;
        
        document.getElementById('baseAmount').textContent = `$${amount.toFixed(2)}`;
        
        const previousDueRow = document.getElementById('previousDueRow');
        const previousDueDisplay = document.getElementById('previousDueDisplay');
        
        if (includePreviousDue && previousDue > 0) {
            previousDueRow.style.display = 'flex';
            previousDueDisplay.textContent = `$${previousDue.toFixed(2)}`;
            document.getElementById('totalAmount').textContent = `$${(amount + previousDue).toFixed(2)}`;
        } else {
            previousDueRow.style.display = 'none';
            document.getElementById('totalAmount').textContent = `$${amount.toFixed(2)}`;
        }
    }

    
    document.getElementById('amount').addEventListener('input', calculateTotal);
    document.getElementById('include_previous_due').addEventListener('change', calculateTotal);

    
    calculateTotal();
</script>
@endpush
@endsection