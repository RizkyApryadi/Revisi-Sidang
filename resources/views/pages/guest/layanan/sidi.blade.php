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

        <form class="p-5 space-y-4">

            {{-- FORMULIR PELAJAR SIDI --}}
            <div class="bg-blue-100 border border-blue-200 text-blue-800 px-3 py-2 rounded-md font-medium">
                Formulir Pelajar Sidi
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Nama Calon Pelajar Sidi</label>
                <input type="text" class="w-full md:w-3/4 border rounded-md p-2">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">HP</label>
                <input type="text" class="w-full md:w-3/4 border rounded-md p-2">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Email</label>
                <input type="email" class="w-full md:w-3/4 border rounded-md p-2">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Jenis Kelamin</label>
                <select class="w-full md:w-3/4 border rounded-md p-2">
                    <option>- Pilih -</option>
                    <option>Laki-laki</option>
                    <option>Perempuan</option>
                </select>
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Tempat / Tgl Lahir</label>
                <div class="w-full md:w-3/4 flex gap-2">
                    <input type="text" placeholder="Tempat Lahir" class="w-1/2 border rounded-md p-2">
                    <input type="date" class="w-1/2 border rounded-md p-2">
                </div>
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Tanggal Baptis</label>
                <input type="date" class="w-full md:w-3/4 border rounded-md p-2">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Nama Ayah</label>
                <input type="text" class="w-full md:w-3/4 border rounded-md p-2">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Nama Ibu</label>
                <input type="text" class="w-full md:w-3/4 border rounded-md p-2">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Wijk</label>
                <select class="w-full md:w-3/4 border rounded-md p-2">
                    <option>- Pilih Wijk -</option>
                </select>
            </div>

            <div class="flex flex-wrap items-start">
                <label class="w-full md:w-1/4 font-medium">Alamat</label>
                <textarea class="w-full md:w-3/4 border rounded-md p-2"></textarea>
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Asal Gereja</label>
                <input type="text" class="w-full md:w-3/4 border rounded-md p-2">
            </div>

            <div class="flex flex-wrap items-start">
                <label class="w-full md:w-1/4 font-medium">Maksud dan Tujuan</label>
                <textarea rows="3" class="w-full md:w-3/4 border rounded-md p-2"></textarea>
            </div>

            {{-- KELENGKAPAN ADMINISTRASI --}}
            <div class="bg-blue-100 border border-blue-200 text-blue-800 px-3 py-2 rounded-md font-medium mt-6">
                Kelengkapan Administrasi
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Foto 4x6</label>
                <input type="file" class="w-full md:w-3/4 border rounded-md p-2 bg-white">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Foto 3x4</label>
                <input type="file" class="w-full md:w-3/4 border rounded-md p-2 bg-white">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Surat Baptis</label>
                <input type="file" class="w-full md:w-3/4 border rounded-md p-2 bg-white">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Surat Keterangan dari Gereja ybs</label>
                <input type="file" class="w-full md:w-3/4 border rounded-md p-2 bg-white">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Kartu Keluarga</label>
                <input type="file" class="w-full md:w-3/4 border rounded-md p-2 bg-white">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Surat Pengantar Sintua Wijk</label>
                <input type="file" class="w-full md:w-3/4 border rounded-md p-2 bg-white">
            </div>

            {{-- TOMBOL --}}
            <div class="flex justify-end gap-3 pt-6">
                <a href="#" class="px-4 py-2 bg-gray-300 rounded">KEMBALI</a>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">
                    KIRIM
                </button>
            </div>

        </form>
    </div>
</div>

@include('pages.guest.partialsGuest.footer')
</body>
</html>
