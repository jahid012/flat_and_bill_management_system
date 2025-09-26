<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        
        switch ($role) {
            case 'admin':
                if (!Auth::guard('admin')->check()) {
                    return redirect()->route('admin.login');
                }
                $user = Auth::guard('admin')->user();
                if (!($user instanceof \App\Models\Admin)) {
                    abort(403, 'Access denied. Admin role required.');
                }
                break;
                
            case 'house_owner':
                if (!Auth::guard('house_owner')->check()) {
                    return redirect()->route('house_owner.login');
                }
                $user = Auth::guard('house_owner')->user();
                if (!($user instanceof \App\Models\HouseOwner)) {
                    abort(403, 'Access denied. House Owner role required.');
                }
                break;
                
            default:
                abort(403, 'Invalid role specified.');
        }

        return $next($request);
    }
}
