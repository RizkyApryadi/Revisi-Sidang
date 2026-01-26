@extends('layouts.main')
@section('title', 'Dashboard Penatua')

@section('content')

@php
try{
$penatua = \App\Models\Penatua::with('wijk')
->where('user_id', Auth::id())
->first();

$wijkId = optional($penatua)->wijk_id ?? null;

$keluargas_in_wijk = $wijkId
? \App\Models\Keluarga::with('jemaats')
->where('wijk_id', $wijkId)
->latest()
->take(5)
->get()
: collect();

$jemaats_in_wijk = $keluargas_in_wijk
->flatMap(fn($k) => $k->jemaats ?? collect())
->sortByDesc('created_at')
->take(10);

}catch(\Exception $e){
$penatua = null;
$keluargas_in_wijk = collect();
$jemaats_in_wijk = collect();
}
@endphp

<section class="section">

    {{-- ================= HEADER ================= --}}
    <div class="section-header">
        <h1>Dashboard Penatua</h1>
    </div>

    {{-- ================= GREETING ================= --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="mb-1">
                        Selamat datang,
                        <strong>
                            Penatua {{ optional($penatua)->nama_lengkap ?? Auth::user()->name }}
                        </strong>
                    </h5>
                    <p class="text-muted mb-0">
                        Bertugas di Wijk:
                        <strong>{{ optional(optional($penatua)->wijk)->nama_wijk ?? 'Belum terdaftar' }}</strong>
                    </p>
                    <small class="text-muted">
                        Login sebagai {{ Auth::user()->name }} ({{ Auth::user()->role }})
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= STATISTIK ================= --}}
    <div class="row mb-4">

        {{-- Wijk --}}
        <div class="col-md-6 col-lg-4">
            <div class="card card-statistic-1 shadow-sm">
                <div class="card-icon bg-primary">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Wijk</h4>
                    </div>
                    <div class="card-body">
                        {{ optional(optional($penatua)->wijk)->nama_wijk ?? '-' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Keluarga --}}
        <div class="col-md-6 col-lg-4">
            <div class="card card-statistic-1 shadow-sm">
                <div class="card-icon bg-success">
                    <i class="fas fa-home"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Keluarga</h4>
                    </div>
                    <div class="card-body">
                        {{ $keluargas_in_wijk->count() }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Jemaat --}}
        <div class="col-md-6 col-lg-4">
            <div class="card card-statistic-1 shadow-sm">
                <div class="card-icon bg-info">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Jemaat</h4>
                    </div>
                    <div class="card-body">
                        {{ $jemaats_in_wijk->count() }}
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ================= DATA DETAIL ================= --}}
    <div class="row">

        {{-- KELUARGA --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <strong>Keluarga Terdaftar</strong>
                    <div class="small">{{ optional(optional($penatua)->wijk)->nama_wijk }}</div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0">
                        <thead>
                            <tr>
                                <th>No KK</th>
                                <th class="text-center">Jumlah Jemaat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($keluargas_in_wijk as $kk)
                            <tr>
                                <td>{{ $kk->nomor_kk }}</td>
                                <td class="text-center">{{ $kk->jemaats->count() }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted py-3">
                                    Belum ada data keluarga
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- JEMAAT --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <strong>Jemaat Terbaru</strong> 
                    <div class="small">{{ optional(optional($penatua)->wijk)->nama_wijk }}</div>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($jemaats_in_wijk as $jm)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $jm->nama }}</strong><br>
                                <small class="text-muted">
                                    KK: {{ optional($jm->keluarga)->nomor_kk }}
                                </small>
                            </div>
                            <small class="text-muted">
                                {{ optional($jm->created_at)->format('d M Y') }}
                            </small>
                        </li>
                        @empty
                        <li class="list-group-item text-center text-muted">
                            Belum ada jemaat
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

    </div>

</section>
@endsection