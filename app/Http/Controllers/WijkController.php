<?php

namespace App\Http\Controllers;

use App\Models\Wijk;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WijkController extends Controller
{
    /**
     * Display the wijk index page.
     */
    public function index()
    {
        $wijks = Wijk::with('penatuas')->orderBy('id')->get();
        return view('pages.admin.MasterData.wijk.index', compact('wijks'));
    }

    /**
     * Store a newly created Wijk in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_wijk' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ]);

        Wijk::create($validated);

        return redirect()->route('admin.wijk')->with('success', 'WIJK berhasil ditambahkan.');
    }

    /**
     * Update the specified Wijk.
     */
    public function update(Request $request, $id)
    {
        $wijk = Wijk::findOrFail($id);

        $validated = $request->validate([
            'nama_wijk' => ['required', 'string', 'max:255', Rule::unique('wijks', 'nama_wijk')->ignore($wijk->id)],
            'keterangan' => ['nullable', 'string'],
        ]);

        $wijk->update($validated);

        return redirect()->route('admin.wijk')->with('success', 'WIJK berhasil diperbarui.');
    }

    /**
     * Remove the specified Wijk.
     */
    public function destroy($id)
    {
        $wijk = Wijk::findOrFail($id);
        $wijk->delete();

        return redirect()->route('admin.wijk')->with('success', 'WIJK berhasil dihapus.');
    }
}
