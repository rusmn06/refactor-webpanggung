@extends('layouts.main')
@section('title', 'Kuesioner - Langkah 3 dari 4')

@section('content')

<div class="mb-4">
    <div class="d-flex justify-content-between mb-1">
        <small class="font-weight-bold">Langkah 3 dari 4 — Rekapitulasi</small>
        <small>75%</small>
    </div>
    <div class="progress" style="height: 8px;">
        <div class="progress-bar bg-primary" style="width: 75%"></div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-list-ol mr-2"></i>Rekapitulasi Rumah Tangga
        </h6>
    </div>
    <div class="card-body">

        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-2"></i>
            Data di bawah <strong>dihitung otomatis</strong> dari data anggota yang sudah diisi.
            Periksa kembali dan sesuaikan jika perlu.
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tkw.step3') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label>Jumlah Anggota RT</label>
                    <input type="number" name="jart" min="0" max="99"
                        value="{{ old('jart', $data['jart'] ?? 0) }}"
                        class="form-control @error('jart') is-invalid @enderror">
                    @error('jart')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label>Anggota Bekerja</label>
                    <input type="number" name="jart_ab" min="0" max="99"
                        value="{{ old('jart_ab', $data['jart_ab'] ?? 0) }}"
                        class="form-control @error('jart_ab') is-invalid @enderror">
                    @error('jart_ab')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label>Anggota Tidak/Belum Bekerja</label>
                    <input type="number" name="jart_tb" min="0" max="99"
                        value="{{ old('jart_tb', $data['jart_tb'] ?? 0) }}"
                        class="form-control @error('jart_tb') is-invalid @enderror">
                    @error('jart_tb')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label>Anggota Masih Sekolah</label>
                    <input type="number" name="jart_ms" min="0" max="99"
                        value="{{ old('jart_ms', $data['jart_ms'] ?? 0) }}"
                        class="form-control @error('jart_ms') is-invalid @enderror">
                    @error('jart_ms')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Pendapatan Rata-rata RT per Bulan <span class="text-danger">*</span></label>
                    <select name="jpr2rtp" class="form-control @error('jpr2rtp') is-invalid @enderror">
                        <option value="">-- Pilih --</option>
                        <option value="0" {{ old('jpr2rtp', $data['jpr2rtp'] ?? '') == '0' ? 'selected' : '' }}>
                            Tidak Ada Pendapatan
                        </option>
                        <option value="1" {{ old('jpr2rtp', $data['jpr2rtp'] ?? '') == '1' ? 'selected' : '' }}>
                            Di atas Rp 500.000
                        </option>
                        <option value="2" {{ old('jpr2rtp', $data['jpr2rtp'] ?? '') == '2' ? 'selected' : '' }}>
                            Di atas Rp 1.000.000
                        </option>
                        <option value="3" {{ old('jpr2rtp', $data['jpr2rtp'] ?? '') == '3' ? 'selected' : '' }}>
                            Di atas Rp 2.000.000
                        </option>
                        <option value="4" {{ old('jpr2rtp', $data['jpr2rtp'] ?? '') == '4' ? 'selected' : '' }}>
                            Di atas Rp 4.000.000
                        </option>
                    </select>
                    @error('jpr2rtp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-between">
                <a href="{{ route('tkw.step2') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    Selanjutnya <i class="fas fa-arrow-right ml-1"></i>
                </button>
            </div>
        </form>
    </div>
</div>

@endsection