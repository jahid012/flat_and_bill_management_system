@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add New Building</h1>
        <a href="{{ route('admin.buildings.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Buildings
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Building Information</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.buildings.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="name">Building Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="house_owner_id">House Owner <span class="text-danger">*</span></label>
                            <select class="form-control @error('house_owner_id') is-invalid @enderror" 
                                    id="house_owner_id" name="house_owner_id" required>
                                <option value="">Select House Owner</option>
                                @foreach($houseOwners as $owner)
                                    <option value="{{ $owner->id }}" 
                                            {{ (old('house_owner_id', $selectedHouseOwner?->id) == $owner->id) ? 'selected' : '' }}>
                                        {{ $owner->name }} ({{ $owner->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('house_owner_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="address">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="city">City <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                           id="city" name="city" value="{{ old('city') }}" required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="state">State <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                           id="state" name="state" value="{{ old('state') }}" required>
                                    @error('state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="zip_code">Zip Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('zip_code') is-invalid @enderror" 
                                   id="zip_code" name="zip_code" value="{{ old('zip_code') }}" required>
                            @error('zip_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="total_floors">Total Floors <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('total_floors') is-invalid @enderror" 
                                           id="total_floors" name="total_floors" value="{{ old('total_floors', 1) }}" 
                                           min="1" max="200" required>
                                    @error('total_floors')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="total_flats">Total Flats <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('total_flats') is-invalid @enderror" 
                                           id="total_flats" name="total_flats" value="{{ old('total_flats', 1) }}" 
                                           min="1" max="10000" required>
                                    @error('total_flats')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" 
                                       id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">
                                    Active Building
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                Active buildings are available for tenant assignments and billing.
                            </small>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Building
                            </button>
                            <a href="{{ route('admin.buildings.index') }}" class="btn btn-secondary ml-2">
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
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total House Owners</div>
                    <div class="h5 mb-3 font-weight-bold text-gray-800">{{ $houseOwners->count() }}</div>
                    
                    @if($selectedHouseOwner)
                        <div class="alert alert-info">
                            <strong>Pre-selected Owner:</strong><br>
                            {{ $selectedHouseOwner->name }}<br>
                            <small>{{ $selectedHouseOwner->email }}</small>
                        </div>
                    @endif
                    
                    <hr>
                    <div class="small text-muted">
                        <strong>Building Guidelines:</strong><br>
                        • Building names should be unique and descriptive<br>
                        • Provide complete address information<br>
                        • Total floors and flats help with organization<br>
                        • Only active buildings appear in tenant assignments
                    </div>
                    
                    @if($houseOwners->count() == 0)
                        <div class="alert alert-warning mt-3">
                            <strong>No House Owners Available:</strong> 
                            Please create house owners first before adding buildings.
                        </div>
                    @endif
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Next Steps</h6>
                </div>
                <div class="card-body">
                    <div class="small text-muted">
                        After creating the building, you can:
                        <ul class="mt-2">
                            <li>Add flats to the building</li>
                            <li>Create bill categories</li>
                            <li>Assign tenants to flats</li>
                            <li>Generate bills for tenants</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection