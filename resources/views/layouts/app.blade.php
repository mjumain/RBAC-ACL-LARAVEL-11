<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="csrf_token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME') }}</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('') }}assets/plugins/fontawesome-free/css/all.min.css">

    <script src=" https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('') }}assets/plugins/toastr/toastr.min.css">
    @stack('css')
    <link rel="stylesheet" href="{{ asset('') }}assets/dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini layout-navbar-fixed">
    <div class="wrapper">
        @include('layouts.component.navbar')
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="{{ url()->current() }}" class="brand-link">
                <img src="{{ asset('') }}assets/dist/img/AdminLTELogo.png" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">{{ env('APP_NAME') }}</span>
            </a>

            @include('layouts.component.sidebar')
        </aside>

        <div class="content-wrapper">
            @yield('content')
        </div>
        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">
                Versi 24.8.1
            </div>
            Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>. All rights
            reserved.
        </footer>
    </div>
    <script src="{{ asset('') }}assets/plugins/jquery/jquery.min.js"></script>
    <script src="{{ asset('') }}assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('') }}assets/plugins/toastr/toastr.min.js"></script>

    @stack('js')
    <script src="{{ asset('') }}assets/dist/js/adminlte.min.js"></script>
</body>

</html>
