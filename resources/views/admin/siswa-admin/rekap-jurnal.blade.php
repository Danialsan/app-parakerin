@extends('layouts.app')
@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Rekap/</span> Rekap Jurnal Siswa</h4>
    <div class="card">
      <div class="card-header">
        {{-- Filter --}}
        <form method="GET" action="{{ route('admin.siswa-admin.rekap-jurnal') }}" class="row g-2 mb-3">
          <div class="col-md-4">
            <select name="jurusan_id" class="form-select form-select-sm">
              <option value="">-- Semua Jurusan --</option>
              @foreach ($jurusan as $j)
                <option value="{{ $j->id }}" {{ request('jurusan_id') == $j->id ? 'selected' : '' }}>
                  {{ $j->nama_jurusan }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <select name="status" class="form-select form-select-sm">
              <option value="">-- Semua Status --</option>
              <option value="terverifikasi" {{ request('status') == 'terverifikasi' ? 'selected' : '' }}>Terverifikasi
              </option>
              <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Diverifikasi</option>
            </select>
          </div>
          <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-outline-primary">
              <i class="bx bx-filter"></i> Filter
            </button>
            <a href="{{ route('admin.siswa-admin.rekap-jurnal') }}" class="btn btn-sm btn-outline-secondary">
              <i class="bx bx-refresh"></i> Reset
            </a>
          </div>
          <div class="col-md-2 text-end">
            <a href="{{ route('admin.siswa-admin.rekap-jurnal.download', request()->only('jurusan_id')) }}"
              class="btn btn-sm btn-outline-success">
              <i class="bx bx-download"></i> Unduh Data
            </a>
          </div>
        </form>
      </div>
      {{-- Table --}}
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-sm">
            <thead class="table-light">
              <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Jurusan</th>
                <th>DUDI</th>
                <th>Tanggal</th>
                <th>Capaian</th>
                <th>Kegiatan</th>
                <th>Pembimbing</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($jurnal as $i => $item)
                <tr>
                  <td>{{ $jurnal->firstItem() + $i }}</td>
                  <td>{{ $item->siswa->nama }}</td>
                  <td>{{ $item->siswa->jurusan->nama_jurusan ?? '-' }}</td>
                  <td>{{ $item->siswa->dudi->nama_dudi ?? '-' }}</td>
                  <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                  <td>{{ $item->capaian }}</td>
                  <td>{{ $item->kegiatan }}</td>
                  <td>{{ $item->siswa->pengaturanPkl->pembimbing->nama_pembimbing ?? '-' }}</td>
                  <td>
                    @if ($item->diverifikasi)
                      <span class="badge bg-success">Terverifikasi</span>
                    @else
                      <span class="badge bg-warning text-dark">Belum</span>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="9" class="text-center">Tidak ada data jurnal</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{-- Pagination --}}
        <div class="card-footer">
          {{ $jurnal->links() }}
        </div>
      </div>

    </div>

  </div>
@endsection
