@extends('layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card">
                    <div class="card-body">
                        <div class="app-brand justify-content-center gap-4 align-items-center mb-4">
                            <img src="{{ asset('assets/static/images/logo/logo-smeksaga.png') }}" width="120"
                                alt="">
                            <img src="{{ asset('assets/static/images/logo/mylogo.png') }}" width="55" alt="">
                        </div>

                        <div class="text-center mb-4">
                            <h4 class="mb-1 fw-bold">Selamat datang di SIMAGANG</h4>
                            <p>Sistem Informasi Magang</p>
                        </div>

                        <div class="alert alert-warning fw-light"
                            style="color: #566a7f; font-size: .9rem; background-color: #fef5e5; border: 1px solid #ffdfa5;"
                            role="alert">
                            Silahkan MASUK menggunakan No Induk Siswa sebagai Nama Pengguna dan Tanggal lahir Anda sebagai
                            Kata
                            Sandi
                        </div>

                        <form id="formAuthentication" class="mb-3 mt-3" action="{{ route('login') }}" method="POST">

                            @csrf

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" id="floatingInput" placeholder="" name="email"
                                    aria-describedby="floatingInputHelp" autocomplete="on" />
                                <label for="floatingInput">Email <span class="text-danger">*</span></label>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3 position-relative form-password-toggle">
                                <div class="form-floating">
                                    <input type="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        placeholder="">
                                    <label for="password">Kata Sandi <span class="text-danger">*</span></label>
                                    @error('password')
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                                <span class="position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer">
                                    <i class="bx bx-hide" id="togglePasswordIcon"></i>
                                </span>

                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember-me" name="remember"
                                        {{ old('remember') ? 'checked' : '' }} />
                                    <label class="form-check-label" for="remember-me"> Ingat saya </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit">Masuk</button>
                            </div>
                        </form>

                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>
@endsection
