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

            @if($galeri->fotos && $galeri->fotos->count())
            <div class="mt-4">
                @php $first = $galeri->fotos->first(); @endphp
                <div class="aspect-[16/9] overflow-hidden rounded-lg">
                    <img id="mainImage" src="{{ asset('storage/' . $first->foto) }}" alt="{{ $galeri->judul }}"
                        class="w-full h-full object-cover rounded-lg shadow-md">
                </div>
            </div>

            @if($galeri->fotos->count() > 1)
            <div class="mt-4 grid grid-cols-3 sm:grid-cols-6 gap-3">
                @foreach($galeri->fotos as $foto)
                <button type="button" onclick="document.getElementById('mainImage').src='{{ asset('storage/' . $foto->foto) }}'"
                    class="focus:outline-none">
                    <img src="{{ asset('storage/' . $foto->foto) }}" alt="thumb-{{ $loop->index }}"
                        class="w-full h-24 object-cover rounded-lg border hover:opacity-90 transition">
                </button>
                @endforeach
            </div>
            @endif
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
