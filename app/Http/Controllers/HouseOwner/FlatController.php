<?php

namespace App\Http\Controllers\HouseOwner;

use App\Http\Controllers\Controller;
use App\Models\Flat;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $houseOwner = Auth::guard('house_owner')->user();
        
        $query = $houseOwner->flats()->with(['building', 'currentTenant']);

        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('flat_number', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%");
            });
        }

        
        if ($request->filled('building_id')) {
            $query->where('building_id', $request->building_id);
        }

        
        if ($request->filled('status')) {
            if ($request->status === 'occupied') {
                $query->where('is_occupied', true);
            } elseif ($request->status === 'vacant') {
                $query->where('is_occupied', false);
            }
        }

        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $flats = $query->paginate(15)->appends($request->query());
        $buildings = $houseOwner->buildings;

        return view('house_owner.flats.index', compact('flats', 'buildings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $houseOwner = Auth::guard('house_owner')->user();
        $buildings = $houseOwner->buildings;
        
        return view('house_owner.flats.create', compact('buildings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $houseOwner = Auth::guard('house_owner')->user();
        
        $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'flat_number' => 'required|string|max:255',
            'floor' => 'required|integer|min:0',
            'type' => 'required|in:1BHK,2BHK,3BHK,4BHK,Studio,Other',
            'area_sqft' => 'nullable|numeric|min:0',
            'rent_amount' => 'required|numeric|min:0',
            'flat_owner_name' => 'nullable|string|max:255',
            'flat_owner_phone' => 'nullable|string|max:20',
            'flat_owner_email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        
        $building = $houseOwner->buildings()->findOrFail($request->building_id);

        
        if ($building->flats()->where('flat_number', $request->flat_number)->exists()) {
            return back()->withErrors(['flat_number' => 'Flat number already exists in this building.'])->withInput();
        }

        $flat = $building->flats()->create([
            'flat_number' => $request->flat_number,
            'floor' => $request->floor,
            'type' => $request->type,
            'area_sqft' => $request->area_sqft,
            'rent_amount' => $request->rent_amount,
            'flat_owner_name' => $request->flat_owner_name,
            'flat_owner_phone' => $request->flat_owner_phone,
            'flat_owner_email' => $request->flat_owner_email,
            'is_active' => $request->boolean('is_active', true),
            'house_owner_id' => $houseOwner->id,
        ]);

        return redirect()->route('house_owner.flats.index')
            ->with('success', 'Flat created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Flat $flat)
    {
        
        $houseOwner = Auth::guard('house_owner')->user();
        if ($flat->house_owner_id !== $houseOwner->id) {
            abort(403);
        }

        $flat->load(['building', 'currentTenant', 'bills.billCategory']);
        
        
        $totalBills = $flat->bills()->count();
        $paidBills = $flat->bills()->where('status', 'paid')->count();
        $unpaidBills = $flat->bills()->where('status', 'unpaid')->count();
        $overdueBills = $flat->bills()->where('status', 'overdue')->count();
        $totalRevenue = $flat->bills()->where('status', 'paid')->sum('paid_amount');

        $recentBills = $flat->bills()->with('billCategory')->latest()->take(10)->get();

        return view('house_owner.flats.show', compact(
            'flat', 
            'totalBills', 
            'paidBills', 
            'unpaidBills', 
            'overdueBills',
            'totalRevenue',
            'recentBills'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Flat $flat)
    {
        
        $houseOwner = Auth::guard('house_owner')->user();
        if ($flat->house_owner_id !== $houseOwner->id) {
            abort(403);
        }

        $buildings = $houseOwner->buildings;
        
        return view('house_owner.flats.edit', compact('flat', 'buildings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Flat $flat)
    {
        
        $houseOwner = Auth::guard('house_owner')->user();
        if ($flat->house_owner_id !== $houseOwner->id) {
            abort(403);
        }

        $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'flat_number' => 'required|string|max:255',
            'floor' => 'required|integer|min:0',
            'type' => 'required|in:1BHK,2BHK,3BHK,4BHK,Studio,Other',
            'area_sqft' => 'nullable|numeric|min:0',
            'rent_amount' => 'required|numeric|min:0',
            'flat_owner_name' => 'nullable|string|max:255',
            'flat_owner_phone' => 'nullable|string|max:20',
            'flat_owner_email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        
        $building = $houseOwner->buildings()->findOrFail($request->building_id);

        // Check unique flat number within building (excluding current flat)
        if ($building->flats()->where('flat_number', $request->flat_number)
            ->where('id', '!=', $flat->id)->exists()) {
            return back()->withErrors(['flat_number' => 'Flat number already exists in this building.'])->withInput();
        }

        $flat->update([
            'building_id' => $request->building_id,
            'flat_number' => $request->flat_number,
            'floor' => $request->floor,
            'type' => $request->type,
            'area_sqft' => $request->area_sqft,
            'rent_amount' => $request->rent_amount,
            'flat_owner_name' => $request->flat_owner_name,
            'flat_owner_phone' => $request->flat_owner_phone,
            'flat_owner_email' => $request->flat_owner_email,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('house_owner.flats.index')
            ->with('success', 'Flat updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Flat $flat)
    {
        
        $houseOwner = Auth::guard('house_owner')->user();
        if ($flat->house_owner_id !== $houseOwner->id) {
            abort(403);
        }

        
        if ($flat->is_occupied) {
            return redirect()->route('house_owner.flats.index')
                ->with('error', 'Cannot delete flat that is currently occupied.');
        }

        if ($flat->bills()->count() > 0) {
            return redirect()->route('house_owner.flats.index')
                ->with('error', 'Cannot delete flat with existing bills.');
        }

        $flat->delete();

        return redirect()->route('house_owner.flats.index')
            ->with('success', 'Flat deleted successfully.');
    }

    /**
     * Assign tenant to flat
     */
    public function assignTenant(Request $request, Flat $flat)
    {
        
        $houseOwner = Auth::guard('house_owner')->user();
        if ($flat->house_owner_id !== $houseOwner->id) {
            abort(403);
        }

        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
        ]);

        $tenant = Tenant::findOrFail($request->tenant_id);
        
        
        $flat->update([
            'is_occupied' => true,
            'current_tenant_id' => $tenant->id,
        ]);

        
        $tenant->update([
            'flat_id' => $flat->id,
        ]);

        return redirect()->route('house_owner.flats.show', $flat)
            ->with('success', 'Tenant assigned successfully.');
    }

    /**
     * Remove tenant from flat
     */
    public function removeTenant(Flat $flat)
    {
        
        $houseOwner = Auth::guard('house_owner')->user();
        if ($flat->house_owner_id !== $houseOwner->id) {
            abort(403);
        }

        if ($flat->currentTenant) {
            $flat->currentTenant->update([
                'flat_id' => null,
            ]);
        }

        $flat->update([
            'is_occupied' => false,
            'current_tenant_id' => null,
        ]);

        return redirect()->route('house_owner.flats.show', $flat)
            ->with('success', 'Tenant removed successfully.');
    }
}
