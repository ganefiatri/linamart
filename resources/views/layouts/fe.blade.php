<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="@yield('page_description', $pageDescription ?? config('global.site_description'))">
    <meta name="author" content="">
    <meta name="generator" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $pageTitle ?? config('global.tag_line')) - {{ config('global.site_name') }}</title>

    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    @php
        $allow_tracking = is_allow_tracking();
        $robots = 'noindex';
        if ($allow_tracking) {
            $robots = 'noindex';
        }
    @endphp
    <meta name="robots" content="{{ $robots }}" />
    @yield('meta')
    <link rel="manifest" href="{{ asset('manifest.json') }}" />

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="{{ asset('img/favicon180.png') }}" sizes="180x180">
    <link rel="icon" href="{{ asset('img/favicon32.png') }}" sizes="32x32" type="image/png">
    <link rel="icon" href="{{ asset('img/favicon16.png') }}" sizes="16x16" type="image/png">

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

    <!-- bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ mix('/css/fe.min.css') }}">
    @yield('css')

    @if ($allow_tracking)
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-0799NR3XZR"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-0799NR3XZR');
    </script>
    @endif

</head>

<body class="@yield('body_class', 'body-scroll')" data-page="signup">

    <!-- loader section -->
    <div class="container-fluid loader-wrap @yield('loader')">
        <div class="row h-100">
            <div class="col-10 col-md-6 col-lg-5 col-xl-3 mx-auto text-center align-self-center">
                <div class="loader-cube-wrap mx-auto">
                    <div class="loader-cube1 loader-cube"></div>
                    <div class="loader-cube2 loader-cube"></div>
                    <div class="loader-cube4 loader-cube"></div>
                    <div class="loader-cube3 loader-cube"></div>
                </div>
                @if (!empty(config('global.company_logo')))
                    {{ get_image(config('global.company_logo'), null, 40) }}
                @else
                <div class="logo-small">
                    <i class="bi bi-shop size-32 mr-1"></i>
                    <h6>{{ config('global.site_name') }}<br><small>{{ config('global.tag_line') }}</small></h6>
                </div>
                @endif
                <p><strong>{{ __('Please wait') }}...</strong></p>
            </div>
        </div>
    </div>
    <!-- loader section ends -->

    @include('layouts.partial._main_menu')

    @yield('content')

    @auth()
        @if ($showFooter ?? true)
            @include('layouts.partial._footer')
        @endif
    @endauth

    <div class="position-fixed top-0 start-50 translate-middle-x p-3  z-index-999">
        <div id="toastprouctaddedtiny" class="toast bg-success border-0 shadow hide mb-3" role="alert" aria-live="assertive"
            aria-atomic="true">
            <div class="toast-body">
                <div class="row">
                    <div class="col text-white">
                        <p id="toast-msg"></p>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ mix('/js/fe.min.js') }}"></script>
    @yield('js')
</body>

</html>
