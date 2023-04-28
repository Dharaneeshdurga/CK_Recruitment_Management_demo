<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Details - {{ $siteTitle }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Common CSS -->
    @include('layouts.commoncss')
    <!-- Common CSS -->
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
                                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">View Details</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="card">

                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                            <!-- <div class="col-lg-2">
													<label for="">Add Ticket</label>
													<br>
													<a href="add_recruit_request" ><span class="badge bg-primary" title="Add Ticket"><i class="bi bi-plus-circle"></i> Add ticket</span></a>
												</div> -->
                                        </div>

                                        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 info_tool">
                                            <div class="row">

                                                <div class="col-lg-7">
                                                    <label for="">Action</label>
                                                    <br>
                                                    <span class="badge bg-primary" title="Fresh Profiles submitted"><i
                                                            class="fa fa-file"></i> Fresh
                                                        Profiles</span>
                                                    <span class="badge bg-success" title="Edit"><i
                                                            class="fa fa-edit"></i> Edit</span>
                                                    <span class="badge bg-info" title="Add More Profiles"><i
                                                            class="fa fa-list-alt"></i> Add More Profiles</span>
                                                    <span class="badge bg-danger" title="Delete Row"><i
                                                            class="fa fa-trash"></i> Delete Row</span>
                                                    
                                                </div>

                                                <div class="col-lg-5">
                                                    <label for="">Position Status</label>
                                                    <br>
                                                    <span class="badge bg-warning" title="Open"><i
                                                            class="fa fa-book-open"></i> Open</span>
                                                    
                                                    <span class="badge bg-success" title="Closed"><i
                                                            class="fa fa-book"></i> Closed</span>

                                                    <span class="badge bg-onhold" title="On Hold"><i
                                                            class="fa fa-pause"></i> On Hold</span>

                                                    <span class="badge bg-dark" title="Re Open"><i
                                                            class="fa fa-retweet"></i> Re Open</span>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- confirmation popup start -->

                                    <!-- Button trigger for info theme modal -->
                                    <button type="button" id="btnConfirm" style="display:none;"
                                        class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#info">
                                        Info
                                    </button>
                                    <!--info theme Modal -->
                                    <div class="modal fade text-left" data-bs-backdrop="true" id="info" tabindex="-1"
                                        role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
                                            role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info">
                                                    <h5 class="modal-title white" id="myModalLabel130">
                                                        Info
                                                    </h5>
                                                    <button type="button" id="confirmClose" class="close"
                                                        data-bs-dismiss="modal" aria-label="Close">
                                                        <i class="bi bi-x-octagon-fill"></i>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure..! You want to process Candidate Onboard?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light-secondary"
                                                        data-bs-dismiss="modal">
                                                        <i class="bx bx-x d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Close</span>
                                                    </button>
                                                    <button type="button" id="btnConfirmsubmit"
                                                        class="btn btn-info ml-1" data-bs-dismiss="modal">
                                                        <i class="bx bx-check d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Accept</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- confirmation popup end -->

                                    <!-- followup model start -->
                                    <!-- Button trigger for large size modal -->
                                    <button type="button" id="btnFollowup" class="btn btn-outline-warning"
                                        style="display:none;" data-bs-toggle="modal" data-bs-target="#large">
                                        Large Modal
                                    </button>
                                    <!--large size Modal -->
                                    <div class="modal fade" id="large" data-bs-backdrop="true" tabindex="-1"
                                        role="dialog" aria-labelledby="followupModalTitle" aria-hidden="true">

                                        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="followupModalTitle">Followup Info -
                                                        <span id="fh_candidate_name"></span>
                                                    </h5>
                                                    <h5 class="modal-title" style="color: #000000;">HEPL Ref.No - <span
                                                            id="fh_hepl_ref_no"></span></h5>
                                                    <h5 class="modal-title" style="color: #000000;">Recruiter - <span
                                                            id="fh_recruiter"></span></h5>
                                                    <h5 class="modal-title">Position - <span id="fh_position"></span>
                                                    </h5>
                                                    <button type="button" class="close" data-bs-dismiss="modal"
                                                        aria-label="Close">
                                                        <i class="bi bi-x-octagon-fill"></i>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="table-responsive">
                                                        <table class="table mb-0">
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


                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item nav-st" role="presentation">
                                            <a class="nav-link active" id="new_position-tab" data-bs-toggle="tab"
                                                href="#new_position" role="tab" aria-controls="profile"
                                                aria-selected="false">New positions allocated for the day</a>
                                        </li>
                                        <li class="nav-item nav-st" role="presentation">
                                            <a class="nav-link " id="old_position-tab" data-bs-toggle="tab"
                                                href="#old_position" role="tab" aria-controls="home"
                                                aria-selected="true">Old positions</a>
                                        </li>
                                        <li class="nav-item nav-st" role="presentation">
                                            <a class="nav-link" id="offer_released_ldj-tab" data-bs-toggle="tab"
                                                href="#offer_released_ldj" role="tab" aria-controls="contact"
                                                aria-selected="false">Offers released</a>
                                        </li>
                                        <li class="nav-item nav-st" role="presentation">
                                            <a class="nav-link" id="candidate_onboarded_ldj-tab" data-bs-toggle="tab"
                                                href="#candidate_onboarded_ldj" role="tab" aria-controls="contact"
                                                aria-selected="false">Candidate Onboarded</a>
                                        </li>
                                        <li class="nav-item nav-st" role="presentation">
                                            <a class="nav-link" id="inactive-tab" data-bs-toggle="tab" href="#inactive"
                                                role="tab" aria-controls="inactive" aria-selected="false">On Hold</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="new_position" role="tabpanel"
                                            aria-labelledby="new_position-tab">
                                            <br>
                                            <h5>No. of Records Found: <span id="total_res_show_tab1"></span></h5>

                                            <div class="table-responsive">
                                                <table class="table" id="table1" cellspacing="0">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>S.No.</th>
                                                            <th>HEPL Recruitment Ref. No</th>
                                                            <th>Position Title</th>
                                                            <th>Sub Position Title</th>
                                                            <th>Position Ageing</th>
                                                            <th>Assigned Date</th>
                                                            <th>Open Date</th>
                                                            <th>Location</th>
                                                            <th>Position Status</th>
                                                            <th>Action</th>
                                                            <th>Request raised by </th>
                                                            <th>Approved by </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="old_position" role="tabpanel"
                                            aria-labelledby="old_position-tab">
                                            <br>
                                            <h5>No. of Records Found: <span id="total_res_show_tab2"></span></h5>

                                            <div class="table-responsive">
                                                <table class="table" id="table_op" cellspacing="0">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>S.No.</th>
                                                            <th>HEPL Recruitment Ref. No</th>
                                                            <th>Position Title</th>
                                                            <th>Sub Position Title</th>
                                                            <th>Position Ageing</th>
                                                            <th>CV Count</th>
                                                            <th>Assigned Date</th>
                                                            <th>Current Status</th>
                                                            <th>Open Date</th>
                                                            <th>Closed Date</th>
                                                            <th>Location</th>
                                                            <th>Position Status</th>
                                                            <th>Action</th>
                                                            <th>Request raised by </th>
                                                            <th>Approved by </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="offer_released_ldj" role="tabpanel"
                                            aria-labelledby="offer_released_ldj-tab">
                                            <!-- Striped rows start -->
                                            <section class="section">
                                                <div class="row" id="table-striped">
                                                    <div class="col-12">
                                                        <div class="card">
                                                            <div class="card-content">
                                                                <br>
                                                                <h5>No. of Records Found: <span
                                                                        id="total_res_show_tab3"></span></h5>

                                                                <!-- table striped -->
                                                                <div class="table-responsive">
                                                                    <table class="table mb-0" id="offer_released_tba">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>S.No.</th>
                                                                                <th>Candidate Name</th>
                                                                                <th>HEPL Recruitment Ref No</th>
                                                                                <!-- <th>Closed date</th> -->
                                                                                <th>Closed Salary</th>
                                                                                <th>Salary Review</th>
                                                                                <th>Joining Type</th>
                                                                                <th>Date of Joining</th>
                                                                                <th>Remark</th>
                                                                                <th>Candidate CV</th>
                                                                                <th>Followup History</th>
                                                                                <th>Action</th>
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
                                        <div class="tab-pane fade" id="inactive" role="tabpanel"
                                            aria-labelledby="inactive-tab">
                                            <br>
                                            <h5>No. of Records Found: <span id="total_res_show_tab5"></span></h5>

                                            <div class="table-responsive">
                                                <table class="table" id="table_inactive" cellspacing="0">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>S.No.</th>
                                                            <th>HEPL Recruitment Ref. No</th>
                                                            <th>Position Title</th>
                                                            <th>Sub Position Title</th>
                                                            <th>Position Ageing</th>
                                                            <th>Assigned Date</th>
                                                            <th>Open Date</th>
                                                            <th>Location</th>
                                                            <th>Position Status</th>
                                                            <!-- <th>Action</th> -->
                                                            <th>Request raised by </th>
                                                            <th>Approved by </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="candidate_onboarded_ldj" role="tabpanel"
                                            aria-labelledby="candidate_onboarded-tab">
                                            <!-- Candidate Onboarded start -->
                                            <section class="section">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="card">
                                                            <div class="card-header">
                                                                <!-- <h4 class="card-title">Candidate Onboarded</h4> -->
                                                            </div>
                                                            <div class="card-content">
                                                                <!-- table striped -->
                                                                <h5>No. of Records Found: <span
                                                                        id="total_res_show_tab4"></span></h5>

                                                                <div class="table-responsive">
                                                                    <table class="table  mb-0" id="c_onboarded">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>S.No.</th>
                                                                                <th>Candidate Name</th>
                                                                                <th>Position</th>
                                                                                <th>Sub Position</th>
                                                                                <th>Candidate Source</th>
                                                                                <th>Gender</th>
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
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
                                            <!-- Candidate Onboarded end -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card" id="show_cv_process" style="display:none;">
                            <div class="col-12">
                                <!-- get profile count field -->
                                <div class="row">
                                    <div class="col-6 col-lg-6 col-md-6 mt-2">
                                        <p><strong>HEPL Recruitment Ref. No.: </strong><span
                                                id="show_pc_hepl_ref_no"></span></p>
                                    </div>
                                    <div class="col-6 col-lg-6 col-md-6 mt-2">
                                        <p><strong>Position Title: </strong><span id="show_pc_position_title"></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6 col-lg-3 col-md-6" id="profile_count_st">
                                        <label for="">Profile Count</label>
                                        <input type="text" name="profile_count" id="profile_count"
                                            oninput="numberValid();" class="form-control" placeholder="Profile Count">
                                    </div>
                                    <div class="col-6 col-lg-3 col-md-6">
                                        <label for="">Action</label>
                                        <br>
                                        <button class="btn btn-info" id="btnactionPc">OK</button>
                                        <button class="btn btn-primary" id="btnaddmorePc" title="Add More Profiles"
                                            disabled><i class="bi bi-hdd-stack"></i></button>
                                    </div>
                                </div>
                                <!-- upload candidate cv form -->
                                <div id="show_cv_upload" style="display:none">
                                    <form id="cvSubmit" method="post" action="javascript:void(0)">
                                        <input type="hidden" id="hepl_recruitment_ref_number"
                                            name="hepl_recruitment_ref_number">
                                        <input type="hidden" id="action_for_the_day_put" name="action_for_the_day_put"
                                            value="Profile submitted to Hiring Manager">
                                        <input type="hidden" id="cv_up_rfh_no" name="cv_up_rfh_no">
                                        <table class="table" id="upload_tb">
                                            <thead>
                                                <tr>
                                                    <th>Candidate Name</th>
                                                    <th>CV</th>
                                                    <th>Candidate Source</th>
                                                    <th>Gender</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="put_cv_upload_form">
                                            </tbody>
                                        </table>
                                        <button type="submit" class="btn btn-primary" id="cvSubmitbtn">Submit</button>
                                    </form>
                                </div>
                            </div>
                            <br><br>
                        </div>
                        <!-- show uploaded candidate cv -->
                        <div class="card" id="show_cv_uploaded_div" style="display:none;">
                            <br>
                            <input type="hidden" name="history_rfh_no" id="history_rfh_no">

                            <div class="row">
                                <div class="col-6 col-lg-5 col-md-5 mt-2">
                                    <p><strong>CV Uploaded Details</strong></p>
                                </div>
                                <div class="col-6 col-lg-4 col-md-4 mt-2">
                                    <p><strong>HEPL Recruitment Ref. No.: </strong><span
                                            id="show_cv_hepl_ref_no"></span></p>
                                </div>
                                <div class="col-6 col-lg-3 col-md-3 mt-2">
                                    <p><strong>Position Title: </strong><span id="show_cv_position_title"></span></p>
                                </div>

                            </div>
                            <table class="table " id="uploaded_tb">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <!-- <th>Candidate ID</th> -->
                                        <th>Candidate Name</th>
                                        <th>Current Status</th>
                                        <th>CV</th>
                                        <th>Follow Up</th>
                                        <th>Position Ageing</th>
                                        <th>Updated On</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="show_cv_uploaded">
                                </tbody>
                            </table>
                            <br>
                        </div>
                        <!-- for offer released status -->
                        <div class="card" id="show_offer_released_div" style="display:none;">
                            <form id="orSubmit" method="post" action="javascript:void(0)">
                                <div class="row">
                                    <input type="hidden" name="or_hepl_recruitment_ref_number"
                                        id="or_hepl_recruitment_ref_number">
                                    <input type="hidden" name="or_action_for_the_day" id="or_action_for_the_day">
                                    <input type="hidden" name="or_cdID" id="or_cdID">
                                    <input type="hidden" name="or_rfh_no" id="or_rfh_no">
                                    <div class="col-6 col-lg-4 col-md-6 mt-2">
                                        <p><strong>HEPL Recruitment Ref. No.: </strong><span id="or_hepl_ref_no"></span>
                                        </p>
                                    </div>
                                    <div class="col-6 col-lg-4 col-md-6 mt-2">
                                        <p><strong>Status: </strong><span id="or_action_status"></span></p>
                                    </div>
                                    <div class="col-6 col-lg-4 col-md-6 mt-2">
                                        <p><strong>Candidate Name: </strong><span id="or_candidate_name"></span></p>
                                    </div>
                                    <!-- <div class="col-6 col-lg-3 col-md-6 mt-2">
                                        <label for="">Closed Date*</label>
                                        <input type="date" name="or_closed_date" id="or_closed_date"
                                            class="form-control" value="@php echo  date('Y-m-d');@endphp" required>
                                    </div> -->
                                    <div class="col-6 col-lg-3 col-md-6 mt-2">
                                        <label for="">CTC (per month)*</label>
                                        <input type="text" name="or_closed_salary" id="or_closed_salary"
                                            class="form-control" placeholder="CTC (per month)" required>
                                        <label for="">Salary Remark</label>

                                        <input type="text" name="or_salary_review" id="or_salary_review"
                                            class="form-control" placeholder="Salary Remark">
                                    </div>
                                    <div class="col-6 col-lg-3 col-md-6 mt-2">
                                        <label for="">Joining Type*</label>
                                        <select name="or_joining_type" id="or_joining_type" class="form-control"
                                            required>
                                            <option value="">Select</option>
                                            <option value="Immediate Joining ">Immediate Joining </option>
                                            <option value="Later Date">Later Date</option>
                                        </select>
                                        <label for="">Date of Joining*</label>

                                        <input type="date" name="or_doj" id="or_doj" class="form-control" required>
                                    </div>
                                    <div class="col-6 col-lg-3 col-md-6 mt-2">
                                        <label for="">Remark</label>
                                        <textarea name="or_remark" id="or_remark" cols="20" rows="4"
                                            class="form-control" Placeholder="Remark"></textarea>
                                    </div>
                                    <div class="col-6 col-lg-3 col-md-6" style="margin-top:30px;">
                                        <button type="submit" class="btn btn-primary" id="orbtnSubmit">Submit</button>
                                    </div>
                                </div>
                            </form>
                            <br>
                        </div>
                        <!-- for offer released ldj edit -->
                        <div class="card" id="show_offer_released_ldj" style="display:none;">
                            <br>
                            <h4>Offer Released - History</h4>
                            <form id="or_ldj_Submit" method="post" action="javascript:void(0)">
                                <div class="row">
                                    <input type="hidden" name="or_ldj_hepl_recruitment_ref_number"
                                        id="or_ldj_hepl_recruitment_ref_number">
                                    <input type="hidden" name="or_ldj_cdID" id="or_ldj_cdID">
                                    <input type="hidden" name="or_ldj_rfh_no" id="or_ldj_rfh_no">
                                    <div class="col-6 col-lg-6 col-md-6 mt-2">
                                        <p><strong>HEPL Recruitment Ref. No.: </strong><span
                                                id="or_ldj_hepl_ref_no"></span></p>
                                    </div>
                                    <div class="col-6 col-lg-3 col-md-3 mt-2">
                                        <p><strong>Position Title: </strong><span id="or_ldj_position_title"></span></p>
                                    </div>
                                    <div class="col-6 col-lg-3 col-md-3 mt-2">
                                        <p><strong>Candidate Name: </strong><span id="or_ldj_candidate_name"></span></p>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table  mb-0" id="or_ldj_history">
                                            <thead>
                                                <tr>
                                                    <th data-label="S.No.">S.No.</th>
                                                    <th data-label="Resignation Received">Resignation Received</th>
                                                    <th data-label="Touch Base">Touch Base</th>
                                                    <th data-label="Initiate Backfil">Initiate Backfil</th>
                                                    <th data-label="Created On">Created On</th>
                                                </tr>
                                            </thead>
                                            <tbody id="or_ldj_history_body">
                                            </tbody>
                                        </table>
                                        <br>
                                    </div>
                                    <div class="col-6 col-lg-4 col-md-6 mt-2">
                                        <label for="">Proof of resignation & Acknowledgement of resignation received:
                                        </label>
                                        <input class="form-check-input" type="radio" name="orladj_resignation_received"
                                            value="Yes" id="flexRadioDefault1">
                                        <label class="form-check-label" for="flexRadioDefault1">Yes</label>
                                        <input class="form-check-input" type="radio" name="orladj_resignation_received"
                                            value="No" id="flexRadioDefault2" checked>
                                        <label class="form-check-label" for="flexRadioDefault2">No</label>
                                    </div>
                                    <div class="col-6 col-lg-3 col-md-6 mt-2">
                                        <label for="">Touch base with candidate:</label>
                                        <br>
                                        <select name="orladj_touchbase" id="orladj_touchbase" class="form-control">
                                            <option value="">Select</option>
                                            <option value="On-Course">On-Course</option>
                                            <option value="Red flag">Red flag</option>
                                        </select>
                                    </div>
                                    <div class="col-6 col-lg-2 col-md-6 mt-2">
                                        <label for="">Initiate backfill:</label>
                                        <br>
                                        <div class="checkbox">
                                            <input type="checkbox" id="checkbox1" class="form-check-input"
                                                name="initiate_backfil">
                                            <label for="checkbox1">Re-Open</label>
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-2 col-md-6 mt-2">
                                        <br>
                                        <button type="submit" class="btn btn-primary"
                                            id="orldjbtnSubmit">Submit</button>
                                    </div>
                                </div>
                            </form>
                            <br>
                        </div>

                        <!-- nop edit popup start-->
                        <button type="button" class="btn btn-outline-warning" id="show_dojedit_pop"
                            data-bs-backdrop="true" style="display:none" data-bs-toggle="modal"
                            data-bs-target="#edit_doj_modal_div">Edit NOP
                            Modal</button>

                        <div class="modal fade text-left" id="edit_doj_modal_div" tabindex="-1" role="dialog"
                            aria-labelledby="show_edit_noppop_title" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Edit Date of Joining</h4>
                                        <button type="button" id="close_edit_doj" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <label for="get_new_doj">Date of Joining</label>
                                        <input type="date" name="get_new_doj" id="get_new_doj" class="form-control">

                                        <input type="hidden" class="form-control" id="doj_candidate_id"
                                            name="doj_candidate_id" value="">

                                        <span id="doj_error" style="color:red;display:none;">* fields are
                                            required</span>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-secondary btn-sm"
                                            data-bs-dismiss="modal">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span class="d-sm-block d-none">Close</span>
                                        </button>
                                        <button type="button" id="btnEditdojUpdate" class="btn btn-primary ml-1 btn-sm"
                                            data-bs-dismiss="modal">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span class="d-sm-block d-none">Update</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- nop edit popup end-->



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
    <script src="../assets/pro_js/view_task_details.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        var page = "task_detail";

        if (page == "task_detail") {
            $(".task_detail_m").addClass("active");
        }

    });

    var get_assigned_recruitment_request_list_link = "{{url('get_assigned_recruitment_request_list')}}";
    var upload_cvprocess_link = "{{url('upload_cvprocess')}}";
    var show_uploaded_cv_link = "{{url('show_uploaded_cv')}}";
    var process_default_status_link = "{{url('process_default_status')}}";
    var process_offer_release_details_link = "{{url('process_offer_release_details')}}";
    var get_offer_released_tb_link = "{{url('get_offer_released_tb')}}";
    var offer_released_edit_process_link = "{{url('offer_released_edit_process')}}";
    var or_ldj_history_link = "{{url('or_ldj_history')}}";

    var or_ldj_onboard_link = "{{url('or_ldj_onboard_status')}}";
    var candidate_follow_up_history_link = "{{url('candidate_follow_up_history')}}";

    var get_offer_released_report_link = "{{url('get_offer_released_report')}}";

    // candidate onborded history
    var get_candidate_onborded_history_link = "{{url('get_candidate_onborded_history')}}";


    // old position
    var get_assigned_recruitment_request_oldlist_link = "{{url('get_assigned_recruitment_request_oldlist')}}";

    //  inactive tab
    var get_recruitment_inactive_link = "{{url('get_recruitment_inactive')}}";

    var closedate_update_link = "{{url('closedate_update')}}";

    var dateofjoining_update_link = "{{url('dateofjoining_update')}}";
    </script>
</body>

</html>