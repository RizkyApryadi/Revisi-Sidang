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

    {{-- Informasi pernikahan --}}
    <div class="max-w-5xl mx-auto px-4 mb-4">
        @if(isset($upcoming) && $upcoming->count())
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
            <div class="font-semibold">Informasi Pernikahan:</div>
            <ul class="mt-2 space-y-2">
                @foreach($upcoming as $pendaftaran)
                <li class="flex items-center justify-between bg-white border rounded p-2">
                    <div>
                        <div class="font-medium">{{ $pendaftaran->pria->nama ?? 'Calon Pengantin Pria' }} &amp; {{
                            $pendaftaran->wanita->nama ?? 'Calon Pengantin Wanita' }}</div>
                        <div class="text-sm text-gray-600">
                            {{ optional($pendaftaran->tanggal_pemberkatan)->translatedFormat('l, d M Y') }}
                            @if($pendaftaran->jam_pemberkatan)
                            - {{ \Carbon\Carbon::parse($pendaftaran->jam_pemberkatan)->format('H:i') }}
                            @endif
                        </div>
                    </div>
                    <div class="text-sm text-green-700 font-semibold">Terpublikasi</div>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

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

            <form action="{{ route('guest.layanan.pernikahan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if ($errors->any())
                <div class="bg-red-100 border border-red-200 text-red-800 p-3 rounded">
                    <strong>Terdapat kesalahan pada pengisian form:</strong>
                    <ul class="mb-0 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="p-5 space-y-6">

                    {{-- CALON PENGANTIN PRIA --}}
                    <div class="bg-blue-100 border border-blue-200 text-blue-800 px-3 py-2 rounded font-medium">
                        Calon Pengantin Pria
                    </div>

                    <div class="row md:flex md:items-center mt-2">
                        <label class="w-full md:w-1/4 font-medium">Nomor KK</label>
                        <input type="text" name="pria[nomor_kk]" required class="w-full md:w-3/4 border rounded p-2"
                            value="{{ old('pria.nomor_kk') }}">
                    </div>


                    <div class="flex flex-wrap items-center">
                        <label class="w-full md:w-1/4 font-medium">Nama Lengkap</label>
                        <input type="text" name="pria[nama_lengkap]" required class="w-full md:w-3/4 border rounded p-2"
                            value="{{ old('pria.nama_lengkap') }}">
                    </div>

                    <div class="flex flex-wrap items-center">
                        <label class="w-full md:w-1/4 font-medium">HP</label>
                        <input type="text" name="pria[no_hp]" class="w-full md:w-3/4 border rounded p-2"
                            value="{{ old('pria.no_hp') }}">
                    </div>

                    <div class="flex flex-wrap items-center">
                        <label class="w-full md:w-1/4 font-medium">Email</label>
                        <input type="email" name="pria[email]" class="w-full md:w-3/4 border rounded p-2"
                            value="{{ old('pria.email') }}">
                    </div>

                    <div class="flex flex-wrap items-center">
                        <label class="w-full md:w-1/4 font-medium">Tempat Lahir</label>
                        <input type="text" name="pria[tempat_lahir]" class="w-full md:w-3/4 border rounded p-2"
                            value="{{ old('pria.tempat_lahir') }}">
                    </div>

                    <div class="flex flex-wrap items-center">
                        <label class="w-full md:w-1/4 font-medium">Tgl. Lahir</label>
                        <input type="date" name="pria[tanggal_lahir]" class="w-full md:w-3/4 border rounded p-2"
                            value="{{ old('pria.tanggal_lahir') }}">
                    </div>

                    <div class="flex flex-wrap items-center">
                        <label class="w-full md:w-1/4 font-medium">Asal Gereja</label>
                        <input type="text" name="pria[asal_gereja]" class="w-full md:w-3/4 border rounded p-2"
                            value="{{ old('pria.asal_gereja') }}">
                    </div>

                    <div class="flex flex-wrap items-center">
                        <label class="w-full md:w-1/4 font-medium">Wijk</label>
                        <input type="text" name="pria[wijk]" class="w-full md:w-3/4 border rounded p-2"
                            value="{{ old('pria.wijk') }}">
                    </div>

                    <div class="flex flex-wrap items-start">
                        <label class="w-full md:w-1/4 font-medium">Alamat Rumah</label>
                        <textarea name="pria[alamat]"
                            class="w-full md:w-3/4 border rounded p-2">{{ old('pria.alamat') }}</textarea>
                    </div>

                    <div class="row md:flex md:items-center mt-2">
                        <label class="w-full md:w-1/4 font-medium">Scan/Foto KK</label>
                        <input type="file" name="pria[kk_file]" class="w-full md:w-3/4 border rounded p-2 bg-white">
                    </div>

                    {{-- CALON PENGANTIN PEREMPUAN --}}
                    <div class="bg-blue-100 border border-blue-200 text-blue-800 px-3 py-2 rounded font-medium mt-6">
                        Calon Pengantin Perempuan
                    </div>

                    {{-- (FIELD SAMA DENGAN PRIA) --}}
                    {{-- Nama --}}

                    <div class="row md:flex md:items-center mt-2">
                        <label class="w-full md:w-1/4 font-medium">Nomor KK</label>
                        <input type="text" name="wanita[nomor_kk]" required class="w-full md:w-3/4 border rounded p-2"
                            value="{{ old('wanita.nomor_kk') }}">
                    </div>

                    <div class="flex flex-wrap items-center">
                        <label class="w-full md:w-1/4 font-medium">Nama Lengkap</label>
                        <input type="text" name="wanita[nama_lengkap]" required
                            class="w-full md:w-3/4 border rounded p-2" value="{{ old('wanita.nama_lengkap') }}">
                    </div>

                    <div class="flex flex-wrap items-center">
                        <label class="w-full md:w-1/4 font-medium">HP</label>
                        <input type="text" name="wanita[no_hp]" class="w-full md:w-3/4 border rounded p-2"
                            value="{{ old('wanita.no_hp') }}">
                    </div>

                    <div class="flex flex-wrap items-center">
                        <label class="w-full md:w-1/4 font-medium">Email</label>
                        <input type="email" name="wanita[email]" class="w-full md:w-3/4 border rounded p-2"
                            value="{{ old('wanita.email') }}">
                    </div>

                    <div class="flex flex-wrap items-center">
                        <label class="w-full md:w-1/4 font-medium">Tempat Lahir</label>
                        <input type="text" name="wanita[tempat_lahir]" class="w-full md:w-3/4 border rounded p-2"
                            value="{{ old('wanita.tempat_lahir') }}">
                    </div>

                    <div class="flex flex-wrap items-center">
                        <label class="w-full md:w-1/4 font-medium">Tgl. Lahir</label>
                        <input type="date" name="wanita[tanggal_lahir]" class="w-full md:w-3/4 border rounded p-2"
                            value="{{ old('wanita.tanggal_lahir') }}">
                    </div>

                    <div class="flex flex-wrap items-center">
                        <label class="w-full md:w-1/4 font-medium">Asal Gereja</label>
                        <input type="text" name="wanita[asal_gereja]" class="w-full md:w-3/4 border rounded p-2"
                            value="{{ old('wanita.asal_gereja') }}">
                    </div>

                    <div class="flex flex-wrap items-center">
                        <label class="w-full md:w-1/4 font-medium">Wijk</label>
                        <input type="text" name="wanita[wijk]" class="w-full md:w-3/4 border rounded p-2"
                            value="{{ old('wanita.wijk') }}">
                    </div>

                    <div class="flex flex-wrap items-start">
                        <label class="w-full md:w-1/4 font-medium">Alamat Rumah</label>
                        <textarea name="wanita[alamat]"
                            class="w-full md:w-3/4 border rounded p-2">{{ old('wanita.alamat') }}</textarea>
                    </div>


                    <div class="row md:flex md:items-center mt-2">
                        <label class="w-full md:w-1/4 font-medium">Scan/Foto KK</label>
                        <input type="file" name="wanita[kk_file]" class="w-full md:w-3/4 border rounded p-2 bg-white">
                    </div>

                    {{-- JADWAL --}}
                    <div class="bg-blue-100 border border-blue-200 text-blue-800 px-3 py-2 rounded font-medium mt-6">
                        Jadwal Pernikahan
                    </div>

                    <div class="font-semibold">Penandatanganan Perjanjian Nikah (Partumpolon)</div>

                    <div class="flex gap-2">
                        <input type="date" name="partumpolon_tanggal" class="w-1/2 border rounded p-2"
                            value="{{ old('partumpolon_tanggal') }}">
                        <input type="time" name="partumpolon_jam" class="w-1/2 border rounded p-2"
                            value="{{ old('partumpolon_jam') }}">
                    </div>

                    <textarea name="partumpolon_keterangan" class="w-full border rounded p-2"
                        placeholder="Keterangan">{{ old('partumpolon_keterangan') }}</textarea>

                    <div class="font-semibold mt-4">Pemberkatan Nikah (Pamasumasuon)</div>

                    <div class="flex gap-2">
                        <input type="date" name="pamasumasuon_tanggal" class="w-1/2 border rounded p-2"
                            value="{{ old('pamasumasuon_tanggal') }}">
                        <input type="time" name="pamasumasuon_jam" class="w-1/2 border rounded p-2"
                            value="{{ old('pamasumasuon_jam') }}">
                    </div>

                    <textarea name="pamasumasuon_keterangan" class="w-full border rounded p-2"
                        placeholder="Keterangan">{{ old('pamasumasuon_keterangan') }}</textarea>

                    {{-- KELENGKAPAN --}}
                    <div
                        class="bg-blue-100 border border-blue-200 text-blue-800 px-3 py-2 rounded font-medium mt-6 mb-4">
                        Kelengkapan Administrasi
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="block mb-1 font-medium">
                                Surat Keterangan Gereja Asal
                            </label>
                            <input type="file" name="surat_gereja_asal"
                                class="w-full border rounded px-3 py-2 bg-white">
                        </div>

                        <div>
                            <label class="block mb-1 font-medium">
                                Surat Baptis Calon Pengantin Pria
                            </label>
                            <input type="file" name="surat_baptis_pria"
                                class="w-full border rounded px-3 py-2 bg-white">
                        </div>

                        <div>
                            <label class="block mb-1 font-medium">
                                Surat Baptis Calon Pengantin Perempuan
                            </label>
                            <input type="file" name="surat_baptis_wanita"
                                class="w-full border rounded px-3 py-2 bg-white">
                        </div>

                        <div>
                            <label class="block mb-1 font-medium">
                                Surat Sidi Calon Pengantin Pria
                            </label>
                            <input type="file" name="surat_sidi_pria" class="w-full border rounded px-3 py-2 bg-white">
                        </div>

                        <div>
                            <label class="block mb-1 font-medium">
                                Surat Sidi Calon Pengantin Perempuan
                            </label>
                            <input type="file" name="surat_sidi_wanita"
                                class="w-full border rounded px-3 py-2 bg-white">
                        </div>

                        <div>
                            <label class="block mb-1 font-medium">
                                Foto Bersama 4x6 Calon Pengantin
                            </label>
                            <input type="file" name="foto_bersama" class="w-full border rounded px-3 py-2 bg-white"
                                accept="image/*">
                        </div>

                        <div>
                            <label class="block mb-1 font-medium">
                                Surat Pengantar Sintua Wijk
                            </label>
                            <input type="file" name="surat_pengantar_sintua"
                                class="w-full border rounded px-3 py-2 bg-white">
                        </div>

                    </div>

                    {{-- TOMBOL --}}
                    <div class="flex justify-end gap-3 pt-6">
                        <a href="#" class="px-4 py-2 bg-gray-300 rounded">
                            KEMBALI
                        </a>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            KIRIM
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    @include('pages.guest.partialsGuest.footer')
</body>

</html>