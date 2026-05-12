@php
    // $i bisa string '__INDEX__' (dari template JS) atau integer (dari loop)
    $isTemplate = $i === '__INDEX__';
    $val = fn($field) => $isTemplate ? '' : old("{$field}.{$i}", optional($anggota)->$field ?? '');
    $sel = fn($field, $v) => !$isTemplate && old("{$field}.{$i}", optional($anggota)->$field) == $v ? 'selected' : '';
@endphp

<div class="card shadow-sm mb-3 member-card">
    <div class="card-header d-flex justify-content-between align-items-center py-2">
        <h6 class="m-0 font-weight-bold text-primary member-title">
            Anggota Keluarga #{{ $isTemplate ? '' : ($i + 1) }}
        </h6>
        <button type="button" class="btn btn-danger btn-sm remove-btn">
            <i class="fas fa-trash"></i>
        </button>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Nama Lengkap</label>
                <input type="text" name="nama[]" value="{{ $val('nama') }}"
                    class="form-control @if(!$isTemplate) @error("nama.{$i}") is-invalid @enderror @endif">
                @if(!$isTemplate) @error("nama.{$i}")<div class="invalid-feedback">{{ $message }}</div>@enderror @endif
            </div>
            <div class="form-group col-md-6">
                <label>NIK (16 digit)</label>
                <input type="text" name="nik[]" value="{{ $val('nik') }}"
                    maxlength="16" class="form-control nik-input">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Jenis Kelamin</label>
                <select name="kelamin[]" class="form-control">
                    <option value="">-- Pilih --</option>
                    <option value="1" {{ $sel('kelamin','1') }}>Laki-laki</option>
                    <option value="2" {{ $sel('kelamin','2') }}>Perempuan</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>Hub. KRT</label>
                <select name="hdkrt[]" class="form-control">
                    <option value="">-- Pilih --</option>
                    @foreach(['1'=>'Kepala Keluarga','2'=>'Istri/Suami','3'=>'Anak','4'=>'Menantu','5'=>'Cucu','6'=>'Orang Tua/Mertua','7'=>'Pembantu','8'=>'Lainnya'] as $v => $l)
                        <option value="{{ $v }}" {{ $sel('hdkrt',$v) }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>Hub. KK</label>
                <select name="hdkk[]" class="form-control">
                    <option value="">-- Pilih --</option>
                    @foreach(['1'=>'Kepala Keluarga','2'=>'Istri/Suami','3'=>'Anak','4'=>'Menantu','5'=>'Cucu','6'=>'Orang Tua/Mertua','7'=>'Pembantu','8'=>'Lainnya'] as $v => $l)
                        <option value="{{ $v }}" {{ $sel('hdkk',$v) }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>No. Urut</label>
                <input type="number" name="nuk[]"
                    value="{{ $isTemplate ? '' : ($i + 1) }}"
                    class="form-control" readonly>
            </div>
        </div>
        <hr class="my-2">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Status Perkawinan</label>
                <select name="status_perkawinan[]" class="form-control">
                    <option value="">-- Pilih --</option>
                    @foreach(['1'=>'Belum Kawin','2'=>'Kawin','3'=>'Cerai Hidup','4'=>'Cerai Mati'] as $v => $l)
                        <option value="{{ $v }}" {{ $sel('status_perkawinan',$v) }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Pendidikan Terakhir</label>
                <select name="pendidikan_terakhir[]" class="form-control">
                    <option value="">-- Pilih --</option>
                    @foreach(['1'=>'Tidak/Belum Sekolah','2'=>'Tamat SD','3'=>'Tamat SMP','4'=>'Tamat SMA','5'=>'Tamat PT','6'=>'Tidak Pernah Sekolah'] as $v => $l)
                        <option value="{{ $v }}" {{ $sel('pendidikan_terakhir',$v) }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Status Pekerjaan</label>
                <select name="status_pekerjaan[]" class="form-control">
                    <option value="">-- Pilih --</option>
                    @foreach(['1'=>'Bekerja','2'=>'Ibu Rumah Tangga','3'=>'Bersekolah','4'=>'Tidak/Belum Bekerja','5'=>'Lainnya'] as $v => $l)
                        <option value="{{ $v }}" {{ $sel('status_pekerjaan',$v) }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Jenis Pekerjaan</label>
                <select name="jenis_pekerjaan[]" class="form-control">
                    <option value="">-- Pilih --</option>
                    @foreach(['1'=>'PNS/TNI/POLRI','2'=>'Karyawan/Honorer','3'=>'Wiraswasta','4'=>'Lainnya'] as $v => $l)
                        <option value="{{ $v }}" {{ $sel('jenis_pekerjaan',$v) }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Sub-Jenis Pekerjaan</label>
                <select name="sub_jenis_pekerjaan[]" class="form-control">
                    <option value="">-- Pilih --</option>
                    @foreach(['1'=>'Aparatur Pemerintah','2'=>'Tenaga Ahli','3'=>'Tenaga Harian','4'=>'Pengusaha','5'=>'Lainnya'] as $v => $l)
                        <option value="{{ $v }}" {{ $sel('sub_jenis_pekerjaan',$v) }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Pendapatan/Bulan</label>
                <select name="pendapatan_per_bulan[]" class="form-control">
                    <option value="">-- Pilih --</option>
                    @foreach(['1'=>'> Rp 500rb','2'=>'> Rp 1jt','3'=>'> Rp 2jt','4'=>'> Rp 4jt','5'=>'Tidak Berpenghasilan'] as $v => $l)
                        <option value="{{ $v }}" {{ $sel('pendapatan_per_bulan',$v) }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>