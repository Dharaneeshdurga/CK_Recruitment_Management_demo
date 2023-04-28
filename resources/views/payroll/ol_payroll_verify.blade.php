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

    .table_btn:disabled {
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

<!--- choose cleint type model -->
                {{-- <button type="button" id="add_client_type" class="btn btn-outline-warning" style="display:none;"
                data-bs-toggle="modal" data-bs-target="#add_client_type">
               Client Type
            </button> --}}
            <!--large size Modal -->
            <div  class="modal fade" id="add_client_type" data-bs-backdrop="true" tabindex="-1" role="dialog"
                aria-labelledby="" aria-hidden="true">

                <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="">Client Type</h5>

                            <button type="button" id="clienttclosebtn" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <i class="bi bi-x-octagon-fill"></i>
                            </button>
                        </div>
                        <div class="modal-body">

                            {{-- <div class="row"> --}}
                                <form  class="form" id="client_typeForm" method="post" action="javascript:void(0)">

                   <div class="row">
                        <div class="col-md-6">
                            <input type="hidden" name="cl_cdID" id="cl_cdID">
                            <input type="hidden" name="cl_rfh_no" id="cl_rfh_no">
                            <input type="hidden" name="po_file" id="po_file">
                            <input type="hidden" name="finance_file" id="finance_file">
                                    <div class="form-group">
                                        <label for="">Client Type</label>
                                        <select name="client_type" id="client_type" class="form-control" required>
                                            <option>Select</option>
                                            <option>External Client</option>
                                            <option>Internal Po</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="">To mail</label>
                                        <textarea class="form-control" name="ex_to_mail" id="ex_to_mail" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="">CC mail</label>
                                       <textarea class="form-control" name="ex_cc_mail" id="ex_cc_mail" placeholder="eg): xxx@hepl.com,yyy@hepl.com"></textarea>
                                    </div>

                                    </div>
                                {{-- </div>
                                <div class="row"> --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Subject</label>
                                           <input type="text"  class="form-control" name="subject" id="subject" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Message</label>
                                            <textarea class="form-control" name="message" id="message" required></textarea>

                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="">File upload</label>
                                           <input type="file" class="form-control" name="att_files[]" id="att_files" multiple>

                                        </div>
                                        <div class="form-group">
                                            <label for="">Preview Files</label>
                                            <div id="preview_files"></div>
                                            <div id="ex_files"></div>
                                        </div>

                                        </div>
                                    </div>
                                {{-- </div> --}}
                                    <hr>
                                    <div class="form-group" style="text-align:center;">
                                        <button class="btn btn-primary" type="submit" id="client_type_btn">Submit</button>
                                        <button class="btn bg-dark"style="color:#fff !important"type="button" id="save_as_btn">Save as Draft</button>

                                    </div>

                                </form>
                            </div>

                        </div>

                    </div>
                </div>
            </div>










                <!--add client po model start -->
                <!-- Button trigger for large size modal -->
                <button type="button" id="add_client_po_popbtn" class="btn btn-outline-warning" style="display:none;"
                    data-bs-toggle="modal" data-bs-target="#add_client_po_pop">
                    Add Client PO Modal
                </button>
                <!--large size Modal -->
                <div class="modal fade" id="add_client_po_pop" data-bs-backdrop="true" tabindex="-1" role="dialog"
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
                                        <input type="hidden" name="update_date" id="update_date" value="<?php echo time();?>">
                                        <div class="form-group">
                                            <label for="">Client PO</label>
                                            <input type="file" name="client_po" id="client_po" class="form-control" required>
                                        </div>
                                       {{-- <span class="text-center"> (OR)</span> --}}
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
<!-- view po details--->
<button type="button" id="view_client_po_popbtn" class="btn btn-outline-warning" style="display:none;"
data-bs-toggle="modal" data-bs-target="#view_client_po_pop">
View Client PO Modal
</button>
<!--large size Modal -->
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
<!-- without po number-->

                <button type="button" id="add_client_no_popbtn" class="btn btn-outline-warning" style="display:none;"
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

                            <button type="button" id="clientPoclosebtn1" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <i class="bi bi-x-octagon-fill"></i>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="row">
                                <form  class="form" id="clientPoForm1" method="post" action="javascript:void(0)">
                                    <input type="hidden" name="cpo_cdID" id="cpo_cdID1">
                                    <input type="hidden" name="cpo_rfh_no" id="cpo_rfh_no1">
                                    <input type="hidden" name="leader_status" id="leader_status1">
                                    <input type="hidden" name="finance_status" id="finance_status1">
                                    <input type="hidden" name="update_date" id="update_date1" value="<?php echo time();?>">
                                    <div class="form-group">
                                        <label for="">Client PO</label>
                                        <input type="file" name="client_po" id="client_po1" class="form-control">
                                    </div>

                                    <hr>
                                    <div class="form-group" style="text-align:center;">
                                        <button class="btn btn-primary btn-sm" type="submit" id="uploadClientpobtn1">Submit</button>
                                    </div>

                                </form>
                            </div>

                        </div>

                    </div>
                </div>
            </div>







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
                                                    aria-selected="true">Pending Offers</a>
                                            </li>

                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" id="approved_offers-tab" data-bs-toggle="tab"
                                                    href="#approved_offers" role="tab" aria-controls="approved_offers"
                                                    aria-selected="false">Approved Offers</a>
                                            </li>

                                            <!-- <li class="nav-item" role="presentation">
                                                <a class="nav-link" id="approved_po-tab" data-bs-toggle="tab"
                                                    href="#approved_po" role="tab" aria-controls="approved_po"
                                                    aria-selected="false">Approved PO</a>
                                            </li> -->


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
                                                                <th>Approver</th>
                                                                <th>PO Details</th>
                                                                <th>Finance Approvel</th>
                                                                <th>BH Approvel</th>
                                                                <th>OAT Status</th>
                                                                <th>OAT Ageing</th>
                                                                <th>Revert</th>
                                                                <th>Action for Approval</th>
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
                                                <h5>No. of Records Found: <span id="total_res_show_tab_ao"></span></h5>

                                                <div class="table-responsive">

                                                    <table class="table " id="ao_tb">
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
                                                                <!-- <th>OAT Status</th> -->
                                                                <th>Finance Status</th>
                                                                <th>BH Status</th>
                                                                <th>PO Details</th>
                                                                <th>Approved By</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="approved_po" role="tabpanel"
                                                aria-labelledby="approved_po-tab">
                                                <p class="mt-2">Duis ultrices purus non eros fermentum hendrerit. Aenean
                                                    ornare interdum
                                                    viverra. Sed ut odio velit. Aenean eu diam
                                                    dictum nibh rhoncus mattis quis ac risus. Vivamus eu congue ipsum.
                                                    Maecenas id
                                                    sollicitudin ex. Cras in ex vestibulum,
                                                    posuere orci at, sollicitudin purus. Morbi mollis elementum enim, in
                                                    cursus sem
                                                    placerat ut.</p>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </section>
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
    <script src="../assets/pro_js/payroll/ol_payroll_verify.js"></script>
    <script src="../assets/sweet-alert/sweet-alert.min.js"></script>
    <script src="../assets/sweet-alert/sweet-alert.init.js"></script>
<!-- date formate js  -->
<script src="../assets/js/moment.js"></script>
<!-- monthly -->
<script src="../assets/js/bootstrap-datepicker.js"></script>
{{-- <script src="../choices.js/choices.min.js"></script> --}}
    <script type="text/javascript">
    $(document).ready(function() {
        $("#client_po_vality").datepicker( {
		    format: "mm-yyyy",
		    startView: "months",
		    minViewMode: "months"
		});

        var page = "ol_payroll_verify";

        if (page == "ol_payroll_verify") {
            $(".ol_payroll_verify_m").addClass("active");
        }
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });

    var get_cor_oat_po_link = "{{url('get_cor_oat_po')}}";
    var get_cor_oat_ao_link = "{{url('get_cor_oat_ao')}}";

    var send_to_leader_ol_link = "{{url('send_to_leader_ol')}}";

    var candidate_follow_up_history_link = "{{url('candidate_follow_up_history_oat')}}";
    var get_offer_released_report_link = "{{url('get_offer_released_report_oat')}}";

    var send_po_finance_link = "{{url('submit_po_process_bh')}}";

    var send_po_bh_link = "{{url('send_po_buisness_head')}}";
    var payroll_revert_update_link = "{{url('payroll_revert_update')}}";
    var upload_clientpo_link = "{{url('upload_clientpo')}}";
    var get_oat_ageing_link = "{{url('get_oat_ageing_dt')}}";
    var get_candidate_for_budgie_link = "{{url('get_candidate_for_budgie')}}";
    var client_type_update_link = "{{url('client_type_update')}}";
    var send_offer_release_link = "{{url('send_offer_release')}}";
    var upload_mail_attach_po_link = "{{url('upload_mail_attach_po')}}";
    var save_as_draft_mail_link = "{{url('save_as_draft_mail')}}";
    var get_mail_details_link = "{{url('get_mail_details')}}";

    </script>
</body>

</html>
