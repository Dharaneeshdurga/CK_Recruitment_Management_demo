<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - {{ $siteTitle }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Common CSS -->
    @include('layouts.commoncss')
    <!-- Common CSS -->
    <link rel="stylesheet" href="../assets/css/daterangepicker.css">

    <style>
    body {
        color: #000000;

    }

    .form-group label {
        color: #212529;
    }

    .dropdown-item.active {
        background-color: #435ebe !important;
        box-shadow: unset !important;
        border: 1px solid #435ebe !important;
        background-image: unset !important;
        color: #fff !important;
        background-image: -webkit-linear-gradient(top, #01549b 0%, #025aa5 100%);
    }

    .dropdown-item {
        background-color: #ffffff !important;
        box-shadow: unset !important;
        border: 1px solid #435ebe !important;
        background-image: unset !important;
        color: #435ebe !important;
        background-image: -webkit-linear-gradient(top, #01549b 0%, #025aa5 100%);
    }

    @import url('https://fonts.googleapis.com/css?family=Poppins');

    * {
        font-family: 'Poppins', sans-serif;
    }

    #chart {
        max-width: 760px;
        margin: 35px auto;
        opacity: 0.9;
    }

    .arrow_box {
        position: relative;
        background: #fff;
        border: 2px solid #efefef;
        color: #000;
        padding: 10px;



    }

    /* .arrow_box:after, .arrow_box:before {
  right: 37%;
  bottom: -39%;
  border: solid transparent;
  content: " ";
  height: 0;
  width: 0;
  position: absolute;
  pointer-events: none;
}

.arrow_box:after {
  border-color: rgba(85, 85, 85, 0
  );
  border-top-color: #fff;
  border-width: 10px;
  margin-top: -10px;
}
.arrow_box:before {
  border-color: rgba(0, 0, 0, 0);
    border-top-color: #efefef;
    border-width: 14px;
    margin-top: -11px;
    bottom: -55%;
    right: 33%;
} */

    .arrow_box:after,
    .arrow_box:before {
        right: 100%;
        top: 50%;
        border: solid transparent;
        content: " ";
        height: 0;
        width: 0;
        position: absolute;
        pointer-events: none;
    }

    .arrow_box:after {
        border-color: rgba(85, 85, 85, 0);
        border-right-color: #fff;
        border-width: 10px;
        margin-top: -10px;
    }

    .arrow_box:before {
        border-color: rgba(0, 0, 0, 0);
        border-right-color: #efefef;
        border-width: 13px;
        margin-top: -13px;
    }


    #chart .apexcharts-tooltip {
        color: #fff;
        transform: translateX(10px) translateY(10px);
        overflow: visible !important;
        white-space: normal !important;
    }

    #chart .apexcharts-tooltip span {
        padding: 5px 10px;
        display: inline-block;
    }

    .apexcharts-tooltip.apexcharts-theme-light {
        border: 0px solid #e3e3e3;
        background: transparent;
    }
    </style>
</head>

<body>
    <div id="app">
        <!-- Sidebar -->
        @include('layouts.sidebar')
        <!-- /Sidebar -->
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Pro Hire Card</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item active" aria-current="page">Pro Hire Card</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- // Basic multiple Column Form section start -->
                <section id="multiple-column-form">
                    <div class="row match-height">
                        <div class="col-12">
                            <div class="card">

                                <div class="card-content">
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-2">
                                                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                                    aria-orientation="vertical">
                                                    <a class="nav-link active" id="v-pills-home-tab"
                                                        data-bs-toggle="pill" href="#v-pills-home" role="tab"
                                                        aria-controls="v-pills-home" aria-selected="true">CV Count</a>
                                                    <a class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill"
                                                        href="#v-pills-profile" role="tab"
                                                        aria-controls="v-pills-profile"
                                                        aria-selected="false">Profile</a>
                                                    <a class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill"
                                                        href="#v-pills-messages" role="tab"
                                                        aria-controls="v-pills-messages"
                                                        aria-selected="false">Messages</a>
                                                    <a class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill"
                                                        href="#v-pills-settings" role="tab"
                                                        aria-controls="v-pills-settings"
                                                        aria-selected="false">Settings</a>
                                                </div>
                                            </div>
                                            <div class="col-10">
                                                <div class="tab-content" id="v-pills-tabContent">
                                                    <div class="tab-pane fade show active" id="v-pills-home"
                                                        role="tabpanel" aria-labelledby="v-pills-home-tab">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                            <input type="text" id="config-demo" class="form-control">
                                                            <input type="text"  class="form-control"name="cv_filter_from_date" id="cv_filter_from_date" value="@php echo date('Y-m-d');@endphp">
                                                            <input type="text"  class="form-control"name="cv_filter_to_date" id="cv_filter_to_date" value="@php echo date('Y-m-d');@endphp">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <button class="btn btn-info" id="dateFilterbtn" type="button">Filter</button>
                                                            </div>
                                                        </div>

                                                        <div id="chart">
                                                            <div id="apex-chart"></div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel"
                                                        aria-labelledby="v-pills-profile-tab">
                                                        Integer interdum diam eleifend metus lacinia, quis gravida eros
                                                        mollis. Fusce
                                                        non sapien sit amet magna dapibus
                                                        ultrices. Morbi tincidunt magna ex, eget faucibus sapien
                                                        bibendum
                                                        non. Duis a
                                                        mauris ex. Ut finibus risus sed massa
                                                        mattis porta. Aliquam sagittis massa et purus efficitur
                                                        ultricies.
                                                    </div>
                                                    <div class="tab-pane fade" id="v-pills-messages" role="tabpanel"
                                                        aria-labelledby="v-pills-messages-tab">
                                                        Integer pretium dolor at sapien laoreet ultricies. Fusce congue
                                                        et
                                                        lorem id
                                                        convallis. Nulla volutpat tellus nec
                                                        molestie finibus. In nec odio tincidunt eros finibus
                                                        ullamcorper. Ut
                                                        sodales,
                                                        dui nec posuere finibus, nisl sem aliquam
                                                        metus, eu accumsan lacus felis at odio.
                                                    </div>
                                                    <div class="tab-pane fade" id="v-pills-settings" role="tabpanel"
                                                        aria-labelledby="v-pills-settings-tab">
                                                        Sed lacus quam, convallis quis condimentum ut, accumsan congue
                                                        massa.
                                                        Pellentesque et quam vel massa pretium ullamcorper
                                                        vitae eu tortor.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- // Basic multiple Column Form section end -->
            </div>
            <!-- Footer -->
            @include('layouts.footer')
            <!-- /Footer -->
        </div>
    </div>
    <!-- Common JS -->
    @include('layouts.commonscript')
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js'></script>
    <script src="../assets/js/daterangepicker.js"></script>

    <!-- Common JS -->
    <script src="../assets/pro_js/prohire_card.js"></script>


    <script type="text/javascript">
    $(document).ready(function() {
        var page = "prohire_card";

        if (page == "prohire_card") {
            $(".prohire_card_m").addClass("active");
        }

        
    });

    
    var get_cv_count_details_link = "{{url('get_cv_count_details')}}";
    </script>
</body>

</html>