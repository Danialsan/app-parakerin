<?php

namespace App\Http\Controllers\Dudi;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BerandaController extends Controller
{
    public function index()
    {
        return view('dudi.beranda');
    }

    public function pengaturan()
    {
        $user = auth()->user();
        return view('pengaturan', [
            'user_role' => 'dudi',
            'dudi' => $user->dudi->first(),
        ]);
    }

    public function updatePengaturan(Request $request)
    {

        try {
            $user = auth()->user();
            $request->validate(
                [
                    'password' => 'nullable|min:6',
                    'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                ],
                [
                    'password.min' => 'Password minimal 6 karakter.',
                    'foto.image' => 'File harus berupa gambar.',
                    'foto.max' => 'Ukuran gambar maksimal 2MB.',
                ]
            );
            // Update password jika diisi
            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }

            // Update foto jika diunggah
            if ($request->hasFile('foto')) {
                // Ambil pembimbingnya dari relasi
                $dudi = $user->dudi->first();

                if ($dudi) {
                    $fotoName = $dudi->foto;

                    if ($request->hasFile('foto')) {
                        if ($dudi->foto && $dudi->foto !== 'default.jpg') {
                            Storage::disk('public')->delete('foto-dudi/' . $dudi->foto);
                        }


                        $fotoFile = $request->file('foto');
                        $fotoName = time() . '-' . Str::slug(pathinfo($fotoFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $fotoFile->getClientOriginalExtension();
                        $fotoFile->storeAs('foto-dudi', $fotoName, 'public');

                    }

                    // Simpan foto baru
                    $dudi->foto = $fotoName;
                    $dudi->save();
                }
            }

            $user->save();

            return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());

        }
    }

}
