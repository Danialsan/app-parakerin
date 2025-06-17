@extends('layouts.app')
@section('title', 'Administrator - PKL SMKN 1 BLEGA')

@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Informasi /</span> Informasi Ke Pembimbing dan Siswa
    </h4>

    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <div class="card">
      <div class="card-header d-flex justify-content-end gap-1">
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
          <i class="bx bx-plus"></i> Tambah Informasi
        </button>
      </div>
      {{-- Table --}}
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Judul</th>
                <th>Isi</th>
                <th class="text-end">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($informasi as $item)
                <tr>
                  <td>{{ $item->target_role ?? 'Umum' }}</td>
                  <td>{{ $item->isi }}</td>
                  <td>
                    <div class="d-grid gap-1 d-md-flex justify-content-md-end">
                      <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="{{ $item->id }}"
                        data-nama="{{ $item->judul }}">
                        <i class="bx bx-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              @endforeach
              @if ($informasi->isEmpty())
                <tr>
                  <td colspan="4" class="text-center">Tidak ada data!</td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
        <div class="d-flex justify-content-end mt-3">
          {{ $informasi->links() }}
        </div>
        <!-- /.table-responsive -->
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.container-xxl -->
  {{-- Modal Tambah --}}
  <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form action="{{ route('admin.informasi.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTambahLabel">Tambah Informasi</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="judul" class="form-label">Informasi untuk</label>
              <select name="target_role" class="form-select">
                <option value="">Umum (Semua Role)</option>
                <option value="siswa">Siswa</option>
                <option value="pembimbing">Pembimbing</option>
                <option value="dudi">DUDI</option>
                <option value="admin">Admin</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="isi" class="form-label">Isi</label>
              <textarea class="form-control @error('isi') is-invalid @enderror" name="isi" id="isi"
                value="{{ old('isi') }}" required>{{ old('isi') }}</textarea>
              @error('isi')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
@push('custom-script')
  <script>
    $(document).on('click', '.btn-delete', function() {
      const id = this.dataset.id;
      const nama = this.dataset.nama;

      Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Informasi \"" + nama + "\" akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(`/admin/informasi/${id}`, {
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
  </script>
@endpush
