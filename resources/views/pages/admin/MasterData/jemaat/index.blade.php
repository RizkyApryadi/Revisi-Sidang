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
                {{-- <div class="card-body" style="padding:0;font-size:15px;font-weight:700;">
                    {{ number_format($totalJemaat) }} Orang
                </div> --}}
            </div>
        </div>
    </div>
</div>

<!-- Data Jemaat removed from this view per request -->

<!-- Keluarga section -->
<div class="keluarga-section mt-3">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Data Keluarga</h4>
            <div>
                <a href="#" class="btn btn-outline-warning btn-sm mr-2">
                    Pengajuan <span class="badge bg-danger">{{ $pendingCount ?? 0 }}</span>
                </a>
                <a href="{{ route('admin.jemaat.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Jemaat
                </a>
            </div>
        </div>

        <div class="card-body pt-2">
            <table class="table table-bordered table-striped w-100">
                <thead class="thead-dark">
                    <tr class="text-center align-middle">
                        <th style="width:4%">No</th>
                        <th style="width:16%">No KK</th>
                        <th style="width:34%">Alamat</th>
                        <th style="width:10%">Wijk</th>
                        <th style="width:6%">Anggota</th>
                        <th style="width:12%">Aksi</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @foreach($keluargas as $idx => $kel)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td class="text-center">{{ $kel->nomor_kk }}</td>
                        <td style="white-space: normal; word-break: break-word;">{{ $kel->alamat }}</td>
                        <td class="text-center">{{ optional($kel->wijk)->nama_wijk ?? '-' }}</td>
                        <td class="text-center">{{ $kel->jemaats_count ?? $kel->jemaats->count() }}</td>
                        <td class="text-center" style="white-space: nowrap;">
                            <a href="{{ url('/admin/keluarga/'.$kel->id) }}" class="btn btn-info btn-sm mr-1"
                                title="Lihat"><i class="fas fa-eye"></i></a>
                            @php $firstJemaat = $kel->jemaats->first(); @endphp
                            @if($firstJemaat)
                            <a href="{{ route('admin.jemaat.edit', $firstJemaat->id) }}" class="btn btn-warning btn-sm mr-1" title="Edit Jemaat"><i class="fas fa-edit"></i></a>
                            @else
                            <button class="btn btn-warning btn-sm mr-1" disabled title="Tidak ada anggota untuk diedit"><i class="fas fa-edit"></i></button>
                            @endif
                            <form action="{{ url('/admin/keluarga/'.$kel->id) }}" method="POST" style="display:inline;"
                                onsubmit="return confirm('Yakin ingin menghapus keluarga ini?');">
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

    .jemaat-compact .text-truncate {
        max-width: 100%;
        display: block;
        white-space: normal;
        word-break: break-word;
        overflow: visible;
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