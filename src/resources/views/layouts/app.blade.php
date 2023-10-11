<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Rese</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/toggle-menu.js') }}"></script>

    @yield('script')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,1,0" />

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('css')
</head>
<body>
    <div class="app" id="app">
        <div class="header">
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container">
                    <button class="open-menu material-symbols-outlined" id="open_menu_button">
                        menu
                    </button>
                    <a class="navbar-brand" href="{{ url('/') }}">
                        Rese
                    </a>
                    {{-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button> --}}

                    <div class="collapse navbar-collapse" id="navbar_supported_content">
                        <button class="close-menu material-symbols-outlined" id="close_menu_button">
                            close
                        </button>
                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ms-auto">
                            <!-- Authentication Links -->
                            @guest
                                @if (Route::has('shop_list'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('shop_list') }}">{{ __('Home') }}</a>
                                    </li>
                                @endif

                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Registration') }}</a>
                                    </li>
                                @endif

                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </li>
                                @endif
                            @else
                                @if (Route::has('shop_list'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('shop_list') }}">{{ __('Home') }}</a>
                                    </li>
                                @endif

                                <li class="nav-item dropdown">
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item nav-link" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>

                                @if (Route::has('my_page'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('my_page') }}">{{ __('Mypage') }}</a>
                                    </li>
                                @endif
                                @if (Route::has('stripe_pay'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('stripe_pay') }}">{{ __('Payment') }}</a>
                                    </li>
                                @endif

                                @if (Route::has('qrcode'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('qrcode') }}">{{ __('MyQrcode') }}</a>
                                    </li>
                                @endif

                                @can('admin')
                                @if (Route::has('admin'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('admin') }}">{{ __('Admin') }}</a>
                                    </li>
                                @endif
                                @elsecan('shopkeeper')
                                @if (Route::has('shopkeeper'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('shopkeeper') }}">{{ __('Shopkeeper') }}</a>
                                    </li>
                                @endif
                                @endcan
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>

        </div>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
