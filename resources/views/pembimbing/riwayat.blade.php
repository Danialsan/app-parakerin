@extends('layouts.app')
@section('title', 'Pembimbing - PKL SMKN 1 BLEGA')
@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
      <span class="text-muted fw-light">Riwayat /</span> Riwayat Kunjungan
    </h4>

    @foreach ($monitoring as $item)
      <div class="card mb-4 shadow-sm">
        <div
          class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
          <div>
            <table class="table table-borderless table-sm mb-0">
              <tr>
                <td class="fw-bold">DUDI</td>
                <td class="fw-bold">:</td>
                <td>{{ $item->dudi->nama_perusahaan }}</td>
              </tr>
              <tr>
                <td class="fw-bold">Keperluan</td>
                <td class="fw-bold">:</td>
                <td>{{ ucfirst(str_replace('_', ' ', $item->keperluan)) }}</td>
              </tr>
              <tr>
                <td class="fw-bold">Tanggal</td>
                <td class="fw-bold">:</td>
                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
              </tr>
            </table>
          </div>

          <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-end mt-2 mt-md-0">
            <a href="{{ route('pembimbing.keperluan.unduh', $item->id) }}" class="btn btn-sm btn-outline-primary">
              <i class="bx bx-printer bx-sm"></i> Unduh
            </a>
            <form action="{{ route('pembimbing.kunjungan.destroy', $item->id) }}" method="POST"
              onsubmit="return confirmDelete(event)">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">
                <i class="bx bx-trash bx-sm"></i> Hapus
              </button>
            </form>
          </div>
        </div>

        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>Nama Siswa</th>
                  <th>Kehadiran</th>
                  <th>Sikap</th>
                  <th>Progres</th>
                  <th>Kesesuaian</th>
                  <th>Catatan</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($item->detail as $detail)
                  <tr>
                    <td>{{ $detail->siswa->nama }}</td>
                    <td>{!! badgeNilai($detail->kehadiran) !!}</td>
                    <td>{!! badgeNilai($detail->sikap) !!}</td>
                    <td>{!! badgeNilai($detail->progres) !!}</td>
                    <td>{!! badgeNilai($detail->kesesuaian) !!}</td>
                    <td>{{ $detail->catatan }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          {{-- Keterangan Nilai --}}
          <div class="mt-3">
            <h6>Keterangan Nilai:</h6>
            <div class="d-flex flex-wrap gap-2">
              <span class="badge bg-success">5 = Sangat Baik</span>
              <span class="badge bg-primary">4 = Baik</span>
              <span class="badge bg-warning text-dark">3 = Cukup</span>
              <span class="badge bg-danger">2 = Kurang</span>
              <span class="badge bg-dark">1 = Sangat Kurang</span>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
@endsection

@push('custom-script')
  <script>
    function confirmDelete(event) {
      event.preventDefault();
      Swal.fire({
        title: 'Yakin hapus riwayat ini?',
        text: "Data yang dihapus tidak bisa dikembalikan.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!'
      }).then((result) => {
        if (result.isConfirmed) {
          event.target.submit();
        }
      });
      return false;
    }
  </script>
@endpush
