<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CandidateAccessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->isAdmin()) {
            return $next($request);
        }

        if ($user->hasLearningAccess()) {
            return $next($request);
        }

        return redirect()
            ->route('dashboard')
            ->with('error', __('ui.candidate_access.payment_required'));
    }
}