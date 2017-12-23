<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Sarfraz Ahmed (sarfraznawaz2005@gmail.com)">

    <title>{{title()}}</title>

    <link rel="stylesheet" href="/css/app.css">

    {!! Packer::css([
    '/css/styles.css',
    '/js/plugins/datatables/datatables.bootstrap.css',
    '/js/plugins/select2/select2.min.css',
    '/js/plugins/sweetalert/dist/sweetalert.css',
    ],
    '/storage/cache/css/')
    !!}

    <link rel="stylesheet" href="/css/animate.css">

    @stack('styles')

    <script>
        window.Laravel = <?=json_encode(['csrfToken' => csrf_token()]); ?>
    </script>
</head>

<body class="animated fadeIn">
<div id="app">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'BasecampApp') }}
                </a>

            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">

                                <li><a href="{{route('user.settings')}}">Settings</a></li>

                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">{{title()}}</div>

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @include('shared.message')
                        @include('shared.errors')
                        @include('shared.loader')

                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Scripts -->
{!! Packer::js([
'/js/app.js',
'/js/plugins/jquery.pulsate.min.js',
'/js/plugins/validator.min.js',
'/js/plugins/datatables/jquery.dataTables.min.js',
'/js/plugins/datatables/datatables.bootstrap.js',
'/js/plugins/datatables/fnFilterOnReturn.js',
'/js/plugins/select2/select2.full.min.js',
'/js/plugins/sweetalert/dist/sweetalert.min.js',
'/js/custom.js',
],
'/storage/cache/js/')
!!}

@stack('scripts')

</body>
</html>
