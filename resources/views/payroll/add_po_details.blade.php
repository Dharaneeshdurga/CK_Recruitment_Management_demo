<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add PO  - {{ $siteTitle }}</title>
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
                            <h3>Add PO Details</h3>
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
                                <button type="button" onclick="add_component_extra();" class="btn btn-sm btn-primary">Add Component</button>

                                    <form class="form" id="addpoForm" method="post" action="javascript:void(0)">
                                        <input type="hidden" name="rfh_no" id="rfh_no">
                                        <input type="hidden" name="cdID" id="cdID">
                                        <input type="hidden" name="ld" id="lead_st">
                                        <div class="responsive">
                                            <table class="table" id="po_components">
                                                <thead>
                                                    <tr>
                                                        <th>S.No.</th>
                                                        <th>Detail</th>
                                                        <th>Description</th>
                                                        <th>Amount (Indian Rupees)/Annum</th>
                                                        <th>Amount (Indian Rupees)/Month</th>
                                                        <th>Remark</th>
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody id="add_po_tbody">
                                                  
                                                </tbody>
                                            </table>
                                             <div id="r_fee">
                                            </div>
                                        </div>          
                                        
                                        <!-- <button id="ctcPreviewbtn" type="button" style="float:left" class="btn btn-sm btn-smn btn-info">Generate & Preview CTC</button> -->
                                        <button id="submitBtn" type="submit" style="float:right" class="btn btn-sm btn-smn btn-primary">Submit</button>
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
    <script src="../assets/pro_js/payroll/add_po_component.js"></script>


    <script type="text/javascript">
    $(document).ready(function() {
        var page = "ol_payroll_verify";

        if (page == "ol_payroll_verify") {
            $(".ol_payroll_verify_m").addClass("active");
        }

    });

    var get_po_components_link = "{{url('get_po_components')}}";
    var submit_po_process_link = "{{url('submit_po_process_bh')}}";
    var send_mail_finance = "{{url('send_po_finance')}}";
    var get_cd_dt = "{{url('get_dandidate_dt')}}";
    </script>
</body>

</html>