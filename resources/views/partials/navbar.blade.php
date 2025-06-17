@php
  $user = auth()->user();
  $user_role = $user->role;
  $siswa = $user->siswa ?? null;
  $dudi = $user->dudi ?? null;
  $pembimbing = $user->pembimbingSekolah->first() ?? null;
@endphp

<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
  id="layout-navbar">
  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
      <i class="bx bx-menu bx-sm"></i>
    </a>
  </div>

  <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
    <ul class="navbar-nav flex-row align-items-center ms-auto">
      <!-- User -->
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="#" data-bs-toggle="dropdown">
          <div class="avatar avatar-online avatar-sm">
            <img src="{{ $foto_profil }}" alt="Foto Profil" class="w-px-40 h-auto rounded-2" />
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item" href="#">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar avatar-online avatar-sm">
                    <img src="{{ $foto_profil }}" alt="Foto Profil" class="w-px-40 h-auto rounded-2" />
                  </div>
                </div>
                <div class="flex-grow-1">
                  <span class="fw-semibold d-block">
                    @switch($user_role)
                      @case('admin')
                        {{ ucfirst($user->name) }}
                      @break

                      @case('siswa')
                        {{ ucfirst($siswa->nama ?? '-') }}
                      @break

                      @case('dudi')
                        {{ ucfirst($dudi->nama_pembimbing ?? '-') }}
                      @break

                      @case('pembimbing')
                        {{ ucfirst($pembimbing->nama_pembimbing ?? '-') }}
                      @break

                      @default
                        {{ ucfirst($user->name) }}
                    @endswitch
                  </span>
                  <small class="text-muted">{{ ucfirst($user_role) }}</small>
                </div>
              </div>
            </a>
          </li>

          <li>
            <div class="dropdown-divider"></div>
          </li>

          <li>
            <a class="dropdown-item" href="{{ route($user_role . '.pengaturan') }}">
              <i class="bx bx-cog me-2"></i>
              <span class="align-middle">Pengaturan</span>
            </a>
          </li>

          <li>
            <div class="dropdown-divider"></div>
          </li>

          <li>
            <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalKeluar">
              <i class="bx bx-power-off me-2"></i>
              <span class="align-middle">Keluar</span>
            </button>
          </li>
        </ul>
      </li>
      <!--/ User -->
    </ul>
  </div>
</nav>

<!-- Toast Informasi -->
@if (in_array(Route::currentRouteName(), ['siswa.beranda', 'admin.beranda', 'pembimbing.beranda']))
  <div id="toast-container">
    @foreach ($informasi_list ?? [] as $info)
      <div class="alert alert-info alert-dismissible fade show informasi-toast" role="alert">
        <strong>Informasi:</strong> {{ Str::limit(strip_tags($info->isi), 100) }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endforeach
  </div>
@endif

<!-- Modal Logout -->
<div class="modal fade" id="modalKeluar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Logout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin keluar?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
        <a href="#" class="btn btn-primary"
          onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          Keluar
        </a>
      </div>
    </div>
  </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
  @csrf
</form>
