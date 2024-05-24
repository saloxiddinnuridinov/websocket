<?php

namespace App\Http\Middleware;

use App\Models\Group;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckGroup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        $username = $request->route('group_username');

        $group = Group::where('username', $username)
            ->selectRaw("IF(group_type = 'public', true, false) as is_public")
            ->first();
        $isPublic = $group->is_public;

        if ($isPublic || !empty($user)) {
            return $next($request);
        }
        return response()->json(['message' => 'Unauthenticated'], 401);
    }
}
