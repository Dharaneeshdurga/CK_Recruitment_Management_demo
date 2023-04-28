<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offers - {{ $siteTitle }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Common CSS -->
    @include('layouts.commoncss')
    <link href="../assets/css/bootstrap-datepicker.css" rel="stylesheet"/>

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
        pointer-events: all !important;
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

                <!-- po view model start -->
                <!-- Button trigger for large size modal -->
                <button type="button" id="view_po_popbtn" class="btn btn-outline-warning" style="display:none;"
                    data-bs-toggle="modal" data-bs-target="#view_po_pop">
                    PO Modal
                </button>
                <!--large size Modal -->
                <div class="modal fade" id="view_po_pop" data-bs-backdrop="true" tabindex="-1" role="dialog"
                    aria-labelledby="poTitle" aria-hidden="true">

                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="poTitle">PO Process</h5>

                                <button type="button" id="poPopclosebtn" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="bi bi-x-octagon-fill"></i>
                                </button>
                            </div>
                            <div class="modal-body">

                                <div class="row">
                                    <form class="form" id="fnpoForm" method="post" action="javascript:void(0)">
                                    <input type="hidden" name="po_rfh_no" id="po_rfh_no">
                                <input type="hidden" name="po_cdID" id="po_cdID">
                                <input type="hidden" name="approver" id="approver">
                                <input type="hidden" name="fn_status" value ="2" id="fn_status">
                                        {{-- <div class="row">
                                                <label for="">Status: </label>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="fn_status" id="flexRadioDefault1" checked="" value="2">
                                                    <label class="form-check-label" for="flexRadioDefault1">
                                                        Approve
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="fn_status" id="flexRadioDefault2" value="3">
                                                    <label class="form-check-label" for="flexRadioDefault2">
                                                        Reject
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <br> --}}
                                        <div class="row">
                                            <div class="form-group" id="fn_remark_div" style="display:none;">
                                                <label for="Remark">Remark: </label>
                                                <textarea name="fn_remark" id="fn_remark" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="Remark">Client Po Attach: </label>
                                                <input type="file" name="client_attach" id="client_attach" class="form-control">
                                            </div>
                                        </div>
                                        <hr>
                                        <div style="text-align:center;">
                                            <button class="btn btn-sm btn-primary" type="submit" id="fnSubmitbtn">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- po model end -->

                <button type="button" id="clientPoFormbtn" class="btn btn-outline-warning" style="display:none;"
                data-bs-toggle="modal" data-bs-target="#add_client_no_pop">
                Add Client PO Modal
            </button>
            <!--large size Modal -->
            <div class="modal fade" id="add_client_no_pop" data-bs-backdrop="true" tabindex="-1" role="dialog"
                aria-labelledby="" aria-hidden="true">

                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="">Add Client PO</h5>

                            <button type="button" id="clientPoclosebtn" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <i class="bi bi-x-octagon-fill"></i>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="row">
                                <form  class="form" id="clientPoForm" method="post" action="javascript:void(0)">
                                    <input type="hidden" name="cpo_cdID" id="cpo_cdID">
                                    <input type="hidden" name="cpo_rfh_no" id="cpo_rfh_no">
                                    <input type="hidden" name="leader_status" id="leader_status">
                                    <input type="hidden" name="finance_status" id="finance_status">
                                    <input type="hidden" name="update_date" id="update_date1" value="<?php echo time();?>">
                                    <div class="form-group">
                                        <label for="">Client PO</label>
                                        <input type="file" name="client_po" id="client_po" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Client PO Number</label>
                                        <input type="text" name="client_po_no" id="client_po_no" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Client PO Value</label>
                                        <input type="text" name="client_po_value" id="client_po_value" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Client PO Vality</label>
                                        <input type="text" name="client_po_vality" id="client_po_vality" class="form-control" required>
                                    </div>
                                    <hr>
                                    <div class="form-group" style="text-align:center;">
                                        <button class="btn btn-primary btn-sm" type="submit" id="uploadClientpobtn">Submit</button>
                                    </div>

                                </form>
                            </div>

                        </div>

                    </div>
                </div>
            </div>


<!--view pop-->
<button type="button" id="view_client_po_popbtn" class="btn btn-outline-warning" style="display:none;"
data-bs-toggle="modal" data-bs-target="#view_client_po_pop">
View Client PO Modal
</button>
<div class="modal fade" id="view_client_po_pop" data-bs-backdrop="true" tabindex="-1" role="dialog"
aria-labelledby="" aria-hidden="true">

<div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="">View Client PO</h5>

            <button type="button" id="clientPoclosebtnv" class="close" data-bs-dismiss="modal" aria-label="Close">
                <i class="bi bi-x-octagon-fill"></i>
            </button>
        </div>
        <div class="modal-body">

            <div class="row">
                <form  class="form" id="" method="post" action="javascript:void(0)">

                    <div class="form-group">
                        <label for="">Client PO: <span id="v_po_att"></span></label>
                        {{-- <a class="dropdown-item" href="../po_attachments/'.$row->cdID.'/'.$row->client_po_attach.'" ><i class="bi bi-upload"></i> Preview Client PO</a> --}}
                    </div>
                   {{-- <span class="text-center"> (OR)</span> --}}
                    <div class="form-group">
                        <label for="">Client PO Number: <span id="v_po_no"></span></label>
                    </div>
                    <div class="form-group">
                        <label for="">Client PO Value: <span id="v_po_value"></span></label>
                    </div>
                    <div class="form-group">
                        <label for="">Client PO Vality: <span id="v_po_val"></span></label>
                    </div>
                    <hr>
                    <div class="form-group" style="text-align:center;">
                        {{-- <button class="btn btn-primary btn-sm" type="submit" id="uploadClientpobtn">Submit</button> --}}
                    </div>

                </form>
            </div>

        </div>

    </div>
</div>
</div>





                <!-- // Basic multiple Column Form section start -->
                <section id="multiple-column-form">
                    <div class="row match-height">
                        <div class="col-12">
                            <div class="card">

                                <div class="card-content">
                                    <div class="card-body">

                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link active" id="po_offers-tab" data-bs-toggle="tab"
                                                    href="#po_offers" role="tab" aria-controls="po_offers"
                                                    aria-selected="true">Pending PO</a>
                                            </li>

                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" id="approved_po-tab" data-bs-toggle="tab"
                                                    href="#approved_po" role="tab" aria-controls="approved_po"
                                                    aria-selected="false">Approved PO</a>
                                            </li>


                                        </ul>
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="po_offers" role="tabpanel"
                                                aria-labelledby="po_offers-tab">
                                                <br>
                                                <h5>No. of Records Found: <span id="total_res_show_tab_po"></span></h5>

                                                <div class="table-responsive">

                                                    <table class="table " id="po_tb">
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
                                                                <th>PO Details</th>
                                                                <th>OAT Ageing</th>
                                                                <th>Action for PO</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>

                                                </div>

                                            </div>

                                            <div class="tab-pane fade" id="approved_po" role="tabpanel"
                                                aria-labelledby="approved_po-tab">
                                                <br>
                                                <h5>No. of Records Found: <span id="total_res_show_tab_apo"></span></h5>

                                                <div class="table-responsive">

                                                    <table class="table " id="apo_tb">
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
                                                                <th>BH Status</th>
                                                                <th>Finance Status</th>
                                                                <th>PO Details</th>
                                                                {{-- <th>BH</th> --}}
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
                <!-- // Basic multiple Column Form section end -->
            </div>
            <!-- Footer -->
            @include('layouts.footer')
            <!-- /Footer -->
        </div>
    </div>
    <!-- Common JS -->
    @include('layouts.commonscript')
    <!-- Common JS -->
    <script src="../assets/pro_js/po_team/po_team.js"></script>
<!-- date formate js  -->
<script src="../assets/js/moment.js"></script>
<!-- monthly -->
<script src="../assets/js/bootstrap-datepicker.js"></script>

    <script type="text/javascript">
    $(document).ready(function() {
        $("#client_po_vality").datepicker( {
		    format: "mm-yyyy",
		    startView: "months",
		    minViewMode: "months"
		});


        var page = "offers_poteam";

        if (page == "offers_poteam") {
            $(".offers_poteam_m").addClass("active");
        }

    });


    var candidate_follow_up_history_link = "{{url('candidate_follow_up_history_bc')}}";
    var get_offer_released_report_link = "{{url('get_offer_released_report_bc')}}";

    var get_internal_po_request_link = "{{url('get_internal_po_request')}}";
    var attach_cl_po_link = "{{url('upload_clientpo_po')}}";
    var process_fn_approval_link = "{{url('process_fn_postatus')}}";

    var get_approved_po_fin_link = "{{url('get_approved_po_po')}}";

    var get_oat_ageing_link = "{{url('get_oat_ageing_fc')}}";

    </script>
</body>

</html>
