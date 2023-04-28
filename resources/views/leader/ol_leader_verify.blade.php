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
    .table_btn:disabled {
        cursor: not-allowed;
        pointer-events: all !important;
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

                 <!-- action model start -->
                <!-- Button trigger for large size modal -->
                <button type="button" id="view_action_popbtn" class="btn btn-outline-warning" style="display:none;"
                    data-bs-toggle="modal" data-bs-target="#view_action_pop">
                    PO Modal
                </button>
                <!--large size Modal -->
                <div class="modal fade" id="view_action_pop" data-bs-backdrop="true" tabindex="-1" role="dialog"
                    aria-labelledby="poTitle" aria-hidden="true">

                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="poTitle">Approvel Process</h5>

                                <button type="button" id="Popclosebtn" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="bi bi-x-octagon-fill"></i>
                                </button>
                            </div>
                            <div class="modal-body">

                                <div class="row">
                                    <form class="form" id="bhactionForm" method="post" action="javascript:void(0)">
                                        <input type="hidden" name="rfh_no" id="rfh_no">
                                        <input type="hidden" name="cdID" id="cdID">
                                        <input type="hidden" name="po_file_status" id="po_file_status">
                                        <input type="hidden" name="po_type" id="po_type">
                                        <div class="row">
                                                <label for="">Status: </label>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ld_status" id="flexRadioDefault1" checked="" value="2">
                                                    <label class="form-check-label" for="flexRadioDefault1">
                                                        Approve
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ld_status" id="flexRadioDefault2" value="3">
                                                    <label class="form-check-label" for="flexRadioDefault2">
                                                        Reject
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="form-group" id="ld_rejecttype_div" style="display:none;">
                                                <label for="">Reject Type</label>
                                                <select name="ld_reject_type" id="ld_reject_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="offer_letter_reject">Reject Offer Letter</option>
                                                    <option value="po_reject">Reject PO</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group" id="ld_remark_div" style="display:none;">
                                                <label for="Remark">Remark: </label>
                                                <textarea name="ld_remark" id="ld_remark" class="form-control"></textarea>
                                            </div>

                                        </div>
                                        <hr>
                                        <div style="text-align:center;">
                                            <button class="btn btn-sm btn-primary" type="submit" id="bhSubmitbtn">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- action model end -->


                <!-- // Basic multiple Column Form section start -->
                <section id="multiple-column-form">
                    <div class="row match-height">
                        <div class="col-12">
                            <div class="card">

                                <div class="card-content">
                                    <div class="card-body">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link active" id="pd_offers-tab" data-bs-toggle="tab"
                                                    href="#pd_offers" role="tab" aria-controls="pd_offers"
                                                    aria-selected="true">Pending Offers</a>
                                            </li>

                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" id="approved_offers-tab" data-bs-toggle="tab"
                                                    href="#approved_offers" role="tab" aria-controls="approved_offers"
                                                    aria-selected="false">Approved Offers</a>
                                            </li>

                                        </ul>
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="pd_offers" role="tabpanel"
                                                aria-labelledby="pd_offers-tab">
                                                <br>
                                                <h5>No. of Records Found: <span id="total_res_show_tab"></span></h5>

                                                <div class="table-responsive">

                                                    <table class="table " id="offers_ld_tb">
                                                        <thead>
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>Candidate Name</th>
                                                                <th>RFH No</th>
                                                                <th>HEPL Recruitment Ref.No</th>
                                                                <th>Department</th>
                                                                <th>Designation</th>
                                                                <th>Band</th>
                                                                <th>Follow up</th>
                                                                <th>Offer Letter</th>
                                                                <th>Document Status</th>
                                                                <th>OAT Status</th>
                                                                <th>Finance Status</th>
                                                                <th>PO Details</th>
                                                                <th>OAT Ageing</th>
                                                                <th>Action for Approval</th>
                                                                <th>Candidate Details</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>

                                                </div>

                                            </div>
                                            <div class="tab-pane fade" id="approved_offers" role="tabpanel"
                                                aria-labelledby="approved_offers-tab">
                                                <br>
                                                <h5>No. of Records Found: <span id="total_res_ao"></span></h5>

                                                <div class="table-responsive">

                                                    <table class="table " id="offers_lda_tb">
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
                                                                <th>Finance Status</th>
                                                                <th>PO Details</th>
                                                                <th>Approved By</th>
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
            <!-- Footer -->
            @include('layouts.footer')
            <!-- /Footer -->
        </div>
    </div>
    <!-- Common JS -->
    @include('layouts.commonscript')
    <!-- Common JS -->
    <script src="../assets/pro_js/leader/ol_leader_verify.js"></script>


    <script type="text/javascript">
    $(document).ready(function() {
        var page = "ol_leader_verify";

        if (page == "ol_leader_verify") {
            $(".ol_leader_verify_m").addClass("active");
        }

    });

    var get_offer_list_ld_link = "{{url('get_offer_list_ld')}}";
    var candidate_follow_up_history_link = "{{url('candidate_follow_up_history_bc')}}";
    var get_offer_released_report_link = "{{url('get_offer_released_report_bc')}}";
    var process_ld_approval_link = "{{url('process_ld_approval')}}";
    var get_cor_ld_ao_link = "{{url('get_cor_ld_ao')}}";
    var get_po_details_link = "{{url('get_po_details_ld')}}";
    var send_po_finance_l = "{{url('send_po_finance_l')}}";
    var get_oat_ageing_link = "{{url('get_oat_ageing_bh')}}";
    var get_candidate_for_budgie_link = "{{url('get_candidate_for_budgie_ld')}}";

    </script>
</body>

</html>
