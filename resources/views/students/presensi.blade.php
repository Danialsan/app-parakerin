@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Siswa /</span> Presensi</h4>
        <div class="row">
            <!-- Lokasi -->
            <div class="col-12 col-lg-6 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-md-8">
                            <h5 class="card-header m-0 me-2 pb-3">Lokasi anda saat ini</h5>
                        </div>
                        <div class="card-body">
                            <div style="width: 100%; height: 290px;">
                                <iframe id="map" src="" width="100%" height="100%"
                                    style="border:0; border-radius: 4px;" allowfullscreen="" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Lokasi -->

            <!-- Daftar absensi -->
            <div class="col-12 col-lg-6 order-2 order-md-3 order-lg-2 mb-4">
                <div class="card">
                    <h5 class="card-header">Absensi Hari Ini</h5>
                    <div class="table-responsive text-nowrap">
                        <button class="btn btn-primary ms-4" data-bs-toggle="modal" data-bs-target="#presensi"
                            onclick="waktu()"> <i
                                class='bx {{ empty($presensiHariIni) ? 'bxs-user' : 'bxs-user-check' }}'></i>
                            Absensi</button>
                        @if (!empty($presensiHariIni) && $presensiHariIni->absensi === 'hadir')
                            <button onclick="posisiPulang(event);"
                                class="btn btn-danger
                                ms-3"
                                {{ $presensiHariIni->waktu_pulang != null ? 'disabled' : '' }}>
                                <i class='bx bx-home'></i> Pulang</button>
                            <form id="update-presensi-form"
                                action="{{ route('siswa.presensi.update', $presensiHariIni->id) }}" method="POST"
                                class="d-none">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id" value="{{ $presensiHariIni->id }}">
                                <input type="hidden" id="posisi_pulang" name="posisi_pulang" placeholder="Posisi pulang">
                            </form>
                        @endif
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td><i class="fab fa-angular fa-lg text-danger me-3"></i>
                                        <strong>Nama</strong>
                                    </td>
                                    <td>:</td>
                                    <td>{{ auth()->user()->name }}</td>
                                </tr>
                                <tr>
                                    <td><i class="fab fa-angular fa-lg text-danger me-3"></i>
                                        <strong>Absensi</strong>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        @if (empty($presensiHariIni->absensi))
                                            {{ '-' }}
                                        @else
                                            {{ ucfirst($presensiHariIni->absensi) }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class="fab fa-angular fa-lg text-danger me-3"></i>
                                        <strong>Keterangan</strong>
                                    </td>
                                    <td>:</td>
                                    <td style="white-space: normal; word-break: break-word">
                                        {{ $presensiHariIni->keterangan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><i class="fab fa-angular fa-lg text-danger me-3"></i>
                                        <strong>Waktu Datang</strong>
                                    </td>
                                    <td>:</td>
                                    <td>{{ $presensiHariIni->waktu_masuk ?? '00:00:00' }}</td>
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class="fab fa-angular fa-lg text-danger me-3"></i>
                                        <strong>Waktu Pulang</strong>
                                    </td>
                                    <td>:</td>
                                    <td>{{ $presensiHariIni->waktu_pulang ?? '00:00:00' }}</td>
                                </tr>
                            </tbody>
                        </table>
                        @if (!empty($presensiHariIni))
                            <div class="p-3 ">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#presensiDetail"> <i
                                        class='bx bx-detail'></i> Detail</button>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
            <!--/ Daftar absensi -->

            {{-- Modal detail absensi awal --}}
            @if (!empty($presensiHariIni))
                <div class="modal fade" id="presensiDetail" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel1">Detail Presensi</h5>
                                <button type="button" onclick="stopWaktu()" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="" class="form-label">Nama</label>
                                        <input type="text" id="" class="form-control"
                                            value="{{ ucfirst(Auth::user()->name ?? '') }}" readonly />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="" class="form-label">Absensi</label>
                                        <input type="text" id="" class="form-control"
                                            value="{{ ucfirst($presensiHariIni->absensi ?? '') }}" readonly />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="" class="form-label">Keterangan</label>
                                        <p>{{ $presensiHariIni->keterangan ?? '-' }}</p>
                                    </div>
                                </div>
                                @if (!empty($presensiHariIni) && $presensiHariIni->absensi === 'hadir')
                                    <div class="row g-2">
                                        <div class="col mb-3">
                                            <label for="" class="form-label">Waktu Masuk</label>
                                            <input type="text" id="" class="form-control" readonly
                                                value="{{ $presensiHariIni->waktu_masuk ?? '-' }}" />
                                        </div>
                                        <div class="col mb-3">
                                            <label for="" class="form-label">Waktu Pulang</label>
                                            <input type="text" id="" class="form-control" readonly
                                                value="{{ $presensiHariIni->waktu_pulang ?? '-' }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col mb-3">
                                            <label for="" class="form-label">Lokasi Masuk</label>
                                            <div style="width: 100%; height: 200px;">
                                                <iframe id="map" src="{{ $presensiHariIni->posisi_masuk ?? '' }}"
                                                    width="100%" height="100%" style="border:0; border-radius: 4px;"
                                                    allowfullscreen="" loading="lazy"
                                                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($presensiHariIni->posisi_pulang != null)
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="" class="form-label">Lokasi Pulang</label>
                                                <div style="width: 100%; height: 200px;">
                                                    <iframe id="map"
                                                        src="{{ $presensiHariIni->posisi_pulang ?? '' }}" width="100%"
                                                        height="100%" style="border:0; border-radius: 4px;"
                                                        allowfullscreen="" loading="lazy"
                                                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" onclick="stopWaktu()"
                                    data-bs-dismiss="modal">
                                    Tutup
                                </button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
            {{-- Modal detail absensi akhir --}}

            {{-- modal absensi awal --}}
            <div class="modal fade" id="presensi" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel1">Peringatan</h5>
                            <button type="button" onclick="stopWaktu()" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        @if (empty($presensiHariIni))
                            <form action="{{ route('siswa.presensi.store') }}" method="post">
                                @csrf
                                <div class="modal-body">
                                    <p class="text-danger">
                                        Absensi hari ini pada jam <span id="waktu" class="fw-bold">00:00:00</span>
                                    </p>
                                    <div class="mb-3">
                                        <label for="absensi" class="form-label">Absensi</label>
                                        <select class="form-control" name="absensi" id="absensi"
                                            onchange="presensi()">
                                            <option value="">Pilih absensi</option>
                                            <option value="hadir">Hadir</option>
                                            <option value="sakit">Sakit</option>
                                            <option value="izin">Izin</option>
                                            <option value="libur">Libur</option>
                                        </select>
                                    </div>
                                    <div id="keteranganGroup" style="display: none">
                                        <label for="keterangan" class="form-label">Keterangan</label>
                                        <textarea name="keterangan" id="keterangan" class="form-control" placeholder="Masukkan keterangan anda..."></textarea>
                                    </div>
                                    <input type="hidden" name="posisi_masuk" id="posisi_masuk">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" onclick="stopWaktu()"
                                        data-bs-dismiss="modal">
                                        Batal
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="btnAbsensi"
                                        style="display: none">
                                        Absensi
                                    </button>
                                </div>
                            </form>
                        @elseif(!empty($presensiHariIni) && $presensiHariIni->absensi === 'hadir')
                            <div class="modal-body">
                                <p class="text-danger">
                                    @if ($presensiHariIni->waktu_pulang == null)
                                        Anda sudah absen {{ $presensiHariIni->absensi }} hari ini silahkan absen pulang.
                                    @else
                                        Silahkan kembali besok untuk melakukan absensi.
                                    @endif
                                </p>
                            </div>
                        @elseif (!empty($presensiHariIni) && $presensiHariIni->absensi != 'hadir')
                            <div class="modal-body">
                                <p class="text-danger">
                                    Anda sudah absen {{ $presensiHariIni->absensi }} hari ini silahkan kembali besok.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            {{-- modal absensi akhir --}}

            {{-- Tombol absensi --}}
            {{-- <div class="col-12 col-md-8 col-lg-6 order-3 order-md-2"> --}}
            {{-- <div class="row"> --}}
            {{-- <div class="col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-center">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                        data-bs-target="#modalHadir" onclick="waktu()">
                                        <i class="bx bxs-user-check"></i>
                                        <span class="align-middle">Hadir</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div> --}}

            {{-- modal hadir awal --}}
            {{-- <div class="modal fade" id="modalHadir" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel1">Peringatan</h5>
                                    <button type="button" onclick="stopWaktu()" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('presensi.store') }}" method="post">
                                    @csrf
                                    <div class="modal-body">
                                        Absensi hari ini pada jam <span id="waktu">00:00:00</span>
                                        <input type="hidden" name="absensi" value="hadir" id="absensi">
                                        <input type="hidden" name="posisi_masuk" id="posisi_masuk">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" onclick="stopWaktu()"
                                            data-bs-dismiss="modal">
                                            Batal
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            Hadir
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> --}}
            {{-- modal hadir akhir --}}

            {{-- <div class="col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-center">
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#modalHadir" onclick="waktu()">
                                        <i class='bx bxs-first-aid'></i>
                                        <span class="align-middle">Sakit</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-center">
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#modalHadir" onclick="waktu()">
                                        <i class='bx bx-notepad'></i>
                                        <span class="align-middle">Izin</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-center">
                                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                        data-bs-target="#modalHadir" onclick="waktu()">
                                        <i class='bx bxs-hotel'></i>
                                        <span class="align-middle">Libur</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (!empty($presensiHariIni->absensi) && $presensiHariIni->absensi === 'hadir')
                        <div class="col-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                            data-bs-target="#modalHadir" onclick="waktu()">
                                            <i class='bx bx-home'></i>
                                            <span class="align-middle">Pulang</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif --}}
            {{-- </div> --}}
            {{-- </div> --}}
            {{-- Tombol absensi --}}
        </div>
    </div>


    @push('custom-script')
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                getLocation();
            });

            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showLocation);
                }
            }

            function showLocation(position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                const mapFrame =
                    `https://www.google.com/maps?q=${latitude},${longitude}&hl=es&z=14&output=embed`;

                document.getElementById('map').src = mapFrame;
                document.getElementById('posisi_masuk').value = mapFrame;
            }

            function posisiPulang(event) {
                event.preventDefault();
                navigator.geolocation.getCurrentPosition((position) => {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    const mapFrame =
                        `https://www.google.com/maps?q=${latitude},${longitude}&hl=es&z=14&output=embed`;
                    document.getElementById('posisi_pulang').value = mapFrame;

                    document.getElementById('update-presensi-form').submit();
                })
            }

            function waktu() {
                const waktuSekarang = new Date;
                const jam = String(waktuSekarang.getHours()).padStart(2, "0");
                const menit = String(waktuSekarang.getMinutes()).padStart(2, "0");
                const detik = String(waktuSekarang.getSeconds()).padStart(2, "0");
                const formatWaktu = `${jam}:${menit}:${detik}`;
                document.getElementById('waktu').textContent = formatWaktu;
                intervalId = setInterval(() => {
                    waktu()
                }, 1000)
            }

            function stopWaktu() {
                clearInterval(intervalId);
            }

            function hurufKapital(string) {
                return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
            }

            function presensi() {
                const absensi = document.getElementById('absensi');
                if (absensi.value == '') {
                    document.getElementById('btnAbsensi').style.display = 'none';
                } else {
                    document.getElementById('btnAbsensi').style.display = 'block'
                    document.getElementById('btnAbsensi').textContent = hurufKapital(absensi.value);
                }
                const keteranganGroup = document.getElementById('keteranganGroup');

                if (absensi.value != 'hadir' && absensi.value != '') {
                    keteranganGroup.style.display = 'block';
                } else {
                    keteranganGroup.style.display = 'none'
                }

            }
        </script>
    @endpush


@endsection
