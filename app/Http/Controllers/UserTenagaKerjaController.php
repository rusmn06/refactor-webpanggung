<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RumahTangga;
use App\Traits\ExportExcelTrait;

class UserTenagaKerjaController extends Controller
{
    
    use ExportExcelTrait;

    // Daftar semua pengajuan milik user yang login
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Hitung statistik
        $totalPengajuan     = RumahTangga::where('user_id', $userId)->count();
        $pengajuanPending   = RumahTangga::where('user_id', $userId)->where('status_validasi', 'pending')->count();
        $pengajuanDisetujui = RumahTangga::where('user_id', $userId)->where('status_validasi', 'validated')->count();
        $pengajuanDitolak   = RumahTangga::where('user_id', $userId)->where('status_validasi', 'rejected')->count();

        // Query utama dengan filter opsional
        $query = RumahTangga::where('user_id', $userId);

        if ($request->filled('status')) {
            $query->where('status_validasi', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_responden', 'like', "%{$search}%")
                  ->orWhere('desa', 'like', "%{$search}%")
                  ->orWhere('kecamatan', 'like', "%{$search}%");
                if (is_numeric($search)) {
                    $q->orWhere('user_sequence_number', $search);
                }
            });
        }

        $items = $query->orderBy('created_at', 'desc')
                       ->paginate(8)
                       ->withQueryString();

        return view('pages.tenagakerja.index', compact(
            'items',
            'totalPengajuan',
            'pengajuanPending',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

    // Detail satu pengajuan
    public function show($id)
    {
        $item = RumahTangga::with('anggotaKeluarga')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('pages.tenagakerja.detail', compact('item'));
    }

    public function exportExcel($id)
    {
        $item = RumahTangga::with('anggotaKeluarga')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return $this->generateExcel($item);
    }
}