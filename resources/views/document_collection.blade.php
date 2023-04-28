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
/* input::-webkit-input-placeholder {
    color:red;
}

input::-moz-placeholder {
    color:red;
}

input::-ms-placeholder {
    color:red;
}

input::placeholder {
    color:red;
} */

/* .modal-lg {
    max-width: 1500px !important;
} */
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

<!-- with esi form modal -->




















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
                                        <input type="hidden" name="or_name" id="or_name">
                                        <input type="hidden" name="hr_recruiter" id="hr_recruiter">

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
                                        {{-- <label for="get_emp_mode">Select ESI Type*</label>
                                        <select name="esi_type" id="esi_type" class="form-control" required>
                                            <option value="">Select</option>
                                            <option value="WITHESI">WITH ESI</option>
                                            <option value="WITHOUT ESI">WITHOUT ESI</option>
                                        </select> --}}
                                        <label for="closed_salary_pa" id="closed_salary_pa_lb">Offer CTC(PA)*</label>
                                        <input type="number" name="closed_salary_pa" id="closed_salary_pa"
                                            class="form-control" placeholder="600000" min="300000">
                                            <label for="or_department">Department*</label>
                                        <input type="text" name="or_department" id="or_department" class="form-control">
                                        {{-- <select name="or_department" id="or_department" class="form-control">
                                            <option value="">select</option>

                                        </select> --}}

                                        <label for="">Salary Remark</label>
                                        <input type="text" name="or_salary_review" id="or_salary_review"
                                            class="form-control" placeholder="Salary Remark">
                                        <label for="or_cc_mailid">cc MailID</label>
                                        <textarea name="or_cc_mailid" id="or_cc_mailid" class="form-control"></textarea>
                                        <label for="or_approver">Approver</label>
                                        <select name="or_approver" id="or_approver" class="form-control">
                                            <option value="1013712">Usha Guru</option>
                                            <option value="900002">Pradeesh</option>
                                        </select>
                                        <label for="">Remarks</label>
                                        <textarea name="or_remark" id="or_remark" cols="20" rows="2"
                                            class="form-control" placeholder="Remarks"></textarea>
                                      <label>Hr Onboarder</label>
                                    <select name="hr_onboarder" id="hr_onboarder" class="form-control" required>
                                     <option value="">select</option>
                                         </select>
                                        <span id="required_fields_err" style="display:none;color:red;">* Fields Required</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="welcome_buddy">Buddy*</label>
                                        <select name="welcome_buddy" id="welcome_buddy" class="form-control">
                                            <option value="">Select</option>
                                            <option value="">Devi</option>
                                            <option value="">Divya</option>
                                        </select>
                                        <label for="last_drawn_ctc">Last Drawn CTC*</label>
                                        <input type="number" name="last_drawn_ctc" id="last_drawn_ctc" class="form-control">
                                        <label for="or_joining_type">Joining Type*</label>
                                        <select name="or_joining_type" id="or_joining_type" class="form-control"
                                            required="">
                                            <option value="">Select</option>
                                            <option value="Immediate Joining ">Immediate Joining </option>
                                            <option value="Later Date">Later Date</option>
                                        </select>

                                        <label for="or_doj">Date of Joining*</label>
                                        <input type="date" name="or_doj" id="or_doj" class="form-control"
                                            value="">
                                        <label for="or_bc_mailid">bcc MailID</label>
                                        <textarea name="or_bc_mailid" id="or_bc_mailid" class="form-control"></textarea>
                                        <label for="po_type">PO Type*</label>
                                        <select name="po_type" id="po_type" class="form-control">
                                            <option value="">Select</option>
                                            <option value="po">PO</option>
                                            <option value="non_po">Non PO</option>
                                        </select>
                                        <label for="register_fee">One Time Recruitment Fee*</label>
                                        <select name="register_fee" id="register_fee" class="form-control"
                                            required="">
                                            <option value="">Select</option>
                                            <option value="Applicable">Applicable</option>
                                            <option value="Not Applicable">Not Applicable</option>
                                        </select>
                                        <label for="proof_of_attach">Attachment for Approval</label>
                                        <input class="form-control form-control-sm" type="file"
                                        id="proof_of_attach" name="proof_of_attach">
                                        {{-- <button class="btn btn-secondary btn-sm" type="button" id="preview_ol_btn">Upload</button> --}}
                                    </div>
                                    {{-- <div class="col-md-4">
                                        <label for="attendance_format">Attendance Format*</label>
                                        <input class="form-control" type="input"
                                        id="attendance_format" name="attendance_format">
                                        <label for="weak_off">Weak Off*</label>
                                        <input class="form-control" type="input"
                                        id="weak_off" name="weak_off">
                                        <label for="payroll_status_ctc">Payroll Status</label>
                                        <input class="form-control" type="input"
                                        id="payroll_status_ctc" name="payroll_status_ctc">

                                        <label for="vertical">Vertical</label>
                                        <input class="form-control" type="input"
                                        id="vertical" name="vertical">
                                        <label for="onboarder">On Boarder</label>
                                        <input class="form-control" type="input"
                                        id="onboarder" name="onboarder">
                                        <label for="reviewer">Reviewer</label>
                                        <input class="form-control" type="input"
                                        id="reviewer" name="reviewer">

                                        <label for="primary_reporter">Primary Reporting Manager</label>
                                        <input class="form-control" type="input"
                                        id="primary_reporter" name="primary_reporter">
                                        <label for="welcome_buddy">Additional Reporting Manager</label>
                                        <input class="form-control" type="input"
                                        id="additional_reporter" name="additional_reporter">
                                    </div> --}}

                                    <!-- <div id="offer_letter_preview"></div> -->

                                </div>
                                <br>
                                <div class="modal-footer" style="display: block;">

                                    <button class="btn btn-secondary btofferReleasedProcessbtnn-sm" type="button" id="preview_ol_btn"
                                        style="float:left;">Generate
                                        & Preview
                                        Offer Letter</button>
                                    <button type="button" id="offerReleasedProcessbtn" disabled style="float:right;"
                                        class="btn btn-primary ml-1 btn-sm">
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
                                                    <th>OAT Remark</th>
                                                    <th>Action</th>
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
             <div class="modal fade" id="view_remark_oat" data-bs-backdrop="true" tabindex="-1" role="dialog"
             aria-labelledby="" aria-hidden="true">

             <div class="modal-dialog modal-dialog-scrollable" role="document">
                 <div class="modal-content">
                     <div class="modal-header">
                         <h5 class="modal-title" id="">View Remark</h5>

                         <button type="button" id="clientPoclosebtn" class="close" data-bs-dismiss="modal" aria-label="Close">
                             <i class="bi bi-x-octagon-fill"></i>
                         </button>
                     </div>
                     <div class="modal-body">

                         <div class="row">
                             <form  class="form" id="oatRemarkform" method="post" action="javascript:void(0)">
                                 {{-- <input type="hidden" name="can_id" id="can_id"> --}}
                                 <div class="form-group">
                                     <label for="Remark">Remark</label>
                                     <input type="text" name="oat_remark" id="oat_remark" class="form-control" readonly>
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


<!-------------------- --->





            </div>
            <!-- with_esi_form MODAL -->
   <div class="modal fade" id="with_esi_form" data-bs-backdrop="true" tabindex="-1" role="dialog"
   aria-labelledby="" aria-hidden="true">

   <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="">WITH ESI CTC FORM</h5>

               <button type="button" id="esiformClosebtn" class="close" data-bs-dismiss="modal" aria-label="Close">
                   <i class="bi bi-x-octagon-fill"></i>
               </button>
           </div>
           <div class="modal-body">

               <div class="row">
                   <form  class="form" id="esiForm" method="post" action="javascript:void(0)">
                       {{-- <input type="hidden" name="can_id" id="can_id"> --}}
                       <div class="responsive">
                        <table class="table" id="edit_ctc_oat">
                            <thead>
                                <tr>
                                    <th>Components</th>
                                    <th>PM</th>
                                    <th>PA</th>
                                    <!-- <th>Description</th> -->
                                </tr>
                            </thead>
                            <tbody id="">
                                <tr><td>Basic</td>  <input type="hidden" name="cdID" id="esi_cdID">
                                    <input type="hidden" name="rfh_no" id="rfh_no">
                                    <input type="hidden" name="offer_letter_name" id="offer_letter_name">
                                    <input type="hidden" name="or_hepl_recruitment_ref_number"
                                        id="or_hepl_recruitment_ref_number">
                                    <td><input type="number" class="form-control cl_right basic" name="basic_pm" id="basic_pm" required></td>
                                    <td><input type="number" class="form-control cl_right " name="basic_pa" id="basic_pa" readonly></td>
                                </tr>

                                <tr>
                                    <td>HRA</td>
                                    <td><input type="number" class="form-control cl_right basic" name="hra_pm" id="hra_pm" required></td>
                                    <td><input type="number" class="form-control cl_right" name="hra_pa" id="hra_pa" readonly></td>
                                </tr>
                                <tr><td>Medical Allowance </td>
                                    <td><input type="number" class="form-control cl_right basic" name="medi_al_pm" id="medi_al_pm" required></td>
                                    <td><input type="number" class="form-control cl_right" name="medi_al_pa" id="medi_al_pa" readonly></td>
                                </tr>
                                <tr><td>Conveyance</td>
                                    <td><input type="number" class="form-control cl_right basic" name="conv_pm" id="conv_pm" required></td>
                                    <td><input type="number" class="form-control cl_right" name="conv_pa" id="conv_pa" readonly></td>
                                </tr>
                                <tr><td>Special Allowance</td>
                                    <td><input type="number" class="form-control cl_right basic" name="spl_al_pm" id="spl_al_pm" required></td>
                                    <td><input type="number" class="form-control cl_right" name="spl_al_pa" id="spl_al_pa" readonly></td>
                                </tr>
                                <tr><td>Monthly Components [A]</td>
                                    <td><input type="number" class="form-control cl_right" name="comp_a_pm" id="comp_a_pm" readonly></td>
                                    <td><input type="number" class="form-control cl_right" name="comp_a_pa" id="comp_a_pa" readonly></td>
                                </tr>
                                <tr><td colspan="3">Employer Contrinbution (DIRECT)</td></tr>
                                <tr>
                                    <td>Employee Contribution PF </td><td><input type="text" class="form-control cl_right ec" name="ec_pf_pm" id="ec_pf_pm" required></td>
                                    <td><input type="number" class="form-control cl_right" name="ec_pf_pa" id="ec_pf_pa" readonly></td>
                                </tr>
                                <tr>
                                    <td>Employer Contribution ESI</td>
                                    <td><input type="number" class="form-control cl_right ec" name="ec_esi_pm " id="ec_esi_pm" required></td>
                                    <td><input type="number" class="form-control cl_right" name="ec_esi_pa" id="ec_esi_pa" readonly></td>
                                </tr>
                                <tr><td>SUB TOTAL [B]</td><td><input type="number" class="form-control cl_right" name="sub_totalb_pm" id="sub_totalb_pm" readonly></td>
                                    <td><input type="number" class="form-control cl_right" name="sub_totalb_pa" id="sub_totalb_pa" readonly></td></tr>
                                <tr><td colspan="3">Annual Benefits (INDIRECT))</td></tr>
                                <tr><td>Gratuity</td>
                                    <td><input type="number" class="form-control cl_right ab" name="gratuity_pm" id="gratuity_pm" required></td>
                                    <td><input type="number" class="form-control cl_right" name="gratuity_pa" id="gratuity_pa" readonly></td>
                                </tr>
                                <tr>
                                    <td>Statutory Bonus</td>
                                    <td><input type="number" class="form-control cl_right ab" name="st_bonus_pm" id="st_bonus_pm" required></td>
                                    <td><input type="number" class="form-control cl_right" name="st_bonus_pa" id="st_bonus_pa" readonly></td>
                                </tr>
                                <tr><td>SUB TOTAL [C]</td>
                                    <td><input type="number" class="form-control cl_right" name="sub_totalc_pm" id="sub_totalc_pm" readonly></td>
                                    <td><input type="number" class="form-control cl_right" name="sub_totalc_pa" id="sub_totalc_pa" readonly></td>
                                </tr>
                                <tr><td>[A] + [B] + [C]</td>
                                    <td><input type="number" class="form-control cl_right" name="abc_pm" id="abc_pm" readonly></td>
                                    <td><input type="number" class="form-control cl_right" name="abc_pa" id="abc_pa" readonly></td></tr>
                                <tr>
                                    <td>NET PAY [In Rs PM]</td>
                                    <td colspan="2"><input type="number" class="form-control cl_right" name="net_pay" id="net_pay" required></td></tr>
                                {{-- <tr><td>Group Mediclaim for Self and Family(if ESI not Covered</td><td colspan="2" class=" cl_right"><td><input type="text" class="form-control cl_right" name="abc_pa" id="abc_pa" ></td></td></tr>
                                <tr><td>Personal Accident Policy</td><td colspan="2" class="cl_right"><td><input type="text" class="form-control cl_right" name="abc_pa" id="abc_pa" ></td></td></tr>
                                <tr><td>Term Insurance</td><td colspan="2" class="cl_right"><td><input type="text" class="form-control cl_right" name="abc_pa" id="abc_pa" ></td></td></tr> --}}
                            </tbody>
                        </table>
                    </div>
                       <hr>
                       <div class="form-group" style="text-align:center;">
                           <button class="btn btn-primary btn-sm" type="submit" id="submitEsi">Save & Generate Preview</button>
                       </div>

                   </form>
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
    <script src="../assets/pro_js/document_collection.js"></script>


    <script type="text/javascript">
    $(document).ready(function() {
        var page = "document_collection";

        if (page == "document_collection") {
            $(".doc_collection_m").addClass("active");
        }

    });

    var get_candidate_docinfo_link = "{{url('get_candidate_docinfo')}}";
    var candidate_follow_up_history_link = "{{url('candidate_follow_up_history')}}";
    var get_offer_released_report_link = "{{url('get_offer_released_report')}}";

    var process_offer_letter_release_link = "{{url('process_offer_letter_release')}}";
    var send_to_payroll_link = "{{url('send_to_payroll')}}";
    var get_buddylist_link = "{{url('get_buddylist')}}";
    var get_department_link = "{{url('get_department_name')}}";

    var get_oat_ageing_link = "{{url('get_oat_ageing_re')}}";
    var upload_rc_file_attach_link = "{{url('upload_rc_file_attach')}}";
        var submit_esi_form_link = "{{url('submit_esi_form')}}";
    </script>
</body>

</html>
