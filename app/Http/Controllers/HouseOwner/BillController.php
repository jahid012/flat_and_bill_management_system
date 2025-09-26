<?php

namespace App\Http\Controllers\HouseOwner;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Building;
use App\Models\Flat;
use App\Models\BillCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $houseOwner = Auth::guard('house_owner')->user();
        
        $bills = Bill::whereHas('building', function ($query) use ($houseOwner) {
            $query->where('house_owner_id', $houseOwner->id);
        })
        ->with(['flat', 'billCategory', 'building'])
        ->when($request->status, function ($query, $status) {
            return $query->where('status', $status);
        })
        ->when($request->building_id, function ($query, $buildingId) {
            return $query->where('building_id', $buildingId);
        })
        ->orderBy('due_date', 'desc')
        ->paginate(15);

        $buildings = $houseOwner->buildings;
        
        return view('house_owner.bills.index', compact('bills', 'buildings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $houseOwner = Auth::guard('house_owner')->user();
        $buildings = $houseOwner->buildings;
        $flats = $houseOwner->flats;
        $billCategories = BillCategory::whereIn('building_id', $buildings->pluck('id'))->get();
        
        return view('house_owner.bills.create', compact('buildings', 'flats', 'billCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $houseOwner = Auth::guard('house_owner')->user();
        
        $request->validate([
            'flat_id' => 'required|exists:flats,id',
            'bill_category_id' => 'required|exists:bill_categories,id',
            'bill_month' => 'required|date_format:Y-m',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date|after:today',
            'notes' => 'nullable|string',
        ]);

        
        $flat = Flat::where('id', $request->flat_id)
            ->where('house_owner_id', $houseOwner->id)
            ->firstOrFail();

        
        $existingBill = Bill::where('flat_id', $request->flat_id)
            ->where('bill_category_id', $request->bill_category_id)
            ->where('bill_month', $request->bill_month)
            ->first();

        if ($existingBill) {
            return back()->withErrors(['bill_month' => 'Bill for this month and category already exists.']);
        }

        
        $previousUnpaidBill = Bill::where('flat_id', $request->flat_id)
            ->where('bill_category_id', $request->bill_category_id)
            ->where('status', '!=', 'paid')
            ->where('bill_month', '<', $request->bill_month)
            ->orderBy('bill_month', 'desc')
            ->first();

        $dueAmount = $previousUnpaidBill ? $previousUnpaidBill->amount + $previousUnpaidBill->due_amount - $previousUnpaidBill->paid_amount : 0;

        $bill = Bill::create([
            'flat_id' => $request->flat_id,
            'bill_category_id' => $request->bill_category_id,
            'building_id' => $flat->building_id,
            'bill_month' => $request->bill_month,
            'amount' => $request->amount,
            'due_amount' => $dueAmount,
            'due_date' => $request->due_date,
            'notes' => $request->notes,
            'created_by' => $houseOwner->id,
        ]);

        
        event(new \App\Events\BillCreated($bill));

        return redirect()->route('house_owner.bills.index')
            ->with('success', 'Bill created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bill $bill)
    {
        
        $houseOwner = Auth::guard('house_owner')->user();
        if ($bill->building->house_owner_id !== $houseOwner->id) {
            abort(403);
        }

        $bill->load(['flat', 'billCategory', 'building']);
        
        return view('house_owner.bills.show', compact('bill'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bill $bill)
    {
        
        $houseOwner = Auth::guard('house_owner')->user();
        if ($bill->building->house_owner_id !== $houseOwner->id) {
            abort(403);
        }

        $buildings = $houseOwner->buildings;
        $flats = $houseOwner->flats;
        $billCategories = BillCategory::whereIn('building_id', $buildings->pluck('id'))->get();
        
        return view('house_owner.bills.edit', compact('bill', 'buildings', 'flats', 'billCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bill $bill)
    {
        
        $houseOwner = Auth::guard('house_owner')->user();
        if ($bill->building->house_owner_id !== $houseOwner->id) {
            abort(403);
        }

        $request->validate([
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $bill->update($request->only('amount', 'due_date', 'notes'));

        return redirect()->route('house_owner.bills.index')
            ->with('success', 'Bill updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bill $bill)
    {
        
        $houseOwner = Auth::guard('house_owner')->user();
        if ($bill->building->house_owner_id !== $houseOwner->id) {
            abort(403);
        }

        
        if ($bill->status === 'paid') {
            return redirect()->route('house_owner.bills.index')
                ->with('error', 'Cannot delete paid bills.');
        }

        $bill->delete();

        return redirect()->route('house_owner.bills.index')
            ->with('success', 'Bill deleted successfully.');
    }

    /**
     * Mark a bill as paid.
     */
    public function markAsPaid(Request $request, Bill $bill)
    {
        
        $houseOwner = Auth::guard('house_owner')->user();
        if ($bill->building->house_owner_id !== $houseOwner->id) {
            abort(403);
        }

        $request->validate([
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
            'payment_notes' => 'nullable|string',
        ]);

        $totalAmount = $bill->amount + $bill->due_amount;
        $paidAmount = $request->paid_amount;

        $status = $paidAmount >= $totalAmount ? 'paid' : 'partially_paid';

        $bill->update([
            'paid_amount' => $paidAmount,
            'status' => $status,
            'paid_date' => now(),
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'payment_notes' => $request->payment_notes,
        ]);

        
        event(new \App\Events\BillPaid($bill));

        return redirect()->route('house_owner.bills.show', $bill)
            ->with('success', 'Payment recorded successfully.');
    }

    /**
     * Show overdue bills.
     */
    public function overdue()
    {
        $houseOwner = Auth::guard('house_owner')->user();
        
        $overdueBills = Bill::whereHas('building', function ($query) use ($houseOwner) {
            $query->where('house_owner_id', $houseOwner->id);
        })
        ->where(function ($query) {
            $query->where('status', 'overdue')
                  ->orWhere(function ($q) {
                      $q->where('status', 'unpaid')
                        ->where('due_date', '<', now());
                  });
        })
        ->with(['flat', 'billCategory', 'building'])
        ->orderBy('due_date', 'asc')
        ->paginate(15);

        return view('house_owner.bills.overdue', compact('overdueBills'));
    }

    /**
     * Generate monthly report.
     */
    public function monthlyReport(Request $request)
    {
        $houseOwner = Auth::guard('house_owner')->user();
        $month = $request->get('month', now()->format('Y-m'));

        $report = [
            'month' => $month,
            'total_bills' => 0,
            'total_amount' => 0,
            'paid_amount' => 0,
            'unpaid_amount' => 0,
            'bills_by_category' => [],
            'bills_by_building' => [],
        ];

        $bills = Bill::whereHas('building', function ($query) use ($houseOwner) {
            $query->where('house_owner_id', $houseOwner->id);
        })
        ->where('bill_month', $month)
        ->with(['billCategory', 'building'])
        ->get();

        $report['total_bills'] = $bills->count();
        $report['total_amount'] = $bills->sum(function ($bill) {
            return $bill->amount + $bill->due_amount;
        });
        $report['paid_amount'] = $bills->where('status', 'paid')->sum('paid_amount');
        $report['unpaid_amount'] = $report['total_amount'] - $report['paid_amount'];

        
        $report['bills_by_category'] = $bills->groupBy('billCategory.name')->map(function ($categoryBills) {
            return [
                'count' => $categoryBills->count(),
                'total_amount' => $categoryBills->sum(function ($bill) {
                    return $bill->amount + $bill->due_amount;
                }),
                'paid_amount' => $categoryBills->where('status', 'paid')->sum('paid_amount'),
            ];
        });

        
        $report['bills_by_building'] = $bills->groupBy('building.name')->map(function ($buildingBills) {
            return [
                'count' => $buildingBills->count(),
                'total_amount' => $buildingBills->sum(function ($bill) {
                    return $bill->amount + $bill->due_amount;
                }),
                'paid_amount' => $buildingBills->where('status', 'paid')->sum('paid_amount'),
            ];
        });

        return view('house_owner.reports.monthly', compact('report'));
    }
}
