@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')

<section class="section">

    <!-- Card Kedukaan -->
    <a href="#" style="text-decoration: none; color: inherit;">
        <div class="card card-statistic-1">
            <div class="card-icon bg-dark">
                <i class="fas fa-cross"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Data Kedukaan</h4>
                </div>
                <div class="card-body">
                    2 Peristiwa
                </div>
            </div>
        </div>
    </a>

    {{-- Tabel Kedukaan --}}
    <div class="card mt-4">
        <div class="card-header">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Data Kedukaan</h4>
                <a href="{{ route('penatua.pelayanan.kedukaan.create') }}" class="btn btn-primary">
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
                            <th>Umur</th>
                            <th>Tanggal Wafat</th>
                            <th>Alamat</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Alm. Budi Hutabarat</td>
                            <td>65</td>
                            <td>18-01-2024</td>
                            <td>Jl. Sisingamangaraja</td>
                            <td>Ibadah Penghiburan</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Alm. Sinta Siregar</td>
                            <td>72</td>
                            <td>02-02-2024</td>
                            <td>Jl. Sudirman</td>
                            <td>Sudah dimakamkan</td>
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