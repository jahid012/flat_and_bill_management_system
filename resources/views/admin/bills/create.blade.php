@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New Bill</h1>
        <a href="{{ route('admin.bills.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Bills
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Bill Information</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.bills.store') }}" method="POST" id="billForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="building_id">Building <span class="text-danger">*</span></label>
                                    <select class="form-control @error('building_id') is-invalid @enderror" 
                                            id="building_id" onchange="updateFlats()" required>
                                        <option value="">Select Building</option>
                                        @foreach($buildings as $building)
                                            <option value="{{ $building->id }}" {{ old('building_id') == $building->id ? 'selected' : '' }}>
                                                {{ $building->name }} ({{ $building->houseOwner->name ?? 'No Owner' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('building_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="flat_id">Flat <span class="text-danger">*</span></label>
                                    <select class="form-control @error('flat_id') is-invalid @enderror" 
                                            id="flat_id" name="flat_id" onchange="updateBillCategories()" required>
                                        <option value="">Select Flat</option>
                                        @foreach($buildings as $building)
                                            @foreach($building->flats as $flat)
                                                <option value="{{ $flat->id }}" 
                                                        data-building="{{ $building->id }}"
                                                        data-tenant="{{ $flat->currentTenant?->name ?? 'No Tenant' }}"
                                                        {{ old('flat_id') == $flat->id ? 'selected' : '' }}>
                                                    Flat {{ $flat->flat_number }} 
                                                    @if($flat->currentTenant)
                                                        - {{ $flat->currentTenant->name }}
                                                    @else
                                                        - No Tenant
                                                    @endif
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                    @error('flat_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bill_category_id">Bill Category <span class="text-danger">*</span></label>
                                    <select class="form-control @error('bill_category_id') is-invalid @enderror" 
                                            id="bill_category_id" name="bill_category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($billCategories as $category)
                                            <option value="{{ $category->id }}" 
                                                    data-building="{{ $category->building_id }}"
                                                    {{ old('bill_category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('bill_category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bill_month">Bill Month <span class="text-danger">*</span></label>
                                    <input type="month" class="form-control @error('bill_month') is-invalid @enderror" 
                                           id="bill_month" name="bill_month" value="{{ old('bill_month', date('Y-m')) }}" required>
                                    @error('bill_month')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Amount <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                               id="amount" name="amount" value="{{ old('amount') }}" 
                                               step="0.01" min="0" required>
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="due_date">Due Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                           id="due_date" name="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" required>
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" placeholder="Optional notes about this bill...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Bill
                            </button>
                            <a href="{{ route('admin.bills.index') }}" class="btn btn-secondary ml-2">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Available Buildings</div>
                    <div class="h5 mb-3 font-weight-bold text-gray-800">{{ $buildings->count() }}</div>
                    
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Bill Categories</div>
                    <div class="h5 mb-3 font-weight-bold text-gray-800">{{ $billCategories->count() }}</div>
                    
                    @if($selectedHouseOwner)
                        <div class="alert alert-info">
                            <strong>Selected Owner:</strong><br>
                            {{ $selectedHouseOwner->name }}<br>
                            <small>{{ $selectedHouseOwner->email }}</small>
                        </div>
                    @endif
                    
                    <hr>
                    <div class="small text-muted">
                        <strong>Bill Creation Guidelines:</strong><br>
                        • Select building first, then flat<br>
                        • Only occupied flats are shown<br>
                        • Bill categories are filtered by building<br>
                        • Duplicate bills for same month/category are prevented<br>
                        • Previous dues are automatically carried forward
                    </div>
                    
                    @if($buildings->count() == 0)
                        <div class="alert alert-warning mt-3">
                            <strong>No Buildings Available:</strong> 
                            Please create buildings and flats first.
                        </div>
                    @endif
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Selected Details</h6>
                </div>
                <div class="card-body">
                    <div id="selectedDetails" class="small text-muted">
                        Select a building and flat to see details...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateFlats() {
    const buildingSelect = document.getElementById('building_id');
    const flatSelect = document.getElementById('flat_id');
    const selectedBuilding = buildingSelect.value;
    
    
    const flatOptions = flatSelect.querySelectorAll('option[data-building]');
    flatOptions.forEach(option => {
        if (selectedBuilding === '' || option.dataset.building === selectedBuilding) {
            option.style.display = 'block';
        } else {
            option.style.display = 'none';
            if (option.selected) {
                option.selected = false;
            }
        }
    });
    
    
    if (selectedBuilding === '') {
        flatSelect.selectedIndex = 0;
    }
    
    updateBillCategories();
    updateSelectedDetails();
}

function updateBillCategories() {
    const buildingSelect = document.getElementById('building_id');
    const categorySelect = document.getElementById('bill_category_id');
    const selectedBuilding = buildingSelect.value;
    
    
    const categoryOptions = categorySelect.querySelectorAll('option[data-building]');
    categoryOptions.forEach(option => {
        if (selectedBuilding === '' || option.dataset.building === selectedBuilding) {
            option.style.display = 'block';
        } else {
            option.style.display = 'none';
            if (option.selected) {
                option.selected = false;
            }
        }
    });
    
    
    if (selectedBuilding === '') {
        categorySelect.selectedIndex = 0;
    }
    
    updateSelectedDetails();
}

function updateSelectedDetails() {
    const buildingSelect = document.getElementById('building_id');
    const flatSelect = document.getElementById('flat_id');
    const detailsDiv = document.getElementById('selectedDetails');
    
    const selectedBuilding = buildingSelect.options[buildingSelect.selectedIndex];
    const selectedFlat = flatSelect.options[flatSelect.selectedIndex];
    
    let details = '';
    
    if (selectedBuilding && selectedBuilding.value) {
        details += '<strong>Building:</strong> ' + selectedBuilding.text + '<br>';
    }
    
    if (selectedFlat && selectedFlat.value && selectedFlat.dataset.tenant) {
        const tenant = selectedFlat.dataset.tenant;
        details += '<strong>Flat:</strong> ' + selectedFlat.text.split(' - ')[0] + '<br>';
        details += '<strong>Tenant:</strong> ' + tenant + '<br>';
    }
    
    if (details === '') {
        details = 'Select a building and flat to see details...';
    }
    
    detailsDiv.innerHTML = details;
}


document.addEventListener('DOMContentLoaded', function() {
    updateFlats();
    
    
    document.getElementById('flat_id').addEventListener('change', updateSelectedDetails);
});
</script>
@endpush
@endsection