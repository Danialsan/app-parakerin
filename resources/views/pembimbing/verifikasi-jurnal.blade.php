@extends('layouts.app')
@section('title', 'Pembimbing - PKL SMKN 1 BLEGA')

@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
      <span class="text-muted fw-light">Jurnal Siswa /</span> Verifikasi Jurnal Siswa
    </h4>

    <div class="card">
      <div class="card-header">
        <form method="GET" class="row g-1 mb-2">
          <div class="col-md-4">
            <select name="status" class="form-select form-select-sm">
              <option value="">-- Semua Status --</option>
              <option value="terverifikasi" {{ request('status') == 'terverifikasi' ? 'selected' : '' }}>Terverifikasi
              </option>
              <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum</option>
            </select>
          </div>
          <div class="col-md-4">
            <button type="submit" class="btn btn-sm btn-outline-primary">
              <i class="bx bx-filter"></i> Filter
            </button>
            <a href="{{ route('pembimbing.verifikasi-jurnal.index') }}" class="btn btn-sm btn-outline-secondary"><i
                class="bx bx-refresh"></i> Reset</a>
          </div>
        </form>

      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Nama Siswa</th>
                <th>Capaian Pembelajaran</th>
                <th>Kegiatan</th>
                <th>Foto</th>
                <th>Status</th>
                <th>Verifikasi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($jurnals as $jurnal)
                <tr>
                  <td>{{ $jurnal->tanggal }}</td>
                  <td>{{ $jurnal->siswa->nama }}</td>
                  <td>{{ $jurnal->capaianPembelajaran->deskripsi_cp ?? '-' }}</td>
                  <td>{{ $jurnal->kegiatan }}</td>
                  <td>
                    @if ($jurnal->foto)
                      <a href="{{ asset('storage/' . $jurnal->foto) }}" target="_blank">
                        <img src="{{ asset('storage/' . $jurnal->foto) }}" width="50">
                      </a>
                    @else
                      -
                    @endif
                  </td>
                  <td>
                    <span class="badge bg-{{ $jurnal->verifikasi_pembimbing ? 'success' : 'warning' }}">
                      {{ $jurnal->verifikasi_pembimbing ? 'Terverifikasi' : 'Belum' }}
                    </span>
                  </td>
                  <td>
                    @if (!$jurnal->verifikasi_pembimbing)
                      <form class="form-verifikasi"
                        action="{{ route('pembimbing.verifikasi-jurnal.verifikasi', $jurnal->id) }}" method="POST">
                        @csrf
                        <textarea name="catatan_pembimbing" class="form-control form-control-sm mb-2"
                          placeholder="Catatan pembimbing (opsional)">{{ old('catatan_pembimbing') }}</textarea>
                        <button type="button" class="btn btn-sm btn-success btn-verifikasi">
                          <i class="bx bx-check-circle"></i> Verifikasi
                        </button>
                      </form>
                    @else
                      <div>
                        <small class="d-block text-muted fst-italic">Catatan:
                          {{ $jurnal->catatan_pembimbing ?? '-' }}</small>
                        <button onclick="batalVerifikasi('{{ $jurnal->id }}')" class="btn btn-sm btn-warning mt-1">
                          <i class="bx bx-undo"></i> Batal
                        </button>
                      </div>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center">Data Jurnal Tidak Ada.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
          {{ $jurnals->links() }}
        </div>
      </div>
    </div>
  </div>
@endsection

@push('custom-script')
  <script>
    $(document).on('click', '.btn-verifikasi', function(e) {
      e.preventDefault();

      const button = $(this);
      const form = button.closest('.form-verifikasi');
      const url = form.attr('action');
      const catatan = form.find('textarea[name="catatan_pembimbing"]').val();
      const row = button.closest('tr');

      Swal.fire({
        title: 'Yakin ingin memverifikasi jurnal ini?',
        text: "Setelah diverifikasi, jurnal akan dikunci.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, verifikasi!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          // Nonaktifkan tombol saat proses AJAX
          button.prop('disabled', true);

          $.post(url, {
            _token: '{{ csrf_token() }}',
            catatan: catatan
          }, function(res) {
            if (res.success) {
              row.find('td:nth-child(6)').html(`
            <span class="badge bg-success">Terverifikasi</span>
          `);
              row.find('td:nth-child(7)').html(`
            <div>
              <small class="d-block text-muted fst-italic">Catatan: ${catatan || '-'}</small>
              <button onclick="batalVerifikasi('${res.id}')" class="btn btn-sm btn-warning mt-1">
                <i class="bx bx-undo"></i> Batal
              </button>
            </div>
          `);

              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Jurnal berhasil diverifikasi',
              });
            } else {
              Swal.fire('Gagal', 'Terjadi kesalahan pada server.', 'error');
              button.prop('disabled', false);
            }
          }).fail(function() {
            Swal.fire('Gagal', 'Tidak bisa menghubungi server.', 'error');
            button.prop('disabled', false);
          });
        }
      });
    });



    function batalVerifikasi(id) {
      Swal.fire({
        title: 'Yakin ingin membatalkan?',
        text: "Verifikasi akan dibatalkan",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, batalkan!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          $.post(`/pembimbing/verifikasi-jurnal/${id}/batal`, {
            _token: '{{ csrf_token() }}'
          }, function(res) {
            if (res.success) {
              Swal.fire({
                title: 'Dibatalkan',
                text: 'Verifikasi berhasil dibatalkan',
                icon: 'success',
                confirmButtonText: 'OK'
              }).then(() => {
                // Cari baris berdasarkan tombol yang diklik
                const row = $(`button[onclick="batalVerifikasi('${id}')"]`).closest('tr');

                // Kembalikan tombol dan status awal
                row.find('td:nth-child(6)').html(`
                  <span class="badge bg-warning">Belum</span>
                `);

                row.find('td:nth-child(7)').html(`
                  <form class="form-verifikasi" action="/pembimbing/verifikasi-jurnal/${id}" method="POST">
                    @csrf
                    <textarea name="catatan_pembimbing" class="form-control form-control-sm mb-2"
                      placeholder="Catatan pembimbing (opsional)"></textarea>
                    <button type="button" class="btn btn-sm btn-success btn-verifikasi">
                      <i class="bx bx-check-circle"></i> Verifikasi
                    </button>
                  </form>
                `);
              });
            }
          }).fail(function() {
            Swal.fire('Gagal', 'Tidak bisa membatalkan verifikasi.', 'error');
          });
        }
      });
    }
  </script>
@endpush
