@extends('layouts.app')
@section('title', 'Siswa - PKL SMKN 1 BLEGA')

@section('content')
    @push('custom-style')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <!-- Make sure you put this AFTER Leaflet's CSS -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <style>
            #map {
                width: 100%;
                height: 100%;
            }
        </style>
    @endpush
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Siswa /</span> Presensi </h4>

        @if (session('success'))
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('error') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

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
                                <div id="map"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Lokasi -->

            {{-- {{ dd($waktuSekarang >= $pengaturan_pkl->mulai) }} --}}
            {{-- {{ dd($waktuSekarang <= $pengaturan_pkl->selesai) }} --}}

            @if (isset($pengaturan_pkl))

                <!-- Daftar absensi -->
                <div class="col-12 col-lg-6 order-2 order-md-3 order-lg-2 mb-4">
                    <div class="card">
                        <h5 class="card-header">Absensi Hari Ini</h5>
                        <div class="table-responsive text-nowrap">
                            {{-- @if ($waktuSekarang >= $pengaturan_pkl->mulai)
                                @if ($waktuSekarang <= $pengaturan_pkl->selesai)
                                    <button class="btn btn-primary ms-4" data-bs-toggle="modal" data-bs-target="#presensi"
                                        onclick="waktu()"> <i
                                            class='bx {{ empty($presensiHariIni) ? 'bxs-user' : 'bxs-user-check' }}'></i>
                                        Absensi</button>
                                @else
                                    <p class="text-primary">
                                        Waktu magang sudah selesai.
                                    </p>
                                @endif
                            @else
                                <p class="text-primary">
                                    Belum waktunya melakukan absensi magang.
                                </p>
                            @endif --}}

                            @if ($waktuSekarang < $pengaturan_pkl->mulai)
                                <p class="text-primary ms-4">
                                    Belum waktunya melakukan absensi magang.
                                </p>
                            @elseif ($waktuSekarang > $pengaturan_pkl->selesai)
                                <p class="text-primary ms-4">
                                    Waktu magang sudah selesai.
                                </p>
                            @else
                                <button class="btn btn-primary ms-4" data-bs-toggle="modal" data-bs-target="#presensi"
                                    onclick="waktu()"> <i
                                        class='bx {{ empty($presensiHariIni) ? 'bxs-user' : 'bxs-user-check' }}'></i>
                                    Absensi</button>
                            @endif

                            @if (!empty($presensiHariIni) && $presensiHariIni->absensi === 'hadir')
                                <button onclick="absensiPulang(event);"
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
                                    <input type="hidden" id="posisi_pulang" name="posisi_pulang"
                                        placeholder="Posisi pulang">
                                </form>
                            @endif
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i>
                                            <strong>Nama</strong>
                                        </td>
                                        <td>:</td>
                                        <td>{{ auth()->user()->siswa->nama ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i>
                                            <strong>Nama Perusahaan</strong>
                                        </td>
                                        <td>:</td>
                                        <td>{{ $dudi->nama_perusahaan ?? '' }}</td>
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
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#presensiDetail">
                                        <i class='bx bx-detail'></i> Detail</button>
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
                                    <div class="row g-2">
                                        <div class="col mb-3">
                                            <label for="" class="form-label">Nama Perusahaan</label>
                                            <input type="text" id="" class="form-control"
                                                value="{{ ucfirst($dudi->nama_perusahaan ?? '') }}" readonly />
                                        </div>
                                        <div class="col mb-3">
                                            <label for="" class="form-label">Absensi</label>
                                            <input type="text" id="" class="form-control"
                                                value="{{ ucfirst($presensiHariIni->absensi ?? '') }}" readonly />
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
                                    @endif
                                    <div class="row">
                                        <div class="col mb-3">
                                            <label for="" class="form-label">Keterangan</label>
                                            <p>{{ $presensiHariIni->keterangan ?? '-' }}</p>
                                        </div>
                                    </div>
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
                                        <p class="text-primary">
                                            Absensi hari ini pada jam <span id="waktu" class="fw-bold">00:00:00</span>
                                        </p>
                                        <p class="text-danger d-none" id="peringatan-tempat">
                                            Posisi anda tidak sesuai dengan tempat dudi, silahkan sesuaikan untuk absen
                                            hadir.
                                        </p>
                                        <div class="mb-3">
                                            <label for="absensi" class="form-label">Absensi</label>
                                            <select class="form-control" name="absensi" id="absensi"
                                                onchange="presensi()">
                                                <option value="">Pilih absensi</option>
                                                <option id="opt-hadir" value="hadir">Hadir</option>
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
                                            Anda sudah absen {{ $presensiHariIni->absensi }} hari ini silahkan absen
                                            pulang.
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
            @else
                <!-- Daftar absensi -->
                <div class="col-12 col-lg-6 order-2 order-md-3 order-lg-2 mb-4">
                    <div class="card">
                        <h5 class="card-header">Anda tidak memiliki dudi</h5>
                        <div class="card-body">
                            <div class="text-info">Silahkan hubungi guru pembimbing untuk mengatur dudi.</div>
                        </div>
                    </div>
                </div>
                <!--/ Daftar absensi -->

            @endif

            <div class="modal fade" id="layananLokasi" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel1">Peringatan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-danger">
                                Hidupkan layanan lokasi anda untuk melakukan absensi.
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @push('custom-script')
        <script>
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(ambilPosisi, tidakSupport);
            }

            var inputPosisiMasuk = document.getElementById('posisi_masuk') || null;


            function ambilPosisi(position) {
                let lat = position.coords.latitude;
                let long = position.coords.longitude;

                if (inputPosisiMasuk) {
                    inputPosisiMasuk.value = `${lat},${long}`;
                }

                var map = L.map('map').setView([lat, long], 20);

                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19
                    // attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                L.marker([lat, long]).addTo(map)
                    .bindPopup('Posisi Saya')
                    .openPopup();

                @if (!empty($dudi->posisi_kantor))
                    var posisiAwal = [{{ $dudi->posisi_kantor }}];
                    var radius = {{ $dudi->radius_kantor }};
                    var circle = L.circle(posisiAwal, {
                        color: 'red',
                        fillColor: '#f03',
                        fillOpacity: 0.5,
                        radius: radius
                    }).addTo(map).bindPopup("radius kantor");

                    // hitung jarak posisi awal dengan posisi sekarang

                    var posisiSekarangLatLng = L.latLng(lat, long);
                    var posisiAwalLatLng = L.latLng(posisiAwal);
                    var distance = posisiSekarangLatLng.distanceTo(posisiAwalLatLng);

                    if (distance <= radius) {
                        document.getElementById('opt-hadir').disabled = false;
                        document.getElementById('peringatan-tempat').classList.add('d-none');
                    } else {
                        document.getElementById('peringatan-tempat').classList.remove('d-none');
                        document.getElementById('opt-hadir').disabled = true;
                        document.getElementById('opt-hadir').remove();
                    }
                @endif
            }

            function tidakSupport(e) {
                switch (e.code) {
                    case 1:
                        // alert('Hidupkan layanan lokasi anda untuk melakukan absen')
                        $('#layananLokasi').modal('show');
                        break;

                    default:
                        return false
                        break;
                }
            }

            function absensiPulang(event) {
                event.preventDefault();
                navigator.geolocation.getCurrentPosition((position) => {
                    const lat = position.coords.latitude;
                    const long = position.coords.longitude;

                    document.getElementById('posisi_pulang').value = `${lat},${long}`;

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
