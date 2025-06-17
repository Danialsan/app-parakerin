@extends('layouts.app')
@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Rekap/</span> Rekap Kunjungan Pembimbing</h4>
    <div class="card">
      <div class="card-header">
        <div class="row g-2 align-items-end justify-content-between">
          <div class="col-md-9">
            <form method="GET" class="row g-2">
              <div class="col-md-6">
                <select name="jurusan_id" class="form-select form-select-sm">
                  <option value="">-- Semua Jurusan --</option>
                  @foreach ($jurusan as $j)
                    <option value="{{ $j->id }}" {{ request('jurusan_id') == $j->id ? 'selected' : '' }}>
                      {{ $j->nama_jurusan }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-outline-primary">
                  <i class="bx bx-filter"></i> Filter
                </button>
                <a href="{{ route('admin.pembimbing-sekolah-admin.rekap-monitoring') }}"
                  class="btn btn-sm btn-outline-secondary">
                  <i class="bx bx-refresh"></i> Reset
                </a>
              </div>
            </form>
          </div>

          <div class="col-md-3 text-end">
            <a href="{{ route('admin.pembimbing-sekolah-admin.rekap-monitoring.download', request()->only('jurusan_id')) }}"
              class="btn btn-sm btn-outline-success">
              <i class="bx bx-download"></i> Unduh PDF
            </a>
          </div>
        </div>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-sm">
            <thead class="table-light">
              <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Keperluan</th>
                <th>Nama Pembimbing</th>
                <th>DUDI</th>
                <th>Jumlah Siswa</th>
                <th>Foto</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($monitoring as $i => $m)
                <tr>
                  <td>{{ $monitoring->firstItem() + $i }}</td>
                  <td>{{ \Carbon\Carbon::parse($m->monitoring->tanggal)->format('d-m-Y') }}</td>
                  <td>{{ ucfirst($m->monitoring->keperluan) }}</td>
                  <td>{{ $m->monitoring->pembimbing->nama_pembimbing ?? '-' }}</td>
                  <td>{{ $m->siswa->dudi->nama_perusahaan ?? '-' }}</td>
                  <td>({{ $m->jumlah_siswa }} siswa)</td>
                  <td>
                    @if ($m->monitoring->foto)
                      <a href="{{ asset('storage/' . $m->monitoring->foto) }}" target="_blank">
                        <img src="{{ asset('storage/' . $m->monitoring->foto) }}" width="50">
                      </a>
                    @else
                      -
                    @endif
                  </td>
                </tr>
              @endforeach

            </tbody>
          </table>
        </div>

        <div class="card-footer">
          {{ $monitoring->links() }}
        </div>
      </div>
    </div>
  </div>
@endsection
