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

        #average {
            background-color: aqua;
            padding: 22px;
            width: fit-content;
            font-size: 20px;
            font-weight: bold;
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

        .col-md-3 {
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
                            <h3>Dashboard</h3>
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

                                <!-- <div class="card-header">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">

                                        </div>

                                        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 info_tool">
                                            <div class="row">


                                                <div class="col-lg-12">
                                                    <label for="">Position Status</label>
                                                    <br>
                                                    <button class="btn btn-warning btn-sm" title="Open"><i
                                                            class="fa fa-book-open"></i> Open</button>
                                                    <button class="btn btn-success btn-sm" title="Closed"><i
                                                            class="fa fa-book"></i> Closed</button>
                                                    <button class="btn btn-onhold btn-sm" title="On Hold"><i
                                                            class="bi bi-pause-fill"></i> On Hold</button>
                                                    <button class="btn btn-dark btn-sm" title="Re Open"><i
                                                            class="bi bi-exclude"></i> Re Open</button>

                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                </div> -->



                                <div class="card-body">

                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item nav-st" role="presentation">
                                            <a class="nav-link active" id="open_position-tab" data-bs-toggle="tab"
                                                href="#open_position" role="tab" aria-controls="profile"
                                                aria-selected="false">Open-Reopen Position</a>
                                        </li>
                                        <li class="nav-item nav-st" role="presentation">
                                            <a class="nav-link " id="closed_position-tab" data-bs-toggle="tab"
                                                href="#closed_position" role="tab" aria-controls="home"
                                                aria-selected="true">Closed Position</a>
                                        </li>
                                        <li class="nav-item nav-st" role="presentation">
                                            <a class="nav-link " id="recruiter-sc-tab" data-bs-toggle="tab"
                                                href="#recruiter-sc" role="tab" aria-controls="home"
                                                aria-selected="true">Recruiter Hourly Score Card</a>
                                        </li>
                                        <li class="nav-item nav-st" role="presentation">
                                            <a class="nav-link " id="recruiter-daily-tab" data-bs-toggle="tab"
                                                href="#recruiter-daily" role="tab" aria-controls="home"
                                                aria-selected="true">Recruiter Daily Report</a>
                                        </li>
                                        <li class="nav-item nav-st" role="presentation">
                                            <a class="nav-link " id="ageing-report-tab" data-bs-toggle="tab"
                                                href="#ageing-report" role="tab" aria-controls="home"
                                                aria-selected="true">Ageing Report</a>
                                        </li>
                                        <li class="nav-item nav-st" role="presentation">
                                            <a class="nav-link " id="sc-tab" data-bs-toggle="tab" href="#sc-daily"
                                                role="tab" aria-controls="home" aria-selected="true">Score Card</a>
                                        </li>


                                    </ul>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="open_position" role="tabpanel"
                                            aria-labelledby="open_position-tab">
                                            <br>
                                            <button class="btn btn-dark" onclick="show_advanced_filter_open();"
                                                id="advanced_filter"><i class="bi bi-funnel-fill"></i> Advanced
                                                Filter</button>

                                            <!-- filter result start -->
                                            <div class="row mt-4 filter_tool" id="show_filter_div_open"
                                                style="display:none;">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="af_from_date">From Date</label>
                                                        <input type="date" name="af_from_date" id="af_from_date"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="af_to_date">To Date</label>
                                                        <input type="date" name="af_to_date" id="af_to_date"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="af_position_title">Position Title</label>
                                                        <select name="af_position_title" id="af_position_title"
                                                            class="form-control js-select2">

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="af_sub_position_title">Sub Position Title</label>
                                                        <select name="af_sub_position_title"
                                                            id="af_sub_position_title"
                                                            class="form-control js-select2">

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="af_location">Location</label>
                                                        <select name="af_location" id="af_location"
                                                            class="form-control js-select2">

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="af_business">Business</label>
                                                        <select name="af_business" id="af_business"
                                                            class="form-control js-select2">

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="Function">Function</label>
                                                        <select name="af_function" id="af_function"
                                                            class="form-control js-select2">

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="af_division">Division</label>
                                                        <select name="af_division" id="af_division"
                                                            class="form-control js-select2">

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="af_billable">Billable</label>
                                                        <select name="af_billable" id="af_billable"
                                                            class="form-control js-select2">
                                                            <option value="">Select</option>
                                                            <option value="Billable">Billable</option>
                                                            <option value="Non Billable">Non Billable</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="af_teams">Teams</label>
                                                        <select name="af_teams" id="af_teams"
                                                            class="form-control js-select2">
                                                            <option value="">Select</option>
                                                            <option value="CKPL">CKPL</option>
                                                            <option value="HEPL">HEPL</option>
                                                            <option value="RPO">RPO</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="af_raisedby">Request raised by</label>
                                                        <select name="af_raisedby" id="af_raisedby"
                                                            class="form-control js-select2">

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="af_interviewer">Interviewer</label>
                                                        <select name="af_interviewer" id="af_interviewer"
                                                            class="form-control js-select2">
                                                            <option value="">Select</option>

                                                        </select>
                                                    </div>
                                                </div>




                                                <div class="col-md-2  col-md-offset-10">
                                                    <div class="form-group">
                                                        <label for="">Action</label>
                                                        <br>
                                                        <button class="btn btn-sm btn-warning"
                                                            id="afClearbtn">Clear</button>
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- filter result end -->

                                            <br>

                                            <h5>No. of Records Found: <span id="total_res_show_tab1"></span></h5>

                                            <div class="table-responsive">
                                                <table class="table" id="table_open" cellspacing="0">
                                                    <thead>
                                                        <tr>
                                                            <th>S.No.</th>
                                                            <th>HEPL Recruitment Ref. No</th>
                                                            <th>Position Title</th>
                                                            <th>Sub Position Title</th>
                                                            <th>Open Date</th>
                                                            <th>Position Ageing</th>
                                                            <th>Total CV's</th>
                                                            <th>Profile Ageing</th>
                                                            <th>Location</th>
                                                            <th>Business</th>
                                                            <th>Recruiter</th>
                                                            <th>Request raised by </th>
                                                            <th>Interviewer </th>
                                                            <th>Current Status</th>
                                                            <th>Last Modified</th>
                                                            <th>Billing Status</th>
                                                            <th>Division</th>
                                                            <th>Function</th>
                                                            <th>Maximum CTC(Per Month)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="closed_position" role="tabpanel"
                                            aria-labelledby="closed_position-tab">
                                            <br>
                                            <button class="btn btn-dark" onclick="show_advanced_filter_closed();"
                                                id="advanced_filter"><i class="bi bi-funnel-fill"></i> Advanced
                                                Filter</button>

                                            <!-- filter result start -->
                                            <div class="row mt-4 filter_tool" id="show_filter_div"
                                                style="display:none;">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="afc_from_date">From Date</label>
                                                        <input type="date" name="afc_from_date" id="afc_from_date"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="afc_to_date">To Date</label>
                                                        <input type="date" name="afc_to_date" id="afc_to_date"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="afc_position_title">Position Title</label>
                                                        <select name="afc_position_title" id="afc_position_title"
                                                            class="form-control js-select2">

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="afc_sub_position_title">Sub Position Title</label>
                                                        <select name="afc_sub_position_title"
                                                            id="afc_sub_position_title"
                                                            class="form-control js-select2">

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="afc_source">Source</label>
                                                        <select name="afc_source" id="afc_source"
                                                            class="form-control js-select2">

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="afc_location">Location</label>
                                                        <select name="afc_location" id="afc_location"
                                                            class="form-control js-select2">

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="afc_business">Business</label>
                                                        <select name="afc_business" id="afc_business"
                                                            class="form-control js-select2">

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="Function">Function</label>
                                                        <select name="afc_function" id="afc_function"
                                                            class="form-control js-select2">

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="afc_division">Division</label>
                                                        <select name="afc_division" id="afc_division"
                                                            class="form-control js-select2">

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="afc_band">Band</label>
                                                        <select name="afc_band" id="afc_band"
                                                            class="form-control js-select2">

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="afc_created_by">Recruiter</label>
                                                        <select name="afc_created_by" id="afc_created_by"
                                                            class="form-control js-select2">

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="afc_raisedby">Request raised by</label>
                                                        <select name="afc_raisedby" id="afc_raisedby"
                                                            class="form-control js-select2">

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="afc_billable">Billable</label>
                                                        <select name="afc_billable" id="afc_billable"
                                                            class="form-control">
                                                            <option value="">Select</option>
                                                            <option value="Billable">Billable</option>
                                                            <option value="Non Billable">Non Billable</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="afc_teams">Teams</label>
                                                        <select name="afc_teams" id="afc_teams"
                                                            class="form-control js-select2">
                                                            <option value="">Select</option>
                                                            <option value="CKPL">CKPL</option>
                                                            <option value="HEPL">HEPL</option>
                                                            <option value="RPO">RPO</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="afc_doj_from_date">DoJ From Date</label>
                                                        <input type="date" name="afc_doj_from_date"
                                                            id="afc_doj_from_date" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="afc_doj_to_date">DoJ To Date</label>
                                                        <input type="date" name="afc_doj_to_date"
                                                            id="afc_doj_to_date" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-2  col-md-offset-10">
                                                    <div class="form-group">
                                                        <label for="">Action</label>
                                                        <br>
                                                        <button class="btn btn-sm btn-warning"
                                                            id="afcClearbtn">Clear</button>
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- filter result end -->

                                            <br>
                                            <h5>No. of Records Found: <span id="total_res_show_tab2"></span></h5>

                                            <div class="table-responsive">
                                                <table class="table" id="table_closed" cellspacing="0">
                                                    <thead>
                                                        <tr>
                                                            <th>S.No.</th>
                                                            <th>HEPL Recruitment Ref. No</th>
                                                            <th>Open Date</th>
                                                            <th>Position Title</th>
                                                            <th>Sub Position Title</th>
                                                            <th>Candidate Name</th>
                                                            <th>Gender</th>
                                                            <th>Source</th>
                                                            <th>DOJ</th>
                                                            <th>Location</th>
                                                            <th>Business</th>
                                                            <th>Band</th>
                                                            <th>Recruiter</th>
                                                            <th>Request raised by </th>
                                                            <th>Billing Status</th>
                                                            <th>Closed Date</th>
                                                            <th>Closed CTC Salary/Month</th>
                                                            <th>Request Status</th>
                                                            <th>Division</th>
                                                            <th>Function</th>
                                                            <th>Current Status</th>
                                                            <th>Assigned Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>





                                        <div class="tab-pane fade" id="recruiter-sc" role="tabpanel"
                                            aria-labelledby="recruiter-sc-tab">
                                            <div class="row col-md-12" style="margin-top:25px; margin-left:20px;">
                                                <br>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>From Date</label>
                                                        <input type="date" name="from-date" id="form_date"
                                                            class="form-control" />
                                                        <input type="hidden" name="today" id="today"
                                                            value="<?php echo date('Y-m-d'); ?>" class="form-control" />

                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>To Date</label>
                                                        <input type="date" name="to-date" id="to_date"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>Select Team</label>
                                                        <select class="form-control" name="team" id="team">
                                                            <option value="">Select</option>
                                                            <option value="CKPL">CKPL</option>
                                                            <option value="HEPL">HEPL</option>
                                                            <option value="RPO">RPO</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>Select Recruiter</label>
                                                        <select class="form-control" name="recruit_name"
                                                            id="recruit_name"></select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">

                                                        {{-- <button type="submit" style ="margin-top: 24px;"  onclick="get_score_card_filter();" class="btn btn-primary">Filter</button> --}}

                                                        <button style="margin-top: 24px;" class="btn  btn-warning"
                                                            id="Clearbtn">Clear</button>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped" id="score_card_tb"
                                                    cellspacing="0">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center"
                                                                style="background:#db6a5e;color:black;font-weight: bold;border: 1px solid;">
                                                                S.No.</th>
                                                            <th
                                                                style="background:#db6a5e;color:black;font-weight: bold;border: 1px solid;">
                                                                Recruiter</th>
                                                            <th
                                                                style="background:#db6a5e;color:black;font-weight: bold;border: 1px solid;">
                                                                Positions Working</th>
                                                            <th
                                                                style="background:#db6a5e;color:black;font-weight: bold;border: 1px solid;">
                                                                Interviews</th>
                                                            <th
                                                                style="background:#db6a5e;color:black;font-weight: bold;border: 1px solid;">
                                                                Offers</th>
                                                            <th
                                                                style="background:#595ee0;color:black;font-weight: bold;border: 1px solid;">
                                                                10am</th>
                                                            <th
                                                                style="background:#595ee0;color:black;font-weight: bold;border: 1px solid;">
                                                                11am</th>
                                                            <th
                                                                style="background:#595ee0;color:black;font-weight: bold;border: 1px solid;">
                                                                12pm</th>
                                                            <th
                                                                style="background:#595ee0;color:black;font-weight: bold;border: 1px solid;">
                                                                1pm</th>
                                                            <th
                                                                style="background:#595ee0;color:black;font-weight: bold;border: 1px solid;">
                                                                3pm</th>
                                                            <th
                                                                style="background:#595ee0;color:black;font-weight: bold;border: 1px solid;">
                                                                4pm</th>
                                                            <th
                                                                style="background:#595ee0;color:black;font-weight: bold;border: 1px solid;">
                                                                5pm</th>
                                                            <th
                                                                style="background:#595ee0;color:black;font-weight: bold;border: 1px solid;">
                                                                6pm</th>
                                                            <th
                                                                style="background:#66e059;color:black;font-weight: bold;border: 1px solid;">
                                                                Total Cvs</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>

                                            </div><!-- tab closed -->

                                            <div class="tab-pane fade" id="ageing-report" role="tabpanel"
                                            aria-labelledby="ageing-report-tab">
                                            <div class="row col-md-12" style="margin-top:25px; margin-left:20px;">

                                            <p id="pro" class="text-center">Processing...</p>
                                            </div>
                                                <br>
                                                <div class="row" style="margin-top:25px; margin-left:20px;">
                                                    <br>


                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Select Team</label>
                                                            <select class="form-control" name="team"
                                                                id="team_age">
                                                                <option value="">Select</option>
                                                                <option value="CKPL">CKPL</option>
                                                                <option value="HEPL">HEPL</option>
                                                                <option value="RPO">RPO</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="form-group">

                                                            {{-- <button type="submit" style ="margin-top: 24px;"  onclick="get_re_daily_filter();" class="btn btn-primary">Filter</button> --}}
                                                            <button style="margin-top: 24px;" class="btn  btn-warning"
                                                                id="Clear_age">Clear</button>
                                                        </div>
                                                    </div>

                                                </div>
                                                <br>
                                            <div class="table-responsive">
                                                <h5 class=""> Open Positions Ageing Report as on  <?php echo date('d-M-y');?></h5>
                                                <table class="table table-bordered" id="age_tb">
                                                    <thead>
                                                        <tr id="td_head">
                                                        </tr>
                                                            {{-- <th>Position Ageing</th>
                                                            <th>Billable</th>
                                                            <th>Non Billable</th>
                                                            <th>Total</th> --}}

                                                    </thead>
                                                            <tbody>
                                                                    <tr id="billable">

                                                                    </tr>
                                                                    <tr id="non-billabe">

                                                                    </tr>
                                                                    <tr id="gt">

                                                                    </tr>
                                                            </tbody>
                                                </table>

                                            </div>
                                     </div>

                                     <!-- end of ageing report-->
                                            <div class="tab-pane fade" id="recruiter-daily" role="tabpanel"
                                                aria-labelledby="recruiter-daily-tab">



                                                <div class="row" style="margin-top:25px; margin-left:20px;">
                                                    <br>

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>From Date</label>
                                                            <input type="date" name="from-date" id="form_date1"
                                                                class="form-control" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>To Date</label>
                                                            <input type="date" name="to-date" id="to_date1"
                                                                class="form-control" />
                                                            <input type="hidden" name="today" id="today"
                                                                value="<?php echo date('d-m-Y'); ?>" class="form-control" />

                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Select Team</label>
                                                            <select class="form-control" name="team"
                                                                id="team1">
                                                                <option value="">Select</option>
                                                                <option value="CKPL">CKPL</option>
                                                                <option value="HEPL">HEPL</option>
                                                                <option value="RPO">RPO</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Select Recruiter</label>
                                                            <select class="form-control" name="recruit_name"
                                                                id="recruit_name1"></select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">

                                                            {{-- <button type="submit" style ="margin-top: 24px;"  onclick="get_re_daily_filter();" class="btn btn-primary">Filter</button> --}}
                                                            <button style="margin-top: 24px;" class="btn  btn-warning"
                                                                id="Clearbtn1">Clear</button>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered" id="r_daily_report_tb">
                                                        <thead>
                                                            <tr>
                                                                <th
                                                                    style="background:#0dcaf0;color:black;font-weight: bold;border: 1px solid;">
                                                                    S.No.</th>
                                                                <th
                                                                    style="background:#0dcaf0;color:black;font-weight: bold;border: 1px solid;">
                                                                    Recruiter</th>
                                                                <th
                                                                    style="background:#0dcaf0;color:black;font-weight: bold;border: 1px solid;">
                                                                    Positions Working</th>
                                                                <th
                                                                    style="background:#0dcaf0;color:black;font-weight: bold;border: 1px solid;">
                                                                    RFH NO</th>
                                                                <th
                                                                    style="background:#0dcaf0;color:black;font-weight: bold;border: 1px solid;">
                                                                    CVs per position</th>
                                                                <th
                                                                    style="background:#0dcaf0;color:black;font-weight: bold;border: 1px solid;">
                                                                    Total Cvs</th>
                                                                <th
                                                                    style="background:#0dcaf0;color:black;font-weight: bold;border: 1px solid;">
                                                                    RFH No. for Offers Released </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>

                                                </div>
                                                <div class="col-md-12">
                                                    <div id="avg_calculation" class="align-center">
                                                        <div class="row" style="margin-top:25px; margin-left:20px;">
                                                            <br>

                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label>From Date</label>
                                                                    <input type="date" name="from-date"
                                                                        id="form_date_avg" class="form-control" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label>To Date</label>
                                                                    <input type="date" name="to-date" id="to_date_avg"
                                                                        class="form-control" />
                                                                    {{-- <input type="hidden" name="today" id="today" value="<?php echo date('d-m-Y'); ?>"  class="form-control"/> --}}

                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <button style="margin-top: 24px;"
                                                                        class="btn  btn-warning"
                                                                        id="Clearbtn_avg">Clear</button>
                                                                </div>
                                                            </div>

                                                            <div style="back" id="average">Average: <span
                                                                    id="avg_count"></span>
                                                                </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- tab closed -->

                                            <div class="tab-pane fade" id="sc-daily" role="tabpanel"
                                                aria-labelledby="sc-tab">

                                                <div class="row" style="margin-top:25px; margin-left:20px;">
                                                    <br>

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>From Date</label>
                                                            <input type="date" name="from-date" id="form_date_sc"
                                                                class="form-control" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>To Date</label>
                                                            <input type="date" name="to-date" id="to_date_sc"
                                                                class="form-control" />
                                                            {{-- <input type="hidden" name="today" id="today" value="<?php echo date('d-m-Y'); ?>"  class="form-control"/> --}}

                                                        </div>
                                                    </div>


                                                    <div class="col-md-2">
                                                        <div class="form-group">

                                                            {{-- <button type="submit" style ="margin-top: 24px;"  onclick="get_re_daily_filter();" class="btn btn-primary">Filter</button> --}}
                                                            <button style="margin-top: 24px;" class="btn  btn-warning"
                                                                id="Clearbtn_sc">Clear</button>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="table-responsive col-md-6">
                                                    <table class="table table-bordered table-striped"
                                                        id="r_count_sc_tb">
                                                        <thead>
                                                            {{-- <tr>
                    <th style="background:#0dcaf0;color:black;font-weight: bold;">Total Recruited</th>
                    <th style="background:#0dcaf0;color:black;font-weight: bold;">Avg Open Position</th>
                    <th style="background:#0dcaf0;color:black;font-weight: bold;">Avg Recuitment Per Recruiter </th>
                    <th style="background:#0dcaf0;color:black;font-weight: bold;">Max Per Recruiter</th>
                    <th style="background:#0dcaf0;color:black;font-weight: bold;">Min Per Recruiter</th>
                    <th style="background:#0dcaf0;color:black;font-weight: bold;">Total New OP</th>
                    <th style="background:#0dcaf0;color:black;font-weight: bold;">Total New Billable </th>
                    <th style="background:#0dcaf0;color:black;font-weight: bold;">Total New Non Billable </th>
                </tr> --}}
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td style="background:#e0b0ca;color:black;font-weight: bold;">Total Open Positions</td>
                                                                <td style="background:#e0b0ca;color:black;font-weight: bold;"id="total_open"></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="background:#d5e0b0;color:black;font-weight: bold;">Avg Open Positions</td>
                                                                <td style="background:#d5e0b0;color:black;font-weight: bold;"id="avg_open"></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="background:#a6c6da;color:black;font-weight: bold;">Total New OP</td>
                                                                <td style="background:#a6c6da;color:black;font-weight: bold;" id="total_op"></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="background:#e0b0ca;color:black;font-weight: bold;">Total Current Billable</td>
                                                                <td style="background:#e0b0ca;color:black;font-weight: bold;" id="total_billable"></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="background:#d5e0b0;color:black;font-weight: bold;">Total Current Non Billable</td>
                                                                <td style="background:#d5e0b0;color:black;font-weight: bold;" id="total_non_billable"></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="background:#e0b0ca;color:black;font-weight: bold;">Total Recruited</td>
                                                                <td style="background:#e0b0ca;color:black;font-weight: bold;"id="total_recruit"></td>
                                                            </tr>

                                                            <tr>
                                                                <td style="background:#a6c6da;color:black;font-weight: bold;">Avg Recruitment Per Recruiter </td>
                                                                <td style="background:#a6c6da;color:black;font-weight: bold;" id="avg_recruit"></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="background:#e0b0ca;color:black;font-weight: bold;">Max Per Recruiter</td>
                                                                <td style="background:#e0b0ca;color:black;font-weight: bold;" id="max_count"></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="background:#d5e0b0;color:black;font-weight: bold;">Min Per Recruiter</td>
                                                                <td style="background:#d5e0b0;color:black;font-weight: bold;" id="min_count"></td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="row" style="margin-top:25px; margin-left:20px;">
                                                    <br>

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>From Date</label>
                                                            <input type="date" name="from-date"
                                                                id="form_date_close" class="form-control" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>To Date</label>
                                                            <input type="date" name="to-date" id="to_date_close"
                                                                class="form-control" />
                                                            {{-- <input type="hidden" name="today" id="today" value="<?php echo date('d-m-Y'); ?>"  class="form-control"/> --}}

                                                        </div>
                                                    </div>
                                                    {{-- <div class="col-md-2">
        <div class="form-group">
        <label>Select Team</label>
        <select class="form-control" name="team" id="team_close">
            <option value="">Select</option>
            <option value="CKPL">CKPL</option>
            <option value="HEPL">HEPL</option>
            <option value="RPO">RPO</option>
        </select>
    </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
        <label>Select Recruiter</label>
        <select class="form-control" name="recruit_name" id="recruit_name_close"></select>
    </div>
    </div> --}}

                                                    <div class="col-md-2">
                                                        <div class="form-group">

                                                            {{-- <button type="submit" style ="margin-top: 24px;"  onclick="get_re_daily_filter();" class="btn btn-primary">Filter</button> --}}
                                                            <button style="margin-top: 24px;" class="btn  btn-warning"
                                                                id="Clearbtn_close">Clear</button>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="table-responsive col-md-6">
                                                    <input type='hidden' id='sort' value='asc'>

                                                    <table class="table table-bordered table-striped"
                                                        id="closure_tb">
                                                        <thead>
                                                            <tr>
                                                                {{-- <th style="background:#0dcaf0;color:black;font-weight: bold;">S.no</th> --}}
                                                                <th id="rc" onclick = "sort_table_closure('recruiter','HR Recruiter &#9650','rc')"
                                                                    style="background:#0dcaf0;color:black;font-weight: bold;">
                                                                    HR Recruiter &#9650</th>
                                                                <th id="bill" onclick = "sort_table_closure('billable_dt','Billable &#9650','bill')"
                                                                    style="background:#0dcaf0;color:black;font-weight: bold;">
                                                                    Billable &#9650</th>
                                                                <th id="nbill" onclick = "sort_table_closure('non_billable_dt','Non Billable &#9650','nbill')"
                                                                    style="background:#0dcaf0;color:black;font-weight: bold;">
                                                                    Non Billable &#9650</th>
                                                                <th id="cc" onclick = "sort_table_closure('closed_dt','Closed Count &#9650','cc')"
                                                                    style="background:#0dcaf0;color:black;font-weight: bold;">
                                                                    Closed Count &#9650</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="closure_table">

                                                        </tbody>
                                                    </table>
                                                </div>






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
    <script src="../assets/pro_js/daily_report.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js'></script>
    <script src="../assets/pro_js/score_card.js"></script>
    <script src="../assets/pro_js/recruiter_daily_report.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(".js-select2").select2();

            var page = "daily_report";

            if (page == "daily_report") {
                $(".daily_report_m").addClass("active");
            }

        });

        var get_closed_position_link = "{{ url('get_closed_position') }}";
        var get_position_title_link = "{{ url('get_position_title_af') }}";
        var get_sub_position_title_link = "{{ url('get_sub_position_title_af') }}";
        var get_location_link = "{{ url('get_location_af') }}";
        var get_business_link = "{{ url('get_business_af') }}";
        var get_function_link = "{{ url('get_function_af') }}";
        var get_division_link = "{{ url('get_division_af') }}";

        var get_band_details_link = "{{ url('get_band_details') }}";
        var get_recruiter_list_link_closed = "{{ url('get_recruiter_list_af') }}";
        var get_raisedby_link = "{{ url('get_raisedby_af') }}";
        var get_source_list_link = "{{ url('get_source_list_af') }}";
        var get_open_position_link = "{{ url('get_open_position') }}";
        var get_interviewer_list_link = "{{ url('get_interviewer_af') }}";

        //RC SC LINK

        var get_score_card_link = "{{ url('get_score_card') }}";
        var get_score_card_filter_link = "{{ url('get_score_card_filter') }}";
        var get_recruiter_list_link = "{{ url('get_recruiter_list_team') }}";
        // daily rc linl
        var get_recruiter_daily_report_link = "{{ url('get_recruiter_daily_report') }}";
        var get_recruiter_daily_report_filter_link = "{{ url('get_recruiter_daily_report_filter') }}";
        var get_recruiter_list_link = "{{ url('get_recruiter_list_team') }}";
        var get_sc_count_filter_link = "{{ url('get_sc_count') }}";
        var get_closure_details_link = "{{ url('get_closure_details') }}";
        var get_average_link = "{{ url('get_average') }}";
        var get_ageing_report_link = "{{ url('get_ageing_report') }}";

    </script>
</body>

</html>
