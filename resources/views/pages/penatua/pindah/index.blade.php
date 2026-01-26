@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')

<section class="section">

    <!-- Card Pindah Jemaat -->
    <a href="#" style="text-decoration: none; color: inherit;">
        <div class="card card-statistic-1">
            <div class="card-icon bg-warning">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Data Pindah Jemaat</h4>
                </div>
                <div class="card-body">
                    {{ isset($pendaftarans) ? $pendaftarans->count() : 0 }} Jemaat
                </div>
            </div>
        </div>
    </a>

    {{-- Tabel Pindah Jemaat --}}
    <div class="card mt-4">
        <div class="card-header">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Data Pindah</h4>
                <a href="{{ route('penatua.pindah.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Data
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Jemaat</th>
                            <th>Tanggal Permohonan</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendaftarans as $i => $p)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $p->jemaat ? $p->jemaat->nama_lengkap : '-' }}</td>
                            <td>{{ $p->created_at ? $p->created_at->format('d-m-Y') : '-' }}</td>
                            <td>{{ $p->keterangan ?? '-' }}</td>
                            <td>
                                <form action="{{ route('penatua.pindah.approve', $p->id) }}" method="POST"
                                    style="display:inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">acc</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada permohonan pindah.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Info Login --}}
    <div class="mt-3">
        @auth
        <p>Login sebagai: <strong>{{ Auth::user()->name }}</strong> (Role: {{ Auth::user()->role }})</p>
        @else
        <p>Belum login</p>
        @endauth
    </div>

</section>

@endsection