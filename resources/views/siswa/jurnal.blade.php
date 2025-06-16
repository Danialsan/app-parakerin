@extends('layouts.app')
@section('title', 'Jurnal Harian - PKL SMKN 1 BLEGA')

@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Jurnal /</span> Isi Jurnal Harian</h4>

    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('siswa.jurnal.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="card mb-4">
        <div class="card-body row g-3">
          <div class="col-md-4">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required
              readonly>
          </div>

          <div class="col-md-4">
            <label for="capaian_pembelajaran_id" class="form-label">Capaian Pembelajaran</label>
            <select name="capaian_pembelajaran_id" id="capaian_pembelajaran_id" class="form-select" required>
              <option value="">-- Pilih Capaian --</option>
              @foreach ($capaian as $cp)
                <option value="{{ $cp->id }}">{{ $cp->deskripsi_cp }}</option>
              @endforeach
            </select>
            <small class="text-muted mt-1 d-block">Jurusan: <strong>{{ $siswa->jurusan->nama_jurusan }}</strong></small>
          </div>

          <div class="col-md-4">
            <label for="foto" class="form-label">Unggah Dokumentasi</label>
            <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
          </div>

          <div class="col-md-12">
            <label for="kegiatan" class="form-label">Kegiatan Hari Ini</label>
            <textarea name="kegiatan" id="kegiatan" rows="5" class="form-control"
              placeholder="Tuliskan kegiatan harian dengan jelas..." required>{{ old('kegiatan') }}</textarea>
          </div>
        </div>
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-primary">
          <i class="bx bx-save"></i> Simpan Jurnal
        </button>
      </div>
    </form>
  </div>
@endsection
