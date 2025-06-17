<?php

namespace App\Http\Controllers\Admin;

use App\Models\Jurusan;
use Illuminate\Http\Request;
use App\Models\CapaianPembelajaran;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CapaianPembelajaranImport;
use Maatwebsite\Excel\Validators\ValidationException;

class CapaianPembelajaranController extends Controller
{
    public function index()
    {
        $jurusan = Jurusan::all();
        $capaianPembelajaran = CapaianPembelajaran::paginate(10);
        return view('admin.capaian-pembelajaran.index', compact('capaianPembelajaran','jurusan'));
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'jurusan_id' => 'required|unique:capaian_pembelajaran,jurusan_id',
                'deskripsi_cp' => 'required',
            ], [
                'jurusan_id.required' => 'Jurusan wajib diisi.',
                'jurusan_id.unique' => 'Jurusan sudah terdaftar.',
                'deskripsi_cp.required' => 'Deskripsi CP wajib diisi.',
            ]);

            $capaianPembelajaran = new CapaianPembelajaran();
            $capaianPembelajaran->jurusan_id = $request->jurusan_id;
            $capaianPembelajaran->deskripsi_cp = $request->deskripsi_cp;
            $capaianPembelajaran->save();

            return redirect()->route('admin.capaian-pembelajaran.index')->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->route('admin.capaian-pembelajaran.index')->with('error', 'Data gagal disimpan');
        }
    }
    public function update(Request $request)
    {
        try {
            $request->validate([
                'jurusan_id' => 'required|unique:capaian_pembelajaran,jurusan_id,'.$request->id,
                'deskripsi_cp' => 'required',
            ], [
                'jurusan_id.required' => 'Jurusan wajib diisi.',
                'jurusan_id.unique' => 'Jurusan sudah terdaftar.',
                'deskripsi_cp.required' => 'Deskripsi CP wajib diisi.',
            ]);

            $capaianPembelajaran = CapaianPembelajaran::find($request->id);
            $capaianPembelajaran->jurusan_id = $request->jurusan_id;
            $capaianPembelajaran->deskripsi_cp = $request->deskripsi_cp;
            $capaianPembelajaran->save();

            return redirect()->route('admin.capaian-pembelajaran.index')->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->route('admin.capaian-pembelajaran.index')->with('error', 'Data gagal disimpan');
        }
    }
    public function destroy(Request $request)
    {
        // dd($request->id);
        try {
            $capaianPembelajaran = CapaianPembelajaran::find($request->id);
            $capaianPembelajaran->delete();

            return redirect()->route('admin.capaian-pembelajaran.index')->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.capaian-pembelajaran.index')->with('error', 'Data gagal dihapus');
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new CapaianPembelajaranImport, $request->file('file'));
            return redirect()->back()->with('success', 'Data Capaian Pembelajaran berhasil di Unggah.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat Unggah: ' . $e->getMessage());
        }
    }


}
