@extends('layouts.main')
@section('title', 'Pending Jemaat')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Pengajuan Jemaat (Menunggu Persetujuan)</h4>
        <a href="{{ route('admin.jemaat') }}" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="card-body pt-2">
        <table class="table table-bordered table-striped w-100">
            <thead class="thead-dark">
                <tr class="text-center align-middle">
                    <th style="width:4%">No</th>
                    <th style="width:10%">No Jemaat</th>
                    <th style="width:12%">No KK</th>
                    <th style="width:20%">Nama</th>
                    <th style="width:18%">Tempat, Tanggal Lahir</th>
                    <th style="width:10%">No HP</th>
                    <th style="width:12%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jemaats as $i => $j)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td class="text-center">{{ $j->nomor_jemaat }}</td>
                    <td class="text-center">{{ $j->keluarga?->nomor_keluarga }}</td>
                    <td>{{ $j->nama_lengkap }}</td>
                    <td class="text-center">{{ $j->tempat_lahir }},<br>{{ $j->tanggal_lahir ? \Carbon\Carbon::parse($j->tanggal_lahir)->format('d M Y') : '' }}</td>
                    <td class="text-center">{{ $j->no_hp }}</td>
                    <td class="text-center" style="white-space:nowrap">
                        <a href="{{ route('admin.jemaat.show', $j->id) }}" class="btn btn-info btn-sm mr-1">Show</a>

                        <form action="{{ route('admin.jemaat.approve', $j->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Setujui jemaat ini?')">ACC</button>
                        </form>

                        <form action="{{ route('admin.jemaat.reject', $j->id) }}" method="POST" style="display:inline;margin-left:6px;" onsubmit="return confirm('Tolak pengajuan jemaat ini?');">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada pengajuan jemaat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
