@extends('layouts.main')
@section('title', 'Data Jemaat')

@section('content')
<div class="row mb-3">
    <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="card card-statistic-1 text-center" style="padding:8px;">
            <div class="card-icon bg-success mx-auto" style="width:44px;height:44px;line-height:44px;font-size:18px;">
                <i class="fas fa-home"></i>
            </div>
            <div class="card-wrap mt-2">
                <div class="card-header" style="padding:0;">
                    <h6 style="font-size:12px;margin:0;font-weight:600;">Kepala Keluarga</h6>
                </div>
                <div class="card-body" style="padding:0;font-size:15px;font-weight:700;">{{ number_format($kkCount ?? 0)
                    }} KK</div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="card card-statistic-1 text-center" style="padding:8px;">
            <div class="card-icon bg-warning mx-auto" style="width:44px;height:44px;line-height:44px;font-size:18px;">
                <i class="fas fa-user-friends"></i>
            </div>
            <div class="card-wrap mt-2">
                <div class="card-header" style="padding:0;">
                    <h6 style="font-size:12px;margin:0;font-weight:600;">Total Jemaat</h6>
                </div>
                <div class="card-body" style="padding:0;font-size:15px;font-weight:700;">{{ number_format($totalJemaat
                    ?? 0) }} Orang</div>
            </div>
        </div>
    </div>
</div>

<div class="jemaat-compact">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Data Jemaat</h4>
            <a href="{{ route('penatua.jemaat.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Jemaat
            </a>
        </div>

        <div class="card-body pt-2">
            <table class="table table-bordered table-striped w-100">
                <thead class="thead-dark">
                    <tr class="text-center align-middle">
                        <th style="width: 4%">No</th>
                        <th style="width: 9%">No Jemaat</th>
                        <th style="width: 9%">No KK</th>
                        <th style="width: 14%">Nama</th>
                        <th style="width: 16%">Tempat, Tanggal Lahir</th>
                        <th style="width: 13%">Alamat</th>
                        <th style="width: 6%">JK</th>
                        <th style="width: 11%">No HP</th>
                        <th style="width: 7%">Foto</th>
                        <th style="width: 11%">Aksi</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @foreach(($jemaats ?? []) as $index => $j)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $j->nomor_jemaat }}</td>
                        <td class="text-center">{{ $j->keluarga?->nomor_keluarga }}</td>
                        <td>{{ $j->nama_lengkap }}</td>
                        <td class="text-center">{{ $j->tempat_lahir }},<br>{{ $j->tanggal_lahir ?
                            \Carbon\Carbon::parse($j->tanggal_lahir)->format('d M Y') : '' }}</td>
                        <td style="white-space: normal; word-break: break-word;">{{ $j->keluarga?->alamat }}</td>
                        <td class="text-center">{{ $j->jenis_kelamin ? (str_contains($j->jenis_kelamin, 'Laki') ? 'L' :
                            'P') : '' }}</td>
                        <td class="text-center" style="white-space: nowrap;">{{ $j->no_hp }}</td>
                        <td class="text-center">
                            @if($j->foto)
                            <img src="{{ asset('storage/'.$j->foto) }}" alt="Foto Jemaat" class="rounded-circle">
                            @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($j->nama_lengkap) }}&background=ddd&color=444"
                                alt="Foto Jemaat" class="rounded-circle">
                            @endif
                        </td>
                        <td class="text-center" style="white-space: nowrap;">
                            <button class="btn btn-info btn-sm mr-1" disabled title="Lihat"><i
                                    class="fas fa-eye"></i></button>
                            <button class="btn btn-warning btn-sm mr-1" disabled title="Edit"><i
                                    class="fas fa-edit"></i></button>
                            <button class="btn btn-danger btn-sm" disabled title="Hapus"><i
                                    class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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

@endsection