<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HouseOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class HouseOwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = HouseOwner::query();

        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $houseOwners = $query->withCount(['buildings', 'flats'])
            ->paginate(15)
            ->appends($request->query());

        return view('admin.house_owners.index', compact('houseOwners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.house_owners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:house_owners',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $houseOwner = HouseOwner::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => $request->boolean('is_active', true),
            'created_by' => Auth::guard('admin')->id(),
        ]);

        return redirect()->route('admin.house-owners.index')
            ->with('success', 'House Owner created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(HouseOwner $houseOwner)
    {
        $houseOwner->load(['buildings.flats', 'createdBy']);
        
        
        $totalBuildings = $houseOwner->buildings()->count();
        $totalFlats = $houseOwner->flats()->count();
        $activeTenants = $houseOwner->flats()->whereNotNull('current_tenant_id')->count();
        $availableFlats = $houseOwner->flats()->whereNull('current_tenant_id')->count();
        
        
        $totalRevenue = $houseOwner->bills()->where('status', 'paid')->sum('paid_amount');
        $pendingAmount = $houseOwner->bills()->where('status', 'unpaid')->sum('amount');
        $overdueAmount = $houseOwner->bills()->where('status', 'overdue')->sum('amount');
        
        
        $paidBills = $houseOwner->bills()->where('status', 'paid')->count();
        $unpaidBills = $houseOwner->bills()->where('status', 'unpaid')->count();
        $overdueBills = $houseOwner->bills()->where('status', 'overdue')->count();
        
        
        $buildings = $houseOwner->buildings()->withCount('flats')->latest()->take(5)->get();
        $recentBills = $houseOwner->bills()->with(['flat', 'building', 'billCategory'])->latest()->take(10)->get();

        return view('admin.house_owners.show', compact(
            'houseOwner', 
            'totalBuildings', 
            'totalFlats', 
            'activeTenants', 
            'availableFlats',
            'totalRevenue',
            'pendingAmount', 
            'overdueAmount',
            'paidBills',
            'unpaidBills', 
            'overdueBills',
            'buildings',
            'recentBills'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HouseOwner $houseOwner)
    {
        return view('admin.house_owners.edit', compact('houseOwner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HouseOwner $houseOwner)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('house_owners')->ignore($houseOwner->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $houseOwner->update($data);

        return redirect()->route('admin.house-owners.index')
            ->with('success', 'House Owner updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HouseOwner $houseOwner)
    {
        
        if ($houseOwner->buildings()->count() > 0) {
            return redirect()->route('admin.house-owners.index')
                ->with('error', 'Cannot delete house owner with existing buildings.');
        }

        $houseOwner->delete();

        return redirect()->route('admin.house-owners.index')
            ->with('success', 'House Owner deleted successfully.');
    }

    /**
     * Deactivate a house owner
     */
    public function deactivate(HouseOwner $houseOwner)
    {
        $houseOwner->update(['is_active' => false]);

        return redirect()->back()
            ->with('success', 'House Owner deactivated successfully.');
    }

    /**
     * Activate a house owner
     */
    public function activate(HouseOwner $houseOwner)
    {
        $houseOwner->update(['is_active' => true]);

        return redirect()->back()
            ->with('success', 'House Owner activated successfully.');
    }
}
