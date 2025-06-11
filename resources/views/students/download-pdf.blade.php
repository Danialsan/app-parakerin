@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Siswa /</span> Download PDF
        </h4>

        <div class="row">
            <!-- Download Presensi -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-primary ">
                        <h5 class="m-0 text-white">
                            <i class="bx bx-calendar-check me-2"></i> Presensi
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <p class="mt-2">Unduh file PDF berisi data kehadiran siswa.</p>
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#download_presensi">
                            <i class="bx bx-download me-1"></i> Download Presensi
                        </button>
                    </div>
                </div>
            </div>

            <!-- Download Jurnal -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-warning ">
                        <h5 class="m-0 text-white">
                            <i class="bx bx-notepad me-2"></i> Jurnal
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <p class="mt-2">Unduh jurnal harian atau mingguan siswa dalam format PDF.</p>
                        <button class="btn btn-outline-warning ">
                            <i class="bx bx-download me-1"></i> Download Jurnal
                        </button>
                    </div>
                </div>
            </div>

            <!-- Download laporan -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-success ">
                        <h5 class="m-0 text-white">
                            <i class='bx bx-edit-alt me-2'></i> Laporan
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <p class="mt-2">Unduh laporan siswa dalam format PDF.</p>
                        <button class="btn btn-outline-success">
                            <i class="bx bx-download me-1"></i> Download Laporan
                        </button>
                    </div>
                </div>
            </div>
            <!-- Download Dokumentasi -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-info ">
                        <h5 class="m-0 text-white">
                            <i class="bx bx-camera me-2"></i> Dokumentasi
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <p class="mt-2">Unduh dokumentasi kegiatan siswa dalam format PDF.</p>
                        <button class="btn btn-outline-info">
                            <i class="bx bx-download me-1"></i> Download Dokumentasi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @php

        $nama_nama_bulan = [
            ['nomor' => '01', 'bulan' => 'Januari'],
            ['nomor' => '02', 'bulan' => 'Februari'],
            ['nomor' => '03', 'bulan' => 'Maret'],
            ['nomor' => '04', 'bulan' => 'April'],
            ['nomor' => '05', 'bulan' => 'Mei'],
            ['nomor' => '06', 'bulan' => 'Juni'],
            ['nomor' => '07', 'bulan' => 'Juli'],
            ['nomor' => '08', 'bulan' => 'Agustus'],
            ['nomor' => '09', 'bulan' => 'September'],
            ['nomor' => '10', 'bulan' => 'Oktober'],
            ['nomor' => '11', 'bulan' => 'November'],
            ['nomor' => '12', 'bulan' => 'Desember'],
        ];

        $tahun_sekarang = request()->tahun ?? date('Y');
        $bulan_sekarang = request()->bulan ?? date('m');
        $selisih_tahun = 3;
    @endphp

    {{-- awal modal download presensi --}}
    <div class="modal fade" id="download_presensi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Download Presensi</h5>
                    <button type="button" onclick="stopWaktu()" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="" method="post">
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="pilihan_download" class="form-label">Pilihan Download</label>
                            <select name="mode" id="mode" class="form-control"
                                onchange="pilihanMode(this.value,['bulanan_presensi', 'mingguan_presensi', 'custom_presensi'])">
                                <option value="">Pilihan Download</option>
                                <option value="bulanan">Bulanan</option>
                                <option value="mingguan">Mingguan</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>

                        <div class="row g-2 d-none" id="bulanan_presensi">
                            <div class="col mb-0">
                                <label for="bulan" class="form-label">Bulan</label>
                                <select name="bulanan_presensi_bulan" id="bulan" class="form-control">
                                    @foreach ($nama_nama_bulan as $nama_bulan)
                                        <option value="{{ $nama_bulan['nomor'] }}"
                                            {{ $nama_bulan['nomor'] == $bulan_sekarang ? 'selected' : '' }}>
                                            {{ $nama_bulan['bulan'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col mb-0">
                                <label for="tahun" class="form-label">Tahun</label>
                                <select name="bulanan_presensi_tahun" id="tahun" class="form-control">
                                    @for ($tahun = $tahun_sekarang - $selisih_tahun; $tahun <= $tahun_sekarang; $tahun++)
                                        <option value="{{ $tahun }}"
                                            {{ $tahun == $tahun_sekarang ? 'selected' : '' }}>{{ $tahun }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="row g-2 d-none" id="mingguan_presensi">
                            <div class="col mb-0">
                                <input type="date" class="form-control" name="mingguan_presensi_awal"
                                    oninput="validateDates(this)">
                                <small>Tanggal Awal</small>
                            </div>
                            <div class="col mb-0">
                                <input type="date" class="form-control" name="mingguan_presensi_akhir"
                                    oninput="validateDates(this)">
                                <small>Tanggal Akhir</small>
                            </div>
                        </div>

                        <div class="row g-2 d-none" id="custom_presensi">
                            <div class="col mb-0">
                                <input type="date" class="form-control" name="custom_presensi_awal">
                                <small>Tanggal Awal</small>
                            </div>
                            <div class="col mb-0">
                                <input type="date" class="form-control" name="custom_presensi_akhir">
                                <small>Tanggal Akhir</small>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Download
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- akhir modal download presensi --}}

    {{-- awal modal download jurnal --}}
    <div class="modal fade" id="download_presensi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Download Presensi</h5>
                    <button type="button" onclick="stopWaktu()" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="" method="post">
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Download
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- akhir modal download jurnal --}}


    <script>
        function pilihanMode(val, arr) {
            for (let i = 0; i < arr.length; i++) {
                const element = arr[i];
                document.getElementById(element).classList.add('d-none')
            }

            for (let i = 0; i < arr.length; i++) {
                const data = arr[i].split('_');

                if (val == data[0]) {
                    document.getElementById(arr[i]).classList.remove('d-none')
                }

            }
        }
    </script>
    <script>
        function validateDates(input) {
            const name = input.name;
            const prefix = name.split('_')[0] + '_' + name.split('_')[
                1]; // Extract 'mingguan_jurnal' or 'mingguan_presensi'

            const startDateInput = document.querySelector(`input[name="${prefix}_awal"]`);
            const endDateInput = document.querySelector(`input[name="${prefix}_akhir"]`);

            if (startDateInput && endDateInput) {
                const startDate = startDateInput.value;
                const endDate = endDateInput.value;

                if (startDateInput.value && endDateInput.value) {
                    if (
                        (parseInt(startDate.split('-')[2]) + 6) == endDate
                        .split(
                            '-')[2] &&
                        startDate.split('-')[1] == endDate
                        .split(
                            '-')[1]) {
                        startDateInput.classList.remove('is-invalid');
                        startDateInput.classList.add('is-valid');
                        endDateInput.classList.remove('is-invalid');
                        endDateInput.classList.add('is-valid');
                    } else {
                        startDateInput.classList.remove('is-invalid');
                        startDateInput.classList.add('is-valid');
                        endDateInput.classList.remove('is-valid');
                        endDateInput.classList.add('is-invalid');
                    }
                } else {
                    startDateInput.classList.remove('is-valid', 'is-invalid');
                    endDateInput.classList.remove('is-valid', 'is-invalid');
                }
            }
        }
    </script>
@endsection
