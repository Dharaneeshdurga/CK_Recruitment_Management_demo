<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offers - {{ $siteTitle }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Common CSS -->
    @include('layouts.commoncss')
    <!-- Common CSS -->
    <style>
    body {
        color: #000000;

    }

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
                            <h3>Offers</h3>
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
                                <h5 class="modal-title">Position - <span id="fh_position"></span></h5>

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

                <!-- finance remark model start -->
                <!-- Button trigger for large size modal -->
                <button type="button" id="view_fn_remark_btn" class="btn btn-outline-warning" style="display:none;"
                    data-bs-toggle="modal" data-bs-target="#view_fn_remark">
                    Finance Remark Modal
                </button>
                <!--large size Modal -->
                <div class="modal fade" id="view_fn_remark" data-bs-backdrop="true" tabindex="-1" role="dialog"
                    aria-labelledby="" aria-hidden="true">

                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="">Finance Remark</h5>

                                <button type="button" id="fnRemarkclosebtn" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="bi bi-x-octagon-fill"></i>
                                </button>
                            </div>
                            <div class="modal-body">

                                <div class="row">
                                    <p id="show_fn_remark"></p>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <!-- finance remark model end -->

                <!--business head remark model start -->
                <!-- Button trigger for large size modal -->
                <button type="button" id="view_ld_remark_btn" class="btn btn-outline-warning" style="display:none;"
                    data-bs-toggle="modal" data-bs-target="#view_ld_remark">
                    BH Remark Modal
                </button>
                <!--large size Modal -->
                <div class="modal fade" id="view_ld_remark" data-bs-backdrop="true" tabindex="-1" role="dialog"
                    aria-labelledby="" aria-hidden="true">

                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="">BH Remark</h5>

                                <button type="button" id="ldRemarkclosebtn" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="bi bi-x-octagon-fill"></i>
                                </button>
                            </div>
                            <div class="modal-body">

                                <div class="row">
                                    <p id="show_ld_remark"></p>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <!-- business head remark model end -->

                <!-- // Basic multiple Column Form section start -->
                <section id="multiple-column-form">
                    <div class="row match-height">
                        <div class="col-12">
                            <div class="card">

                                <div class="card-content">
                                    <div class="card-body">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">

                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link active" id="pending_qc-tab" data-bs-toggle="tab" href="#pending_qcs"
                                                role="tab" aria-controls="pending_qcs" aria-selected="false">Pending Non Po QC'S</a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link " id="approved_qc-tab" data-bs-toggle="tab" href="#approved_qcs"
                                                role="tab" aria-controls="approved_qcs" aria-selected="true">Approved Non Po QC'S</a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" id="approved_offers-tab" data-bs-toggle="tab" href="#approved_offers"
                                                role="tab" aria-controls="approved_offers" aria-selected="true">Approved Offers</a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link " id="offers_accepted-tab" data-bs-toggle="tab" href="#offers_accepted"
                                                role="tab" aria-controls="offers_accepted" aria-selected="true">Offers Accepted</a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link " id="offers_rejected-tab" data-bs-toggle="tab" href="#offers_rejected"
                                                role="tab" aria-controls="offers_rejected" aria-selected="true">Offers Rejected</a>
                                        </li>


                                    </ul>
                                    <div class="tab-content" id="myTabContent">

                                        <div class="tab-pane fade show active" id="pending_qcs" role="tabpanel"
                                        aria-labelledby="pending_qcs-tab">
                                        <br>
                                        <h5>No. of Records Found: <span id="total_nonpo"></span></h5>

                                            <div class="table-responsive">

                                                <table class="table " id="pending_nonpo_tb">
                                                    <thead>
                                                        <th>S.No</th>
                                                        <th>Candidate Name</th>
                                                        <th>RFH No</th>
                                                        <th>HEPL Recruitment Ref.No</th>
                                                        <th>Department</th>
                                                        <th>Designation</th>
                                                        <th>Band</th>
                                                        <th>Followup</th>
                                                        <th>Offer Letter</th>
                                                        <th>Document Status</th>
                                                        <th>Approver</th>
                                                        <th>PO Details</th>
                                                        {{-- <th>Finance Approvel</th> --}}
                                                        <th>BH Approvel</th>
                                                        <th>OAT Status</th>
                                                        <th>OAT Ageing</th>
                                                        <th>Revert</th>
                                                        <th>Action for Approval</th>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>

                                            </div>
                                    </div>
                                    <div class="tab-pane fade " id="approved_qcs" role="tabpanel"
                                    aria-labelledby="approved_qcs-tab">
                                    <br>
                                    <h5>No. of Records Found: <span id="app_non_po"></span></h5>

                                        <div class="table-responsive">

                                            <table class="table " id="app_npo_tb">
                                                <thead>
                                                    <th>S.No</th>
                                                                <th>Candidate Name</th>
                                                                <th>RFH No</th>
                                                                <th>HEPL Recruitment Ref.No</th>
                                                                <th>Department</th>
                                                                <th>Designation</th>
                                                                <th>Band</th>
                                                                <th>Followup</th>
                                                                <th>Offer Letter</th>
                                                                <th>Document Status</th>
                                                                <th>BH Status</th>
                                                                <th>PO Details</th>
                                                                <th>Approved By</th>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>

                                        </div>

                                </div>
                                        <div class="tab-pane fade " id="approved_offers" role="tabpanel"
                                            aria-labelledby="approved_offers-tab">
                                            <br>
                                            <h5>No. of Records Found: <span id="total_res_ao"></span></h5>

                                                <div class="table-responsive">

                                                    <table class="table " id="offers_ra_tb">
                                                        <thead>
                                                            <tr>
                                                            <th>S.No</th>
                                                            <th>Candidate Name</th>
                                                            <th>RFH No</th>
                                                            <th>HEPL Recruitment Ref.No</th>
                                                            <th>Department</th>
                                                            <th>Designation</th>
                                                            <th>Band</th>
                                                            <th>Followup</th>
                                                            <th>Offer Letter</th>
                                                            <th>Document Status</th>
                                                            <th>OAT Status</th>
                                                            <th>PO Type</th>
                                                            <th>Finance Status</th>
                                                            <th>BH Status</th>
                                                            <th>OAT Ageing</th>
                                                            <th>Offer Release Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>

                                                </div>

                                        </div>
                                        <div class="tab-pane fade" id="offers_accepted" role="tabpanel"
                                            aria-labelledby="rj_offers-tab">
                                            <br>
                                            <h5>No. of Records Found: <span id="total_res_ap_offers"></span></h5>

                                                <div class="table-responsive">

                                                    <table class="table " id="ap_offers_tb">
                                                        <thead>
                                                            <tr>
                                                            <th>S.No</th>
                                                            <th>Candidate Name</th>
                                                            <th>RFH No</th>
                                                    <th>HEPL Recruitment Ref.No</th>
                                                            <th>Department</th>
                                                            <th>Designation</th>
                                                            <th>Band</th>
                                                            <th>Followup</th>
                                                            <th>Offer Letter</th>
                                                            <th>Document Status</th>
                                                            <th>OAT Status</th>
                                                            <th>PO Type</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>

                                                </div>
                                        </div>
                                        <div class="tab-pane fade" id="offers_rejected" role="tabpanel"
                                            aria-labelledby="offers_rejected-tab">
                                            <br>
                                            <h5>No. of Records Found: <span id="total_res_re_offers"></span></h5>

                                                <div class="table-responsive">

                                                    <table class="table " id="re_offers_tb">
                                                    <thead>
                                                            <tr>
                                                            <th>S.No</th>
                                                            <th>Candidate Name</th>
                                                            <th>RFH No</th>
                                                    <th>HEPL Recruitment Ref.No</th>
                                                            <th>Department</th>
                                                            <th>Designation</th>
                                                            <th>Band</th>
                                                            <th>Followup</th>
                                                            <th>Offer Letter</th>
                                                            <th>Document Status</th>
                                                            <th>OAT Status</th>
                                                            <th>PO Type</th>
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
                    </div>
                </section>
                <!-- // Basic multiple Column Form section end -->
                   <!-- OAT AGEING MODEL model end -->
                   <div class="modal fade" id="view_oat_ageing" data-bs-backdrop="true" tabindex="-1" role="dialog"
                   aria-labelledby="" aria-hidden="true">

                   <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
                       <div class="modal-content">
                           <div class="modal-header">

                               <h5 class="modal-title" id="followupModalTitle">Followup Info - <span
                                   id="oat_candidate_name"></span></h5>
                           <h5 class="modal-title" style="color: #000000;">HEPL Ref.No - <span
                                   id="oat_hepl_ref_no"></span></h5>
                           <h5 class="modal-title" style="color: #000000;">Recruiter - <span
                                   id="oat_recruiter"></span></h5>
                           <h5 class="modal-title">Position - <span id="oat_position"></span></h5>
                               <button type="button" id="fnRemarkclosebtn" class="close" data-bs-dismiss="modal" aria-label="Close">
                                   <i class="bi bi-x-octagon-fill"></i>
                               </button>
                           </div>
                           <div class="modal-body">

                               <div class="table-responsive">
                                   <table class="table mb-0">
                                       <thead>
                                           <tr>
                                               <th>S.No.</th>
                                               <th>Description</th>
                                               <th>Created On</th>

                                           </tr>
                                       </thead>
                                       <tbody id="oatageing_body">

                                       </tbody>
                                   </table>
                               </div>

                           </div>

                       </div>
                   </div>
               </div>
            </div>
             <!-- OAT REMARK MODAL -->

               <!-- OAT REMARK MODAL -->
            <div class="modal fade" id="view_remark_oat" data-bs-backdrop="true" tabindex="-1" role="dialog"
            aria-labelledby="" aria-hidden="true">

            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="">Add Remark</h5>

                        <button type="button" id="Remarkclosebtn" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x-octagon-fill"></i>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <form  class="form" id="oatRemarkform" method="post" action="javascript:void(0)">
                                <input type="hidden" name="can_id" id="can_id">
                                <div class="form-group">
                                    <label for="Remark">Remark</label>
                                    <input type="text" name="oat_remark" id="Remark" class="form-control" required>
                                </div>
                                <hr>
                                <div class="form-group" style="text-align:center;">
                                    <button class="btn btn-primary btn-sm" type="submit" id="Remarkbtn">Submit</button>
                                </div>

                            </form>
                        </div>

                    </div>

                </div>
            </div>
        </div>
            </div>
            <!-- Footer -->
            @include('layouts.footer')
            <!-- /Footer -->
        </div>
    </div>
    <!-- Common JS -->
    @include('layouts.commonscript')
    <!-- Common JS -->
    <script src="../assets/pro_js/offers_bc.js"></script>


    <script type="text/javascript">
    $(document).ready(function() {
        var page = "offers_bc";

        if (page == "offers_bc") {
            $(".offers_bc_m").addClass("active");
        }

    });

    var candidate_follow_up_history_link = "{{url('candidate_follow_up_history_bc')}}";
    var get_offer_released_report_link = "{{url('get_offer_released_report_bc')}}";
    var get_offer_list_bc_apo_link = "{{url('get_offer_list_bc_apo')}}";
    var get_offer_accepted_for_bc_link = "{{url('get_offer_accepted_for_bc')}}";
    var get_offer_rejected_for_bc_link = "{{url('get_offer_rejected_for_bc')}}";
    var get_oat_ageing_link = "{{url('get_oat_ageing_bc')}}";
    var get_nonpo_pending_list_link ="{{url('get_nonpo_pending_list') }}";
    var send_po_bh_link = "{{url('send_po_buisness_head')}}";
    var send_to_leader_ol_link = "{{url('send_to_leader_ol_bc')}}";
    var get_approved_nonpo_link = "{{url('get_approved_nonpo')}}";
    var payroll_revert_update_link = "{{url('payroll_revert_update_bc')}}";
    </script>
</body>

</html>
