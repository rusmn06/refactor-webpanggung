@extends('layouts.main')

@section('title', 'Dashboard - SID Panggung')

@section('content')

{{-- Page Heading --}}
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <a href="{{ route('tkw.step1') }}" class="btn btn-primary btn-sm shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Isi Kuesioner Baru
    </a>
</div>

{{-- ===== KARTU STATISTIK MILIK USER ===== --}}
<div class="row mb-4">

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Pengajuan Saya
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $myStats['total'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-alt fa-2x text-gray-300"></i>
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
                            Menunggu Validasi
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $myStats['pending'] }}
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
                            Disetujui
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $myStats['validated'] }}
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
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Ditolak
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $myStats['rejected'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ===== GRAFIK DATA PER RT ===== --}}
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    Rekap Data Tenaga Kerja per RT
                    <span class="badge badge-primary ml-2">Total: {{ $grandTotal }}</span>
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
                    <p class="text-gray-500">Belum ada data yang masuk.</p>
                </div>
                @endif
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