<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HKBP Soposurung - Jadwal</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-50 text-gray-900">
    @include('pages.guest.partialsGuest.navGuest')

    <main class="container mx-auto py-10 px-4">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 class="text-2xl font-bold text-indigo-700">{{ optional($ibadah->warta)->nama_minggu ?? 'Ibadah' }}</h2>
                    <p class="text-sm text-gray-500">{{ optional($ibadah->warta)->tanggal ? \Carbon\Carbon::parse(optional($ibadah->warta)->tanggal)->translatedFormat('j F Y') : '-' }} • {{ $ibadah->waktu ?? '-' }} WIB</p>
                </div>
                <a href="{{ route('guest.jadwal') }}" class="text-sm text-indigo-600">Kembali ke Jadwal</a>
            </div>

            {{-- PDF (embedded) --}}
            @if(!empty($ibadah->file))
            <div class="mb-6">
                <div class="text-sm text-gray-600 mb-2">File:</div>
                <div class="w-full h-[720px] rounded overflow-hidden border">
                    <iframe src="{{ route('guest.jadwal.file', $ibadah->id) }}" class="w-full h-full" frameborder="0"></iframe>
                </div>
            </div>
            @endif

            {{-- PDF text extracted --}}
            @if(!empty($ibadah->pdf_text))
            <div class="mb-6">
                <h3 class="font-semibold text-lg mb-2">Teks PDF</h3>
                <div class="prose max-w-none text-gray-800">
                    {!! nl2br(e($ibadah->pdf_text)) !!}
                </div>
            </div>
            @endif

            {{-- Tema & Keterangan --}}
            @if(!empty($ibadah->tema))
            <div class="mb-4">
                <h3 class="font-semibold">Tema</h3>
                <p class="text-gray-700">{{ $ibadah->tema }}</p>
            </div>
            @endif

            @if(!empty($ibadah->keterangan))
            <div class="mb-4">
                <h3 class="font-semibold">Keterangan</h3>
                <p class="text-gray-700">{!! nl2br(e($ibadah->keterangan)) !!}</p>
            </div>
            @endif

            {{-- Pendeta & Lokasi --}}
            <div class="mb-4">
                <h3 class="font-semibold">Pendeta</h3>
                <p class="text-gray-700">{{ optional($ibadah->pendeta)->nama_lengkap ?? '-' }}</p>
            </div>

            @if(!empty($ibadah->lokasi) || !empty($ibadah->keterangan_tambahan))
            <div class="mb-4">
                @if(!empty($ibadah->lokasi))
                <div class="text-sm text-gray-600"><strong>Lokasi:</strong> {{ $ibadah->lokasi }}</div>
                @endif
                @if(!empty($ibadah->keterangan_tambahan))
                <div class="text-sm text-gray-600"><strong>Catatan:</strong> {{ $ibadah->keterangan_tambahan }}</div>
                @endif
            </div>
            @endif

            {{-- Warta (paling bawah) --}}
            @if(optional($ibadah->warta))
            <div class="mt-8 pt-4 border-t">
                <h4 class="text-lg font-semibold">Warta: {{ optional($ibadah->warta)->nama_minggu }}</h4>
                <p class="text-sm text-gray-500">Tanggal: {{ optional($ibadah->warta)->tanggal ? \Carbon\Carbon::parse(optional($ibadah->warta)->tanggal)->translatedFormat('j F Y') : '-' }}</p>
                @if(!empty(optional($ibadah->warta)->deskripsi))
                <div class="mt-2 text-gray-800">{!! nl2br(e(optional($ibadah->warta)->deskripsi)) !!}</div>
                @endif
            </div>
            @endif

        </div>
    </main>

    @include('pages.guest.partialsGuest.footer')

</body>

</html>