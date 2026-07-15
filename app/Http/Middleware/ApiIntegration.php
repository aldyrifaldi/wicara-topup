<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiIntegration
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-Key');
        $apiSecret = $request->header('X-API-Secret');

        if (!$apiKey || !$apiSecret) {
            return response()->json([
                'success' => false,
                'message' => 'API credentials required',
                'errors' => [
                    'credentials' => ['X-API-Key and X-API-Secret headers are required']
                ]
            ], 401);
        }

        // Retrieve API integration from database or config
        $validApi = \App\Models\ApiIntegration::where('api_key', $apiKey)
            ->where('api_secret', $apiSecret)
            ->where('is_active', true)
            ->first();

        if (!$validApi) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API credentials',
                'errors' => []
            ], 401);
        }

        // Update last used timestamp
        $validApi->update([
            'last_used_at' => now(),
        ]);

        // Attach API integration to request for later use
        $request->attributes->set('api_integration', $validApi);

        return $next($request);
    }
}