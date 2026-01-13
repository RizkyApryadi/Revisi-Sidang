<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HKBP Soposurung</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HKBP Soposurung - Kegiatan</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-50 text-gray-900">
    @include('pages.guest.partialsGuest.navGuest')

    <main class="container mx-auto py-8 px-4">
        <h1 class="text-3xl font-bold mb-6">Kegiatan</h1>

        @if(isset($kegiatans) && $kegiatans->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($kegiatans as $kegiatan)
                    <article class="bg-white rounded-lg shadow-sm overflow-hidden">
                        @if($kegiatan->gambar)
                            <img src="{{ asset('storage/'.$kegiatan->gambar) }}" alt="{{ $kegiatan->judul }}" class="w-full h-44 object-cover">
                        @endif
                        <div class="p-4">
                            <h2 class="text-xl font-semibold mb-1">{{ $kegiatan->judul }}</h2>
                            <p class="text-sm text-gray-500 mb-2">{{ \Illuminate\Support\Carbon::parse($kegiatan->tanggal)->translatedFormat('d F Y') }}@if($kegiatan->waktu) • {{ $kegiatan->waktu }}@endif</p>
                            <p class="text-gray-700">{{ \Illuminate\Support\Str::limit($kegiatan->deskripsi, 150) }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">Tidak ada kegiatan saat ini.</p>
        @endif
    </main>

    @include('pages.guest.partialsGuest.footer')

</body>

</html>