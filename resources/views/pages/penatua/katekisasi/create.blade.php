@extends('layouts.main')
@section('title', 'Pendaftaran Sidi')

@section('content')
<section class="section">

    <div class="section-header">
        <h1>Pendaftaran Sidi</h1>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-9 col-sm-12">
            <div class="card shadow-sm border">
                <div class="card-body px-4 py-4">

                    <form action="{{ route('penatua.pelayanan.katekisasi.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        @if(request('katekisasi_id'))
                            <input type="hidden" name="katekisasi_id" value="{{ request('katekisasi_id') }}">
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- DATA CALON SIDI --}}
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-user"></i> Data Calon Sidi
                        </h5>

                        <div class="form-group">
                            <label>Nama Lengkap Calon</label>
                            <input type="text" class="form-control" name="nama" value="{{ old('nama') }}">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nomor HP</label>
                                    <input type="text" class="form-control" name="no_hp" value="{{ old('no_hp') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jenis Kelamin</label>
                                    <select class="form-control" name="jenis_kelamin">
                                        <option value="">-- Pilih --</option>
                                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tempat Lahir</label>
                                    <input type="text" class="form-control" name="tempat_lahir" value="{{ old('tempat_lahir') }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            <input type="date" class="form-control" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Ayah</label>
                                    <input type="text" class="form-control" name="nama_ayah" value="{{ old('nama_ayah') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Ibu</label>
                                    <input type="text" class="form-control" name="nama_ibu" value="{{ old('nama_ibu') }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Wijk</label>
                            <input type="text" class="form-control" name="wijk" value="{{ old('wijk', $wijkName ?? '') }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea class="form-control" name="alamat" rows="3">{{ old('alamat') }}</textarea>
                        </div>

                        <hr>

                        {{-- KELENGKAPAN ADMINISTRASI --}}
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-file-upload"></i> Kelengkapan Administrasi
                        </h5>

                        <div class="form-group">
                            <label>Upload Foto 4x6</label>
                            <input type="file" class="form-control" name="foto_4x6">
                        </div>

                        <div class="form-group">
                            <label>Upload Foto 3x4</label>
                            <input type="file" class="form-control" name="foto_3x4">
                        </div>

                        <div class="form-group">
                            <label>Upload Surat Baptis</label>
                            <input type="file" class="form-control" name="surat_baptis">
                        </div>

                        <div class="form-group">
                            <label>Upload Kartu Keluarga</label>
                            <input type="file" class="form-control" name="kartu_keluarga">
                        </div>

                        <div class="form-group">
                            <label>Upload Surat Pengantar Sintua</label>
                            <input type="file" class="form-control" name="surat_pengantar_sintua">
                        </div>

                        <hr>

                        {{-- JENIS PENDAFTAR --}}
                        <div class="form-group">
                            <label>Jenis Pendaftar</label>
                            <select class="form-control" name="jenis_pendaftar">
                                <option value="">-- Pilih --</option>
                                <option value="external" {{ old('jenis_pendaftar') == 'external' ? 'selected' : '' }}>External (Non-Jemaat)</option>
                                <option value="internal" {{ old('jenis_pendaftar') == 'internal' ? 'selected' : '' }}>Internal (Jemaat HKBP)</option>
                            </select>
                        </div>

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
