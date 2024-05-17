<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\StatsArticle;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StatsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $stats = new StatsArticle([
            'url' => $request->path(),
        ]);

        $stats->save();

        return $next($request);
    }
}
