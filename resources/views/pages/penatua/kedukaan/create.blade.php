@extends('layouts.main')
@section('title', 'Tambah Kedukaan')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4>Form Tambah Kedukaan</h4>
        </div>
        <div class="card-body">
            <form action="#" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nama Jemaat</label>
                    <input type="text" name="nama" class="form-control" />
                </div>
                <div class="form-group">
                    <label>Umur</label>
                    <input type="number" name="umur" class="form-control" />
                </div>
                <div class="form-group">
                    <label>Tanggal Wafat</label>
                    <input type="date" name="tanggal_wafat" class="form-control" />
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control"></textarea>
                </div>
                <div class="form-group mt-3">
                    <button class="btn btn-primary">Simpan</button>
                    <a href="{{ route('penatua.kedukaan') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
