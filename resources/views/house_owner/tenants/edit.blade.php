@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit  Resident : {{ $tenant->name }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('house_owner.tenants.show', $tenant) }}" class="btn btn-outline-info">
                <i class="bi bi-eye me-2"></i>
                View Details
            </a>
        </div>
        <a href="{{ route('house_owner.tenants.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>
            Back to  Resident s
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('house_owner.tenants.update', $tenant) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $tenant->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $tenant->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $tenant->phone) }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="national_id" class="form-label">National ID</label>
                            <input type="text" class="form-control @error('national_id') is-invalid @enderror" 
                                   id="national_id" name="national_id" value="{{ old('national_id', $tenant->national_id) }}">
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
                                    <option value="{{ $flat->id }}" {{ old('flat_id', $tenant->flat_id) == $flat->id ? 'selected' : '' }}>
                                        {{ $flat->building->name }} - Flat {{ $flat->flat_number }}
                                        @if($flat->id == $tenant->flat_id) (Current) @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('flat_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($tenant->flat)
                                <div class="form-text">
                                    Currently assigned to: <strong>{{ $tenant->flat->building->name }} - Flat {{ $tenant->flat->flat_number }}</strong>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status', $tenant->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $tenant->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                                   id="lease_start_date" name="lease_start_date" 
                                   value="{{ old('lease_start_date', $tenant->lease_start_date ? $tenant->lease_start_date->format('Y-m-d') : '') }}">
                            @error('lease_start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="lease_end_date" class="form-label">Lease End Date</label>
                            <input type="date" class="form-control @error('lease_end_date') is-invalid @enderror" 
                                   id="lease_end_date" name="lease_end_date" 
                                   value="{{ old('lease_end_date', $tenant->lease_end_date ? $tenant->lease_end_date->format('Y-m-d') : '') }}">
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
                                       id="monthly_rent" name="monthly_rent" value="{{ old('monthly_rent', $tenant->monthly_rent) }}" 
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
                                       id="security_deposit" name="security_deposit" value="{{ old('security_deposit', $tenant->security_deposit) }}" 
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
                            <i class="bi bi-check-circle me-2"></i>
                            Update  Resident 
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
                    <i class="bi bi-person-badge me-2"></i>
                     Resident  Information
                </h6>
                <div class="small">
                    <div class="mb-2">
                        <strong>Created:</strong><br>
                        {{ $tenant->created_at->format('M d, Y \a\t g:i A') }}
                    </div>
                    <div class="mb-2">
                        <strong>Last Updated:</strong><br>
                        {{ $tenant->updated_at->format('M d, Y \a\t g:i A') }}
                    </div>
                    @if($tenant->flat)
                        <div class="mb-2">
                            <strong>Current Flat:</strong><br>
                            <span class="badge bg-primary">{{ $tenant->flat->building->name }} - Flat {{ $tenant->flat->flat_number }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="bi bi-info-circle me-2"></i>
                    Edit Information
                </h6>
                <div class="small text-muted">
                    <p><strong>Flat Changes:</strong> Moving a tenant to a different flat will automatically update flat availability.</p>
                    <p><strong>Status Changes:</strong> Changing status to inactive will not automatically remove the tenant from their flat.</p>
                    <p><strong>Email Changes:</strong> Make sure the email is unique and valid.</p>
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
                        <p class="text-success mb-2">{{ $availableFlats->count() }} flats available</p>
                        @foreach($availableFlats->take(3) as $flat)
                            <div class="mb-1">
                                <span class="badge bg-light text-dark">{{ $flat->building->name }}</span>
                                Flat {{ $flat->flat_number }}
                                @if($flat->id == $tenant->flat_id)
                                    <span class="badge bg-success">Current</span>
                                @endif
                            </div>
                        @endforeach
                        @if($availableFlats->count() > 3)
                            <div class="text-muted">and {{ $availableFlats->count() - 3 }} more...</div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection