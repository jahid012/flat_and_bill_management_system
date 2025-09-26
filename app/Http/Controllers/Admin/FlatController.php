<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flat;
use App\Models\Building;
use Illuminate\Http\Request;

class FlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Flat::with(['building.houseOwner', 'currentTenant']);

        
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

        
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $flats = $query->paginate(15)->appends($request->query());
        $buildings = Building::all();

        return view('admin.flats.index', compact('flats', 'buildings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $buildings = Building::with('houseOwner')->where('is_active', true)->get();
        $selectedBuilding = null;
        
        
        if ($request->filled('building_id')) {
            $selectedBuilding = Building::find($request->building_id);
        }
        
        return view('admin.flats.create', compact('buildings', 'selectedBuilding'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'flat_number' => 'required|string|max:20',
            'floor' => 'required|integer|min:0|max:200',
            'type' => 'required|string|max:50',
            'area_sqft' => 'required|numeric|min:0|max:99999.99',
            'rent_amount' => 'required|numeric|min:0|max:999999.99',
            'building_id' => 'required|exists:buildings,id',
            'flat_owner_name' => 'nullable|string|max:255',
            'flat_owner_phone' => 'nullable|string|max:20',
            'flat_owner_email' => 'nullable|email|max:255',
            'is_active' => 'sometimes|boolean'
        ]);

        
        $building = Building::findOrFail($request->building_id);

        
        $existingFlat = Flat::where('building_id', $request->building_id)
                           ->where('flat_number', $request->flat_number)
                           ->first();

        if ($existingFlat) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'A flat with this number already exists in the selected building.');
        }

        $flat = Flat::create([
            'flat_number' => $request->flat_number,
            'floor' => $request->floor,
            'type' => $request->type,
            'area_sqft' => $request->area_sqft,
            'rent_amount' => $request->rent_amount,
            'building_id' => $request->building_id,
            'house_owner_id' => $building->house_owner_id,
            'flat_owner_name' => $request->flat_owner_name,
            'flat_owner_phone' => $request->flat_owner_phone,
            'flat_owner_email' => $request->flat_owner_email,
            'is_occupied' => false, 
            'is_active' => $request->has('is_active') ? true : false
        ]);

        return redirect()->route('admin.flats.show', $flat)
                        ->with('success', 'Flat created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Flat $flat)
    {
        $flat->load(['building.houseOwner', 'currentTenant', 'bills.billCategory', 'tenants']);
        
        
        $totalBills = $flat->bills()->count();
        $paidBills = $flat->bills()->where('status', 'paid')->count();
        $unpaidBills = $flat->bills()->where('status', 'unpaid')->count();
        $overdueBills = $flat->bills()->where('status', 'overdue')->count();
        $totalRevenue = $flat->bills()->where('status', 'paid')->sum('paid_amount');
        $pendingAmount = $flat->bills()->where('status', 'unpaid')->sum('amount');
        $overdueAmount = $flat->bills()->where('status', 'overdue')->sum('amount');

        return view('admin.flats.show', compact(
            'flat',
            'totalBills',
            'paidBills',
            'unpaidBills',
            'overdueBills',
            'totalRevenue',
            'pendingAmount',
            'overdueAmount'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Flat $flat)
    {
        $buildings = Building::with('houseOwner')->where('is_active', true)->get();
        
        return view('admin.flats.edit', compact('flat', 'buildings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Flat $flat)
    {
        $request->validate([
            'flat_number' => 'required|string|max:20',
            'floor' => 'required|integer|min:0|max:200',
            'type' => 'required|string|max:50',
            'area_sqft' => 'required|numeric|min:0|max:99999.99',
            'rent_amount' => 'required|numeric|min:0|max:999999.99',
            'building_id' => 'required|exists:buildings,id',
            'flat_owner_name' => 'nullable|string|max:255',
            'flat_owner_phone' => 'nullable|string|max:20',
            'flat_owner_email' => 'nullable|email|max:255',
        ]);

        
        $building = Building::findOrFail($request->building_id);

        // Check for duplicate flat number in the same building (excluding current flat)
        $existingFlat = Flat::where('building_id', $request->building_id)
                           ->where('flat_number', $request->flat_number)
                           ->where('id', '!=', $flat->id)
                           ->first();

        if ($existingFlat) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'A flat with this number already exists in the selected building.');
        }

        $flat->update([
            'flat_number' => $request->flat_number,
            'floor' => $request->floor,
            'type' => $request->type,
            'area_sqft' => $request->area_sqft,
            'rent_amount' => $request->rent_amount,
            'building_id' => $request->building_id,
            'house_owner_id' => $building->house_owner_id,
            'flat_owner_name' => $request->flat_owner_name,
            'flat_owner_phone' => $request->flat_owner_phone,
            'flat_owner_email' => $request->flat_owner_email,
            'is_occupied' => $request->has('is_occupied') ? true : false,
            'is_active' => $request->has('is_active') ? true : false
        ]);

        return redirect()->route('admin.flats.show', $flat)
                        ->with('success', 'Flat updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Flat $flat)
    {
        
        if ($flat->bills()->count() > 0) {
            return redirect()->route('admin.flats.index')
                           ->with('error', 'Cannot delete flat that has bills. Please remove all bills first.');
        }

        
        if ($flat->tenants()->count() > 0) {
            return redirect()->route('admin.flats.index')
                           ->with('error', 'Cannot delete flat that has tenants. Please remove all tenants first.');
        }

        $flatNumber = $flat->flat_number;
        $buildingName = $flat->building->name;
        $flat->delete();

        return redirect()->route('admin.flats.index')
                        ->with('success', "Flat '{$flatNumber}' from building '{$buildingName}' deleted successfully.");
    }
}