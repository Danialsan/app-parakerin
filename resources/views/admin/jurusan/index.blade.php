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
      <div class="card-header d-flex justify-content-end">
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
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
                    <div class="d-flex flex-wrap gap-1 justify-content-end">
                      <button class="btn btn-sm btn-warning btn-edit" data-id="{{ $item->id }}"
                        data-kode="{{ $item->kode_jurusan }}" data-nama="{{ $item->nama_jurusan }}" data-bs-toggle="modal"
                        data-bs-target="#modalEdit" alt="Ubah Data">
                        <i class="bx bx-pencil"></i>
                      </button>

                      <button type="button" class="btn btn-sm me-1 btn-danger btn-delete" data-id="{{ $item->id }}"
                        data-nama="{{ $item->nama_jurusan }}" data-bs-toggle="modal" data-bs-target="#modalDelete">
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
  <!-- Modal Hapus -->
  <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="formDelete" method="POST">
        @csrf
        @method('DELETE')
        <input type="hidden" name="id" id="delete_id">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalDeleteLabel">Konfirmasi Hapus</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <p>Apakah Anda yakin ingin menghapus jurusan <strong id="deleteNama"></strong>?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-danger">Hapus</button>
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

      // --- Script Delete
      const formDelete = document.getElementById('formDelete');
      const deleteNama = document.getElementById('deleteNama');

      document.querySelectorAll('.btn-delete').forEach(button => {
        console.log("Button found:", button); // test
        button.addEventListener('click', function() {
          const id = this.dataset.id;
          const nama = this.dataset.nama;

          deleteNama.textContent = nama;
          delete_id.value = id;
          //   console.log("Tombol diklik");
          //   console.log("Data ID:", id);
          formDelete.action = `/admin/jurusan/${id}`;
        });
      });

    });
  </script>
@endpush
