@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Add New Bill Category</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
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
                <form action="{{ route('house_owner.bill-categories.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
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

                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="icon" class="form-label">Icon Class</label>
                            <input type="text" name="icon" id="icon" class="form-control @error('icon') is-invalid @enderror" 
                                   value="{{ old('icon') }}" placeholder="e.g., bi bi-lightning">
                            <small class="form-text text-muted">Use Bootstrap Icons classes (e.g., bi bi-lightning, bi bi-droplet)</small>
                            @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="default_amount" class="form-label">Default Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="default_amount" id="default_amount" class="form-control @error('default_amount') is-invalid @enderror" 
                                       value="{{ old('default_amount') }}" step="0.01" min="0">
                            </div>
                            <small class="form-text text-muted">Optional default amount for bills in this category</small>
                            @error('default_amount')
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
                        <a href="{{ route('house_owner.bill-categories.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>
                            Create Category
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
                    Common Categories
                </h6>
                <ul class="list-unstyled small">
                    <li><strong>Monthly Rent</strong> - Regular monthly rent charges</li>
                    <li><strong>Electricity Bill</strong> - Electricity usage charges</li>
                    <li><strong>Water Bill</strong> - Water usage charges</li>
                    <li><strong>Gas Bill</strong> - Gas usage charges</li>
                    <li><strong>Internet & Cable</strong> - Internet and cable TV</li>
                    <li><strong>Maintenance Fee</strong> - Building maintenance</li>
                    <li><strong>Parking Fee</strong> - Parking space charges</li>
                    <li><strong>Late Payment Fee</strong> - Late payment penalties</li>
                </ul>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="bi bi-palette me-2"></i>
                    Icon Examples
                </h6>
                <div class="row g-2 small">
                    <div class="col-6">
                        <i class="bi bi-house-door"></i> bi bi-house-door
                    </div>
                    <div class="col-6">
                        <i class="bi bi-lightning"></i> bi bi-lightning
                    </div>
                    <div class="col-6">
                        <i class="bi bi-droplet"></i> bi bi-droplet
                    </div>
                    <div class="col-6">
                        <i class="bi bi-fire"></i> bi bi-fire
                    </div>
                    <div class="col-6">
                        <i class="bi bi-wifi"></i> bi bi-wifi
                    </div>
                    <div class="col-6">
                        <i class="bi bi-car-front"></i> bi bi-car-front
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection