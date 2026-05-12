@extends('layouts.main')
@section('title', 'Kuesioner - Langkah 2 dari 4')

@push('styles')
<style>
    .member-card { border-left: 4px solid #4e73df; }
    .member-card .card-header { background-color: #f8f9fc; }
</style>
@endpush

@section('content')

<div class="mb-4">
    <div class="d-flex justify-content-between mb-1">
        <small class="font-weight-bold">Langkah 2 dari 4 — Identitas Anggota Keluarga</small>
        <small>50%</small>
    </div>
    <div class="progress" style="height: 8px;">
        <div class="progress-bar bg-primary" style="width: 50%"></div>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <strong>Terdapat kesalahan:</strong>
        <ul class="mb-0 mt-1 pl-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('tkw.step2') }}" method="POST" id="step2-form">
    @csrf

    <div id="members-container">
        @php
            $members = old('nama', $data['nama'] ?? ['']);
            if(empty($members)) $members = [''];
        @endphp

        @foreach($members as $i => $nama)
        <div class="card shadow-sm mb-3 member-card">
            <div class="card-header d-flex justify-content-between align-items-center py-2">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user mr-2"></i>Anggota Keluarga #{{ $i + 1 }}
                </h6>
                @if($i > 0)
                    <button type="button" class="btn btn-danger btn-sm remove-btn">
                        <i class="fas fa-trash"></i>
                    </button>
                @endif
            </div>
            <div class="card-body">

                {{-- Nama & NIK --}}
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama[]"
                            value="{{ old('nama.'.$i, $data['nama'][$i] ?? '') }}"
                            class="form-control @error('nama.'.$i) is-invalid @enderror">
                        @error('nama.'.$i)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>NIK (16 digit) <span class="text-danger">*</span></label>
                        <input type="text" name="nik[]" maxlength="16"
                            value="{{ old('nik.'.$i, $data['nik'][$i] ?? '') }}"
                            class="form-control nik-input @error('nik.'.$i) is-invalid @enderror"
                            placeholder="________________">
                        @error('nik.'.$i)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Kelamin, Hub.KRT, Hub.KK, NUK --}}
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>Jenis Kelamin <span class="text-danger">*</span></label>
                        <select name="kelamin[]" class="form-control @error('kelamin.'.$i) is-invalid @enderror">
                            <option value="">-- Pilih --</option>
                            @foreach(['1' => 'Laki-laki', '2' => 'Perempuan'] as $v => $l)
                                <option value="{{ $v }}"
                                    {{ old('kelamin.'.$i, $data['kelamin'][$i] ?? '') == $v ? 'selected' : '' }}>
                                    {{ $l }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelamin.'.$i)<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label>Hub. KRT <span class="text-danger">*</span></label>
                        <select name="hdkrt[]" class="form-control @error('hdkrt.'.$i) is-invalid @enderror">
                            <option value="">-- Pilih --</option>
                            @foreach(['1'=>'Kepala Keluarga','2'=>'Istri/Suami','3'=>'Anak','4'=>'Menantu','5'=>'Cucu','6'=>'Orang Tua/Mertua','7'=>'Pembantu','8'=>'Lainnya'] as $v => $l)
                                <option value="{{ $v }}"
                                    {{ old('hdkrt.'.$i, $data['hdkrt'][$i] ?? '') == $v ? 'selected' : '' }}>
                                    {{ $l }}
                                </option>
                            @endforeach
                        </select>
                        @error('hdkrt.'.$i)<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label>Hub. KK <span class="text-danger">*</span></label>
                        <select name="hdkk[]" class="form-control @error('hdkk.'.$i) is-invalid @enderror">
                            <option value="">-- Pilih --</option>
                            @foreach(['1'=>'Kepala Keluarga','2'=>'Istri/Suami','3'=>'Anak','4'=>'Menantu','5'=>'Cucu','6'=>'Orang Tua/Mertua','7'=>'Pembantu','8'=>'Lainnya'] as $v => $l)
                                <option value="{{ $v }}"
                                    {{ old('hdkk.'.$i, $data['hdkk'][$i] ?? '') == $v ? 'selected' : '' }}>
                                    {{ $l }}
                                </option>
                            @endforeach
                        </select>
                        @error('hdkk.'.$i)<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label>No. Urut</label>
                        <input type="number" name="nuk[]" min="1" max="99"
                            value="{{ old('nuk.'.$i, $data['nuk'][$i] ?? ($i + 1)) }}"
                            class="form-control" readonly>
                    </div>
                </div>

                <hr class="my-2">

                {{-- Status, Pendidikan, Pekerjaan --}}
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Status Perkawinan <span class="text-danger">*</span></label>
                        <select name="status_perkawinan[]" class="form-control">
                            <option value="">-- Pilih --</option>
                            @foreach(['1'=>'Belum Kawin','2'=>'Kawin','3'=>'Cerai Hidup','4'=>'Cerai Mati'] as $v => $l)
                                <option value="{{ $v }}"
                                    {{ old('status_perkawinan.'.$i, $data['status_perkawinan'][$i] ?? '') == $v ? 'selected' : '' }}>
                                    {{ $l }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Pendidikan Terakhir <span class="text-danger">*</span></label>
                        <select name="pendidikan_terakhir[]" class="form-control">
                            <option value="">-- Pilih --</option>
                            @foreach(['1'=>'Tidak/Belum Sekolah','2'=>'Tamat SD','3'=>'Tamat SMP','4'=>'Tamat SMA','5'=>'Tamat PT','6'=>'Tidak Pernah Sekolah'] as $v => $l)
                                <option value="{{ $v }}"
                                    {{ old('pendidikan_terakhir.'.$i, $data['pendidikan_terakhir'][$i] ?? '') == $v ? 'selected' : '' }}>
                                    {{ $l }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Status Pekerjaan <span class="text-danger">*</span></label>
                        <select name="status_pekerjaan[]" class="form-control">
                            <option value="">-- Pilih --</option>
                            @foreach(['1'=>'Bekerja','2'=>'Ibu Rumah Tangga','3'=>'Bersekolah','4'=>'Tidak/Belum Bekerja','5'=>'Lainnya'] as $v => $l)
                                <option value="{{ $v }}"
                                    {{ old('status_pekerjaan.'.$i, $data['status_pekerjaan'][$i] ?? '') == $v ? 'selected' : '' }}>
                                    {{ $l }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Jenis Pekerjaan <span class="text-danger">*</span></label>
                        <select name="jenis_pekerjaan[]" class="form-control">
                            <option value="">-- Pilih --</option>
                            @foreach(['1'=>'PNS/TNI/POLRI','2'=>'Karyawan/Honorer','3'=>'Wiraswasta','4'=>'Lainnya'] as $v => $l)
                                <option value="{{ $v }}"
                                    {{ old('jenis_pekerjaan.'.$i, $data['jenis_pekerjaan'][$i] ?? '') == $v ? 'selected' : '' }}>
                                    {{ $l }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Sub-Jenis Pekerjaan <span class="text-danger">*</span></label>
                        <select name="sub_jenis_pekerjaan[]" class="form-control">
                            <option value="">-- Pilih --</option>
                            @foreach(['1'=>'Aparatur Pemerintah','2'=>'Tenaga Ahli/Profesional','3'=>'Tenaga Harian','4'=>'Pengusaha','5'=>'Lainnya'] as $v => $l)
                                <option value="{{ $v }}"
                                    {{ old('sub_jenis_pekerjaan.'.$i, $data['sub_jenis_pekerjaan'][$i] ?? '') == $v ? 'selected' : '' }}>
                                    {{ $l }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Pendapatan per Bulan <span class="text-danger">*</span></label>
                        <select name="pendapatan_per_bulan[]" class="form-control">
                            <option value="">-- Pilih --</option>
                            @foreach(['1'=>'> Rp 500.000','2'=>'> Rp 1.000.000','3'=>'> Rp 2.000.000','4'=>'> Rp 4.000.000','5'=>'Tidak Berpenghasilan'] as $v => $l)
                                <option value="{{ $v }}"
                                    {{ old('pendapatan_per_bulan.'.$i, $data['pendapatan_per_bulan'][$i] ?? '') == $v ? 'selected' : '' }}>
                                    {{ $l }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
        </div>
        @endforeach
    </div>

    {{-- Tombol Tambah Anggota --}}
    <div class="mb-4">
        <button type="button" id="add-member" class="btn btn-success">
            <i class="fas fa-plus mr-1"></i> Tambah Anggota
        </button>
        <small class="text-muted ml-2">Klik untuk menambah anggota keluarga</small>
    </div>

    <hr>
    <div class="d-flex justify-content-between">
        <a href="{{ route('tkw.step1') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
        <button type="submit" class="btn btn-primary">
            Selanjutnya <i class="fas fa-arrow-right ml-1"></i>
        </button>
    </div>
</form>

{{-- Template tersembunyi untuk anggota baru --}}
<template id="member-template">
    <div class="card shadow-sm mb-3 member-card">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-user mr-2"></i>Anggota Keluarga #__NUM__
            </h6>
            <button type="button" class="btn btn-danger btn-sm remove-btn">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama[]" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label>NIK (16 digit) <span class="text-danger">*</span></label>
                    <input type="text" name="nik[]" maxlength="16" class="form-control nik-input">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Jenis Kelamin</label>
                    <select name="kelamin[]" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="1">Laki-laki</option>
                        <option value="2">Perempuan</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Hub. KRT</label>
                    <select name="hdkrt[]" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="1">Kepala Keluarga</option><option value="2">Istri/Suami</option>
                        <option value="3">Anak</option><option value="4">Menantu</option>
                        <option value="5">Cucu</option><option value="6">Orang Tua/Mertua</option>
                        <option value="7">Pembantu</option><option value="8">Lainnya</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Hub. KK</label>
                    <select name="hdkk[]" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="1">Kepala Keluarga</option><option value="2">Istri/Suami</option>
                        <option value="3">Anak</option><option value="4">Menantu</option>
                        <option value="5">Cucu</option><option value="6">Orang Tua/Mertua</option>
                        <option value="7">Pembantu</option><option value="8">Lainnya</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>No. Urut</label>
                    <input type="number" name="nuk[]" class="form-control nuk-input" readonly>
                </div>
            </div>
            <hr class="my-2">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Status Perkawinan</label>
                    <select name="status_perkawinan[]" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="1">Belum Kawin</option><option value="2">Kawin</option>
                        <option value="3">Cerai Hidup</option><option value="4">Cerai Mati</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label>Pendidikan Terakhir</label>
                    <select name="pendidikan_terakhir[]" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="1">Tidak/Belum Sekolah</option><option value="2">Tamat SD</option>
                        <option value="3">Tamat SMP</option><option value="4">Tamat SMA</option>
                        <option value="5">Tamat PT</option><option value="6">Tidak Pernah Sekolah</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label>Status Pekerjaan</label>
                    <select name="status_pekerjaan[]" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="1">Bekerja</option><option value="2">Ibu Rumah Tangga</option>
                        <option value="3">Bersekolah</option><option value="4">Tidak/Belum Bekerja</option>
                        <option value="5">Lainnya</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Jenis Pekerjaan</label>
                    <select name="jenis_pekerjaan[]" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="1">PNS/TNI/POLRI</option><option value="2">Karyawan/Honorer</option>
                        <option value="3">Wiraswasta</option><option value="4">Lainnya</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label>Sub-Jenis Pekerjaan</label>
                    <select name="sub_jenis_pekerjaan[]" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="1">Aparatur Pemerintah</option><option value="2">Tenaga Ahli/Profesional</option>
                        <option value="3">Tenaga Harian</option><option value="4">Pengusaha</option>
                        <option value="5">Lainnya</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label>Pendapatan per Bulan</label>
                    <select name="pendapatan_per_bulan[]" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="1">> Rp 500.000</option><option value="2">> Rp 1.000.000</option>
                        <option value="3">> Rp 2.000.000</option><option value="4">> Rp 4.000.000</option>
                        <option value="5">Tidak Berpenghasilan</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</template>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('members-container');

    function updateNumbers() {
        container.querySelectorAll('.member-card').forEach((card, i) => {
            card.querySelector('h6').innerHTML =
                `<i class="fas fa-user mr-2"></i>Anggota Keluarga #${i + 1}`;
            const nukInput = card.querySelector('.nuk-input, input[name="nuk[]"]');
            if (nukInput) nukInput.value = i + 1;
        });
    }

    // Tambah anggota baru dari template
    document.getElementById('add-member').addEventListener('click', function () {
        const count = container.querySelectorAll('.member-card').length;
        const template = document.getElementById('member-template');
        const clone = template.content.cloneNode(true);

        // Set nomor urut
        const nukInput = clone.querySelector('.nuk-input');
        if (nukInput) nukInput.value = count + 1;

        container.appendChild(clone);
        updateNumbers();
    });

    // Hapus anggota (event delegation)
    container.addEventListener('click', function (e) {
        if (e.target.closest('.remove-btn')) {
            const card = e.target.closest('.member-card');
            const total = container.querySelectorAll('.member-card').length;
            if (total <= 1) {
                alert('Minimal harus ada 1 anggota keluarga.');
                return;
            }
            card.remove();
            updateNumbers();
        }
    });

    // Batasi NIK hanya angka
    container.addEventListener('input', function (e) {
        if (e.target.classList.contains('nik-input')) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '').slice(0, 16);
        }
    });
});
</script>
@endpush