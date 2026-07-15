<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
                'errors' => []
            ], 401);
        }

        // Check if user has the required role
        if ($user->role !== $role) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
                'errors' => [
                    'role' => ["This action requires {$role} role"]
                ]
            ], 403);
        }

        return $next($request);
    }
}