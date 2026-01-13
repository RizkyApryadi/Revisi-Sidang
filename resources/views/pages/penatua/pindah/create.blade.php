@extends('layouts.main')
@section('title', 'Permohonan Pindah Jemaat')

@section('content')
<section class="section">

    <div class="section-header">
        <h1>Permohonan Pindah Jemaat</h1>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-sm-12">
            <div class="card shadow-sm border">
                <div class="card-body px-4 py-4">

                    {{-- HEADER --}}
                    <div class="mb-4">
                        <h5 class="text-primary">
                            <i class="fas fa-exchange-alt"></i> Data Permohonan
                        </h5>
                        <p class="text-muted mb-0">
                            Mohon mengisi data dengan lengkap dan benar.
                        </p>
                    </div>

                    <form action="#" method="POST">
                        @csrf

                        {{-- DATA UTAMA --}}
                        <div class="card mb-4 border">
                            <div class="card-header bg-light">
                                <strong><i class="fas fa-users"></i> Data Keluarga</strong>
                            </div>
                            <div class="card-body">

                                <div class="form-group">
                                    <label>Nama Suami</label>
                                    <input type="text" class="form-control" name="nama_suami">
                                </div>

                                <div class="form-group">
                                    <label>Nama Istri</label>
                                    <input type="text" class="form-control" name="nama_istri">
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nomor HP</label>
                                            <input type="text" class="form-control" name="no_hp">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control" name="email">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Wijk</label>
                                    <input type="text" class="form-control" name="wijk">
                                </div>

                                <div class="form-group">
                                    <label>Alamat</label>
                                    <textarea class="form-control" name="alamat" rows="3"></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea class="form-control" name="keterangan" rows="3"></textarea>
                                </div>

                            </div>
                        </div>

                        {{-- ANGGOTA KELUARGA --}}
                        <div class="card mb-4 border">
                            <div class="card-header bg-light">
                                <strong><i class="fas fa-child"></i> Daftar Anggota Keluarga</strong>
                            </div>
                            <div class="card-body">

                                <div class="border rounded p-3 mb-3">
                                    <div class="form-group">
                                        <label>Nama Anak</label>
                                        <input type="text" class="form-control" name="anak_nama[]">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tempat, Tanggal Lahir</label>
                                                <input type="text" class="form-control" name="anak_lahir[]"
                                                    placeholder="Contoh: Medan, 12-05-2015">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Jenis Kelamin</label>
                                                <select class="form-control" name="anak_jk[]">
                                                    <option value="">-- Pilih --</option>
                                                    <option value="Laki-laki">Laki-laki</option>
                                                    <option value="Perempuan">Perempuan</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-0">
                                        <label>Hubungan Keluarga</label>
                                        <input type="text" class="form-control" name="hubungan_keluarga[]"
                                            placeholder="Contoh: Anak Kandung">
                                    </div>
                                </div>

                                {{-- kalau mau multiple anak → nanti tinggal JS clone --}}
                                <small class="text-muted">
                                    * Tambah anggota keluarga dapat dilakukan oleh admin.
                                </small>

                            </div>
                        </div>

                        {{-- SUBMIT --}}
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save"></i> Kirim Permohonan
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

</section>
@endsection
