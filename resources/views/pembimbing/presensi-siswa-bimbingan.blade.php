@extends('layouts.app')
@section('title', 'Presensi Siswa Bimbingan')

@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Presensi /</span> Siswa Bimbingan</h4>

    <div class="card">
      <div class="card-body table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Nama Siswa</th>
              <th>Tanggal</th>
              <th>Absensi</th>
              <th>Masuk</th>
              <th>Pulang</th>
              <th>Keterangan</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($presensis as $presensi)
              <tr>
                <td>{{ $presensi->siswa->nama }}</td>
                <td>{{ \Carbon\Carbon::parse($presensi->waktu_masuk)->format('d-m-Y') }}</td>
                <td><span class="badge bg-primary">{{ ucfirst($presensi->absensi) }}</span></td>
                <td>{{ $presensi->waktu_masuk ? \Carbon\Carbon::parse($presensi->waktu_masuk)->format('H:i') : '-' }}</td>
                <td>{{ $presensi->waktu_pulang ? \Carbon\Carbon::parse($presensi->waktu_pulang)->format('H:i') : '-' }}
                </td>
                <td>{{ $presensi->keterangan ?? '-' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center">Belum ada data presensi</td>
              </tr>
            @endforelse
          </tbody>
        </table>

        <div class="d-flex justify-content-end mt-3">
          {{ $presensis->withQueryString()->links() }}
        </div>
      </div>
    </div>
  </div>
@endsection
