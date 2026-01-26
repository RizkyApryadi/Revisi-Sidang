@extends('layouts.main')
@section('title', 'Create Penatua')

@section('content')
<div class="max-w-4xl mx-auto mt-10 bg-white shadow-md rounded-lg p-8">

    <!-- Judul -->
    <h1 class="text-2xl font-bold mb-8 text-gray-800">
        Tambah Data Penatua
    </h1>

    <form action="{{ route('admin.penatua.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- ================= DATA PENATUA ================= -->
      

        <!-- Nama Lengkap -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">
                Nama Lengkap
            </label>
            <input type="hidden" name="user_id" value="{{ optional($user)->id }}">
            <input type="text"
                   name="nama_lengkap"
                   value="{{ old('nama_lengkap', optional($user)->name) }}"
                   class="w-full border rounded-lg px-4 py-2 bg-gray-100"
                   readonly>
        </div>

        <!-- Jenis Kelamin -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">
                Jenis Kelamin
            </label>
            <select name="jenis_kelamin"
                class="w-full border rounded-lg px-4 py-2 @error('jenis_kelamin') border-red-500 @enderror" required>
                <option value="">-- Pilih Jenis Kelamin --</option>
                <option value="Laki-laki" {{ old('jenis_kelamin')=='Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ old('jenis_kelamin')=='Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
            @error('jenis_kelamin')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Pilih WIJK -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">
                WIJK
            </label>
            @if(isset($wijks) && $wijks->count())
                <select name="wijk_id"
                    class="w-full border rounded-lg px-4 py-2 @error('wijk_id') border-red-500 @enderror" required>
                    <option value="">-- Pilih WIJK --</option>
                    @foreach($wijks as $wijk)
                        <option value="{{ $wijk->id }}" {{ old('wijk_id') == $wijk->id ? 'selected' : '' }}>
                            {{ $wijk->nama_wijk }}
                        </option>
                    @endforeach
                </select>
                @error('wijk_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            @else
                <div class="text-sm text-red-600">Belum ada WIJK. Silakan tambahkan WIJK terlebih dahulu.</div>
            @endif
        </div>

        <!-- Tempat & Tanggal Lahir -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 font-medium mb-2">
                    Tempat Lahir
                </label>
                  <input type="text"
                      name="tempat_lahir"
                      value="{{ old('tempat_lahir') }}"
                      class="w-full border rounded-lg px-4 py-2 @error('tempat_lahir') border-red-500 @enderror"
                      placeholder="Tempat lahir" required>
                @error('tempat_lahir')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">
                    Tanggal Lahir
                </label>
                  <input type="date"
                      name="tanggal_lahir"
                      value="{{ old('tanggal_lahir') }}"
                      class="w-full border rounded-lg px-4 py-2 @error('tanggal_lahir') border-red-500 @enderror" required>
                @error('tanggal_lahir')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Alamat -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">
                Alamat
            </label>
            <textarea rows="3"
                      name="alamat"
                      class="w-full border rounded-lg px-4 py-2 @error('alamat') border-red-500 @enderror"
                      placeholder="Alamat lengkap" required>{{ old('alamat') }}</textarea>
            @error('alamat')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Nomor HP -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">
                Nomor HP
            </label>
                 <input type="text"
                     name="no_hp"
                     value="{{ old('no_hp') }}"
                     class="w-full border rounded-lg px-4 py-2 @error('no_hp') border-red-500 @enderror"
                     placeholder="08xxxxxxxxxx" required>
            @error('no_hp')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Foto -->
        <div class="mb-8">
            <label class="block text-gray-700 font-medium mb-2">
                Foto (opsional)
            </label>
                 <input type="file"
                     name="foto"
                     accept="image/*"
                     class="w-full border rounded-lg px-4 py-2 bg-white @error('foto') border-red-500 @enderror">
            @error('foto')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Button -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.penatua') }}" 
               class="px-6 py-2 bg-gray-300 rounded-lg text-gray-700 hover:bg-gray-400">
                Kembali
            </a>

            <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
