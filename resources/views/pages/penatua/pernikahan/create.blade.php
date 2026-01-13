@extends('layouts.main')
@section('title', 'Perjanjian & Pemberkatan Nikah')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Perjanjian Nikah & Pemberkatan Nikah</h1>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9 col-md-10 col-sm-12">
            <div class="card shadow-sm border">
                <div class="card-body px-4 py-4">

                    {{-- TABS --}}
                    <ul class="nav nav-tabs mb-4" id="formTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pria-tab" data-bs-toggle="tab" data-bs-target="#pria" type="button" role="tab">Data Pria</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="wanita-tab" data-bs-toggle="tab" data-bs-target="#wanita" type="button" role="tab">Data Wanita</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="acara-tab" data-bs-toggle="tab" data-bs-target="#acara" type="button" role="tab">Acara</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="administrasi-tab" data-bs-toggle="tab" data-bs-target="#administrasi" type="button" role="tab">Administrasi</button>
                        </li>
                    </ul>

                    {{-- FORM --}}
                    <form action="{{ route('penatua.pelayanan.pernikahan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="tab-content" id="formTabsContent">

                            {{-- DATA PRIA --}}
                            <div class="tab-pane fade show active" id="pria" role="tabpanel">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-male"></i> Calon Pengantin Laki-laki
                                </h5>
                                <div class="form-group mb-2">
                                    <label>Nama Lengkap</label>
                                    <input type="text" class="form-control" name="pria_nama">
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label>No. HP</label>
                                        <input type="text" class="form-control" name="pria_hp">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="pria_email">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label>Tempat Lahir</label>
                                        <input type="text" class="form-control" name="pria_tempat_lahir">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Tanggal Lahir</label>
                                        <input type="date" class="form-control" name="pria_tanggal_lahir">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label>Tanggal Baptis</label>
                                        <input type="date" class="form-control" name="pria_tanggal_baptis">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Tanggal Sidi</label>
                                        <input type="date" class="form-control" name="pria_tanggal_sidi">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label>Nama Ayah</label>
                                        <input type="text" class="form-control" name="pria_nama_ayah">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Nama Ibu</label>
                                        <input type="text" class="form-control" name="pria_nama_ibu">
                                    </div>
                                </div>
                                <div class="form-group mb-2">
                                    <label>Asal Gereja</label>
                                    <input type="text" class="form-control" name="pria_asal_gereja">
                                </div>
                                <div class="form-group mb-2">
                                    <label>Wijk</label>
                                    <input type="text" class="form-control" name="pria_wijk">
                                </div>
                                <div class="form-group mb-2">
                                    <label>Alamat</label>
                                    <textarea class="form-control" name="pria_alamat" rows="3"></textarea>
                                </div>
                            </div>

                            {{-- DATA WANITA --}}
                            <div class="tab-pane fade" id="wanita" role="tabpanel">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-female"></i> Calon Pengantin Perempuan
                                </h5>
                                <div class="form-group mb-2">
                                    <label>Nama Lengkap</label>
                                    <input type="text" class="form-control" name="wanita_nama">
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label>No. HP</label>
                                        <input type="text" class="form-control" name="wanita_hp">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="wanita_email">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label>Tempat Lahir</label>
                                        <input type="text" class="form-control" name="wanita_tempat_lahir">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Tanggal Lahir</label>
                                        <input type="date" class="form-control" name="wanita_tanggal_lahir">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label>Tanggal Baptis</label>
                                        <input type="date" class="form-control" name="wanita_tanggal_baptis">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Tanggal Sidi</label>
                                        <input type="date" class="form-control" name="wanita_tanggal_sidi">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label>Nama Ayah</label>
                                        <input type="text" class="form-control" name="wanita_nama_ayah">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Nama Ibu</label>
                                        <input type="text" class="form-control" name="wanita_nama_ibu">
                                    </div>
                                </div>
                                <div class="form-group mb-2">
                                    <label>Asal Gereja</label>
                                    <input type="text" class="form-control" name="wanita_asal_gereja">
                                </div>
                                <div class="form-group mb-2">
                                    <label>Wijk</label>
                                    <input type="text" class="form-control" name="wanita_wijk">
                                </div>
                                <div class="form-group mb-2">
                                    <label>Alamat</label>
                                    <textarea class="form-control" name="wanita_alamat" rows="3"></textarea>
                                </div>
                            </div>

                            {{-- ACARA --}}
                            <div class="tab-pane fade" id="acara" role="tabpanel">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-calendar"></i> Rencana Acara
                                </h5>

                                <div class="card mb-3 border">
                                    <div class="card-header bg-light"><strong>Perjanjian Nikah (Partumpolon)</strong></div>
                                    <div class="card-body">
                                        <div class="row mb-2">
                                            <div class="col-md-6"><label>Tanggal</label><input type="date" class="form-control" name="partumpolon_tanggal"></div>
                                            <div class="col-md-6"><label>Jam</label><input type="time" class="form-control" name="partumpolon_jam"></div>
                                        </div>
                                        <div class="form-group mt-2">
                                            <label>Keterangan</label>
                                            <textarea class="form-control" name="partumpolon_keterangan" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-3 border">
                                    <div class="card-header bg-light"><strong>Pemberkatan Nikah (Pamasumasuon)</strong></div>
                                    <div class="card-body">
                                        <div class="row mb-2">
                                            <div class="col-md-6"><label>Tanggal</label><input type="date" class="form-control" name="pamasumasuon_tanggal"></div>
                                            <div class="col-md-6"><label>Jam</label><input type="time" class="form-control" name="pamasumasuon_jam"></div>
                                        </div>
                                        <div class="form-group mt-2">
                                            <label>Keterangan</label>
                                            <textarea class="form-control" name="pamasumasuon_keterangan" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ADMINISTRASI --}}
                            <div class="tab-pane fade" id="administrasi" role="tabpanel">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-file-upload"></i> Kelengkapan Administrasi
                                </h5>
                                <div class="form-group mb-2">
                                    <label>Surat Keterangan Gereja Asal</label>
                                    <input type="file" class="form-control" name="surat_gereja_asal">
                                </div>
                                <div class="form-group mb-2">
                                    <label>Surat Baptis Calon Pengantin Pria</label>
                                    <input type="file" class="form-control" name="surat_baptis_pria">
                                </div>
                                <div class="form-group mb-2">
                                    <label>Surat Baptis Calon Pengantin Wanita</label>
                                    <input type="file" class="form-control" name="surat_baptis_wanita">
                                </div>
                                <div class="form-group mb-2">
                                    <label>Surat Sidi Calon Pengantin Pria</label>
                                    <input type="file" class="form-control" name="surat_sidi_pria">
                                </div>
                                <div class="form-group mb-2">
                                    <label>Surat Sidi Calon Pengantin Wanita</label>
                                    <input type="file" class="form-control" name="surat_sidi_wanita">
                                </div>
                                <div class="form-group mb-2">
                                    <label>Foto Bersama 4x6</label>
                                    <input type="file" class="form-control" name="foto_bersama">
                                </div>
                                <div class="form-group mb-2">
                                    <label>Surat Pengantar Sintua Wijk</label>
                                    <input type="file" class="form-control" name="surat_pengantar_sintua">
                                </div>
                            </div>

                        </div>

                        {{-- SUBMIT --}}
                        <div class="text-right mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save"></i> Simpan Pendaftaran
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

</section>
@endsection
