<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AnggotaController extends Controller
{
    public function index()
    {
        $anggotas = Anggota::latest()->paginate(10);
        return view('anggota.index', compact('anggotas'));
    }

    public function create()
    {
        return view('anggota.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:255',
            'alamat'  => 'required|string',
            'telepon' => 'required|string|max:20',
            'email'   => 'required|email|unique:anggotas|unique:users,email',
        ]);

        // Buat data anggota
        Anggota::create($request->all());

        // Buat akun login otomatis dengan password default
        User::create([
            'name'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make('password123'),
            'role'     => 'anggota',
        ]);

        return redirect()->route('anggota.index')
            ->with('success', 'Anggota berhasil ditambahkan. Akun login: ' . $request->email . ' / password123');
    }

    public function show(Anggota $anggota)
    {
        return view('anggota.show', compact('anggota'));
    }

    public function edit(Anggota $anggota)
    {
        return view('anggota.edit', compact('anggota'));
    }

    public function update(Request $request, Anggota $anggota)
    {
        $request->validate([
            'nama'    => 'required|string|max:255',
            'alamat'  => 'required|string',
            'telepon' => 'required|string|max:20',
            'email'   => 'required|email|unique:anggotas,email,' . $anggota->id . '|unique:users,email,' . optional(User::where('email', $anggota->email)->first())->id,
        ]);

        // Update akun login jika email berubah
        $user = User::where('email', $anggota->email)->first();
        if ($user && $user->email !== $request->email) {
            $user->update([
                'name'  => $request->nama,
                'email' => $request->email,
            ]);
        } elseif ($user) {
            $user->update(['name' => $request->nama]);
        }

        $anggota->update($request->all());

        return redirect()->route('anggota.index')
            ->with('success', 'Anggota berhasil diperbarui.');
    }

    public function destroy(Anggota $anggota)
    {
        // Hapus akun login juga
        User::where('email', $anggota->email)->delete();

        $anggota->delete();

        return redirect()->route('anggota.index')
            ->with('success', 'Anggota dan akun login berhasil dihapus.');
    }
}