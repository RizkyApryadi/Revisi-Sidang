<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HKBP Soposurung - Pendaftaran Sidi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="pt-24 bg-gray-50 text-gray-900">
    @include('pages.guest.partialsGuest.navGuest')

    <div class="max-w-5xl mx-auto px-4">

        {{-- HEADER --}}
        <div class="bg-indigo-700 text-white p-3 mb-4 flex justify-between items-center rounded">
            <strong>HURIA KRISTEN BATAK PROTESTAN</strong>
            <span>{{ now()->translatedFormat('l, d M Y') }}</span>
        </div>

        {{-- FORM --}}
        <div class="bg-white border border-green-500 rounded-md shadow-sm">
            <div class="bg-green-600 text-white text-center py-2 rounded-t-md font-semibold">
                Formulir Pendaftaran Sidi
            </div>

            <form action="{{ route('guest.layanan.sidi.store') }}" method="POST" enctype="multipart/form-data"
                class="p-5 space-y-4">
                @csrf

                @if(request('katekisasi_id'))
                <input type="hidden" name="katekisasi_id" value="{{ request('katekisasi_id') }}">
                @endif

                @if(!empty($katekisasis) && count($katekisasis))
                <div class="flex flex-wrap items-center">
                    <label class="w-full md:w-1/4 font-medium">Pendaftaran Katekisasi   </label>
                    <select name="katekisasi_id" class="w-full md:w-3/4 border rounded-md p-2">
                        <option value="">-- Pilih --</option>
                        @foreach($katekisasis as $k)
                        <option value="{{ $k->id }}" {{ old('katekisasi_id')==$k->id ? 'selected' : '' }}>
                            {{ $k->periode_ajaran }} @if(!empty($k->tanggal_mulai)) ({{
                            \Carbon\Carbon::parse($k->tanggal_mulai)->translatedFormat('d M Y') }}) @endif
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif

                @if ($errors->any())
                <div class="bg-red-100 border border-red-200 text-red-800 p-3 rounded">
                    <ul class="mb-0 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- DATA CALON SIDI --}}
                <div class="bg-blue-100 border border-blue-200 text-blue-800 px-3 py-2 rounded-md font-medium">
                    Data Calon Sidi
                </div>

                {{-- NOMOR KK --}}
                <div class="flex flex-wrap items-center">
                    <label class="w-full md:w-1/4 font-medium">Nomor Kartu Keluarga</label>
                    <input type="text" class="w-full md:w-3/4 border rounded-md p-2" name="nomor_kk"
                        value="{{ old('nomor_kk') }}" placeholder="16 digit Nomor KK">
                </div>
                <div class="flex flex-wrap items-center">
                    <label class="w-full md:w-1/4 font-medium">Nama Lengkap Calon</label>
                    <input type="text" class="w-full md:w-3/4 border rounded-md p-2" name="nama"
                        value="{{ old('nama') }}">
                </div>

                <div class="flex flex-wrap items-center">
                    <label class="w-full md:w-1/4 font-medium">Nomor HP</label>
                    <input type="text" class="w-full md:w-3/4 border rounded-md p-2" name="no_hp"
                        value="{{ old('no_hp') }}">
                </div>

                <div class="flex flex-wrap items-center">
                    <label class="w-full md:w-1/4 font-medium">Email</label>
                    <input type="email" class="w-full md:w-3/4 border rounded-md p-2" name="email"
                        value="{{ old('email') }}">
                </div>

                <div class="flex flex-wrap items-center">
                    <label class="w-full md:w-1/4 font-medium">Jenis Kelamin</label>
                    <select class="w-full md:w-3/4 border rounded-md p-2" name="jenis_kelamin">
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin')=='Laki-laki' ? 'selected' : '' }}>Laki-laki
                        </option>
                        <option value="Perempuan" {{ old('jenis_kelamin')=='Perempuan' ? 'selected' : '' }}>Perempuan
                        </option>
                    </select>
                </div>

                <div class="flex flex-wrap items-center">
                    <label class="w-full md:w-1/4 font-medium">Tempat Lahir</label>
                    <input type="text" class="w-full md:w-3/4 border rounded-md p-2" name="tempat_lahir"
                        value="{{ old('tempat_lahir') }}">
                </div>

                <div class="flex flex-wrap items-center">
                    <label class="w-full md:w-1/4 font-medium">Tanggal Lahir</label>
                    <input type="date" class="w-full md:w-3/4 border rounded-md p-2" name="tanggal_lahir"
                        value="{{ old('tanggal_lahir') }}">
                </div>

                <div class="flex flex-wrap items-center">
                    <label class="w-full md:w-1/4 font-medium">Wijk</label>
                    <input type="text" class="w-full md:w-3/4 border rounded-md p-2" name="wijk"
                        value="{{ old('wijk', $wijkName ?? '') }}" readonly>
                </div>

                <div class="flex flex-wrap items-start">
                    <label class="w-full md:w-1/4 font-medium">Alamat</label>
                    <textarea class="w-full md:w-3/4 border rounded-md p-2" name="alamat"
                        rows="3">{{ old('alamat') }}</textarea>
                </div>

                <hr class="border-t my-2">

                {{-- KELENGKAPAN ADMINISTRASI --}}
                <div class="bg-blue-100 border border-blue-200 text-blue-800 px-3 py-2 rounded-md font-medium mt-2">
                    Kelengkapan Administrasi
                </div>

                <div class="flex flex-wrap items-center">
                    <label class="w-full md:w-1/4 font-medium">Upload Foto 4x6</label>
                    <input type="file" class="w-full md:w-3/4 border rounded-md p-2 bg-white" name="foto_4x6">
                </div>

                <div class="flex flex-wrap items-center">
                    <label class="w-full md:w-1/4 font-medium">Upload Foto 3x4</label>
                    <input type="file" class="w-full md:w-3/4 border rounded-md p-2 bg-white" name="foto_3x4">
                </div>

                <div class="flex flex-wrap items-center">
                    <label class="w-full md:w-1/4 font-medium">Upload Surat Baptis</label>
                    <input type="file" class="w-full md:w-3/4 border rounded-md p-2 bg-white" name="surat_baptis">
                </div>

                <div class="flex flex-wrap items-center">
                    <label class="w-full md:w-1/4 font-medium">Upload Kartu Keluarga</label>
                    <input type="file" class="w-full md:w-3/4 border rounded-md p-2 bg-white" name="kartu_keluarga">
                </div>

                <div class="flex flex-wrap items-center">
                    <label class="w-full md:w-1/4 font-medium">Upload Surat Gereja Asal</label>
                    <input type="file" class="w-full md:w-3/4 border rounded-md p-2 bg-white" name="surat_gereja_asal">
                </div>

                <div class="flex flex-wrap items-center">
                    <label class="w-full md:w-1/4 font-medium">Upload Surat Pengantar Sintua</label>
                    <input type="file" class="w-full md:w-3/4 border rounded-md p-2 bg-white"
                        name="surat_pengantar_sintua">
                </div>

                <hr class="border-t my-2">

                <div class="flex justify-end gap-3 pt-4">
                    <a href="#" class="px-4 py-2 bg-gray-300 rounded">KEMBALI</a>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Simpan Pendaftaran</button>
                </div>

            </form>
        </div>
    </div>

    @include('pages.guest.partialsGuest.footer')
</body>

</html>