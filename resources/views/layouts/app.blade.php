<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--{{ config('app.name', 'Laravel') }} in title-->
    <title>
        LetsOrgan
    </title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        #app-navbar-collapse{
            background-color: #2c3e50;
        }
        #app-navbar-collapse .intro-btn{
            font-size: 25px;
            border: 2px solid #2c3e50;
            padding-right: 30px;
            color: #fff;
        }
        #app-navbar-collapse .intro-btn:hover{
            border: 2px solid #fff;
            background-color: #2c3e50;
        }
        #login{
            //margin-bottom: 200px;
        }
    </style>

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <!-- {{ config('app.name', 'Laravel') }} -->
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        <li><a href="/" style="color: #fff; font-size: 25px">LetsOrgan</a></li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li id="login-btn" class="hidden"><a href="{{ route('login') }}" class="intro-btn" style="color: #fff">Login</a></li>
                            <li><a href="/qa/" class="intro-btn"><img src="/assets/images/qa.png" width="40px" height="30px" alt="qa" title="Question & Answer Community">Forum</a></li>
                            <li><a class="intro-btn" href="/features"><img src="" height="30px" alt="">Features</a></li>
                            <li><a href="{{ route('register') }}" class="intro-btn"><img src="" height="30px" alt="">Register</a></li>
                        @else
                            <li><a href="/" style="color: #fff">Home</a></li>
                            <li class="dropdown">
                                <a style="color: #fff" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
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

        @yield('content')
    </div>

    @include('pages.partial.footer')

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
