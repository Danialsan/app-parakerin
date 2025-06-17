@extends('layouts.app')
@section('title', 'Pengaturan Akun')

@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
      <span class="text-muted fw-light">Pengaturan Akun</span>
    </h4>

    @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill"></i>
        <span>{{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
    @if (session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-x-circle-fill"></i>
        <span>{{ session('error') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5"> {{-- Batasi lebar form --}}
        <div class="card shadow">
          <div class="card-body">
            <form method="POST" action="{{ route($user_role . '.pengaturan.update') }}" enctype="multipart/form-data"
              id="formPengaturan">
              @csrf
              <div class="mb-3">
                <label for="password" class="form-label">Password Baru</label>
                <input type="password" name="password" class="form-control">
              </div>
              <div class="mb-3">
                <label for="foto" class="form-label">Foto Profil</label>
                <input type="file" name="foto" class="form-control">
              </div>
              <button type="submit" class="btn btn-primary w-100" id="btnSubmit">
                <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"
                  id="spinnerSubmit"></span>
                Simpan
              </button>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('custom-script')
  <script>
    document.getElementById('formPengaturan').addEventListener('submit', function() {
      const btn = document.getElementById('btnSubmit');
      const spinner = document.getElementById('spinnerSubmit');
      btn.disabled = true;
      spinner.classList.remove('d-none');
    });
  </script>
@endpush
