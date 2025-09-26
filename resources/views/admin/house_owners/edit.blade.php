@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit House Owner</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.house-owners.index') }}" class="btn btn-outline-secondary me-2">
            <i class="fa fa-arrow-left me-2"></i>
            Back to House Owners
        </a>
        <a href="{{ route('admin.house-owners.show', $houseOwner) }}" class="btn btn-outline-info">
            <i class="bi bi-eye me-2"></i>
            View Details
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.house-owners.update', $houseOwner) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $houseOwner->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $houseOwner->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone', $houseOwner->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                            <small class="form-text text-muted">Leave blank to keep current password</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                        <small class="form-text text-muted">Required only if changing password</small>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" 
                                  rows="3">{{ old('address', $houseOwner->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="city" class="form-label">City</label>
                            <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror" 
                                   value="{{ old('city', $houseOwner->city) }}">
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="state" class="form-label">State/Province</label>
                            <input type="text" name="state" id="state" class="form-control @error('state') is-invalid @enderror" 
                                   value="{{ old('state', $houseOwner->state) }}">
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" name="country" id="country" class="form-control @error('country') is-invalid @enderror" 
                                   value="{{ old('country', $houseOwner->country) }}">
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="postal_code" class="form-label">Postal Code</label>
                            <input type="text" name="postal_code" id="postal_code" class="form-control @error('postal_code') is-invalid @enderror" 
                                   value="{{ old('postal_code', $houseOwner->postal_code) }}">
                            @error('postal_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" 
                                   {{ old('is_active', $houseOwner->is_active) ? 'checked' : '' }}>
                            <label for="is_active" class="form-check-label">Active Account</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.house-owners.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i>
                            Update House Owner
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
                    <i class="fa fa-chart-bar me-2"></i>
                    Account Statistics
                </h6>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-center">
                            <div class="h4 mb-0 text-primary">{{ $houseOwner->buildings_count ?? 0 }}</div>
                            <small class="text-muted">Buildings</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <div class="h4 mb-0 text-info">{{ $houseOwner->flats_count ?? 0 }}</div>
                            <small class="text-muted">Flats</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <div class="h4 mb-0 text-success">{{ $houseOwner->tenants_count ?? 0 }}</div>
                            <small class="text-muted">Tenants</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <div class="h4 mb-0 text-warning">{{ $houseOwner->bills_count ?? 0 }}</div>
                            <small class="text-muted">Bills</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fa fa-info-circle me-2"></i>
                    Account Information
                </h6>
                <div class="small">
                    <div class="mb-2">
                        <strong>Created:</strong> {{ $houseOwner->created_at->format('M d, Y') }}
                    </div>
                    <div class="mb-2">
                        <strong>Last Updated:</strong> {{ $houseOwner->updated_at->format('M d, Y') }}
                    </div>
                    <div class="mb-2">
                        <strong>Status:</strong> 
                        <span class="badge {{ $houseOwner->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $houseOwner->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    @if($houseOwner->email_verified_at)
                        <div class="mb-2">
                            <strong>Email Verified:</strong> {{ $houseOwner->email_verified_at->format('M d, Y') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fa fa-exclamation-triangle me-2"></i>
                    Important Notes
                </h6>
                <ul class="list-unstyled small">
                    <li class="mb-1">• Changing email may require re-verification</li>
                    <li class="mb-1">• Deactivating account restricts login access</li>
                    <li class="mb-1">• Password changes take effect immediately</li>
                    <li class="mb-1">• Contact info updates affect notifications</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection