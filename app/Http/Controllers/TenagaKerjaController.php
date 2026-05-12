<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\RumahTangga;
use App\Models\AnggotaKeluarga;

class TenagaKerjaController extends Controller
{
    public function edit($id)
    {
        $item = RumahTangga::with('anggotaKeluarga')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        // Data yang sudah disetujui tidak bisa diedit
        if ($item->status_validasi === 'validated') {
            return redirect()->route('tenagakerja.show', $id)
                ->with('error', 'Data yang sudah disetujui tidak dapat diubah.');
        }

        return view('pages.tenagakerja.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = RumahTangga::where('user_id', Auth::id())->findOrFail($id);

        if ($item->status_validasi === 'validated') {
            return redirect()->route('tenagakerja.show', $id)
                ->with('error', 'Data yang sudah disetujui tidak dapat diubah.');
        }

        $validated = $request->validate([
            // Lokasi
            'provinsi'        => 'required|string|max:50',
            'kabupaten'       => 'required|string|max:50',
            'kecamatan'       => 'required|string|max:50',
            'desa'            => 'required|string|max:50',
            'rt'              => 'required|numeric|min:1',
            'rw'              => 'required|numeric|min:1',
            'tgl_pembuatan'   => 'required|date',
            'nama_pendata'    => 'required|string|max:100',
            'nama_responden'  => 'required|string|max:100',
            // Rekap
            'jpr2rtp'         => 'required|in:0,1,2,3,4',
            // Verifikasi
            'verif_nama_pendata'  => 'required|string|max:100',
            'verif_tgl_pembuatan' => 'required|date',
            'ttd_pendata'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            // Anggota
            'nama'                   => 'required|array|min:1',
            'nama.*'                 => 'required|string|max:100',
            'nik'                    => 'required|array',
            'nik.*'                  => 'required|digits:16|distinct',
            'hdkrt'                  => 'required|array',
            'hdkrt.*'                => 'required|in:1,2,3,4,5,6,7,8',
            'nuk'                    => 'required|array',
            'nuk.*'                  => 'required|integer|min:1',
            'hdkk'                   => 'required|array',
            'hdkk.*'                 => 'required|in:1,2,3,4,5,6,7,8',
            'kelamin'                => 'required|array',
            'kelamin.*'              => 'required|in:1,2',
            'status_perkawinan'      => 'required|array',
            'status_perkawinan.*'    => 'required|in:1,2,3,4',
            'status_pekerjaan'       => 'required|array',
            'status_pekerjaan.*'     => 'required|in:1,2,3,4,5',
            'pendidikan_terakhir'    => 'required|array',
            'pendidikan_terakhir.*'  => 'required|in:1,2,3,4,5,6',
            'jenis_pekerjaan'        => 'required|array',
            'jenis_pekerjaan.*'      => 'required|in:1,2,3,4',
            'sub_jenis_pekerjaan'    => 'required|array',
            'sub_jenis_pekerjaan.*'  => 'required|in:1,2,3,4,5',
            'pendapatan_per_bulan'   => 'required|array',
            'pendapatan_per_bulan.*' => 'required|in:1,2,3,4,5,6',
        ]);

        DB::beginTransaction();
        try {
            // Hitung ulang rekap dari data anggota
            $statusPekerjaan = collect($validated['status_pekerjaan']);
            $recap = [
                'jart'    => count($validated['nama']),
                'jart_ab' => $statusPekerjaan->filter(fn($s) => $s == '1')->count(),
                'jart_ms' => $statusPekerjaan->filter(fn($s) => $s == '3')->count(),
                'jart_tb' => $statusPekerjaan->filter(fn($s) => in_array($s, ['2','4','5']))->count(),
            ];

            // Handle TTD baru jika diupload
            $ttdPath = $item->ttd_pendata;
            if ($request->hasFile('ttd_pendata')) {
                if ($item->ttd_pendata) {
                    Storage::disk('public')->delete('ttd/pendata/' . $item->ttd_pendata);
                }
                $path    = $request->file('ttd_pendata')->store('ttd/pendata', 'public');
                $ttdPath = basename($path);
            }

            // Update data utama
            $item->update(array_merge(
                collect($validated)->except([
                    'nama','nik','hdkrt','nuk','hdkk','kelamin',
                    'status_perkawinan','status_pekerjaan','pendidikan_terakhir',
                    'jenis_pekerjaan','sub_jenis_pekerjaan','pendapatan_per_bulan',
                    'ttd_pendata',
                ])->toArray(),
                $recap,
                ['ttd_pendata' => $ttdPath]
            ));

            // Hapus anggota lama, buat ulang
            $item->anggotaKeluarga()->delete();
            foreach ($validated['nama'] as $i => $nama) {
                AnggotaKeluarga::create([
                    'rumah_tangga_id'      => $item->id,
                    'nama'                 => $nama,
                    'nik'                  => $validated['nik'][$i],
                    'hdkrt'                => $validated['hdkrt'][$i],
                    'nuk'                  => $validated['nuk'][$i],
                    'hdkk'                 => $validated['hdkk'][$i],
                    'kelamin'              => $validated['kelamin'][$i],
                    'status_perkawinan'    => $validated['status_perkawinan'][$i],
                    'status_pekerjaan'     => $validated['status_pekerjaan'][$i],
                    'pendidikan_terakhir'  => $validated['pendidikan_terakhir'][$i],
                    'jenis_pekerjaan'      => $validated['jenis_pekerjaan'][$i],
                    'sub_jenis_pekerjaan'  => $validated['sub_jenis_pekerjaan'][$i],
                    'pendapatan_per_bulan' => $validated['pendapatan_per_bulan'][$i],
                ]);
            }

            // Jika sebelumnya ditolak, reset ke pending
            if ($item->status_validasi === 'rejected') {
                $item->update([
                    'status_validasi' => 'pending',
                    'admin_catatan'   => null,
                ]);
            }

            DB::commit();

            return redirect()->route('tenagakerja.show', $item->id)
                ->with('success', 'Data berhasil diperbarui dan dikirim ulang.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal update kuesioner: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }
}
