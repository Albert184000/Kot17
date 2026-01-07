<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // 1) If not logged in -> redirect to login
        if (!auth()->check()) {
            return redirect()->route('login'); // or redirect('login')
        }

        $userRole = auth()->user()->role;

        // 2) Support both:
        // - role:admin,treasurer,utility
        // - role:admin|treasurer|utility  (if passed as one string)
        if (count($roles) === 1 && str_contains($roles[0], '|')) {
            $roles = explode('|', $roles[0]);
        }

        // clean whitespace just in case
        $roles = array_map('trim', $roles);

        // 3) authorize
        if (!in_array($userRole, $roles, true)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
