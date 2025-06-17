@extends('layouts.app')
@section('title', 'Administrator - PKL SMKN 1 BLEGA')

@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Siswa /</span> Daftar Siswa</h4>
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
                <th>Nis</th>
                <th>Nama</th>
                <th>Kelas / Jurusan</th>
                <th>Email</th>
                <th>Jenis Kelamin</th>
                <th>Alamat</th>
                <th>Telepon</th>
                <th>Foto</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($siswa as $item)
                <tr>
                  <td>{{ $item->nis }}</td>
                  <td>{{ $item->nama }}</td>
                  <td>{{ $item->kelas }} / {{ $item->jurusan->nama_jurusan }}</td>
                  <td>{{ $item->user->email }}</td>
                  <td>{{ $item->gender }}</td>
                  <td>{{ $item->alamat }}</td>
                  <td>{{ $item->telepon }}</td>
                  <td><img src="{{ asset('storage/foto-siswa/' . $item->foto) }}" alt="Foto Siswa" width="30"
                      height="30" class="rounded-circle">
                  </td>
                  <td>
                    <div class="d-grid gap-1 d-md-flex justify-content-md-end">
                      <button type="button" class="btn btn-sm btn-warning btnEdit" data-id="{{ $item->id }}"
                        data-jurusan="{{ $item->jurusan->id }}" data-nis="{{ $item->nis }}"
                        data-nama="{{ $item->nama }}" data-email="{{ $item->user->email }}"
                        data-kelas="{{ $item->kelas }}" data-gender="{{ $item->gender }}"
                        data-alamat="{{ $item->alamat }}" data-telepon="{{ $item->telepon }}"
                        data-foto="{{ $item->foto }}" data-bs-toggle="modal" data-bs-target="#editModal">
                        <i class="bx bx-pencil"></i>
                      </button>

                      <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="{{ $item->id }}"
                        data-nama="{{ $item->nama }}"> <i class="bx bx-trash"></i>
                      </button>
                    </div>
                </tr>
            </tbody>
            @endforeach
            @if ($siswa->isEmpty())
              <tr>
                <td colspan="9" class="text-center">Tidak ada data!</td>
              </tr>
            @endif
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.container-xxl -->
  </div>

  <!-- Modal Tambah Data -->
  <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form action="{{ route('admin.siswa-admin.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTambahLabel">Tambah Siswa</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body row g-3">
            <div class="col-md-6">
              <label for="nis" class="form-label">NIS</label>
              <input type="text" class="form-control @error('nis') is-invalid @enderror" name="nis" id="nis"
                value="{{ old('nis') }}" required>
              @error('nis')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label for="nama" class="form-label">Nama</label>
              <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" id="nama"
                value="{{ old('nama') }}" required>
              @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label for="kelas" class="form-label">Kelas</label>
              <select name="kelas" id="kelas" class="form-select" required>
                <option disabled selected>-- Pilih Kelas --</option>
                <option value="XII">XII</option>
                <option value="XI">XI</option>
                <option value="X">X</option>
              </select>
              @error('kelas')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label for="jurusan" class="form-label">Jurusan</label>
              <select name="jurusan" id="jurusan" class="form-select" required>
                <option disabled selected>-- Pilih Jurusan --</option>
                @foreach ($jurusan as $item)
                  <option value="{{ $item->id }}">{{ $item->nama_jurusan }}</option>
                @endforeach
              </select>
              @error('jurusan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label for="gender" class="form-label">Jenis Kelamin</label>
              <select name="gender" id="gender" class="form-select" required>
                <option disabled selected>-- Pilih Jenis Kelamin --</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
              </select>
              @error('gender')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label for="alamat" class="form-label">Alamat</label>
              <input type="text" class="form-control @error('alamat') is-invalid @enderror" name="alamat"
                id="alamat" value="{{ old('alamat') }}" required>
              @error('alamat')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label for="telepon" class="form-label">Telepon</label>
              <input type="text" class="form-control @error('telepon') is-invalid @enderror" name="telepon"
                id="telepon" value="{{ old('telepon') }}" required>
              @error('telepon')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label for="email" class="form-label">Email</label>
              <input type="email" placeholder="example@example.com"
                class="form-control @error('email') is-invalid @enderror" name="email" id="email"
                value="{{ old('email') }}" required>
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <small>Email yang digunakan untuk login.</small>
            </div>
            <div class="col-md-6">
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
  <!-- Modal Edit Siswa -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" enctype="multipart/form-data" id="editForm">
        @csrf
        @method('PUT')
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Ubah Siswa</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <div class="col-md-6">
              <label for="edit_nis" class="form-label">NIS</label>
              <input type="text" name="nis" id="edit_nis" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="edit_nama" class="form-label">Nama</label>
              <input type="text" name="nama" id="edit_nama" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="edit_kelas" class="form-label">Kelas</label>
              <select name="kelas" id="edit_kelas" class="form-control" required>
                <option disabled selected>-- Pilih Kelas --</option>
                <option value="XII">XII</option>
                <option value="XI">XI</option>
                <option value="X">X</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="edit_jurusan" class="form-label">Jurusan</label>
              <select name="jurusan" id="edit_jurusan" class="form-control" required>
                <option disabled selected>-- Pilih Jurusan --</option>
                @foreach ($jurusan as $item)
                  <option value="{{ $item->id }}">{{ $item->nama_jurusan }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label for="edit_gender" class="form-label">Jenis Kelamin</label>
              <select name="gender" id="edit_gender" class="form-control" required>
                <option disabled selected>-- Pilih Jenis Kelamin --</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="edit_alamat" class="form-label">Alamat</label>
              <input type="text" name="alamat" id="edit_alamat" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="edit_telepon" class="form-label">Telepon</label>
              <input type="text" name="telepon" id="edit_telepon" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="edit_email" class="form-label">Email</label>
              <input type="email" name="email" id="edit_email" class="form-control" required>
            </div>
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="edit_reset_password" name="reset_password">
              <label class="form-check-label" for="edit_reset_password">Reset password ke default?</label>
              <small class="text-muted d-block">Password akan direset ke <code>siswa123</code></small>
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
  <!-- Modal Import -->
  <div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form action="{{ route('admin.siswa-admin.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalImportLabel">Unggah Siswa</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="file" class="form-label">Pilih file Excel (.xlsx)</label>
              <input type="file" name="file" class="form-control" required accept=".xlsx">
            </div>
            <div class="alert-info p-2">
              Gunakan format sesuai template. <br>
              <a href="{{ asset('template/template_siswa.xlsx') }}" class="btn btn-sm btn-outline-primary mt-2"
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
      const btnEdits = document.querySelectorAll('.btnEdit');
      const form = document.getElementById('editForm');
      const inputNama = document.getElementById('edit_nama');
      const inputEmail = document.getElementById('edit_email');
      const inputNis = document.getElementById('edit_nis');
      const inputFoto = document.getElementById('edit_foto');
      const previewFoto = document.getElementById('preview_edit_foto');
      const inputJurusan = document.getElementById('edit_jurusan');
      const inputKelas = document.getElementById('edit_kelas');
      const inputAlamat = document.getElementById('edit_alamat');
      const inputTelepon = document.getElementById('edit_telepon');
      const inputGender = document.getElementById('edit_gender');

      btnEdits.forEach(button => {
        button.addEventListener('click', function() {
          const id = this.dataset.id;
          const nama = this.dataset.nama;
          const email = this.dataset.email;
          const nis = this.dataset.nis;
          const foto = this.dataset.foto;
          const jurusan = this.dataset.jurusan;
          const kelas = this.dataset.kelas;
          const alamat = this.dataset.alamat;
          const telepon = this.dataset.telepon;
          const gender = this.dataset.gender;

          // Set form action
          form.action = `/admin/siswa-admin/${id}`;
          inputNama.value = nama;
          inputEmail.value = email;
          inputNis.value = nis;
          inputJurusan.value = jurusan;
          inputKelas.value = kelas;
          inputAlamat.value = alamat;
          inputTelepon.value = telepon;
          inputGender.value = gender;

          // Set preview foto
          if (foto) {
            previewFoto.src = `/storage/foto-siswa/${foto}`;
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
        const email = this.dataset.email;
        const nis = this.dataset.nis;
        const foto = this.dataset.foto;
        const jurusan = this.dataset.jurusan;
        const kelas = this.dataset.kelas;

        Swal.fire({
          title: 'Yakin ingin menghapus?',
          text: `Siswa "${nama}" akan dihapus permanen!`,
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
            form.action = `/admin/siswa-admin/${id}`;
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
