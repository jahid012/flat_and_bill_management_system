@extends('layouts.app')

@section('title', 'Edit Building')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Building</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.buildings.index') }}">Buildings</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.buildings.show', $building) }}">{{ $building->name }}</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Building: {{ $building->name }}</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="POST" action="{{ route('admin.buildings.update', $building) }}">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Building Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name', $building->name) }}" 
                                                   placeholder="Enter building name" required>
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="house_owner_id">House Owner <span class="text-danger">*</span></label>
                                            <select class="form-control @error('house_owner_id') is-invalid @enderror" 
                                                    id="house_owner_id" name="house_owner_id" required>
                                                <option value="">Select House Owner</option>
                                                @foreach($houseOwners as $houseOwner)
                                                    <option value="{{ $houseOwner->id }}" 
                                                            {{ old('house_owner_id', $building->house_owner_id) == $houseOwner->id ? 'selected' : '' }}>
                                                        {{ $houseOwner->name }} ({{ $houseOwner->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('house_owner_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="address">Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" name="address" rows="3" 
                                              placeholder="Enter complete address" required>{{ old('address', $building->address) }}</textarea>
                                    @error('address')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="city">City <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                                   id="city" name="city" value="{{ old('city', $building->city) }}" 
                                                   placeholder="Enter city" required>
                                            @error('city')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="state">State <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                                   id="state" name="state" value="{{ old('state', $building->state) }}" 
                                                   placeholder="Enter state" required>
                                            @error('state')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="zip_code">ZIP Code <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('zip_code') is-invalid @enderror" 
                                                   id="zip_code" name="zip_code" value="{{ old('zip_code', $building->zip_code) }}" 
                                                   placeholder="Enter ZIP code" required>
                                            @error('zip_code')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="total_floors">Total Floors <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('total_floors') is-invalid @enderror" 
                                                   id="total_floors" name="total_floors" 
                                                   value="{{ old('total_floors', $building->total_floors) }}" 
                                                   placeholder="Enter total floors" min="1" max="200" required>
                                            @error('total_floors')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="total_flats">Total Flats <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('total_flats') is-invalid @enderror" 
                                                   id="total_flats" name="total_flats" 
                                                   value="{{ old('total_flats', $building->total_flats) }}" 
                                                   placeholder="Enter total flats" min="1" max="10000" required>
                                            @error('total_flats')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" 
                                               {{ old('is_active', $building->is_active) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Active</label>
                                    </div>
                                </div>

                                <!-- Current Statistics -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card card-light">
                                            <div class="card-header">
                                                <h3 class="card-title">Current Statistics</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="text-center">
                                                            <div class="text-muted">Actual Flats</div>
                                                            <div class="h4">{{ $building->flats->count() }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="text-center">
                                                            <div class="text-muted">Occupied</div>
                                                            <div class="h4 text-success">{{ $building->flats->where('is_occupied', true)->count() }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="text-center">
                                                            <div class="text-muted">Vacant</div>
                                                            <div class="h4 text-warning">{{ $building->flats->where('is_occupied', false)->count() }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="text-center">
                                                            <div class="text-muted">Bill Categories</div>
                                                            <div class="h4">{{ $building->billCategories->count() }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Building
                                </button>
                                <a href="{{ route('admin.buildings.show', $building) }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    
    $('#house_owner_id').select2({
        theme: 'bootstrap4',
        placeholder: 'Select House Owner'
    });

    
    $('form').on('submit', function(e) {
        let isValid = true;
        const requiredFields = ['name', 'address', 'city', 'state', 'zip_code', 'total_floors', 'total_flats', 'house_owner_id'];
        
        requiredFields.forEach(function(field) {
            const input = $(`[name="${field}"]`);
            if (!input.val().trim()) {
                input.addClass('is-invalid');
                isValid = false;
            } else {
                input.removeClass('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            toastr.error('Please fill in all required fields.');
        }
    });

    
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
});
</script>
@endpush