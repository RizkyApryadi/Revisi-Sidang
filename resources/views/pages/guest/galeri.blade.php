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

    <main class="container mx-auto py-10 px-4">
        <h1 class="text-3xl font-bold mb-6">Galeri</h1>

        @if(isset($galeris) && $galeris->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($galeris as $galeri)
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        @if($galeri->foto_path)
                            <img src="{{ asset('storage/'.$galeri->foto_path) }}" alt="{{ $galeri->judul }}" class="w-full h-48 object-cover">
                        @endif
                        <div class="p-4">
                            <h2 class="text-lg font-semibold mb-1">{{ $galeri->judul }}</h2>
                            <p class="text-sm text-gray-500 mb-2">{{ \Illuminate\Support\Carbon::parse($galeri->tanggal)->translatedFormat('d F Y') }}</p>
                            <p class="text-gray-700 text-sm">{{ \Illuminate\Support\Str::limit($galeri->deskripsi, 120) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">Tidak ada foto di galeri saat ini.</p>
        @endif
    </main>

    @include('pages.guest.partialsGuest.footer')

</body>

</html>