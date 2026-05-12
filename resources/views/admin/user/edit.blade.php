@extends('layouts.main')
@section('title', 'Edit Akun User')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Akun: {{ $user->name }}</h1>
    <a href="{{ route('admin.user.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>
</div>

<div class="card shadow-sm col-lg-6 mx-auto">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Form Edit Akun</h6>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name"
                    value="{{ old('name', $user->name) }}"
                    class="form-control @error('name') is-invalid @enderror">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username"
                    value="{{ old('username', $user->username) }}"
                    class="form-control @error('username') is-invalid @enderror">
                @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>
                    Password Baru
                    <small class="text-muted">(kosongkan jika tidak ingin ganti)</small>
                </label>
                <input type="password" name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Minimal 6 karakter">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation"
                    class="form-control" placeholder="Ulangi password baru">
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-save mr-1"></i> Update
            </button>
        </form>
    </div>
</div>

@endsection