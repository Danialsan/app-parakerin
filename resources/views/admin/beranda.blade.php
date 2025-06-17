@extends('layouts.app')
@section('title', 'Administrator - PKL SMKN 1 BLEGA')

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
              <p class="mb-0 text-muted">Anda login sebagai Administrator. Silakan kelola data Praktik Kerja Lapangan.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="row g-3">
      @php
        $cards = [
            [
                'label' => 'DUDI',
                'jumlah' => $jumlah_dudi . ' DUDI',
                'icon' => 'bx-building-house',
                'bg' => 'bg-label-primary',
            ],
            [
                'label' => 'Pembimbing',
                'jumlah' => $jumlah_pembimbing . ' Pembimbing',
                'icon' => 'bx-user-voice',
                'bg' => 'bg-label-warning',
            ],
            ['label' => 'Siswa', 'jumlah' => $jumlah_siswa . ' Siswa', 'icon' => 'bx-user', 'bg' => 'bg-label-success'],
            [
                'label' => 'Kunjungan Pembimbing',
                'jumlah' => $jumlah_monitoring . ' Kunjungan',
                'icon' => 'bx-search-alt-2',
                'bg' => 'bg-label-danger',
            ],
            [
                'label' => 'Presensi Hari Ini',
                'jumlah' => $jumlah_presensi_hari_ini . ' Siswa',
                'icon' => 'bx-calendar-check',
                'bg' => 'bg-label-info',
            ],
            [
                'label' => 'Jurnal Hari Ini',
                'jumlah' => $jumlah_jurnal_hari_ini . ' Jurnal',
                'icon' => 'bx-book-alt',
                'bg' => 'bg-label-secondary',
            ],
        ];
      @endphp

      @foreach ($cards as $card)
        <div class="col-md-6 col-xl-4">
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

    {{-- Grafik Section --}}
    <div class="row g-3 mt-4">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h6 class="mb-0">Jurnal Harian Hari Ini</h6>
          </div>
          <div class="card-body">
            <div style="max-width: 250px; margin: auto;">
              <canvas id="jurnalDonut"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h6 class="mb-0">Rekap Total</h6>
          </div>
          <div class="card-body">
            <canvas id="rekapBar"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('custom-script')
  <script>
    // Donut Chart - Jurnal Hari Ini
    const ctxDonut = document.getElementById('jurnalDonut').getContext('2d');
    new Chart(ctxDonut, {
      type: 'doughnut',
      data: {
        labels: ['Sudah Isi Jurnal', 'Belum Isi Jurnal'],
        datasets: [{
          data: [{{ $jumlah_jurnal_hari_ini }}, {{ $jumlah_siswa - $jumlah_jurnal_hari_ini }}],
          backgroundColor: ['#696CFF', '#FFAB00'],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom'
          }
        }
      }
    });

    // Bar Chart - Total Data
    const ctxBar = document.getElementById('rekapBar').getContext('2d');
    new Chart(ctxBar, {
      type: 'bar',
      data: {
        labels: ['Siswa', 'Presensi Hari Ini', 'Jurnal Hari Ini'],
        datasets: [{
          label: 'Jumlah',
          data: [{{ $jumlah_siswa }}, {{ $jumlah_presensi_hari_ini }}, {{ $jumlah_jurnal_hari_ini }}],
          backgroundColor: ['#00CFE8', '#FF9F43', '#28C76F'],
          borderRadius: 5
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>
@endpush
