<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RumahTangga;
use App\Models\AnggotaKeluarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Traits\ExportExcelTrait;

class TenagaKerjaVerifController extends Controller
{
    
    use ExportExcelTrait;
    
    // Daftar pengajuan pending
    public function index()
    {
        $items = RumahTangga::where('status_validasi', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.tkw.index', compact('items'));
    }


    public function listRtPage()
    {
        $rumahTanggaCounts = RumahTangga::select('rt', DB::raw('count(*) as total_rt'))
            ->whereBetween('rt', [1, 24])
            ->groupBy('rt')
            ->pluck('total_rt', 'rt');

        $anggotaCounts = AnggotaKeluarga::select('fm_rumah_tangga.rt', DB::raw('count(fm_anggota_keluarga.id) as total_anggota'))
            ->join('fm_rumah_tangga', 'fm_anggota_keluarga.rumah_tangga_id', '=', 'fm_rumah_tangga.id')
            ->whereBetween('fm_rumah_tangga.rt', [1, 24])
            ->groupBy('fm_rumah_tangga.rt')
            ->pluck('total_anggota', 'rt');

        return view('admin.tkw.listrt', compact('rumahTanggaCounts', 'anggotaCounts'));
    }

    public function show($id)
    {
        $item = RumahTangga::with('anggotaKeluarga')->findOrFail($id);
        return view('admin.tkw.show', compact('item'));
    }

    public function showRtData($rt)
    {
        $rumahTaggas = RumahTangga::where('rt', $rt)
            ->with('anggotaKeluarga')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.tkw.rtshow', compact('rumahTaggas', 'rt'));
    }

    public function processVerification(Request $request, $id)
    {
        $item = RumahTangga::findOrFail($id);

        $rules = [
            'status'       => 'required|in:validated,rejected',
            'admin_catatan' => 'required_if:status,rejected|nullable|string|max:500',
            'admin_tgl_validasi'      => 'required_if:status,validated|nullable|date',
            'admin_nama_kepaladusun'  => 'required_if:status,validated|nullable|string|max:100',
            'admin_ttd_pendata'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        $validated = $request->validate($rules, [
            'admin_catatan.required_if'           => 'Alasan penolakan wajib diisi.',
            'admin_tgl_validasi.required_if'      => 'Tanggal verifikasi wajib diisi.',
            'admin_nama_kepaladusun.required_if'  => 'Nama verifikator wajib diisi.',
        ]);

        $update = ['status_validasi' => $validated['status']];

        if ($validated['status'] === 'validated') {
            // Handle upload TTD baru (opsional jika sudah ada)
            if ($request->hasFile('admin_ttd_pendata')) {
                if ($item->admin_ttd_pendata) {
                    Storage::disk('public')->delete('ttd/admin/' . $item->admin_ttd_pendata);
                }
                $path = $request->file('admin_ttd_pendata')->store('ttd/admin', 'public');
                $update['admin_ttd_pendata'] = basename($path);
            }
            $update['admin_tgl_validasi']     = $validated['admin_tgl_validasi'];
            $update['admin_nama_kepaladusun'] = $validated['admin_nama_kepaladusun'];
            $update['admin_catatan']          = null;
        } else {
            $update['admin_catatan']          = $validated['admin_catatan'];
            $update['admin_tgl_validasi']     = null;
            $update['admin_nama_kepaladusun'] = null;
        }

        $item->update($update);

        return redirect()->route('admin.tkw.show', $item->id)
            ->with('success', "Status pengajuan {$item->nama_responden} berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $item = RumahTangga::findOrFail($id);
        $rt   = $item->rt;

        DB::beginTransaction();
        try {
            // Hapus file TTD
            if ($item->ttd_pendata) {
                Storage::disk('public')->delete('ttd/pendata/' . $item->ttd_pendata);
            }
            if ($item->admin_ttd_pendata) {
                Storage::disk('public')->delete('ttd/admin/' . $item->admin_ttd_pendata);
            }

            // Anggota terhapus otomatis (cascade), tapi eksplisit lebih aman
            $item->anggotaKeluarga()->delete();
            $item->delete();

            DB::commit();

            return redirect()->route('admin.tkw.showrt', $rt)
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal hapus data: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus.');
        }
    }

    public function exportExcel($id)
    {
        $item = RumahTangga::with('anggotaKeluarga')->findOrFail($id);
        return $this->generateExcel($item);
    }

}
