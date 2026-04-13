<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\VisitorStat;

class TrackVisitor
{
    public function handle(Request $request, Closure $next)
    {
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent() ?? 'unknown';
        $pageName = $request->route()->getName() ?? basename($request->path());
        $visitDate = now()->toDateString();
        $isAdmin = $request->session()->get('admin_logged_in', false);

        $exists = VisitorStat::where('ip_address', $ipAddress)
            ->where('page_visited', $pageName)
            ->where('visit_date', $visitDate)
            ->exists();

        if (!$exists) {
            VisitorStat::create([
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'page_visited' => $pageName,
                'visit_date' => $visitDate,
                'is_admin' => $isAdmin ? 1 : 0,
            ]);
        }

        return $next($request);
    }
}
