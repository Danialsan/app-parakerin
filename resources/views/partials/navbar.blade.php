@php
  $user = auth()->user();
  $user_role = $user->role;
  $siswa = $user->siswa;
  $dudi = $user->dudi;
  $pembimbingSekolah = $user->pembimbingSekolah->first();
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
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <div class="avatar avatar-online">
            <img src="{{ $foto_profil }}" alt class="w-px-40 h-auto rounded-circle" />
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item" href="#">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar avatar-online">
                    <img src="{{ $foto_profil }}" alt class="w-px-40 h-auto rounded-circle" />
                  </div>
                </div>
                <div class="flex-grow-1">
                  @if ($user_role == 'admin')
                    <span class="fw-semibold d-block">{{ ucfirst($user->name) }}</span>
                  @elseif ($user_role == 'siswa')
                    <span class="fw-semibold d-block">{{ ucfirst($siswa->nama) }}</span>
                  @elseif ($user_role == 'dudi')
                    <span class="fw-semibold d-block">{{ ucfirst($dudi->nama_pembimbing) }}</span>
                  @elseif ($user_role == 'pembimbing')
                    <span class="fw-semibold d-block">{{ ucfirst($pembimbingSekolah->nama_pembimbing) }}</span>
                  @endif
                  <small class="text-muted">{{ ucfirst(Auth::user()->role) }}</small>
                </div>
              </div>
            </a>
          </li>
          <li>
            <div class="dropdown-divider"></div>
          </li>

          <li>
            <a class="dropdown-item" href="#">
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
@if (Route::currentRouteName() == 'siswa.beranda' ||
        Route::currentRouteName() == 'pembimbing.beranda' ||
        Route::currentRouteName() == 'admin.beranda')
  <div id="toast-container">
    @foreach ($informasi_list as $info)
      <div class="alert alert-info alert-dismissible fade show informasi-toast" role="alert">
        <strong>Informasi:</strong> {{ Str::limit(strip_tags($info->isi), 100) }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endforeach
  </div>
@endif
<!-- Modal -->
<div class="modal fade" id="modalKeluar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel1">Peringatan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Apakah yakin ingin keluar?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
          Batal
        </button>
        <a class="btn btn-primary" href="{{ route('login') }}"
          onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();">Keluar</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
          @csrf
        </form>
      </div>
    </div>
  </div>
</div>
