<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TeacherMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if ($user->role !== 'teacher') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. This endpoint is restricted to Teachers.',
            ], 403);
        }

        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Your teacher account has been deactivated. Please contact your Institute Administrator.',
            ], 403);
        }

        if (!$user->institute_id || !$user->institute || !$user->institute->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Your institute account is inactive. Please contact administration.',
            ], 403);
        }

        if ($user->institute->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'Your institute subscription/validity has expired. Please contact administration.',
            ], 403);
        }

        return $next($request);
    }
}
