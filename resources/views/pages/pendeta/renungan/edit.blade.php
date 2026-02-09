@extends('layouts.main')
@section('title', 'Edit Renungan')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Renungan</h1>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('pendeta.renungan.update', $renungan->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Judul</label>
                        <input type="text" name="judul" class="form-control" value="{{ old('judul', $renungan->judul) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', optional($renungan->tanggal)->toDateString()) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konten</label>
                        <textarea name="konten" class="form-control tinymce" rows="6">{!! old('konten', $renungan->konten) !!}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="draft" {{ old('status', $renungan->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="publish" {{ old('status', $renungan->status) == 'publish' ? 'selected' : '' }}>Publish</option>
                        </select>
                    </div>

                    <button class="btn btn-primary">Simpan</button>
                    <a href="{{ route('pendeta.renungan.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
