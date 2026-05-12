@extends('layouts.main')
@section('title', 'Data RT ' . str_pad($rt, 3, '0', STR_PAD_LEFT))

@push('styles')
<link href="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        Data Responden — RT {{ str_pad($rt, 3, '0', STR_PAD_LEFT) }}
    </h1>
    <a href="{{ route('admin.tkw.listrt') }}" class="btn btn-secondary btn-sm shadow-sm">
        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Pilihan RT
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            Daftar Pengajuan RT {{ str_pad($rt, 3, '0', STR_PAD_LEFT) }}
            <span class="badge badge-primary ml-2">{{ $rumahTaggas->count() }}</span>
        </h6>
    </div>
    <div class="card-body">
        @if($rumahTaggas->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-folder-open fa-4x text-gray-300 mb-3"></i>
            <p class="text-gray-600">Belum ada data untuk RT ini.</p>
        </div>
        @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="rtTable" width="100%">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Responden</th>
                        <th>Nama Pendata</th>
                        <th>Jml. Anggota</th>
                        <th>Tgl. Pengajuan</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rumahTaggas as $i => $rt_data)
                    @php
                    $badge = match($rt_data->status_validasi) {
                    'pending' => 'warning',
                    'validated' => 'success',
                    'rejected' => 'danger',
                    default => 'secondary',
                    };
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $rt_data->nama_responden }}</td>
                        <td>{{ $rt_data->nama_pendata }}</td>
                        <td class="text-center">{{ $rt_data->jart }}</td>
                        <td>{{ $rt_data->tgl_pembuatan->isoFormat('D MMM YYYY') }}</td>
                        <td class="text-center">
                            <span class="badge badge-{{ $badge }} px-2 py-1">
                                {{ $rt_data->status_validasi_text }}
                            </span>
                        </td>
                        {{-- Ganti kolom Aksi --}}
                        <td class="text-center">
                            <div class="d-flex justify-content-center" style="gap: .3rem;">
                                <a href="{{ route('admin.tkw.show', $rt_data->id) }}"
                                    class="btn btn-primary btn-sm" title="Verifikasi">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.tkw.export', $rt_data->id) }}"
                                    class="btn btn-success btn-sm" title="Export Excel">
                                    <i class="fas fa-file-excel"></i>
                                </a>

                                <form action="{{ route('admin.tkw.destroy', $rt_data->id) }}"
                                    method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus data {{ addslashes($rt_data->nama_responden) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(document).ready(function() {
        @if($rumahTaggas -> isNotEmpty())
        $('#rtTable').DataTable({
            order: [
                [4, 'desc']
            ],
            language: {
                search: 'Cari:',
                lengthMenu: 'Tampilkan _MENU_ data',
                zeroRecords: 'Data tidak ditemukan',
                paginate: {
                    next: 'Berikutnya',
                    previous: 'Sebelumnya'
                }
            },
            columnDefs: [{
                orderable: false,
                targets: [6]
            }]
        });
        @endif
    });
</script>
@endpush