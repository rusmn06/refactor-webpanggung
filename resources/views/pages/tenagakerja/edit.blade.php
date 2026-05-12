@extends('layouts.main')
@section('title', 'Edit Pengajuan #' . $item->user_sequence_number)

@push('styles')
<style>
    .member-card { border-left: 4px solid #1a7a5e; }
    .member-card .card-header { background-color: #f8f9fc; }
    fieldset[disabled] .form-control,
    fieldset[disabled] select { background-color: #eaecf4; }
</style>
@endpush

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center">
        <h1 class="h3 mb-0 text-gray-800 mr-3">
            Edit Pengajuan #{{ $item->user_sequence_number ?? $item->id }}
        </h1>
        @php
            $badge = match($item->status_validasi) {
                'pending'   => 'warning',
                'validated' => 'success',
                'rejected'  => 'danger',
                default     => 'secondary',
            };
        @endphp
        <span class="badge badge-{{ $badge }} p-2">
            {{ $item->status_validasi_text }}
        </span>
    </div>
    <a href="{{ route('tenagakerja.show', $item->id) }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>
</div>

{{-- Alert jika ditolak --}}
@if($item->status_validasi === 'rejected' && $item->admin_catatan)
    <div class="alert alert-danger">
        <h6 class="font-weight-bold">
            <i class="fas fa-exclamation-triangle mr-2"></i>Pengajuan Ditolak
        </h6>
        <p class="mb-0">
            <strong>Catatan admin:</strong> {{ $item->admin_catatan }}
        </p>
        <small class="text-muted">Perbaiki data di bawah lalu klik "Simpan & Kirim Ulang".</small>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 pl-3">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<form action="{{ route('tenagakerja.update', $item->id) }}" method="POST"
      enctype="multipart/form-data" id="edit-form">
    @csrf
    @method('PUT')

    {{-- ===== INFORMASI LOKASI ===== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-map-marker-alt mr-2"></i>Informasi Lokasi
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach([
                    ['provinsi',   'Provinsi'],
                    ['kabupaten',  'Kabupaten'],
                    ['kecamatan',  'Kecamatan'],
                    ['desa',       'Desa'],
                ] as [$field, $label])
                <div class="col-md-3 mb-3">
                    <label>{{ $label }}</label>
                    <input type="text" name="{{ $field }}"
                        value="{{ old($field, $item->$field) }}"
                        class="form-control @error($field) is-invalid @enderror">
                    @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                @endforeach

                <div class="col-md-2 mb-3">
                    <label>RT</label>
                    <input type="number" name="rt" min="1"
                        value="{{ old('rt', $item->rt) }}"
                        class="form-control @error('rt') is-invalid @enderror">
                    @error('rt')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2 mb-3">
                    <label>RW</label>
                    <input type="number" name="rw" min="1"
                        value="{{ old('rw', $item->rw) }}"
                        class="form-control @error('rw') is-invalid @enderror">
                    @error('rw')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label>Tanggal Pembuatan</label>
                    <input type="date" name="tgl_pembuatan"
                        value="{{ old('tgl_pembuatan', $item->tgl_pembuatan->format('Y-m-d')) }}"
                        class="form-control @error('tgl_pembuatan') is-invalid @enderror">
                    @error('tgl_pembuatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label>Nama Pendata</label>
                    <input type="text" name="nama_pendata"
                        value="{{ old('nama_pendata', $item->nama_pendata) }}"
                        class="form-control" readonly>
                </div>
                <div class="col-md-4 mb-0">
                    <label>Nama Responden</label>
                    <input type="text" name="nama_responden"
                        value="{{ old('nama_responden', $item->nama_responden) }}"
                        class="form-control @error('nama_responden') is-invalid @enderror">
                    @error('nama_responden')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ANGGOTA KELUARGA ===== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-users mr-2"></i>Anggota Keluarga
            </h6>
            <button type="button" id="add-member" class="btn btn-success btn-sm">
                <i class="fas fa-plus mr-1"></i> Tambah
            </button>
        </div>
        <div class="card-body" id="members-container">
            @foreach($item->anggotaKeluarga as $i => $anggota)
                @include('pages.tenagakerja._member_row', [
                    'i'      => $i,
                    'anggota'=> $anggota,
                ])
            @endforeach
        </div>
    </div>

    {{-- ===== REKAPITULASI ===== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list-ol mr-2"></i>Pendapatan RT
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label>Pendapatan Rata-rata per Bulan</label>
                    <select name="jpr2rtp"
                        class="form-control @error('jpr2rtp') is-invalid @enderror">
                        <option value="">-- Pilih --</option>
                        @foreach([
                            '0' => 'Tidak Ada Pendapatan',
                            '1' => 'Di atas Rp 500.000',
                            '2' => 'Di atas Rp 1.000.000',
                            '3' => 'Di atas Rp 2.000.000',
                            '4' => 'Di atas Rp 4.000.000',
                        ] as $v => $l)
                        <option value="{{ $v }}"
                            {{ old('jpr2rtp', $item->jpr2rtp) == $v ? 'selected' : '' }}>
                            {{ $l }}
                        </option>
                        @endforeach
                    </select>
                    @error('jpr2rtp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    {{-- ===== VERIFIKASI & TTD ===== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-signature mr-2"></i>Verifikasi & Tanda Tangan
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Nama Pendata</label>
                    <input type="text" name="verif_nama_pendata"
                        value="{{ old('verif_nama_pendata', $item->verif_nama_pendata) }}"
                        class="form-control @error('verif_nama_pendata') is-invalid @enderror">
                    @error('verif_nama_pendata')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label>Tanggal Verifikasi</label>
                    <input type="date" name="verif_tgl_pembuatan"
                        value="{{ old('verif_tgl_pembuatan', optional($item->verif_tgl_pembuatan)->format('Y-m-d')) }}"
                        class="form-control @error('verif_tgl_pembuatan') is-invalid @enderror">
                    @error('verif_tgl_pembuatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label>Tanda Tangan
                        <small class="text-muted">(kosongkan jika tidak ingin mengganti)</small>
                    </label>

                    @if($item->ttd_pendata)
                        <div class="mb-2 p-2 border rounded text-center bg-light">
                            <img src="{{ asset('storage/ttd/pendata/'.$item->ttd_pendata) }}"
                                 alt="TTD saat ini" style="max-height:80px;">
                            <p class="small text-muted mb-0">TTD saat ini</p>
                        </div>
                    @endif

                    <input type="file" name="ttd_pendata" id="ttd-input"
                        accept="image/png,image/jpeg"
                        class="form-control-file @error('ttd_pendata') is-invalid @enderror">
                    @error('ttd_pendata')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                    <div id="ttd-preview" class="mt-2 d-none">
                        <img id="ttd-img" src="" style="max-height:80px; border:1px solid #ddd; padding:4px; border-radius:4px;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between mb-5">
        <a href="{{ route('tenagakerja.show', $item->id) }}" class="btn btn-secondary">
            <i class="fas fa-times mr-1"></i> Batal
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane mr-1"></i> Simpan & Kirim Ulang
        </button>
    </div>

</form>

{{-- Template anggota baru --}}
<template id="member-template">
    @include('pages.tenagakerja._member_row', ['i' => '__INDEX__', 'anggota' => null])
</template>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('members-container');

    function updateNumbers() {
        container.querySelectorAll('.member-card').forEach((card, i) => {
            card.querySelector('.member-title').textContent = `Anggota Keluarga #${i + 1}`;
            const nuk = card.querySelector('input[name="nuk[]"]');
            if (nuk) nuk.value = i + 1;
        });
    }

    document.getElementById('add-member').addEventListener('click', function () {
        const count  = container.querySelectorAll('.member-card').length;
        const tpl    = document.getElementById('member-template').innerHTML
                           .replace(/__INDEX__/g, count);
        const div    = document.createElement('div');
        div.innerHTML = tpl;
        container.appendChild(div.firstElementChild);
        updateNumbers();
    });

    container.addEventListener('click', function (e) {
        if (e.target.closest('.remove-btn')) {
            if (container.querySelectorAll('.member-card').length <= 1) {
                alert('Minimal 1 anggota.'); return;
            }
            e.target.closest('.member-card').remove();
            updateNumbers();
        }
    });

    container.addEventListener('input', function (e) {
        if (e.target.classList.contains('nik-input')) {
            e.target.value = e.target.value.replace(/\D/g, '').slice(0, 16);
        }
    });

    // Preview TTD
    document.getElementById('ttd-input').addEventListener('change', function () {
        const preview = document.getElementById('ttd-preview');
        const img     = document.getElementById('ttd-img');
        if (!this.files[0]) { preview.classList.add('d-none'); return; }
        const reader  = new FileReader();
        reader.onload = e => { img.src = e.target.result; preview.classList.remove('d-none'); };
        reader.readAsDataURL(this.files[0]);
    });
});
</script>
@endpush