@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Flat</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('house_owner.flats.index') }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-arrow-left me-2"></i>
            Back to Flats
        </a>
        <a href="{{ route('house_owner.flats.show', $flat) }}" class="btn btn-outline-info">
            <i class="bi bi-eye me-2"></i>
            View Details
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('house_owner.flats.update', $flat) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="building_id" class="form-label">Building <span class="text-danger">*</span></label>
                        <select name="building_id" id="building_id" class="form-select @error('building_id') is-invalid @enderror" required>
                            <option value="">Select Building</option>
                            @foreach($buildings as $building)
                                <option value="{{ $building->id }}" {{ old('building_id', $flat->building_id) == $building->id ? 'selected' : '' }}>
                                    {{ $building->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('building_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="flat_number" class="form-label">Flat Number <span class="text-danger">*</span></label>
                            <input type="text" name="flat_number" id="flat_number" class="form-control @error('flat_number') is-invalid @enderror" 
                                   value="{{ old('flat_number', $flat->flat_number) }}" required>
                            @error('flat_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="floor" class="form-label">Floor</label>
                            <input type="number" name="floor" id="floor" class="form-control @error('floor') is-invalid @enderror" 
                                   value="{{ old('floor', $flat->floor) }}" min="0">
                            @error('floor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="bedrooms" class="form-label">Bedrooms</label>
                            <input type="number" name="bedrooms" id="bedrooms" class="form-control @error('bedrooms') is-invalid @enderror" 
                                   value="{{ old('bedrooms', $flat->bedrooms) }}" min="0">
                            @error('bedrooms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="bathrooms" class="form-label">Bathrooms</label>
                            <input type="number" name="bathrooms" id="bathrooms" class="form-control @error('bathrooms') is-invalid @enderror" 
                                   value="{{ old('bathrooms', $flat->bathrooms) }}" min="0" step="0.5">
                            @error('bathrooms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="size_sqft" class="form-label">Size (sq ft)</label>
                            <input type="number" name="size_sqft" id="size_sqft" class="form-control @error('size_sqft') is-invalid @enderror" 
                                   value="{{ old('size_sqft', $flat->size_sqft) }}" min="0" step="0.01">
                            @error('size_sqft')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="rent_amount" class="form-label">Monthly Rent</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="rent_amount" id="rent_amount" class="form-control @error('rent_amount') is-invalid @enderror" 
                                       value="{{ old('rent_amount', $flat->rent_amount) }}" min="0" step="0.01">
                            </div>
                            @error('rent_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="3">{{ old('description', $flat->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_available" id="is_available" class="form-check-input" value="1" 
                                   {{ old('is_available', $flat->is_available) ? 'checked' : '' }}>
                            <label for="is_available" class="form-check-label">Available for Rent</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('house_owner.flats.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>
                            Update Flat
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
                    Current Status
                </h6>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small">Availability:</span>
                        <span class="badge {{ $flat->is_available ? 'bg-success' : 'bg-secondary' }}">
                            {{ $flat->is_available ? 'Available' : 'Not Available' }}
                        </span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small">Current Tenant:</span>
                        <span>
                            @if($flat->currentTenant)
                                <a href="{{ route('house_owner.tenants.show', $flat->currentTenant) }}" class="text-decoration-none">
                                    {{ $flat->currentTenant->name }}
                                </a>
                            @else
                                <span class="text-muted">None</span>
                            @endif
                        </span>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small">Building:</span>
                        <span>{{ $flat->building->name }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="bi bi-calendar me-2"></i>
                    Important Dates
                </h6>
                <div class="small">
                    <div class="mb-2">
                        <strong>Created:</strong> {{ $flat->created_at->format('M d, Y') }}
                    </div>
                    <div class="mb-2">
                        <strong>Last Updated:</strong> {{ $flat->updated_at->format('M d, Y') }}
                    </div>
                    @if($flat->currentTenant)
                        <div class="mb-2">
                            <strong>Tenant Since:</strong> {{ $flat->currentTenant->move_in_date?->format('M d, Y') ?? 'Not specified' }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if($flat->currentTenant)
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-person me-2"></i>
                        Current Tenant
                    </h6>
                    <div class="small">
                        <div class="mb-2">
                            <strong>Name:</strong> {{ $flat->currentTenant->name }}
                        </div>
                        <div class="mb-2">
                            <strong>Email:</strong> {{ $flat->currentTenant->email }}
                        </div>
                        <div class="mb-2">
                            <strong>Phone:</strong> {{ $flat->currentTenant->phone ?? 'Not provided' }}
                        </div>
                        <div class="mb-2">
                            <strong>Status:</strong> 
                            <span class="badge bg-{{ $flat->currentTenant->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($flat->currentTenant->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('house_owner.tenants.show', $flat->currentTenant) }}" class="btn btn-outline-primary btn-sm">
                            View Tenant Details
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection