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

                <!-- // Basic multiple Column Form section start -->
                <section id="multiple-column-form">
                    <div class="row match-height">
                        <div class="col-12">
                            <div class="card">

                                <div class="card-content">
                                    <div class="card-body">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">

                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link active" id="approved_offers-tab" data-bs-toggle="tab" href="#approved_offers"
                                                role="tab" aria-controls="approved_offers" aria-selected="false">Pending Offers</a>
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

                                        <div class="tab-pane fade show active" id="approved_offers" role="tabpanel"
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
                                                            <th>Finance Status</th>
                                                            <th>BH Status</th>
                                                            <th>PO Type</th>
                                                            <th>OAT Ageing</th>
                                                            {{-- <th>Offer Release Action</th> --}}
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
                                                            <th>Action</th>
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


              <!-- dooj modal -->
              <div class="modal fade" id="update_doj" data-bs-backdrop="true" tabindex="-1" role="dialog"
              aria-labelledby="" aria-hidden="true">

              <div class="modal-dialog modal-dialog-scrollable " role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                        Update Candidate Details
                        <button type="button" id="updoj_closebtn" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x-octagon-fill"></i>
                        </button>
                    </div>
                      <div class="modal-body">
                        <div class="row" class="col-md-6">
                    <form id="up_doj_form" method="POST" action="javascript:void(0)">
                    <div class="form-group">
                        <label>Candidate status</label>
                        <select class="form-control" class="cand_status" id="cand_status">
                            <option value=""> Select</option>
                            <option value="0">Ready For Preonboarding</option>
                            <option value="1">Candidate Onboard</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Date of Joining</label>
                        <input type="date" class="form-control" name="up_doj" id="up_doj"/>
                        <input type="hidden" class="form-control" name="can_id" id="can_id"/>
                    </div>

                      </div>
                      <div class="form-group text-center">
                             <button type="submit"  id="can_status_update" class="btn btn-sm btn-primary">Update</button>
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
    <script src="../assets/pro_js/offers_recruiter.js"></script>


    <script type="text/javascript">
    $(document).ready(function() {
        var page = "offers_recruiter";

        if (page == "offers_recruiter") {
            $(".offers_m").addClass("active");
        }

    });

    var get_offer_list_rt_apo_link = "{{url('get_offer_list_rt_apo')}}";
    var candidate_follow_up_history_link = "{{url('candidate_follow_up_history')}}";
    var get_offer_released_report_link = "{{url('get_offer_released_report')}}";

    var process_offer_letter_release_link = "{{url('process_offer_letter_release')}}";
    var send_to_candidate_ol_link = "{{url('send_to_candidate_ol')}}";
    var get_offer_accepted_for_rr_link = "{{url('get_offer_accepted_for_rr')}}";
    var get_offer_rejected_for_rr_link = "{{url('get_offer_rejected_for_rr')}}";
    var get_oat_ageing_link = "{{url('get_oat_ageing_re')}}";
    var update_doj_link = "{{url('update_can_doj')}}";

    </script>
</body>

</html>
