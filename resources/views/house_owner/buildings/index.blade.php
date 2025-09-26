@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">My Buildings</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('house_owner.buildings.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            Add New Building
        </a>
    </div>
</div>

@if($buildings->count() > 0)
    <div class="row">
        @foreach($buildings as $building)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $building->name }}</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('house_owner.buildings.show', $building) }}">
                                        <i class="bi bi-eye me-2"></i>View
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('house_owner.buildings.edit', $building) }}">
                                        <i class="bi bi-pencil me-2"></i>Edit
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('house_owner.buildings.destroy', $building) }}" 
                                          onsubmit="return confirm('Are you sure you want to delete this building?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-trash me-2"></i>Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="bi bi-geo-alt text-muted me-2"></i>
                            <span class="text-muted">{{ $building->address }}</span>
                        </div>
                        
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <h4 class="text-primary mb-0">{{ $building->flats_count ?? 0 }}</h4>
                                    <small class="text-muted">Flats</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success mb-0">{{ $building->occupied_flats_count ?? 0 }}</h4>
                                <small class="text-muted">Occupied</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                Created {{ $building->created_at->diffForHumans() }}
                            </small>
                            <div>
                                <a href="{{ route('house_owner.flats.index', ['building' => $building->id]) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-door-open me-1"></i>
                                    View Flats
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($buildings->hasPages())
        <div class="d-flex justify-content-center">
            {{ $buildings->links() }}
        </div>
    @endif
@else
    <div class="text-center py-5">
        <i class="bi bi-building display-1 text-muted"></i>
        <h3 class="mt-3 text-muted">No Buildings Yet</h3>
        <p class="text-muted">Start by adding your first building to manage flats and bills.</p>
        <a href="{{ route('house_owner.buildings.create') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-circle me-2"></i>
            Add Your First Building
        </a>
    </div>
@endif
@endsection