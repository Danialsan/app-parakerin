@extends('layouts.app')
@section('title', 'Dudi - PKL SMKN 1 BLEGA')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> Absensi /</span> Jurnal Siswa </h4>
        <div class="row">
            <!--  awal Rekap presensi -->

            <div class="card">
                <h5 class="card-header">Rekap Presensi</h5>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama Siswa</th>
                                    <th>Capaian Pembelajaran</th>
                                    <th>Kegiatan</th>
                                    <th>Foto</th>
                                    <th>Verifikasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rekap_jurnal as $jurnal)
                                    <tr>
                                        <td>{{ $jurnal->tanggal->translatedFormat('l, d F Y') }}</td>
                                        <td>{{ $jurnal->siswa->nama }}</td>
                                        <td>{{ $jurnal->capaianPembelajaran->deskripsi_cp }}</td>
                                        <td>{{ $jurnal->kegiatan }}</td>
                                        <td>{{ $jurnal->foto ?? '-' }}</td>
                                        <td>
                                            @if ($jurnal->verifikasi_pembimbing == 0)
                                                <span class="badge bg-label-danger"> Belum </span>
                                            @else
                                                <span class="badge bg-label-success"> Sudah </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Data Jurnal Tidak Ada.</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                        <div class="mt-3 d-flex justify-content-end">
                        </div>
                    </div>
                </div>
            </div>
            <!--/ akhir Rekap presensi -->
        </div>
    </div>
@endsection
