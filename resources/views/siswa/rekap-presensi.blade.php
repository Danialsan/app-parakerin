@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> Presensi /</span> Rekap Presensi </h4>
        <div class="row">
            <!--  awal Rekap presensi -->
            <div class="card">
                <h5 class="card-header">Rekap Presensi</h5>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Absensi</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>Angular
                                            Project</strong>
                                    </td>
                                    <td>Albert Cook</td>
                                    <td>Albert Cook</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--/ akhir Rekap presensi -->

        </div>
    </div>
@endsection
