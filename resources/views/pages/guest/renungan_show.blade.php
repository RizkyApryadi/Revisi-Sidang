<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renungan - {{ $renungan->judul }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .prose p img { display: inline-block; max-width: 48%; margin-right: 0.5rem; vertical-align: middle; }
        .prose p:only-child img { display: block; max-width: 100%; margin-right: 0; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900">
    @include('pages.guest.partialsGuest.navGuest')

    <main class="container mx-auto px-4 pt-24 pb-12">
        <div class="max-w-[900px] mx-auto">
            <article class="bg-white rounded-xl shadow-sm p-6">
                <h1 class="text-2xl font-bold text-slate-800">{{ $renungan->judul }}</h1>
                <div class="text-sm text-gray-500 mt-2 mb-4">{{ $renungan->tanggal ? \Carbon\Carbon::parse($renungan->tanggal)->translatedFormat('j F Y') : '-' }} • {{ optional($renungan->pendeta)->nama ?? '-' }}</div>

                <div class="prose max-w-none text-gray-800">{!! $renungan->konten !!}</div>

                <div class="mt-6">
                    <a href="{{ route('guest.renungan') }}" class="text-sm text-indigo-600 hover:underline">← Kembali ke daftar Renungan</a>
                </div>
            </article>
        </div>
    </main>

    @include('pages.guest.partialsGuest.footer')
</body>
</html>
