<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PesananController extends Controller
{
    /**
     * Display a listing of the user's orders.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Debug: Log awal
            Log::info('Mengakses halaman pesanan');
            
            $user = Auth::user();
            if (!$user) {
                Log::warning('User tidak terautentikasi saat mengakses halaman pesanan');
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }

            Log::info('Mengambil data pesanan untuk user: ' . $user->id);
            
            // Ambil data pesanan dengan relasi sederhana dulu
            $pesanan = Order::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            Log::info('Ditemukan ' . $pesanan->count() . ' pesanan untuk user ' . $user->id);
            
            return view('pages.pesanan.index', [
                'pesanan' => $pesanan,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error di PesananController@index: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            return response()->view('errors.custom', [
                'message' => 'Terjadi kesalahan saat memuat data pesanan. Silakan coba lagi nanti.',
                'code' => 500,
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
