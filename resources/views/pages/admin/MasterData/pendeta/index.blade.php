@extends('layouts.main')
@section('title', 'Data Pendeta')

@section('content')


<div class="section">
    <!-- Statistik -->
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total Pendeta</h4>
                    </div>
                    <div class="card-body">
                        <strong id="totalPendetaCount">-- Orang</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- pending-stat card removed (handled via Tambah Pendeta button) -->
    </div>

    <div class="row mb-3">

        <div class="col text-right">
            <a href="{{ route('admin.pendeta.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Pendeta
            </a>
        </div>
    </div>
</div>

<!-- Card Data Penatua -->
<div class="card">
    <div class="card-header">
        <h4>Daftar Pendeta</h4>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Pendeta</th>
                        <th>Nama WIJK</th>
                        <th>No. HP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

            </table>
        </div>
    </div>
</div>







@endsection