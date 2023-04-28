<!doctype html>
<html class="no-js " lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>:: TSV :: @yield('title')</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon"> <!-- Favicon-->
    {{-- <link rel="stylesheet" href={{ asset('assets/plugins/morrisjs/morris.css') }} />
    <link rel="stylesheet" href={{ asset('assets/plugins/jvectormap/jquery-jvectormap-2.0.3.min.css') }} />
    <link rel="stylesheet" href={{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}>
    {{-- <script src={{asset("toastify/toastify.css")}}></script> --}}
    {{-- <link rel="stylesheet" href="../assets/css/bootstrap.css"> --}}
  {{-- <link rel="stylesheet" href={{ asset('assets/vendors/simple-datatables/style.css') }}>
    <link rel="stylesheet" href={{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}>
    <link rel="stylesheet" href={{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}>
    <link rel="stylesheet" href={{ asset('assets/vendors/fontawesome/all.min.css') }}> --}}

    <!-- Custom Css -->
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/vendor/font-awesome/css/font-awesome.min.css">

<link rel="stylesheet" href="../assets/vendor/charts-c3/plugin.css"/>
<link rel="stylesheet" href="../assets/vendor/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css">
<link rel="stylesheet" href="../assets/vendor/chartist/css/chartist.min.css">
<link rel="stylesheet" href="../assets/vendor/chartist-plugin-tooltip/chartist-plugin-tooltip.css">
{{-- <link rel="stylesheet" href="../assets/vendor/toastr/toastr.min.css"> --}}
{{-- <link rel="stylesheet" href="../toastify/toastify.js"> --}}

    <link rel="stylesheet" href={{ asset('assets/css/main.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/color_skins.css') }}>
    <style>
  td.details-control {
  background: url("../assets/images/details_open.png") no-repeat center center !important;
  cursor: pointer; }

tr.shown td.details-control {
  background: url("../assets/images/details_close.png") no-repeat center center !important; }
.toastify {
            padding: 12px 20px;
            color: #ffffff;
            display: inline-block;
            box-shadow: 0 3px 6px -1px rgba(0, 0, 0, 0.12), 0 10px 36px -4px rgba(77, 96, 232, 0.3);
            background: -webkit-linear-gradient(315deg, #73a5ff, #5477f5);
            background: linear-gradient(135deg, #73a5ff, #5477f5);
            position: fixed;
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.215, 0.61, 0.355, 1);
            border-radius: 2px;
            cursor: pointer;
            text-decoration: none;
            max-width: calc(50% - 20px);
            z-index: 2147483647;
        }

        .toastify.on {
            opacity: 1;
        }

        .toast-close {
            opacity: 0.4;
            padding: 0 5px;
        }

        .toastify-right {
            right: 15px;
        }

        .toastify-left {
            left: 15px;
        }

        .toastify-top {
            top: -150px;
        }

        .toastify-bottom {
            bottom: -150px;
        }

        .toastify-rounded {
            border-radius: 25px;
        }

        .toastify-avatar {
            width: 1.5em;
            height: 1.5em;
            margin: 0 5px;
            border-radius: 2px;
        }

        .toastify-center {
            margin-left: auto;
            margin-right: auto;
            left: 0;
            right: 0;
            max-width: fit-content;
        }



        @media only screen and (max-width: 360px) {

            .toastify-right,
            .toastify-left {
                margin-left: auto;
                margin-right: auto;
                left: 0;
                right: 0;
                max-width: fit-content;
            }
        }

    </style>
    @yield('styles')
</head>

<body class="theme-purple">

    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="m-t-30"><img src="../assets/images/icon-light.svg" width="48" height="48" alt="HexaBit"></div>
            <p>Please wait...</p>
        </div>
    </div>
    <div class="overlay"></div>
    <div id="wrapper">
    @include('admin.layouts.overlay_sidebar')
    @include('admin.layouts.left_sidebar')
    @include('admin.layouts.right_sidebar')

    @yield('content')
    </div><!-- end wrapper --->
    <!-- Jquery Core Js -->
    {{-- <script src={{ asset("assets/jquery/jquery.min.js")}}></script> --}}
    {{-- <script src={{ asset('assets/bundles/libscripts.bundle.js') }}></script>
    <script src={{ asset('assets/bundles/vendorscripts.bundle.js') }}></script> <!-- Lib Scripts Plugin Js -->


    <script src={{ asset('assets/bundles/knob.bundle.js') }}></script> <!-- Jquery Knob-->
    <script src={{ asset('assets/bundles/jvectormap.bundle.js') }}></script> <!-- JVectorMap Plugin Js -->
    <script src={{ asset('assets/bundles/morrisscripts.bundle.js') }}></script> <!-- Morris Plugin Js -->
    <script src={{ asset('assets/bundles/sparkline.bundle.js') }}></script> <!-- sparkline Plugin Js -->
    <script src={{ asset('assets/bundles/doughnut.bundle.js') }}></script>
    <script src={{ asset('toastify/toastify.js') }}></script>
    <script src={{ asset('assets/vendors/fontawesome/all.min.js') }}></script>

    <script src={{ asset('assets/bundles/mainscripts.bundle.js') }}></script>
    <script src={{ asset('assets/js/pages/index.js') }}></script> --}}
    <script src={{ asset("assets/bundles/libscripts.bundle.js") }}></script>
<script src={{ asset("assets/bundles/vendorscripts.bundle.js") }}></script>

<script src={{ asset("assets/bundles/c3.bundle.js") }}></script>
<script src={{ asset("assets/bundles/chartist.bundle.js") }}></script>
<script src={{ asset("../assets/vendor/toastr/toastr.js") }}></script>

<script src={{ asset("assets/bundles/mainscripts.bundle.js") }}></script>
<script src={{ asset("assets/js/index.js") }}></script>
<script src={{ asset("assets/js/common.js") }}></script>
<script src={{ asset('toastify/toastify.js') }}></script>

    @yield('scripts')
</body>

</html>
