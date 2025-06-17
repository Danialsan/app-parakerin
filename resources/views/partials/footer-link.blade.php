 <!-- Core JS -->
 <!-- build:js assets/vendor/js/core.js -->
 <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
 <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
 <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
 <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

 <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
 <!-- endbuild -->

 <!-- Vendors JS -->
 <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

 <!-- Main JS -->
 <script src="{{ asset('assets/js/main.js') }}"></script>

 <!-- Page JS -->
 <script src="{{ asset('assets/js/form-basic-inputs.js') }}"></script>
 <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
 <script src="{{ asset('assets/js/ui-modals.js') }}"></script>

 <!-- Place this tag in your head or just before your close body tag. -->
 <script async defer src="https://buttons.github.io/buttons.js"></script>
 {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}
 <script>
     setTimeout(() => {
         const alert = document.querySelector('.alert');
         if (alert) {
             const bsAlert = new bootstrap.Alert(alert);
             bsAlert.close();
         }
     }, 5000); // 5 detik
 </script>
 @stack('custom-script')


