@extends('layouts.main')
@section('title', 'Data per RT')

@push('styles')
<style>
    .rt-card {
        border-left: 4px solid #dddfeb;
        transition: transform .15s ease, box-shadow .15s ease, border-left-color .15s ease;
        cursor: pointer;
    }
    .rt-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.1) !important;
        border-left-color: #4e73df;
    }
    .rt-card:hover .rt-icon { color: #4e73df !important; }
</style>
@endpush

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data per RT</h1>
</div>

{{-- Pencarian RT --}}
<div class="card shadow-sm mb-4">
    <div class="card-body py-2">
        <div class="input-group input-group-sm" style="max-width: 300px;">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
            </div>
            <input type="text" id="rt-search" class="form-control"
                   placeholder="Cari nomor RT (misal: 003)...">
        </div>
    </div>
</div>

<div class="row" id="rt-container">
    @for($i = 1; $i <= 24; $i++)
    @php
        $rtNum    = str_pad($i, 3, '0', STR_PAD_LEFT);
        $totalRT  = $rumahTanggaCounts[$i] ?? 0;
        $totalOrg = $anggotaCounts[$i] ?? 0;
    @endphp
    <div class="col-lg-4 col-md-6 mb-4 rt-item" data-rt="{{ $rtNum }}">
        <a href="{{ route('admin.tkw.showrt', $i) }}" class="text-decoration-none text-reset">
            <div class="card shadow-sm rt-card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="mr-3">
                        <i class="fas fa-map-marker-alt fa-2x text-gray-400 rt-icon"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="font-weight-bold mb-1 text-primary">RT {{ $rtNum }}</h6>
                        <div class="small text-muted">
                            <i class="fas fa-home mr-1"></i>{{ $totalRT }} Responden &nbsp;|&nbsp;
                            <i class="fas fa-users mr-1"></i>{{ $totalOrg }} Orang
                        </div>
                    </div>
                    <div>
                        @if($totalRT > 0)
                            <span class="badge badge-primary">{{ $totalRT }}</span>
                        @else
                            <span class="badge badge-light text-muted border">0</span>
                        @endif
                    </div>
                </div>
            </div>
        </a>
    </div>
    @endfor
</div>

<p id="no-result" class="text-center text-muted d-none">RT tidak ditemukan.</p>

@endsection

@push('scripts')
<script>
document.getElementById('rt-search').addEventListener('input', function () {
    const keyword = this.value.trim();
    let found = 0;

    document.querySelectorAll('.rt-item').forEach(item => {
        const rtNum = item.getAttribute('data-rt');
        const match = rtNum.includes(keyword);
        item.style.display = match ? '' : 'none';
        if (match) found++;
    });

    document.getElementById('no-result').classList.toggle('d-none', found > 0);
});
</script>
@endpush