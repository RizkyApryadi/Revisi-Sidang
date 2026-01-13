@extends('layouts.main')
@section('title', 'Add Jemaat')

@section('content')

<div class="section">
    <div class="section-body">
        <div class="bg-white p-4 rounded">
            <div class="row justify-content-center">
                <div class="col-lg-11 col-md-12">
                    <h1><i class="fas fa-users"></i> Add Jemaat</h1>
                    <div class="border rounded p-4">

                        <form method="POST" action="{{ route('admin.jemaat.store') }}" enctype="multipart/form-data">
                            @csrf

                            <ul class="nav nav-tabs mb-4" role="tablist">
                                <li class="nav-item"><a class="nav-link active" id="tab-pribadi" data-bs-toggle="tab"
                                        href="#data-pribadi" role="tab">Data Pribadi</a></li>
                                <li class="nav-item"><a class="nav-link" id="tab-baptis" data-bs-toggle="tab"
                                        href="#data-baptis" role="tab">Data Baptis</a></li>
                                <li class="nav-item"><a class="nav-link" id="tab-sidi" data-bs-toggle="tab"
                                        href="#data-sidi" role="tab">Data Sidi</a></li>
                                <li class="nav-item"><a class="nav-link" id="tab-pernikahan" data-bs-toggle="tab"
                                        href="#data-pernikahan" role="tab">Data Pernikahan</a></li>
                                <li class="nav-item"><a class="nav-link" id="tab-pindah" data-bs-toggle="tab"
                                        href="#data-pindah" role="tab">Data Pindah</a></li>
                                <li class="nav-item"><a class="nav-link" id="tab-kedukaan" data-bs-toggle="tab"
                                        href="#data-kedukaan" role="tab">Data Kedukaan</a></li>
                            </ul>


                            <div class="tab-content">
                                {{-- Data Pribadi --}}
                                <div class="tab-pane fade show active" id="data-pribadi" role="tabpanel">

                                    <div class="form-group row mb-3">
                                        <label class="col-md-2 col-form-label text-md-right">Nomor Jemaat :</label>
                                        <div class="col-md-4">
                                            <input name="nomor_jemaat" class="form-control form-control-sm">
                                        </div>

                                        <label class="col-md-2 col-form-label text-md-right">Nomor Keluarga :</label>
                                        <div class="col-md-4">
                                            <input name="nomor_keluarga" class="form-control form-control-sm">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-md-2 col-form-label text-md-right">Nama Lengkap :</label>
                                        <div class="col-md-10">
                                            <input name="nama_lengkap" class="form-control form-control-sm">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-md-2 col-form-label text-md-right">Alamat :</label>
                                        <div class="col-md-10">
                                            <textarea name="alamat" class="form-control form-control-sm"
                                                rows="3"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-md-2 col-form-label text-md-right">RT / RW / WIJK :</label>
                                        <div class="col-md-2">
                                            <input name="rt" class="form-control form-control-sm text-center"
                                                placeholder="RT">
                                        </div>
                                        <div class="col-md-2">
                                            <input name="rw" class="form-control form-control-sm text-center"
                                                placeholder="RW">
                                        </div>
                                        <div class="col-md-6">
                                            <select name="wijk" class="form-control form-control-sm text-center">
                                                <option value="">-- Pilih Wijk --</option>
                                                @foreach($wijks as $w)
                                                <option value="{{ $w->id }}">{{ $w->nama_wijk }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-md-2 col-form-label text-md-right">Kelurahan :</label>
                                        <div class="col-md-4">
                                            <input name="kelurahan" class="form-control form-control-sm">
                                        </div>

                                        <label class="col-md-2 col-form-label text-md-right">Kecamatan :</label>
                                        <div class="col-md-4">
                                            <input name="kecamatan" class="form-control form-control-sm">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-md-2 col-form-label text-md-right">Kabupaten :</label>
                                        <div class="col-md-4">
                                            <input name="kabupaten" class="form-control form-control-sm">
                                        </div>

                                        <label class="col-md-2 col-form-label text-md-right">Provinsi :</label>
                                        <div class="col-md-4">
                                            <input name="provinsi" class="form-control form-control-sm">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-md-2 col-form-label text-md-right">Kode Pos :</label>
                                        <div class="col-md-4">
                                            <input name="kode_pos" class="form-control form-control-sm">
                                        </div>

                                        <label class="col-md-2 col-form-label text-md-right">Email :</label>
                                        <div class="col-md-4">
                                            <input type="email" name="email" class="form-control form-control-sm">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-md-2 col-form-label text-md-right">Tempat Lahir :</label>
                                        <div class="col-md-4">
                                            <input name="tempat_lahir" class="form-control form-control-sm">
                                        </div>

                                        <label class="col-md-2 col-form-label text-md-right">Tanggal Lahir :</label>
                                        <div class="col-md-4">
                                            <input type="date" name="tanggal_lahir"
                                                class="form-control form-control-sm">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-md-2 col-form-label text-md-right">Jenis Kelamin :</label>
                                        <div class="col-md-4">
                                            <select name="jenis_kelamin" class="form-control form-control-sm">
                                                <option value="Laki-laki">Laki-laki</option>
                                                <option value="Perempuan">Perempuan</option>
                                            </select>
                                        </div>

                                        <label class="col-md-2 col-form-label text-md-right">Nomor HP :</label>
                                        <div class="col-md-4">
                                            <input name="no_hp" class="form-control form-control-sm">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-md-2 col-form-label text-md-right">Nama Ayah :</label>
                                        <div class="col-md-4">
                                            <input name="nama_ayah" class="form-control form-control-sm">
                                        </div>

                                        <label class="col-md-2 col-form-label text-md-right">Nama Ibu :</label>
                                        <div class="col-md-4">
                                            <input name="nama_ibu" class="form-control form-control-sm">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-md-2 col-form-label text-md-right">Status Pernikahan :</label>
                                        <div class="col-md-4">
                                            <select name="status_pernikahan" class="form-control form-control-sm">
                                                <option value="">-- Pilih --</option>
                                                <option value="belum">Belum Menikah</option>
                                                <option value="sudah">Sudah Menikah</option>
                                            </select>
                                        </div>

                                        <label class="col-md-2 col-form-label text-md-right">Hubungan Keluarga :</label>
                                        <div class="col-md-4">
                                            <select id="hubungan_keluarga" name="hubungan_keluarga"
                                                class="form-control form-control-sm">
                                                <option value="">-- Pilih --</option>
                                                <option value="Ayah">Ayah</option>
                                                <option value="Ibu">Ibu</option>
                                                <option value="Anak">Anak</option>
                                            </select>
                                        </div>
                                        <div id="anak-ke-wrapper" class="col-md-2" style="display:none;">
                                            <input type="number" min="1" name="anak_ke" id="anak_ke"
                                                class="form-control form-control-sm" placeholder="Anak ke">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-md-2 col-form-label text-md-right">Keterangan :</label>
                                        <div class="col-md-10">
                                            <textarea name="keterangan" class="form-control form-control-sm"
                                                rows="2"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label class="col-md-2 col-form-label text-md-right">Pas Foto :</label>
                                        <div class="col-md-10">
                                            <input type="file" name="foto" class="form-control form-control-sm">
                                        </div>
                                    </div>

                                </div>
                            </div>

                            {{-- Data Baptis --}}
                            <div class="tab-pane fade" id="data-baptis" role="tabpanel">

                                {{-- STATUS BAPTIS --}}
                                <div class="form-group row mb-3">
                                    <label class="col-md-2 col-form-label text-md-right">
                                        Status Baptis :
                                    </label>
                                    <div class="col-md-4">
                                        <select class="form-control form-control-sm" id="status_baptis"
                                            name="status_baptis">
                                            <option value="">-- Pilih --</option>
                                            <option value="belum">Belum Baptis</option>
                                            <option value="sudah">Sudah Baptis</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- PILIHAN GEREJA --}}
                                <div id="pilihan-gereja" style="display:none;">
                                    <div class="row">

                                        {{-- GEREJA LOKAL --}}
                                        <div class="col-md-6">
                                            <div class="card border">
                                                <div class="card-header">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="gereja_lokal" name="jenis_gereja"
                                                            class="custom-control-input" value="lokal">
                                                        <label class="custom-control-label" for="gereja_lokal">
                                                            Gereja Lokal
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label>Nomor Kartu</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="lokal_nomor_kartu">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Tanggal Baptisan</label>
                                                        <input type="date" class="form-control form-control-sm"
                                                            name="lokal_tgl_baptis">
                                                    </div>

                                                    <div class="form-group mb-0">
                                                        <label>Dibaptis Oleh</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="lokal_baptis_oleh">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- GEREJA LUAR --}}
                                        <div class="col-md-6">
                                            <div class="card border">
                                                <div class="card-header">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="gereja_luar" name="jenis_gereja"
                                                            class="custom-control-input" value="luar">
                                                        <label class="custom-control-label" for="gereja_luar">
                                                            Gereja Luar
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label>Nomor Kartu</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="luar_nomor_kartu">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Tanggal Baptisan</label>
                                                        <input type="date" class="form-control form-control-sm"
                                                            name="luar_tgl_baptis">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Dibaptis Oleh</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="luar_baptis_oleh">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Di Gereja</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="luar_nama_gereja">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Alamat Gereja</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="luar_alamat_gereja">
                                                    </div>

                                                    <div class="form-group mb-0">
                                                        <label>Kota</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="luar_kota">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                {{-- BUTTON --}}
                                <div class="text-center mt-4">
                                    <button type="button" class="btn btn-secondary btn-sm px-5 d-none">
                                        <i class="fas fa-save"></i> Simpan Baptis
                                    </button>
                                </div>

                            </div>

                            {{-- Data Sidi --}}
                            <div class="tab-pane fade" id="data-sidi" role="tabpanel">

                                {{-- STATUS SIDI --}}
                                <div class="form-group row mb-3">
                                    <label class="col-md-2 col-form-label text-md-right">
                                        Status Sidi :
                                    </label>
                                    <div class="col-md-4">
                                        <select class="form-control form-control-sm" id="status_sidi"
                                            name="status_sidi">
                                            <option value="">-- Pilih --</option>
                                            <option value="belum">Belum Sidi</option>
                                            <option value="sudah">Sudah Sidi</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- PILIHAN GEREJA --}}
                                <div id="pilihan-gereja-sidi" style="display:none;">
                                    <div class="row">

                                        {{-- GEREJA LOKAL --}}
                                        <div class="col-md-6">
                                            <div class="card border">
                                                <div class="card-header">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="sidi_gereja_lokal"
                                                            name="jenis_gereja_sidi" class="custom-control-input"
                                                            value="lokal">
                                                        <label class="custom-control-label" for="sidi_gereja_lokal">
                                                            Gereja Lokal
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label>Nomor Kartu</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="sidi_lokal_nomor_kartu">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Tanggal Sidi</label>
                                                        <input type="date" class="form-control form-control-sm"
                                                            name="sidi_lokal_tgl">
                                                    </div>

                                                    <div class="form-group mb-0">
                                                        <label>Disidi Oleh</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="sidi_lokal_oleh">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- GEREJA LUAR --}}
                                        <div class="col-md-6">
                                            <div class="card border">
                                                <div class="card-header">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="sidi_gereja_luar"
                                                            name="jenis_gereja_sidi" class="custom-control-input"
                                                            value="luar">
                                                        <label class="custom-control-label" for="sidi_gereja_luar">
                                                            Gereja Luar
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label>Nomor Kartu</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="sidi_luar_nomor_kartu">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Tanggal Sidi</label>
                                                        <input type="date" class="form-control form-control-sm"
                                                            name="sidi_luar_tgl">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Disidi Oleh</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="sidi_luar_oleh">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Di Gereja</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="sidi_luar_nama_gereja">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Alamat Gereja</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="sidi_luar_alamat_gereja">
                                                    </div>

                                                    <div class="form-group mb-0">
                                                        <label>Kota</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="sidi_luar_kota">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                {{-- BUTTON --}}
                                <div class="text-center mt-4">
                                    <button type="button" class="btn btn-secondary btn-sm px-5 d-none">
                                        <i class="fas fa-save"></i> Simpan Sidi
                                    </button>
                                </div>

                            </div>

                            {{-- Data Pernikahan --}}
                            <div class="tab-pane fade" id="data-pernikahan" role="tabpanel">

                                {{-- STATUS PERNIKAHAN --}}
                                <div class="form-group row mb-3">
                                    <label class="col-md-2 col-form-label text-md-right">
                                        Status Pernikahan :
                                    </label>
                                    <div class="col-md-4">
                                        <select class="form-control form-control-sm" id="status_nikah"
                                            name="status_nikah">
                                            <option value="" selected disabled>-- Pilih --</option>
                                            <option value="belum">Belum Menikah</option>
                                            <option value="sudah">Sudah Menikah</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- PILIHAN GEREJA --}}
                                <div id="pilihan-gereja-nikah" style="display:none;">
                                    <div class="row">

                                        {{-- GEREJA LOKAL --}}
                                        <div class="col-md-6">
                                            <div class="card border">
                                                <div class="card-header">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="nikah_gereja_lokal"
                                                            name="jenis_gereja_nikah" class="custom-control-input"
                                                            value="lokal">
                                                        <label class="custom-control-label" for="nikah_gereja_lokal">
                                                            Gereja Lokal
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label>Nomor Kartu Nikah</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="nikah_lokal_nomor_kartu">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Tanggal Pernikahan</label>
                                                        <input type="date" class="form-control form-control-sm"
                                                            name="nikah_lokal_tgl">
                                                    </div>

                                                    <div class="form-group mb-0">
                                                        <label>Diberkati Oleh</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="nikah_lokal_oleh">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- GEREJA LUAR --}}
                                        <div class="col-md-6">
                                            <div class="card border">
                                                <div class="card-header">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="nikah_gereja_luar"
                                                            name="jenis_gereja_nikah" class="custom-control-input"
                                                            value="luar">
                                                        <label class="custom-control-label" for="nikah_gereja_luar">
                                                            Gereja Luar
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label>Nomor Kartu Nikah</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="nikah_luar_nomor_kartu">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Tanggal Pernikahan</label>
                                                        <input type="date" class="form-control form-control-sm"
                                                            name="nikah_luar_tgl">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Diberkati Oleh</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="nikah_luar_oleh">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Di Gereja</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="nikah_luar_nama_gereja">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Alamat Gereja</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="nikah_luar_alamat_gereja">
                                                    </div>

                                                    <div class="form-group mb-0">
                                                        <label>Kota</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="nikah_luar_kota">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                {{-- BUTTON --}}
                                <div class="text-center mt-4">
                                    <button type="button" class="btn btn-secondary btn-sm px-5 d-none">
                                        <i class="fas fa-save"></i> Simpan Pernikahan
                                    </button>
                                </div>

                            </div>

                            {{-- Data Pindah Masuk Jemaat --}}
                            <div class="tab-pane fade" id="data-pindah" role="tabpanel">

                                {{-- STATUS JEMAAT --}}
                                <div class="form-group row mb-3">
                                    <label class="col-md-2 col-form-label text-md-right">
                                        Status Jemaat :
                                    </label>
                                    <div class="col-md-4">
                                        <select class="form-control form-control-sm" id="status_pindah"
                                            name="status_pindah">
                                            <option value="" selected disabled>-- Pilih --</option>
                                            <option value="tetap">Tetap</option>
                                            <option value="pindah_masuk">Pindah Masuk</option>
                                        </select>
                                    </div>

                                </div>

                                {{-- DETAIL PINDAH MASUK --}}

                                <div id="detail-pindah-masuk" class="w-100" style="display:none;">
                                    <div class="p-3 border rounded mx-auto"
                                        style="background-color: #f9f9f9; max-width: 700px;">

                                        <div class="form-group row mb-3">
                                            <label class="col-md-4 col-form-label text-md-right">No. Surat Pindah
                                                :</label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control form-control-sm"
                                                    name="no_surat_pindah" placeholder="Contoh: 470/123/2025">
                                            </div>
                                        </div>

                                        <div class="form-group row mb-3">
                                            <label class="col-md-4 col-form-label text-md-right">Tanggal Pindah Masuk
                                                :</label>
                                            <div class="col-md-6">
                                                <input type="date" class="form-control form-control-sm"
                                                    name="tgl_pindah_masuk">
                                            </div>
                                        </div>

                                        <div class="form-group row mb-3">
                                            <label class="col-md-4 col-form-label text-md-right">Gereja Asal :</label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control form-control-sm"
                                                    name="gereja_asal" placeholder="Nama gereja asal jemaat">
                                            </div>
                                        </div>

                                        <div class="form-group row mb-3">
                                            <label class="col-md-4 col-form-label text-md-right">Kota Gereja Asal
                                                :</label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control form-control-sm"
                                                    name="kota_gereja_asal" placeholder="Kota gereja asal">
                                            </div>
                                        </div>

                                    </div>
                                </div>


                                {{-- BUTTON --}}
                                <div class="text-center mt-4">
                                    <button type="button" class="btn btn-secondary btn-sm px-5 d-none">
                                        <i class="fas fa-save"></i> Simpan Status Pindah
                                    </button>
                                </div>

                            </div>

                            {{-- Data Wafat Jemaat --}}
                            <div class="tab-pane fade" id="data-kedukaan" role="tabpanel">

                                {{-- STATUS JEMAAT --}}
                                <div class="form-group row mb-3">
                                    <label class="col-md-2 col-form-label text-md-right">
                                        Status Jemaat :
                                    </label>
                                    <div class="col-md-4">
                                        <select class="form-control form-control-sm" id="status_jemaat"
                                            name="status_jemaat">
                                            <option value="" selected disabled>-- Pilih --</option>
                                            <option value="hidup">Hidup</option>
                                            <option value="wafat">Wafat</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- DATA WAFAT --}}
                                <div id="data-wafat" style="display:none;">

                                    <div class="form-group row mb-3">
                                        <label class="col-md-2 col-form-label text-md-right">
                                            Tanggal Wafat :
                                        </label>
                                        <div class="col-md-4">
                                            <input type="date" class="form-control form-control-sm" name="tgl_wafat">
                                        </div>

                                        <label class="col-md-2 col-form-label text-md-right">
                                            No. Surat Kematian :
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control form-control-sm"
                                                name="no_surat_kematian" placeholder="Contoh: 470/123/2025">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-md-2 col-form-label text-md-right">
                                            Keterangan :
                                        </label>
                                        <div class="col-md-10">
                                            <textarea class="form-control form-control-sm" rows="2"
                                                name="keterangan_wafat" placeholder="Opsional"></textarea>
                                        </div>
                                    </div>

                                </div>

                                {{-- BUTTON --}}
                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary btn-sm px-5">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<script>
    // Baptis
    const statusBaptis = document.getElementById('status_baptis');
    const pilihanGereja = document.getElementById('pilihan-gereja');
    if (statusBaptis && pilihanGereja) {
        statusBaptis.addEventListener('change', function () {
            pilihanGereja.style.display = this.value === 'sudah' ? 'block' : 'none';
        });
    }

    // Sidi
    const statusSidi = document.getElementById('status_sidi');
    const pilihanGerejaSidi = document.getElementById('pilihan-gereja-sidi');
    if (statusSidi && pilihanGerejaSidi) {
        statusSidi.addEventListener('change', function () {
            pilihanGerejaSidi.style.display = this.value === 'sudah' ? 'block' : 'none';
        });
    }

    // Pernikahan
    const statusNikah = document.getElementById('status_nikah');
    const pilihanGerejaNikah = document.getElementById('pilihan-gereja-nikah');
    if (statusNikah && pilihanGerejaNikah) {
        statusNikah.addEventListener('change', function () {
            pilihanGerejaNikah.style.display = this.value === 'sudah' ? 'block' : 'none';
        });
    }

    // Kedukaan
    const statusJemaat = document.getElementById('status_jemaat');
    const dataWafat = document.getElementById('data-wafat');
    if (statusJemaat && dataWafat) {
        statusJemaat.addEventListener('change', function () {
            dataWafat.style.display = this.value === 'wafat' ? 'block' : 'none';
        });
    }

    // Pindah Jemaat
    const statusPindahEl = document.getElementById('status_pindah');
    const detailPindahMasukEl = document.getElementById('detail-pindah-masuk');
    if (statusPindahEl && detailPindahMasukEl) {
        statusPindahEl.addEventListener('change', function () {
            detailPindahMasukEl.style.display = this.value === 'pindah_masuk' ? 'block' : 'none';
        });
    }

    // Hubungan keluarga -> show anak_ke when 'Anak' selected
    const hubunganSelect = document.getElementById('hubungan_keluarga');
    const anakWrapper = document.getElementById('anak-ke-wrapper');
    if (hubunganSelect && anakWrapper) {
        hubunganSelect.addEventListener('change', function () {
            anakWrapper.style.display = this.value === 'Anak' ? 'block' : 'none';
        });
    }

    // Robust tab visibility: show only clicked/active pane without relying on Bootstrap events
    document.addEventListener('DOMContentLoaded', function () {
        const tabLinks = Array.from(document.querySelectorAll('a[data-bs-toggle="tab"]'));

        function showPane(targetId) {
            if (!targetId) return;
            document.querySelectorAll('.tab-pane').forEach(p => p.style.display = 'none');
            const el = document.querySelector(targetId);
            if (el) el.style.display = '';
        }

        tabLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                const target = this.getAttribute('href') || this.dataset.bsTarget;
                // allow Bootstrap to toggle classes then enforce visibility shortly after
                setTimeout(() => showPane(target), 30);
            });
        });

        // Initial show: the nav link with 'active' (or first) determines visible pane
        const activeLink = tabLinks.find(l => l.classList.contains('active')) || tabLinks[0];
        if (activeLink) {
            const target = activeLink.getAttribute('href') || activeLink.dataset.bsTarget;
            showPane(target);
        }
    });
</script>
@endsection