@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="bi bi-pencil"></i>
                        Edit Building - {{ $building->name }}
                    </h3>
                    <div class="btn-group">
                        <a href="{{ route('house_owner.buildings.show', $building) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Building
                        </a>
                        <a href="{{ route('house_owner.buildings.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list"></i> All Buildings
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

                    <form action="{{ route('house_owner.buildings.update', $building) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Basic Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name">Building Name *</label>
                                            <input type="text" 
                                                   class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" 
                                                   name="name" 
                                                   value="{{ old('name', $building->name) }}" 
                                                   required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="type">Building Type</label>
                                            <select class="form-control @error('type') is-invalid @enderror" 
                                                    id="type" 
                                                    name="type">
                                                <option value="">Select Type</option>
                                                <option value="residential" {{ old('type', $building->type) == 'residential' ? 'selected' : '' }}>Residential</option>
                                                <option value="commercial" {{ old('type', $building->type) == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                                <option value="mixed" {{ old('type', $building->type) == 'mixed' ? 'selected' : '' }}>Mixed Use</option>
                                            </select>
                                            @error('type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="total_floors">Total Floors *</label>
                                                    <input type="number" 
                                                           class="form-control @error('total_floors') is-invalid @enderror" 
                                                           id="total_floors" 
                                                           name="total_floors" 
                                                           min="1" 
                                                           value="{{ old('total_floors', $building->total_floors) }}" 
                                                           required>
                                                    @error('total_floors')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="total_flats">Total Units *</label>
                                                    <input type="number" 
                                                           class="form-control @error('total_flats') is-invalid @enderror" 
                                                           id="total_flats" 
                                                           name="total_flats" 
                                                           min="1" 
                                                           value="{{ old('total_flats', $building->total_flats) }}" 
                                                           required>
                                                    @error('total_flats')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="is_active">Status</label>
                                            <select class="form-control @error('is_active') is-invalid @enderror" 
                                                    id="is_active" 
                                                    name="is_active">
                                                <option value="1" {{ old('is_active', $building->is_active) ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ !old('is_active', $building->is_active) ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            @error('is_active')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Address Information -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Address Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="address">Street Address *</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                                      id="address" 
                                                      name="address" 
                                                      rows="3" 
                                                      required>{{ old('address', $building->address) }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="city">City *</label>
                                                    <input type="text" 
                                                           class="form-control @error('city') is-invalid @enderror" 
                                                           id="city" 
                                                           name="city" 
                                                           value="{{ old('city', $building->city) }}" 
                                                           required>
                                                    @error('city')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="state">State *</label>
                                                    <input type="text" 
                                                           class="form-control @error('state') is-invalid @enderror" 
                                                           id="state" 
                                                           name="state" 
                                                           value="{{ old('state', $building->state) }}" 
                                                           required>
                                                    @error('state')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="zip_code">ZIP Code *</label>
                                            <input type="text" 
                                                   class="form-control @error('zip_code') is-invalid @enderror" 
                                                   id="zip_code" 
                                                   name="zip_code" 
                                                   value="{{ old('zip_code', $building->zip_code) }}" 
                                                   required>
                                            @error('zip_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                      id="description" 
                                                      name="description" 
                                                      rows="4" 
                                                      placeholder="Enter building description...">{{ old('description', $building->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Building Stats (Read-only) -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Current Building Statistics</h6>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h5 class="text-info">{{ $building->flats->count() }}</h5>
                                                    <small class="text-muted">Total Flats Added</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h5 class="text-success">{{ $building->flats->where('is_occupied', true)->count() }}</h5>
                                                    <small class="text-muted">Occupied Flats</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h5 class="text-warning">{{ $building->flats->where('is_occupied', false)->count() }}</h5>
                                                    <small class="text-muted">Vacant Flats</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h5 class="text-primary">{{ $building->tenants->count() }}</h5>
                                                    <small class="text-muted">Total Tenants</small>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small><strong>Created:</strong> {{ $building->created_at->format('M d, Y h:i A') }}</small>
                                            </div>
                                            <div class="col-md-6">
                                                <small><strong>Last Updated:</strong> {{ $building->updated_at->format('M d, Y h:i A') }}</small>
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
                                        <i class="fas fa-save"></i> Update Building
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
@endsection