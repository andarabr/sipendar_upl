<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SIPENDAR XML Generator</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <link rel="icon" href="https://www.shinhan.co.id/favicon.ico" sizes="32x32" type="image/png">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    SIPENDAR XML Generator
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    {{-- <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->email }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul> --}}
                </div>
            </div>
        </nav>



        {{-- <main class="py-4">
            @yield('content')
        </main> --}}
        <main>
            <div class="row">
                <div class="col-lg-2 col-md-3" style="border: 1px solid rgba(0, 0, 0, 0.125);; min-height:90vh; padding-right : 0; background-color : rgb(255, 255, 255);">
                    {{-- <ul class="list-group">
                        <li class="list-group-item sq"><a href="{{ route('namelist.index2')}}">List Nama PPATK</a></li>
                        <li class="list-group-item sq"><a href="{{ route('customers.index')}}">Master CBS Data</a></li>
                        <li class="list-group-item sq"><a href="{{ route('customers.lookup')}}">Join PPATK & CBS data</a></li>
                    </ul> --}}
                    <img src="{{ asset('logo_alt.png') }}" class="img-fluid p-2 pr-4 pl-3" alt="Bank Shinhan Indonesia">
                    <hr class="m-0">
                    <div class="list-group list-group-flush">
                        {{-- <a href="#" class="list-group-item list-group-item-action active">
                          Cras justo odio
                        </a> --}}
                        <a href="{{ route('namelist.index2')}}" class="list-group-item list-group-item-action">List Nama PPATK</a>
                        <a href="{{ route('customers.index')}}" class="list-group-item list-group-item-action">Master CBS Data</a>
                        {{-- <a href="{{ route('customers.lookup')}}" class="list-group-item list-group-item-action">Join PPATK & CBS data</a> --}}
                        <a class="list-group-item list-group-item-action" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                            Lookup Data
                        </a>
                    </div>
                    <div class="collapse" id="collapseExample">
                        <a href="{{ route('individu.lookup')}}" class="list-group-item list-group-item-secondary list-group-item-action pt-1 pb-1">Proaktif Individu</a>
                        <a href="{{ route('korporasi.lookup')}}" class="list-group-item list-group-item-secondary list-group-item-action pt-1 pb-1">Proaktif Korporasi</a>
                    </div>
                </div>
                <div class="col-lg-10 col-md-9" style="">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
    <footer>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
            <div class="container-fluid">
                </div>
            </div>
        </nav>
    </footer>
</body>
</html>
