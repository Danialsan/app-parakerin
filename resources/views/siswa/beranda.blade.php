@extends('layouts.app')
@section('title', 'Siswa - PKL SMKN 1 BLEGA')

@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
      <div class="col mb-4 order-0">
        <div class="card">
          <div class="d-flex align-items-end row">
            <div class="col-sm-7">
              <div class="card-body">

                <h5 class="card-title text-primary"> <img class="rounded-circle"
                    src="{{ asset('assets/img/avatars/1.png') }}" alt="" width="60">
                  Selamat datang kembali, {{ auth()->user()->name }}!</h5>

                <div class="mt-4">
                  <div class="fw-semibold text-dark fs-5 mb-1">{{ auth()->user()->username }}</div>
                  <div class="fw-medium">Rekayasa Perangkat Lunak</div>
                  <div class="fw-medium">Gasal 2024/2025</div>
                </div>
              </div>
            </div>
            <div class="col-sm-5 text-center text-sm-left d-none d-sm-block">
              <div class="card-body pb-0 px-0 px-md-4">
                <img src="../assets/img/illustrations/man-with-laptop-light.png" height="140" alt="View Badge User"
                  data-app-dark-img="illustrations/man-with-laptop-dark.png"
                  data-app-light-img="illustrations/man-with-laptop-light.png" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
