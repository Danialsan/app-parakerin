<?php

namespace App\Http\Controllers\Admin;

use App\Models\Dudi;
use App\Models\User;
use App\Imports\DudiImport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class DudiAdminController extends Controller
{
    public function index()
    {
        $dudi = Dudi::paginate(10);
        $user = User::where('role', 'dudi')->get();
        return view('admin.dudi.index',compact('dudi','user'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        try {
            $request->validate([
                'nama_perusahaan' => 'required|unique:dudi,nama_perusahaan',
                'alamat_dudi' => 'required',
                'bidang_usaha' => 'required',
                'pimpinan_dudi' => 'required',
                'nama_pembimbing' => 'required',
                'email' => 'required|email|unique:users,email',
            ], [
                'nama_perusahaan.required' => 'Nama Perusahaan wajib diisi.',
                'alamat_dudi.required' => 'Alamat Dudi wajib diisi.',
                'bidang_usaha.required' => 'Bidang Usaha wajib diisi.',
                'pimpinan_dudi.required' => 'Pimpinan Dudi wajib diisi.',
                'nama_pembimbing.required' => 'Pembimbing wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.unique' => 'Email sudah digunakan.',
            ]);

            // 1. Buat user akun
            $user = User::create([
                'name' => $request->nama_perusahaan,
                'email' => $request->email,
                'password' => Hash::make('dudi123'), // Default password
                'role' => 'dudi',
            ]);

            // 2. Simpan data dudi dan relasikan ke user_id
            Dudi::create([
                'nama_perusahaan' => $request->nama_perusahaan,
                'alamat_dudi' => $request->alamat_dudi,
                'pimpinan_dudi' => $request->pimpinan_dudi,
                'radius_kantor' => 0,
                'posisi_kantor' => 0,
                'user_id' => $user->id,
                'bidang_usaha' => $request->bidang_usaha,
                'nama_pembimbing' => $request->nama_pembimbing,
            ]);

            return redirect()->route('admin.dudi-admin.index')->with('success', 'Data berhasil disimpan dan akun berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->route('admin.dudi-admin.index')->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {
            // Ambil data Dudi berdasarkan UUID dari form
            $dudi = Dudi::findOrFail($request->id);

            // Ambil user_id dari relasi Dudi
            $userId = $dudi->user_id;

            // Validasi data
            $request->validate([
                'nama_perusahaan' => 'required|unique:dudi,nama_perusahaan,' . $request->id,
                'alamat_dudi' => 'required',
                'bidang_usaha' => 'required',
                'pimpinan_dudi' => 'required',
                'nama_pembimbing' => 'required',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users', 'email')->ignore($userId),
                ],
            ], [
                'nama_perusahaan.required' => 'Nama Perusahaan wajib diisi.',
                'alamat_dudi.required' => 'Alamat Dudi wajib diisi.',
                'bidang_usaha.required' => 'Bidang Usaha wajib diisi.',
                'pimpinan_dudi.required' => 'Pimpinan Dudi wajib diisi.',
                'nama_pembimbing.required' => 'Pembimbing wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.unique' => 'Email sudah digunakan.',
            ]);

            // Update data Dudi
            $dudi->update([
                'nama_perusahaan' => $request->nama_perusahaan,
                'alamat_dudi' => $request->alamat_dudi,
                'pimpinan_dudi' => $request->pimpinan_dudi,
                'radius_kantor' => 0,
                'posisi_kantor' => 0,
                'bidang_usaha' => $request->bidang_usaha,
                'nama_pembimbing' => $request->nama_pembimbing,
            ]);

            // Update di tabel users jika email berubah atau reset password dicentang
            if ($request->email && $dudi->user_id) {
                $user = User::find($dudi->user_id);
                if ($user) {
                    $updateData = [];

                    if ($user->email !== $request->email) {
                        $updateData['email'] = $request->email;
                    }

                    if ($request->has('reset_password')) {
                        $updateData['password'] = Hash::make('dudi123'); // default password
                    }

                    if (!empty($updateData)) {
                        $user->update($updateData);
                    }
                }
            }

            return redirect()->route('admin.dudi-admin.index')->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->route('admin.dudi-admin.index')->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }


    public function destroy(Request $request)
    {
        try {
            $cari = Dudi::findOrFail($request->id);
            $user = User::findOrFail($cari->user_id);
            Dudi::destroy($request->id);
            User::destroy($user->id);
            return redirect()->route('admin.dudi-admin.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.dudi-admin.index')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }

    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new DudiImport, $request->file('file'));
            return redirect()->back()->with('success', 'Data DUDI berhasil di Unggah.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat Unggah: ' . $e->getMessage());
        }
    }
}
