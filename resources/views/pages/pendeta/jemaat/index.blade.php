@extends('layouts.main')
@section('title', 'Data Jemaat')

@section('content')

@php
$keluargasWithWijk = collect($keluargas ?? [])->filter(function ($k) {
return !empty(optional($k)->wijk);
})->values();

$kkCount = $keluargasWithWijk->count();

$totalJemaat = $keluargasWithWijk->sum(function ($k) {
return $k->jemaats_count ?? ($k->jemaats->count() ?? 0);
});
@endphp

<div class="row mb-3">
    <!-- Kepala Keluarga -->
    <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="card card-statistic-1 text-center" style="padding:8px;">
            <div class="card-icon bg-success mx-auto" style="width:44px;height:44px;line-height:44px;font-size:18px;">
                <i class="fas fa-home"></i>
            </div>
            <div class="card-wrap mt-2">
                <div class="card-header" style="padding:0;">
                    <h6 style="font-size:12px;margin:0;font-weight:600;">
                        Total Keluarga
                    </h6>
                </div>
                <div class="card-body" style="padding:0;font-size:15px;font-weight:700;">
                    {{ number_format($kkCount ?? 0) }} KK
                </div>
            </div>
        </div>
    </div>

    <!-- Total Jemaat -->
    <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="card card-statistic-1 text-center" style="padding:8px;">
            <div class="card-icon bg-warning mx-auto" style="width:44px;height:44px;line-height:44px;font-size:18px;">
                <i class="fas fa-user-friends"></i>
            </div>
            <div class="card-wrap mt-2">
                <div class="card-header" style="padding:0;">
                    <h6 style="font-size:12px;margin:0;font-weight:600;">
                        Total Jemaat
                    </h6>
                </div>
                <div class="card-body" style="padding:0;font-size:15px;font-weight:700;">
                    {{ number_format($totalJemaat ?? 0) }} Orang
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Keluarga section -->
<div class="keluarga-section mt-3">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Data Keluarga</h4>
            <div>
                <a href="{{ route('pendeta.jemaat.create') }}" class="btn btn-primary" title="Tambah Jemaat">
                    <i class="fas fa-plus"></i> Tambah Jemaat
                </a>
            </div>
        </div>

        <div class="card-body pt-2">
            <div class="table-responsive-wrapper">
                <div class="table-responsive-wrapper">
                    <table class="table table-bordered table-striped w-100">
                        <thead class="thead-dark">
                            <tr class="text-center align-middle">
                                <th style="width:4%">No</th>
                                <th style="width:16%">No Registrasi</th>
                                <th style="width:28%"> Kepala Keluarga</th>
                                <th style="width:34%">Alamat</th>
                                <th style="width:10%">Wijk</th>
                                <th style="width:9%; white-space:nowrap;">Anggota</th>
                                <th style="width:12%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="align-middle">
                            @foreach(($keluargas ?? []) as $idx => $kel)
                            @php
                            $kepala = $kel->jemaats->firstWhere('hubungan_keluarga', 'Kepala Keluarga');
                            @endphp
                            <tr>
                                <td class="text-center">{{ $idx + 1 }}</td>
                                <td class="text-center">{{ $kel->nomor_registrasi ?? '-' }}</td>
                                <td style="white-space: normal; word-break: break-word;">{{ $kepala ? $kepala->nama :
                                    '-' }}</td>
                                <td style="white-space: normal; word-break: break-word;">{{ $kel->alamat }}</td>
                                <td class="text-center">{{ optional($kel->wijk)->nama_wijk ?? '-' }}</td>
                                <td class="text-center">{{ $kel->jemaats_count ?? $kel->jemaats->count() }}</td>
                                <td class="text-center" style="white-space: nowrap;">
                                    <button type="button" class="btn btn-info btn-sm mr-1 btn-view-keluarga"
                                        data-id="{{ $kel->id }}" title="Lihat"><i class="fas fa-eye"></i></button>
                                    @php $firstJemaat = $kel->jemaats->first(); @endphp
                                    @if($firstJemaat)
                                    <a href="{{ url('/pendeta/jemaat/'.$firstJemaat->id.'/edit') }}"
                                        class="btn btn-warning btn-sm mr-1" title="Edit Jemaat"><i
                                            class="fas fa-edit"></i></a>
                                    @else
                                    <button class="btn btn-warning btn-sm mr-1" disabled
                                        title="Tidak ada anggota untuk diedit"><i class="fas fa-edit"></i></button>
                                    @endif
                                    <form action="{{ url('/pendeta/keluarga/'.$kel->id) }}" method="POST"
                                        style="display:inline;"
                                        onsubmit="return confirm('Yakin ingin menghapus keluarga ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>

                            {{-- Detail anggota per keluarga (collapsible) --}}
                            <tr class="keluarga-members-row" id="kel-members-row-{{ $kel->id }}" style="display:none;">
                                <td colspan="7">
                                    <div class="card card-body p-2">
                                        <h6 class="mb-2">Anggota Keluarga: {{ $kel->nomor_registrasi ?? '-' }}</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Nama</th>
                                                        <th>Hubungan</th>
                                                        <th class="text-center">JK</th>
                                                        <th>Tempat, Tgl Lahir</th>
                                                        <th>No. Telp</th>
                                                        <th class="text-center">Sidi</th>
                                                        <th class="text-center">Baptis</th>
                                                        <th class="text-center">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($kel->jemaats as $j)
                                                    <tr>
                                                        <td>{{ $j->nama ?? '-' }}</td>
                                                        <td>{{ $j->hubungan_keluarga ?? $j->hubungan ?? '-' }}</td>
                                                        <td class="text-center">{{ $j->jenis_kelamin ?
                                                            strtoupper(substr($j->jenis_kelamin,0,1)) : '-' }}</td>
                                                        <td>{{ ($j->tempat_lahir ?? '-') . ', ' . ($j->tanggal_lahir ?
                                                            \Carbon\Carbon::parse($j->tanggal_lahir)->format('d-m-Y') :
                                                            '-') }}</td>
                                                        <td>{{ $j->no_telp ?? '-' }}</td>
                                                        <td class="text-center">@if($j->tanggal_sidi)<i
                                                                class="fas fa-check-circle text-success"
                                                                title="{{ \Carbon\Carbon::parse($j->tanggal_sidi)->format('d-m-Y') }}"></i>@else<i
                                                                class="fas fa-times-circle text-danger"></i>@endif</td>
                                                        <td class="text-center">@if($j->tanggal_baptis)<i
                                                                class="fas fa-check-circle text-success"
                                                                title="{{ \Carbon\Carbon::parse($j->tanggal_baptis)->format('d-m-Y') }}"></i>@else<i
                                                                class="fas fa-times-circle text-danger"></i>@endif</td>
                                                        <td class="text-center">
                                                            <a href="{{ url('/pendeta/jemaat/'.$j->id) }}"
                                                                class="btn btn-sm btn-info">Lihat</a>
                                                            <a href="{{ url('/pendeta/jemaat/'.$j->id.'/edit') }}"
                                                                class="btn btn-sm btn-warning">Edit</a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Jemaat flat list -->
<div class="jemaat-section mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Daftar Jemaat</h4>

        </div>

        <div class="card-body pt-2">
            @php
            $allJemaats = [];
            if(!empty($keluargas)){
            // Define explicit ordering; Kepala first then Suami/Istri/Anak
            $order = ['Kepala Keluarga' => 0, 'Suami' => 1, 'Istri' => 2, 'Anak' => 3];
            foreach($keluargas as $kel){
            foreach($kel->jemaats as $j){
            $j->keluarga_ref = $kel;
            $allJemaats[] = $j;
            }
            }

            // Sort by keluarga id then by hubungan order (Kepala, Suami, Istri, Anak)
            usort($allJemaats, function($a, $b) use ($order){
            $ka = $a->keluarga_ref->id <=> $b->keluarga_ref->id;
                if($ka !== 0) return $ka;
                $oa = $order[$a->hubungan_keluarga ?? ''] ?? 99;
                $ob = $order[$b->hubungan_keluarga ?? ''] ?? 99;
                return $oa <=> $ob;
                    });
                    }
                    @endphp

                    <div class="table-responsive-wrapper">
                        <table class="table table-bordered table-striped w-100">
                            <thead class="thead-light">
                                <tr class="text-center align-middle">
                                    <th style="width:3%">No</th>
                                    <th style="width:18%">Nama</th>
                                    <th style="width:9%">Hubungan</th>
                                    <th style="width:6%">JK</th>
                                    <th style="width:20%">Tempat, Tgl Lahir</th>
                                    <th style="width:7%">No. Telp</th>
                                    <th style="width:6%">Sidi</th>
                                    <th style="width:6%">Baptis</th>
                                    <th style="width:9%">No Registrasi</th>
                                    <th style="width:12%">Alamat</th>
                                    <th style="width:4%">Wijk</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allJemaats as $i => $jm)
                                <tr class="jemaat-row" data-jemaat-id="{{ $jm->id }}">
                                    @php
                                    $kepala = ($jm->keluarga_ref && $jm->keluarga_ref->jemaats) ?
                                    $jm->keluarga_ref->jemaats->firstWhere('hubungan_keluarga', 'Kepala Keluarga') :
                                    null;
                                    $isKepala = $kepala && ($kepala->id == $jm->id);
                                    $displayHubungan = $isKepala ? 'Kepala Keluarga' : ($jm->hubungan_keluarga ??
                                    ($jm->hubungan ?? '-'));
                                    @endphp
                                    <td class="text-center">{{ $i + 1 }}</td>
                                    <td>{{ $jm->nama ?? '-' }}</td>
                                    <td class="text-center">{{ $displayHubungan }}</td>
                                    <td class="text-center">{{ $jm->jenis_kelamin ?
                                        strtoupper(substr($jm->jenis_kelamin,0,1)) : '-' }}</td>
                                    <td style="white-space: normal;">{{ $jm->tempat_lahir ?? '-' }},
                                        {{ $jm->tanggal_lahir ?
                                        \Carbon\Carbon::parse($jm->tanggal_lahir)->format('d-m-Y') : '-' }}</td>
                                    <td class="text-center">{{ $jm->no_telp ?? '-' }}</td>
                                    <td class="text-center">
                                        @if($jm->tanggal_sidi)
                                        <i class="fas fa-check-circle text-success"
                                            title="{{ \Carbon\Carbon::parse($jm->tanggal_sidi)->format('d-m-Y') }}"></i>
                                        @else
                                        <i class="fas fa-times-circle text-danger" title="Belum Sidi"></i>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($jm->tanggal_baptis)
                                        <i class="fas fa-check-circle text-success"
                                            title="{{ \Carbon\Carbon::parse($jm->tanggal_baptis)->format('d-m-Y') }}"></i>
                                        @else
                                        <i class="fas fa-times-circle text-danger" title="Belum Baptis"></i>
                                        @endif
                                    </td>
                                    <td style="white-space: normal;">{{ $jm->keluarga_ref->nomor_registrasi ?? '-' }}
                                    </td>
                                    <td style="white-space: normal;">{{ $jm->keluarga_ref->alamat ?? '-' }}</td>
                                    <td class="text-center">{{ optional(optional($jm->keluarga_ref)->wijk)->nama_wijk ??
                                        '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center">Belum ada jemaat terdaftar.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Context menu for jemaat rows -->
                    <div id="jemaat-context-menu"
                        style="display:none; position:absolute; z-index:99999; background:#fff; border:1px solid rgba(0,0,0,0.12); box-shadow:0 6px 18px rgba(0,0,0,0.12); border-radius:6px; min-width:180px;">
                        <ul style="list-style:none;margin:0;padding:6px 0;">
                            <li data-role="penatua"
                                style="padding:8px 12px;cursor:pointer;border-bottom:1px solid rgba(0,0,0,0.03);">
                                Jadikan Penatua</li>
                            <li data-role="pendeta" style="padding:8px 12px;cursor:pointer;">Jadikan Pendeta</li>
                        </ul>
                    </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.btn-view-keluarga').forEach(function(btn){
        btn.addEventListener('click', function(){
            var id = this.dataset.id;
            var row = document.getElementById('kel-members-row-' + id);
            if(!row) return;
            row.style.display = (row.style.display === 'none' || row.style.display === '') ? 'table-row' : 'none';
            // scroll into view when opening
            if(row.style.display === 'table-row'){
                row.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    });
});
</script>

<style>
    /* Make table headers blue in this view */
    .table thead th {
        background-color: #0d6efd !important;
        color: #fff !important;
        border-color: rgba(0, 0, 0, 0.05) !important;
    }

    .jemaat-compact {
        font-size: 12px;
    }

    .jemaat-compact .card-header h4 {
        font-size: 14px;
        margin-bottom: 0;
    }

    .jemaat-compact .card-body {
        padding: .5rem;
    }

    .jemaat-compact table.table th,
    .jemaat-compact table.table td {
        padding: .35rem .5rem;
        vertical-align: middle;
    }

    .jemaat-compact .btn {
        padding: .25rem .45rem;
        font-size: .75rem;
    }

    .jemaat-compact .btn .fas {
        font-size: .75rem;
    }

    .jemaat-compact img.rounded-circle {
        width: 32px;
        height: 32px;
        object-fit: cover;
    }

    .jemaat-compact .card {
        padding-bottom: 1rem;
    }

    .jemaat-compact .card-body {
        padding-bottom: 1.25rem;
    }

    .jemaat-compact table {
        margin-bottom: 0;
    }
</style>

@endsection