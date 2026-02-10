<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Wijk;
use App\Models\Keluarga;
use App\Models\Jemaat;
use App\Models\Penatua;
use App\Models\Pendeta;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic counts
        $total_wijks = Wijk::count();
        $total_penatua = Penatua::count();
        $total_pendeta = Pendeta::count();

        // Count of jemaat whose keluarga is assigned to a wijk
        $jemaats_in_wijk_count = Jemaat::whereHas('keluarga', function ($q) {
            $q->whereNotNull('wijk_id');
        })->count();

        // Keluarga per Wijk (labels and values for chart)
        $keluargaPerWijk = DB::table('keluargas')
            ->join('wijks', 'keluargas.wijk_id', '=', 'wijks.id')
            ->select('wijks.nama_wijk as name', DB::raw('count(keluargas.id) as total'))
            ->groupBy('wijks.nama_wijk')
            ->orderBy('wijks.nama_wijk')
            ->get();

        $keluarga_labels = $keluargaPerWijk->pluck('name')->toArray();
        $keluarga_values = $keluargaPerWijk->pluck('total')->toArray();

        // Gender distribution for Jemaat
        $genderData = Jemaat::select('jenis_kelamin', DB::raw('count(*) as total'))
            ->groupBy('jenis_kelamin')
            ->get();

        // Normalize gender labels (common codes: 'L'/'P' or full strings)
        $gender_map = [
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            'l' => 'Laki-laki',
            'p' => 'Perempuan',
        ];

        $gender_labels = [];
        $gender_values = [];
        foreach ($genderData as $row) {
            $raw = $row->jenis_kelamin;
            $label = $gender_map[$raw] ?? ($raw ?: 'Tidak diketahui');
            $gender_labels[] = $label;
            $gender_values[] = (int) $row->total;
        }

        // Ensure arrays exist for the view
        $keluarga_labels = $keluarga_labels ?? [];
        $keluarga_values = $keluarga_values ?? [];
        $gender_labels = $gender_labels ?? [];
        $gender_values = $gender_values ?? [];

        return view('pages.admin.dashboard', compact(
            'total_wijks',
            'total_penatua',
            'total_pendeta',
            'jemaats_in_wijk_count',
            'keluarga_labels',
            'keluarga_values',
            'gender_labels',
            'gender_values'
        ));
    }
}
