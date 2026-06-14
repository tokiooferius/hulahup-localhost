<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCanteenAccess
{
    /**
     * Handle an incoming request.
     * 
     * Memastikan ibu_kantin hanya bisa akses canteen miliknya sendiri
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        // Jika bukan ibu_kantin, boleh lanjut
        if ($user->role !== 'ibu_kantin') {
            return $next($request);
        }

        // Jika ibu_kantin, check apakah canteen_id di parameter sama dengan miliknya
        $canteenId = $request->route('canteen') ?? $request->route('canteen_id');
        
        if ($canteenId && $user->canteen_id != $canteenId) {
            abort(403, 'Anda tidak memiliki akses ke kantin ini');
        }

        return $next($request);
    }
}
