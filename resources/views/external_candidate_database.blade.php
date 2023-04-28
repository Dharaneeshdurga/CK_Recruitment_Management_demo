<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Profile - {{ $siteTitle }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Common CSS -->
    @include('layouts.commoncss')
    <!-- Common CSS -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css'>
    <link href="../assets/css/select2.css" rel="stylesheet">
    <style>
    .table {
        color: #000000;
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

        }

        #but_save {
            margin-top: 8px;
        }

        .info_tool {
            margin-top: 8px;

        }

        .badge {
            margin-bottom: 5px;
        }
    }

    .filter_tool {
        color: #000;
        box-shadow: 0 2px 6px 0 rgba(0, 0, 0, .3) !important;
        padding: .75rem 1rem !important;
        border-radius: .267rem !important;
        font-weight: 600;
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

    .modal{
        top: -10px !important;
    }
    .col-md-6{
        margin-bottom:8px;
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
                            <h3>Candidate Database</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item active" aria-current="page">Assign Ticket</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- followup model start -->

                <!-- Import edit popup start-->
                <button type="button" class="btn btn-outline-warning" id="show_nopedit_pop" data-bs-backdrop="true"
                    style="display:none" data-bs-toggle="modal" data-bs-target="#edit_noppop_modal_div">Edit NOP
                    Modal</button>

                <div class="modal fade text-left" id="edit_noppop_modal_div" tabindex="-1" role="dialog"
                    aria-labelledby="show_edit_noppop_title" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
                    <form method="post" enctype="multipart/form-data" action="{{ url('/import_excel/import') }}">
                        {{ csrf_field() }}
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Import</h4>
                                <button type="button" id="close_edit_noppop" class="close" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i data-feather="x"></i>
                                </button>
                            </div>

                            <div class="modal-body">
                                <h6 class="modal-title"><?php echo 'Sample Format' ?></h6>
                                <a href="../assets/sample_format/sample_import_file.xlsx" download>
                                <button type="button" class="btn btn-primary"><?php echo 'Download' ?></button></a><br><br>

                                <h6 class="modal-title"><?php echo 'Import' ?></h6>
                                <input type="file" id="select_file" name="select_file" required>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-secondary btn-sm" data-bs-dismiss="modal">
                                    <i class="bx bx-x d-block d-sm-none"></i>
                                    <span class="d-sm-block d-none">Close</span>
                                </button>
                                <button type="submit" class="btn btn-primary ml-1 btn-sm" id="btnSubmit"><?php echo 'Update' ?></button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
                <!-- Import edit popup end-->

                <!-- CV Upload edit popup start-->
                <button type="button" class="btn btn-outline-warning" id="show_cv_upload_edit_pop" data-bs-backdrop="true"
                    style="display:none" data-bs-toggle="modal" data-bs-target="#edit_cv_upload_pop_modal_div">Edit CV UPload Modal</button>

                <div class="modal fade text-left" id="edit_cv_upload_pop_modal_div" tabindex="-1" role="dialog"
                    aria-labelledby="show_edit_noppop_title" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
                    <form method="post" id="form_cv_upload" action="javascript:void(0)">
                        {{ csrf_field() }}
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">CV Upload</h4>
                                <button type="button" id="close_edit_noppop" class="close" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i data-feather="x"></i>
                                </button>
                            </div>

                            <div class="modal-body">
                                <label><?php echo 'CV Upload' ?></label><br>
                                <input type="file" id="file" name="file" required>
                                <input type="hidden" class="form-control" id="hiddenid" name="hiddenid">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-secondary btn-sm" data-bs-dismiss="modal">
                                    <i class="bx bx-x d-block d-sm-none"></i>
                                    <span class="d-sm-block d-none">Close</span>
                                </button>
                                <button type="submit" class="btn btn-primary ml-1 btn-sm" id="btnCVSubmit"><?php echo 'Update' ?></button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
                <!-- CV Upload edit popup end-->



                <section class="section">
                    <div class="card">
                        <div class="card-header">

                            @if($message = Session::get('success'))
                            <div class="alert alert-success alert-block" style="width: 31%;">
                                <button type="button" class="btn-close btn-close-white" aria-label="Close" style="font-size: 12px;"></button>
                                    <strong>{{ $message }}</strong>
                            </div>
                            @endif

                            <!-- Simple Datatable -->
                            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                            </div>

                            <div class="row">

                                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                    <br>
                                     <button class="btn btn-primary" onclick="show_advanced_filter();"><i
                                            class="bi bi-funnel-fill"></i> Advanced Filter</button>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                    <br>
                                    <button class="btn btn-primary" onclick="show_import_div();"><i class="fas fa-file-import"></i> Import</button>
                                </div>
                                {{-- <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 info_tool">
                                    <div class="row">
                                        <div class="col-lg-12   ">

                                            <span class="badge bg-info" title="CV"><i class="bi bi-eye"></i> CV</span>
                                             <span class="badge bg-secondary" title="Follow Up"><i
                                                    class="bi bi-stack"></i> Follow Up</span>
                                            <span class="badge bg-light-danger" title="Red Flag Status"><i
                                                    class="bi bi-flag-fill"></i> Red Flag Status</span>
                                            <span class="badge bg-light-warning" title="Offer Rejected"><i
                                                    class="bi bi-x-circle-fill"></i> Offer Rejected</span>

                                        </div>

                                         <div class="col-lg-6">
                                            <label for="">Position Status</label>
                                            <br>
                                            <span class="badge bg-dark" title="Open"><i class="fa fa-book-open"></i> Open</span>
                                            <span class="badge bg-danger" title="Closed"><i class="fa fa-book"></i> Closed</span>

                                        </div>
                                    </div>
                                </div> --}}


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
                                        <label for="Position Title">Position</label>
                                        <select name="af_position_title" id="af_position_title"
                                            class="form-control js-select2">

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="Function">Action</label>
                                        <br>
                                        <button class="btn btn-sm btn-warning" id="afClearbtn">Clear</button>
                                    </div>
                                </div>

                            </div>
                            <!-- filter result end -->


                        <div class="card-body">
                            <h5>No. of Records Found: <span id="total_res_show"></span></h5>

                            <table class="table" id="cand_profile" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile Number</th>
                                        <th>Current Location</th>
                                        <th>Department Type</th>
                                        <th>Skill Set</th>
                                        <th>Position Applying To</th>
                                        <th>Years of Experience</th>
                                        <th>Remarks</th>
                                        <th>CV Upload</th>
                                        <th>Created at</th>
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

    <script src="../assets/pro_js/view_external_candidate_list.js"></script>


    <script type="text/javascript">
    $(document).ready(function() {

        $(".js-select2").select2();

        var page = "external_candidate_database";

        if (page == "external_candidate_database") {
            $(".external_candidate_database_m").addClass("active");
        }

    });

    var get_external_candidate_database_link = "{{url('get_external_candidate_database')}}";
    var get_position_apply_title_link = "{{url('get_position_apply_title_af')}}";
    var save_CV_form_link = "{{url('get_save_CV_form')}}";

    //notification hideup
    $('div.alert').delay(2000).slideUp(300);

    </script>


</body>

</html>
