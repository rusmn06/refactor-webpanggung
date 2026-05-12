@extends('layouts.main')
@section('title', 'Detail Pengajuan')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center">
        <h1 class="h3 mb-0 text-gray-800 mr-3">
            Detail Pengajuan #{{ $item->user_sequence_number ?? $item->id }}
        </h1>
        @php
        $badgeClass = match($item->status_validasi) {
        'pending' => 'badge-warning text-dark',
        'validated' => 'badge-success',
        'rejected' => 'badge-danger',
        default => 'badge-secondary',
        };
        @endphp
        <span class="badge {{ $badgeClass }} p-2" style="font-size:0.9rem;">
            {{ $item->status_validasi_text }}
        </span>
    </div>
    <a href="{{ route('tenagakerja.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>
</div>

{{-- Alert jika ditolak --}}
@if($item->status_validasi === 'rejected')
<div class="alert alert-danger">
    <h6 class="font-weight-bold"><i class="fas fa-times-circle mr-2"></i>Pengajuan Ditolak</h6>
    <p class="mb-0">
        Silakan perbaiki data dan ajukan kembali.
        @if($item->admin_catatan)
        <br><strong>Catatan admin:</strong> {{ $item->admin_catatan }}
        @endif
    </p>
</div>
@endif

<div class="row">
    {{-- Info Pengajuan --}}
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Pengajuan</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <th>Nama Responden</th>
                        <td>{{ $item->nama_responden }}</td>
                    </tr>
                    <tr>
                        <th>Nama Pendata</th>
                        <td>{{ $item->nama_pendata }}</td>
                    </tr>
                    <tr>
                        <th>Tgl. Pembuatan</th>
                        <td>{{ $item->tgl_pembuatan->isoFormat('D MMMM YYYY') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <hr class="my-1">
                        </td>
                    </tr>
                    <tr>
                        <th>Provinsi</th>
                        <td>{{ $item->provinsi }}</td>
                    </tr>
                    <tr>
                        <th>Kabupaten</th>
                        <td>{{ $item->kabupaten }}</td>
                    </tr>
                    <tr>
                        <th>Kecamatan</th>
                        <td>{{ $item->kecamatan }}</td>
                    </tr>
                    <tr>
                        <th>Desa</th>
                        <td>{{ $item->desa }}</td>
                    </tr>
                    <tr>
                        <th>RT / RW</th>
                        <td>{{ str_pad($item->rt,3,'0',STR_PAD_LEFT) }} /
                            {{ str_pad($item->rw,3,'0',STR_PAD_LEFT) }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Rekapitulasi --}}
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm mb-3">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Rekapitulasi</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <th>Total Anggota</th>
                        <td class="text-right font-weight-bold">{{ $item->jart }} orang</td>
                    </tr>
                    <tr>
                        <th>Bekerja</th>
                        <td class="text-right font-weight-bold">{{ $item->jart_ab }} orang</td>
                    </tr>
                    <tr>
                        <th>Tidak/Belum Bekerja</th>
                        <td class="text-right font-weight-bold">{{ $item->jart_tb }} orang</td>
                    </tr>
                    <tr>
                        <th>Masih Sekolah</th>
                        <td class="text-right font-weight-bold">{{ $item->jart_ms }} orang</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Kartu Aksi --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <p class="text-muted small mb-3">
                    Unduh data pengajuan ini dalam format Excel,
                    atau edit jika belum disetujui.
                </p>

                {{-- Tombol Export --}}
                <a href="{{ route('tenagakerja.export', $item->id) }}"
                    class="btn btn-success btn-block mb-2">
                    <i class="fas fa-file-excel mr-2"></i> Download Excel
                </a>

                {{-- Tombol Edit --}}
                @if($item->status_validasi !== 'validated')
                <a href="{{ route('tenagakerja.edit', $item->id) }}"
                    class="btn btn-warning btn-block">
                    <i class="fas fa-edit mr-2"></i> Edit Data
                </a>
                @else
                <button class="btn btn-secondary btn-block" disabled>
                    <i class="fas fa-lock mr-2"></i> Data Sudah Final
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Tabel Anggota Keluarga --}}
<div class="card shadow-sm mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Anggota Keluarga</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>Kelamin</th>
                        <th>Hub. KRT</th>
                        <th>Status Kawin</th>
                        <th>Status Kerja</th>
                        <th>Pendidikan</th>
                        <th>Pendapatan/bln</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($item->anggotaKeluarga as $anggota)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $anggota->nama }}</td>
                        <td>
                            <code style="font-size:0.8rem;">
                                {{ $anggota->formatted_nik }}
                            </code>
                        </td>
                        <td>{{ $anggota->kelamin_text }}</td>
                        <td>{{ $anggota->hdkrt_text }}</td>
                        <td>{{ $anggota->status_perkawinan_text }}</td>
                        <td>{{ $anggota->status_pekerjaan_text }}</td>
                        <td>{{ $anggota->pendidikan_terakhir_text }}</td>
                        <td>{{ $anggota->pendapatan_per_bulan_text }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">
                            Tidak ada data anggota.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection