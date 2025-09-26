<?php

namespace App\Http\Controllers\HouseOwner;

use App\Http\Controllers\Controller;
use App\Models\BillCategory;
use App\Models\HouseOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $houseOwner = Auth::guard('house_owner')->user();
        
        $query = BillCategory::whereHas('building', function($q) use ($houseOwner) {
            $q->where('house_owner_id', $houseOwner->id);
        })->with('building');

        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        
        if ($request->filled('building_id')) {
            $query->where('building_id', $request->building_id);
        }

        
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $billCategories = $query->paginate(15)->appends($request->query());
        $buildings = $houseOwner->buildings;

        return view('house_owner.bill_categories.index', compact('billCategories', 'buildings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $houseOwner = Auth::guard('house_owner')->user();
        $buildings = $houseOwner->buildings;
        
        return view('house_owner.bill_categories.create', compact('buildings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $houseOwner = Auth::guard('house_owner')->user();
        
        $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'default_amount' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        
        $building = $houseOwner->buildings()->findOrFail($request->building_id);

        
        if ($building->billCategories()->where('name', $request->name)->exists()) {
            return back()->withErrors(['name' => 'Category with this name already exists in this building.'])->withInput();
        }

        $billCategory = $building->billCategories()->create([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'default_amount' => $request->default_amount,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('house_owner.bill-categories.index')
            ->with('success', 'Bill Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BillCategory $billCategory)
    {
        
        $houseOwner = Auth::guard('house_owner')->user();
        if ($billCategory->building->house_owner_id !== $houseOwner->id) {
            abort(403);
        }

        $billCategory->load('building');
        
        
        $totalBills = $billCategory->bills()->count();
        $paidBills = $billCategory->bills()->where('status', 'paid')->count();
        $unpaidBills = $billCategory->bills()->where('status', 'unpaid')->count();
        $overdueBills = $billCategory->bills()->where('status', 'overdue')->count();
        $totalAmount = $billCategory->bills()->sum('amount');
        $averageAmount = $totalBills > 0 ? $totalAmount / $totalBills : 0;

        
        $bills = $billCategory->bills()->with(['flat'])->latest()->take(10)->get();

        return view('house_owner.bill_categories.show', compact(
            'billCategory', 
            'totalBills', 
            'paidBills', 
            'unpaidBills', 
            'overdueBills',
            'totalAmount',
            'averageAmount',
            'bills'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BillCategory $billCategory)
    {
        
        $houseOwner = Auth::guard('house_owner')->user();
        if ($billCategory->building->house_owner_id !== $houseOwner->id) {
            abort(403);
        }

        $buildings = $houseOwner->buildings;
        
        return view('house_owner.bill_categories.edit', compact('billCategory', 'buildings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BillCategory $billCategory)
    {
        
        $houseOwner = Auth::guard('house_owner')->user();
        if ($billCategory->building->house_owner_id !== $houseOwner->id) {
            abort(403);
        }

        $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'default_amount' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        
        $building = $houseOwner->buildings()->findOrFail($request->building_id);

        // Check if category with same name exists in building (excluding current)
        if ($building->billCategories()->where('name', $request->name)
            ->where('id', '!=', $billCategory->id)->exists()) {
            return back()->withErrors(['name' => 'Category with this name already exists in this building.'])->withInput();
        }

        $billCategory->update([
            'building_id' => $request->building_id,
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'default_amount' => $request->default_amount,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('house_owner.bill-categories.index')
            ->with('success', 'Bill Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BillCategory $billCategory)
    {
        
        $houseOwner = Auth::guard('house_owner')->user();
        if ($billCategory->building->house_owner_id !== $houseOwner->id) {
            abort(403);
        }

        
        if ($billCategory->bills()->count() > 0) {
            return redirect()->route('house_owner.bill-categories.index')
                ->with('error', 'Cannot delete category with existing bills.');
        }

        $billCategory->delete();

        return redirect()->route('house_owner.bill-categories.index')
            ->with('success', 'Bill Category deleted successfully.');
    }

    /**
     * Toggle category status
     */
    public function toggle(BillCategory $billCategory)
    {
        
        $houseOwner = Auth::guard('house_owner')->user();
        if ($billCategory->building->house_owner_id !== $houseOwner->id) {
            abort(403);
        }

        $billCategory->update([
            'is_active' => !$billCategory->is_active,
        ]);

        $status = $billCategory->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('house_owner.bill-categories.index')
            ->with('success', "Bill Category {$status} successfully.");
    }
}
