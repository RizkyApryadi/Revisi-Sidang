

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
    

        @if($renungans->count())
            @php
                $first = $renungans->first();
                $renunganArray = $renungans->map(function($r){
                    return [
                        'id' => $r->id,
                        'judul' => $r->judul,
                        'tanggal' => $r->tanggal ? $r->tanggal->format('d F Y') : '',
                        'pendeta' => optional($r->pendeta)->nama_lengkap ?? 'Pendeta',
                        'konten' => nl2br(e($r->konten)),
                    ];
                });
            @endphp

            <div class="grid md:grid-cols-4 gap-6 mb-8">
                <div class="md:col-span-1">
                    <h3 class="text-lg font-semibold mb-4">Daftar Renungan</h3>
                    <div id="renungan-grid" class="space-y-3">
                        @foreach($renungans as $i => $r)
                            <button data-index="{{ $i }}" class="renungan-item w-full text-left p-3 rounded-lg border hover:bg-slate-50" type="button">
                                <div class="font-medium text-slate-800">{{ $r->judul }}</div>
                                <div class="text-sm text-slate-500">{{ optional($r->tanggal)->format('d F Y') }}</div>
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="md:col-span-3">
                    <div id="renungan-detail" class="bg-white rounded-xl shadow-md border border-slate-200 p-8">
                        <h2 id="detail-judul" class="text-2xl md:text-3xl font-semibold text-slate-800 mb-4 text-center">{{ $first->judul }}</h2>
                        <div id="detail-meta" class="text-sm text-slate-500 mb-6 text-center">{{ optional($first->tanggal)->format('d F Y') }} &nbsp;•&nbsp; {{ optional($first->pendeta)->nama_lengkap ?? 'Pendeta' }}</div>
                        <div id="detail-konten" class="prose prose-xl prose-slate max-w-none leading-relaxed text-justify mx-auto">{!! nl2br(e($first->konten)) !!}</div>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                {{ $renungans->links() }}
            </div>
        @else
            <div class="text-center text-slate-600 italic">
                Belum ada renungan yang dipublikasikan.
            </div>
        @endif

        <script>
            const renunganData = @json($renunganArray);

            function setActiveIndex(idx){
                document.querySelectorAll('.renungan-item').forEach((el,i)=>{
                    if(i === idx) el.classList.add('bg-slate-100','ring','ring-slate-200'); else el.classList.remove('bg-slate-100','ring','ring-slate-200');
                });
            }

            function showRenungan(idx){
                const data = renunganData[idx];
                if(!data) return;
                document.getElementById('detail-judul').textContent = data.judul;
                document.getElementById('detail-meta').textContent = data.tanggal + ' • ' + data.pendeta;
                document.getElementById('detail-konten').innerHTML = data.konten;
                setActiveIndex(idx);
            }

            document.addEventListener('DOMContentLoaded', function(){
                document.querySelectorAll('#renungan-grid .renungan-item').forEach(btn=>{
                    btn.addEventListener('click', function(){ showRenungan(parseInt(this.dataset.index,10)); });
                });
                setActiveIndex(0);
            });
        </script>
    </div>
</section>


    @include('pages.guest.partialsGuest.footer')

</body>

</html>