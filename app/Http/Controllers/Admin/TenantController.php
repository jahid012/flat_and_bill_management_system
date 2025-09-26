<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Building;
use App\Models\Flat;
use App\Models\HouseOwner;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Tenant::with(['building', 'flat']);

        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        
        if ($request->filled('building_id')) {
            $query->where('building_id', $request->building_id);
        }

        
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $tenants = $query->paginate(15)->appends($request->query());
        $buildings = Building::all();
        $houseOwners = HouseOwner::all();

        
        $stats = [
            'total' => Tenant::count(),
            'active' => Tenant::where('is_active', true)->count(),
            'inactive' => Tenant::where('is_active', false)->count(),
            'new_this_month' => Tenant::whereMonth('created_at', now()->month)
                                     ->whereYear('created_at', now()->year)
                                     ->count(),
        ];

        return view('admin.tenants.index', compact('tenants', 'buildings', 'houseOwners', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $buildings = Building::with('flats')->get();
        $availableFlats = Flat::where('is_occupied', false)->with('building')->get();
        $selectedFlat = null;
        
        
        if ($request->filled('flat_id')) {
            $selectedFlat = Flat::with('building')->find($request->flat_id);
        }
        
        return view('admin.tenants.create', compact('buildings', 'availableFlats', 'selectedFlat'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:tenants',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'building_id' => 'required|exists:buildings,id',
            'flat_id' => 'required|exists:flats,id',
            'lease_start_date' => 'required|date',
            'lease_end_date' => 'required|date|after:lease_start_date',
            'security_deposit' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        
        $flat = Flat::findOrFail($request->flat_id);
        if ($flat->is_occupied) {
            return back()->withErrors(['flat_id' => 'Selected flat is already occupied.'])->withInput();
        }

        $tenant = Tenant::create($request->all());

        
        $flat->update([
            'is_occupied' => true,
            'current_tenant_id' => $tenant->id,
        ]);

        return redirect()->route('admin.tenants.index')
            ->with('success', 'Tenant created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant)
    {
        $tenant->load(['building', 'flat', 'assignedBy']);
        return view('admin.tenants.show', compact('tenant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant)
    {
        $buildings = Building::with('flats')->get();
        $availableFlats = Flat::where('is_occupied', false)
            ->orWhere('id', $tenant->flat_id)
            ->with('building')
            ->get();
            
        return view('admin.tenants.edit', compact('tenant', 'buildings', 'availableFlats'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tenant $tenant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:tenants,email,' . $tenant->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'building_id' => 'required|exists:buildings,id',
            'flat_id' => 'required|exists:flats,id',
            'lease_start_date' => 'required|date',
            'lease_end_date' => 'required|date|after:lease_start_date',
            'security_deposit' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $oldFlatId = $tenant->flat_id;
        $newFlatId = $request->flat_id;

        
        if ($oldFlatId != $newFlatId) {
            $newFlat = Flat::findOrFail($newFlatId);
            if ($newFlat->is_occupied && $newFlat->current_tenant_id != $tenant->id) {
                return back()->withErrors(['flat_id' => 'Selected flat is already occupied.'])->withInput();
            }

            
            Flat::where('id', $oldFlatId)->update([
                'is_occupied' => false,
                'current_tenant_id' => null,
            ]);

            
            $newFlat->update([
                'is_occupied' => true,
                'current_tenant_id' => $tenant->id,
            ]);
        }

        $tenant->update($request->all());

        return redirect()->route('admin.tenants.index')
            ->with('success', 'Tenant updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        
        if ($tenant->flat) {
            $tenant->flat->update([
                'is_occupied' => false,
                'current_tenant_id' => null,
            ]);
        }

        $tenant->delete();

        return redirect()->route('admin.tenants.index')
            ->with('success', 'Tenant deleted successfully.');
    }
}
