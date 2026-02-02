@extends('layouts.main')

@section('title', 'Edit Warta Jemaat')

@section('content')
<section class="section">

    <div class="card">
        <div class="card-header">
            <h4>Edit Warta Jemaat</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.ibadah.warta.update', $warta->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Tanggal --}}
                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" required value="{{ old('tanggal', $warta->tanggal) }}">
                </div>

                {{-- Nama Minggu --}}
                <div class="form-group">
                    <label for="nama_minggu">Nama Minggu</label>
                    <input type="text" name="nama_minggu" id="nama_minggu" class="form-control" placeholder="Contoh: Minggu Epifani" required value="{{ old('nama_minggu', $warta->nama_minggu) }}">
                </div>

                {{-- Pengumuman --}}
                <div class="form-group">
                    <label for="pengumuman">Pengumuman</label>
                    <textarea name="pengumuman" id="pengumuman" class="form-control tinymce" rows="6" placeholder="Isi pengumuman (opsional)">{!! old('pengumuman', $warta->pengumuman) !!}</textarea>
                </div>

                {{-- Tombol --}}
                <div class="form-group text-right">
                    <a href="{{ route('admin.ibadah') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>

</section>
@endsection
