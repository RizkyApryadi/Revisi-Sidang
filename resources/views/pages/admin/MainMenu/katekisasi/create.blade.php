@extends('layouts.main')
@section('title', 'Tambah Katekisasi')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Tambah Informasi Katekisasi</h1>
        </p>
    </div>

    <div class="card">
        <div class="card-body">
            @if(!empty($errorMessage))
            <div class="alert alert-danger">Terjadi kesalahan: {{ $errorMessage }}</div>
            @endif
            <form method="POST" action="{{ route('admin.pelayanan.katekisasi.store') }}">
                @csrf

                <div class="form-group mb-4">
                    <label for="periode_ajaran">Periode Ajaran</label>
                    <select id="periode_ajaran" name="periode_ajaran" class="form-control">
                        <option value="">-- Pilih Periode --</option>
                        @php
                        $tahun_awal = 2024; // default mulai periode pertama
                        $jumlah_periode = 5; // tampilkan 5 periode
                        @endphp

                        @for ($i = 0; $i < $jumlah_periode; $i++) @php $periode_mulai=$tahun_awal + $i;
                            $periode_selesai=$periode_mulai + 1; $periode=$periode_mulai . '/' . $periode_selesai;
                            @endphp <option value="{{ $periode }}">{{ $periode }}</option>
                            @endfor
                    </select>
                </div>


                <div class="row">
                    <div class="col-md-6">
                        {{-- Tanggal Mulai --}}
                        <div class="form-group mb-4">
                            <label for="tanggal_mulai">Tanggal Mulai Katekisasi</label>
                            <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        {{-- Tanggal Selesai --}}
                        <div class="form-group mb-4">
                            <label for="tanggal_selesai">Tanggal Selesai Katekisasi</label>
                            <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="form-control">
                        </div>
                    </div>
                </div>

                {{-- Pendaftaran dibuka sampai --}}
                <div class="form-group mb-4">
                    <label for="tanggal_pendaftaran_tutup">Pendaftaran Dibuka Sampai</label>
                    <input type="date" id="tanggal_pendaftaran_tutup" name="tanggal_pendaftaran_tutup"
                        class="form-control">
                    <small class="form-text text-muted">
                        Tentukan tanggal terakhir jemaat bisa mendaftar untuk periode katekisasi ini.
                    </small>
                </div>

                {{-- Pendeta --}}
                <div class="form-group mb-4">
                    <label for="pendeta">Pendeta Pembina</label>
                    <select id="pendeta" name="pendeta_id" class="form-control">
                        <option value="">-- Pilih Pendeta --</option>
                        @if(isset($pendetas) && $pendetas->count())
                        @foreach($pendetas as $p)
                        <option value="{{ $p->id }}">{{ $p->nama_lengkap }}</option>
                        @endforeach
                        @else
                        <option value="">Tidak ada pendeta terdaftar</option>
                        @endif
                    </select>
                </div>

                {{-- Catatan / Deskripsi --}}
                <div class="form-group mb-4">
                    <label for="deskripsi">Catatan / Keterangan</label>
                    <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3"
                        placeholder="Opsional: info tambahan untuk katekisasi"></textarea>
                </div>

                {{-- Tombol --}}
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan Informasi
                </button>
                <button type="reset" class="btn btn-secondary">Batal</button>

            </form>
        </div>
    </div>
</section>
@endsection