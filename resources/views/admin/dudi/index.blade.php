@extends('layouts.app')
@section('title', 'Administrator - PKL SMKN 1 BLEGA')

@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dudi /</span> Daftar Dudi</h4>
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
                <th>Nama</th>
                <th>Alamat</th>
                <th>Bidang Usaha</th>
                <th>Pimpinan</th>
                <th>Pembimbing</th>
                <th>Email</th>
                <th class="text-end">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($dudi as $item)
                <tr>
                  <td>{{ $item->nama_perusahaan }}</td>
                  <td>{{ $item->alamat_dudi }}</td>
                  <td>{{ $item->bidang_usaha }}</td>
                  <td>{{ $item->pimpinan_dudi }}</td>
                  <td>{{ $item->nama_pembimbing }}</td>
                  <td>{{ $item->user->email }}</td>
                  <td>
                    <div class="d-flex flex-wrap gap-1 justify-content-end">
                      <button class="btn btn-sm btn-warning btn-edit" data-id="{{ $item->id }}"
                        data-nama="{{ $item->nama_perusahaan }}" data-alamat="{{ $item->alamat_dudi }}"
                        data-bidang="{{ $item->bidang_usaha }}" data-pimpinan="{{ $item->pimpinan_dudi }}"
                        data-pembimbing="{{ $item->nama_pembimbing }}" data-email="{{ $item->user->email }}"
                        data-bs-toggle="modal" data-bs-target="#modalEdit" alt="Ubah Data">
                        <i class="bx bx-pencil"></i>
                      </button>

                      <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="{{ $item->id }}"
                        data-nama="{{ $item->nama_perusahaan }}">
                        <i class="bx bx-trash"></i>
                      </button>

                    </div>
                  </td>
                </tr>
              @endforeach
              @if ($dudi->isEmpty())
                <tr>
                  <td colspan="8" class="text-center">Tidak ada data!</td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
        <div class="d-flex justify-content-end">
          {{ $dudi->links() }}
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
      <form action="{{ route('admin.dudi-admin.store') }}" method="POST">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTambahLabel">Tambah Dudi</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="nama_perusahaan" class="form-label">Nama Perusahaan</label>
              <input type="text" class="form-control @error('nama_perusahaan') is-invalid @enderror"
                name="nama_perusahaan" id="nama_perusahaan" value="{{ old('nama_perusahaan') }}" required>
              @error('nama_perusahaan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3">
              <label for="alamat_dudi" class="form-label">Alamat Dudi</label>
              <input type="text" class="form-control @error('alamat_dudi') is-invalid @enderror" name="alamat_dudi"
                id="alamat_dudi" value="{{ old('alamat_dudi') }}" required>
              @error('alamat_dudi')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3">
              <label for="bidang_usaha" class="form-label">Bidang Usaha</label>
              <input type="text" class="form-control @error('bidang_usaha') is-invalid @enderror" name="bidang_usaha"
                id="bidang_usaha" value="{{ old('bidang_usaha') }}" required>
              @error('bidang_usaha')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3">
              <label for="pimpinan_dudi" class="form-label">Pimpinan Dudi</label>
              <input type="text" class="form-control @error('pimpinan_dudi') is-invalid @enderror"
                name="pimpinan_dudi" id="pimpinan_dudi" value="{{ old('pimpinan_dudi') }}" required>
              @error('pimpinan_dudi')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3">
              <label for="nama_pembimbing" class="form-label">Nama Pembimbing</label>
              <input type="text" class="form-control @error('nama_pembimbing') is-invalid @enderror"
                name="nama_pembimbing" id="nama_pembimbing" value="{{ old('nama_pembimbing') }}" required>
              @error('nama_pembimbing')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" placeholder="example@example.com"
                class="form-control @error('email') is-invalid @enderror" name="email" id="email"
                value="{{ old('email') }}" required>
              @error('email')
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
  <!-- Modal Edit Data -->
  <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="formEdit" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" id="edit_id">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalEditLabel">Edit Dudi</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="edit_nama_perusahaan" class="form-label">Nama Perusahaan</label>
              <input type="text" class="form-control" name="nama_perusahaan" id="edit_nama_perusahaan" required>
            </div>
            <div class="mb-3">
              <label for="edit_alamat_dudi" class="form-label">Alamat Dudi</label>
              <input type="text" class="form-control" name="alamat_dudi" id="edit_alamat_dudi" required>
            </div>
            <div class="mb-3">
              <label for="edit_bidang_usaha" class="form-label">Bidang Usaha</label>
              <input type="text" class="form-control" name="bidang_usaha" id="edit_bidang_usaha" required>
            </div>
            <div class="mb-3">
              <label for="edit_pimpinan_dudi" class="form-label">Pimpinan Dudi</label>
              <input type="text" class="form-control" name="pimpinan_dudi" id="edit_pimpinan_dudi" required>
            </div>
            <div class="mb-3">
              <label for="edit_pembimbing" class="form-label">Pembimbing</label>
              <input type="text" class="form-control" name="nama_pembimbing" id="edit_pembimbing" required>
            </div>
            <div class="mb-3">
              <label for="edit_email" class="form-label">Email</label>
              <input type="email" placeholder="example@example.com" class="form-control" name="email"
                id="edit_email" required>
            </div>
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="edit_reset_password" name="reset_password">
              <label class="form-check-label" for="edit_reset_password">Reset password ke default?</label>
              <small class="text-muted d-block">Password akan direset ke <code>dudi123</code></small>
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
      <form action="{{ route('admin.dudi-admin.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalImportLabel">Unggah Dudi</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="file" class="form-label">Pilih file Excel (.xlsx)</label>
              <input type="file" name="file" class="form-control" required accept=".xlsx">
            </div>
            <div class="alert-info p-2">
              Gunakan format sesuai template. <br>
              <a href="{{ asset('template/template_dudi.xlsx') }}" class="btn btn-sm btn-outline-primary mt-2"
                download>
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
@endsection
@push('custom-script')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const inputId = document.getElementById('edit_id');
      const inputNama = document.getElementById('edit_nama_perusahaan');
      const inputAlamat = document.getElementById('edit_alamat_dudi');
      const inputBidang = document.getElementById('edit_bidang_usaha');
      const inputPimpinan = document.getElementById('edit_pimpinan_dudi');
      const inputPembimbing = document.getElementById('edit_pembimbing');
      const inputEmail = document.getElementById('edit_email');
      const formEdit = document.getElementById('formEdit');

      // --- Script Edit
      document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function() {
          const id = this.dataset.id;
          const nama = this.dataset.nama;
          const alamat = this.dataset.alamat;
          const bidang = this.dataset.bidang;
          const pimpinan = this.dataset.pimpinan;
          const pembimbing = this.dataset.pembimbing;
          const email = this.dataset.email;

          inputId.value = id;
          inputNama.value = nama;
          inputAlamat.value = alamat;
          inputBidang.value = bidang;
          inputPimpinan.value = pimpinan;
          inputPembimbing.value = pembimbing;
          inputEmail.value = email;

          formEdit.action = `/admin/dudi-admin/${id}`;
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
          text: `Data Dudi "${nama}" akan dihapus permanen!`,
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
            form.action = `/admin/dudi-admin/${id}`;
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
