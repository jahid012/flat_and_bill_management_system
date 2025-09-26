@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Add New Tenant</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('house_owner.tenants.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>
            Back to Tenants
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('house_owner.tenants.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="national_id" class="form-label">National ID</label>
                            <input type="text" class="form-control @error('national_id') is-invalid @enderror" 
                                   id="national_id" name="national_id" value="{{ old('national_id') }}">
                            @error('national_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="flat_id" class="form-label">Assign to Flat</label>
                            <select class="form-select @error('flat_id') is-invalid @enderror" id="flat_id" name="flat_id">
                                <option value="">Select a flat (optional)</option>
                                @foreach($availableFlats as $flat)
                                    <option value="{{ $flat->id }}" {{ old('flat_id') == $flat->id ? 'selected' : '' }}>
                                        {{ $flat->building->name }} - Flat {{ $flat->flat_number }}
                                    </option>
                                @endforeach
                            </select>
                            @error('flat_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="lease_start_date" class="form-label">Lease Start Date</label>
                            <input type="date" class="form-control @error('lease_start_date') is-invalid @enderror" 
                                   id="lease_start_date" name="lease_start_date" value="{{ old('lease_start_date') }}">
                            @error('lease_start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="lease_end_date" class="form-label">Lease End Date</label>
                            <input type="date" class="form-control @error('lease_end_date') is-invalid @enderror" 
                                   id="lease_end_date" name="lease_end_date" value="{{ old('lease_end_date') }}">
                            @error('lease_end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="monthly_rent" class="form-label">Monthly Rent</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control @error('monthly_rent') is-invalid @enderror" 
                                       id="monthly_rent" name="monthly_rent" value="{{ old('monthly_rent') }}" 
                                       step="0.01" min="0">
                                @error('monthly_rent')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="security_deposit" class="form-label">Security Deposit</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control @error('security_deposit') is-invalid @enderror" 
                                       id="security_deposit" name="security_deposit" value="{{ old('security_deposit') }}" 
                                       step="0.01" min="0">
                                @error('security_deposit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('house_owner.tenants.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-person-plus me-2"></i>
                            Create Tenant
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
                <div class="small text-muted">
                    <p><strong>Required Fields:</strong> Fields marked with <span class="text-danger">*</span> are required.</p>
                    <p><strong>Flat Assignment:</strong> You can assign the tenant to a flat now or later. Only available flats are shown.</p>
                    <p><strong>Lease Dates:</strong> Optional but recommended for tracking lease periods.</p>
                    <p><strong>Rent Information:</strong> Use this to track agreed rent amounts and deposits.</p>
                </div>
            </div>
        </div>

        @if($availableFlats->count() > 0)
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-building me-2"></i>
                        Available Flats
                    </h6>
                    <div class="small">
                        <p class="text-success mb-2">{{ $availableFlats->count() }} flats available for assignment</p>
                        @foreach($availableFlats->take(3) as $flat)
                            <div class="mb-1">
                                <span class="badge bg-light text-dark">{{ $flat->building->name }}</span>
                                Flat {{ $flat->flat_number }}
                            </div>
                        @endforeach
                        @if($availableFlats->count() > 3)
                            <div class="text-muted">and {{ $availableFlats->count() - 3 }} more...</div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title text-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        No Available Flats
                    </h6>
                    <p class="small text-muted mb-0">
                        All flats are currently occupied. You can still create the tenant and assign them to a flat later.
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection