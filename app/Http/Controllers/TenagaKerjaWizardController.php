<?php

namespace App\Http\Controllers;

use App\Models\RumahTangga;
use App\Models\AnggotaKeluarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TenagaKerjaWizardController extends Controller
{
    // =============================================
    // STEP 1 — Informasi Lokasi & Pendata
    // =============================================

    public function showStep1()
    {
        $data = session('tk.step1', []);
        return view('pages.tenagakerja.tkw.step1', compact('data'));
    }

    public function postStep1(Request $request)
    {
        $data = $request->validate([
            'provinsi'       => 'required|string|max:50',
            'kabupaten'      => 'required|string|max:50',
            'kecamatan'      => 'required|string|max:50',
            'desa'           => 'required|string|max:50',
            'rt'             => 'required|numeric|min:1|max:999',
            'rw'             => 'required|numeric|min:1|max:999',
            'tgl_pembuatan'  => 'required|date',
            'nama_pendata'   => 'required|string|max:100',
            'nama_responden' => 'required|string|max:100',
        ]);

        // Simpan ke session, lanjut ke step berikutnya
        session(['tk.step1' => $data]);

        return redirect()->route('tkw.step2');
    }

    // =============================================
    // STEP 2 — Identitas Anggota Keluarga
    // =============================================

    public function showStep2()
    {
        // Cek step sebelumnya sudah diisi
        if (!session('tk.step1')) {
            return redirect()->route('tkw.step1')
                ->with('error', 'Silakan isi informasi lokasi terlebih dahulu.');
        }

        $data = session('tk.step2', []);
        return view('pages.tenagakerja.tkw.step2', compact('data'));
    }

    public function postStep2(Request $request)
    {
        $validatedData = $request->validate([
            'nama'                   => 'required|array|min:1',
            'nama.*'                 => 'required|string|max:100',
            'nik'                    => 'required|array',
            'nik.*'                  => 'required|digits:16|distinct',
            'hdkrt'                  => 'required|array',
            'hdkrt.*'                => 'required|in:1,2,3,4,5,6,7,8',
            'nuk'                    => 'required|array',
            'nuk.*'                  => 'required|integer|min:1|max:99',
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
        ], [
            'nama.*.required'   => 'Nama anggota ke-:position wajib diisi.',
            'nik.*.digits'      => 'NIK anggota ke-:position harus 16 digit.',
            'nik.*.distinct'    => 'Ada NIK yang duplikat, pastikan semua NIK unik.',
        ]);

        session(['tk.step2' => $validatedData]);

        return redirect()->route('tkw.step3');
    }

    // =============================================
    // STEP 3 — Rekapitulasi
    // =============================================

    public function showStep3()
    {
        if (!session('tk.step2')) {
            return redirect()->route('tkw.step2')
                ->with('error', 'Silakan isi data anggota terlebih dahulu.');
        }

        // Hitung otomatis dari data step 2
        $step2 = session('tk.step2');
        $autoRecap = [
            'jart'    => count($step2['nama']),
            'jart_ab' => collect($step2['status_pekerjaan'])->filter(fn($s) => $s == '1')->count(),
            'jart_ms' => collect($step2['status_pekerjaan'])->filter(fn($s) => $s == '3')->count(),
            'jart_tb' => collect($step2['status_pekerjaan'])->filter(fn($s) => in_array($s, ['2','4','5']))->count(),
        ];

        $data = session('tk.step3', $autoRecap);

        return view('pages.tenagakerja.tkw.step3', compact('data'));
    }

    public function postStep3(Request $request)
    {
        $data = $request->validate([
            'jart'    => 'required|integer|min:0|max:99',
            'jart_ab' => 'required|integer|min:0|max:99',
            'jart_tb' => 'required|integer|min:0|max:99',
            'jart_ms' => 'required|integer|min:0|max:99',
            'jpr2rtp' => 'required|in:0,1,2,3,4',
        ]);

        session(['tk.step3' => $data]);

        return redirect()->route('tkw.step4');
    }

    // =============================================
    // STEP 4 — Verifikasi & Simpan
    // =============================================

    public function showStep4()
    {
        if (!session('tk.step3')) {
            return redirect()->route('tkw.step3')
                ->with('error', 'Silakan isi rekapitulasi terlebih dahulu.');
        }

        $data = session('tk.step4', []);

        // Isi otomatis nama pendata dari step1
        $data['verif_nama_pendata'] = old(
            'verif_nama_pendata',
            $data['verif_nama_pendata'] ?? session('tk.step1.nama_pendata') ?? Auth::user()->name
        );
        $data['verif_tgl_pembuatan'] = old(
            'verif_tgl_pembuatan',
            $data['verif_tgl_pembuatan'] ?? now()->toDateString()
        );

        return view('pages.tenagakerja.tkw.step4', compact('data'));
    }

    public function postStep4(Request $request)
    {
        $step4Data = $request->validate([
            'verif_tgl_pembuatan' => 'required|date',
            'verif_nama_pendata'  => 'required|string|max:100',
            'ttd_pendata'         => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'ttd_pendata.required' => 'Tanda tangan wajib diunggah.',
            'ttd_pendata.image'    => 'File harus berupa gambar.',
            'ttd_pendata.max'      => 'Ukuran file maksimal 2MB.',
        ]);

        // Ambil semua data dari session
        $step1 = session('tk.step1');
        $step2 = session('tk.step2');
        $step3 = session('tk.step3');

        if (!$step1 || !$step2 || !$step3) {
            return redirect()->route('tkw.step1')
                ->with('error', 'Sesi berakhir, silakan mulai dari awal.');
        }

        // Upload tanda tangan
        $ttdPath = $request->file('ttd_pendata')
            ->store('ttd/pendata', 'public');
        $ttdFilename = basename($ttdPath);

        DB::beginTransaction();
        try {
            // Nomor urut pengajuan user
            $urutan = RumahTangga::where('user_id', Auth::id())->count() + 1;

            // Simpan RumahTangga
            $rumahTangga = RumahTangga::create([
                'user_id'              => Auth::id(),
                'user_sequence_number' => $urutan,

                // Step 1
                'provinsi'        => $step1['provinsi'],
                'kabupaten'       => $step1['kabupaten'],
                'kecamatan'       => $step1['kecamatan'],
                'desa'            => $step1['desa'],
                'rt'              => $step1['rt'],
                'rw'              => $step1['rw'],
                'tgl_pembuatan'   => $step1['tgl_pembuatan'],
                'nama_pendata'    => $step1['nama_pendata'],
                'nama_responden'  => $step1['nama_responden'],

                // Step 3
                'jart'    => $step3['jart'],
                'jart_ab' => $step3['jart_ab'],
                'jart_tb' => $step3['jart_tb'],
                'jart_ms' => $step3['jart_ms'],
                'jpr2rtp' => $step3['jpr2rtp'],

                // Step 4
                'verif_tgl_pembuatan' => $step4Data['verif_tgl_pembuatan'],
                'verif_nama_pendata'  => $step4Data['verif_nama_pendata'],
                'ttd_pendata'         => $ttdFilename,
                'status_validasi'     => 'pending',
            ]);

            // Simpan setiap AnggotaKeluarga
            $jumlahAnggota = count($step2['nama']);
            for ($i = 0; $i < $jumlahAnggota; $i++) {
                AnggotaKeluarga::create([
                    'rumah_tangga_id'      => $rumahTangga->id,
                    'nama'                 => $step2['nama'][$i],
                    'nik'                  => $step2['nik'][$i],
                    'hdkrt'                => $step2['hdkrt'][$i],
                    'nuk'                  => $step2['nuk'][$i],
                    'hdkk'                 => $step2['hdkk'][$i],
                    'kelamin'              => $step2['kelamin'][$i],
                    'status_perkawinan'    => $step2['status_perkawinan'][$i],
                    'status_pekerjaan'     => $step2['status_pekerjaan'][$i],
                    'pendidikan_terakhir'  => $step2['pendidikan_terakhir'][$i],
                    'jenis_pekerjaan'      => $step2['jenis_pekerjaan'][$i],
                    'sub_jenis_pekerjaan'  => $step2['sub_jenis_pekerjaan'][$i],
                    'pendapatan_per_bulan' => $step2['pendapatan_per_bulan'][$i],
                ]);
            }

            DB::commit();

            // Bersihkan session wizard
            session()->forget(['tk.step1', 'tk.step2', 'tk.step3', 'tk.step4']);

            return redirect()->route('tenagakerja.show', $rumahTangga->id)
                ->with('success_modal', true)
                ->with('success_title', 'Pengajuan Terkirim!')
                ->with('success_body', "Pengajuan ke-{$urutan} berhasil dikirim. Silakan tunggu verifikasi admin.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal simpan wizard: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }
}