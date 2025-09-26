@extends('layouts.app')

@section('title', 'Create Flat')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Create New Flat</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.flats.index') }}">Flats</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Create New Flat</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="POST" action="{{ route('admin.flats.store') }}">
                            @csrf
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
                                            <label for="building_id">Building <span class="text-danger">*</span></label>
                                            <select class="form-control @error('building_id') is-invalid @enderror" 
                                                    id="building_id" name="building_id" required>
                                                <option value="">Select Building</option>
                                                @foreach($buildings as $building)
                                                    <option value="{{ $building->id }}" 
                                                            {{ old('building_id', $selectedBuilding?->id) == $building->id ? 'selected' : '' }}
                                                            data-house-owner="{{ $building->houseOwner->name }}">
                                                        {{ $building->name }} - {{ $building->city }} ({{ $building->houseOwner->name }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('building_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="flat_number">Flat Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('flat_number') is-invalid @enderror" 
                                                   id="flat_number" name="flat_number" value="{{ old('flat_number') }}" 
                                                   placeholder="e.g., A-101, 2B, 205" required>
                                            @error('flat_number')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="floor">Floor <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('floor') is-invalid @enderror" 
                                                   id="floor" name="floor" value="{{ old('floor') }}" 
                                                   placeholder="Floor number" min="0" max="200" required>
                                            @error('floor')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="type">Flat Type <span class="text-danger">*</span></label>
                                            <select class="form-control @error('type') is-invalid @enderror" 
                                                    id="type" name="type" required>
                                                <option value="">Select Type</option>
                                                <option value="1BHK" {{ old('type') == '1BHK' ? 'selected' : '' }}>1BHK</option>
                                                <option value="2BHK" {{ old('type') == '2BHK' ? 'selected' : '' }}>2BHK</option>
                                                <option value="3BHK" {{ old('type') == '3BHK' ? 'selected' : '' }}>3BHK</option>
                                                <option value="4BHK" {{ old('type') == '4BHK' ? 'selected' : '' }}>4BHK</option>
                                                <option value="Studio" {{ old('type') == 'Studio' ? 'selected' : '' }}>Studio</option>
                                                <option value="Penthouse" {{ old('type') == 'Penthouse' ? 'selected' : '' }}>Penthouse</option>
                                                <option value="Duplex" {{ old('type') == 'Duplex' ? 'selected' : '' }}>Duplex</option>
                                                <option value="Other" {{ old('type') == 'Other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('type')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="area_sqft">Area (sq ft) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control @error('area_sqft') is-invalid @enderror" 
                                                   id="area_sqft" name="area_sqft" value="{{ old('area_sqft') }}" 
                                                   placeholder="Area in square feet" min="0" max="99999.99" required>
                                            @error('area_sqft')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="rent_amount">Monthly Rent ($) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control @error('rent_amount') is-invalid @enderror" 
                                                   id="rent_amount" name="rent_amount" value="{{ old('rent_amount') }}" 
                                                   placeholder="Monthly rent amount" min="0" max="999999.99" required>
                                            @error('rent_amount')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Flat Owner Information (Optional) -->
                                <div class="card card-light">
                                    <div class="card-header">
                                        <h3 class="card-title">Flat Owner Information (Optional)</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="flat_owner_name">Owner Name</label>
                                                    <input type="text" class="form-control @error('flat_owner_name') is-invalid @enderror" 
                                                           id="flat_owner_name" name="flat_owner_name" value="{{ old('flat_owner_name') }}" 
                                                           placeholder="Flat owner name">
                                                    @error('flat_owner_name')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="flat_owner_phone">Owner Phone</label>
                                                    <input type="text" class="form-control @error('flat_owner_phone') is-invalid @enderror" 
                                                           id="flat_owner_phone" name="flat_owner_phone" value="{{ old('flat_owner_phone') }}" 
                                                           placeholder="Phone number">
                                                    @error('flat_owner_phone')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="flat_owner_email">Owner Email</label>
                                                    <input type="email" class="form-control @error('flat_owner_email') is-invalid @enderror" 
                                                           id="flat_owner_email" name="flat_owner_email" value="{{ old('flat_owner_email') }}" 
                                                           placeholder="Email address">
                                                    @error('flat_owner_email')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" 
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Active</label>
                                    </div>
                                    <small class="form-text text-muted">New flats are automatically set as vacant until a tenant is assigned.</small>
                                </div>

                                <!-- Building Info Display -->
                                <div id="building-info" class="mt-3" style="display: none;">
                                    <div class="card card-info">
                                        <div class="card-header">
                                            <h3 class="card-title">Selected Building Information</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row" id="building-details">
                                                <!-- Building details will be populated via JavaScript -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Create Flat
                                </button>
                                <a href="{{ route('admin.flats.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                @if($selectedBuilding)
                                    <a href="{{ route('admin.buildings.show', $selectedBuilding) }}" class="btn btn-info">
                                        <i class="fas fa-building"></i> Back to Building
                                    </a>
                                @endif
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
    
    $('#building_id').select2({
        theme: 'bootstrap4',
        placeholder: 'Select Building'
    });

    
    $('#building_id').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const buildingId = $(this).val();
        
        if (buildingId) {
            
            $('#building-info').show();
            
            
            $.get(`/admin/buildings/${buildingId}/info`, function(data) {
                $('#building-details').html(`
                    <div class="col-md-3">
                        <strong>Building:</strong><br>
                        ${data.name}
                    </div>
                    <div class="col-md-3">
                        <strong>House Owner:</strong><br>
                        ${data.house_owner.name}
                    </div>
                    <div class="col-md-3">
                        <strong>Total Floors:</strong><br>
                        ${data.total_floors}
                    </div>
                    <div class="col-md-3">
                        <strong>Existing Flats:</strong><br>
                        ${data.flats_count}/${data.total_flats}
                    </div>
                `);
            }).fail(function() {
                
                const houseOwner = selectedOption.data('house-owner');
                $('#building-details').html(`
                    <div class="col-md-6">
                        <strong>House Owner:</strong><br>
                        ${houseOwner}
                    </div>
                    <div class="col-md-6">
                        <strong>Status:</strong><br>
                        <span class="badge badge-success">Selected</span>
                    </div>
                `);
            });
        } else {
            $('#building-info').hide();
        }
    });

    
    if ($('#building_id').val()) {
        $('#building_id').trigger('change');
    }

    
    $('form').on('submit', function(e) {
        let isValid = true;
        const requiredFields = ['building_id', 'flat_number', 'floor', 'type', 'area_sqft', 'rent_amount'];
        
        requiredFields.forEach(function(field) {
            const input = $(`[name="${field}"]`);
            if (!input.val() || (typeof input.val() === 'string' && !input.val().trim())) {
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

    
    $('#floor').on('input', function() {
        const floor = $(this).val();
        const currentFlatNumber = $('#flat_number').val();
        
        if (floor && !currentFlatNumber) {
            
            const suggestedNumber = floor.padStart(2, '0') + '01';
            $('#flat_number').attr('placeholder', `Suggested: ${suggestedNumber}`);
        }
    });
});
</script>
@endpush