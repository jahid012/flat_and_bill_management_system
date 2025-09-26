<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\HouseOwner;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Building::with(['houseOwner', 'flats']);

        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        
        if ($request->filled('house_owner')) {
            $query->where('house_owner_id', $request->house_owner);
        }

        
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $buildings = $query->paginate(15)->appends($request->query());
        $houseOwners = HouseOwner::all();

        return view('admin.buildings.index', compact('buildings', 'houseOwners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $houseOwners = HouseOwner::where('is_active', true)->get();
        $selectedHouseOwner = null;
        
        
        if ($request->filled('house_owner_id')) {
            $selectedHouseOwner = HouseOwner::find($request->house_owner_id);
        }
        
        return view('admin.buildings.create', compact('houseOwners', 'selectedHouseOwner'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
            'total_floors' => 'required|integer|min:1|max:200',
            'total_flats' => 'required|integer|min:1|max:10000',
            'house_owner_id' => 'required|exists:house_owners,id',
            'is_active' => 'boolean'
        ]);

        $building = Building::create([
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'total_floors' => $request->total_floors,
            'total_flats' => $request->total_flats,
            'house_owner_id' => $request->house_owner_id,
            'is_active' => $request->has('is_active') ? true : false
        ]);

        return redirect()->route('admin.buildings.index')
                        ->with('success', 'Building created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Building $building)
    {
        $building->load(['houseOwner', 'flats.currentTenant', 'billCategories', 'flats.bills']);
        
        
        $totalFlats = $building->flats()->count();
        $occupiedFlats = $building->flats()->where('is_occupied', true)->count();
        $vacantFlats = $totalFlats - $occupiedFlats;
        $totalRevenue = $building->flats()->with('bills')->get()->sum(function($flat) {
            return $flat->bills->where('status', 'paid')->sum('paid_amount');
        });
        $pendingAmount = $building->flats()->with('bills')->get()->sum(function($flat) {
            return $flat->bills->where('status', 'unpaid')->sum('amount');
        });

        return view('admin.buildings.show', compact(
            'building',
            'totalFlats',
            'occupiedFlats',
            'vacantFlats',
            'totalRevenue',
            'pendingAmount'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Building $building)
    {
        $houseOwners = HouseOwner::where('is_active', true)->get();
        
        return view('admin.buildings.edit', compact('building', 'houseOwners'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Building $building)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
            'total_floors' => 'required|integer|min:1|max:200',
            'total_flats' => 'required|integer|min:1|max:10000',
            'house_owner_id' => 'required|exists:house_owners,id',
            'is_active' => 'boolean'
        ]);

        $building->update([
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'total_floors' => $request->total_floors,
            'total_flats' => $request->total_flats,
            'house_owner_id' => $request->house_owner_id,
            'is_active' => $request->has('is_active') ? true : false
        ]);

        return redirect()->route('admin.buildings.show', $building)
                        ->with('success', 'Building updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Building $building)
    {
        
        if ($building->flats()->count() > 0) {
            return redirect()->route('admin.buildings.index')
                           ->with('error', 'Cannot delete building that has flats. Please remove all flats first.');
        }

        
        $hasActiveBills = $building->flats()
            ->join('bills', 'flats.id', '=', 'bills.flat_id')
            ->where('bills.status', '!=', 'paid')
            ->exists();

        if ($hasActiveBills) {
            return redirect()->route('admin.buildings.index')
                           ->with('error', 'Cannot delete building that has unpaid bills. Please resolve all bills first.');
        }

        $buildingName = $building->name;
        $building->delete();

        return redirect()->route('admin.buildings.index')
                        ->with('success', "Building '{$buildingName}' deleted successfully.");
    }

    /**
     * Display buildings for a specific house owner
     */
    public function indexByHouseOwner(Request $request, HouseOwner $houseOwner)
    {
        $query = $houseOwner->buildings()->with(['flats']);

        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $buildings = $query->paginate(15)->appends($request->query());

        return view('admin.house_owners.buildings', compact('buildings', 'houseOwner'));
    }
}