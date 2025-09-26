@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Add New Flat</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('house_owner.flats.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>
            Back to Flats
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('house_owner.flats.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="building_id" class="form-label">Building <span class="text-danger">*</span></label>
                            <select name="building_id" id="building_id" class="form-select @error('building_id') is-invalid @enderror" required>
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
                        
                        <div class="col-md-6">
                            <label for="flat_number" class="form-label">Flat Number <span class="text-danger">*</span></label>
                            <input type="text" name="flat_number" id="flat_number" class="form-control @error('flat_number') is-invalid @enderror" 
                                   value="{{ old('flat_number') }}" required>
                            @error('flat_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="floor" class="form-label">Floor <span class="text-danger">*</span></label>
                            <input type="number" name="floor" id="floor" class="form-control @error('floor') is-invalid @enderror" 
                                   value="{{ old('floor') }}" min="0" required>
                            @error('floor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="">Select Type</option>
                                <option value="1BHK" {{ old('type') == '1BHK' ? 'selected' : '' }}>1BHK</option>
                                <option value="2BHK" {{ old('type') == '2BHK' ? 'selected' : '' }}>2BHK</option>
                                <option value="3BHK" {{ old('type') == '3BHK' ? 'selected' : '' }}>3BHK</option>
                                <option value="4BHK" {{ old('type') == '4BHK' ? 'selected' : '' }}>4BHK</option>
                                <option value="Studio" {{ old('type') == 'Studio' ? 'selected' : '' }}>Studio</option>
                                <option value="Other" {{ old('type') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label for="area_sqft" class="form-label">Area (sqft)</label>
                            <input type="number" name="area_sqft" id="area_sqft" class="form-control @error('area_sqft') is-invalid @enderror" 
                                   value="{{ old('area_sqft') }}" step="0.01" min="0">
                            @error('area_sqft')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="rent_amount" class="form-label">Rent Amount <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="rent_amount" id="rent_amount" class="form-control @error('rent_amount') is-invalid @enderror" 
                                   value="{{ old('rent_amount') }}" step="0.01" min="0" required>
                        </div>
                        @error('rent_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <h5>Flat Owner Details (Optional)</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="flat_owner_name" class="form-label">Owner Name</label>
                            <input type="text" name="flat_owner_name" id="flat_owner_name" class="form-control @error('flat_owner_name') is-invalid @enderror" 
                                   value="{{ old('flat_owner_name') }}">
                            @error('flat_owner_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label for="flat_owner_phone" class="form-label">Owner Phone</label>
                            <input type="text" name="flat_owner_phone" id="flat_owner_phone" class="form-control @error('flat_owner_phone') is-invalid @enderror" 
                                   value="{{ old('flat_owner_phone') }}">
                            @error('flat_owner_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label for="flat_owner_email" class="form-label">Owner Email</label>
                            <input type="email" name="flat_owner_email" id="flat_owner_email" class="form-control @error('flat_owner_email') is-invalid @enderror" 
                                   value="{{ old('flat_owner_email') }}">
                            @error('flat_owner_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label for="is_active" class="form-check-label">Active</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('house_owner.flats.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>
                            Create Flat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="bi bi-info-circle me-2"></i>
                    Information
                </h6>
                <ul class="list-unstyled small">
                    <li><strong>Flat Number:</strong> Unique identifier for the flat within the building</li>
                    <li><strong>Floor:</strong> Floor number (0 for ground floor)</li>
                    <li><strong>Type:</strong> Bedroom configuration</li>
                    <li><strong>Area:</strong> Total area in square feet</li>
                    <li><strong>Rent Amount:</strong> Monthly rent for this flat</li>
                    <li><strong>Owner Details:</strong> Optional information about flat owner (if different from building owner)</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection