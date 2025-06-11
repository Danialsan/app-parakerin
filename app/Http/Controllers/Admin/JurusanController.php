<?php

namespace App\Http\Controllers\Admin;

use App\Models\Jurusan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JurusanController extends Controller
{
    public function index()
    {
        $jurusan = Jurusan::paginate(10);
        return view('admin.jurusan.index', compact('jurusan'));
    }
    public function store(Request $request)
    {
       try  {
           $request->validate([
               'kode_jurusan' => 'required|unique:jurusan,kode_jurusan',
               'nama_jurusan' => 'required',
           ], [
               'kode_jurusan.required' => 'Kode jurusan wajib diisi.',
               'kode_jurusan.unique' => 'Kode jurusan sudah terdaftar.',
               'nama_jurusan.required' => 'Nama jurusan wajib diisi.',
           ]);

           Jurusan::create($request->only(['kode_jurusan', 'nama_jurusan']));

           return redirect()->route('admin.jurusan.index')->with('success', 'Data berhasil disimpan');
       } catch (\Exception $e) {
           return redirect()->route('admin.jurusan.index')->with('error', 'Data gagal disimpan');
       }
    }
    public function update(Request $request){
      try {
        $request->validate([
          'kode_jurusan' => 'required|unique:jurusan,kode_jurusan,'.$request->id,
          'nama_jurusan' => 'required',
        ], [
          'kode_jurusan.required' => 'Kode jurusan wajib diisi.',
          'kode_jurusan.unique' => 'Kode jurusan sudah terdaftar.',
          'nama_jurusan.required' => 'Nama jurusan wajib diisi.',
        ]);

        Jurusan::find($request->id)->update($request->only(['kode_jurusan', 'nama_jurusan']));

        return redirect()->route('admin.jurusan.index')->with('success', 'Data berhasil disimpan');
      } catch (\Exception $e) {
        return redirect()->route('admin.jurusan.index')->with('error', 'Data gagal disimpan');
      }
    }
    public function destroy(Request $request)
    {
        // dd($request->id);
      try {
        Jurusan::destroy($request->id);

        return redirect()->route('admin.jurusan.index')->with('success', 'Data berhasil dihapus');
      } catch (\Exception $e) {
        return redirect()->route('admin.jurusan.index')->with('error', 'Data gagal dihapus');
      }
    }
}
