@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')
@php
$total_wijks = \App\Models\Wijk::count();
$total_penatua = \App\Models\Penatua::count();
$total_pendeta = \App\Models\Pendeta::count();

$jemaats_in_wijk_count = \Illuminate\Support\Facades\DB::table('jemaats as j')
->join('keluargas as k','j.keluarga_id','=','k.id')
->whereNotNull('k.wijk_id')
->count();

$total_ibadah = class_exists(\App\Models\Ibadah::class) ? \App\Models\Ibadah::count() : 0;
$total_berita = class_exists(\App\Models\Berita::class) ? \App\Models\Berita::count() : 0;
$total_users = \App\Models\User::count();

$recent_jemaats = \App\Models\Jemaat::with('keluarga.wijk')->orderBy('created_at','desc')->limit(5)->get();
$recent_keluargas = \App\Models\Keluarga::with('wijk','jemaats')->orderBy('created_at','desc')->limit(5)->get();
@endphp

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

{{-- Diagram Lingkaran: Perbandingan Keluarga per Wijk --}}

@php
$wijk_rows = \Illuminate\Support\Facades\DB::table('wijks as w')
->leftJoin('keluargas as k','k.wijk_id','w.id')
->select('w.id','w.nama_wijk', \Illuminate\Support\Facades\DB::raw('COUNT(k.id) as total'))
->groupBy('w.id','w.nama_wijk')
->orderBy('w.nama_wijk')
->get();
$wijk_labels = $wijk_rows->pluck('nama_wijk')->map(fn($v)=> $v ?? '—')->toArray();
$wijk_keluarga_counts = $wijk_rows->pluck('total')->toArray();
@endphp

@php
// Gender distribution for jemaat (safe fallback if column missing)
if(\Illuminate\Support\Facades\Schema::hasColumn('jemaats','jenis_kelamin')){
$gender_rows = \Illuminate\Support\Facades\DB::table('jemaats')
->select('jenis_kelamin', \Illuminate\Support\Facades\DB::raw('COUNT(*) as total'))
->groupBy('jenis_kelamin')
->get();
$gender_labels = $gender_rows->pluck('jenis_kelamin')->map(fn($v)=> $v ?? 'Unknown')->toArray();
$gender_counts = $gender_rows->pluck('total')->toArray();
} else {
$gender_labels = ['Laki-laki','Perempuan'];
$gender_counts = [0,0];
}
@endphp

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
<script>
    document.addEventListener('DOMContentLoaded', function(){
    try{
        if(typeof Chart === 'undefined'){
            console.warn('Chart.js not loaded');
            return;
        }

        const buildColors = (n)=>{
            const palette = ['#4e73df','#1cc88a','#36b9cc','#f6c23e','#e74a3b','#6f42c1','#20c997','#fd7e14','#6610f2'];
            return Array.from({length:n}, (_,i)=> palette[i % palette.length]);
        };

        // Keluarga per Wijk
        const el = document.getElementById('keluargaWijkChart');
        if(el){
            const labels = @json($wijk_labels);
            const data = @json($wijk_keluarga_counts);
            const bg = buildColors(labels.length || data.length || 1);
            try{
                new Chart(el.getContext('2d'), {
                    type: 'pie',
                    data: { labels: labels, datasets: [{ data: data, backgroundColor: bg }] },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 15 } } }
                    }
                });
            }catch(e){ console.error('Failed to render keluargaWijkChart', e); }
        }

        // Gender chart
        const genderEl = document.getElementById('genderChart');
        if(genderEl){
            let gLabels = @json($gender_labels);
            let gData = @json($gender_counts);
            console.debug('gender labels:', gLabels, 'counts:', gData, 'genderEl size:', genderEl.clientWidth, genderEl.clientHeight);
            // ensure numeric counts
            gData = (gData || []).map(v => Number(v) || 0);
            const total = gData.reduce((s,v)=>s+v, 0);
            let gBg;
            if(total === 0){
                // render a neutral placeholder so chart area is visible
                gLabels = ['No Data'];
                gData = [1];
                gBg = ['#e9ecef'];
            } else {
                gBg = buildColors(Math.max(gLabels.length, gData.length, 1));
            }
            try{
                new Chart(genderEl.getContext('2d'), {
                    type: 'doughnut',
                    data: { labels: gLabels, datasets: [{ data: gData, backgroundColor: gBg }] },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
                });
            }catch(e){ console.error('Failed to render genderChart', e); }
        }

    }catch(err){ console.error('Chart init error', err); }
});
</script>
@endpush

@endsection