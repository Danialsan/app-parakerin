@extends('layouts.app')
@section('title', 'Administrator - PKL SMKN 1 BLEGA')

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
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
          <i class="bx bx-plus"></i> Tambah Data
        </button>
        <!-- Tombol Import -->
        <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalImport">
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
                    <div class="d-grid gap-1 d-md-flex justify-content-md-end">
                      <button class="btn btn-sm btn-warning btn-edit" data-id="{{ $item->id }}"
                        data-jurusan="{{ $item->jurusan->id }}" data-deskripsi="{{ $item->deskripsi_cp }}"
                        data-bs-toggle="modal" data-bs-target="#modalEdit">
                        <i class="bx bx-pencil"></i>
                      </button>

                      <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="{{ $item->id }}"
                        data-deskripsi="{{ $item->deskripsi_cp }}"><i class="bx bx-trash"></i>
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
            <div class="mb-3">
              <label for="file" class="form-label">Pilih file Excel (.xlsx)</label>
              <input type="file" name="file" class="form-control" required accept=".xlsx">
            </div>
            <div class="alert-info p-2">
              Gunakan format sesuai template. <br>
              <a href="{{ asset('template/template_cp.xlsx') }}" class="btn btn-sm btn-outline-primary mt-2" download>
                <i class="bx bx-download"></i> Unduh Template Excel
              </a>
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
  {{-- </div> --> --}}
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
    });
  </script>
  <script>
    // --- Script Delete
    document.querySelectorAll('.btn-delete').forEach(button => {
      button.addEventListener('click', function() {
        const id = this.dataset.id;
        // console.log('ID yang akan dihapus:', id);
        const deskripsi = this.dataset.deskripsi;

        Swal.fire({
          title: 'Yakin ingin menghapus?',
          text: `CP "${deskripsi}" akan dihapus permanen!`,
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
            form.action = `/admin/capaian-pembelajaran/${id}`;
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
