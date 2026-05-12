@extends('layouts.main')
@section('title', 'Kuesioner - Langkah 1 dari 4')

@section('content')

{{-- Progress Bar --}}
<div class="mb-4">
    <div class="d-flex justify-content-between mb-1">
        <small class="font-weight-bold">Langkah 1 dari 4 — Informasi Lokasi</small>
        <small>25%</small>
    </div>
    <div class="progress" style="height: 8px;">
        <div class="progress-bar bg-primary" style="width: 25%"></div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-map-marker-alt mr-2"></i>Informasi Lokasi & Pendata
        </h6>
    </div>
    <div class="card-body">

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tkw.step1') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Provinsi <span class="text-danger">*</span></label>
                    <input type="text" name="provinsi"
                        value="{{ old('provinsi', $data['provinsi'] ?? '') }}"
                        class="form-control @error('provinsi') is-invalid @enderror"
                        placeholder="Contoh: Kalimantan Selatan">
                    @error('provinsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Kabupaten / Kota <span class="text-danger">*</span></label>
                    <input type="text" name="kabupaten"
                        value="{{ old('kabupaten', $data['kabupaten'] ?? '') }}"
                        class="form-control @error('kabupaten') is-invalid @enderror"
                        placeholder="Contoh: Banjar">
                    @error('kabupaten')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Kecamatan <span class="text-danger">*</span></label>
                    <input type="text" name="kecamatan"
                        value="{{ old('kecamatan', $data['kecamatan'] ?? '') }}"
                        class="form-control @error('kecamatan') is-invalid @enderror">
                    @error('kecamatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Desa / Kelurahan <span class="text-danger">*</span></label>
                    <input type="text" name="desa"
                        value="{{ old('desa', $data['desa'] ?? '') }}"
                        class="form-control @error('desa') is-invalid @enderror">
                    @error('desa')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label>RT <span class="text-danger">*</span></label>
                    <input type="number" name="rt" min="1" max="999"
                        value="{{ old('rt', $data['rt'] ?? '') }}"
                        class="form-control @error('rt') is-invalid @enderror"
                        placeholder="001">
                    @error('rt')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label>RW <span class="text-danger">*</span></label>
                    <input type="number" name="rw" min="1" max="999"
                        value="{{ old('rw', $data['rw'] ?? '') }}"
                        class="form-control @error('rw') is-invalid @enderror"
                        placeholder="001">
                    @error('rw')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Tanggal Pembuatan <span class="text-danger">*</span></label>
                    <input type="date" name="tgl_pembuatan"
                        value="{{ old('tgl_pembuatan', $data['tgl_pembuatan'] ?? date('Y-m-d')) }}"
                        class="form-control @error('tgl_pembuatan') is-invalid @enderror">
                    @error('tgl_pembuatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Nama Pendata <span class="text-danger">*</span></label>
                    <input type="text" name="nama_pendata"
                        value="{{ old('nama_pendata', $data['nama_pendata'] ?? auth()->user()->name) }}"
                        class="form-control @error('nama_pendata') is-invalid @enderror">
                    @error('nama_pendata')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Nama Responden <span class="text-danger">*</span></label>
                    <input type="text" name="nama_responden"
                        value="{{ old('nama_responden', $data['nama_responden'] ?? '') }}"
                        class="form-control @error('nama_responden') is-invalid @enderror"
                        placeholder="Nama kepala rumah tangga">
                    @error('nama_responden')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-between">
                <a href="{{ route('tenagakerja.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    Selanjutnya <i class="fas fa-arrow-right ml-1"></i>
                </button>
            </div>
        </form>
    </div>
</div>

@endsection