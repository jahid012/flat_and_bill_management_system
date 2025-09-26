@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Tenant</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.tenants.index') }}" class="btn btn-outline-secondary me-2">
            <i class="fa fa-arrow-left me-2"></i>
            Back to Tenants
        </a>
        <a href="{{ route('admin.tenants.show', $tenant) }}" class="btn btn-outline-info">
            <i class="bi bi-eye me-2"></i>
            View Details
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.tenants.update', $tenant) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $tenant->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $tenant->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone', $tenant->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="security_deposit" class="form-label">Security Deposit <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="security_deposit" id="security_deposit" 
                                       class="form-control @error('security_deposit') is-invalid @enderror" 
                                       step="0.01" min="0" value="{{ old('security_deposit', $tenant->security_deposit) }}" required>
                                @error('security_deposit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="address" class="form-label">Address</label>
                            <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', $tenant->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="building_id" class="form-label">Building <span class="text-danger">*</span></label>
                            <select name="building_id" id="building_id" class="form-select @error('building_id') is-invalid @enderror" required>
                                <option value="">Select Building</option>
                                @foreach($buildings as $building)
                                    <option value="{{ $building->id }}" {{ old('building_id', $tenant->building_id) == $building->id ? 'selected' : '' }}>
                                        {{ $building->name }} - {{ $building->address }}
                                    </option>
                                @endforeach
                            </select>
                            @error('building_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="flat_id" class="form-label">Flat <span class="text-danger">*</span></label>
                            <select name="flat_id" id="flat_id" class="form-select @error('flat_id') is-invalid @enderror" required>
                                <option value="">Select Flat</option>
                                @foreach($availableFlats as $flat)
                                    <option value="{{ $flat->id }}" 
                                            data-building="{{ $flat->building_id }}"
                                            {{ old('flat_id', $tenant->flat_id) == $flat->id ? 'selected' : '' }}>
                                        Flat {{ $flat->flat_number }} - {{ $flat->type }} ({{ $flat->building->name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('flat_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="lease_start_date" class="form-label">Lease Start Date</label>
                            <input type="date" name="lease_start_date" id="lease_start_date" 
                                   class="form-control @error('lease_start_date') is-invalid @enderror" 
                                   value="{{ old('lease_start_date', $tenant->lease_start_date ? $tenant->lease_start_date->format('Y-m-d') : '') }}">
                            @error('lease_start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="lease_end_date" class="form-label">Lease End Date</label>
                            <input type="date" name="lease_end_date" id="lease_end_date" 
                                   class="form-control @error('lease_end_date') is-invalid @enderror" 
                                   value="{{ old('lease_end_date', $tenant->lease_end_date ? $tenant->lease_end_date->format('Y-m-d') : '') }}">
                            @error('lease_end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" 
                                       {{ old('is_active', $tenant->is_active) ? 'checked' : '' }}>
                                <label for="is_active" class="form-check-label">
                                    Active Tenant
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i>
                            Update Tenant
                        </button>
                        <a href="{{ route('admin.tenants.show', $tenant) }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Current Information</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="small text-muted">Current Status</div>
                    <div class="fw-bold">
                        @if($tenant->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </div>
                </div>

                @if($tenant->building && $tenant->flat)
                <div class="mb-3">
                    <div class="small text-muted">Current Property</div>
                    <div class="fw-bold">{{ $tenant->building->name }}</div>
                    <div class="small">Flat {{ $tenant->flat->flat_number }} - {{ $tenant->flat->type }}</div>
                </div>
                @endif
                
                <div class="mb-3">
                    <div class="small text-muted">Created</div>
                    <div class="fw-bold">{{ $tenant->created_at->format('M d, Y') }}</div>
                </div>

                @if($tenant->assignedBy)
                <div class="mb-3">
                    <div class="small text-muted">Assigned By</div>
                    <div class="fw-bold">{{ $tenant->assignedBy->name }}</div>
                </div>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title mb-0">Help</h6>
            </div>
            <div class="card-body">
                <div class="small text-muted">
                    <ul class="ps-3">
                        <li>Select a building first to see available flats</li>
                        <li>Only vacant flats and the current tenant's flat will be shown</li>
                        <li>Changing flats will update occupancy status automatically</li>
                        <li>Lease dates are optional but recommended</li>
                    </ul>
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
    const flatOptions = Array.from(flatSelect.querySelectorAll('option[data-building]'));

    function filterFlats() {
        const selectedBuilding = buildingSelect.value;
        
        // Clear current options (except first option)
        flatSelect.innerHTML = '<option value="">Select Flat</option>';
        
        
        flatOptions.forEach(option => {
            if (!selectedBuilding || option.dataset.building === selectedBuilding) {
                flatSelect.appendChild(option.cloneNode(true));
            }
        });
    }

    buildingSelect.addEventListener('change', filterFlats);
    
    
    filterFlats();
});
</script>
@endpush
@endsection