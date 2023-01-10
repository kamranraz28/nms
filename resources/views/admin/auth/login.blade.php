@extends('admin.layouts.app')

@section('content')

<style>
.login-card {
  border: 0;
  border-radius: 27.5px;
  box-shadow: 0 10px 30px 0 rgba(172, 168, 168, 0.43);
  overflow: hidden; }
  .login-card-img {
    border-radius: 0;
    position: absolute;
    width: 100%;
    height: 100%;
    -o-object-fit: cover;
       object-fit: cover; }
  .login-card .card-body {
    padding: 85px 60px 60px; }
    @media (max-width: 422px) {
      .login-card .card-body {
        padding: 35px 24px; } }
  .login-card-description {
    font-size: 25px;
    color: #000;
    font-weight: normal;
    margin-bottom: 23px; }
  .login-card form {
    max-width: 326px; }
  .login-card .form-control {
    border: 1px solid #d5dae2;
    padding: 15px 25px;
    margin-bottom: 20px;
    min-height: 45px;
    font-size: 13px;
    line-height: 15;
    font-weight: normal; }
    .login-card .form-control::-webkit-input-placeholder {
      color: #919aa3; }
    .login-card .form-control::-moz-placeholder {
      color: #919aa3; }
    .login-card .form-control:-ms-input-placeholder {
      color: #919aa3; }
    .login-card .form-control::-ms-input-placeholder {
      color: #919aa3; }
    .login-card .form-control::placeholder {
      color: #919aa3; }
  .login-card .login-btn {
    padding: 13px 20px 12px;
    background-color: #000;
    border-radius: 4px;
    font-size: 17px;
    font-weight: bold;
    line-height: 20px;
    color: #fff;
    margin-bottom: 24px; }
    .login-card .login-btn:hover {
      border: 1px solid #000;
      background-color: transparent;
      color: #000; }
  .login-card .forgot-password-link {
    font-size: 14px;
    color: #919aa3;
    margin-bottom: 12px; }
  .login-card-footer-text {
    font-size: 16px;
    color: #0d2366;
    margin-bottom: 60px; }
    @media (max-width: 767px) {
      .login-card-footer-text {
        margin-bottom: 24px; } }
  .login-card-footer-nav a {
    font-size: 14px;
    color: #919aa3; }

</style>







<div class="container">
      <div class="card login-card">
        <div class="row no-gutters">
          <div class="col-md-5">
            <img src="{{ asset('site/assets/images/login_banner.png') }}" alt="login" class="login-card-img">
          </div>
          <div class="col-md-7">
            <div class="card-body">
                @if (Session::has('error'))
                  <div class="alert alert-danger">{{ Session::get('error') }}</div>
                @endif
                @if (Session::has('success'))
                  <div class="alert alert-success">{{ Session::get('success') }}</div>
                @endif
              <div class="brand-wrapper">
                  
                  <p class="login-card-description">{{ __('admin.login.software_name')}}</p>
                <img src="{{ asset('site/assets/images/logo.png') }}" alt="logo" class="logo">
              </div>
              <p class="login-card-description">{{ __('admin.login.title')}}</p>
              <form method="POST" action="{{ route('admin.login') }}">
                        @csrf

                  <div class="form-group">
                    <label for="email" class="sr-only">{{ __('admin.login.email') }}</label>
                    <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email"  placeholder="Email Address">
                                @if (Session::has('error'))
                                    <span class="text-danger">
                                        <strong>{{ Session::get('error') }}</strong>
                                    </span>
                                @else
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endif
                                
                  </div>
                  <div class="form-group mb-4">
                    <label for="password" class="sr-only">{{ __('admin.login.password') }}</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                  </div>
                  {{-- <button type="submit" class="btn btn-primary">
                                    {{  __('admin.login.save') }}
                                </button> --}}

                    <div class="form-group row mb-0">
                      <div class="col-md-12">
                        <button type="submit" class="btn btn-primary" style="width:100%;">
                          {{  __('site.login.save') }}
                        </button>
                      </div>
                      <div class="col-md-12">
                        @if (Route::has('admin.password.request'))
                          <a style="padding: 5px 0px;" class="btn btn-link" href="{{ route('admin.password.request') }}">
                            <span><i class="fas fa-unlock-alt"></i></span> {{  __('site.login.forgot') }}
                          </a>
                        @endif

                        <nav class="login-card-footer-nav">
                          {{  __('admin.login.manualcaption') }}  <a href="#!">  {{  __('admin.login.manual') }}</a>
                        </nav>
                      </div>
                  </div>
                </form>
            </div>
          </div>
        </div>
      </div>


@endsection
