<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Laporan Kunjungan PKL</title>
  <style>
    body {
      font-family: sans-serif;
      font-size: 12px;
    }

    .table-data {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th,
    td {
      border: 1px solid #000;
      padding: 6px;
      text-align: left;
    }

    th {
      background-color: #f0f0f0;
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

    .judul-surat {
      text-align: center;
    }

    .judul-surat h3 {
      margin: 5px 0;
    }

    .no-border,
    .no-border td,
    .no-border tr {
      border: none !important;
    }
  </style>
</head>

<body>
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

  <div class="judul-surat">
    <h3>LAPORAN KUNJUNGAN PKL SISWA</h3>
    <h3>TAHUN PELAJARAN 2025 - 2026</h3>
  </div>

  <table class="no-border" style="margin-top: 10px;">
    <tr>
      <td><strong>DUDI</strong></td>
      <td>: {{ $monitoring->dudi->nama_perusahaan }}</td>
    </tr>
    <tr>
      <td><strong>Keperluan</strong></td>
      <td>: {{ ucfirst(str_replace('_', ' ', $monitoring->keperluan)) }}</td>
    </tr>
    <tr>
      <td><strong>Tanggal</strong></td>
      <td>: {{ \Carbon\Carbon::parse($monitoring->created_at)->translatedFormat('d F Y') }}</td>
    </tr>
  </table>

  <br>
  <table class="table-data">
    <thead>
      <tr>
        <th>No</th>
        <th>Nama Siswa</th>
        <th>Kehadiran</th>
        <th>Sikap</th>
        <th>Progres</th>
        <th>Kesesuaian</th>
        <th>Catatan</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($monitoring->detail as $i => $detail)
        <tr>
          <td>{{ $i + 1 }}</td>
          <td>{{ $detail->siswa->nama }}</td>
          <td>{{ $detail->kehadiran }}</td>
          <td>{{ $detail->sikap }}</td>
          <td>{{ $detail->progres }}</td>
          <td>{{ $detail->kesesuaian }}</td>
          <td>{{ $detail->catatan }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <p><strong>Keterangan Penilaian:</strong><br>
    <span>5 = Sangat Baik, 4 = Baik, 3 = Cukup, 2 = Kurang, 1 = Sangat Kurang</span>
  </p>

  <br><br><br>
  <table class="no-border" style="width: 100%;">
    <tr>
      <td style="width: 65%;"></td>
      <td style="width: 35%; text-align: left;">
        Bangkalan, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
        Pembimbing Sekolah<br><br><br><br>
        <u><strong>{{ $monitoring->pembimbing->nama_pembimbing ?? '..................' }}</strong></u><br>
        NIP. {{ $monitoring->pembimbing->nip ?? '..................' }}
      </td>
    </tr>
  </table>
</body>

</html>
