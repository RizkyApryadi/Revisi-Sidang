@extends('layouts.main')
@section('title', 'Pendaftaran Baptisan')

@section('content')
<section class="section">

    <div class="section-header">
        <h1>Pendaftaran Baptisan Kudus</h1>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="#" method="POST">
                @csrf

                {{-- PILIH TIPE BAPTISAN --}}
                <div class="form-group">
                    <label>Tipe Baptisan</label>
                    <select class="form-control" name="tipe_baptisan" id="tipe_baptisan">
                        <option value="">-- Pilih Tipe Baptisan --</option>
                        <option value="anak">Baptisan Anak</option>
                        <option value="dewasa">Baptisan Dewasa</option>
                    </select>
                </div>

                <hr>

                {{-- ========================= --}}
                {{-- BAPTISAN ANAK --}}
                {{-- ========================= --}}
                <div id="form-anak" hidden>

                    {{-- PEMBUNGKUS BIAR FORM LEBIH KECIL & TENGAH --}}
                    <div class="row justify-content-center">
                        <div class="col-lg-8 col-md-9 col-sm-12">

                            {{-- HEADER --}}
                            <div class="mb-4">
                                <h4 class="text-primary">
                                    <i class="fas fa-child"></i> Pendaftaran Baptisan Anak
                                </h4>
                                <p class="text-muted mb-0">
                                    Mohon mengisi data dengan lengkap dan benar.
                                </p>
                            </div>

                            {{-- DATA ORANG TUA --}}
                            <div class="card mb-4 border">
                                <div class="card-header bg-light">
                                    <strong><i class="fas fa-users"></i> Data Orang Tua</strong>
                                </div>
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nama Ayah</label>
                                                <input type="text" class="form-control" name="nama_ayah">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nama Ibu</label>
                                                <input type="text" class="form-control" name="nama_ibu">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>No. HP</label>
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
                                        <label>Alamat Lengkap</label>
                                        <textarea class="form-control" name="alamat" rows="3"></textarea>
                                    </div>

                                </div>
                            </div>

                            {{-- DATA ANAK --}}
                            <div class="card mb-4 border">
                                <div class="card-header bg-light">
                                    <strong><i class="fas fa-baby"></i> Data Anak yang Dibaptis</strong>
                                </div>
                                <div class="card-body">

                                    <div class="form-group">
                                        <label>Nama Anak</label>
                                        <input type="text" class="form-control" name="nama_anak">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Jenis Kelamin</label>
                                                <select class="form-control" name="jenis_kelamin">
                                                    <option value="">-- Pilih --</option>
                                                    <option value="Laki-laki">Laki-laki</option>
                                                    <option value="Perempuan">Perempuan</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tempat Lahir</label>
                                                <input type="text" class="form-control" name="tempat_lahir">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Tanggal Lahir</label>
                                        <input type="date" class="form-control" name="tanggal_lahir">
                                    </div>

                                </div>
                            </div>

                            {{-- JADWAL --}}
                            <div class="card mb-4 border">
                                <div class="card-header bg-light">
                                    <strong><i class="fas fa-calendar-alt"></i> Jadwal Baptisan</strong>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-0">
                                        <label>Tanggal Kebaktian Baptisan</label>
                                        <input type="date" class="form-control" name="tanggal_ibadah">
                                    </div>
                                </div>
                            </div>

                            {{-- ADMINISTRASI --}}
                            <div class="card mb-4 border">
                                <div class="card-header bg-light">
                                    <strong><i class="fas fa-file-alt"></i> Kelengkapan Administrasi</strong>
                                </div>
                                <div class="card-body">

                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" id="akte"
                                            name="akte_pernikahan">
                                        <label class="custom-control-label" for="akte">Akte Pernikahan</label>
                                    </div>

                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" id="sintua"
                                            name="surat_sintua">
                                        <label class="custom-control-label" for="sintua">Surat Pengantar Sintua</label>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="hkbp"
                                            name="surat_hkbp_lain">
                                        <label class="custom-control-label" for="hkbp">
                                            Surat Pengantar HKBP lain
                                        </label>
                                    </div>

                                </div>
                            </div>

                            {{-- SUBMIT --}}
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save"></i> Simpan Pendaftaran
                                </button>
                            </div>

                        </div>
                    </div>
                </div>



                {{-- ========================= --}}
                {{-- BAPTISAN DEWASA --}}
                {{-- ========================= --}}
                <div id="form-dewasa" hidden>

                    <div class="row justify-content-center">
                        <div class="col-lg-8 col-md-9 col-sm-12">

                            {{-- HEADER --}}
                            <div class="mb-4">
                                <h4 class="text-primary">
                                    <i class="fas fa-user"></i> Pendaftaran Baptisan Dewasa
                                </h4>
                                <p class="text-muted mb-0">
                                    Formulir ini diperuntukkan bagi calon baptisan dewasa sesuai tata gereja HKBP.
                                </p>
                            </div>

                            {{-- DATA PRIBADI --}}
                            <div class="card mb-4 border">
                                <div class="card-header bg-light">
                                    <strong><i class="fas fa-id-card"></i> Data Pribadi Calon Baptisan</strong>
                                </div>
                                <div class="card-body">

                                    <div class="form-group">
                                        <label>Nama Lengkap</label>
                                        <input type="text" class="form-control" name="nama_calon">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Jenis Kelamin</label>
                                                <select class="form-control" name="jenis_kelamin">
                                                    <option value="">-- Pilih --</option>
                                                    <option value="Laki-laki">Laki-laki</option>
                                                    <option value="Perempuan">Perempuan</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Status Perkawinan</label>
                                                <select class="form-control" name="status_perkawinan">
                                                    <option value="">-- Pilih --</option>
                                                    <option value="Belum Menikah">Belum Menikah</option>
                                                    <option value="Menikah">Menikah</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tempat Lahir</label>
                                                <input type="text" class="form-control" name="tempat_lahir">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tanggal Lahir</label>
                                                <input type="date" class="form-control" name="tanggal_lahir">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            {{-- DATA KEGEREJAAN --}}
                            <div class="card mb-4 border">
                                <div class="card-header bg-light">
                                    <strong><i class="fas fa-church"></i> Data Kegerejaan</strong>
                                </div>
                                <div class="card-body">

                                    <div class="form-group">
                                        <label>Asal Gereja (jika pindahan)</label>
                                        <input type="text" class="form-control" name="asal_gereja">
                                    </div>

                                    <div class="form-group">
                                        <label>Sudah Mengikuti Katekisasi?</label>
                                        <select class="form-control" name="sudah_katekisasi">
                                            <option value="">-- Pilih --</option>
                                            <option value="1">Sudah</option>
                                            <option value="0">Belum</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Sudah Sidi?</label>
                                        <select class="form-control" name="sudah_sidi">
                                            <option value="">-- Pilih --</option>
                                            <option value="1">Sudah</option>
                                            <option value="0">Belum</option>
                                        </select>
                                    </div>

                                </div>
                            </div>

                            {{-- ADMINISTRASI --}}
                            <div class="card mb-4 border">
                                <div class="card-header bg-light">
                                    <strong><i class="fas fa-file-alt"></i> Kelengkapan Administrasi</strong>
                                </div>
                                <div class="card-body">

                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" id="kate"
                                            name="surat_katekisasi">
                                        <label class="custom-control-label" for="kate">
                                            Surat Keterangan Katekisasi
                                        </label>
                                    </div>

                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" id="sintua-dewasa"
                                            name="surat_sintua">
                                        <label class="custom-control-label" for="sintua-dewasa">
                                            Surat Pengantar Sintua
                                        </label>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="gereja-lain"
                                            name="surat_gereja_lain">
                                        <label class="custom-control-label" for="gereja-lain">
                                            Surat Pengantar dari Gereja Asal
                                        </label>
                                    </div>

                                </div>
                            </div>

                            {{-- JADWAL --}}
                            <div class="card mb-4 border">
                                <div class="card-header bg-light">
                                    <strong><i class="fas fa-calendar-alt"></i> Jadwal Baptisan</strong>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-0">
                                        <label>Tanggal Kebaktian Baptisan</label>
                                        <input type="date" class="form-control" name="tanggal_ibadah">
                                    </div>
                                </div>
                            </div>

                            {{-- SUBMIT --}}
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save"></i> Simpan Pendaftaran
                                </button>
                            </div>

                        </div>
                    </div>
                </div>


                {{-- ========================= --}}
                {{-- KONTAK & JADWAL --}}
                {{-- ========================= --}}
                <div id="form-umum" hidden>

                    <hr>
                    <h5><i class="fas fa-calendar"></i> Kontak & Jadwal</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No HP</label>
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
                        <label>Tanggal Kebaktian Baptisan</label>
                        <input type="date" class="form-control" name="tanggal_ibadah">
                    </div>

                    <div class="text-right">
                        <button class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Pendaftaran
                        </button>
                    </div>

                </div>

            </form>

        </div>
    </div>

</section>

{{-- SCRIPT TOGGLE --}}
<script>
    document.getElementById('tipe_baptisan').addEventListener('change', function () {
        const anak   = document.getElementById('form-anak');
        const dewasa = document.getElementById('form-dewasa');

        anak.hidden   = true;
        dewasa.hidden = true;

        if (this.value === 'anak') {
            anak.hidden = false; // anak includes its own contact/jadwal/admin
        }

        if (this.value === 'dewasa') {
            dewasa.hidden = false;
            umum.hidden   = false; // dewasa uses umum for contact/jadwal/admin
        }
    });
</script>
@endsection