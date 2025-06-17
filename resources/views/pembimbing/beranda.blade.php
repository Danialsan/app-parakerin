@extends('layouts.app')
@section('title', 'Beranda Pembimbing - PKL SMKN 1 BLEGA')

@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    {{-- Card Sambutan --}}
    <div class="row mb-4">
      <div class="col-12">
        <div class="card bg-light-primary shadow-sm">
          <div class="card-body d-flex align-items-center">
            <div class="avatar bg-primary text-white rounded-circle me-3">
              <i class="bx bx-user-check fs-4"></i>
            </div>
            <div>
              <h5 class="mb-1">Selamat Datang, {{ Auth::user()->name }}!</h5>
              <p class="mb-0 text-muted">Anda login sebagai Pembimbing. Pantau Siswa Bimbingan Anda.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-3">
      @php
        $cards = [
            [
                'label' => 'Kunjungan',
                'jumlah' => $jumlah_kunjungan . ' Kunjungan',
                'icon' => 'bx-search-alt-2',
                'bg' => 'bg-label-info',
            ],
            [
                'label' => 'Siswa Bimbingan',
                'jumlah' => $jumlah_siswa . ' Siswa',
                'icon' => 'bx-user',
                'bg' => 'bg-label-primary',
            ],
            [
                'label' => 'Presensi Hari Ini',
                'jumlah' => $jumlah_presensi_hari_ini . ' Siswa',
                'icon' => 'bx-calendar-check',
                'bg' => 'bg-label-success',
            ],
            [
                'label' => 'Jurnal Hari Ini',
                'jumlah' => $jumlah_jurnal_hari_ini . ' Jurnal',
                'icon' => 'bx-book-content',
                'bg' => 'bg-label-warning',
            ],
            [
                'label' => 'Perlu Verifikasi Jurnal',
                'jumlah' => $jumlah_jurnal_perlu_verifikasi . ' Jurnal',
                'icon' => 'bx-task',
                'bg' => 'bg-label-danger',
            ],
        ];
      @endphp

      @foreach ($cards as $card)
        <div class="col-md-4 col-sm-6">
          <div class="card h-100">
            <div class="card-body d-flex align-items-center">
              <div class="avatar {{ $card['bg'] }} me-3">
                <i class="bx {{ $card['icon'] }} fs-4"></i>
              </div>
              <div>
                <h6 class="mb-0">{{ $card['label'] }}</h6>
                <small class="text-muted">{{ $card['jumlah'] }}</small>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endsection
