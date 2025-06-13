<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PembimbingSekolah;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Imports\PembimbingSekolahImport;

class PembimbingSekolahAdminController extends Controller
{
    public function index()
    {
        $pembimbingSekolah = PembimbingSekolah::paginate(10);
        $user = User::where('role', 'pembimbing')->get();
        // $jurusan = Jurusan::get();
        return view('admin.pembimbing-sekolah-admin.index',compact('pembimbingSekolah','user'));
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_pembimbing' => 'required|unique:pembimbing_sekolah,nama_pembimbing',
                // 'nip' => 'required|unique:pembimbing_sekolah,nip',
                'jabatan' => 'required',
                'email' => 'required|email|unique:users,email',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ], [
                'nama_pembimbing.required' => 'Nama Pembimbing wajib diisi.',
                // 'nip.required' => 'NIP wajib diisi.',
                'jabatan.required' => 'Jabatan wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.unique' => 'Email sudah digunakan.',
                'foto.image' => 'File harus berupa gambar.',
                'foto.max' => 'Ukuran gambar maksimal 2MB.',
            ]);

            // Upload foto jika ada, jika tidak pakai default
            $fotoName = 'default.png';

            if ($request->hasFile('foto')) {
                $fotoFile = $request->file('foto');
                $fotoName = time() . '-' . Str::slug(pathinfo($fotoFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $fotoFile->getClientOriginalExtension();
                $fotoFile->storeAs('foto-pembimbing', $fotoName, 'public');

            }


            // 1. Buat akun user
            $user = User::create([
                'name' => $request->nama_pembimbing,
                'email' => $request->email,
                'password' => Hash::make('pembimbing123'),
                'role' => 'pembimbing',
            ]);

            // 2. Simpan data pembimbing sekolah
            PembimbingSekolah::create([
                'nama_pembimbing' => $request->nama_pembimbing,
                'nip' => $request->nip,
                'jabatan' => $request->jabatan,
                'foto' => $fotoName,
                'user_id' => $user->id,
            ]);

            return redirect()->route('admin.pembimbing-sekolah-admin.index')->with('success', 'Data berhasil disimpan dan akun berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->route('admin.pembimbing-sekolah-admin.index')->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $pembimbing = PembimbingSekolah::findOrFail($id);

            $request->validate([
                'nama_pembimbing' => 'required|unique:pembimbing_sekolah,nama_pembimbing,' . $id,
                'nip' => 'nullable|unique:pembimbing_sekolah,nip,' . $id,
                'jabatan' => 'required',
                'email' => 'required|email|unique:users,email,' . $pembimbing->user_id,
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ], [
                'nama_pembimbing.required' => 'Nama Pembimbing wajib diisi.',
                'jabatan.required' => 'Jabatan wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.unique' => 'Email sudah digunakan.',
                'foto.image' => 'File harus berupa gambar.',
                'foto.max' => 'Ukuran gambar maksimal 2MB.',
            ]);

            $fotoName = $pembimbing->foto;

            if ($request->hasFile('foto')) {
                if ($pembimbing->foto && $pembimbing->foto !== 'default.jpg') {
                    Storage::disk('public')->delete('foto-pembimbing/' . $pembimbing->foto);
                }

                $fotoFile = $request->file('foto');
                $fotoPath = $fotoFile->store('foto-pembimbing', 'public');
                $fotoName = basename($fotoPath);
            }

            // Update tabel pembimbing
            $pembimbing->update([
                'nama_pembimbing' => $request->nama_pembimbing,
                'nip' => $request->nip,
                'jabatan' => $request->jabatan,
                'foto' => $fotoName,
            ]);

            // Update juga di tabel users jika email berubah
            if ($request->email && $pembimbing->user_id) {
                $user = User::find($pembimbing->user_id);
                if ($user && $user->email !== $request->email) {
                    $user->update([
                        'email' => $request->email,
                    ]);
                }
            }

            return redirect()->route('admin.pembimbing-sekolah-admin.index')->with('success', 'Data pembimbing berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('admin.pembimbing-sekolah-admin.index')->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $cari = PembimbingSekolah::findOrFail($request->id);
            $user = User::findOrFail($cari->user_id);

            // Hapus foto jika bukan default
            if ($cari->foto && $cari->foto !== 'default.png') {
                Storage::disk('public')->delete('foto-pembimbing/' . $cari->foto);
            }
            PembimbingSekolah::destroy($request->id);
            User::destroy($user->id);
            return redirect()->route('admin.pembimbing-sekolah-admin.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.pembimbing-sekolah-admin.index')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
    public function import(Request $request)
    {
        // dd($request->file('file'));
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new PembimbingSekolahImport, $request->file('file'));
            return back()->with('success', 'Impor berhasil!');
        } catch (ValidationException $e) {
            return back()->withErrors($e->failures()); // tampilkan detail validasi
        } catch (\Throwable $e) {
            return back()->with('error', 'Kesalahan: ' . $e->getMessage());
        }
    }
}
