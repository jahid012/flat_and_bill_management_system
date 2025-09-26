@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Add New House Owner</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.house-owners.index') }}" class="btn btn-outline-secondary">
            <i class="fa fa-arrow-left me-2"></i>
            Back to House Owners
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.house-owners.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                            <small class="form-text text-muted">Minimum 8 characters</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" 
                                  rows="3">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="city" class="form-label">City</label>
                            <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror" 
                                   value="{{ old('city') }}">
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="state" class="form-label">State/Province</label>
                            <input type="text" name="state" id="state" class="form-control @error('state') is-invalid @enderror" 
                                   value="{{ old('state') }}">
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" name="country" id="country" class="form-control @error('country') is-invalid @enderror" 
                                   value="{{ old('country') }}">
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="postal_code" class="form-label">Postal Code</label>
                            <input type="text" name="postal_code" id="postal_code" class="form-control @error('postal_code') is-invalid @enderror" 
                                   value="{{ old('postal_code') }}">
                            @error('postal_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label for="is_active" class="form-check-label">Active Account</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.house-owners.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i>
                            Create House Owner
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
                    <i class="fa fa-info-circle me-2"></i>
                    Account Information
                </h6>
                <ul class="list-unstyled small">
                    <li class="mb-2">
                        <strong>Login Access:</strong> House owners can log in using their email and password at the house owner portal.
                    </li>
                    <li class="mb-2">
                        <strong>Default Status:</strong> New accounts are active by default and can immediately access the system.
                    </li>
                    <li class="mb-2">
                        <strong>Password Policy:</strong> Passwords must be at least 8 characters long for security.
                    </li>
                    <li class="mb-2">
                        <strong>Email Notifications:</strong> House owners will receive email notifications for bills and system updates.
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fa fa-lightbulb me-2"></i>
                    Tips
                </h6>
                <ul class="list-unstyled small">
                    <li class="mb-1">• Use a valid email address for notifications</li>
                    <li class="mb-1">• Include complete address information</li>
                    <li class="mb-1">• Phone number helps with quick contact</li>
                    <li class="mb-1">• Activate account to allow immediate login</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection