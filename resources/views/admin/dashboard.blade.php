@extends('layouts.main')

@section('title', 'Dashboard Admin - SID Panggung')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard Admin</h1>
    <a href="{{ route('admin.tkw.index') }}" class="btn btn-warning btn-sm shadow-sm">
        <i class="fas fa-user-check fa-sm mr-1"></i>
        Antrian Verifikasi
        @if($stats['pending'] > 0)
        <span class="badge badge-danger ml-1">{{ $stats['pending'] }}</span>
        @endif
    </a>
</div>

{{-- ===== KARTU STATISTIK GLOBAL ===== --}}
<div class="row mb-4">

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Seluruh Pengajuan
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $stats['total'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-database fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Menunggu Verifikasi
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $stats['pending'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Sudah Divalidasi
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $stats['validated'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Akun User
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $stats['users'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ===== GRAFIK + TABEL RINGKASAN ===== --}}
<div class="row">

    {{-- Grafik Bar --}}
    <div class="col-xl-8 col-lg-7 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    Jumlah Data per RT (Total: {{ $grandTotal }})
                </h6>
            </div>
            <div class="card-body">
                @if($grandTotal > 0)
                <div class="chart-bar">
                    <canvas id="rtBarChart"></canvas>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-chart-bar fa-4x text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Belum ada data.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Ringkasan Status --}}
    <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Ringkasan Status</h6>
            </div>
            <div class="card-body">

                {{-- Progress Validated --}}
                @php
                $total = $stats['total'] ?: 1; // hindari division by zero
                $pctValidated = round($stats['validated'] / $total * 100);
                $pctPending = round($stats['pending'] / $total * 100);
                $pctRejected = round($stats['rejected'] / $total * 100);
                @endphp

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-success font-weight-bold small">Disetujui</span>
                        <span class="small text-muted">{{ $stats['validated'] }} ({{ $pctValidated }}%)</span>
                    </div>
                    <div class="progress" style="height:12px;">
                        <div class="progress-bar bg-success" style="width:{{ $pctValidated }}%"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-warning font-weight-bold small">Pending</span>
                        <span class="small text-muted">{{ $stats['pending'] }} ({{ $pctPending }}%)</span>
                    </div>
                    <div class="progress" style="height:12px;">
                        <div class="progress-bar bg-warning" style="width:{{ $pctPending }}%"></div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-danger font-weight-bold small">Ditolak</span>
                        <span class="small text-muted">{{ $stats['rejected'] }} ({{ $pctRejected }}%)</span>
                    </div>
                    <div class="progress" style="height:12px;">
                        <div class="progress-bar bg-danger" style="width:{{ $pctRejected }}%"></div>
                    </div>
                </div>

                <hr>

                <div class="text-center">
                    <a href="{{ route('admin.tkw.index') }}" class="btn btn-warning btn-sm btn-block">
                        <i class="fas fa-list mr-1"></i> Lihat Antrian Verifikasi
                    </a>
                    <a href="{{ route('admin.tkw.listrt') }}" class="btn btn-primary btn-sm btn-block mt-2">
                        <i class="fas fa-map-marker-alt mr-1"></i> Lihat Data per RT
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="{{ asset('template/vendor/chart.js/Chart.min.js') }}"></script>
<script>
    @if($grandTotal > 0)

    const rtLabels = @json($chartLabels);
    const rtData   = @json($chartData);

    const ctx = document.getElementById('rtBarChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: rtLabels,
            datasets: [{
                label: 'Jumlah Data',
                data: rtData,
                backgroundColor: '#1a7a5e',
                hoverBackgroundColor: '#145e48',
                borderWidth: 0,
                maxBarThickness: 30,
            }]
        },
        options: {
            maintainAspectRatio: false,
            layout: { padding: { left: 10, right: 25, top: 25, bottom: 0 } },
            scales: {
                xAxes: [{
                    gridLines: { display: false, drawBorder: false },
                    ticks: { fontColor: '#858796' }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1,
                        callback: function(val) {
                            return Number.isInteger(val) ? val : '';
                        },
                        fontColor: '#858796'
                    },
                    gridLines: {
                        color: 'rgba(234,236,244,1)',
                        drawBorder: false,
                        borderDash: [2],
                    }
                }]
            },
            legend: { display: false },
            tooltips: {
                backgroundColor: 'rgba(255,255,255,0.9)',
                titleFontColor: '#6e707e',
                bodyFontColor: '#858796',
                borderColor: '#dddfeb',
                borderWidth: 1,
            }
        }
    });

    @endif
</script>
@endpush