@extends('layouts.main')

@section('title', 'Tambah Warta Jemaat')

@section('content')
<section class="section">

    <div class="card">
        <div class="card-header">
            <h4>Tambah Warta Jemaat</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.ibadah.warta.store') }}" method="POST">
                @csrf

                {{-- Tanggal --}}
                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal"
                           class="form-control"
                           required>
                </div>

                {{-- Nama Minggu --}}
                <div class="form-group">
                    <label for="nama_minggu">Nama Minggu</label>
                    <input type="text" name="nama_minggu" id="nama_minggu"
                           class="form-control"
                           placeholder="Contoh: Minggu Epifani"
                           required>
                </div>

                {{-- deskripsi --}}
                <div class="form-group">
                    <label for="deskripsi">deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi"
                              class="form-control"
                              rows="4"
                              placeholder="Isi deskripsi"></textarea>
                </div>

                {{-- Tombol --}}
                <div class="form-group text-right">
                    <a href="{{ route('admin.ibadah') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>

</section>
@endsection
