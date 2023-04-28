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

    body,
    .table {
        color: #000000;
    }

    /* .dropdown-item.active,
    .dropdown-item:active {
        background-image:unset !important;
        color: #fff !important;
        background-color: #435ebe;
        background-image: -webkit-linear-gradient(top, #01549b 0%, #025aa5 100%);
    } */

    .dt-button-collection,.dropdown-item{
        border: 1px solid #435ebe;
        color: #435ebe;
        background-color: #ffffff;
    }
    .dt-button-collection,
    .dropdown-menu {
        height: 300px !important;
        max-height: 300px !important;
        overflow-y: scroll !important;
    }

    .choices {
        margin-bottom: 6px !important;
    }
    div.dt-button-collection{
        width: 170px !important;
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
    .choices__list--dropdown .choices__item--selectable{
        padding-right: unset !important;
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
                                    <li class="breadcrumb-item"><a href="view_recruit_request_default">View Allocation
                                            List</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Assign Allocation</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            <!-- Simple Datatable -->

                            <div class="row">

                                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                    <div class="col-lg-6">
                                        <label for="">Add Allocation</label>
                                        <br>
                                        <a href="http://hub1.cavinkare.in/CK_RFH/"><button class="btn btn-primary"
                                                title="Add Allocation"><i class="bi bi-plus-circle"></i> Add
                                                Allocation</button></a>

                                    </div>
                                    <div class="col-lg-6">
                                        <!-- <p style="margin-top:5px;">Last HEPL Recruitment Reference No - <span id="last_hepl_ref_no"></span></p> -->

                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 info_tool">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="">Assigned Status</label>
                                            <br>
                                            <span class="badge bg-danger" title="Pending"><i
                                                    class="bi bi-shield-slash"></i> Pending</span>
                                            <span class="badge bg-secondary" title="Assigned"><i
                                                    class="bi bi-shield-check"></i> Assigned</span>

                                        </div>

                                        <div class="col-lg-6">
                                            <label for="">Position Status</label>
                                            <br>
                                            <span class="badge bg-warning" title="Open"><i class="fa fa-book-open"></i>
                                                Open</span>
                                            <span class="badge bg-success" title="Closed"><i class="fa fa-book"></i>
                                                Closed</span>

                                        </div>
                                    </div>
                                </div>



                            </div>

                        </div>

                        <!-- Button trigger for info theme modal -->
                        <button type="button" id="btn_unassign_yal" style="display:none;" class="btn btn-outline-info"
                            data-bs-toggle="modal" data-bs-target="#info">
                            Info
                        </button>
                        <!--info theme Modal -->
                        <div class="modal fade text-left" data-bs-backdrop="true" id="info" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel130" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-info">
                                        <h5 class="modal-title white" id="myModalLabel130">
                                            Info
                                        </h5>
                                        <button type="button" id="confirmClose" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i class="bi bi-x-octagon-fill"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure..! You want to Unassign?</p>
                                        <input type="hidden" name="heplrr_unassign_yal" id="heplrr_unassign_yal">
                                        <input type="hidden" name="recReqID_unassign_yal" id="recReqID_unassign_yal">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-secondary" id="close_pop"
                                            data-bs-dismiss="modal">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Close</span>
                                        </button>
                                        <button type="button" id="btnConfirm_unassign_yal" class="btn btn-info ml-1"
                                            data-bs-dismiss="modal">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Accept</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- confirmation popup end -->
                        
                        <!--delete hepl row  -->
                        <button type="button" id="show_pop_hepl" style="display:none;" class="btn btn-outline-info"
                            data-bs-toggle="modal" data-bs-target="#delete_pop">
                            Info
                        </button>
                        <!--delete hepl row -->
                        <div class="modal fade text-left" data-bs-backdrop="true" id="delete_pop" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel13" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-info">
                                        <h5 class="modal-title white" id="myModalLabel13">
                                            Info
                                        </h5>
                                        <button type="button" id="confirmClose" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i class="bi bi-x-octagon-fill"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure..! You want to Delete?</p>
                                        <input type="hidden" name="heplrr_del" id="heplrr_del">
                                        <input type="hidden" name="recReqID_del" id="recReqID_del">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-secondary" id="close_del_pop"
                                            data-bs-dismiss="modal">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Close</span>
                                        </button>
                                        <button type="button" id="btnConfirm_delete_hepl" class="btn btn-info ml-1"
                                            data-bs-dismiss="modal">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Accept</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- confirmation popup end -->


                        <div class="card-body">
                            <table class="table" id="table1" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>S.No.</th>
                                        <th>HEPL Recruitment Ref Number</th>
                                        <th>Position Title</th>
                                        <th>Sub Position Title</th>
                                        <th>No of Position</th>
                                        <th>Position Ageing</th>
                                        <th>Open Date</th>
                                        <th>Location</th>
                                        <th>Assigned Status</th>
                                        <th>Action</th>
                                        <th>Assigned Status</th>
                                        <th>Position Status</th>
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
    <script src="../assets/pro_js/view_recruit_request.js"></script>


    <script type="text/javascript">
    $(document).ready(function() {
        var page = "view_recruit_request";

        if (page == "view_recruit_request") {
            $(".recruit_req_m").addClass("active");
        }

        $('.choices__list .choices__item').data('select-text', '');

    });

    var get_recruitment_request_list_link = "{{url('get_recruitment_request_list')}}";
    var process_assign_link = "{{url('process_recruitment_assign')}}";
    var process_assigned_assign_link = "{{url('process_recruitment_assigned_assign')}}";
    var get_last_reference_no_link = "{{url('get_last_hepl_reference_no')}}";
    var process_unassign_link = "{{url('process_unassign')}}";
    var update_sub_position_title_link = "{{url('update_sub_position_title')}}";
    var process_hepldelete_link = "{{url('process_hepldelete')}}";
    
    </script>

    <script>
    // Simple Datatable
    // let table1 = document.querySelector('#table1');
    // let dataTable = new simpleDatatables.DataTable(table1);

    
    </script>

</body>

</html>