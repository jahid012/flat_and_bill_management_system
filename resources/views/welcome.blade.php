@extends('layouts.app')

@section('content')
<div class="container-fluid vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="row justify-content-center w-100">
        <div class="col-md-10 col-lg-8">
            <div class="text-center mb-5">
                <h1 class="display-4 text-white mb-3">
                    <i class="bi bi-building me-3"></i>
                    Multi-Tenant Bill Management
                </h1>
                <p class="lead text-white-50">
                    Complete solution for managing buildings, flats, and bills with multi-tenant isolation
                </p>
            </div>

            <div class="row g-4">
                <!-- Admin Login Card -->
                <div class="col-md-6">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-header bg-primary text-white text-center py-4">
                            <i class="bi bi-shield-lock display-6 mb-2"></i>
                            <h4 class="mb-0">Admin Panel</h4>
                        </div>
                        <div class="card-body p-4 text-center">
                            <h5 class="card-title text-primary">System Administrator</h5>
                            <p class="card-text text-muted mb-4">
                                Manage the entire system, house owners, and system-wide settings. 
                                Full access to all buildings and tenant data.
                            </p>
                            <a href="{{ route('admin.login') }}" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Admin Login
                            </a>
                        </div>
                    </div>
                </div>

                <!-- House Owner Login Card -->
                <div class="col-md-6">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-header bg-success text-white text-center py-4">
                            <i class="bi bi-house-door display-6 mb-2"></i>
                            <h4 class="mb-0">House Owner Panel</h4>
                        </div>
                        <div class="card-body p-4 text-center">
                            <h5 class="card-title text-success">Property Owner</h5>
                            <p class="card-text text-muted mb-4">
                                Manage your buildings, flats, tenants and bills. 
                                Complete control over your properties with tenant isolation.
                            </p>
                            <a href="{{ route('house_owner.login') }}" class="btn btn-success btn-lg w-100">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                House Owner Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection