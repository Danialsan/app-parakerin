@extends('layouts.app')
@section('title', 'Dudi - PKL SMKN 1 BLEGA')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> Absensi /</span> Jurnal Siswa </h4>
        <div class="row">
            <!--  awal Rekap presensi -->

            <div class="card">
                <div class="card-header">
                    <div class="row g-3 align-items-end">
                        <!-- Form Filter -->
                        <div class="col-md-9">
                            <form method="GET" class="row g-2">
                                <!-- Tanggal Awal -->
                                <div class="col-md-4">
                                    <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                                    <input type="date" name="tanggal_awal" class="form-control form-control-sm"
                                        value="{{ request('tanggal_awal') }}">
                                </div>

                                <!-- Tanggal Akhir -->
                                <div class="col-md-4">
                                    <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                                    <input type="date" name="tanggal_akhir" class="form-control form-control-sm"
                                        value="{{ request('tanggal_akhir') }}">
                                </div>

                                <!-- Tombol Filter dan Reset -->
                                <div class="col-md-4 d-flex align-items-end gap-2">
                                    <button class="btn btn-sm btn-outline-primary" type="submit">
                                        <i class="bx bx-filter"></i> Filter
                                    </button>
                                    <a href="{{ route('dudi.jurnal') }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bx bx-reset"></i> Reset
                                    </a>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
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
                            {{ $rekap_jurnal->links() }}
                        </div>
                    </div>
                </div>
            </div>
            <!--/ akhir Rekap presensi -->
        </div>
    </div>
@endsection
