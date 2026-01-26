<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HKBP Soposurung - Galeri</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-50 text-gray-900">
    @include('pages.guest.partialsGuest.navGuest')

    <main class="container mx-auto py-10 px-4 pt-24">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-indigo-700">{{ $galeri->judul ?? 'Galeri' }}</h1>
                    <p class="text-sm text-gray-500 mt-1">{{ $galeri->tanggal ? \Carbon\Carbon::parse($galeri->tanggal)->translatedFormat('j F Y') : '-' }}</p>
                </div>
                <a href="{{ route('guest.galeri') }}" class="text-sm text-indigo-600">Kembali</a>
            </div>

            @if($galeri->foto_path)
            <div class="mt-4">
                <img src="{{ asset('storage/' . $galeri->foto_path) }}" alt="{{ $galeri->judul }}" class="w-full rounded-lg shadow-md">
            </div>
            @endif

            @if(!empty($galeri->deskripsi))
            <div class="mt-4 text-gray-800">
                <p>{!! nl2br(e($galeri->deskripsi)) !!}</p>
            </div>
            @endif

            <div class="mt-6 text-sm text-gray-600">Diposting: {{ optional($galeri->user)->name ?? '-' }}</div>
        </div>
    </main>

    @include('pages.guest.partialsGuest.footer')

</body>

</html>
