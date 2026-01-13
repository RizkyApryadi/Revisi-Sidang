<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Penatua;
use App\Models\Wijk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PenatuaController extends Controller
{
    /**
     * Return number of penatua accounts pending and total profiles.
     */
    public function pendingCount()
    {
        $pending = User::where('role', 'penatua')
            ->whereDoesntHave('penatua')
            ->count();

        $total = Penatua::count();

        return response()->json(['pending' => $pending, 'total' => $total]);
    }

    /**
     * Display a listing of Penatua.
     */
    public function index()
    {
        $penatuas = Penatua::with('wijk')->orderBy('id')->get();
        $wijks = Wijk::all();

        return view('pages.admin.MasterData.penatua.index', compact('penatuas', 'wijks'));
    }

    /**
     * Update a penatua record.
     */
    public function update(Request $request, $id)
    {
        $penatua = Penatua::findOrFail($id);

        $data = $request->validate([
            'wijk_id' => ['nullable', 'exists:wijks,id'],
            'nama_lengkap' => ['required', 'string'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'tempat_lahir' => ['required', 'string'],
            'tanggal_lahir' => ['required', 'date'],
            'alamat' => ['required', 'string'],
            'no_hp' => ['required', 'string'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('foto')) {
            // delete old photo if exists
            if ($penatua->foto) {
                Storage::disk('public')->delete($penatua->foto);
            }
            $data['foto'] = $request->file('foto')->store('penatua', 'public');
        }

        // allow wijk_id to be null
        $penatua->update([
            'wijk_id' => $data['wijk_id'] ?? $penatua->wijk_id,
            'nama_lengkap' => $data['nama_lengkap'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'tempat_lahir' => $data['tempat_lahir'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'alamat' => $data['alamat'],
            'no_hp' => $data['no_hp'],
            'foto' => $data['foto'] ?? $penatua->foto,
        ]);

        return redirect()->route('admin.penatua')->with('success', 'Data Penatua berhasil diperbarui.');
    }

    /**
     * Return the list of user accounts with role 'penatua' that
     * still need their Penatua data filled.
     */
    public function pendingList(Request $request)
    {
        $users = User::where('role', 'penatua')
            ->whereDoesntHave('penatua')
            ->get();

        if ($request->ajax()) {
            return response()->json(['users' => $users]);
        }

        return view('pages.admin.MasterData.penatua.pending', compact('users'));
    }

    /**
     * Show create form for a Penatua. If `user_id` query param is present,
     * prefill the `nama_lengkap` with the related user's `name`.
     */
    public function create(Request $request)
    {
        $user = null;
        if ($request->has('user_id')) {
            $user = User::find($request->query('user_id'));
        }

        // Provide available wijks so Penatua can be assigned to one
        $wijks = Wijk::all();

        return view('pages.admin.MasterData.penatua.create', compact('user', 'wijks'));
    }

    /**
     * Store a new Penatua record. Use the related user's `name` as `nama_lengkap`.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'wijk_id' => ['required', 'exists:wijks,id'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'tempat_lahir' => ['required', 'string'],
            'tanggal_lahir' => ['required', 'date'],
            'alamat' => ['required', 'string'],
            'no_hp' => ['required', 'string'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ]);

        $user = User::find($data['user_id']);

        if (!$user) {
            return redirect()->back()->withErrors(['user_id' => 'User tidak ditemukan.']);
        }

        if ($user->penatua) {
            return redirect()->back()->withErrors(['user_id' => 'Akun ini sudah memiliki data Penatua.']);
        }

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('penatua', 'public');
        }

        $penatua = Penatua::create([
            'user_id' => $user->id,
            'wijk_id' => $data['wijk_id'],
            'nama_lengkap' => $user->name,
            'jenis_kelamin' => $data['jenis_kelamin'],
            'tempat_lahir' => $data['tempat_lahir'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'alamat' => $data['alamat'],
            'no_hp' => $data['no_hp'],
            'foto' => $fotoPath,
        ]);

        return redirect()->route('admin.penatua')->with('success', 'Data Penatua berhasil dibuat.');
    }

    /**
     * Remove the specified Penatua.
     */
    public function destroy($id)
    {
        $penatua = Penatua::findOrFail($id);

        // delete stored photo if exists
        if ($penatua->foto) {
            Storage::disk('public')->delete($penatua->foto);
        }

        $penatua->delete();

        return redirect()->route('admin.penatua')->with('success', 'Data Penatua berhasil dihapus.');
    }
}
