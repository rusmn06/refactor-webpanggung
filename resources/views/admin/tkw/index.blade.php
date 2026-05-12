@extends('layouts.main')
@section('title', 'Verifikasi Tenaga Kerja')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Antrian Verifikasi</h1>
    <a href="{{ route('admin.tkw.listrt') }}" class="btn btn-primary btn-sm shadow-sm">
        <i class="fas fa-map-marker-alt mr-1"></i> Lihat Data per RT
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            Pengajuan Menunggu Verifikasi
            <span class="badge badge-warning ml-2">{{ $items->total() }}</span>
        </h6>
    </div>
    <div class="card-body">
        @if($items->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                <p class="lead text-gray-700">Tidak ada pengajuan yang menunggu verifikasi.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Responden</th>
                            <th>Pendata</th>
                            <th>Desa</th>
                            <th>RT/RW</th>
                            <th>Tgl. Pengajuan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $i => $item)
                            <tr>
                                <td>{{ $items->firstItem() + $i }}</td>
                                <td>{{ $item->nama_responden }}</td>
                                <td>{{ $item->nama_pendata }}</td>
                                <td>{{ $item->desa }}</td>
                                <td>{{ str_pad($item->rt, 3, '0', STR_PAD_LEFT) }}/{{ str_pad($item->rw, 3, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $item->tgl_pembuatan->isoFormat('D MMM YYYY') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.tkw.show', $item->id) }}"
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye mr-1"></i> Verifikasi
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</div>

@endsection