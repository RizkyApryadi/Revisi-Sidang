@extends('layouts.main')
@section('title', 'Dashboard Pendeta')

@section('content')

{{-- HEADER --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1 fw-bold">Dashboard Pendeta</h4>
                    @auth
                    <small class="text-muted">
                        Selamat datang, <strong>{{ Auth::user()->name }}</strong>
                        <span class="badge bg-primary ms-1">{{ Auth::user()->role }}</span>
                    </small>
                    @endauth
                </div>
                <i class="fas fa-church fa-2x text-primary opacity-75"></i>
            </div>
        </div>
    </div>
</div>

{{-- STATISTIK --}}
<div class="row">
    {{-- Jemaat --}}
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="icon-circle bg-primary text-white me-3">
                    <i class="fas fa-users"></i>
                </div>
                {{-- <div>
                    <h6 class="mb-0 text-muted">Jumlah Jemaat</h6>
                    <h3 class="mb-0 fw-bold">
                        @php
                        if(isset($jemaats_count)){
                        $__jemaat_total = $jemaats_count;
                        } else {
                        try{
                        $__jemaat_total = \Illuminate\Support\Facades\DB::table('jemaats as j')
                        ->join('keluargas as k','j.keluarga_id','=','k.id')
                        ->whereNotNull('k.wijk_id')
                        ->count();
                        }catch(\Exception $e){
                        $__jemaat_total = isset($jemaats) ? collect($jemaats)->count() : 0;
                        }
                        }
                        @endphp
                        {{ $__jemaat_total }}
                    </h3>
                </div> --}}
            </div>
        </div>
    </div>

    {{-- Keluarga --}}
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="icon-circle bg-success text-white me-3">
                    <i class="fas fa-home"></i>
                </div>
                {{-- <div>
                    <h6 class="mb-0 text-muted">Keluarga (KK)</h6>
                    <h3 class="mb-0 fw-bold">
                        @php
                        if(isset($keluargas_count)){
                        $__kk_total = $keluargas_count;
                        } else {
                        try{
                        $__kk_total =
                        \Illuminate\Support\Facades\DB::table('keluargas')->whereNotNull('wijk_id')->count();
                        }catch(\Exception $e){
                        $__kk_total = isset($keluargas) ? collect($keluargas)->count() : 0;
                        }
                        }
                        @endphp
                        {{ $__kk_total }}
                    </h3>
                </div> --}}
            </div>
        </div>
    </div>

    {{-- Pendaftaran --}}
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="icon-circle bg-warning text-white me-3">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                {{-- <div>
                    <h6 class="mb-0 text-muted">Pendaftaran</h6>
                    <h3 class="mb-0 fw-bold">
                        {{ $pendaftaran_count ?? (isset($pendaftaran) ? $pendaftaran->count() : 0) }}
                    </h3>
                </div> --}}
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    @php
    $keluargas_with_wijk = collect($keluargas ?? [])->filter(function($k){ return !empty($k->wijk_id); });
    $jemaats_from_keluarga = $keluargas_with_wijk->flatMap(function($k){
    return collect($k->jemaats ?? []);
    })->sortByDesc('created_at')->values();
    @endphp
    {{-- Jemaat --}}
    <div class="col-lg-4 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-users me-1"></i> Jemaat Terbaru
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Wijk</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jemaats_from_keluarga as $jemaat)
                        @break($loop->index == 5)
                        <tr>
                            <td>{{ $jemaat->nama ?? '-' }}</td>
                            <td>{{ optional(optional($jemaat->keluarga)->wijk)->nama_wijk ?? optional($jemaat->wijk)->nama_wijk ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-3">
                                Belum ada data
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Keluarga --}}
    <div class="col-lg-4 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-success text-white">
                <i class="fas fa-home me-1"></i> Keluarga Terbaru
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No KK</th>
                            <th>Wijk</th>
                            <th>Anggota</th>
                        </tr>
                    </thead>
                    {{-- <tbody>
                        @forelse($keluargas_with_wijk as $kk)
                        @break($loop->index == 5)
                        <tr>
                            <td>{{ $kk->nomor_kk ?? '-' }}</td>
                            <td>{{ optional($kk->wijk)->nama_wijk ?? '-' }}</td>
                            <td>{{ $kk->jemaats->pluck('nama')->take(5)->implode(', ') ?: '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-3">
                                Belum ada data
                            </td>
                        </tr>
                        @endforelse
                    </tbody> --}}
                </table>
            </div>
        </div>
    </div>


</div>

<!-- Menunggu Persetujuan Pendeta -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-header">
                <strong>Permohonan Menunggu Persetujuan</strong>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- <div class="col-md-4">
                        <h6>Baptisan ({{ $need_baptis->count() }})</h6>
                        <ul class="list-unstyled small">
                            @forelse($need_baptis as $b)
                            <li>
                                <a href="#">{{ optional($b->jemaat)->nama ?? '—' }}</a>
                                <div class="text-muted">{{ $b->created_at ? $b->created_at->format('d-m-Y') : '' }}
                                </div>
                            </li>
                            @empty
                            <li class="text-muted">Tidak ada.</li>
                            @endforelse
                        </ul>
                    </div> --}}

                    {{-- <div class="col-md-4">
                        <h6>Pernikahan ({{ $need_pernikahan->count() }})</h6>
                        <ul class="list-unstyled small">
                            @forelse($need_pernikahan as $pn)
                            <li>
                                <a href="#">{{ optional($pn->pria)->nama ?? '—' }} & {{ optional($pn->wanita)->nama ??
                                    '—' }}</a>
                                <div class="text-muted">{{ $pn->created_at ? $pn->created_at->format('d-m-Y') : '' }}
                                </div>
                            </li>
                            @empty
                            <li class="text-muted">Tidak ada.</li>
                            @endforelse
                        </ul>
                    </div> --}}

                    {{-- <div class="col-md-4">
                        <h6>Pindah ({{ $need_pindah->count() }})</h6>
                        <ul class="list-unstyled small">
                            @forelse($need_pindah as $pd)
                            <li>
                                <a href="#">{{ optional($pd->jemaat)->nama_lengkap ?? optional($pd->jemaat)->nama ?? '—'
                                    }}</a>
                                <div class="text-muted">{{ $pd->created_at ? $pd->created_at->format('d-m-Y') : '' }}
                                </div>
                            </li>
                            @empty
                            <li class="text-muted">Tidak ada.</li>
                            @endforelse
                        </ul>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

{{-- STYLE TAMBAHAN --}}
@push('styles')
<style>
    .icon-circle {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }
</style>
@endpush