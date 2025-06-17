@extends('layouts.app')
@section('title', 'Administrator - PKL SMKN 1 BLEGA')

@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pembimbing Sekolah /</span> Daftar Pembimbing Sekolah
    </h4>
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
                <th>Nama Pembimbing</th>
                <th>NIP</th>
                <th>Jabatan</th>
                <th>Email</th>
                <th>Foto</th>
                <th class="text-end">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($pembimbingSekolah as $item)
                <tr>
                  <td>{{ $item->nama_pembimbing }}</td>
                  <td>{{ $item->nip ?? '-' }}</td>
                  <td>{{ $item->jabatan }}</td>
                  <td>{{ $item->user->email }}</td>
                  <td><img src="{{ asset('storage/foto-pembimbing/' . $item->foto) }}" alt="Foto Pembimbing"
                      width="40" height="40" class="rounded-circle">
                  </td>
                  <td>
                    <div class="d-grid gap-1 d-md-flex justify-content-md-end">
                      <button type="button" class="btn btn-sm btn-warning btnEdit" data-id="{{ $item->id }}"
                        data-nama="{{ $item->nama_pembimbing }}" data-email="{{ $item->user->email }}"
                        data-nip="{{ $item->nip }}" data-jabatan="{{ $item->jabatan }}"
                        data-foto="{{ $item->foto }}" data-bs-toggle="modal" data-bs-target="#editModal">
                        <i class="bx bx-pencil"></i>
                      </button>

                      <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="{{ $item->id }}"
                        data-nama="{{ $item->nama_pembimbing }}" data-nip="{{ $item->nip }}"
                        data-jabatan="{{ $item->jabatan }}">
                        <i class="bx bx-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              @endforeach
              @if ($pembimbingSekolah->isEmpty())
                <tr>
                  <td colspan="6" class="text-center">Tidak ada data!</td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
        <div class="d-flex justify-content-end">
          {{ $pembimbingSekolah->links() }}
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
      <form action="{{ route('admin.pembimbing-sekolah-admin.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTambahLabel">Tambah Pembimbing Sekolah</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="nama_pembimbing" class="form-label">Nama Pembimbing</label>
              <input type="text" class="form-control @error('nama_pembimbing') is-invalid @enderror"
                name="nama_pembimbing" id="nama_pembimbing" value="{{ old('nama_pembimbing') }}" required>
              @error('nama_pembimbing')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3">
              <label for="nip" class="form-label">NIP</label>
              <input type="text" class="form-control @error('nip') is-invalid @enderror" name="nip" id="nip"
                value="{{ old('nip') }}" placeholder="Boleh dikosongkan">
              @error('nip')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3">
              <label for="jabatan" class="form-label">Jabatan</label>
              <select name="jabatan" id="jabatan" class="form-select" required>
                <option disabled selected>-- Pilih Jabatan --</option>
                <option value="Guru RPL">Guru RPL</option>
                <option value="Guru TKJ">Guru TKJ</option>
                <option value="Guru TSM">Guru TSM</option>
                <option value="Guru AKL">Guru AKL</option>
                <option value="Guru">Guru</option>
              </select>
              @error('jabatan')
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
            <div class="mb-3">
              <label for="foto" class="form-label">Foto</label>
              <input type="file" name="foto" class="form-control" accept=".jpg,.png,.jpeg">
              <small>Format file yang diizinkan: jpg, png, jpeg (maks 2MB)</small>
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
  <!-- Modal Edit Pembimbing Sekolah -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" enctype="multipart/form-data" id="editForm">
        @csrf
        @method('PUT')
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Pembimbing Sekolah</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="edit_nama" class="form-label">Nama</label>
              <input type="text" name="nama_pembimbing" id="edit_nama" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="edit_nip" class="form-label">NIP</label>
              <input type="text" name="nip" id="edit_nip" class="form-control"
                placeholder="Boleh dikosongkan">
            </div>
            <div class="mb-3">
              <label for="edit_jabatan" class="form-label">Jabatan</label>
              <select name="jabatan" id="edit_jabatan" class="form-select" required>
                <option disabled selected>-- Pilih Jabatan --</option>
                <option value="Guru RPL">Guru RPL</option>
                <option value="Guru TKJ">Guru TKJ</option>
                <option value="Guru TSM">Guru TSM</option>
                <option value="Guru AKL">Guru AKL</option>
                <option value="Guru">Guru</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="edit_email" class="form-label">Email</label>
              <input type="email" name="email" id="edit_email" class="form-control" required>
            </div>
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="edit_reset_password" name="reset_password">
              <label class="form-check-label" for="edit_reset_password">Reset password ke default?</label>
              <small class="text-muted d-block">Password akan direset ke <code>pembimbing123</code></small>
            </div>

            <div class="mb-3">
              <label for="edit_foto" class="form-label">Foto (opsional)</label>
              <input type="file" name="foto" id="edit_foto" class="form-control" accept=".jpg,.jpeg,.png">
              <img id="preview_edit_foto" src="#" alt="Preview Foto"
                style="display: none; width: 120px; margin-top: 10px;">
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form action="{{ route('admin.pembimbing-sekolah-admin.import') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalImportLabel">Unggah Pembimbing Sekolah</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="file" class="form-label">Pilih file Excel (.xlsx)</label>
              <input type="file" name="file" class="form-control" required accept=".xlsx">
            </div>
            <div class="alert-info p-2">
              Gunakan format sesuai template. <br>
              <a href="{{ asset('template/template_pembimbing_sekolah.xlsx') }}"
                class="btn btn-sm btn-outline-primary mt-2" download>
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
      const btnEdits = document.querySelectorAll('.btnEdit');
      const form = document.getElementById('editForm');
      const inputNama = document.getElementById('edit_nama');
      const inputEmail = document.getElementById('edit_email');
      const inputNip = document.getElementById('edit_nip');
      const inputFoto = document.getElementById('edit_foto');
      const previewFoto = document.getElementById('preview_edit_foto');
      const inputJabatan = document.getElementById('edit_jabatan');

      btnEdits.forEach(button => {
        button.addEventListener('click', function() {
          const id = this.dataset.id;
          const nama = this.dataset.nama;
          const email = this.dataset.email;
          const nip = this.dataset.nip;
          const foto = this.dataset.foto;
          const jabatan = this.dataset.jabatan;

          // Set form action
          form.action = `/admin/pembimbing-sekolah-admin/${id}`;
          inputNama.value = nama;
          inputEmail.value = email;
          inputNip.value = nip;
          inputJabatan.value = jabatan;

          // Set preview foto
          if (foto) {
            previewFoto.src = `/storage/foto-pembimbing/${foto}`;
            previewFoto.style.display = 'block';
          } else {
            previewFoto.src = '#';
            previewFoto.style.display = 'none';
          }

          // Reset input file
          inputFoto.value = '';
        });
      });

      // Preview saat memilih file baru
      inputFoto.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
            previewFoto.src = e.target.result;
            previewFoto.style.display = 'block';
          };
          reader.readAsDataURL(file);
        }
      });
    });
  </script>
  <script>
    // --- Script Delete
    document.querySelectorAll('.btn-delete').forEach(button => {
      button.addEventListener('click', function() {
        const id = this.dataset.id;
        const nama = this.dataset.nama;
        const nip = this.dataset.nip;
        const jabatan = this.dataset.jabatan;
        const email = this.dataset.email;

        Swal.fire({
          title: 'Yakin ingin menghapus?',
          text: `Pembimbing Sekolah "${nama}" akan dihapus permanen!`,
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
            form.action = `/admin/pembimbing-sekolah-admin/${id}`;
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
