<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HKBP Soposurung - Berita</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-50 text-gray-900">
    @include('pages.guest.partialsGuest.navGuest')

    <main class="container mx-auto py-10 px-4 pt-24">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-indigo-700">{{ $berita->judul ?? 'Berita' }}</h1>
                    <p class="text-sm text-gray-500 mt-1">{{ $berita->tanggal ? \Carbon\Carbon::parse($berita->tanggal)->translatedFormat('j F Y') : '-' }}</p>
                </div>
                <a href="{{ route('pages.guest.dashboard') }}" class="text-sm text-indigo-600">Kembali</a>
            </div>

            <div class="mt-4 text-gray-800">
                <p>{!! nl2br(e($berita->ringkasan)) !!}</p>
            </div>

            @if(!empty($berita->file))
            <div class="mt-6">
                <h3 class="font-semibold">Lampiran</h3>
                <a href="{{ asset('storage/' . $berita->file) }}" target="_blank" class="text-indigo-600 underline">Lihat File</a>
            </div>
            @endif

            <div class="mt-6 text-sm text-gray-600">Diposting oleh: {{ optional($berita->user)->name ?? '-' }}</div>
        </div>
    </main>

    @include('pages.guest.partialsGuest.footer')

</body>

</html>
