@extends('layouts.app')
@section('title', 'Siswa - PKL SMKN 1 BLEGA')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> Presensi /</span> Rekap Presensi </h4>
        <div class="row">
            <!--  awal Rekap presensi -->

            @php
                $nama_nama_bulan = [
                    ['no' => '01', 'bulan' => 'Januari'],
                    ['no' => '02', 'bulan' => 'Februari'],
                    ['no' => '03', 'bulan' => 'Maret'],
                    ['no' => '04', 'bulan' => 'April'],
                    ['no' => '05', 'bulan' => 'Mei'],
                    ['no' => '06', 'bulan' => 'Juni'],
                    ['no' => '07', 'bulan' => 'Juli'],
                    ['no' => '08', 'bulan' => 'Agustus'],
                    ['no' => '09', 'bulan' => 'September'],
                    ['no' => '10', 'bulan' => 'Oktober'],
                    ['no' => '11', 'bulan' => 'November'],
                    ['no' => '12', 'bulan' => 'Desember'],
                ];
            @endphp


            {{-- <form>
                <select name="bulan" id="bulan" onchange="perPage(this.value, 'bulan')">
                    <option value="">--Pilih Bulan--</option>
                    @foreach ($nama_nama_bulan as $bulan)
                        <option value="{{ $bulan['no'] }}">{{ $bulan['bulan'] }}</option>
                    @endforeach
                </select>
                <select name="perpage" id="page" onchange="perPage(this.value, 'halaman')">
                    <option value="">--Pilih halaman--</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                </select>
            </form> --}}

            <div class="card">
                <h5 class="card-header">Rekap Presensi</h5>
                <form method="get">
                    <input type="text" name="cari" class="form-control" placeholder="Cari absensi"
                        value="{{ $cari }}">
                </form>

                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Absensi</th>
                                    <th>Keterangan</th>
                                    <th>Waktu Datang</th>
                                    <th>Waktu Pulang</th>
                                    <th>Waktu Absen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rekap_presensi as $presensi)
                                    <tr>
                                        <td>
                                            @if ($presensi->absensi == 'hadir')
                                                <span class="badge bg-label-primary">{{ $presensi->absensi }}</span>
                                            @elseif ($presensi->absensi == 'sakit')
                                                <span class="badge bg-label-danger">{{ $presensi->absensi }}</span>
                                            @elseif ($presensi->absensi == 'libur')
                                                <span class="badge bg-label-success">{{ $presensi->absensi }}</span>
                                            @elseif ($presensi->absensi == 'izin')
                                                <span class="badge bg-label-warning">{{ $presensi->absensi }}</span>
                                            @endif
                                        </td>
                                        <td style="white-space: normal; word-break: break-word">
                                            {{ $presensi->keterangan ?? '-' }}</td>
                                        <td>{{ $presensi->waktu_masuk ?? '-' }}</td>
                                        <td>{{ $presensi->waktu_pulang ?? '-' }}</td>
                                        <td>{{ $presensi->created_at->format('d-m-Y') ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3 d-flex justify-content-end">
                            {{ $rekap_presensi->links() }}
                        </div>
                    </div>
                </div>
            </div>
            <!--/ akhir Rekap presensi -->
        </div>
    </div>

    {{-- @push('custom-script')
        <script>
            function perPage(value, type) {
                let bulan = `${value}_${type}`;
                let halaman = `${value}_${type}`;
                let prefixBulan = bulan.split('_')[0];
                let prefixHalaman = halaman.split('_')[0];

                const url = new URL(window.location.href);
                url.searchParams.set('halaman', prefixHalaman);
                window.history.pushState({}, '', url);
                if (prefixHalaman) url.searchParams.append('halaman', prefixHalaman);
                window.location.reload();
            }
        </script>
    @endpush --}}


@endsection
