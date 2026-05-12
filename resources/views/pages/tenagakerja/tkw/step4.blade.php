@extends('layouts.main')
@section('title', 'Kuesioner - Langkah 4 dari 4')

@section('content')

<div class="mb-4">
    <div class="d-flex justify-content-between mb-1">
        <small class="font-weight-bold">Langkah 4 dari 4 — Verifikasi & Tanda Tangan</small>
        <small>100%</small>
    </div>
    <div class="progress" style="height: 8px;">
        <div class="progress-bar bg-success" style="width: 100%"></div>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 pl-3">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card shadow-sm mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-signature mr-2"></i>Verifikasi & Tanda Tangan Pendata
        </h6>
    </div>
    <div class="card-body">
        <form action="{{ route('tkw.step4') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Nama Pendata <span class="text-danger">*</span></label>
                    <input type="text" name="verif_nama_pendata"
                        value="{{ old('verif_nama_pendata', $data['verif_nama_pendata'] ?? '') }}"
                        class="form-control @error('verif_nama_pendata') is-invalid @enderror">
                    @error('verif_nama_pendata')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Tanggal Verifikasi <span class="text-danger">*</span></label>
                    <input type="date" name="verif_tgl_pembuatan"
                        value="{{ old('verif_tgl_pembuatan', $data['verif_tgl_pembuatan'] ?? date('Y-m-d')) }}"
                        class="form-control @error('verif_tgl_pembuatan') is-invalid @enderror">
                    @error('verif_tgl_pembuatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label>Upload Tanda Tangan <span class="text-danger">*</span></label>
                    <small class="text-muted d-block mb-1">Format JPG/PNG, maksimal 2MB</small>

                    <input type="file" name="ttd_pendata" id="ttd-input"
                        accept="image/png,image/jpeg"
                        class="form-control-file @error('ttd_pendata') is-invalid @enderror">

                    @error('ttd_pendata')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror

                    {{-- Preview tanda tangan --}}
                    <div id="ttd-preview" class="mt-3" style="display:none;">
                        <p class="text-muted small mb-1">Preview:</p>
                        <img id="ttd-img" src="" alt="Preview TTD"
                            style="max-height:120px; border:1px solid #ddd; padding:5px; border-radius:4px;">
                    </div>
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-between">
                <a href="{{ route('tkw.step3') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-paper-plane mr-1"></i> Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Preview tanda tangan sebelum submit
    document.getElementById('ttd-input').addEventListener('change', function () {
        const file = this.files[0];
        const preview = document.getElementById('ttd-preview');
        const img = document.getElementById('ttd-img');

        if (!file) { preview.style.display = 'none'; return; }

        if (!['image/jpeg', 'image/png'].includes(file.type)) {
            alert('Format harus JPG atau PNG.');
            this.value = '';
            preview.style.display = 'none';
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2MB.');
            this.value = '';
            preview.style.display = 'none';
            return;
        }

        const reader = new FileReader();
        reader.onload = e => {
            img.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush