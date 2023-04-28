<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Collection - {{ $siteTitle }}</title>
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

    .dataTables_scrollBody {
        overflow: hidden !important;
    }

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

    #c_profile {
        display: block;
        max-width: -moz-fit-content;
        max-width: fit-content;
        margin: 0 auto;
        overflow-x: auto;
        /* white-space: nowrap; */

        max-height: 800px;
        overflow-y: auto;
        /* vertical scrol */
    }

    .dataTables_length {
        margin-bottom: 10px;
    }

    .dropdown-item.active,
    .dropdown-item:active {
        color: #000 !important;
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
                            <h3>Document Collection</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item active" aria-current="page">Document Collection</li>
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

                    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
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

                <!-- offer released model start -->
                <!-- Button trigger for large size modal -->
                <button type="button" id="show_offer_rel_pro_pop" class="btn btn-outline-warning" style="display:none;"
                    data-bs-toggle="modal" data-bs-target="#offer_release_modal_div">
                    Large Modal
                </button>
                <!--large size Modal -->
                <div class="modal fade" id="offer_release_modal_div" data-bs-backdrop="true" tabindex="-1" role="dialog"
                    aria-labelledby="followupModalTitle" aria-hidden="true">

                    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Offer Process</h4>
                                <button type="button" id="close_offer_rel" class="close" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i data-feather="x">X</i>
                                </button>
                            </div>

                            <div class="modal-body">
                            <form id="orpForm" method="post" action="javascript:void(0)" enctype="multipart/form-data">

                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="hidden" name="or_cdID" id="or_cdID">
                                        <input type="hidden" name="or_rfh_no" id="or_rfh_no">
                                        <input type="hidden" name="offer_letter_name" id="offer_letter_name">
                                        <input type="hidden" name="or_hepl_recruitment_ref_number"
                                            id="or_hepl_recruitment_ref_number">
                                        
                                            
                                        <label for="get_emp_mode">Emp Type*</label>
                                        <select name="get_emp_mode" id="get_emp_mode" class="form-control">
                                            <option value="">Select</option>
                                            <option value="HEPL">HEPL</option>
                                            <option value="HEPL NAPS">HEPL NAPS</option>
                                        </select>

                                        <label for="closed_salary_pa">Offer CTC(PA)*</label>
                                        <input type="text" name="closed_salary_pa" id="closed_salary_pa"
                                            class="form-control" value="600000">
                                        <label for="">Salary Remark</label>
                                        <input type="text" name="or_salary_review" id="or_salary_review"
                                            class="form-control" placeholder="Salary Remark">
                                        <label for="or_cc_mailid">CC MailID</label>
                                        <textarea name="or_cc_mailid" id="or_cc_mailid" class="form-control"></textarea>
                                        <label for="or_approver">Approver</label>
                                        <select name="or_approver" id="or_approver" class="form-control">
                                            <option value="1013712">Usha Guru</option>
                                            <option value="900002">Pradeesh</option>
                                        </select>
                                        <label for="">Remarks</label>
                                        <textarea name="or_remark" id="or_remark" cols="20" rows="2"
                                            class="form-control" placeholder="Remarks"></textarea>
                                        <!-- <a href="" id="preview_offer_letter" target="_blank"> -->

                                        <!-- </a> -->

                                        <span id="required_fields_err" style="display:none;color:red;">* Fields Required</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="welcome_buddy">Buddy*</label>
                                        <select name="welcome_buddy" id="welcome_buddy" class="form-control">
                                            <option value="">Select</option>
                                            <option value="">Devi</option>
                                            <option value="">Divya</option>
                                        </select>
                                        <label for="or_joining_type">Joining Type*</label>
                                        <select name="or_joining_type" id="or_joining_type" class="form-control"
                                            required="">
                                            <option value="">Select</option>
                                            <option value="Immediate Joining ">Immediate Joining </option>
                                            <option value="Later Date">Later Date</option>
                                        </select>
                                        <label for="or_doj">Date of Joining*</label>
                                        <input type="date" name="or_doj" id="or_doj" class="form-control"
                                            value="2022-02-15">
                                        <label for="or_bc_mailid">BC MailID</label>
                                        <textarea name="or_bc_mailid" id="or_bc_mailid" class="form-control"></textarea>
                                        <label for="or_department">Department*</label>
                                        <input typr="text" name="or_department" id="or_department" class="form-control">
                                        <label for="po_type">PO Type*</label>
                                        <select name="po_type" id="po_type" class="form-control">
                                            <option value="">Select</option>
                                            <option value="po">PO</option>
                                            <option value="non_po">Non PO</option>
                                        </select>
                                    </div>

                                    <!-- <div id="offer_letter_preview"></div> -->

                                </div>
                                <br>
                                <div class="modal-footer" style="display: block;">

                                    <button class="btn btn-secondary btn-sm" type="button" id="preview_ol_btn"
                                        style="float:left;">Generate
                                        & Preview
                                        Offer Letter</button>
                                    <button type="button" id="offerReleasedProcessbtn" disabled style="float:right;"
                                        class="btn btn-primary ml-1 btn-sm" data-bs-dismiss="modal">
                                        Send to OAT
                                    </button>
                                    <br>
                                </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- offer released model end -->

                <!-- offer released popup start-->
                <button type="button" class="btn btn-outline-warning" id="show_offer_rel_pro_pop1"
                    data-bs-backdrop="true" style="display:none" data-bs-toggle="modal"
                    data-bs-target="#offer_release_modal_div">Offer Released Process
                    Modal</button>

                <div class="modal fade text-left" id="offer_release_modal_div" tabindex="-1" role="dialog"
                    aria-labelledby="" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Offer Process</h4>
                                <button type="button" id="close_offer_rel" class="close" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i data-feather="x">X</i>
                                </button>
                            </div>
                            <form id="orpForm" method="post" action="javascript:void(0)" enctype="multipart/form-data">
                                <div class="modal-body">


                                </div>
                                <div class="modal-footer" style="display: block;">

                                    <button class="btn btn-secondary btn-sm" type="button" id="preview_ol_btn"
                                        style="float:left;">Generate
                                        & Preview
                                        Offer Letter</button>
                                    <button type="button" id="offerReleasedProcessbtn" style="float:right;"
                                        class="btn btn-primary ml-1 btn-sm" data-bs-dismiss="modal">
                                        <i class="bx bx-check d-block d-sm-none"></i>
                                        <span class="d-sm-block d-none">Send to OAT</span>
                                    </button>
                                    <br><br>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- offer released popup end-->

                <!-- // Basic multiple Column Form section start -->
                <section id="multiple-column-form">
                    <div class="row match-height">
                        <div class="col-12">
                            <div class="card">

                                <div class="card-content">
                                    <div class="card-body">
                                        <h5>No. of Records Found: <span id="total_res_show"></span></h5>
                                        <div class="table-responsive">

                                            <table class="table " id="doc_col_tb">
                                                <thead>
                                                    <th>S.No</th>
                                                    <th>Candidate Name</th>
                                                    <th>RFH No</th>
                                                    <th>HEPL Recruitment Ref.No</th>
                                                    <th>Position</th>
                                                    <th>Sub Position</th>
                                                    <th>Followup</th>
                                                    <th>Document Status</th>
                                                    <th>OAT Status</th>
                                                    <th>Finance Status</th>
                                                    <th>BH Status</th>
                                                    <th>Offer Letter</th>
                                                    <th>OAT Ageing</th>
                                                    <th>Doc Detail</th>
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
            </div>
            <!-- Footer -->
            @include('layouts.footer')
            <!-- /Footer -->
        </div>
    </div>
    <!-- Common JS -->
    @include('layouts.commonscript')
    <!-- Common JS -->
    <script src="../assets/pro_js/document_collection_bc.js"></script>


    <script type="text/javascript">
    $(document).ready(function() {
        var page = "document_collection_bc";

        if (page == "document_collection_bc") {
            $(".doc_collection_bc_m").addClass("active");
        }

    });

    var get_candidate_docinfo_link = "{{url('get_candidate_docinfo_bc')}}";
    var candidate_follow_up_history_link = "{{url('candidate_follow_up_history_bc')}}";
    var get_offer_released_report_link = "{{url('get_offer_released_report_bc')}}";
    var get_oat_ageing_link = "{{url('get_oat_ageing_bc')}}";


    </script>
</body>

</html>