@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')

<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1 fw-bold">Dashboard Admin</h4>
                    <small class="text-muted">Selamat datang, <strong>{{ Auth::user()->name }}</strong></small>
                </div>
                <i class="fas fa-cog fa-2x text-primary opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="icon-circle bg-info text-white me-3"><i class="fas fa-map-marker-alt"></i></div>
                <div>
                    <h6 class="mb-0 text-muted">Total Wijk</h6>
                    <h3 class="mb-0 fw-bold">{{ $total_wijks ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="icon-circle bg-primary text-white me-3"><i class="fas fa-user-tie"></i></div>
                <div>
                    <h6 class="mb-0 text-muted">Total Penatua</h6>
                    <h3 class="mb-0 fw-bold">{{ $total_penatua ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="icon-circle bg-success text-white me-3"><i class="fas fa-chalkboard-teacher"></i></div>
                <div>
                    <h6 class="mb-0 text-muted">Total Pendeta</h6>
                    <h3 class="mb-0 fw-bold">{{ $total_pendeta ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="icon-circle bg-warning text-white me-3"><i class="fas fa-users"></i></div>
                <div>
                    <h6 class="mb-0 text-muted">Jemaat (KK terdaftar di Wijk)</h6>
                    <h3 class="mb-0 fw-bold">{{ $jemaats_in_wijk_count ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>


</div>

<div class="row mt-4">
    {{-- Perbandingan Keluarga per Wijk (kiri) --}}
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center flex-wrap">
                <strong>Perbandingan Keluarga per Wijk</strong>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center">
                <canvas id="keluargaWijkChart" width="250" height="250"></canvas>
            </div>
        </div>
    </div>

    {{-- Distribusi Jenis Kelamin Jemaat (kanan) --}}
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <strong>Distribusi Jenis Kelamin Jemaat</strong>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center">
                <canvas id="genderChart" width="250" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<style>
    /* Satukan ukuran chart */
    .card-body canvas {
        max-width: 300px !important;
        max-height: 300px !important;
        width: 100% !important;
        height: auto !important;
    }
</style>


@push('script')
<!-- use a specific Chart.js build for compatibility -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<style>
    /* Make chart card bodies equal height and center the canvas */
    .chart-card-body {
        min-height: 260px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chart-card-body canvas {
        width: 100% !important;
        max-width: 300px;
        height: auto !important;
        max-height: 300px;
    }
</style>

@endpush

@endsection