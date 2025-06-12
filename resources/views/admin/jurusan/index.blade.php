@extends('layouts.app')

@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Jurusan /</span> Daftar Jursan</h4>
    @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill"></i>
        <span>{{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if (session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-x-circle-fill"></i>
        <span>{{ session('error') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="card">
      <div class="card-header d-flex justify-content-end gap-1">
        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
          <i class="bx bx-plus"></i> Tambah Data
        </button>

      </div>

      <!-- /.card-header -->
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th class="text-end">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($jurusan as $item)
                <tr>
                  <td>{{ $item->kode_jurusan }}</td>
                  <td>{{ $item->nama_jurusan }}</td>
                  <td>
                    <div class="d-grid gap-1 d-md-flex justify-content-md-end">
                      <button class="btn btn-sm btn-warning btn-edit" data-id="{{ $item->id }}"
                        data-kode="{{ $item->kode_jurusan }}" data-nama="{{ $item->nama_jurusan }}" data-bs-toggle="modal"
                        data-bs-target="#modalEdit" alt="Ubah Data">
                        <i class="bx bx-pencil"></i>
                      </button>

                      <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="{{ $item->id }}"
                        data-nama="{{ $item->nama_jurusan }}">
                        <i class="bx bx-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              @endforeach
              @if ($jurusan->isEmpty())
                <tr>
                  <td colspan="4" class="text-center">Tidak ada data!</td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
        <div class="d-flex justify-content-end">
          {{ $jurusan->links() }}
        </div>
        <!-- /.table-responsive -->
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>

  <!-- Modal Tambah Data -->
  <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form action="{{ route('admin.jurusan.store') }}" method="POST">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTambahLabel">Tambah Jurusan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="kode_jurusan" class="form-label">Kode Jurusan</label>
              <input type="text" placeholder="RPL / TKJ / TSM / AKL"
                class="form-control @error('kode_jurusan') is-invalid @enderror" name="kode_jurusan" id="kode_jurusan"
                value="{{ old('kode_jurusan') }}" required>
              @error('kode_jurusan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3">
              <label for="nama_jurusan" class="form-label">Nama Jurusan</label>
              <input type="text" class="form-control @error('nama_jurusan') is-invalid @enderror" name="nama_jurusan"
                id="nama_jurusan" value="{{ old('nama_jurusan') }}" required>
              @error('nama_jurusan')
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
  <!-- Modal Edit Data -->
  <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="formEdit" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" id="edit_id">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalEditLabel">Edit Jurusan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="edit_kode_jurusan" class="form-label">Kode Jurusan</label>
              <input type="text" class="form-control" name="kode_jurusan" id="edit_kode_jurusan" required>
            </div>
            <div class="mb-3">
              <label for="edit_nama_jurusan" class="form-label">Nama Jurusan</label>
              <input type="text" class="form-control" name="nama_jurusan" id="edit_nama_jurusan" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Ubah</button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
@push('custom-script')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // --- Script Edit
      const formEdit = document.getElementById('formEdit');
      const inputKode = document.getElementById('edit_kode_jurusan');
      const inputNama = document.getElementById('edit_nama_jurusan');
      const inputId = document.getElementById('edit_id');

      document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function() {
          const id = this.dataset.id;
          const kode = this.dataset.kode;
          const nama = this.dataset.nama;

          inputId.value = id;
          inputKode.value = kode;
          inputNama.value = nama;

          formEdit.action = `/admin/jurusan/${id}`;
        });
      });
    });
  </script>
  <script>
    // --- Script Delete
    document.querySelectorAll('.btn-delete').forEach(button => {
      button.addEventListener('click', function() {
        const id = this.dataset.id;
        const nama = this.dataset.nama;
        Swal.fire({
          title: 'Yakin ingin menghapus?',
          text: `Jurusan "${nama}" akan dihapus permanen!`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, Hapus!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            // Kirim form hapus lewat JS
            const form = document.createElement('form');
            form.action = `/admin/jurusan/${id}`;
            form.method = 'POST';
            const token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = '{{ csrf_token() }}';
            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(token);
            form.appendChild(method);
            document.body.appendChild(form);
            const inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = 'id';
            inputId.value = id;
            form.appendChild(inputId);
            form.submit();
          }
        });
      });
    });
  </script>
@endpush
