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
                                    <th>Nama</th>
                                    <th>Absensi</th>
                                    <th>Keterangan</th>
                                    <th>Waktu Datang</th>
                                    <th>Waktu Pulang</th>
                                    <th>Waktu Absen</th>
                                </tr>
                            </thead>
                            <tbody>
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
