<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\HouseOwner;
use App\Models\Building;
use App\Models\Flat;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        return view('admin.login');
    }

    /**
     * Handle admin login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login');
    }

    /**
     * Show admin dashboard.
     */
    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();
        
        
        $houseOwnersCount = HouseOwner::count();
        $buildingsCount = Building::count();
        $flatsCount = Flat::count();
        $tenantsCount = Tenant::count();
        
        
        $recentHouseOwners = HouseOwner::with('buildings')
            ->withCount('buildings')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'admin', 
            'houseOwnersCount', 
            'buildingsCount', 
            'flatsCount', 
            'tenantsCount',
            'recentHouseOwners'
        ));
    }
}
