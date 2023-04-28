<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Candidate Profile - {{ $siteTitle }}</title>
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

    .table {
        color: #000000;
    }

    .filter_tool {
        color: #000;
        box-shadow: 0 2px 6px 0 rgba(0, 0, 0, .3) !important;
        padding: .75rem 1rem !important;
        border-radius: .267rem !important;
        font-weight: 600;
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
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
    .dataTables_length {
        margin-bottom: 10px;
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
                            <h3> Candidate Profile</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="recruiter_report">Recruiter Report</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Candidate Profile</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
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
                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 info_tool">
                                    <div class="row">
                                        <div class="col-lg-12">

                                            <span class="badge bg-info" title="CV"><i class="bi bi-eye"></i> CV</span>
                                            <span class="badge bg-secondary" title="Follow Up"><i
                                                    class="bi bi-stack"></i> Follow Up</span>
                                            <span class="badge bg-light-danger" title="Red Flag Status"><i
                                                    class="bi bi-flag-fill"></i> Red Flag Status</span>
                                            <span class="badge bg-light-warning" title="Offer Rejected"><i
                                                    class="bi bi-x-circle-fill"></i> Offer Rejected</span>

                                        </div>

                                        <!-- <div class="col-lg-6">
                                            <label for="">Position Status</label>
                                            <br>
                                            <span class="badge bg-dark" title="Open"><i class="fa fa-book-open"></i> Open</span>
                                            <span class="badge bg-danger" title="Closed"><i class="fa fa-book"></i> Closed</span>

                                        </div> -->
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
                                        <label for="Position Status">Position Status</label>
                                        <select name="af_position_status" id="af_position_status" class="form-control js-select2">
                                            <option value="">Select</option>
                                            <option value="Profile submitted to Hiring Manager">Profile submitted to Hiring Manager</option>
                                            <option value="Profile Rejected">Profile Rejected</option>
                                            <option value="Profile on Hold">Profile on Hold</option>
                                            <option value="Sourcing Profiles again after profile rejection">Sourcing Profiles again after profile rejection</option>
                                            <option value="Interview scheduled with Hiring Manager">Interview scheduled with Hiring Manager</option>
                                            <option value="Profile shortlisted by Hiring Manager">Profile shortlisted by Hiring Manager</option>
                                            <option value="CTC Negotiation">CTC Negotiation</option>
                                            <option value="Document Collection">Document Collection</option>
                                            <option value="Offer Released">Offer Released</option>
                                            <option value="Offer Accepted">Offer Accepted</option>
                                            <option value="Offer Rejected">Offer Rejected</option>
                                            <option value="Candidate Onboarded"> Candidate Onboarded</option>
                                            <option value="Candidate No Show">Candidate No Show</option>
                                            <option value="Candidate Abscond">Candidate Abscond</option>>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="Action">Action</label>
                                        <br>
                                        <button class="btn btn-sm btn-warning" id="afClearbtn">Clear</button>
                                    </div>
                                </div>

                            </div>
                            <!-- filter result end -->
                        </div>
                        <div class="card-body">
                        <h5>No. of Records Found: <span id="total_res_show"></span></h5>

                            <table class="table" id="c_profile" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Candidate Name</th>
                                        <th>Position</th>
                                        <th>Sub Position Title</th>
                                        <th>Candidate Source</th>
                                        <th>Gender</th>
                                        <th>CV</th>
                                        <th>Follow Up</th>
                                        <th>Position Status</th>
                                        <th>Recruiter</th>
                                        <th>Created On</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
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

    <script src="../assets/pro_js/rr_profiles.js"></script>


    <script type="text/javascript">
    $(document).ready(function() {
        $(".js-select2").select2();

        var page = "recruiter_report";

        if (page == "recruiter_report") {
            $(".recruiter_report_m").addClass("active");
        }



    });

    var get_candidate_profile_link = "{{url('get_recruiter_cp')}}";
    var candidate_follow_up_history_link = "{{url('candidate_follow_up_history_bc')}}";
    var get_offer_released_report_link = "{{url('get_offer_released_report_bc')}}";
    </script>

    <script>
    </script>

</body>

</html>
