@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Bill Categories</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('house_owner.bill-categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            Add New Category
        </a>
    </div>
</div>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('house_owner.bill-categories.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" name="search" id="search" class="form-control" 
                           value="{{ request('search') }}" placeholder="Category name or description">
                </div>
                <div class="col-md-3">
                    <label for="building_id" class="form-label">Building</label>
                    <select name="building_id" id="building_id" class="form-select">
                        <option value="">All Buildings</option>
                        @foreach($buildings as $building)
                            <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                                {{ $building->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi bi-search"></i>
                        </button>
                        <a href="{{ route('house_owner.bill-categories.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Categories Table -->
<div class="card">
    <div class="card-body">
        @if($billCategories->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Building</th>
                            <th>Description</th>
                            <th>Default Amount</th>
                            <th>Status</th>
                            <th>Bills Count</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($billCategories as $category)
                            <tr>
                                <td>
                                    @if($category->icon)
                                        <i class="{{ $category->icon }} fa-2x text-primary"></i>
                                    @else
                                        <i class="bi bi-receipt fa-2x text-muted"></i>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $category->name }}</strong>
                                </td>
                                <td>{{ $category->building->name }}</td>
                                <td>
                                    <span class="text-muted">{{ Str::limit($category->description, 50) ?: 'No description' }}</span>
                                </td>
                                <td>
                                    @if($category->default_amount)
                                        BDT {{ number_format($category->default_amount, 2) }}
                                    @else
                                        <span class="text-muted">No default</span>
                                    @endif
                                </td>
                                <td>
                                    @if($category->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $category->bills_count ?? 0 }}</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('house_owner.bill-categories.show', $category) }}" class="btn btn-outline-info" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('house_owner.bill-categories.edit', $category) }}" class="btn btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($category->bills()->count() == 0)
                                            <form action="{{ route('house_owner.bill-categories.destroy', $category) }}" method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this category?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $billCategories->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-tags display-1 text-muted"></i>
                <h3 class="mt-3">No Bill Categories Found</h3>
                <p class="text-muted">You haven't created any bill categories yet or none match your search criteria.</p>
                <a href="{{ route('house_owner.bill-categories.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>
                    Create Your First Category
                </a>
            </div>
        @endif
    </div>
</div>
@endsection