<?php

namespace App\Http\Controllers\HouseOwner;

use App\Http\Controllers\Controller;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuildingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $buildings = Auth::guard('house_owner')->user()->buildings()
            ->withCount(['flats', 'tenants'])
            ->paginate(10);
            
        return view('house_owner.buildings.index', compact('buildings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('house_owner.buildings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10',
            'total_floors' => 'required|integer|min:1',
            'total_flats' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $building = Auth::guard('house_owner')->user()->buildings()->create($request->all());

        return redirect()->route('house_owner.buildings.index')
            ->with('success', 'Building created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Building $building)
    {
        
        if ($building->house_owner_id !== Auth::guard('house_owner')->id()) {
            abort(403);
        }

        $building->load(['flats.currentTenant', 'tenants']);
        
        return view('house_owner.buildings.show', compact('building'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Building $building)
    {
        
        if ($building->house_owner_id !== Auth::guard('house_owner')->id()) {
            abort(403);
        }

        return view('house_owner.buildings.edit', compact('building'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Building $building)
    {
        
        if ($building->house_owner_id !== Auth::guard('house_owner')->id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10',
            'total_floors' => 'required|integer|min:1',
            'total_flats' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $building->update($request->all());

        return redirect()->route('house_owner.buildings.index')
            ->with('success', 'Building updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Building $building)
    {
        
        if ($building->house_owner_id !== Auth::guard('house_owner')->id()) {
            abort(403);
        }

        
        if ($building->flats()->count() > 0 || $building->tenants()->count() > 0) {
            return redirect()->route('house_owner.buildings.index')
                ->with('error', 'Cannot delete building that has flats or tenants.');
        }

        $building->delete();

        return redirect()->route('house_owner.buildings.index')
            ->with('success', 'Building deleted successfully.');
    }
}
