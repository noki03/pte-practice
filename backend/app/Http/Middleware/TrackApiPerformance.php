<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TrackApiPerformance
{
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);

        $response = $next($request);

        $durationMs = (int) round((microtime(true) - $start) * 1000);

        $response->headers->set('X-Response-Time', "{$durationMs}ms");

        $threshold = config('pte.performance.slow_response_threshold_ms', 200);

        if ($durationMs > $threshold) {
            Log::channel('stack')->warning('Slow API response', [
                'method'      => $request->method(),
                'path'        => $request->path(),
                'duration_ms' => $durationMs,
                'user_id'     => $request->user()?->id,
            ]);
        }

        return $response;
    }
}
