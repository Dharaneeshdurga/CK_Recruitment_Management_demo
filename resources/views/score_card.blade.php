<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Report - {{ $siteTitle }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Common CSS -->
    @include('layouts.commoncss')
    <!-- Common CSS -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css'>
    <link href="../assets/css/select2.css" rel="stylesheet">
    <style>
    .btn:disabled {
        cursor: not-allowed;
        pointer-events: all !important;
    }

    .disabled {
        cursor: not-allowed;
        pointer-events: none !important;
    }

    .badge:disabled {
        cursor: not-allowed;
        pointer-events: all !important;
    }

    .form-control:disabled {
        cursor: not-allowed;
        pointer-events: all !important;
    }

    table {
        width: 100% !important;
    }

    /* table  td,th{
			white-space:normal !important;
			} */
    .nav-st a {
        font-size: larger;
        font-weight: 700;
    }

    @media only screen and (max-width: 600px) {
        .btn-group {
            /* margin-bottom: -25px !important; */
            display: inline-grid !important;
            margin-left: 5px;
        }

        #table1_filter {
            float: left !important;
            /* margin-top: 32px; */
            /* margin-top: 233px !important; */
        }

        #uploaded_tb_filter {
            float: left !important;
            margin-top: 32px;
        }

        #offer_released_tba_filter {
            float: left !important;
            margin-top: 32px;
        }

        .info_tool .btn {
            margin-bottom: 5px;
            ;
        }

        .dataTables_filter {
            margin-top: 25px !important;
        }

        #table1_wrapper .btn-group {
            float: left !important;
            margin-bottom: 5px;

        }

        #offer_released_tba_wrapper .btn-group {
            float: left !important;
            margin-bottom: 5px;
        }
    }

    .dataTables_length {
        margin-bottom: 10px;
    }

    .buttons-columnVisibility {
        color: #000000;
        text-decoration: none;
        background-color: rgb(255 255 255 / 12%);
        font-weight: 600;
    }

    .bg-onhold,
    .btn-onhold {
        background-color: #964f8e;
    }

    .bg-reopen,
    .btn-reopen {
        background-color: #e4717a;
    }

    .btn-onhold {
        color: white;
    }

    .btn-reopen {
        color: white;
    }

    .modal-backdrop.show {
        width: 100%;
        height: 100%;
    }

    .info_tool {
        background: #0dcaf02b !important;
        color: #000;
        box-shadow: 0 2px 6px 0 rgba(0, 0, 0, .3) !important;
        padding: .75rem 1rem !important;
        border-radius: .267rem !important;
        font-weight: 600;
    }

    @media only screen and (max-width: 600px) {
        .info_tool {
            margin-top: 8px;
            ;
        }

    }

    body,
    .table {
        color: #000000;
    }

    .dropdown-item.active,
    .dropdown-item:active {
        color: #000 !important;
    }

    .dt-button-collection,
    .dropdown-menu {
        height: 300px !important;
        max-height: 300px !important;
        overflow-y: scroll !important;
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

    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }

    .filter_tool {
        color: #000;
        box-shadow: 0 2px 6px 0 rgba(0, 0, 0, .3) !important;
        padding: .75rem 1rem !important;
        border-radius: .267rem !important;
        font-weight: 600;
    }

    .table-danger-dark {
        --bs-table-bg: #dc3545;
        --bs-table-striped-bg: #dc3545;
        --bs-table-striped-color: #000;
        --bs-table-active-bg: #dc3545;
        --bs-table-active-color: #000;
        --bs-table-hover-bg: #dc3545;
        --bs-table-hover-color: #000;
        color: #000;
        border-color: #dc3545;
    }

    .table-danger-orange {
        --bs-table-bg: #ffc107;
        --bs-table-striped-bg: #ffc107;
        --bs-table-striped-color: #000;
        --bs-table-active-bg: #ffc107;
        --bs-table-active-color: #000;
        --bs-table-hover-bg: #ffc107;
        --bs-table-hover-color: #000;
        color: #000;
        border-color: #ffc107;
    }
    .col-md-3{
        margin-bottom: -14px !important;
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
                            <h3>Score Card</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <!-- <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">View Details</li>
                                </ol>
                            </nav> -->
                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="card">
                                <div class="row col-md-12" style="margin-top:25px; margin-left:20px;">
                                    <br>
                                    
                                <div class="col-md-2">
                                    <div class="form-group">
                                    <label>From Date</label>
                                    <input type="date" name="from-date" id="form_date"  class="form-control"/>
                                    <input type="hidden" name="today" id="today" value="<?php echo date('d-m-Y');?>"  class="form-control"/>

                                </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                    <label>To Date</label>
                                    <input type="date" name="to-date" id="to_date"  class="form-control"/>
                                </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                    <label>Select Team</label>
                                    <select class="form-control" name="team" id="team">
                                        <option value="">Select</option>
                                        <option value="HEPL">HEPL</option>
                                        <option value="CKPL">CKPL</option>
                                    </select>
                                </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                    <label>Select Recruiter</label>
                                    <select class="form-control" name="recruit_name" id="recruit_name"></select>
                                </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                     
                                   <button type="submit" style ="margin-top: 24px;"  onclick="get_score_card_filter();" class="btn btn-primary">Filter</button>
                                  
                                   <button style ="margin-top: 24px;" class="btn  btn-warning" id="Clearbtn">Clear</button>
                                </div>
                                </div>
                           
                            </div>


                                <div class="card-body">

                                
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="open_position" role="tabpanel"
                                            aria-labelledby="open_position-tab">
                                            <br>
                                          

                                            <br>

                                            <h5>No. of Records Found: <span id="total_res_show_tab1"></span></h5>

                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped" id="score_card_tb" cellspacing="0">
                                                    <thead>
                                                        <tr>
                                                            <th>S.No.</th>
                                                            <th>Recruiter</th>
                                                            <th>Position</th>
                                                            <th>Interviews</th>
                                                            <th>Offers</th>
                                                            <th>10am</th>
                                                            <th>11am</th>
                                                            <th>12pm</th>
                                                            <th>1pm</th>
                                                            <th>3pm</th>
                                                            <th>4pm</th>
                                                            <th>5pm</th>
                                                            <th>6pm</th>
                                                            <th>Total Cvs</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>
            </div>

            <!-- Footer -->
            @include('layouts.footer')
            <!-- /Footer -->
        </div>
    </div>

    <!-- Common JS -->
    @include('layouts.commonscript')
    <!-- Common JS -->
    <script src="../assets/pro_js/score_card.js"></script>


    <script src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js'></script>

    <script type="text/javascript">
    $(document).ready(function() {
        $(".js-select2").select2();

        var page = "score_card";

        if (page == "score_card") {
            $(".score_card_m").addClass("active");
        }

    });

    var get_score_card_link = "{{url('get_score_card')}}";
    var get_score_card_filter_link = "{{url('get_score_card_filter')}}";
    var get_recruiter_list_link = "{{url('get_recruiter_list_team')}}";
 
    </script>
</body>

</html>