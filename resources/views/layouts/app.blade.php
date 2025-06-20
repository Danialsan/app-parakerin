<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
  class="light-style  {{ auth()->user() ? 'layout-menu-fixed' : 'customizer-hide' }}" dir="ltr"
  data-theme="theme-default" data-assets-path="{{ asset('assets') }}" data-template="vertical-menu-template-free">

<head>
  <title>@yield('title', 'Official - PKL - SMK NEGERI 1 BLEGA')</title>
  @auth
    @include('partials.pwa.head-link')
  @endauth
  @include('partials.head-link')

  @stack('custom-style')
  <style>
    #loading {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: transparent;
      z-index: 99999;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      opacity: 1;
      transition: opacity 1s ease;
      /* lebih halus */
      pointer-events: all;
    }

    #loading.hidden {
      opacity: 0;
      pointer-events: none;
    }

    .informasi-toast {
      position: fixed;
      top: 90px;
      right: 20px;
      z-index: 1055;
      min-width: 300px;
      animation: slideIn 0.5s ease-out;
    }

    @keyframes slideIn {
      from {
        transform: translateX(100%);
        opacity: 0;
      }

      to {
        transform: translateX(0);
        opacity: 1;
      }
    }
  </style>

</head>

<body>
  <div id="loading">
    <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_usmfx6bp.json" speed="1"
      style="width: 200px; height: 200px;" loop autoplay></lottie-player>
  </div>
  @guest
    @yield('content')
  @else
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->
        @include('partials.sidebar')
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->
          @include('partials.navbar')
          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            @yield('content')
            <!-- / Content -->

            <!-- Footer -->
            @include('partials.footer')
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>

  @endguest

  @include('partials.footer-link')

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      setTimeout(() => {
        const loader = document.getElementById('loading');
        loader.classList.add('hidden');

        // Setelah transisi selesai, hapus elemen dari DOM
        setTimeout(() => {
          loader.remove();
        }, 1000); // sama dengan durasi transition (1s)
      }, 1000); // Delay sebelum mulai fade out
    });
  </script>
  <script>
    // Aktifkan semua tooltip
    document.addEventListener('DOMContentLoaded', function() {
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      tooltipTriggerList.forEach(function(tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl)
      })
    });
  </script>
  <script>
    // Auto hide toast setelah 7 detik
    document.querySelectorAll('.informasi-toast').forEach((toast, index) => {
      setTimeout(() => {
        toast.classList.remove('show');
        toast.classList.add('hide');
      }, 7000 + (index * 1000)); // delay bertahap kalau banyak
    });
  </script>

  @stack('custom-script')

  @auth
    @include('partials.pwa.footer-link')
  @endauth

</body>

</html>
