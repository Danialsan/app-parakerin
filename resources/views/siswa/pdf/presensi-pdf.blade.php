<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Rekap Presensi - {{ $siswa->nama }}</title>
  <style>
    body {
      font-family: sans-serif;
      font-size: 12px;
    }

    .kop {
      text-align: center;
      margin-bottom: 10px;
    }

    .kop h3,
    .kop h4,
    .kop h2 {
      margin: 0;
    }

    .kop p {
      margin: 0;
    }

    .logo {
      float: left;
      width: 80px;
      height: 80px;
    }

    .garis {
      border-bottom: 3px solid black;
      margin-top: 10px;
    }

    .judul {
      text-align: center;
      margin: 10px 0;
    }

    .judul h3 {
      margin: 5px 0;
    }

    .table-data {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    .table-data th,
    .table-data td {
      border: 1px solid #000;
      padding: 6px;
      text-align: left;
      vertical-align: top;
    }

    .table-data th {
      background-color: #f0f0f0;
    }

    .table-data td {
      border: 1px solid black;
      border-collapse: collapse;
    }

    .no-border {
      border: none;
    }

    .info-table td {
      padding: 4px 2px;
    }

    .ttd {
      width: 100%;
      margin-top: 50px;
    }

    .ttd td {
      border: none;
      text-align: left;
      vertical-align: top;
      padding: 0;
    }

    .ttd .kanan {
      text-align: left;
      width: 40%;
    }
  </style>
</head>

<body>

  {{-- KOP SURAT --}}
  <div class="kop">
    @php
      $path = public_path('storage/logo/logo-provinsi.png');
      $type = pathinfo($path, PATHINFO_EXTENSION);
      $d = file_get_contents($path);
      $base64 = 'data:image/' . $type . ';base64,' . base64_encode($d);
    @endphp
    <img src="{{ $base64 }}" alt="Logo" class="logo">
    <h3>PEMERINTAH PROVINSI JAWA TIMUR</h3>
    <h3>DINAS PENDIDIKAN</h3>
    <h2>SMK NEGERI 1 BLEGA BANGKALAN</h2>
    <p>Jalan Esemka Nomor 1, Panjalinan, Blega, Bangkalan, Jawa Timur 69174</p>
    <p>Telepon (031) 30431272</p>
    <div class="garis"></div>
  </div>
  <!-- JUDUL -->
  <div class="judul">
    <h3><strong>REKAP PRESENSI SISWA</strong></h3>
    <h3>TAHUN PELAJARAN 2025 - 2026</h3>
  </div>

  {{-- INFO SISWA --}}
  <table style="border: none;">
    <tr>
      <td style="border: none; width: 25%;">Nama</td>
      <td style="border: none;">: {{ $siswa->nama }}</td>
    </tr>
    <tr>
      <td style="border: none;">Kelas</td>
      <td style="border: none;">: {{ $siswa->kelas }}</td>
    </tr>
    <tr>
      <td style="border: none;">Jurusan</td>
      <td style="border: none;">: {{ $siswa->jurusan->nama_jurusan ?? '-' }}</td>
    </tr>
    <tr>
      <td style="border: none;">Periode</td>
      <td style="border: none;">
        : {{ \Carbon\Carbon::parse($tanggal_awal)->translatedFormat('d F Y') }} -
        {{ \Carbon\Carbon::parse($tanggal_akhir)->translatedFormat('d F Y') }}
      </td>
    </tr>
  </table>

  {{-- TABEL PRESENSI --}}
  <h4 class="text-center">Rekapitulasi Presensi PKL</h4>
  <table class="table-data">
    <thead>
      <tr>
        <th>No</th>
        <th>Absensi</th>
        <th>Keterangan</th>
        <th>Jam Masuk</th>
        <th>Jam Pulang</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($presensi as $i => $item)
        <tr>
          <td>{{ $i + 1 }}</td>
          <td>{{ ucfirst($item->absensi) }}</td>
          <td>{{ $item->keterangan ?? '-' }}</td>
          <td>{{ \Carbon\Carbon::parse($item->waktu_masuk)->format('d/m/Y H:i') }}</td>
          <td>{{ \Carbon\Carbon::parse($item->waktu_pulang)->format('d/m/Y H:i') }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="text-center">Tidak ada data presensi!</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <br><br><br>

  @php
    $pembimbing = $siswa->pengaturanPkl->pembimbing ?? null;
  @endphp

  <table class="no-border" style="width: 100%;">
    <tr>
      <td style="width: 65%;"></td>
      <td style="width: 35%; text-align: left;">
        Bangkalan, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
        Pembimbing Sekolah<br><br><br><br>
        <u><strong>{{ $pembimbing->nama_pembimbing ?? '..................' }}</strong></u><br>
        NIP. {{ $pembimbing->nip ?? '..................' }}
      </td>
    </tr>
  </table>

</body>

</html>
