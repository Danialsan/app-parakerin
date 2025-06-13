<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Jurusan;
use Illuminate\Support\Str;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class SiswaAdminController extends Controller
{
    public function index()
    {
        $siswa = Siswa::paginate(10);
        $user = User::where('role', 'siswa')->get();
        $jurusan = Jurusan::get();
        return view('admin.siswa-admin.index', compact('siswa','user','jurusan'));
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nis' => 'required|unique:siswa,nis',
                'nama' => 'required',
                'kelas' => 'required',
                'jurusan' => 'required',
                'gender' => 'required',
                'alamat' => 'required',
                'telepon' => 'required',
                'email' => 'required|email|unique:users,email',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ], [
                'nis.required' => 'NIS wajib diisi.',
                'nama.required' => 'Nama wajib diisi.',
                'kelas.required' => 'Kelas wajib diisi.',
                'jurusan.required' => 'Jurusan wajib diisi.',
                'gender.required' => 'Jenis kelamin wajib diisi.',
                'alamat.required' => 'Alamat wajib diisi.',
                'telepon.required' => 'Telepon wajib diisi.',
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
                $fotoFile->storeAs('foto-siswa', $fotoName, 'public');

            }

            // 1. Buat akun user
            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make('siswa123'),
                'role' => 'siswa',
            ]);

            // 2. Simpan data siswa
            Siswa::create([
                'nis' => $request->nis,
                'nama' => $request->nama,
                'kelas' => $request->kelas,
                'jurusan_id' => $request->jurusan,
                'gender' => $request->gender,
                'alamat' => $request->alamat,
                'telepon' => $request->telepon,
                'foto' => $fotoName,
                'user_id' => $user->id,
                'pembimbing_sekolah_id' => null,
                'dudi_id' => null,
            ]);

            return redirect()->route('admin.siswa-admin.index')->with('success', 'Data berhasil disimpan dan akun berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->route('admin.siswa-admin.index')->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $siswa = Siswa::findOrFail($id);

            $request->validate([
                'nis' => 'required|unique:siswa,nis,' . $id,
                'nama' => 'required',
                'kelas' => 'required',
                'jurusan' => 'required',
                'gender' => 'required',
                'alamat' => 'required',
                'telepon' => 'required',
                'email' => 'required|email|unique:users,email,' . $siswa->user_id,
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ], [
                'nis.required' => 'NIS wajib diisi.',
                'nama.required' => 'Nama wajib diisi.',
                'kelas.required' => 'Kelas wajib diisi.',
                'jurusan.required' => 'Jurusan wajib diisi.',
                'gender.required' => 'Jenis kelamin wajib diisi.',
                'alamat.required' => 'Alamat wajib diisi.',
                'telepon.required' => 'Telepon wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.unique' => 'Email sudah digunakan.',
                'foto.image' => 'File harus berupa gambar.',
                'foto.max' => 'Ukuran gambar maksimal 2MB.',
            ]);

            $fotoName = $siswa->foto;

            if ($request->hasFile('foto')) {
                if ($siswa->foto && $siswa->foto !== 'default.jpg') {
                    Storage::disk('public')->delete('foto-siswa/' . $siswa->foto);
                }

                $fotoFile = $request->file('foto');
                $fotoPath = $fotoFile->store('foto-siswa', 'public');
                $fotoName = basename($fotoPath);
            }

            // Update tabel siswa
            $siswa->update([
                'nis' => $request->nis,
                'nama' => $request->nama,
                'kelas' => $request->kelas,
                'jurusan_id' => $request->jurusan,
                'gender' => $request->gender,
                'alamat' => $request->alamat,
                'telepon' => $request->telepon,
                'foto' => $fotoName,
            ]);

            // Update juga di tabel users jika email berubah
            if ($request->email && $siswa->user_id) {
                $user = User::find($siswa->user_id);
                if ($user && $user->email !== $request->email) {
                    $user->update([
                        'email' => $request->email,
                    ]);
                }
            }

            return redirect()->route('admin.siswa-admin.index')->with('success', 'Data siswa berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('admin.siswa-admin.index')->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $cari = Siswa::findOrFail($request->id);
            $user = User::findOrFail($cari->user_id);

            // Hapus foto jika bukan default
            if ($cari->foto && $cari->foto !== 'default.png') {
                Storage::disk('public')->delete('foto-siswa/' . $cari->foto);
            }
            Siswa::destroy($request->id);
            User::destroy($user->id);
            return redirect()->route('admin.siswa-admin.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.siswa-admin.index')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new SiswaImport, $request->file('file'));
            return redirect()->back()->with('success', 'Data Siswa berhasil di Unggah.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat Unggah: ' . $e->getMessage());
        }
    }
}
