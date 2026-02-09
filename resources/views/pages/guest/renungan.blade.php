

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
<section class="pt-24 pb-14 bg-slate-50">
    <div class="w-full px-8 lg:px-24">

        <h1 class="text-3xl md:text-4xl font-bold text-center mb-10 text-slate-800">
            Renungan Harian
        </h1>

        @if(!isset($renungans))
            @php $renungans = collect(); @endphp
        @endif

        {{-- GRID LIST --}}
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">

            @forelse($renungans as $r)
                <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 border border-slate-200 flex flex-col">

                    <div class="p-6 flex-1 flex flex-col">
                        <h2 class="text-xl font-semibold text-slate-800 mb-2">
                            {{ $r->judul }}
                        </h2>

                        <div class="text-sm text-slate-500 mb-3">
                            {{ $r->tanggal ? \Carbon\Carbon::parse($r->tanggal)->translatedFormat('j F Y') : '-' }} • {{ optional($r->pendeta)->nama ?? '-' }}
                        </div>

                        <p class="text-slate-600 leading-relaxed mb-5 line-clamp-3">
                            {!! \Illuminate\Support\Str::limit(strip_tags($r->konten), 160, '...') !!}
                        </p>

                        <div class="mt-auto">
                            <a href="{{ route('guest.renungan.show', $r->id) }}" class="w-full inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2 rounded-lg transition">
                                Baca Renungan
                            </a>
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-3 text-center text-slate-600 py-12">Belum ada renungan yang dipublikasikan.</div>
            @endforelse

        </div>

    </div>
</section>



    @include('pages.guest.partialsGuest.footer')

</body>

</html>