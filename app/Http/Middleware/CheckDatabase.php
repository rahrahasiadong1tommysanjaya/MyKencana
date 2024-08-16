<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class CheckDatabase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         // Daftar rute yang tidak perlu pengecekan database
        $excludedRoutes = [
            'restore-database', // nama rute yang dikecualikan
        ];

        // Jika rute saat ini ada dalam daftar pengecualian, lewati pengecekan database
        if (in_array($request->route()->getName(), $excludedRoutes)) {
            return $next($request);
        }

        try {
            // Coba koneksi ke database
            DB::connection()->getPdo();
        } catch (\Exception $e) {
                        
            // Tampilkan response page restore ketika database tidak ada
            $pageConfigs = ['myLayout' => 'blank'];
            return response()->view('content.restore-database.index', ['pageConfigs' => $pageConfigs]);
        }
        
        // Lanjutkan eksekusi middleware berikutnya jika koneksi berhasil
        return $next($request);
    }
}
