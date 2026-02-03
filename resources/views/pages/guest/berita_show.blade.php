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
        <div class="max-w-[1200px] mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Left: list of berita -->
                <div class="md:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm p-4 max-h-[70vh] overflow-y-auto border">
                        <h3 class="text-gray-800 text-lg font-bold mb-4 border-b pb-2">
                            Berita Lainnya
                        </h3>

                        <ul id="beritaList" class="space-y-2">
                            @foreach($beritas as $i => $b)
                            <li>
                                <button data-id="{{ $b->id }}"
                                    class="berita-item w-full text-left p-3 rounded-lg transition
                    hover:bg-indigo-50
                    {{ $b->id == $berita->id ? 'bg-indigo-100 border border-indigo-300' : 'border border-transparent' }}">

                                    <div>
                                        <div class="text-gray-800 font-semibold text-sm leading-snug">
                                            {{ $b->judul }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $b->tanggal ? \Carbon\Carbon::parse($b->tanggal)->translatedFormat('j F
                                            Y') : '-' }}
                                        </div>
                                    </div>
                                </button>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>


                <!-- Right: detail pane -->
                <div class="md:col-span-2">
                    <article id="detailPane" class="bg-white rounded-xl shadow-sm overflow-hidden">

                        <!-- HEADER -->
                        <div class="px-6 pt-6 pb-4 border-b">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h1 id="detailTitle" class="text-2xl font-bold text-indigo-700 leading-snug">{{
                                        $berita->judul ?? 'Berita' }}</h1>
                                    <p id="detailDate" class="text-sm text-gray-500 mt-1">{{ $berita->tanggal ?
                                        \Carbon\Carbon::parse($berita->tanggal)->translatedFormat('j F Y') : '-' }}</p>
                                </div>
                                <a href="{{ route('pages.guest.dashboard') }}"
                                    class="text-sm text-indigo-600 hover:underline">← Kembali</a>
                            </div>
                        </div>

                        <!-- FOTO (render only if exists) -->
                        @if(!empty($berita->foto))
                        <div class="px-6 pt-6">
                            <figure id="detailFotoWrap" class="relative w-full overflow-hidden rounded-lg">
                                <img id="detailFoto" src="{{ asset('storage/' . $berita->foto) }}"
                                    alt="{{ $berita->judul }}" class="w-full h-64 md:h-80 lg:h-96 object-cover rounded-lg shadow-md">
                            </figure>
                        </div>
                        @endif

                        <!-- KONTEN -->
                        <div class="px-6 py-6 prose max-w-none text-gray-800" id="detailBody">
                            {!! $berita->ringkasan !!}
                        </div>

                        <!-- LAMPIRAN -->
                        <div id="detailAttachment" class="px-6 pb-6">
                            @if(!empty($berita->file))
                            <div class="bg-gray-50 border rounded-lg p-4">
                                <h3 class="font-semibold text-sm mb-2">📎 Lampiran</h3>
                                <a id="detailFile" href="{{ asset('storage/' . $berita->file) }}" target="_blank"
                                    class="text-indigo-600 text-sm hover:underline">Lihat File</a>
                            </div>
                            @else
                            <div id="detailFileWrap"></div>
                            @endif
                        </div>

                        <!-- FOOTER INFO -->
                        <div class="px-6 py-4 border-t text-sm text-gray-600">Diposting oleh: <span id="detailAuthor"
                                class="font-medium">{{ optional($berita->user)->name ?? '-' }}</span></div>

                    </article>
                </div>
            </div>
        </div>

        <script>
            const beritas = @json($beritas);

        function renderDetail(item) {
            if (!item) return;

            const titleEl = document.getElementById('detailTitle');
            const dateEl = document.getElementById('detailDate');
            const bodyEl = document.getElementById('detailBody');
            const authorEl = document.getElementById('detailAuthor');
            const fotoWrap = document.getElementById('detailFotoWrap');

            titleEl.innerText = item.judul || '';
            dateEl.innerText = item.tanggal ? new Date(item.tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-';
            bodyEl.innerHTML = item.ringkasan || '';
            authorEl.innerText = item.user ? (item.user.name || '-') : '-';

            // handle photo: create/remove wrapper dynamically so no empty box appears
            if (item.foto) {
                const existingImg = document.getElementById('detailFoto');
                if (existingImg) {
                    existingImg.src = '/storage/' + item.foto;
                    existingImg.alt = item.judul || '';
                } else {
                    // create wrapper + img and insert before the body
                    const bodyEl = document.getElementById('detailBody');
                    const wrapper = document.createElement('div');
                    wrapper.className = 'px-6 pt-6';

                    const figure = document.createElement('figure');
                    figure.id = 'detailFotoWrap';
                    figure.className = 'relative w-full overflow-hidden rounded-lg';

                    const img = document.createElement('img');
                    img.id = 'detailFoto';
                    img.className = 'w-full h-64 md:h-80 lg:h-96 object-cover rounded-lg shadow-md';
                    img.src = '/storage/' + item.foto;
                    img.alt = item.judul || '';

                    figure.appendChild(img);
                    wrapper.appendChild(figure);

                    bodyEl.parentNode.insertBefore(wrapper, bodyEl);
                }
            } else {
                const existingFigure = document.getElementById('detailFotoWrap');
                if (existingFigure) {
                    const parent = existingFigure.parentNode; // this is the px-6 pt-6 wrapper
                    parent.parentNode.removeChild(parent);
                }
            }

            // attachment
            const detailFileWrap = document.getElementById('detailFileWrap');
            const detailFile = document.getElementById('detailFile');
            if (item.file) {
                if (detailFile) {
                    detailFile.href = '/storage/' + item.file;
                } else if (detailFileWrap) {
                    detailFileWrap.innerHTML = `<div class="bg-gray-50 border rounded-lg p-4"><h3 class="font-semibold text-sm mb-2">📎 Lampiran</h3><a href="/storage/${item.file}" target="_blank" class="text-indigo-600 text-sm hover:underline">Lihat File</a></div>`;
                }
            } else {
                if (detailFile) detailFile.remove();
                if (detailFileWrap) detailFileWrap.innerHTML = '';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.berita-item');
            buttons.forEach(btn => {
                    btn.addEventListener('click', function () {
                        buttons.forEach(b => b.classList.remove('bg-blue-600'));
                        btn.classList.add('bg-blue-600');
                    const id = btn.getAttribute('data-id');
                    const item = beritas.find(x => String(x.id) === String(id));
                    renderDetail(item);
                });
            });
        });
        </script>
    </main>


    @include('pages.guest.partialsGuest.footer')

</body>

</html>