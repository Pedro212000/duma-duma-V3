<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Retrieve the authenticated user's role
        $authUserRole = Auth::user()->role;

        // Define route-role mapping
        $roles = [
            'admin.dashboard' => 'Admin',
            'publisher.dashboard' => 'Publisher',
        ];

        // Check if the user's role matches the route's required role
        if (isset($roles[$role]) && $roles[$role] === $authUserRole) {
            return $next($request);
        }

        // Redirect to the appropriate dashboard if roles don't match
        switch ($authUserRole) {
            case 'Admin':
                return redirect()->route('admin.dashboard');
            case 'Publisher':
                return redirect()->route('publisher.dashboard');
        }

        // Default redirect to login if something goes wrong
        return redirect()->route('login');
    }
}
