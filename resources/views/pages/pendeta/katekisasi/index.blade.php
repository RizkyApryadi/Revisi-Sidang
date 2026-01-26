@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')

<section class="section">

@php
    // Controller should provide $katekisasis; keep view simple.
    $totalKatekisasis = collect($katekisasis ?? [])->count();
@endphp

    <!-- Card Katekisasi -->
    <a href="#" style="text-decoration: none; color: inherit;">
        <div class="card card-statistic-1">
            <div class="card-icon bg-success">
                <i class="fas fa-book-bible"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Data Katekisasi</h4>
                </div>
                <div class="card-body">
                    {{ $totalKatekisasis }} Katekisasi
                </div>
            </div>
        </div>
    </a>

    {{-- Tabel Katekisasi --}}
    <div class="card mt-4">
        <div class="card-header">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Data Katekisasi</h4>
              
            </div>
        </div>

        <div class="card-body">
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Periode Katekisasi</th>
                            <th>Pembina</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Peserta</th>
                            <th style="width:200px" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($katekisasis ?? [] as $k)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $k->periode_ajaran }}</td>
                                <td>{{ optional($k->pendeta)->nama_lengkap ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($k->tanggal_mulai)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($k->tanggal_selesai)->format('d M Y') }}</td>
                                <td>{{ optional($k->pendaftaranSidis)->count() ?? 0 }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group" aria-label="Aksi">
                                      

                                        <!-- Show -->
                                        <a href="#" class="btn btn-sm btn-info" title="Show">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- Edit -->
                                        <a href="#" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- Delete -->
                                        <form action="#" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus katekisasi ini?');" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data katekisasi.</td>
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