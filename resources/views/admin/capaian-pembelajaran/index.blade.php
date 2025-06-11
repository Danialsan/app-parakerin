@extends('layouts.app')

@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Capaian Pembelajaran /</span> Daftar CP PKL</h4>
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
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
          <i class="bx bx-plus"></i> Tambah Data
        </button>
        <!-- Tombol Import -->
        <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalImport">
          <i class="bx bx-upload"></i> Unggah Data
        </button>
      </div>

      <!-- /.card-header -->
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Jurusan</th>
                <th>Deskripsi CP</th>
                <th class="text-end">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($capaianPembelajaran as $item)
                <tr>
                  <td>{{ $item->jurusan->nama_jurusan }}</td>
                  <td>{{ $item->deskripsi_cp }}</td>
                  <td>
                    <div class="d-flex flex-wrap gap-1 justify-content-end">
                      <button class="btn btn-sm btn-warning btn-edit" data-id="{{ $item->id }}"
                        data-jurusan="{{ $item->jurusan->id }}" data-deskripsi="{{ $item->deskripsi_cp }}"
                        data-bs-toggle="modal" data-bs-target="#modalEdit">
                        <i class="bx bx-pencil"></i>
                      </button>

                      <button type="button" class="btn btn-sm me-1 btn-danger btn-delete" data-id="{{ $item->id }}"
                        data-deskripsi="{{ $item->deskripsi_cp }}" data-bs-toggle="modal" data-bs-target="#modalDelete">
                        <i class="bx bx-trash"></i>
                      </button>

                    </div>
                  </td>
                </tr>
              @endforeach
              @if ($capaianPembelajaran->isEmpty())
                <tr>
                  <td colspan="4" class="text-center">Tidak ada data!</td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
        <div class="d-flex justify-content-end">
          {{ $capaianPembelajaran->links() }}
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
      <form action="{{ route('admin.capaian-pembelajaran.store') }}" method="POST">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTambahLabel">Tambah Jurusan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="jurusan" class="form-label">Jurusan</label>
              <select class="form-select" name="jurusan_id" id="jurusan" required>
                <option disabled selected>-- Pilih Jurusan --</option>
                @foreach ($jurusan as $item)
                  <option value="{{ $item->id }}">{{ $item->nama_jurusan }}</option>
                @endforeach
              </select>
              @error('jurusan_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3">
              <label for="deskripsi" class="form-label">Deskripsi CP</label>
              <input type="text" class="form-control @error('deskripsi_cp') is-invalid @enderror" name="deskripsi_cp"
                id="deskripsi" value="{{ old('deskripsi_cp') }}" required>
              @error('deskripsi_cp')
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
            <h5 class="modal-title" id="modalEditLabel">Ubah Deskripsi CP</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="edit_jurusan" class="form-label">Jurusan</label>
              <select class="form-select" name="jurusan_id" id="edit_jurusan" required>
                {{-- <option disabled selected>-- Pilih Jurusan --</option> --}}
                @foreach ($jurusan as $item)
                  <option value="{{ $item->id }}">{{ $item->nama_jurusan }}</option>
                @endforeach
              </select>
              @error('jurusan_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3">
              <label for="edit_deskripsi" class="form-label">Deskripsi CP</label>
              <input type="text" class="form-control @error('deskripsi_cp') is-invalid @enderror"
                name="deskripsi_cp" id="edit_deskripsi" required>

              @error('deskripsi_cp')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
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
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Apakah Anda yakin ingin menghapus CP : <strong id="deleteDeskripsi_cp"></strong>?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-danger">Hapus</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <!-- Modal Import -->
  <div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form action="{{ route('admin.capaian-pembelajaran.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalImportLabel">Unggah Capaian Pembelajaran</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="alert alert-info" role="alert">
              Gunakan format sesuai template. <br>
              <a href="{{ asset('template/template_cp.xlsx') }}" class="btn btn-sm btn-outline-primary mt-2" download>
                <i class="bx bx-download"></i> Unduh Template Excel
              </a>
            </div>
            <div class="mb-3">
              <label for="file" class="form-label">Pilih file Excel (.xlsx)</label>
              <input type="file" name="file" class="form-control" required accept=".xlsx">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success">Unggah</button>
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
      const inputJurusan = document.getElementById('edit_jurusan');
      const inputDeskripsi = document.getElementById('edit_deskripsi');
      const inputId = document.getElementById('edit_id');

      document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function() {
          const id = this.dataset.id;
          const nama = this.dataset.nama;
          const jurusan = this.dataset.jurusan;
          const deskripsi = this.dataset.deskripsi;

          inputId.value = id;
          inputJurusan.value = jurusan;
          inputDeskripsi.value = deskripsi;

          formEdit.action = `/admin/capaian-pembelajaran/${id}`;
        });
      });

      // --- Script Delete
      const formDelete = document.getElementById('formDelete');
      const deleteDeskripsi_cp = document.getElementById('deleteDeskripsi_cp');
      const delete_id = document.getElementById('delete_id');

      document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
          const id = this.dataset.id;
          const deskripsi = this.dataset.deskripsi;

          deleteDeskripsi_cp.textContent = deskripsi;
          delete_id.value = id;
          formDelete.action = `/admin/capaian-pembelajaran/${id}`;
        });
      });

    });
  </script>
@endpush
