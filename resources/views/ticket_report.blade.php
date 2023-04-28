<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Report - {{ $siteTitle }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Common CSS -->
    @include('layouts.commoncss')
    <!-- Common CSS -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css'>
    <link href="../assets/css/select2.css" rel="stylesheet">
    <style>
    .dataTables_filter {
        display: none;
    }

    #custom_filter {
        float: right !important;
        padding: .375rem .75rem;
        border: 1px solid #dce7f1;
    }

    .fontawesome-icons {
        text-align: center;
    }

    article dl {
        background-color: rgba(0, 0, 0, .02);
        padding: 20px;
    }

    .fontawesome-icons .the-icon svg {
        font-size: 24px;
    }

    .btn:disabled {
        cursor: not-allowed;
        pointer-events: all !important;
    }

    .form-control:disabled {
        cursor: not-allowed;
        pointer-events: all !important;
    }

    /* .dataTables_scrollBody {
        overflow: hidden !important;
    } */

    table {
        width: 100% !important;
    }

    .info_tool {
        background: #0dcaf02b !important;
        /* float: left!important; */
        color: #000;
        box-shadow: 0 2px 6px 0 rgba(0, 0, 0, .3) !important;
        padding: .75rem 1rem !important;
        border-radius: .267rem !important;
        font-weight: 600;
    }

    @media only screen and (max-width: 600px) {
        .btn-group {
            margin-bottom: -25px !important;
            display: inline-grid !important;
            margin-left: 5px;
        }

        #table1_filter {
            float: left !important;
            margin-top: 32px;
            ;
        }

        #but_save {
            margin-top: 8px;
            ;
        }

        .info_tool {
            margin-top: 8px;
            ;
        }

        .badge {
            margin-bottom: 5px;
            ;
        }
    }

    .bg-onhold {
        background-color: #964f8e;
    }

    .bg-reopen {
        background-color: #e4717a;
    }

    .modal-backdrop.show {
        width: 100%;
        height: 100%;
    }

    .table {
        color: #000000;
    }

    .form-group label {
        color: #000000;

    }

    .filter_tool {
        color: #000;
        box-shadow: 0 2px 6px 0 rgba(0, 0, 0, .3) !important;
        padding: .75rem 1rem !important;
        border-radius: .267rem !important;
        font-weight: 600;
    }

    .bg-c-pink {
        background-color: #FC6180;
    }

    /* table {
        display: block;
        max-width: -moz-fit-content;
        max-width: fit-content;
        margin: 0 auto;
        overflow-x: auto;

        max-height: 800px;
        overflow-y: auto;
        white-space: nowrap;

    } */

    #child_view {
        max-width: unset !important;
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

    .form-group {
        margin-bottom: unset !important;
    }

    .dataTables_length {
        margin-bottom: 10px;
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
                            <h3>Allocation Report</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <!-- <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="add_recruit_request">Add Ticket</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">View Ticket</li>
                                    </ol>
                                </nav> -->
                        </div>
                    </div>
                </div>

                <!-- ticket edit popup start-->
                <button type="button" class="btn btn-outline-warning" id="show_edit_pop" data-bs-backdrop="true"
                    style="display:none" data-bs-toggle="modal" data-bs-target="#edit_pop_modal_div">Edit Pop
                    Modal</button>

                <div class="modal fade text-left" id="edit_pop_modal_div" tabindex="-1" role="dialog"
                    aria-labelledby="show_edit_pop_title" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="show_edit_pop_title"></h4>
                                <button type="button" id="close_edit_pop" class="close" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i data-feather="x"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="ticket_rfh_no" name="ticket_rfh_no">
                                <select name="ticket_status" id="ticket_status" class="form-control">
                                    <option value="">Select Position Status</option>
                                    <option value="On Hold">On Hold</option>
                                    <option value="Re Open">Re Open</option>
                                </select>

                                <span class="badge bg-warning" style="display:none;">* Fields Required</span>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-secondary btn-sm" data-bs-dismiss="modal">
                                    <i class="bx bx-x d-block d-sm-none"></i>
                                    <span class="d-sm-block d-none">Close</span>
                                </button>
                                <button type="button" id="btnEditUpdate" class="btn btn-primary ml-1 btn-sm"
                                    data-bs-dismiss="modal">
                                    <i class="bx bx-check d-block d-sm-none"></i>
                                    <span class="d-sm-block d-none">Update</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ticket edit popup end-->
                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            <!-- Simple Datatable -->
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                    <br>
                                    <button class="btn btn-primary" onclick="show_advanced_filter();"><i
                                            class="bi bi-funnel-fill"></i> Advanced Filter</button>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">

                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 info_tool">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="">Assigned Status</label>
                                            <br>
                                            <span class="badge bg-danger" title="Pending"><i
                                                    class="bi bi-shield-slash"></i> Pending</span>
                                            <span class="badge bg-secondary" title="Assigned"><i
                                                    class="bi bi-shield-check"></i> Assigned</span>
                                            <!-- <span class="badge bg-info" title="Edit"><i class="bi bi-pencil-square"></i> Edit</span> -->
                                            <span class="badge bg-primary" title="Candidate Details"><i
                                                    class="bi bi-people-fill"></i> Candidate Details</span>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="">Position Status</label>
                                            <br>
                                            <span class="badge bg-warning" title="Open"><i class="fa fa-book-open"></i>
                                                Open</span>
                                            <span class="badge bg-success" title="Closed"><i class="fa fa-book"></i>
                                                Closed</span>
                                            <span class="badge bg-onhold" title="On Hold"><i
                                                    class="bi bi-pause-fill"></i> On Hold</span>
                                            <span class="badge bg-dark" title="Re Open"><i class="bi bi-exclude"></i> Re
                                                Open</span>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <!-- filter result start -->
                            <div class="row mt-4 filter_tool" id="show_filter_div" style="display:none;">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="From Date">From Date</label>
                                        <input type="date" name="af_from_date" id="af_from_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="To Date">To Date</label>
                                        <input type="date" name="af_to_date" id="af_to_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="Position Title">Position Title</label>
                                        <select name="af_position_title" id="af_position_title"
                                            class="form-control js-select2">

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="Sub Position Title">Sub Position Title</label>
                                        <select name="af_sub_position_title" id="af_sub_position_title"
                                            class="form-control js-select2">

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="Band">Band</label>
                                        <select name="af_band" id="af_band" class="form-control js-select2">

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="Critical Position">Critical Position</label>
                                        <select name="af_critical_position" id="af_critical_position"
                                            class="form-control">
                                            <option value="">Select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="Position Status">Position Status</label>
                                        <select name="af_position_status" id="af_position_status" class="form-control">
                                            <option value="">Select</option>
                                            <option value="Open">Open</option>
                                            <option value="Closed">Closed</option>
                                            <option value="Re Open">Re Open</option>
                                            <option value="On Hold">On Hold</option>
                                            <option value="Openall">Open | Re Open</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="af_closed_by">Closed By</label>
                                        <select name="af_closed_by" id="af_closed_by" class="form-control js-select2">

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="Assigned Status">Assigned Status</label>
                                        <select name="af_assigned_status" id="af_assigned_status" class="form-control">
                                            <option value="">Select</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Assigned">Assigned</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="af_billable">Billable</label>
                                        <select name="af_billable" id="af_billable" class="form-control">
                                            <option value="">Select</option>
                                            <option value="Billable">Billable</option>
                                            <option value="Non Billable">Non Billable</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="Location">Location</label>
                                        <select name="af_location" id="af_location" class="form-control js-select2">

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="Business">Business</label>
                                        <select name="af_business" id="af_business" class="form-control js-select2">

                                        </select>
                                    </div>
                                </div>
                                <!-- <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="Billing Status">Billing Status</label>
                                            <select name="af_billing_status" id="af_billing_status" class="form-control">
                                                <option value="">Select</option>
                                                <option value="Hiring for Client">Hiring for Client</option>
                                                <option value="Hiring for HEPL">Hiring for HEPL</option>
                                            </select>
                                        </div>
                                    </div> -->


                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="Function">Function</label>
                                        <select name="af_function" id="af_function" class="form-control js-select2">

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="af_division">Division</label>
                                        <select name="af_division" id="af_division" class="form-control js-select2">

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="af_raisedby">Request raised by</label>
                                        <select name="af_raisedby" id="af_raisedby" class="form-control js-select2">

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="af_approvedby">Approved by</label>
                                        <select name="af_approvedby" id="af_approvedby" class="form-control js-select2">

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="af_teams">Teams</label>
                                        <select name="af_teams" id="af_teams" class="form-control">
                                            <option value="">Select</option>
                                            <option value="CKPL">CKPL</option>
                                            <option value="HEPL">HEPL</option>
                                            <option value="RPO">RPO</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2  col-md-offset-10">
                                    <div class="form-group">
                                        <label for="Function">Action</label>
                                        <br>
                                        <button class="btn btn-sm btn-warning" id="afClearbtn">Clear</button>
                                    </div>
                                </div>

                            </div>
                            <!-- filter result end -->
                        </div>

                        <!-- Button trigger for large size modal -->
                        <button type="button" id="stages_of_recruitment_btn" class="btn btn-outline-warning"
                            style="display:none;" data-bs-toggle="modal" data-bs-target="#stages_of_recruitment_pop">
                            Large Modal
                        </button>
                        <!--large size Modal -->
                        <div class="modal fade" id="stages_of_recruitment_pop" data-bs-backdrop="true" tabindex="-1"
                            role="dialog" aria-labelledby="sorTitle" aria-hidden="true">

                            <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="sorTitle">Stages of Recruitment - <span
                                                id="fh_hepl_ref_no"></span></h5>
                                        <h5 class="modal-title" id="sorTitle">Open Date - <span
                                                id="fh_open_date"></span></h5>
                                        <h5 class="modal-title" id="sorTitle">Position Title - <span
                                                id="fh_position_title"></span></h5>
                                        <h5 class="modal-title" id="fh_new_open_date_title"></h5>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                            <i class="bi bi-x-octagon-fill"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div id='cns_div'></div>
                                        <br>
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Stages of Recruitment ageing</th>
                                                        <th>Ageing Days</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Date of RFH to Profile Screened</td>
                                                        <td id="sor_one"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Date Profile Screened to Last Interviewed date</td>
                                                        <td id="sor_two"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Date Last interviewed to Offer Released Date</td>
                                                        <td id="sor_three"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Date Offer Released to Date of Offer Accepted</td>
                                                        <td id="sor_four"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Date Offer Accepted to Onboarded date</td>
                                                        <td id="sor_five"></td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>


                                    </div>

                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <h5>No. of Records Found: <span id="total_res_show"></span></h5>
                            <div class="row  mt-4">
                                <div class="col-md-10">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="custom_filter" id="custom_filter" autocomplete="off"
                                        placeholder="Search">

                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table" id="table1" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>S.No.</th>
                                            <th>HEPL Recruitment Ref Number</th>
                                            <th>Position Title</th>
                                            <th>Sub Position Title</th>
                                            <th id="cv_count">CV Count</th>
                                            <th id="pos_ageing">Position Ageing</th>
                                            <th>Open Date</th>
                                            <th>Closed Date</th>
                                            <th>Current Status</th>
                                            <th>Last Modified</th>
                                            <th>Location</th>
                                            <th>Recruiters</th>
                                            <th>Assigned Status</th>
                                            <th>Closed By</th>
                                            <th>Action</th>

                                            <th>Assigned Status</th>
                                            <th>Position Status</th>
                                            <th>RFH No</th>
                                            <th>No of Position</th>
                                            <th>Business</th>
                                            <th>Band</th>
                                            <th>Critical Position</th>
                                            <th>Division</th>
                                            <th>Function</th>
                                            <th>Billing Status</th>
                                            <th>Interviewer</th>
                                            <th>Maximum CTC(Per Month)</th>
                                            <th>Request raised by </th>
                                            <th>Approved by </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
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
    <script src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js'></script>

    <script src="../assets/pro_js/ticket_report.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {

        $(".js-select2").select2();

        var page = "ticket_report";

        if (page == "ticket_report") {
            $(".ticket_report_m").addClass("active");
        }



    });

    var get_ticket_report_link = "{{url('get_ticket_report')}}";
    var process_ticket_edit_link = "{{url('index.php/process_ticket_edit')}}";

    var get_position_title_link = "{{url('get_position_title_af')}}";
    var get_sub_position_title_link = "{{url('get_sub_position_title_af')}}";
    var get_location_link = "{{url('get_location_af')}}";
    var get_business_link = "{{url('get_business_af')}}";
    var get_function_link = "{{url('get_function_af')}}";
    var get_band_details_link = "{{url('get_band_details')}}";
    var get_division_link = "{{url('get_division_af')}}";
    var get_raisedby_link = "{{url('get_raisedby_af')}}";
    var get_approvedby_link = "{{url('get_approvedby_af')}}";
    var get_recruiter_list_link = "{{url('get_recruiter_list_af')}}";
    var get_stagesof_recruitment_link = "{{url('get_stagesof_recruitment')}}";
    </script>
    <script>

    </script>

</body>

</html>