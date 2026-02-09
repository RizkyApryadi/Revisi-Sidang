@extends('layouts.main')
@section('title', 'Data Jemaat')

@section('content')
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
                        Kepala Keluarga
                    </h6>
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

                <a href="{{ route('penatua.jemaat.create') }}" class="btn btn-primary">
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
                                <td class="text-center">{{ $kel->nomor_registrasi ?? $kel->nomor_kk ?? '-' }}</td>
                                <td style="white-space: normal; word-break: break-word;">{{ $kepala ? $kepala->nama :
                                    '-' }}
                                </td>
                                <td style="white-space: normal; word-break: break-word;">{{ $kel->alamat }}</td>
                                <td class="text-center">{{ optional($kel->wijk)->nama_wijk ?? '-' }}</td>
                                <td class="text-center">{{ $kel->jemaats_count ?? $kel->jemaats->count() }}</td>
                                <td class="text-center" style="white-space: nowrap;">
                                    <button type="button" class="btn btn-info btn-sm mr-1 btn-view-keluarga"
                                        data-id="{{ $kel->id }}" title="Lihat"><i class="fas fa-eye"></i></button>
                                    @php $firstJemaat = $kel->jemaats->first(); @endphp
                                    @if($firstJemaat)
                                    <a href="{{ url('/penatua/jemaat/'.$firstJemaat->id.'/edit') }}"
                                        class="btn btn-warning btn-sm mr-1" title="Edit Jemaat"><i
                                            class="fas fa-edit"></i></a>
                                    @else
                                    <button class="btn btn-warning btn-sm mr-1" disabled
                                        title="Tidak ada anggota untuk diedit"><i class="fas fa-edit"></i></button>
                                    @endif
                                    <form action="{{ url('/penatua/keluarga/'.$kel->id) }}" method="POST"
                                        style="display:inline;" class="confirm-delete-keluarga">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
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
                                    <td style="white-space: normal;">{{ $jm->keluarga_ref->nomor_registrasi ??
                                        $jm->keluarga_ref->nomor_kk ?? '-' }}</td>
                                    <td style="white-space: normal;">{{ $jm->keluarga_ref->alamat ?? '-' }}</td>
                                    <td class="text-center">{{ optional(optional($jm->keluarga_ref)->wijk)->nama_wijk ??
                                        '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center">Belum ada jemaat terdaftar untuk wijk Anda.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Modal containers for keluarga detail (Kartu Keluarga) -->
                    @foreach($keluargas ?? [] as $kel)
                    <!-- Modal containers for keluarga detail (Kartu Keluarga) -->
                    <div id="modal-keluarga-{{ $kel->id }}" class="modal-custom" style="display:none;">
                        <div class="modal-overlay"></div>

                        <div class="modal-box">
                            <!-- HEADER -->
                            <div class="modal-header">
                                <div>
                                    <h3 style="margin:0;">Informasi Keluarga</h3>
                                </div>
                                <button class="modal-close btn btn-sm btn-secondary">Tutup</button>
                            </div>

                            <div class="modal-body">

                                <!-- ================= DETAIL KELUARGA ================= -->
                                <div style="margin-top:10px;">


                                    <table class="kk-info-table">
                                        <tr>
                                            <td>Nomor Registrasi</td>
                                            <td>: {{ $kel->nomor_registrasi ?? $kel->nomor_kk ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Wijk</td>
                                            <td>: {{ optional($kel->wijk)->nama_wijk ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Alamat</td>
                                            <td>: {{ $kel->alamat ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Registrasi</td>
                                            <td>: {{ $kel->tanggal_registrasi
                                                ? \Carbon\Carbon::parse($kel->tanggal_registrasi)->format('d-m-Y')
                                                : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Pernikahan</td>
                                            <td>: {{ $kel->tanggal_pernikahan
                                                ? \Carbon\Carbon::parse($kel->tanggal_pernikahan)->format('d-m-Y')
                                                : '-' }}</td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- ================= ANGGOTA ================= -->
                                <div style="margin-top:24px;">
                                    <div style="
                                font-weight:600;
                                margin-bottom:8px;
                                border-bottom:1px solid #e5e7eb;
                                padding-bottom:4px;
                            ">
                                        Daftar Anggota Keluarga
                                    </div>

                                    <div class="table-responsive-wrapper">
                                        <table class="kk-table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama</th>
                                                    <th>Hubungan</th>
                                                    <th>JK</th>
                                                    <th>Tempat, Tgl Lahir</th>
                                                    <th>No. Telp</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($kel->jemaats as $i => $j)
                                                <tr>
                                                    <td>{{ $i + 1 }}</td>
                                                    <td>{{ $j->nama ?? '-' }}</td>
                                                    <td>{{ $j->hubungan_keluarga ?? '-' }}</td>
                                                    <td>{{ $j->jenis_kelamin ? strtoupper(substr($j->jenis_kelamin,0,1))
                                                        : '-' }}</td>
                                                    <td>
                                                        {{ $j->tempat_lahir ?? '-' }},
                                                        {{ $j->tanggal_lahir
                                                        ? \Carbon\Carbon::parse($j->tanggal_lahir)->format('d-m-Y')
                                                        : '-' }}
                                                    </td>
                                                    <td>{{ $j->no_telp ?? '-' }}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="6" style="text-align:center; color:#64748b;">
                                                        Tidak ada anggota keluarga
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    @endforeach


        </div>
    </div>
</div>

<style>
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

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    (function(){
        function initPage(){
            @if(session()->has('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: {!! json_encode(session('success')) !!},
                    timer: 2200,
                    showConfirmButton: false
                });
            @endif

            @if(session()->has('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: {!! json_encode(session('error')) !!},
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            // Confirm delete keluarga
            document.querySelectorAll('.confirm-delete-keluarga').forEach(function(form){
                form.addEventListener('submit', function(e){
                    e.preventDefault();
                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: 'Menghapus data keluarga akan otomatis menghapus anggota keluarga',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal'
                    }).then(function(result){
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Modal show/hide handlers for keluarga info
            document.querySelectorAll('.btn-view-keluarga').forEach(function(btn){
                btn.addEventListener('click', function(){
                    var id = this.getAttribute('data-id');
                    var modal = document.getElementById('modal-keluarga-' + id);
                    if(modal) modal.style.display = 'flex';
                });
            });
            document.querySelectorAll('.modal-close').forEach(function(b){
                b.addEventListener('click', function(){
                    this.closest('.modal-custom').style.display = 'none';
                });
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initPage);
        } else {
            initPage();
        }
    })();
</script>

@endsection