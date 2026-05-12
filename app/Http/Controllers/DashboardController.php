<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\RumahTangga;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $rtCounts = RumahTangga::select('rt', DB::raw('COUNT(*) as total'))
            ->groupBy('rt')
            ->orderBy('rt')
            ->pluck('total', 'rt');

        $grandTotal = $rtCounts->sum();

        // Siapkan label & data untuk chart DI SINI, bukan di Blade
        $chartLabels = $rtCounts->keys()
            ->map(fn($k) => 'RT ' . str_pad($k, 3, '0', STR_PAD_LEFT))
            ->values()
            ->toArray();

        $chartData = $rtCounts->values()->toArray();

        $myStats = [
            'total'     => RumahTangga::where('user_id', $userId)->count(),
            'pending'   => RumahTangga::where('user_id', $userId)->where('status_validasi', 'pending')->count(),
            'validated' => RumahTangga::where('user_id', $userId)->where('status_validasi', 'validated')->count(),
            'rejected'  => RumahTangga::where('user_id', $userId)->where('status_validasi', 'rejected')->count(),
        ];

        return view('pages.dashboard', compact(
            'grandTotal',
            'myStats',
            'chartLabels',
            'chartData'
        ));
    }
}
