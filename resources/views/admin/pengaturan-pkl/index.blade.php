@extends('layouts.app')
@section('title', 'Administrator - PKL SMKN 1 BLEGA')

@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan Pkl /</span> Daftar Pengaturan Pkl</h4>
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
      <div class="card-header">
        <form action="{{ route('admin.pengaturan-pkl.index') }}" method="GET">
          <div class="row g-2 align-items-end">
            <!-- Filter Jurusan -->
            <div class="col-12 col-md-3">
              <select name="jurusan_id" class="form-select form-select-sm">
                <option value="">-- Semua Jurusan --</option>
                @foreach ($jurusan as $j)
                  <option value="{{ $j->id }}" {{ request('jurusan_id') == $j->id ? 'selected' : '' }}>
                    {{ $j->nama_jurusan }}
                  </option>
                @endforeach
              </select>
            </div>

            <!-- Filter DUDI -->
            <div class="col-12 col-md-3">
              <select name="dudi_id" class="form-select form-select-sm">
                <option value="">-- Semua DUDI --</option>
                @foreach ($dudi as $d)
                  <option value="{{ $d->id }}" {{ request('dudi_id') == $d->id ? 'selected' : '' }}>
                    {{ $d->nama_perusahaan }}
                  </option>
                @endforeach
              </select>
            </div>

            <!-- Tombol Cari dan Reset -->
            <div class="col-12 col-md-3 d-flex gap-2">
              <button type="submit" class="btn btn-sm btn-outline-secondary w-100">
                <i class="bx bx-search me-1"></i> Cari
              </button>
              <a href="{{ route('admin.pengaturan-pkl.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                <i class="bx bx-refresh me-1"></i> Reset
              </a>
            </div>

            <!-- Tombol Aksi -->
            <div class="col-12 col-md-3 d-flex justify-content-md-end gap-2">
              <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bx bx-plus me-1"></i> Tambah Data
              </button>
              <a href="{{ route('admin.pengaturan-pkl.download', request()->only(['jurusan_id', 'dudi_id'])) }}"
                class="btn btn-sm btn-outline-success">
                <i class="bx bx-download me-1"></i> Unduh Data
              </a>
            </div>
          </div>
        </form>
      </div>

      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Nama Siswa</th>
              <th>Jurusan</th>
              <th>Dudi</th>
              <th>Pembimbing Dudi</th>
              <th>Pembimbing Sekolah</th>
              <th>Tanggal Mulai</th>
              <th>Tanggal Selesai</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($pengaturanPkl as $item)
              <tr>
                <td>{{ $item->siswa->nama }}</td>
                <td>{{ $item->jurusan->nama_jurusan }}</td>
                <td>{{ $item->dudi->nama_perusahaan }}</td>
                <td>{{ $item->dudi->nama_pembimbing }}</td>
                <td>{{ $item->pembimbing->nama_pembimbing }}</td>
                <td>{{ $item->mulai }}</td>
                <td>{{ $item->selesai }}</td>
                <td>
                  <div class="d-grid gap-1 d-md-flex justify-content-md-end">
                    <button type="button" class="btn btn-warning btn-sm btnEdit" data-id="{{ $item->id }}"
                      data-dudi="{{ $item->dudi_id }}" data-pembimbing="{{ $item->pembimbing_sekolah_id }}"
                      data-jurusan="{{ $item->jurusan_id }}" data-siswa="{{ $item->siswa_id }}"
                      data-mulai="{{ $item->mulai }}" data-selesai="{{ $item->selesai }}" data-bs-toggle="modal"
                      data-bs-target="#editModal">
                      <i class="bx bx-pencil"></i>
                    </button>

                    <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="{{ $item->id }}"
                      data-nama="{{ $item->siswa->nama }}"> <i class="bx bx-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @endforeach

            @if ($pengaturanPkl->isEmpty())
              <tr>
                <td colspan="8" class="text-center">Tidak ada data!</td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-end">
        {{ $pengaturanPkl->links() }}
      </div>
      <!-- /.table-responsive -->
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
  </div>

  <!-- Modal Tambah -->
  <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form id="formTambah" action="{{ route('admin.pengaturan-pkl.store') }}" method="POST">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTambahLabel">Tambah Pengaturan PKL</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body row g-3">

            <div class="col-md-6">
              <label for="dudi_id" class="form-label">Pilih DUDI</label>
              <select name="dudi_id" id="dudi_id" class="form-select" required>
                <option value="">-- Pilih DUDI --</option>
                @foreach ($dudi as $item)
                  <option value="{{ $item->id }}">{{ $item->nama_perusahaan }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label for="pembimbing_sekolah_id" class="form-label">Pilih Pembimbing Sekolah</label>
              <select name="pembimbing_sekolah_id" id="pembimbing_sekolah_id" class="form-select" required>
                <option value="">-- Pilih Pembimbing --</option>
                @foreach ($pembimbing as $item)
                  <option value="{{ $item->id }}">{{ $item->nama_pembimbing }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label for="jurusan_id" class="form-label">Pilih Jurusan</label>
              <select name="jurusan_id" id="jurusan_id" class="form-select" required>
                <option value="">-- Pilih Jurusan --</option>
                @foreach ($jurusan as $item)
                  <option value="{{ $item->id }}">{{ $item->nama_jurusan }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label for="siswa_id" class="form-label">Pilih Siswa</label>
              <select name="siswa_id[]" id="siswa_id" class="form-select" multiple required>
                <option value="">-- Pilih Jurusan Dulu --</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="mulai" class="form-label">Tanggal Mulai</label>
              <input type="date" name="mulai" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label for="selesai" class="form-label">Tanggal Selesai</label>
              <input type="date" name="selesai" class="form-control" required>
            </div>

          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <!-- Modal Edit -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form id="formEdit" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Pengaturan PKL</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body row g-3">

            <input type="hidden" id="edit_id">

            <!-- isi sama seperti modal tambah -->
            <div class="col-md-6">
              <label for="edit_dudi_id" class="form-label">DUDI</label>
              <select id="edit_dudi_id" class="form-select" name="dudi_id" required>
                @foreach ($dudi as $item)
                  <option value="{{ $item->id }}">{{ $item->nama_perusahaan }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label for="edit_pembimbing_sekolah_id" class="form-label">Pembimbing Sekolah</label>
              <select id="edit_pembimbing_sekolah_id" class="form-select" name="pembimbing_sekolah_id" required>
                @foreach ($pembimbing as $item)
                  <option value="{{ $item->id }}">{{ $item->nama_pembimbing }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label for="edit_jurusan_id" class="form-label">Jurusan</label>
              <select id="edit_jurusan_id" class="form-select" name="jurusan_id" required>
                @foreach ($jurusan as $item)
                  <option value="{{ $item->id }}">{{ $item->nama_jurusan }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label for="edit_siswa_id" class="form-label">Siswa</label>
              <select id="edit_siswa_id" name="siswa_id" class="form-select" required>
                <option value="">-- Pilih Siswa --</option>
                @foreach ($siswa as $item)
                  <option value="{{ $item->id }}">{{ $item->nama }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label for="edit_mulai" class="form-label">Tanggal Mulai</label>
              <input type="date" id="edit_mulai" name="mulai" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label for="edit_selesai" class="form-label">Tanggal Selesai</label>
              <input type="date" id="edit_selesai" name="selesai" class="form-control" required>
            </div>

          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
@push('custom-script')
  <script>
    $(document).ready(function() {
      $('#jurusan_id').on('change', function() {
        let jurusanId = $(this).val();
        if (jurusanId) {
          $.ajax({
            url: "{{ route('admin.siswa.by.jurusan') }}",
            type: "GET",
            data: {
              jurusan_id: jurusanId
            },
            success: function(data) {
              $('#siswa_id').empty();
              if (data.length === 0) {
                $('#siswa_id').append('<option value="">Tidak ada siswa tersedia</option>');
              } else {
                data.forEach(function(siswa) {
                  $('#siswa_id').append(`<option value="${siswa.id}">${siswa.nama}</option>`);
                });
              }
            }
          });
        } else {
          $('#siswa_id').empty().append('<option value="">-- Pilih Jurusan Dulu --</option>');
        }
      });
    });
    $(document).on('click', '.btnEdit', function() {
      const id = $(this).data('id');
      const dudi = $(this).data('dudi');
      const pembimbing = $(this).data('pembimbing');
      const jurusan = $(this).data('jurusan');
      const siswa = $(this).data('siswa'); // array
      const mulai = $(this).data('mulai');
      const selesai = $(this).data('selesai');

      $('#edit_id').val(id);
      $('#edit_dudi_id').val(dudi);
      $('#edit_pembimbing_sekolah_id').val(pembimbing);
      $('#edit_jurusan_id').val(jurusan);
      $('#edit_siswa_id').val(siswa);
      $('#edit_mulai').val(mulai);
      $('#edit_selesai').val(selesai);
      $('#formEdit').attr('action', '/admin/pengaturan-pkl/' + id);
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
          text: `Pengaturan PKL "${nama}" akan dihapus permanen!`,
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
            form.action = `/admin/pengaturan-pkl/${id}`;
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
