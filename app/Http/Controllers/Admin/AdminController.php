<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\RumahTangga;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $rtCounts = RumahTangga::select('rt', DB::raw('COUNT(*) as total'))
            ->groupBy('rt')
            ->orderBy('rt')
            ->pluck('total', 'rt');

        $grandTotal = $rtCounts->sum();

        // Siapkan label & data untuk chart DI SINI
        $chartLabels = $rtCounts->keys()
            ->map(fn($k) => 'RT ' . str_pad($k, 3, '0', STR_PAD_LEFT))
            ->values()
            ->toArray();

        $chartData = $rtCounts->values()->toArray();

        $stats = [
            'total'     => RumahTangga::count(),
            'pending'   => RumahTangga::where('status_validasi', 'pending')->count(),
            'validated' => RumahTangga::where('status_validasi', 'validated')->count(),
            'rejected'  => RumahTangga::where('status_validasi', 'rejected')->count(),
            'users'     => User::where('role', 'user')->count(),
        ];

        return view('admin.dashboard', compact(
            'grandTotal',
            'stats',
            'chartLabels',
            'chartData'
        ));
    }
}
