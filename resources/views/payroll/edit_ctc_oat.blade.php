<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit CTC  - {{ $siteTitle }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Common CSS -->
    @include('layouts.commoncss')
    <!-- Common CSS -->
    <style>
    body {
        color: #000000;

    }
    .table {
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
    .cl_right{
        text-align:right;
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
                            <h3>Edit CTC</h3>
                        </div>
                        <!-- <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item active" aria-current="page">Offer Released</li>
                                </ol>
                            </nav>
                        </div> -->
                    </div>
                </div>
               
                <!-- // Basic multiple Column Form section start -->
                <section id="multiple-column-form">
                    <div class="row match-height">
                        <div class="col-12">
                            <div class="card">

                                <div class="card-content">
                                    <div class="card-body">
                                    <form class="form" id="editctcForm" method="post" action="javascript:void(0)">
                                        <input type="hidden" name="rfh_no" id="rfh_no">
                                        <input type="hidden" name="cdID" id="cdID">
                                        <div class="responsive">
                                            <table class="table" id="edit_ctc_oat">
                                                <thead>
                                                    <tr>
                                                        <th>Components</th>
                                                        <th>PM</th>
                                                        <th>PA</th>
                                                        <!-- <th>Description</th> -->
                                                    </tr>
                                                </thead>
                                                <tbody id="edit_ctc_oat_tbody">

                                                </tbody>
                                            </table>
                                        </div>          
                                        
                                        <!-- <button id="ctcPreviewbtn" type="button" style="float:left" class="btn btn-sm btn-smn btn-info">Generate & Preview CTC</button> -->
                                        <button id="updateBtn" type="submit" style="float:right" class="btn btn-sm btn-smn btn-primary">Update</button>
                                        </form>
                                        <br><br>
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
    <!-- Common JS -->
    <script src="../assets/pro_js/payroll/edit_ctc_oat.js"></script>


    <script type="text/javascript">
    $(document).ready(function() {
        var page = "ol_payroll_verify";

        if (page == "ol_payroll_verify") {
            $(".ol_payroll_verify_m").addClass("active");
        }

    });

    var get_ctc_edit_oat_link = "{{url('get_ctc_edit_oat')}}";
    var update_ctc_edit_link = "{{url('update_ctc_edit')}}";
    </script>
</body>

</html>