<?php

namespace App\Http\Middleware;

use Closure;

class ApiKeyAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $apiKey = $request->header('X-API-Key');
        $expectedKey = config('services.pagamento.api_key');

        if (!$apiKey || $apiKey !== $expectedKey) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized. Invalid API key.'
            ], 401);
        }

        return $next($request);
    }
}
