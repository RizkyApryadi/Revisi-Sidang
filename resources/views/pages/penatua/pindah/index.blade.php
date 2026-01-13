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
                    2 Jemaat
                </div>
            </div>
        </div>
    </a>

    {{-- Tabel Pindah Jemaat --}}
    <div class="card mt-4">
        <div class="card-header">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Data Pindah</h4>
                <a href="{{ route('penatua.pelayanan.pindah.create') }}" class="btn btn-primary">
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
                            <th>Tanggal Pindah</th>
                            <th>Dari Gereja</th>
                            <th>Ke Gereja</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Rina Siregar</td>
                            <td>15-01-2024</td>
                            <td>HKBP Medan Kota</td>
                            <td>HKBP Medan Baru</td>
                            <td>Pindah Domisili</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Anton Simanjuntak</td>
                            <td>28-02-2024</td>
                            <td>HKBP Medan Kota</td>
                            <td>HKBP Balige</td>
                            <td>Pindah Pekerjaan</td>
                        </tr>
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