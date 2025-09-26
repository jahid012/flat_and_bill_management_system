<?php

namespace App\Http\Controllers\HouseOwner;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Flat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantController extends Controller
{
    /**
     * Display a listing of tenants.
     */
    public function index(Request $request)
    {
        $houseOwner = auth('house_owner')->user();
        
        
        $query = Tenant::whereHas('flat.building', function ($q) use ($houseOwner) {
            $q->where('house_owner_id', $houseOwner->id);
        })->with(['flat.building', 'bills']);
        
        
        if ($request->filled('building_id')) {
            $query->whereHas('flat', function ($q) use ($request) {
                $q->where('building_id', $request->building_id);
            });
        }
        
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $tenants = $query->paginate(15)->appends($request->query());
        $buildings = $houseOwner->buildings;
        
        return view('house_owner.tenants.index', compact('tenants', 'buildings'));
    }

    /**
     * Display the specified tenant.
     */
    public function show(Tenant $tenant)
    {
        
        if ($tenant->building && $tenant->building->house_owner_id !== auth('house_owner')->id()) {
            abort(403, 'You do not have permission to view this tenant.');
        }

        $tenant->load(['building', 'flat', 'bills.billCategory']);
        
        
        $totalBills = $tenant->bills()->count();
        $paidBills = $tenant->bills()->where('status', 'paid')->count();
        $unpaidBills = $tenant->bills()->where('status', 'unpaid')->count();
        $overdueBills = $tenant->bills()->where('status', 'overdue')->count();
        $totalRevenue = $tenant->bills()->where('status', 'paid')->sum('paid_amount');
        $pendingAmount = $tenant->bills()->where('status', 'unpaid')->sum('amount');
        $overdueAmount = $tenant->bills()->where('status', 'overdue')->sum('amount');

        return view('house_owner.tenants.show', compact(
            'tenant',
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
     * Show the form for creating a new tenant.
     */
    public function create()
    {
        $houseOwner = auth('house_owner')->user();
        $buildings = $houseOwner->buildings;
        $availableFlats = Flat::where('house_owner_id', $houseOwner->id)
                               ->where('is_occupied', false)
                               ->with('building')
                               ->get();
        
        return view('house_owner.tenants.create', compact('buildings', 'availableFlats'));
    }

    /**
     * Store a newly created tenant.
     */
    public function store(Request $request)
    {
        $houseOwner = auth('house_owner')->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:tenants,email',
            'phone' => 'required|string|max:20',
            'national_id' => 'nullable|string|max:50',
            'flat_id' => 'nullable|exists:flats,id',
            'lease_start_date' => 'nullable|date',
            'lease_end_date' => 'nullable|date|after:lease_start_date',
            'monthly_rent' => 'nullable|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        
        if ($request->flat_id) {
            $flat = Flat::where('house_owner_id', $houseOwner->id)
                        ->where('id', $request->flat_id)
                        ->first();
            if (!$flat) {
                return back()->withErrors(['flat_id' => 'Selected flat does not belong to you.']);
            }
            if ($flat->is_occupied) {
                return back()->withErrors(['flat_id' => 'Selected flat is not available.']);
            }
        }

        $tenant = Tenant::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'national_id' => $request->national_id,
            'flat_id' => $request->flat_id,
            'lease_start_date' => $request->lease_start_date,
            'lease_end_date' => $request->lease_end_date,
            'monthly_rent' => $request->monthly_rent,
            'security_deposit' => $request->security_deposit,
            'status' => $request->status,
        ]);

        
        if ($request->flat_id) {
            $flat->update(['is_occupied' => true]);
        }

        return redirect()->route('house_owner.tenants.index')
                         ->with('success', 'Tenant created successfully.');
    }

    /**
     * Show the form for editing the specified tenant.
     */
    public function edit(Tenant $tenant)
    {
        $houseOwner = auth('house_owner')->user();
        
        
        if ($tenant->flat && $tenant->flat->building && $tenant->flat->building->house_owner_id !== $houseOwner->id) {
            abort(403, 'You do not have permission to edit this tenant.');
        }

        $buildings = $houseOwner->buildings;
        $availableFlats = Flat::where('house_owner_id', $houseOwner->id)
                               ->where(function($query) use ($tenant) {
                                   $query->where('is_occupied', false)
                                         ->orWhere('id', $tenant->flat_id);
                               })
                               ->with('building')
                               ->get();
        
        return view('house_owner.tenants.edit', compact('tenant', 'buildings', 'availableFlats'));
    }

    /**
     * Update the specified tenant.
     */
    public function update(Request $request, Tenant $tenant)
    {
        $houseOwner = auth('house_owner')->user();
        
        
        if ($tenant->flat && $tenant->flat->building && $tenant->flat->building->house_owner_id !== $houseOwner->id) {
            abort(403, 'You do not have permission to update this tenant.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:tenants,email,' . $tenant->id,
            'phone' => 'required|string|max:20',
            'national_id' => 'nullable|string|max:50',
            'flat_id' => 'nullable|exists:flats,id',
            'lease_start_date' => 'nullable|date',
            'lease_end_date' => 'nullable|date|after:lease_start_date',
            'monthly_rent' => 'nullable|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $oldFlatId = $tenant->flat_id;

        
        if ($request->flat_id && $request->flat_id != $oldFlatId) {
            $flat = Flat::where('house_owner_id', $houseOwner->id)
                        ->where('id', $request->flat_id)
                        ->first();
            if (!$flat) {
                return back()->withErrors(['flat_id' => 'Selected flat does not belong to you.']);
            }
            if ($flat->is_occupied) {
                return back()->withErrors(['flat_id' => 'Selected flat is not available.']);
            }
        }

        $tenant->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'national_id' => $request->national_id,
            'flat_id' => $request->flat_id,
            'lease_start_date' => $request->lease_start_date,
            'lease_end_date' => $request->lease_end_date,
            'monthly_rent' => $request->monthly_rent,
            'security_deposit' => $request->security_deposit,
            'status' => $request->status,
        ]);

        
        if ($oldFlatId != $request->flat_id) {
            
            if ($oldFlatId) {
                Flat::where('house_owner_id', $houseOwner->id)
                    ->where('id', $oldFlatId)
                    ->update(['is_occupied' => false]);
            }
            
            
            if ($request->flat_id) {
                Flat::where('house_owner_id', $houseOwner->id)
                    ->where('id', $request->flat_id)
                    ->update(['is_occupied' => true]);
            }
        }

        return redirect()->route('house_owner.tenants.index')
                         ->with('success', 'Tenant updated successfully.');
    }
}