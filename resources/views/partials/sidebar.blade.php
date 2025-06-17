<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="" class="app-brand-link">
      {{-- <span class="app-brand-logo demo"> --}}
      <img src="{{ asset('assets/static/images/logo/logo-auth.png') }}" width="155" alt="">

    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">

    {{-- Siswa --}}
    @if (request()->is('siswa*'))
      <!-- Dashboard -->
      <li class="menu-item {{ request()->is('siswa/beranda') ? 'active' : '' }}">
        <a href="{{ route('siswa.beranda') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-home-circle"></i>
          <div data-i18n="Analytics">Beranda</div>
        </a>
      </li>

      <li class="menu-item {{ request()->is('siswa/presensi') ? 'active' : '' }}">
        <a href="{{ route('siswa.presensi.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-calendar-check"></i>
          <div data-i18n="Analytics">Presensi</div>
        </a>
      </li>
      <!-- Sidebar untuk Role Siswa -->
      {{-- Tampilkan Jurnal jika sudah presensi --}}
      @if ($sudah_presensi_hari_ini)
        <li class="menu-item {{ request()->is('siswa/jurnal') ? 'active' : '' }}">
          <a href="{{ route('siswa.jurnal.index') }}" class="menu-link">
            <i class="menu-icon bx bx-book-add"></i>
            <div>Jurnal Harian</div>
          </a>
        </li>
      @else
        <li class="menu-item disabled">
          <a href="javascript:void(0);" class="menu-link text-muted" title="Lakukan presensi dulu">
            <i class="menu-icon bx bx-book-add"></i>
            <div>Jurnal Harian</div>
          </a>
        </li>
      @endif
      <li class="menu-item {{ request()->is('siswa/jurnal/riwayat') ? 'active' : '' }}">
        <a href="{{ route('siswa.jurnal.riwayat') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-list-check"></i>
          <div data-i18n="Analytics">Rekap Jurnal</div>
        </a>
      </li>
      <li class="menu-item {{ request()->is('siswa/rekap-presensi') ? 'active' : '' }}">
        <a href="{{ route('siswa.rekap-presensi.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bxs-book-content"></i>
          <div data-i18n="Analytics">Rekap Presensi</div>
        </a>
      </li>

    @endif

    @if (request()->is('admin*'))
      <!-- Dashboard -->
      <li class="menu-item {{ request()->is('admin/beranda') ? 'active' : '' }}">
        <a href="{{ route('admin.beranda') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-home-circle"></i>
          <div data-i18n="Analytics">Beranda</div>
        </a>
      </li>
      <li class="menu-item {{ request()->is('admin/jurusan') ? 'active' : '' }}">
        <a href="{{ route('admin.jurusan.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-adjust "></i>
          <div data-i18n="Analytics">Jurusan</div>
        </a>
      </li>
      <li class="menu-item {{ request()->is('admin/capaian-pembelajaran') ? 'active' : '' }}">
        <a href="{{ route('admin.capaian-pembelajaran.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-target-lock "></i>
          <div data-i18n="Analytics">Capaian Pembelajaran</div>
        </a>
      </li>
      <li class="menu-item {{ request()->is('admin/dudi-admin') ? 'active' : '' }}">
        <a href="{{ route('admin.dudi-admin.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bxs-factory"></i>
          <div data-i18n="Analytics">Dudi</div>
        </a>
      </li>
      <li class="menu-item {{ request()->is('admin/pembimbing-sekolah-admin') ? 'active' : '' }}">
        <a href="{{ route('admin.pembimbing-sekolah-admin.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-user-circle"></i>
          <div data-i18n="Analytics">Pembimbing Sekolah</div>
        </a>
      </li>
      <li class="menu-item {{ request()->is('admin/siswa-admin') ? 'active' : '' }}">
        <a href="{{ route('admin.siswa-admin.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-accessibility"></i>
          <div data-i18n="Analytics">Siswa</div>
        </a>
      </li>
      <li class="menu-item {{ request()->is('admin/pengaturan-pkl') ? 'active' : '' }}">
        <a href="{{ route('admin.pengaturan-pkl.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-add-to-queue"></i>
          <div data-i18n="Analytics">Pengaturan Pkl</div>
        </a>
      </li>
      <li class="menu-item {{ request()->is('admin/siswa-admin/rekap-jurnal') ? 'active' : '' }}">
        <a href="{{ route('admin.siswa-admin.rekap-jurnal') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-list-check"></i>
          <div data-i18n="Analytics">Rekap Jurnal Siswa</div>
        </a>
      </li>
      <li class="menu-item {{ request()->is('admin/pembimbing-sekolah-admin.rekap-monitoring') ? 'active' : '' }}">
        <a href="{{ route('admin.pembimbing-sekolah-admin.rekap-monitoring') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-list-check"></i>
          <div data-i18n="Analytics">Rekap Kunjungan</div>
        </a>
      </li>
    @endif

    @if (request()->is('pembimbing*'))
      <!-- Dashboard -->
      <li class="menu-item {{ request()->is('pembimbing/beranda') ? 'active' : '' }}">
        <a href="{{ route('pembimbing.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-home-circle"></i>
          <div data-i18n="Analytics">Beranda</div>
        </a>
      </li>
      <li class="menu-item {{ request()->is('pembimbing/verifikasi-jurnal.index') ? 'active' : '' }}">
        <a href="{{ route('pembimbing.verifikasi-jurnal.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-list-check"></i>
          <div data-i18n="Analytics">Jurnal Siswa</div>
        </a>
      </li>
      <li class="menu-item {{ request()->is('pembimbing/presensi.index') ? 'active' : '' }}">
        <a href="{{ route('pembimbing.presensi.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-calendar-check"></i>
          <div data-i18n="Analytics">Presensi Siswa</div>
        </a>
      </li>
      <li class="menu-item {{ request()->is('pembimbing/kunjungan') ? 'active' : '' }}">
        <a href="{{ route('pembimbing.kunjungan.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-add-to-queue "></i>
          <div data-i18n="Analytics">Kunjungan</div>
        </a>
      </li>
      <li class="menu-item {{ request()->is('pembimbing/riwayat') ? 'active' : '' }}">
        <a href="{{ route('pembimbing.keperluan.riwayat') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-history "></i>
          <div data-i18n="Analytics">Riwayat Kunjungan</div>
        </a>
      </li>
    @endif

  </ul>
</aside>
