<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HKBP Soposurung - Perjanjian & Pemberkatan Nikah</title>
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
            Formulir Perjanjian Nikah dan Pemberkatan Nikah
        </div>

        <div class="p-5 space-y-6">

            {{-- CALON PENGANTIN PRIA --}}
            <div class="bg-blue-100 border border-blue-200 text-blue-800 px-3 py-2 rounded font-medium">
                Calon Pengantin Pria
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Nama Lengkap</label>
                <input type="text" class="w-full md:w-3/4 border rounded p-2">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">HP</label>
                <input type="text" class="w-full md:w-3/4 border rounded p-2">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Email</label>
                <input type="email" class="w-full md:w-3/4 border rounded p-2">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Tempat Lahir</label>
                <input type="text" class="w-full md:w-3/4 border rounded p-2">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Tgl. Lahir</label>
                <input type="date" class="w-full md:w-3/4 border rounded p-2">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Tgl. Baptis</label>
                <input type="date" class="w-full md:w-3/4 border rounded p-2">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Tgl. Sidi</label>
                <input type="date" class="w-full md:w-3/4 border rounded p-2">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Nama Ayah</label>
                <input type="text" class="w-full md:w-3/4 border rounded p-2">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Nama Ibu</label>
                <input type="text" class="w-full md:w-3/4 border rounded p-2">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Asal Gereja</label>
                <input type="text" class="w-full md:w-3/4 border rounded p-2">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Wijk</label>
                <input type="text" class="w-full md:w-3/4 border rounded p-2">
            </div>

            <div class="flex flex-wrap items-start">
                <label class="w-full md:w-1/4 font-medium">Alamat Rumah</label>
                <textarea class="w-full md:w-3/4 border rounded p-2"></textarea>
            </div>

            {{-- CALON PENGANTIN PEREMPUAN --}}
            <div class="bg-blue-100 border border-blue-200 text-blue-800 px-3 py-2 rounded font-medium mt-6">
                Calon Pengantin Perempuan
            </div>

            {{-- (FIELD SAMA DENGAN PRIA) --}}
            {{-- Nama --}}
            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Nama Lengkap</label>
                <input type="text" class="w-full md:w-3/4 border rounded p-2">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">HP</label>
                <input type="text" class="w-full md:w-3/4 border rounded p-2">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Email</label>
                <input type="email" class="w-full md:w-3/4 border rounded p-2">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Tempat Lahir</label>
                <input type="text" class="w-full md:w-3/4 border rounded p-2">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Tgl. Lahir</label>
                <input type="date" class="w-full md:w-3/4 border rounded p-2">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Tgl. Baptis</label>
                <input type="date" class="w-full md:w-3/4 border rounded p-2">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Tgl. Sidi</label>
                <input type="date" class="w-full md:w-3/4 border rounded p-2">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Nama Ayah</label>
                <input type="text" class="w-full md:w-3/4 border rounded p-2">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Nama Ibu</label>
                <input type="text" class="w-full md:w-3/4 border rounded p-2">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Asal Gereja</label>
                <input type="text" class="w-full md:w-3/4 border rounded p-2">
            </div>

            <div class="flex flex-wrap items-center">
                <label class="w-full md:w-1/4 font-medium">Wijk</label>
                <input type="text" class="w-full md:w-3/4 border rounded p-2">
            </div>

            <div class="flex flex-wrap items-start">
                <label class="w-full md:w-1/4 font-medium">Alamat Rumah</label>
                <textarea class="w-full md:w-3/4 border rounded p-2"></textarea>
            </div>

            {{-- JADWAL --}}
            <div class="bg-blue-100 border border-blue-200 text-blue-800 px-3 py-2 rounded font-medium mt-6">
                Jadwal Pernikahan
            </div>

            <div class="font-semibold">Penandatanganan Perjanjian Nikah (Partumpolon)</div>

            <div class="flex gap-2">
                <input type="date" class="w-1/2 border rounded p-2">
                <input type="time" class="w-1/2 border rounded p-2">
            </div>

            <textarea class="w-full border rounded p-2" placeholder="Keterangan"></textarea>

            <div class="font-semibold mt-4">Pemberkatan Nikah (Pamasumasuon)</div>

            <div class="flex gap-2">
                <input type="date" class="w-1/2 border rounded p-2">
                <input type="time" class="w-1/2 border rounded p-2">
            </div>

            <textarea class="w-full border rounded p-2" placeholder="Keterangan"></textarea>

            {{-- KELENGKAPAN --}}
            <div class="bg-blue-100 border border-blue-200 text-blue-800 px-3 py-2 rounded font-medium mt-6">
                Kelengkapan Administrasi
            </div>

            <input type="file" class="w-full">
            <input type="file" class="w-full">
            <input type="file" class="w-full">
            <input type="file" class="w-full">
            <input type="file" class="w-full">
            <input type="file" class="w-full">
            <input type="file" class="w-full">
            <input type="file" class="w-full">

            {{-- TOMBOL --}}
            <div class="flex justify-end gap-3 pt-4">
                <a href="#" class="px-4 py-2 bg-gray-300 rounded">KEMBALI</a>
                <button class="px-4 py-2 bg-green-600 text-white rounded">KIRIM</button>
            </div>

        </div>
    </div>
</div>

@include('pages.guest.partialsGuest.footer')
</body>
</html>
