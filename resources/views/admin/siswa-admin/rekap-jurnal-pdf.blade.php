<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
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

    <img src="{{ $base64 }}" alt="Logo Provinsi" class="logo">

    <h3>PEMERINTAH PROVINSI JAWA TIMUR</h3>
    <h3>DINAS PENDIDIKAN</h3>
    <h2>SMK NEGERI 1 BLEGA BANGKALAN</h2>
    <p>Jalan Esemka Nomor 1, Panjalinan, Blega, Bangkalan, Jawa Timur 69174</p>
    <p>Telepon (031) 30431272</p>
    <div class="garis"></div>
  </div>

  <div class="judul-surat">
    <h3>REKAP JURNAL PKL SISWA</h3>
    <h3>TAHUN PELAJARAN 2025 - 2026</h3>
  </div>
  <table class="table-data">
    <thead>
      <tr>
        <th>No</th>
        <th>Nama Siswa</th>
        <th>Jurusan</th>
        <th>DUDI</th>
        <th>Tanggal</th>
        <th>Capaian</th>
        <th>Kegiatan</th>
        <th>Pembimbing</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($jurnal as $i => $item)
        <tr>
          <td>{{ $i + 1 }}</td>
          <td>{{ $item->siswa->nama }}</td>
          <td>{{ $item->siswa->jurusan->nama_jurusan ?? '-' }}</td>
          <td>{{ $item->siswa->dudi->nama_perusahaan ?? '-' }}</td>
          <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
          <td>{{ $item->capaianPembelajaran->deskripsi_cp ?? '-' }}</td>
          <td>{{ $item->kegiatan }}</td>
          <td>{{ $item->siswa->pengaturanPkl->pembimbing->nama_pembimbing ?? '-' }}</td>
          <td>{{ $item->verifikasi_pembimbing ? 'Terverifikasi' : 'Belum' }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="9" style="text-align: center;">Tidak ada data jurnal.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
  <br><br><br>
  <table class="no-border" style="width: 100%; margin-top: 60px;">
    <tr>
      <td style="width: 65%;"></td>
      <td style="width: 35%; text-align: left;">
        Bangkalan, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
        Kepala SMK Negeri 1 Blega<br><br><br><br>
        <u><strong>SUIEB, S.Pd, M.Pd</strong></u><br>
        NIP.19720918 200003 1 004<br>
      </td>
    </tr>
  </table>
</body>

</html>
