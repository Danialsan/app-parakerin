@extends('layouts.app')

@section('title', 'Dudi - PKL SMKN 1 BLEGA')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-7">
                            <div class="card-body">

                                <h5 class="card-title text-primary"> <img
                                        src="{{ asset('assets/static/images/logo/favicon.png') }}" alt="">
                                    Selamat datang kembali pembimbing, {{ ucfirst(auth()->user()->dudi->nama_pembimbing) }}!
                                </h5>

                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-left d-none d-sm-block">
                            <div class="card-body pb-0 px-0 px-md-4">
                                <img src="../assets/img/illustrations/man-with-laptop-light.png" height="140"
                                    alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                    data-app-light-img="illustrations/man-with-laptop-light.png" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
