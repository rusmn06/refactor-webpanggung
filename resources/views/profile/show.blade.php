@extends('layouts.main')
@section('title', 'Profil Saya')

@push('styles')
<style>
    .avatar-wrapper { position: relative; display: inline-block; cursor: pointer; }
    .avatar-wrapper img { width: 120px; height: 120px; object-fit: cover; border-radius: 50%; border: 3px solid #4e73df; }
    .avatar-overlay {
        position: absolute; bottom: 0; right: 0;
        background: #4e73df; color: white;
        width: 32px; height: 32px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px;
    }
</style>
@endpush

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Profil Saya</h1>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm text-center py-4">
            <div class="card-body">

                {{-- Avatar Upload --}}
                <form id="avatar-form" action="{{ route('profile.avatar.update') }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="avatar-wrapper mx-auto mb-3"
                         onclick="document.getElementById('avatar-input').click()">
                        <img src="{{ $user->avatar
                            ? asset('storage/avatars/' . $user->avatar)
                            : asset('template/img/undraw_profile.svg') }}"
                            alt="Avatar">
                        <div class="avatar-overlay">
                            <i class="fas fa-camera"></i>
                        </div>
                    </div>
                    <input type="file" id="avatar-input" name="avatar"
                           accept="image/*" class="d-none">
                </form>

                <h5 class="font-weight-bold mb-0">{{ $user->name }}</h5>
                <p class="text-muted">{{ $user->username }}</p>
                <span class="badge badge-{{ $user->isAdmin() ? 'danger' : 'primary' }} px-3 py-2">
                    {{ strtoupper($user->role) }}
                </span>

                <hr>

                <a href="{{ route('profile.settings') }}" class="btn btn-primary btn-block">
                    <i class="fas fa-cog mr-2"></i>Edit Profil & Password
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Akun</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">Nama Lengkap</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>Username</th>
                        <td>{{ $user->username }}</td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td>
                            <span class="badge badge-{{ $user->isAdmin() ? 'danger' : 'primary' }}">
                                {{ strtoupper($user->role) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Bergabung Sejak</th>
                        <td>{{ $user->created_at->isoFormat('D MMMM YYYY') }}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Diperbarui</th>
                        <td>{{ $user->updated_at->isoFormat('D MMMM YYYY, HH:mm') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Auto-submit saat avatar dipilih
    document.getElementById('avatar-input').addEventListener('change', function () {
        if (this.files[0]) {
            document.getElementById('avatar-form').submit();
        }
    });
</script>
@endpush