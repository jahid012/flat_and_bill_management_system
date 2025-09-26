<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\HouseOwner;
use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class HouseOwnerAuthController extends Controller
{
    /**
     * Show the house owner login form.
     */
    public function showLoginForm()
    {
        return view('house_owner.login');
    }

    /**
     * Handle house owner login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('house_owner')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('house_owner.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    /**
     * Handle house owner logout.
     */
    public function logout(Request $request)
    {
        Auth::guard('house_owner')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('house_owner.login');
    }

    /**
     * Show house owner dashboard.
     */
    public function dashboard()
    {
        $houseOwner = Auth::guard('house_owner')->user();
        
        
        $buildingsCount = $houseOwner->buildings()->count();
        $flatsCount = $houseOwner->flats()->count();
        $tenantsCount = $houseOwner->flats()->whereNotNull('current_tenant_id')->count();
        
        
        $overdueBillsCount = Bill::whereHas('flat', function($query) use ($houseOwner) {
            $query->where('house_owner_id', $houseOwner->id);
        })->where('status', 'overdue')->count();
        
        
        $currentMonth = now()->format('Y-m');
        $thisMonthBills = Bill::whereHas('flat', function($query) use ($houseOwner) {
            $query->where('house_owner_id', $houseOwner->id);
        })->where('bill_month', $currentMonth)->count();
        
        $thisMonthPaidBills = Bill::whereHas('flat', function($query) use ($houseOwner) {
            $query->where('house_owner_id', $houseOwner->id);
        })->where('bill_month', $currentMonth)->where('status', 'paid')->count();
        
        $thisMonthRevenue = Bill::whereHas('flat', function($query) use ($houseOwner) {
            $query->where('house_owner_id', $houseOwner->id);
        })->where('bill_month', $currentMonth)->where('status', 'paid')->sum('paid_amount');
        
        $collectionRate = $thisMonthBills > 0 ? ($thisMonthPaidBills / $thisMonthBills) * 100 : 0;
        
        // Monthly revenue for chart (last 12 months)
        $monthlyRevenue = collect();
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthKey = $month->format('Y-m');
            $revenue = Bill::whereHas('flat', function($query) use ($houseOwner) {
                $query->where('house_owner_id', $houseOwner->id);
            })
            ->where('bill_month', $monthKey)
            ->where('status', 'paid')
            ->sum('paid_amount');
            
            $monthlyRevenue->push([
                'month' => $month->format('M Y'),
                'revenue' => $revenue
            ]);
        }
        
        
        $recentBills = Bill::whereHas('flat', function($query) use ($houseOwner) {
            $query->where('house_owner_id', $houseOwner->id);
        })
        ->with(['flat', 'billCategory'])
        ->where('status', 'overdue')
        ->orderBy('due_date', 'asc')
        ->take(5)
        ->get();

        return view('house_owner.dashboard', compact(
            'houseOwner', 'buildingsCount', 'flatsCount', 'tenantsCount', 
            'overdueBillsCount', 'thisMonthBills', 'thisMonthPaidBills', 
            'thisMonthRevenue', 'collectionRate', 'monthlyRevenue', 'recentBills'
        ));
    }
}
