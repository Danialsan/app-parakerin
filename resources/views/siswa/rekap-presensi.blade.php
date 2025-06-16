    @extends('layouts.app')
    @section('title', 'Siswa - PKL SMKN 1 BLEGA')

    @section('content')
      <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> Presensi /</span> Rekap Presensi </h4>
        <div class="row">
          <!--  awal Rekap presensi -->
          @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
          @endif
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
            <div class="card-header">
              <div class="row g-3 align-items-end">
                <!-- Form Filter -->
                <div class="col-md-9">
                  <form method="GET" action="{{ route('siswa.presensi.riwayat') }}" class="row g-2" id="formFilter">
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
                      <a href="{{ route('siswa.presensi.riwayat') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-reset"></i> Reset
                      </a>
                    </div>
                  </form>
                </div>

                <!-- Tombol Unduh PDF -->
                <div class="col-md-3">
                  <form method="GET" action="{{ route('siswa.presensi.download') }}">
                    <input type="hidden" name="tanggal_awal" value="{{ request('tanggal_awal') }}">
                    <input type="hidden" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
                    <button id="btn-unduh" class="btn btn-sm btn-outline-danger w-100" type="submit">
                      <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"
                        id="spinner"></span>
                      <i class="bx bxs-file-pdf"></i> Unduh Presensi
                    </button>
                  </form>

                </div>
              </div>
            </div>

            <div class="card-body">
              <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Absensi</th>
                      <th>Keterangan</th>
                      <th>Waktu Datang</th>
                      <th>Waktu Pulang</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($rekap_presensi as $presensi)
                      <tr>
                        <td>
                          @php
                            $badgeClass = match ($presensi->absensi) {
                                'hadir' => 'bg-label-primary',
                                'sakit' => 'bg-label-danger',
                                'libur' => 'bg-label-success',
                                'izin' => 'bg-label-warning',
                                default => 'bg-label-secondary',
                            };
                          @endphp
                          <span class="badge {{ $badgeClass }}">{{ $presensi->absensi }}</span>
                        </td>
                        <td style="white-space: normal; word-break: break-word">{{ $presensi->keterangan ?? '-' }}</td>
                        <td>{{ $presensi->waktu_masuk ?? '-' }}</td>
                        <td>{{ $presensi->waktu_pulang ?? '-' }}</td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="4" class="text-center">Tidak ada data Presensi!</td>
                      </tr>
                    @endforelse
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
