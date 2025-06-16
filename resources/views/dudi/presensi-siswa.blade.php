@extends('layouts.app')

@section('title', 'Dudi - PKL SMKN 1 BLEGA')

@push('custom-style')
    <style>
        .keterangan-td {
            white-space: normal;
            word-break: break-word;
            min-width: 120px;
            max-width: 300px;
        }

        @media (max-width: 576px) {
            .table-responsive table {
                display: block;
                width: 100%;
                overflow-x: auto;
            }

            .table-responsive thead {
                display: none;
            }

            .table-responsive tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 0.5rem;
            }

            .table-responsive td,
            .table-responsive th {
                display: block;
                width: 100%;
                padding: 0.25rem 0.5rem;
            }

            .table-responsive td::before,
            .table-responsive th::before {
                content: attr(data-label);
                font-weight: bold;
                display: block;
                color: #6c757d;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> Absensi /</span> Presensi Siswa </h4>
        <div class="row">
            <!--  awal Rekap presensi -->
            <div class="card">
                <h5 class="card-header">Rekap Presensi</h5>
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
                                                <span class="badge bg-label-primary">{{ ucfirst($presensi->absensi) }}</span>
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
                                        <td class="keterangan-td ">
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
                        </div>
                    </div>
                </div>
            </div>
            <!--/ akhir Rekap presensi -->
        </div>
    </div>
@endsection
