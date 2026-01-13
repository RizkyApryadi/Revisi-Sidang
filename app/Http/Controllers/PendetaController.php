<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pendeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Validation\Rule;

class PendetaController extends Controller
{
    /**
     * Return number of pendeta accounts pending and total profiles.
     */
    public function pendingCount()
    {
        $pending = User::where('role', 'pendeta')
            ->whereDoesntHave('pendeta')
            ->count();

        $total = Pendeta::count();

        return response()->json(['pending' => $pending, 'total' => $total]);
    }

    /**
     * Display a listing of Pendeta.
     */
    public function index()
    {
        $pendetas = Pendeta::all();

        return view('pages.admin.MasterData.pendeta.index', compact('pendetas'));
    }

    /**
     * Return the list of user accounts with role 'pendeta' that
     * still need their Pendeta data filled.
     */
    public function pendingList(Request $request)
    {
        $users = User::where('role', 'pendeta')
            ->whereDoesntHave('pendeta')
            ->get();

        if ($request->ajax()) {
            return response()->json(['users' => $users]);
        }

        return view('pages.admin.MasterData.pendeta.pending', compact('users'));
    }

    /**
     * Show create form for a Pendeta. Prefill `nama_lengkap` from user if provided.
     */
    public function create(Request $request)
    {
        $user = null;
        if ($request->has('user_id')) {
            $user = User::find($request->query('user_id'));
        }

        return view('pages.admin.MasterData.pendeta.create', compact('user'));
    }

    /**
     * Store a new Pendeta record. Use the related user's `name` as `nama_lengkap`.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
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

        if ($user->pendeta) {
            return redirect()->back()->withErrors(['user_id' => 'Akun ini sudah memiliki data Pendeta.']);
        }

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('pendeta', 'public');
        }

        $pendeta = Pendeta::create([
            'user_id' => $user->id,
            'nama_lengkap' => $user->name,
            'jenis_kelamin' => $data['jenis_kelamin'],
            'tempat_lahir' => $data['tempat_lahir'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'alamat' => $data['alamat'],
            'no_hp' => $data['no_hp'],
            'foto' => $fotoPath,
        ]);

        return redirect()->route('admin.pendeta')->with('success', 'Data Pendeta berhasil dibuat.');
    }

    /**
     * Update an existing Pendeta record.
     */
    public function update(Request $request, $id)
    {
        $pendeta = Pendeta::findOrFail($id);

        $data = $request->validate([
            'nama_lengkap' => ['required', 'string'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'tempat_lahir' => ['required', 'string'],
            'tanggal_lahir' => ['required', 'date'],
            'alamat' => ['required', 'string'],
            'no_hp' => ['required', 'string'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('foto')) {
            if ($pendeta->foto) {
                Storage::disk('public')->delete($pendeta->foto);
            }
            $data['foto'] = $request->file('foto')->store('pendeta', 'public');
        }

        $pendeta->update([
            'nama_lengkap' => $data['nama_lengkap'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'tempat_lahir' => $data['tempat_lahir'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'alamat' => $data['alamat'],
            'no_hp' => $data['no_hp'],
            'foto' => $data['foto'] ?? $pendeta->foto,
        ]);

        return redirect()->route('admin.pendeta')->with('success', 'Data Pendeta berhasil diperbarui.');
    }

    /**
     * Remove the specified Pendeta.
     */
    public function destroy($id)
    {
        $pendeta = Pendeta::findOrFail($id);

        // delete stored photo if exists
        if ($pendeta->foto) {
            Storage::disk('public')->delete($pendeta->foto);
        }

        $pendeta->delete();

        return redirect()->route('admin.pendeta')->with('success', 'Data Pendeta berhasil dihapus.');
    }
}
