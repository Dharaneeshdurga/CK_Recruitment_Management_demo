<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Allocation List - {{ $siteTitle }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Common CSS -->
    @include('layouts.commoncss')
    <!-- Common CSS -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css'>
    <link href="../assets/css/select2.css" rel="stylesheet">
    <style>
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

    .nav-st a {
        font-size: larger;
        font-weight: 700;
    }

    .buttons-columnVisibility {
        color: #000000;
        text-decoration: none;
        background-color: rgb(255 255 255 / 12%);
        font-weight: 600;
    }

    .table {
        color: #000000;
    }

    a.disabled {
        /* And disable the pointer events */
        pointer-events: none;
        cursor: not-allowed;
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
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
    table {
        /* display: block;
        max-width: -moz-fit-content;
        max-width: fit-content;
        margin: 0 auto;
        overflow-x: auto;
        white-space: nowrap; */
        /* max-height: 800px; */
        /* overflow-y: auto; */
    }

    #child_view {
        max-width: unset !important;
    }

    #fh_1 {
        max-width: unset !important;
        display: inline-table;
    }

    #fh_2 {
        max-width: unset !important;
        display: inline-table;
    }

    #fh_3 {
        max-width: unset !important;
        display: inline-table;
    }

    .filter_tool {
        color: #000;
        box-shadow: 0 2px 6px 0 rgba(0, 0, 0, .3) !important;
        padding: .75rem 1rem !important;
        border-radius: .267rem !important;
        font-weight: 600;
    }

    .nop_st {
        height: unset !important;
        max-height: unset !important;
        overflow-y: unset !important;
    }

    .dropdown-item.active{
        background-color: #435ebe !important;
        box-shadow: unset !important;
        border: 1px solid #435ebe !important;
        background-image:unset !important;
        color: #fff !important;
        background-image: -webkit-linear-gradient(top, #01549b 0%, #025aa5 100%);
    }
    .dropdown-item{
        background-color: #ffffff !important;
        box-shadow: unset !important;
        border: 1px solid #435ebe !important;
        background-image:unset !important;
        color: #435ebe !important;
        background-image: -webkit-linear-gradient(top, #01549b 0%, #025aa5 100%);
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
                            <h3>Allocation List</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="add_recruit_request">Add Allocation</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">View Allocation List</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <!-- nop edit popup start-->
                <button type="button" class="btn btn-outline-warning" id="show_nopedit_pop" data-bs-backdrop="true"
                    style="display:none" data-bs-toggle="modal" data-bs-target="#edit_noppop_modal_div">Edit NOP
                    Modal</button>

                <div class="modal fade text-left" id="edit_noppop_modal_div" tabindex="-1" role="dialog"
                    aria-labelledby="show_edit_noppop_title" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="show_edit_noppop_title"></h4>
                                <button type="button" id="close_edit_noppop" class="close" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i data-feather="x"></i>
                                </button>
                            </div>

                            <div class="modal-body">
                                <label for="action_type">Action Type</label>
                                <select name="action_type" id="action_type" class="form-control">';
                                    <option value="add">Add</option>
                                    <option value="minus">Reduce</option>
                                </select>
                                <label for="close_date_edit">No of Position</label>
                                <input type="hidden" class="form-control" id="current_no_of_position"
                                    name="current_no_of_position" value="">
                                <input type="number" min="1" class="form-control" id="no_of_position"
                                    name="no_of_position" value="1">
                                <input type="hidden" class="form-control" id="rfh_nop" name="rfh_nop" value="">

                                <span id="nop_error" style="color:red;display:none;">* fields are required</span>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-secondary btn-sm" data-bs-dismiss="modal">
                                    <i class="bx bx-x d-block d-sm-none"></i>
                                    <span class="d-sm-block d-none">Close</span>
                                </button>
                                <button type="button" id="btnEditnopUpdate" class="btn btn-primary ml-1 btn-sm"
                                    data-bs-dismiss="modal">
                                    <i class="bx bx-check d-block d-sm-none"></i>
                                    <span class="d-sm-block d-none">Update</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- nop edit popup end-->


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

                <!-- ticket delete popup start-->
                <button type="button" class="btn btn-outline-warning" id="confirmbox" data-bs-backdrop="true"
                    style="display:none" data-bs-toggle="modal" data-bs-target="#delete_pop_modal_div">Delete Pop
                    Modal</button>

                <div class="modal fade text-left" id="delete_pop_modal_div" tabindex="-1" role="dialog"
                    aria-labelledby="show_delete_pop_title" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <!-- <h4 class="modal-title" id="show_edit_pop_title"></h4> -->
                                <button type="button" id="close_delete_pop" class="close" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i data-feather="x"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <h6>Are you sure you want to Delete this Record?</h6>
                                <label for="delete_remark">Delete Remark*</label>
                                <textarea name="delete_remark" id="delete_remark" class="form-control"></textarea>
                                <span id="error_delete_remark" style="display:none;color:red;">* field is
                                    required..!</span>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger cancelbtn" id="cancelSubmit">No</button>
                                <button type="button" class="btn btn-success deletebtn" id="confirmSubmit">Yes</button>


                            </div>
                        </div>
                    </div>
                </div>
                <!-- ticket delete popup end-->


                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            <!-- Simple Datatable -->
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                                    <div class="row">

                                        <div class="col-lg-6">
                                            <label for="">Add Allocation </label>
                                            <br>
                                            <a href="http://hub1.cavinkare.in/CK_RFH/" target="_blank"><button
                                                    class="btn btn-primary" title="Add Allocation"><i
                                                        class="bi bi-plus-circle"></i> Add Allocation</button></a>
                                            <br> <br>
                                            <button class="btn btn-dark" onclick="show_advanced_filter();"
                                                id="advanced_filter"><i class="bi bi-funnel-fill"></i> Advanced
                                                Filter</button>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="">Last Allocated Form No</label>
                                            <p id="last_rfhno"></p>
                                        </div>
                                    </div>

                                </div>


                                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 info_tool">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="">Assigned Status</label>
                                            <br>
                                            <span class="badge bg-danger" title="Pending"><i
                                                    class="bi bi-shield-slash"></i> Pending</span>
                                            <span class="badge bg-secondary" title="Assigned"><i
                                                    class="bi bi-shield-check"></i> Assigned</span>
                                            <span class="badge bg-info" title="Edit"><i class="bi bi-pencil-square"></i>
                                                Edit</span>
                                            <span class="badge bg-primary" title="Assign"><i
                                                    class="bi bi-person-lines-fill"></i> Assign</span>
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

                        <!-- followup model start -->
                        <!-- Button trigger for large size modal -->
                        <button type="button" id="btnFollowup" class="btn btn-outline-warning" style="display:none;"
                            data-bs-toggle="modal" data-bs-target="#large">
                            Large Modal
                        </button>
                        <!--large size Modal -->
                        <div class="modal fade" id="large" data-bs-backdrop="true" tabindex="-1" role="dialog"
                            aria-labelledby="followupModalTitle" aria-hidden="true">

                            <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="followupModalTitle">Followup Info - <span
                                                id="fh_candidate_name"></span></h5>
                                                <h5 class="modal-title" style="color: #000000;">HEPL Ref.No - <span
                                        id="fh_hepl_ref_no"></span></h5>
                                        <h5 class="modal-title" style="color: #000000;">Recruiter - <span
                                        id="fh_recruiter"></span></h5>
                                        <h5 class="modal-title" >Position - <span
                                        id="fh_position"></span></h5>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                            <i class="bi bi-x-octagon-fill"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="table-responsive">
                                            <table class="table mb-0" id="fh_1">
                                                <thead>
                                                    <tr>
                                                        <th>S.No.</th>
                                                        <th>Follow up Status</th>
                                                        <th>Created On</th>

                                                    </tr>
                                                </thead>
                                                <tbody id="followupModalBody">

                                                </tbody>
                                            </table>
                                        </div>
                                        <div id="or_report_tb"></div>
                                        <div id="orld_report_tb"></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- followup model end -->

                        <div class="card-body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item nav-st" role="presentation">
                                    <a class="nav-link active" id="yet_to_allocate-tab" data-bs-toggle="tab"
                                        href="#yet_to_allocate" role="tab" aria-controls="profile"
                                        aria-selected="false">Yet To Allocate List</a>
                                </li>
                                <li class="nav-item nav-st" role="presentation">
                                    <a class="nav-link " id="allocated_list-tab" data-bs-toggle="tab"
                                        href="#allocated_list" role="tab" aria-controls="home"
                                        aria-selected="true">Allocated List</a>
                                </li>
                                <li class="nav-item nav-st" role="presentation">
                                    <a class="nav-link " id="offer_released-tab" data-bs-toggle="tab"
                                        href="#offer_released" role="tab" aria-controls="home"
                                        aria-selected="true">Offer Released</a>
                                </li>
                                <li class="nav-item nav-st" role="presentation">
                                    <a class="nav-link " id="candidate_onboarded-tab" data-bs-toggle="tab"
                                        href="#candidate_onboarded" role="tab" aria-controls="home"
                                        aria-selected="true">Candidate Onboarded</a>
                                </li>

                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="yet_to_allocate" role="tabpanel"
                                    aria-labelledby="yet_to_allocate-tab">

                                    <br>
                                    <h5>No. of Records Found: <span id="total_res_show_tab1"></span></h5>

                                    <div class="table-responsive">
                                        <table class="table" id="table1" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>S.No.</th>
                                                    <th>RFH No</th>
                                                    <th>Position Title</th>
                                                    <th>No. of Position</th>
                                                    <th>Position Ageing</th>
                                                    <th>Open Date</th>
                                                    <th>Location</th>
                                                    <th>Assigned Status</th>
                                                    <th>Action</th>

                                                    <th>Assigned Status</th>
                                                    <th>Position Status</th>
                                                    <th>Business</th>
                                                    <th>Band</th>
                                                    <th>Critical Position</th>
                                                    <th>Division</th>
                                                    <th>Function</th>
                                                    <th>Location</th>
                                                    <th>Billing Status</th>
                                                    <th>Interviewer</th>
                                                    <th>Maximum CTC(Per Month)</th>
                                                    <th>Closed Date</th>
                                                    <th>Edit</th>
                                                    <th>Request raised by </th>
                                                    <th>Approved by </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="allocated_list" role="tabpanel"
                                    aria-labelledby="allocated_list-tab">
                                    <br>
                                    <h5>No. of Records Found: <span id="total_res_show_tab2"></span></h5>

                                    <div class="table-responsive">
                                        <table class="table" id="table2" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>S.No.</th>
                                                    <th>RFH No</th>
                                                    <th>Position Title</th>
                                                    <th>Sub Position Title</th>
                                                    <th>No. of Position</th>
                                                    <th>Position Ageing</th>
                                                    <th>Open Date</th>
                                                    <th>Location</th>
                                                    <th>Assigned Status</th>
                                                    <th>Action</th>

                                                    <th>Assigned Status</th>
                                                    <th>Position Status</th>
                                                    <th>Business</th>
                                                    <th>Band</th>
                                                    <th>Critical Position</th>
                                                    <th>Division</th>
                                                    <th>Function</th>
                                                    <th>Billing Status</th>
                                                    <th>Interviewer</th>
                                                    <th>Maximum CTC(Per Month)</th>
                                                    <th>Closed Date</th>
                                                    <th>Edit</th>
                                                    <th>Request raised by </th>
                                                    <th>Mobile number</th>
                                                    <th>Email address</th>
                                                    <th>Approved by </th>
                                                    <th>Ticket Number</th>
                                                    <th>Location Preferred</th>
                                                    <th>JD / Roles & Responsibilities</th>
                                                    <th>Qualification</th>
                                                    <th>Essential Skill sets</th>
                                                    <th>Good to have Skill sets</th>
                                                    <th>Experience </th>
                                                    <th>Any other specific consideration</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="offer_released" role="tabpanel"
                                    aria-labelledby="offer_released-tab">
                                    <!-- Striped rows start -->
                                    <section class="section">
                                        <div class="row" id="table-striped">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-content">
                                                        <br>
                                                        <h5>No. of Records Found: <span id="total_res_show_tab3"></span>
                                                        </h5>

                                                        <!-- table striped -->
                                                        <div class="table-responsive">
                                                            <table class="table mb-0" id="offer_released_tba">
                                                                <thead>
                                                                    <tr>
                                                                        <th>S.No.</th>
                                                                        <th>Candidate Name</th>
                                                                        <th>HEPL Recruitment Ref No</th>
                                                                        <th>Closed date</th>
                                                                        <th>Closed Salary</th>
                                                                        <th>Salary Review</th>
                                                                        <th>Joining Type</th>
                                                                        <th>Date of Joining</th>
                                                                        <th>Remark</th>
                                                                        <th>Candidate CV</th>
                                                                        <th>Followup History</th>
                                                                        <!-- <th>Action</th> -->
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
                                    </section>
                                    <!-- Striped rows end -->
                                </div>
                                <div class="tab-pane fade" id="candidate_onboarded" role="tabpanel"
                                    aria-labelledby="candidate_onboarded-tab">
                                    <!-- Candidate Onboarded start -->
                                    <br>
                                    <div class="card-content">
                                        <!-- table striped -->
                                        <h5>No. of Records Found: <span id="total_res_show_tab4"></span></h5>

                                        <div class="table-responsive">
                                            <table class="table" id="c_onboarded">
                                                <thead>
                                                    <tr>
                                                        <th>S.No.</th>
                                                        <th>Candidate Name</th>
                                                        <th>Position</th>
                                                        <th>Sub Position</th>
                                                        <th>Candidate Source</th>
                                                        <th>Gender</th>
                                                        <th>CTC</th>
                                                        <th>CV</th>
                                                        <th>Follow Up</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Candidate Onboarded end -->
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
    <script src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js'></script>

    <script src="../assets/pro_js/view_recruit_request_def.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {

        $(".js-select2").select2();

        var page = "view_recruit_request";

        if (page == "view_recruit_request") {
            $(".recruit_req_m").addClass("active");
        }



    });

    var get_recruitment_request_def_list_link = "{{url('get_recruitment_request_def_list')}}";
    var get_recruitment_request_def_list_ag_link = "{{url('get_recruitment_request_def_list_ag')}}";

    var process_ticket_edit_link = "{{url('process_ticket_edit')}}";
    var getlast_rfhno_link = "{{url('getlast_rfhno')}}";
    var process_ticket_delete_link = "{{url('process_ticket_delete')}}";

    var get_position_title_link = "{{url('get_position_title_af')}}";
    var get_sub_position_title_link = "{{url('get_sub_position_title_af')}}";

    var get_location_link = "{{url('get_location_af')}}";
    var get_business_link = "{{url('get_business_af')}}";
    var get_function_link = "{{url('get_function_af')}}";
    var get_band_details_link = "{{url('get_band_details')}}";
    var get_division_link = "{{url('get_division_af')}}";

    var get_offer_released_bc_link = "{{url('get_offer_released_bc')}}";
    var candidate_follow_up_history_bc_link = "{{url('candidate_follow_up_history_bc')}}";
    var get_offer_released_report_bc_link = "{{url('get_offer_released_report_bc')}}";
    var get_candidate_onborded_history_bc_link = "{{url('get_candidate_onborded_history_bc')}}";
    var update_no_of_position_link = "{{('update_no_of_position')}}";

    var get_raisedby_link = "{{url('get_raisedby_af')}}";
    var get_approvedby_link = "{{url('get_approvedby_af')}}";
    </script>
    <script>
    // Simple Datatable
    // let table1 = document.querySelector('#table1');
    // let dataTable = new simpleDatatables.DataTable(table1);
    </script>
</body>

</html>
