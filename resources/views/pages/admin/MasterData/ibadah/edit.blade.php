@extends('layouts.main')
@section('title', 'Edit Warta')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Edit Warta</h4>
            <a href="{{ route('admin.ibadah.warta.show', $warta->id) }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.ibadah.warta.update', $warta->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', optional($warta->tanggal)->format('Y-m-d')) }}">
                    @error('tanggal') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Minggu</label>
                    <input type="text" name="nama_minggu" class="form-control" value="{{ old('nama_minggu', $warta->nama_minggu) }}">
                    @error('nama_minggu') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Pengumuman / Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="6">{{ old('deskripsi', $warta->deskripsi) }}</textarea>
                    @error('deskripsi') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</section>
@endsection
