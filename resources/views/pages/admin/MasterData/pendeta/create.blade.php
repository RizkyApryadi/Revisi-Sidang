@extends('layouts.main')
@section('title', 'Create Pendeta')

@section('content')
<div class="max-w-4xl mx-auto mt-10 bg-white shadow-md rounded-lg p-8">

    <!-- Judul -->
    <h1 class="text-2xl font-bold mb-8 text-gray-800">
        Tambah Data Pendeta
    </h1>

    <form action="{{ route('admin.pendeta.store') }}" method="POST" enctype="multipart/form-data">
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
            <select name="jenis_kelamin" class="w-full border rounded-lg px-4 py-2">
                <option>-- Pilih Jenis Kelamin --</option>
                <option>Laki-laki</option>
                <option>Perempuan</option>
            </select>
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
                       class="w-full border rounded-lg px-4 py-2"
                       placeholder="Tempat lahir">
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">
                    Tanggal Lahir
                </label>
                <input type="date"
                       name="tanggal_lahir"
                       value="{{ old('tanggal_lahir') }}"
                       class="w-full border rounded-lg px-4 py-2">
            </div>
        </div>

        <!-- Alamat -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">
                Alamat
            </label>
            <textarea rows="3"
                      name="alamat"
                      class="w-full border rounded-lg px-4 py-2"
                      placeholder="Alamat lengkap">{{ old('alamat') }}</textarea>
        </div>

        <!-- Nomor HP -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">
                Nomor HP
            </label>
            <input type="text"
                   name="no_hp"
                   value="{{ old('no_hp') }}"
                   class="w-full border rounded-lg px-4 py-2"
                   placeholder="08xxxxxxxxxx">
        </div>

        <!-- Foto -->
        <div class="mb-8">
            <label class="block text-gray-700 font-medium mb-2">
                Foto
            </label>
            <input type="file"
                   name="foto"
                   class="w-full border rounded-lg px-4 py-2 bg-white">
        </div>

        <!-- Button -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.pendeta') }}" 
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
