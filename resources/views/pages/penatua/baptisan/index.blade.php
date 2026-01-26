@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')

<section class="section">

    {{-- Kartu Header --}}
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-water"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Data Baptisan</h4>
                    </div>
                    <div class="card-body">
                        3 Data
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Baptisan --}}
    <div class="card mt-4">
        <div class="card-header">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Data Baptisan</h4>
                <a href="#" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Data Baptisan
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
                            <th>Jenis Kelamin</th>
                            <th>Tanggal Baptisan</th>
                            <th>Tempat Baptisan</th>
                            <th>Nama Pendeta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Samuel Situmorang</td>
                            <td>Laki-laki</td>
                            <td>12-01-2024</td>
                            <td>HKBP Medan Kota</td>
                            <td>Pdt. Andreas Hutabarat</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Maria Siregar</td>
                            <td>Perempuan</td>
                            <td>20-02-2024</td>
                            <td>HKBP Medan Kota</td>
                            <td>Pdt. Jhon Tampubolon</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Daniel Nainggolan</td>
                            <td>Laki-laki</td>
                            <td>05-03-2024</td>
                            <td>HKBP Medan Kota</td>
                            <td>Pdt. Markus Simanjuntak</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</section>

@endsection