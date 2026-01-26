@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')
<!-- Jemaat -->
<a href="" style="text-decoration: none; color: inherit;">
    <div class="card card-statistic-1">
        <div class="card-icon bg-primary"><i class="fas fa-user-friends"></i></div>
        <div class="card-wrap">
            <div class="card-header">
                <h4>Halo Admin</h4>
            </div>
            <div class="card-body"></div>
        </div>
    </div>
</a>

<div class="row">
    <div class="col-md-6">
        <h5>Siap Dipublish</h5>
        <div class="card mt-2">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Jemaat</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($readyToPublish ?? collect() as $i => $p)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $p->jemaat ? $p->jemaat->nama_lengkap : '-' }}</td>
                                <td>{{ $p->created_at ? $p->created_at->format('d-m-Y') : '-' }}</td>
                                <td>
                                    <form action="{{ route('admin.pelayanan.pindah.publish', $p->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">Publish</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data untuk dipublish.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <h5>Telah Dipublish</h5>
        <div class="card mt-2">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Jemaat</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($published ?? collect() as $i => $p)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $p->jemaat ? $p->jemaat->nama_lengkap : '-' }}</td>
                                <td>{{ $p->created_at ? $p->created_at->format('d-m-Y') : '-' }}</td>
                                <td>{{ $p->keterangan ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data yang dipublikasikan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection