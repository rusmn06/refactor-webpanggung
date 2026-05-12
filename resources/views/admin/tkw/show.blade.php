@extends('layouts.main')
@section('title', 'Verifikasi: ' . $item->nama_responden)

@push('styles')
<style>
    .signature-box {
        border: 2px dashed #d1d3e2;
        border-radius: .35rem;
        padding: 1rem;
        text-align: center;
        min-height: 120px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #f8f9fc;
    }

    .signature-box img {
        max-height: 90px;
        max-width: 100%;
        object-fit: contain;
    }

    .info-row {
        border-bottom: 1px solid #eaecf4;
        padding: .5rem 0;
    }

    .info-row:last-child {
        border-bottom: none;
    }
</style>
@endpush

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center">
        <h1 class="h3 mb-0 text-gray-800 mr-3">Verifikasi Pengajuan</h1>
        @php
        $badge = match($item->status_validasi) {
        'pending' => 'warning',
        'validated' => 'success',
        'rejected' => 'danger',
        default => 'secondary',
        };
        @endphp
        <span class="badge badge-{{ $badge }} p-2" style="font-size:.9rem;">
            {{ $item->status_validasi_text }}
        </span>
    </div>

    {{-- Tombol kanan atas --}}
    <div class="d-flex" style="gap: .5rem;">
        <a href="{{ route('admin.tkw.export', $item->id) }}"
            class="btn btn-success btn-sm shadow-sm">
            <i class="fas fa-file-excel mr-1"></i> Export Excel
        </a>
        <a href="{{ route('admin.tkw.showrt', $item->rt) }}"
            class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<div class="row">
    {{-- Kolom kiri: Info + Rekap --}}
    <div class="col-lg-7">

        {{-- Info Pengajuan --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Pengajuan</h6>
            </div>
            <div class="card-body py-2">
                @foreach([
                ['Nama Responden', $item->nama_responden],
                ['Nama Pendata', $item->nama_pendata],
                ['Tgl. Pengajuan', $item->tgl_pembuatan->isoFormat('D MMMM YYYY')],
                ] as [$label, $value])
                <div class="row info-row">
                    <div class="col-5 text-muted">{{ $label }}</div>
                    <div class="col-7 font-weight-bold">{{ $value }}</div>
                </div>
                @endforeach
                <hr class="my-2">
                @foreach([
                ['Provinsi', $item->provinsi],
                ['Kabupaten', $item->kabupaten],
                ['Kecamatan', $item->kecamatan],
                ['Desa', $item->desa],
                ['RT / RW', str_pad($item->rt,3,'0',STR_PAD_LEFT).' / '.str_pad($item->rw,3,'0',STR_PAD_LEFT)],
                ] as [$label, $value])
                <div class="row info-row">
                    <div class="col-5 text-muted">{{ $label }}</div>
                    <div class="col-7">{{ $value }}</div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Rekapitulasi --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Rekapitulasi</h6>
            </div>
            <div class="card-body py-2">
                @foreach([
                ['Total Anggota', $item->jart],
                ['Bekerja', $item->jart_ab],
                ['Tidak/Belum Bekerja', $item->jart_tb],
                ['Masih Sekolah', $item->jart_ms],
                ] as [$label, $value])
                <div class="row info-row">
                    <div class="col-8 text-muted">{{ $label }}</div>
                    <div class="col-4 text-right font-weight-bold">{{ $value }} orang</div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Tabel Anggota --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Anggota Keluarga</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>Kelamin</th>
                                <th>Hub. KRT</th>
                                <th>Status Kerja</th>
                                <th>Pendidikan</th>
                                <th>Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($item->anggotaKeluarga as $anggota)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $anggota->nama }}</td>
                                <td><code style="font-size:.8rem;">{{ $anggota->formatted_nik }}</code></td>
                                <td>{{ $anggota->kelamin_text }}</td>
                                <td>{{ $anggota->hdkrt_text }}</td>
                                <td>{{ $anggota->status_pekerjaan_text }}</td>
                                <td>{{ $anggota->pendidikan_terakhir_text }}</td>
                                <td>{{ $anggota->pendapatan_per_bulan_text }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Tidak ada anggota.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Kolom kanan: Form Verifikasi --}}
    <div class="col-lg-5">
        <div class="card shadow-sm sticky-top" style="top: 20px;">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tindakan Verifikasi</h6>
            </div>
            <div class="card-body">

                @if($item->status_validasi !== 'pending')
                {{-- Sudah diverifikasi, tampilkan hasil --}}
                <div class="alert alert-{{ $badge }} mb-3">
                    <strong>{{ $item->status_validasi_text }}</strong>
                    @if($item->admin_catatan)
                    <p class="mb-0 mt-1 small">Catatan: {{ $item->admin_catatan }}</p>
                    @endif
                </div>
                <div class="mb-3 text-center text-muted small">
                    Diverifikasi pada:
                    {{ optional($item->admin_tgl_validasi)->isoFormat('D MMMM YYYY') ?? '-' }}
                    <br>Oleh: {{ $item->admin_nama_kepaladusun ?? '-' }}
                </div>
                {{-- Tanda Tangan Admin --}}
                @if($item->admin_ttd_pendata)
                <div class="mb-3">
                    <p class="small font-weight-bold mb-1">TTD Verifikator:</p>
                    <div class="signature-box">
                        <img src="{{ asset('storage/ttd/admin/' . $item->admin_ttd_pendata) }}"
                            alt="TTD Admin">
                        <small class="mt-2 text-muted">{{ $item->admin_nama_kepaladusun }}</small>
                    </div>
                </div>
                @endif
                <hr>
                @endif

                {{-- Form Verifikasi --}}
                <form action="{{ route('admin.tkw.process', $item->id) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Pilih Status --}}
                    <div class="mb-3">
                        <label class="font-weight-bold small">Pilih Status</label>
                        <div class="d-flex gap-3 mt-1">
                            <div class="custom-control custom-radio mr-3">
                                <input type="radio" id="st_validated" name="status"
                                    value="validated" class="custom-control-input"
                                    {{ old('status', $item->status_validasi) === 'validated' ? 'checked' : '' }}>
                                <label class="custom-control-label text-success font-weight-bold"
                                    for="st_validated">
                                    <i class="fas fa-check-circle mr-1"></i> Setujui
                                </label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="st_rejected" name="status"
                                    value="rejected" class="custom-control-input"
                                    {{ old('status') === 'rejected' ? 'checked' : '' }}>
                                <label class="custom-control-label text-danger font-weight-bold"
                                    for="st_rejected">
                                    <i class="fas fa-times-circle mr-1"></i> Tolak
                                </label>
                            </div>
                        </div>
                        @error('status')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Panel Setujui --}}
                    <div id="panel-validated" style="display:none;">
                        <div class="form-group">
                            <label class="small">Tanggal Verifikasi <span class="text-danger">*</span></label>
                            <input type="date" name="admin_tgl_validasi"
                                value="{{ old('admin_tgl_validasi', now()->toDateString()) }}"
                                class="form-control form-control-sm @error('admin_tgl_validasi') is-invalid @enderror">
                            @error('admin_tgl_validasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="small">Nama Verifikator <span class="text-danger">*</span></label>
                            <input type="text" name="admin_nama_kepaladusun"
                                value="{{ old('admin_nama_kepaladusun', $item->admin_nama_kepaladusun ?? auth()->user()->name) }}"
                                class="form-control form-control-sm @error('admin_nama_kepaladusun') is-invalid @enderror">
                            @error('admin_nama_kepaladusun')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="small">TTD Verifikator <span class="text-danger">*</span></label>
                            <input type="file" name="admin_ttd_pendata" accept="image/*"
                                class="form-control-file @error('admin_ttd_pendata') is-invalid @enderror">
                            <small class="text-muted">JPG/PNG, maks. 2MB</small>
                            @error('admin_ttd_pendata')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            {{-- Preview TTD lama --}}
                            @if($item->admin_ttd_pendata)
                            <div class="mt-2 signature-box" style="min-height:80px;">
                                <img src="{{ asset('storage/ttd/admin/'.$item->admin_ttd_pendata) }}"
                                    alt="TTD saat ini">
                                <small class="text-muted mt-1">TTD saat ini (kosongkan untuk tidak mengubah)</small>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Panel Tolak --}}
                    <div id="panel-rejected" style="display:none;">
                        <div class="form-group">
                            <label class="small">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea name="admin_catatan" rows="3"
                                class="form-control form-control-sm @error('admin_catatan') is-invalid @enderror"
                                placeholder="Tuliskan alasan penolakan...">{{ old('admin_catatan', $item->admin_catatan) }}</textarea>
                            @error('admin_catatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-1">
                                @foreach(['Kesalahan input data.', 'Data tidak lengkap.', 'NIK tidak valid.'] as $tag)
                                <span class="badge badge-light border mr-1 mb-1"
                                    style="cursor:pointer;"
                                    onclick="appendCatatan('{{ $tag }}')">
                                    {{ $tag }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- TTD Pendata (selalu tampil sebagai referensi) --}}
                    <div class="mb-3">
                        <p class="small font-weight-bold mb-1">TTD Pendata:</p>
                        <div class="signature-box" style="min-height:80px;">
                            @if($item->ttd_pendata)
                            <img src="{{ asset('storage/ttd/pendata/'.$item->ttd_pendata) }}"
                                alt="TTD Pendata">
                            <small class="mt-1 text-muted">{{ $item->verif_nama_pendata }}</small>
                            @else
                            <small class="text-muted">Belum ada TTD pendata</small>
                            @endif
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-save mr-1"></i> Simpan Verifikasi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const radioValidated = document.getElementById('st_validated');
    const radioRejected = document.getElementById('st_rejected');
    const panelVal = document.getElementById('panel-validated');
    const panelRej = document.getElementById('panel-rejected');

    function togglePanels() {
        panelVal.style.display = radioValidated.checked ? 'block' : 'none';
        panelRej.style.display = radioRejected.checked ? 'block' : 'none';
    }

    radioValidated.addEventListener('change', togglePanels);
    radioRejected.addEventListener('change', togglePanels);

    // Jalankan saat load jika sudah ada pilihan
    togglePanels();

    function appendCatatan(text) {
        const ta = document.querySelector('textarea[name="admin_catatan"]');
        ta.value = ta.value ? ta.value + ' ' + text : text;
    }
</script>
@endpush