@extends('layouts.main')
@section('title', 'Edit Pelayanan Ibadah')

@section('content')
<section class="section">

    <div class="section-header">
        <h1>Edit Pelayanan Ibadah</h1>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Form Ibadah</h4>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.pelayan.update', $ibadah->id) }}">
                @csrf
                @method('PUT')

                {{-- Nama Minggu (select dari wartas) --}}
                <div class="form-group">
                    <label>Nama Minggu</label>
                    <select id="wartaSelect" name="warta_id" class="form-control" required>
                        <option value="">-- Pilih Nama Minggu --</option>
                        @foreach($wartas as $w)
                            <option value="{{ $w->id }}" {{ optional($ibadah)->warta_id == $w->id ? 'selected' : '' }}>{{ $w->nama_minggu }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Jam Ibadah (terisi berdasarkan Nama Minggu yang dipilih) --}}
                <div class="form-group">
                    <label>Jam Ibadah</label>
                    <select id="ibadahSelect" name="ibadah_id" class="form-control" required>
                        <option value="">-- Pilih Jam Ibadah --</option>
                        @foreach($wartasForJs as $w)
                            @foreach($w['ibadahs'] as $i)
                                <option value="{{ $i['id'] }}" {{ $ibadah->id == $i['id'] ? 'selected' : '' }}>{{ substr($i['waktu'],0,5) }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>

                {{-- Pelayanan Dinamis --}}
                <div class="form-group">
                    <label>Pelayan Ibadah</label>

                    <div id="pelayanan-wrapper">

                        @if($pelayan && $pelayan->count())
                            @foreach($pelayan as $p)
                                <div class="row pelayanan-item mb-2">
                                    <div class="col-md-5">
                                        <input type="text"
                                               name="jenis_pelayanan[]"
                                               class="form-control"
                                               value="{{ $p->jenis_pelayanan }}"
                                               placeholder="Jenis Pelayanan (Pendeta, Liturgis)">
                                    </div>

                                    <div class="col-md-5">
                                        <input type="text"
                                               name="petugas[]"
                                               class="form-control"
                                               value="{{ $p->petugas }}"
                                               placeholder="Nama Petugas">
                                    </div>

                                    <div class="col-md-2">
                                        <button type="button"
                                                class="btn btn-danger btn-block remove-pelayanan">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="row pelayanan-item mb-2">
                                <div class="col-md-5">
                                    <input type="text" name="jenis_pelayanan[]" class="form-control" placeholder="Jenis Pelayanan (Pendeta, Liturgis)">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="petugas[]" class="form-control" placeholder="Nama Petugas">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-block remove-pelayanan">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endif

                    </div>

                    <button type="button" class="btn btn-sm btn-success mt-2" id="add-pelayanan">
                        <i class="fas fa-plus"></i> Tambah Pelayanan
                    </button>
                </div>

                {{-- Tombol --}}
                <div class="text-right">
                    <a href="{{ route('admin.pelayan') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>

</section>
@endsection

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        if (window.pelayananInit) return;
        window.pelayananInit = true;

        const wrapper = document.getElementById('pelayanan-wrapper');
        const addBtn  = document.getElementById('add-pelayanan');

        addBtn.addEventListener('click', function () {
            const row = document.createElement('div');
            row.className = 'row pelayanan-item mb-2';

            row.innerHTML = `
                <div class="col-md-5">
                    <input type="text" name="jenis_pelayanan[]" class="form-control"
                           placeholder="Jenis Pelayanan (Pendeta, Liturgis)">
                </div>

                <div class="col-md-5">
                    <input type="text" name="petugas[]" class="form-control"
                           placeholder="Nama Petugas">
                </div>

                <div class="col-md-2">
                    <button type="button"
                            class="btn btn-danger btn-block remove-pelayanan">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;

            wrapper.appendChild(row);
        });

        wrapper.addEventListener('click', function (e) {
            if (e.target.closest('.remove-pelayanan')) {
                e.target.closest('.pelayanan-item').remove();
            }
        });

        // --- populate ibadah times based on selected warta ---
        const wartas = @json($wartasForJs);

        const wartaSelect = document.getElementById('wartaSelect');
        const ibadahSelect = document.getElementById('ibadahSelect');

        function populateIbadahOptions(wartaId) {
            ibadahSelect.innerHTML = '<option value="">-- Pilih Jam Ibadah --</option>';
            const w = wartas.find(x => x.id == wartaId);
            if (!w) return;
            w.ibadahs.forEach(i => {
                const opt = document.createElement('option');
                opt.value = i.id;
                opt.textContent = (i.waktu || '').toString().substring(0,5);
                ibadahSelect.appendChild(opt);
            });
        }

        wartaSelect.addEventListener('change', function () {
            populateIbadahOptions(this.value);
        });

    });
</script>
@endpush
