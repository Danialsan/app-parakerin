@extends('layouts.app')

@section('title', 'Dudi - PKL SMKN 1 BLEGA')


@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> Absensi /</span> Presensi Siswa </h4>
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
                                    <a href="{{ route('dudi.presensi') }}" class="btn btn-sm btn-outline-secondary">
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
                                    <th>Nama</th>
                                    <th>Absensi</th>
                                    <th>Keterangan</th>
                                    <th>Waktu Datang</th>
                                    <th>Waktu Pulang</th>
                                    <th>Waktu Absen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($presensi_siswa as $presensi)
                                    <tr>
                                        <th>{{ ucfirst($presensi->siswa->nama) }}</th>
                                        <td>
                                            @if ($presensi->absensi == 'hadir')
                                                <span
                                                    class="badge bg-label-primary">{{ ucfirst($presensi->absensi) }}</span>
                                            @elseif ($presensi->absensi == 'sakit')
                                                <span class="badge bg-label-danger">{{ ucfirst($presensi->absensi) }}</span>
                                            @elseif ($presensi->absensi == 'libur')
                                                <span
                                                    class="badge bg-label-success">{{ ucfirst($presensi->absensi) }}</span>
                                            @elseif ($presensi->absensi == 'izin')
                                                <span
                                                    class="badge bg-label-warning">{{ ucfirst($presensi->absensi) }}</span>
                                            @endif
                                        </td>
                                        <td style="white-space: normal; word-break: break-word;">
                                            {{ ucfirst($presensi->keterangan ?? '-') }}</td>
                                        <td>{{ $presensi->waktu_masuk ? $presensi->waktu_masuk->diffForHumans() : '-' }}
                                        </td>
                                        <td>{{ $presensi->waktu_pulang ? $presensi->waktu_pulang->diffForHumans() : '-' }}
                                        </td>
                                        <td>{{ $presensi->created_at ? $presensi->created_at->translatedFormat('l, d F Y H:i') : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3 d-flex justify-content-end">
                            {{ $presensi_siswa->links() }}
                        </div>
                    </div>
                </div>
            </div>
            <!--/ akhir Rekap presensi -->
        </div>
    </div>
@endsection
