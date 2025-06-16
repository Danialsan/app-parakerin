<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Rekap Jurnal Harian - {{ $siswa->nama }}</title>
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
    <h3><strong>REKAP JURNAL HARIAN SISWA</strong></h3>
    <h3>TAHUN PELAJARAN 2025 - 2026</h3>
  </div>

  <!-- IDENTITAS -->
  <table class="info-table" style="margin-bottom: 10px;">
    <tr>
      <td width="20%"><strong>Nama</strong></td>
      <td>: {{ $siswa->nama }}</td>
    </tr>
    <tr>
      <td><strong>Kelas</strong></td>
      <td>: {{ $siswa->kelas }}</td>
    </tr>
    <tr>
      <td><strong>Jurusan</strong></td>
      <td>: {{ $siswa->jurusan->nama_jurusan ?? '-' }}</td>
    </tr>
    <tr>
      <td style="border: none;"><strong>Periode</strong></td>
      <td style="border: none;">
        : {{ \Carbon\Carbon::parse($tanggal_awal)->translatedFormat('d F Y') }} -
        {{ \Carbon\Carbon::parse($tanggal_akhir)->translatedFormat('d F Y') }}
      </td>
    </tr>
  </table>

  <!-- TABEL JURNAL -->
  <table class="table-data">
    <thead>
      <tr>
        <th style="width: 5%;">No</th>
        <th style="width: 20%;">Tanggal</th>
        <th style="width: 30%;">Capaian Pembelajaran</th>
        <th style="width: 30%;">Kegiatan</th>
        <th style="width: 15%;">Dokumentasi</th>
        <th style="width: 15%;">Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($jurnals as $i => $jurnal)
        <tr>
          <td>{{ $i + 1 }}</td>
          <td>{{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('d F Y') }}</td>
          <td>{{ $jurnal->capaianPembelajaran->deskripsi_cp ?? '-' }}</td>
          <td>{{ $jurnal->kegiatan }}</td>
          <td>
            @if ($jurnal->foto)
              @php
                $fotoPath = public_path('storage/' . $jurnal->foto);
                $fotoType = pathinfo($fotoPath, PATHINFO_EXTENSION);
                $fotoData = file_get_contents($fotoPath);
                $fotoBase64 = 'data:image/' . $fotoType . ';base64,' . base64_encode($fotoData);
              @endphp
              <img src="{{ $fotoBase64 }}" width="50">
            @else
              -
            @endif
          <td>{{ $jurnal->verifikasi_pembimbing ? 'Terverifikasi' : 'Belum' }}</td>
        </tr>
      @endforeach
      @if ($jurnals->isEmpty())
        <td colspan="6" class="text-center">Tidak ada Jurnal, Pastikan Jurnal Harian Sudah Terverifikasi</td>
      @endif
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
