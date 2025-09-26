@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="bi bi-pencil"></i>
                        Edit Bill - {{ $bill->billCategory->name }}
                    </h3>
                    <div class="btn-group">
                        <a href="{{ route('house_owner.bills.show', $bill) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Bill
                        </a>
                        <a href="{{ route('house_owner.bills.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list"></i> All Bills
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('house_owner.bills.update', $bill) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Bill Information -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Bill Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="building_id">Building *</label>
                                            <select class="form-control @error('building_id') is-invalid @enderror" 
                                                    id="building_id" 
                                                    name="building_id" 
                                                    required>
                                                <option value="">Select Building</option>
                                                @foreach($buildings as $building)
                                                    <option value="{{ $building->id }}" 
                                                            {{ old('building_id', $bill->building_id) == $building->id ? 'selected' : '' }}>
                                                        {{ $building->name }} - {{ $building->address }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('building_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="flat_id">Flat</label>
                                            <select class="form-control @error('flat_id') is-invalid @enderror" 
                                                    id="flat_id" 
                                                    name="flat_id">
                                                <option value="">All Flats (Common Bill)</option>
                                                @foreach($flats as $flat)
                                                    <option value="{{ $flat->id }}" 
                                                            data-building="{{ $flat->building_id }}"
                                                            {{ old('flat_id', $bill->flat_id) == $flat->id ? 'selected' : '' }}>
                                                        {{ $flat->flat_number }} - Floor {{ $flat->floor }}
                                                        @if($flat->currentTenant)
                                                            ({{ $flat->currentTenant->name }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('flat_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="bill_category_id">Bill Category *</label>
                                            <select class="form-control @error('bill_category_id') is-invalid @enderror" 
                                                    id="bill_category_id" 
                                                    name="bill_category_id" 
                                                    required>
                                                <option value="">Select Category</option>
                                                @foreach($billCategories as $category)
                                                    <option value="{{ $category->id }}" 
                                                            data-building="{{ $category->building_id }}"
                                                            {{ old('bill_category_id', $bill->bill_category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('bill_category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="amount">Amount *</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">₹</span>
                                                </div>
                                                <input type="number" 
                                                       class="form-control @error('amount') is-invalid @enderror" 
                                                       id="amount" 
                                                       name="amount" 
                                                       step="0.01" 
                                                       min="0" 
                                                       value="{{ old('amount', $bill->amount) }}" 
                                                       required>
                                            </div>
                                            @error('amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="due_date">Due Date *</label>
                                            <input type="date" 
                                                   class="form-control @error('due_date') is-invalid @enderror" 
                                                   id="due_date" 
                                                   name="due_date" 
                                                   value="{{ old('due_date', $bill->due_date->format('Y-m-d')) }}" 
                                                   required>
                                            @error('due_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Additional Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                      id="description" 
                                                      name="description" 
                                                      rows="4" 
                                                      placeholder="Enter bill description or notes...">{{ old('description', $bill->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control @error('status') is-invalid @enderror" 
                                                    id="status" 
                                                    name="status">
                                                <option value="unpaid" {{ old('status', $bill->status) == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                                <option value="partially_paid" {{ old('status', $bill->status) == 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                                                <option value="paid" {{ old('status', $bill->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                                <option value="overdue" {{ old('status', $bill->status) == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        @if($bill->paid_amount > 0)
                                        <div class="form-group">
                                            <label for="paid_amount">Paid Amount</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">₹</span>
                                                </div>
                                                <input type="number" 
                                                       class="form-control @error('paid_amount') is-invalid @enderror" 
                                                       id="paid_amount" 
                                                       name="paid_amount" 
                                                       step="0.01" 
                                                       min="0" 
                                                       max="{{ $bill->amount }}" 
                                                       value="{{ old('paid_amount', $bill->paid_amount) }}">
                                            </div>
                                            @error('paid_amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        @endif

                                        <!-- Current Bill Info -->
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title">Current Bill Information</h6>
                                                <table class="table table-sm mb-0">
                                                    <tr>
                                                        <td><strong>Created:</strong></td>
                                                        <td>{{ $bill->created_at->format('M d, Y') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Last Updated:</strong></td>
                                                        <td>{{ $bill->updated_at->format('M d, Y') }}</td>
                                                    </tr>
                                                    @if($bill->paid_date)
                                                    <tr>
                                                        <td><strong>Paid Date:</strong></td>
                                                        <td>{{ $bill->paid_date->format('M d, Y') }}</td>
                                                    </tr>
                                                    @endif
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="text-right">
                                    <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Bill
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const buildingSelect = document.getElementById('building_id');
    const flatSelect = document.getElementById('flat_id');
    const categorySelect = document.getElementById('bill_category_id');

    function filterOptions() {
        const selectedBuilding = buildingSelect.value;
        
        
        Array.from(flatSelect.options).forEach(option => {
            if (option.value === '') {
                option.style.display = 'block';
                return;
            }
            
            const buildingId = option.dataset.building;
            option.style.display = (buildingId === selectedBuilding || !selectedBuilding) ? 'block' : 'none';
        });

        
        Array.from(categorySelect.options).forEach(option => {
            if (option.value === '') {
                option.style.display = 'block';
                return;
            }
            
            const buildingId = option.dataset.building;
            option.style.display = (buildingId === selectedBuilding || !selectedBuilding) ? 'block' : 'none';
        });

        
        if (selectedBuilding) {
            const selectedFlat = flatSelect.value;
            const selectedCategory = categorySelect.value;
            
            if (selectedFlat) {
                const flatOption = flatSelect.querySelector(`option[value="${selectedFlat}"]`);
                if (flatOption && flatOption.dataset.building !== selectedBuilding) {
                    flatSelect.value = '';
                }
            }
            
            if (selectedCategory) {
                const categoryOption = categorySelect.querySelector(`option[value="${selectedCategory}"]`);
                if (categoryOption && categoryOption.dataset.building !== selectedBuilding) {
                    categorySelect.value = '';
                }
            }
        }
    }

    buildingSelect.addEventListener('change', filterOptions);
    
    
    filterOptions();
});
</script>
@endpush
@endsection