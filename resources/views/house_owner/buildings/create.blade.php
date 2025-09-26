@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Add New Building</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('house_owner.buildings.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>
            Back to Buildings
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-building me-2"></i>
                    Building Information
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('house_owner.buildings.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Building Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Building Type</label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                                <option value="">Select Type</option>
                                <option value="residential" {{ old('type') == 'residential' ? 'selected' : '' }}>Residential</option>
                                <option value="commercial" {{ old('type') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                <option value="mixed" {{ old('type') == 'mixed' ? 'selected' : '' }}>Mixed Use</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="total_floors" class="form-label">Total Floors</label>
                            <input type="number" class="form-control @error('total_floors') is-invalid @enderror" 
                                   id="total_floors" name="total_floors" value="{{ old('total_floors') }}" min="1">
                            @error('total_floors')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="total_flats" class="form-label">Total Flats</label>
                            <input type="number" class="form-control @error('total_flats') is-invalid @enderror" 
                                   id="total_flats" name="total_flats" value="{{ old('total_flats') }}" min="1">
                            @error('total_flats')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="built_year" class="form-label">Built Year</label>
                            <input type="number" class="form-control @error('built_year') is-invalid @enderror" 
                                   id="built_year" name="built_year" value="{{ old('built_year') }}" 
                                   min="1900" max="{{ date('Y') }}">
                            @error('built_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" 
                                  placeholder="Additional details about the building...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="has_parking" id="has_parking" 
                                       value="1" {{ old('has_parking') ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_parking">
                                    Has Parking Facility
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="has_elevator" id="has_elevator" 
                                       value="1" {{ old('has_elevator') ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_elevator">
                                    Has Elevator
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('house_owner.buildings.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>
                            Create Building
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection