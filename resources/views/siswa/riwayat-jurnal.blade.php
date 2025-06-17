@extends('layouts.app')
@section('title', 'Riwayat Jurnal PKL')

@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Jurnal Pkl /</span> Rekap Jurnal</h4>

    @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill"></i>
        <span>{{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
    @if (session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <div class="card">
      <div class="card-header">
        <div class="row g-3 align-items-end">
          <!-- Form Filter -->
          <div class="col-md-9">
            <form method="GET" class="row g-2">
              <!-- Tanggal Awal -->
              <div class="col-md-4">
                <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                <input type="date" name="tanggal_awal" class="form-control form-control-sm"
                  value="{{ request('tanggal_awal') }}">
              </div>

              <!-- Tanggal Akhir -->
              <div class="col-md-4">
                <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" class="form-control form-control-sm"
                  value="{{ request('tanggal_akhir') }}">
              </div>

              <!-- Tombol Filter dan Reset -->
              <div class="col-md-4 d-flex align-items-end gap-2">
                <button class="btn btn-sm btn-outline-primary" type="submit">
                  <i class="bx bx-filter"></i> Filter
                </button>
                <a href="{{ route('siswa.jurnal.riwayat') }}" class="btn btn-sm btn-outline-secondary">
                  <i class="bx bx-reset"></i> Reset
                </a>
              </div>
            </form>
          </div>

          <!-- Tombol Unduh PDF -->
          <div class="col-md-3">
            <form method="GET" action="{{ route('siswa.jurnal.download') }}">
              <input type="hidden" name="tanggal_awal" value="{{ request('tanggal_awal') }}">
              <input type="hidden" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
              <button id="btn-unduh" class="btn btn-sm btn-outline-danger w-100" type="submit">
                <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"
                  id="spinner"></span>
                <i class="bx bxs-file-pdf"></i> Unduh Jurnal
              </button>
            </form>

          </div>
        </div>
      </div>

      <div class="card-body">
        <!-- Tabel -->
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Capaian Pembelajaran</th>
                <th>Kegiatan</th>
                <th>Foto</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($jurnals as $jurnal)
                <tr>
                  <td>{{ $jurnal->tanggal }}</td>
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
                    @if ($jurnal->verifikasi_pembimbing)
                      <span class="badge bg-success">Terverifikasi</span>
                      @if ($jurnal->catatan_pembimbing)
                        <br>
                        <small class="text-muted fst-italic ps-2 border-start border-2 border-secondary">
                          {{ $jurnal->catatan_pembimbing }}
                        </small>
                      @else
                        <br>
                        <small class="text-muted fst-italic ps-2 border-start border-2 border-secondary">
                          ---
                        </small>
                      @endif
                    @else
                      <span class="badge bg-danger">Belum</span>
                    @endif
                  </td>
                  <td>
                    <div class="d-grid gap-1 d-md-flex justify-content-md-end">
                      @if ($jurnal->verifikasi_pembimbing)
                        <button type="button" class="btn btn-sm btn-secondary" disabled title="Sudah diverifikasi">
                          <i class="bx bx-lock"></i>
                        </button>
                      @else
                        <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="{{ $jurnal->id }}"
                          data-nama="{{ $jurnal->siswa->nama }}">
                          <i class="bx bx-trash"></i>
                        </button>
                      @endif
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center">Belum ada jurnal</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="d-flex justify-content-end m-3">
          {{ $jurnals->withQueryString()->links() }}
        </div>
      </div>
    </div>
  </div>
@endsection
@push('custom-script')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.querySelector('form[action="{{ route('siswa.jurnal.download') }}"]');
      const button = document.getElementById('btn-unduh');
      const spinner = document.getElementById('spinner');

      form.addEventListener('submit', function() {
        spinner.classList.remove('d-none');
        button.setAttribute('disabled', 'disabled');
      });
    });
  </script>
  {{-- Script Hapus Jurnal --}}
  <script>
    document.querySelectorAll('.btn-delete').forEach(button => {
      button.addEventListener('click', function() {
        const jurnalId = this.dataset.id;

        Swal.fire({
          title: 'Yakin ingin menghapus?',
          text: "Data jurnal yang dihapus tidak bisa dikembalikan.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, Hapus!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            fetch(`/siswa/jurnal/${jurnalId}`, {
                method: 'DELETE',
                headers: {
                  'X-CSRF-TOKEN': '{{ csrf_token() }}',
                  'Accept': 'application/json'
                }
              })
              .then(async res => {
                if (!res.ok) {
                  const data = await res.json();
                  throw new Error(data.message || 'Terjadi kesalahan');
                }
                return res.json();
              })
              .then(data => {
                Swal.fire('Berhasil!', data.message, 'success').then(() => {
                  location.reload();
                });
              })
              .catch(err => {
                Swal.fire('Gagal!', err.message, 'error');
              });
          }
        });
      });
    });
  </script>w
@endpush
