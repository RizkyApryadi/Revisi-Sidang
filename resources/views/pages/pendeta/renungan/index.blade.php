@extends('layouts.main')
@section('title', 'Renungan Harian')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Renungan Harian</h1>
        <div class="section-header-button">
            <a href="{{ route('pendeta.renungan.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Renungan
            </a>
        </div>
    </div>

    <div class="section-body">
        <div class="card shadow-sm">
            <div class="card-header">
                <h4>Daftar Renungan</h4>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Tanggal</th>
                                <th>Pendeta</th>
                                <th>Status</th>
                                <th>Konten</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($renungans as $renungan)
                                <tr>
                                    <td>{{ $loop->iteration + (($renungans->currentPage()-1) * $renungans->perPage()) }}</td>
                                    <td>{{ $renungan->judul }}</td>
                                    <td>{{ optional($renungan->tanggal)->format('d M Y') }}</td>
                                    <td>{{ optional($renungan->pendeta)->nama_lengkap ?? '-' }}</td>
                                    <td>
                                        @if($renungan->status === 'publish')
                                            <span class="badge badge-success">Publish</span>
                                        @else
                                            <span class="badge badge-warning">Draft</span>
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($renungan->konten, 100, '...') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        Belum ada renungan harian
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $renungans->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
