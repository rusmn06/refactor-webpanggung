@extends('layouts.main')
@section('title', 'Kuesioner Tenaga Kerja')

@push('styles')
    <link href="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Kuesioner Tenaga Kerja Saya</h1>
    <a href="{{ route('tkw.step1') }}" class="btn btn-primary btn-sm shadow-sm">
        <i class="fas fa-plus fa-sm mr-1"></i> Isi Kuesioner Baru
    </a>
</div>

{{-- Kartu Statistik --}}
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pengajuan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPengajuan }}</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-file-alt fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Menunggu Validasi</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pengajuanPending }}</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-clock fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Disetujui</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pengajuanDisetujui }}</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-check-circle fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Ditolak</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pengajuanDitolak }}</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-times-circle fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tabel DataTables --}}
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Riwayat Pengajuan</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Responden</th>
                        <th>Desa</th>
                        <th>Kecamatan</th>
                        <th>RT/RW</th>
                        <th>Tgl. Pengajuan</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $i => $item)
                    @php
                        $badge = match($item->status_validasi) {
                            'pending'   => 'warning',
                            'validated' => 'success',
                            'rejected'  => 'danger',
                            default     => 'secondary',
                        };
                    @endphp
                    <tr>
                        <td>{{ $items->firstItem() + $i }}</td>
                        <td>{{ $item->nama_responden }}</td>
                        <td>{{ $item->desa }}</td>
                        <td>{{ $item->kecamatan }}</td>
                        <td class="text-center">
                            {{ str_pad($item->rt, 3, '0', STR_PAD_LEFT) }} /
                            {{ str_pad($item->rw, 3, '0', STR_PAD_LEFT) }}
                        </td>
                        <td>{{ $item->tgl_pembuatan->isoFormat('D MMM YYYY') }}</td>
                        <td class="text-center">
                            <span class="badge badge-{{ $badge }} px-2 py-1">
                                {{ $item->status_validasi_text }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('tenagakerja.show', $item->id) }}"
                               class="btn btn-info btn-sm" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($item->status_validasi !== 'validated')
                            <a href="{{ route('tenagakerja.edit', $item->id) }}"
                               class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                            Belum ada pengajuan. 
                            <a href="{{ route('tkw.step1') }}">Isi kuesioner sekarang</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Laravel (backup jika DataTables dimatikan) --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $items->links() }}
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>
$(document).ready(function () {
    $('#dataTable').DataTable({
        // Matikan paging DataTables karena sudah pakai pagination Laravel
        paging: false,
        info: false,
        // Aktifkan pencarian & sorting bawaan DataTables
        searching: true,
        ordering: true,
        order: [[5, 'desc']], // Sort by tanggal terbaru
        language: {
            search: 'Cari:',
            zeroRecords: 'Data tidak ditemukan',
            infoFiltered: '(difilter dari _MAX_ total data)',
        },
        columnDefs: [
            { orderable: false, targets: [7] } // Kolom Aksi tidak bisa di-sort
        ]
    });
});
</script>
@endpush