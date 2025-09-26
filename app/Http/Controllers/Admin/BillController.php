<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Building;
use App\Models\BillCategory;
use Illuminate\Http\Request;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Bill::with(['flat.building.houseOwner', 'billCategory']);

        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('bill_month', 'like', "%{$search}%")
                  ->orWhereHas('flat', function($flatQuery) use ($search) {
                      $flatQuery->where('flat_number', 'like', "%{$search}%");
                  });
            });
        }

        
        if ($request->filled('house_owner')) {
            $query->whereHas('flat.building', function($q) use ($request) {
                $q->where('house_owner_id', $request->house_owner);
            });
        }

        
        if ($request->filled('building_id')) {
            $query->where('building_id', $request->building_id);
        }

        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        
        if ($request->filled('month')) {
            $query->where('bill_month', $request->month);
        }

        
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $bills = $query->paginate(15)->appends($request->query());
        $buildings = Building::all();
        $billCategories = BillCategory::all();

        return view('admin.bills.index', compact('bills', 'buildings', 'billCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $buildings = Building::with(['flats' => function($query) {
            $query->where('is_occupied', true)->with('currentTenant');
        }])->get();
        
        $billCategories = BillCategory::all();
        $selectedHouseOwner = null;
        
        
        if ($request->filled('house_owner_id')) {
            $selectedHouseOwner = \App\Models\HouseOwner::find($request->house_owner_id);
            $buildings = $buildings->where('house_owner_id', $request->house_owner_id);
            $billCategories = $billCategories->whereIn('building_id', $buildings->pluck('id'));
        }
        
        return view('admin.bills.create', compact('buildings', 'billCategories', 'selectedHouseOwner'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'flat_id' => 'required|exists:flats,id',
            'bill_category_id' => 'required|exists:bill_categories,id',
            'bill_month' => 'required|date_format:Y-m',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date|after:today',
            'notes' => 'nullable|string|max:1000',
        ]);

        
        $flat = \App\Models\Flat::findOrFail($request->flat_id);
        
        
        $existingBill = Bill::where('flat_id', $request->flat_id)
            ->where('bill_category_id', $request->bill_category_id)
            ->where('bill_month', $request->bill_month)
            ->first();

        if ($existingBill) {
            return back()->withErrors(['bill_month' => 'Bill for this month and category already exists.'])
                        ->withInput();
        }

        
        $previousUnpaidBill = Bill::where('flat_id', $request->flat_id)
            ->where('bill_category_id', $request->bill_category_id)
            ->where('status', '!=', 'paid')
            ->where('bill_month', '<', $request->bill_month)
            ->orderBy('bill_month', 'desc')
            ->first();

        $previousDue = $previousUnpaidBill ? 
            ($previousUnpaidBill->amount + $previousUnpaidBill->previous_due - $previousUnpaidBill->paid_amount) : 0;

        $bill = Bill::create([
            'flat_id' => $request->flat_id,
            'bill_category_id' => $request->bill_category_id,
            'building_id' => $flat->building_id,
            'bill_month' => $request->bill_month,
            'amount' => $request->amount,
            'previous_due' => $previousDue,
            'total_amount' => $request->amount + $previousDue,
            'due_date' => $request->due_date,
            'notes' => $request->notes,
            'status' => 'unpaid',
            'bill_number' => 'BILL-' . strtoupper(uniqid()),
        ]);

        return redirect()->route('admin.bills.index')
                        ->with('success', 'Bill created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bill $bill)
    {
        $bill->load(['flat.building.houseOwner', 'flat.currentTenant', 'billCategory']);
        
        return view('admin.bills.show', compact('bill'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bill $bill)
    {
        $buildings = Building::with(['flats' => function($query) {
            $query->where('is_occupied', true)->with('currentTenant');
        }])->get();
        
        $billCategories = BillCategory::all();
        
        return view('admin.bills.edit', compact('bill', 'buildings', 'billCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bill $bill)
    {
        $request->validate([
            'flat_id' => 'required|exists:flats,id',
            'bill_category_id' => 'required|exists:bill_categories,id',
            'bill_month' => 'required|date_format:Y-m',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        
        $flat = \App\Models\Flat::findOrFail($request->flat_id);
        
        // Check for existing bill for same flat, category, and month (excluding current bill)
        $existingBill = Bill::where('flat_id', $request->flat_id)
            ->where('bill_category_id', $request->bill_category_id)
            ->where('bill_month', $request->bill_month)
            ->where('id', '!=', $bill->id)
            ->first();

        if ($existingBill) {
            return back()->withErrors(['bill_month' => 'Bill for this month and category already exists.'])
                        ->withInput();
        }

        
        $totalAmount = $request->amount + $bill->previous_due;

        $bill->update([
            'flat_id' => $request->flat_id,
            'bill_category_id' => $request->bill_category_id,
            'building_id' => $flat->building_id,
            'bill_month' => $request->bill_month,
            'amount' => $request->amount,
            'total_amount' => $totalAmount,
            'due_date' => $request->due_date,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.bills.show', $bill)
                        ->with('success', 'Bill updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bill $bill)
    {
        
        if ($bill->status === 'paid') {
            return redirect()->route('admin.bills.index')
                           ->with('error', 'Cannot delete a paid bill. Please contact system administrator.');
        }

        $billNumber = $bill->bill_number;
        $flatNumber = $bill->flat->flat_number;
        $bill->delete();

        return redirect()->route('admin.bills.index')
                        ->with('success', "Bill '{$billNumber}' for flat '{$flatNumber}' deleted successfully.");
    }

    /**
     * Mark a bill as paid.
     */
    public function markAsPaid(Request $request, Bill $bill)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:0|max:' . $bill->total_amount,
            'payment_date' => 'required|date|before_or_equal:today',
            'payment_method' => 'nullable|string|max:50',
            'payment_notes' => 'nullable|string|max:500',
        ]);

        $paidAmount = $request->paid_amount;
        $totalAmount = $bill->total_amount;

        
        if ($paidAmount >= $totalAmount) {
            $status = 'paid';
        } elseif ($paidAmount > 0) {
            $status = 'partially_paid';
        } else {
            $status = $bill->status; 
        }

        $bill->update([
            'paid_amount' => ($bill->paid_amount ?? 0) + $paidAmount,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'payment_notes' => $request->payment_notes,
            'status' => $status,
        ]);

        
        if ($status === 'paid') {
            event(new \App\Events\BillPaid($bill));
        }

        $message = $status === 'paid' ? 'Bill marked as fully paid!' : 'Partial payment recorded successfully!';

        return redirect()->route('admin.bills.show', $bill)
                        ->with('success', $message);
    }
}