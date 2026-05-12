@extends('layouts.main')
@section('title', 'Settings Profil')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Settings Profil</h1>
    <a href="{{ route('profile.show') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

<div class="card shadow-sm col-lg-6 mx-auto">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Nama & Password</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('profile.settings.update') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name"
                    value="{{ old('name', $user->name) }}"
                    class="form-control @error('name') is-invalid @enderror">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" value="{{ $user->username }}"
                    class="form-control" disabled>
                <small class="text-muted">Username tidak dapat diubah.</small>
            </div>

            <hr>

            <div class="form-group">
                <label>Password Baru
                    <small class="text-muted">(kosongkan jika tidak ingin ganti)</small>
                </label>
                <div class="input-group">
                    <input type="password" name="password" id="pw-new"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Minimal 6 karakter">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="pw-new">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <div class="input-group">
                    <input type="password" name="password_confirmation" id="pw-confirm"
                        class="form-control" placeholder="Ulangi password baru">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="pw-confirm">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-save mr-2"></i>Simpan Perubahan
            </button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.toggle-pw').forEach(btn => {
        btn.addEventListener('click', function () {
            const input = document.getElementById(this.dataset.target);
            const icon  = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });
</script>
@endpush