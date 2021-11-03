@extends('layouts.app')

@section('content')
<div class="container">
    <div class="alert alert-light  alert-dismissible fade show text-center" role="alert">
        <strong>Selamat datang di Sistem Sipendar XML Generator. Silahkan Login untuk melanjutkan.</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card mt-3 border border-light">
                {{-- <div class="card-header text-center">{{ __('Login') }}</div> --}}
                <div class="card-body">
                    <img class="img-fluid p-2" src="{{ asset('logo_alt.png') }}" alt="Shinhan Logo">
                    <hr>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
{{--
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> --}}

                        <div class="form-group row">
                            {{-- <label for="username" class="col-md-4 col-form-label text-md-right">Username</label> --}}
                            <div class="col-md-12">
                                <input id="username" placeholder="username" type="username" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required  autofocus>
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            {{-- <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label> --}}

                            <div class="col-md-12">
                                <input id="password" placeholder="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div> --}}


                                <button type="submit" class="btn btn-success float-right">
                                    {{ __('Login') }}
                                </button>

                                {{-- @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif --}}

                    </form>
                </div>
            </div>
            <p class='text-center mt-3 text-dark'><b>Bank Shinhan Indonesia</b> | Copyright © 2021</p>
        </div>
    </div>
</div>
@endsection
