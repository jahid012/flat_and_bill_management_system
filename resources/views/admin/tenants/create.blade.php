@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add New Tenant</h1>
        <a href="{{ route('admin.tenants.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Tenants
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tenant Information</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tenants.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="national_id">National ID</label>
                                    <input type="text" class="form-control @error('national_id') is-invalid @enderror" 
                                           id="national_id" name="national_id" value="{{ old('national_id') }}">
                                    @error('national_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="building_id">Building</label>
                                    <select class="form-control @error('building_id') is-invalid @enderror" 
                                            id="building_id" name="building_id" onchange="updateFlats()">
                                        <option value="">Select Building (Optional)</option>
                                        @foreach($buildings as $building)
                                            <option value="{{ $building->id }}" {{ old('building_id', $selectedFlat?->building_id) == $building->id ? 'selected' : '' }}>
                                                {{ $building->name }} ({{ $building->houseOwner->name }})
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
                                    <label for="flat_id">Flat</label>
                                    <select class="form-control @error('flat_id') is-invalid @enderror" 
                                            id="flat_id" name="flat_id">
                                        <option value="">Select Flat (Optional)</option>
                                        @foreach($availableFlats as $flat)
                                            <option value="{{ $flat->id }}" 
                                                    data-building="{{ $flat->building_id }}"
                                                    {{ old('flat_id', $selectedFlat?->id) == $flat->id ? 'selected' : '' }}>
                                                Flat {{ $flat->flat_number }} - {{ $flat->building->name }}
                                            </option>
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
                                    <label for="lease_start_date">Lease Start Date</label>
                                    <input type="date" class="form-control @error('lease_start_date') is-invalid @enderror" 
                                           id="lease_start_date" name="lease_start_date" value="{{ old('lease_start_date') }}">
                                    @error('lease_start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lease_end_date">Lease End Date</label>
                                    <input type="date" class="form-control @error('lease_end_date') is-invalid @enderror" 
                                           id="lease_end_date" name="lease_end_date" value="{{ old('lease_end_date') }}">
                                    @error('lease_end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="monthly_rent">Monthly Rent</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" class="form-control @error('monthly_rent') is-invalid @enderror" 
                                               id="monthly_rent" name="monthly_rent" value="{{ old('monthly_rent') }}" 
                                               step="0.01" min="0">
                                        @error('monthly_rent')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="security_deposit">Security Deposit</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" class="form-control @error('security_deposit') is-invalid @enderror" 
                                               id="security_deposit" name="security_deposit" value="{{ old('security_deposit') }}" 
                                               step="0.01" min="0">
                                        @error('security_deposit')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="is_active">Status</label>
                            <select class="form-control @error('is_active') is-invalid @enderror" 
                                    id="is_active" name="is_active" required>
                                <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Tenant
                            </button>
                            <a href="{{ route('admin.tenants.index') }}" class="btn btn-secondary ml-2">
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
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Available Flats</div>
                    <div class="h5 mb-3 font-weight-bold text-gray-800">{{ $availableFlats->count() }}</div>
                    
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Buildings</div>
                    <div class="h5 mb-3 font-weight-bold text-gray-800">{{ $buildings->count() }}</div>
                    
                    <hr>
                    <div class="small text-muted">
                        <strong>Note:</strong> You can create a tenant without assigning them to a flat immediately. 
                        Flats can be assigned later through the tenant edit page.
                    </div>
                    
                    @if($availableFlats->count() == 0)
                        <div class="alert alert-warning mt-3">
                            <strong>No Available Flats:</strong> All flats are currently occupied. 
                            You can still create the tenant and assign them later.
                        </div>
                    @endif
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
}


document.addEventListener('DOMContentLoaded', function() {
    updateFlats();
});
</script>
@endpush
@endsection