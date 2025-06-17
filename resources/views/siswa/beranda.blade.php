@extends('layouts.app')
@section('title', 'Beranda Siswa - PKL SMKN 1 BLEGA')

@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    {{-- Card Sambutan --}}
    <div class="row mb-4">
      <div class="col-12">
        <div class="card bg-light-info shadow-sm">
          <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between">
            <div class="d-flex align-items-center mb-3 mb-md-0">
              <div class="avatar bg-info text-white rounded-circle me-3">
                <i class="bx bx-user fs-4"></i>
              </div>
              <div>
                <h5 class="mb-1">Halo, {{ $siswa->nama }}!</h5>
                <p class="mb-0 text-muted">
                  Selamat datang di dashboard siswa PKL.<br>
                  Pembimbing: <strong>{{ $nama_pembimbing ?? '-' }}</strong><br>
                  Industri: <strong>{{ $nama_industri ?? '-' }}</strong><br>
                  Jurnal Belum Diverifikasi: <strong>{{ $jurnal_belum_verifikasi }} Jurnal</strong>
                </p>
              </div>
            </div>
            <div class="text-end">
              @if (!$sudah_presensi_datang)
                <a href="{{ route('siswa.presensi.index') }}" class="btn btn-sm btn-danger">
                  <i class="bx bx-log-in"></i> Presensi Datang
                </a>
              @elseif (!$sudah_presensi_pulang)
                <a href="{{ route('siswa.presensi.index') }}" class="btn btn-sm btn-warning">
                  <i class="bx bx-log-out"></i> Presensi Pulang
                </a>
              @endif

              @if (!$sudah_isi_jurnal && $sudah_presensi_hari_ini)
                <a href="{{ route('siswa.jurnal.index') }}" class="btn btn-sm btn-primary">
                  <i class="bx bx-book-add"></i> Isi Jurnal
                </a>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Notifikasi Kegiatan Hari Ini --}}
    <div class="row g-3">
      {{-- Presensi Datang --}}
      <div class="col-md-4">
        <div class="p-3 {{ $sudah_presensi_datang ? 'alert-success' : 'alert-danger' }} d-flex align-items-center">
          <i class="bx {{ $sudah_presensi_datang ? 'bx-log-in' : 'bx-error' }} me-2"></i>
          <div>
            {{ $sudah_presensi_datang ? 'Sudah presensi datang.' : 'Belum presensi datang!' }}
          </div>
        </div>
      </div>

      {{-- Presensi Pulang --}}
      <div class="col-md-4">
        <div class="p-3 {{ $sudah_presensi_pulang ? 'alert-success' : 'alert-danger' }} d-flex align-items-center">
          <i class="bx {{ $sudah_presensi_pulang ? 'bx-log-out' : 'bx-time' }} me-2"></i>
          <div>
            {{ $sudah_presensi_pulang ? 'Sudah presensi pulang.' : 'Belum presensi pulang!' }}
          </div>
        </div>
      </div>

      {{-- Jurnal Harian --}}
      <div class="col-md-4">
        <div class="p-3 {{ $sudah_isi_jurnal ? 'alert-success' : 'alert-danger' }} d-flex align-items-center">
          <i class="bx {{ $sudah_isi_jurnal ? 'bx-book' : 'bx-book-add' }} me-2"></i>
          <div>
            {{ $sudah_isi_jurnal ? 'Sudah mengisi jurnal.' : 'Belum mengisi jurnal hari ini!' }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
