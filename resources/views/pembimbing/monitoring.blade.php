@extends('layouts.app')
@section('title', 'Pembimbing - PKL SMKN 1 BLEGA')
@push('custom-style')
  <style>
    .nilai-select {
      transition: background-color 0.3s ease;
      color: #000;
      font-weight: bold;
      text-align: center;
    }

    #btn-simpan .spinner-border {
      vertical-align: text-bottom;
    }

    .nilai-select.is-invalid {
      border-color: #dc3545;
      background-color: #f8d7da;
    }
  </style>
@endpush
@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Kunjungan /</span> Isi Kunjungan</h4>

    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('pembimbing.kunjungan.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="card mb-4">
        <div class="card-body row g-3">
          <div class="col-md-4">
            <label for="dudi_id" class="form-label">Pilih DUDI</label>
            <select name="dudi_id" id="dudi_id" class="form-select" required>
              <option value="">-- Pilih DUDI --</option>
              @foreach ($dudi as $item)
                <option value="{{ $item->id }}">{{ $item->nama_perusahaan }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label for="keperluan" class="form-label">Keperluan</label>
            <select name="keperluan" id="keperluan" class="form-select" required>
              <option value="">-- Pilih Keperluan --</option>
              <option value="pengantaran">Pengantaran</option>
              <option value="monitoring 1">Monitoring 1</option>
              <option value="monitoring 2">Monitoring 2</option>
              <option value="monitoring 3">Monitoring 3</option>
              <option value="penjemputan">Penjemputan</option>
            </select>
            <small id="keperluan-alert" class="text-danger mt-1 d-block"></small>
          </div>
          <div class="col-md-4">
            <label for="foto" class="form-label">Unggah Foto Dokumentasi</label>
            <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
          </div>
        </div>
      </div>

      <div id="siswa-section" style="display: none;">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title mb-0">Catatan Siswa</h5>
          </div>

          {{-- Desktop: Table --}}
          <div class="table-responsive d-none d-md-block">
            <table class="table table-bordered align-middle d-none d-md-table">

              <thead>
                <tr>
                  <th>Nama Siswa</th>
                  <th>Kehadiran</th>
                  <th>Sikap</th>
                  <th>Progres</th>
                  <th>Kesesuaian</th>
                  <th>Catatan</th>
                </tr>
              </thead>
              <tbody id="table-siswa-body"></tbody>
            </table>
          </div>

          {{-- Mobile: Card per siswa --}}
          <div class="d-block d-md-none" id="siswa-mobile-list">
            <!-- Akan diisi via JS juga -->
          </div>

        </div>

        <div class="text-end mt-3">
          <button type="submit" id="btn-simpan" class="btn btn-primary">
            Simpan
          </button>

        </div>
      </div>
    </form>
  </div>
@endsection

@push('custom-script')
  <script>
    const nilaiOptions = `
  <option value="">- Pilih -</option>
  <option value="5">5 - Sangat Baik ✅</option>
  <option value="4">4 - Baik 👍</option>
  <option value="3">3 - Cukup 😐</option>
  <option value="2">2 - Kurang 😕</option>
  <option value="1">1 - Sangat Kurang ❌</option>
`;

    const fields = ['kehadiran', 'sikap', 'progres', 'kesesuaian'];

    function generatePenilaianSelect(field) {
      return `
    <select name="${field}[]" class="form-select text-center nilai-select">
      ${nilaiOptions}
    </select>
  `;
    }

    function tampilkanSiswaTable(siswaList) {
      const rows = siswaList.map(siswa => {
        const penilaianCols = fields.map(f => `<td>${generatePenilaianSelect(f)}</td>`).join('');
        return `
      <tr>
        <td class="align-middle fw-semibold">
          ${siswa.nama}
          <input type="hidden" name="siswa_id[]" value="${siswa.id}">
        </td>
        ${penilaianCols}
        <td><input type="text" name="catatan[]" class="form-control" placeholder="Opsional"></td>
      </tr>
    `;
      }).join('');
      $('#table-siswa-body').html(rows);
    }

    function tampilkanSiswaMobile(siswaList) {
      const cards = siswaList.map(siswa => {
        const penilaianSelects = fields.map(f => `
      <div class="mb-2">
        <label class="form-label text-capitalize">${f}</label>
        ${generatePenilaianSelect(f)}
      </div>
    `).join('');
        return `
      <div class="card mb-3 p-3 shadow-sm border">
        <h6 class="fw-bold mb-2">${siswa.nama}</h6>
        <input type="hidden" name="siswa_id[]" value="${siswa.id}">
        ${penilaianSelects}
        <div class="mb-2">
          <label class="form-label">Catatan</label>
          <input type="text" name="catatan[]" class="form-control" placeholder="Opsional">
        </div>
      </div>
    `;
      }).join('');
      $('#siswa-mobile-list').html(cards);
    }

    $('#dudi_id').on('change', function() {
      const dudiId = $(this).val();

      // Reset semua tampilan & info
      $('#keperluan option').prop('disabled', false);
      $('#keperluan-alert').text('');
      $('#siswa-section').hide();
      $('#table-siswa-body').html('');
      $('#siswa-mobile-list').html('');

      if (dudiId) {
        // Disable keperluan yang sudah digunakan
        $.get("{{ route('pembimbing.keperluan.used') }}", {
          dudi_id: dudiId
        }, function(usedKeperluan) {
          usedKeperluan.forEach(k => {
            $('#keperluan option[value="' + k + '"]').prop('disabled', true);
          });

          if (usedKeperluan.length > 0) {
            $('#keperluan-alert').text("Keperluan yang sudah dilakukan: " + usedKeperluan.join(', ').replaceAll(
              '_', ' '));
          }
        });

        // Ambil daftar siswa
        $.get("{{ route('pembimbing.siswa.by.dudi') }}", {
          dudi_id: dudiId
        }, function(res) {
          if (res.length > 0) {
            $('#siswa-section').show();

            // Tampilkan berdasarkan lebar layar
            if (window.innerWidth < 768) {
              tampilkanSiswaMobile(res);
            } else {
              tampilkanSiswaTable(res);
            }
          }
        });
      }
    });

    $('form').on('submit', function(e) {
      let valid = true;

      $('.nilai-select:visible').each(function() {
        if (!$(this).val()) {
          $(this).addClass('is-invalid');
          valid = false;
        } else {
          $(this).removeClass('is-invalid');
        }
      });

      if (!valid) {
        e.preventDefault();
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Pastikan semua nilai penilaian sudah diisi.'
        });
        return false;
      }

      const btn = $('#btn-simpan');
      btn.prop('disabled', true);
      btn.html(
        `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...`);
    });
    $(document).on('change', '.nilai-select', function() {
      const val = parseInt($(this).val());
      $(this).removeClass('bg-success bg-primary bg-warning bg-danger bg-dark bg-secondary text-dark');

      switch (val) {
        case 5:
          $(this).addClass('bg-success text-white');
          break;
        case 4:
          $(this).addClass('bg-primary text-white');
          break;
        case 3:
          $(this).addClass('bg-warning text-dark');
          break;
        case 2:
          $(this).addClass('bg-danger text-white');
          break;
        case 1:
          $(this).addClass('bg-dark text-white');
          break;
        default:
          $(this).addClass('bg-secondary text-white');
      }
    });
  </script>
@endpush
