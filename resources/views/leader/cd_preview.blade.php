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
    <style>
    body {
        color: #000000;

    }

    .bi-eye::before {
        margin-top: 4px !important;
    }

    .bi-eye-slash::before {
        margin-top: 4px !important;

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

    .timeline {
        max-width: 830px;
        margin: 0px auto;
        display: flex;
        flex-direction: column;
        position: relative;
        padding: 15px 0px;
    }

    .timeline::after {
        content: "";
        position: absolute;
        width: 3px;
        background-color: #848892;
        height: 100%;
        top: 0px;
        left: 50%;
        transform: translateX(-50%);
    }

    .timeline__content {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        padding: 18px 30px;
        background-color: white;
        border-radius: 5px;
        position: relative;
        width: 386px;
        box-shadow: 0 2px 8px 0 #242e4c59;
    }

    .timeline__content::after {
        content: "";
        position: absolute;
        width: 20px;
        height: 20px;
        background-color: #435ebe;
        top: 50%;
        transform: translateY(-50%) rotate(45deg);
    }

    .timeline__content::before {
        content: "";
        position: absolute;
        width: 20px;
        height: 20px;
        background-color: #ffffff;
        border-radius: 50%;
        transform: translateY(-50%);
    }

    .timeline__content:nth-child(odd) {
        margin-left: auto;
    }

    .timeline__content:nth-child(odd) .content_tag {
        right: 5px;
    }

    .timeline__content:nth-child(odd)::after {
        left: -10px;
    }

    .timeline__content:nth-child(odd)::before {
        top: 50%;
        left: -39px;
    }

    .timeline__content:nth-child(even) {
        align-items: flex-end;
    }

    .timeline__content:nth-child(even) .content_p {
        text-align: right;
    }

    .timeline__content:nth-child(even)::after {
        right: -10px;
    }

    .timeline__content:nth-child(even)::before {
        top: 50%;
        right: -39px;
    }

    .timeline__content:nth-child(even) .content_tag {
        left: 5px;
    }

    .content_tag {
        position: absolute;
        top: 5px;
        padding: 6px 10px;
        background-color: #fcf3e3;
        border-radius: 3px;
        font-weight: bold;
        font-size: 14px;
        color: #1f1f1f;
    }

    .content_date {
        margin-bottom: 10px;
        font-weight: bold;
        font-size: 14px;
        color: #848892;
    }

    .content_p {
        color: #242e4c;
        max-width: 230px;
        margin-bottom: 20px;
    }

    .content_link {
        display: inline-flex;
        text-decoration: none;
        align-items: center;
        font-weight: bold;
        font-size: 14px;
        color: #1f1f1f;
    }

    .content_link svg {
        margin-left: 5px;
    }

    .content_link:hover {
        color: royalblue;
        transition-duration: 300ms;
    }

    .content_link:hover svg path {
        fill: royalblue;
    }

    @media screen and (max-width: 600px) {
        .timeline {
            gap: 15px;
            padding: 10px;
        }

        .timeline::after {
            display: none;
        }

        .timeline__content {
            width: 100%;
        }

        .timeline__content::after {
            display: none;
        }

        .timeline__content::before {
            display: none;
        }
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
                            <h3>Candidate Profile</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item active" aria-current="page">Candidate Profile</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>


                <!-- // Basic multiple Column Form section start -->
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12 col-xl-3">
                            <div class="card">
                                <!-- <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-xl">
                                            <img src="../assets/images/faces/1.jpg" alt="Face 1">
                                        </div>
                                        <div class="ms-3 name">
                                            <h5 class="font-bold" id="show_candidate_name"></h5>
                                            <h6 class="text-muted mb-0">@johnducky</h6>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="card-body">
                                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                        aria-orientation="vertical">
                                        <a class="nav-link active" id="v-pills-cbd-tab" data-bs-toggle="pill"
                                            href="#v-pills-cbd" role="tab" aria-controls="v-pills-cbd"
                                            aria-selected="true">Basic
                                            Details</a>
                                        <a class="nav-link" id="v-pills-edu-tab" data-bs-toggle="pill"
                                            href="#v-pills-edu" role="tab" aria-controls="v-pills-edu"
                                            aria-selected="false">Education</a>
                                        <a class="nav-link" id="v-pills-exp-tab" data-bs-toggle="pill"
                                            href="#v-pills-exp" role="tab" aria-controls="v-pills-exp"
                                            aria-selected="false">Experience</a>
                                        <!-- <a class="nav-link" id="v-pills-pia-tab" data-bs-toggle="pill"
                                            href="#v-pills-pia" role="tab" aria-controls="v-pills-pia"
                                            aria-selected="false">Proof
                                            of Identity & Address</a> -->

                                        <a class="nav-link" id="v-pills-compensation-tab" data-bs-toggle="pill"
                                            href="#v-pills-compensation" role="tab" aria-controls="v-pills-compensation"
                                            aria-selected="false">Proof of Compensation & Benefits
                                            Received</a>

                                        <a class="nav-link" id="v-pills-proofdoc-tab" data-bs-toggle="pill"
                                            href="#v-pills-proofdoc" role="tab" aria-controls="v-pills-proofdoc"
                                            aria-selected="false">Other Proof Documents</a>

                                        <a class="nav-link" id="v-pills-others-tab" data-bs-toggle="pill"
                                            href="#v-pills-others" role="tab" aria-controls="v-pills-action"
                                            aria-selected="false">others</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-xl-9">
                            <form id="docSubmit" method="post" action="javascript:void(0)">

                                <div class="card">

                                    <div class="card-body">
                                        <div class="tab-content" id="v-pills-tabContent">
                                            <div class="tab-pane fade show active" id="v-pills-cbd" role="tabpanel"
                                                aria-labelledby="v-pills-cbd-tab">

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h5>Name</h5>
                                                        <p id="cdp_name"></p>

                                                        <h5>Mobile Number</h5>
                                                        <p id="cdp_mobileno"></p>

                                                        <h5>Email</h5>
                                                        <p id="cdp_email"></p>

                                                        <h5>Proof of Identity</h5>
                                                        <p id="poi_name"></p>
                                                        <div id="poi_file"></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h5>Gender</h5>
                                                        <p id="cdp_gender"></p>

                                                        <h5>Candidate Source</h5>
                                                        <p id="cdp_csource"></p>

                                                        <h5>Candidate Type</h5>
                                                        <p id="cdp_ctype"></p>
                                                        <input type="hidden" name="cdp_ctype_val" id="cdp_ctype_val">
                                                        <h5>Proof of Address</h5>
                                                        <p id="poa_name"></p>
                                                        <div id="poa_file"></div>

                                                    </div>

                                                    
                                                </div>
                                                <button class="btn btn-sm btn-smn btn-info" style="float:right;" type="button" id="basicdocNextbtn">Next <i class="bi bi-arrow-right-circle" aria-hidden="true"></i></button>
                        
                                            </div>
                                            <div class="tab-pane fade" id="v-pills-edu" role="tabpanel"
                                                aria-labelledby="v-pills-edu-tab">
                                                
                                                <div class="row">
                                                    <div class="timeline" id="put_candidate_edu_details">
                                                        
                                                    </div>
                                                </div>

                                                <button class="btn btn-sm btn-smn btn-info" style="float:right;" type="button" id="edudocNextbtn">Next <i class="bi bi-arrow-right-circle" aria-hidden="true"></i></button>
                                                <button class="btn btn-sm btn-smn btn-info" style="float:right;margin-right: 5px;" type="button" id="eduPreviousbtn"><i class="bi bi-arrow-left-circle" aria-hidden="true"></i> Previous </button>

                                            </div>
                                            <div class="tab-pane fade" id="v-pills-exp" role="tabpanel"
                                                aria-labelledby="v-pills-exp-tab">
                                                <div class="row">
                                                    <div class="timeline" id="put_candidate_exp_details">
                                                            
                                                    </div>

                                                </div>

                                                <button class="btn btn-sm btn-smn btn-info" style="float:right;" type="button" id="expdocNextbtn">Next <i class="bi bi-arrow-right-circle" aria-hidden="true"></i></button>
                                                <button class="btn btn-sm btn-smn btn-info" style="float:right;margin-right: 5px;" type="button" id="expPreviousbtn"><i class="bi bi-arrow-left-circle" aria-hidden="true"></i> Previous </button>

                                            </div>
                                            <!-- <div class="tab-pane fade" id="v-pills-pia" role="tabpanel"
                                                aria-labelledby="v-pills-pia-tab">
                                                <div class="row">
                                                    <div class="timeline" id="put_poi_poa_details">
                                                                
                                                    </div>

                                                </div>

                                                <button class="btn btn-sm btn-smn btn-info" style="float:right;" type="button" id="edudocNextbtn">Next <i class="bi bi-arrow-right-circle" aria-hidden="true"></i></button>
                                                <button class="btn btn-sm btn-smn btn-info" style="float:right;margin-right: 5px;" type="button" id="eduPreviousbtn"><i class="bi bi-arrow-left-circle" aria-hidden="true"></i> Previous </button>

                                            </div> -->

                                            <div class="tab-pane fade" id="v-pills-compensation" role="tabpanel"
                                                aria-labelledby="v-pills-compensation-tab">
                                                <div class="row">
                                                    <div class="timeline" id="put_candidate_benefits_details">
                                                                
                                                    </div>

                                                </div>
                                                <button class="btn btn-sm btn-smn btn-info" style="float:right;" type="button" id="benefitsdocNextbtn">Next <i class="bi bi-arrow-right-circle" aria-hidden="true"></i></button>
                                                <button class="btn btn-sm btn-smn btn-info" style="float:right;margin-right: 5px;" type="button" id="benefitsPreviousbtn"><i class="bi bi-arrow-left-circle" aria-hidden="true"></i> Previous </button>

                                            </div>

                                            <div class="tab-pane fade" id="v-pills-proofdoc" role="tabpanel"
                                                aria-labelledby="v-pills-proofdoc-tab">
                                                <div class="row" >
                                                    <div class="timeline" id="put_proofdoc_details">

                                                    </div>

                                                </div>

                                                <button class="btn btn-sm btn-smn btn-info" style="float:right;" type="button" id="proofdocNextbtn">Next <i class="bi bi-arrow-right-circle" aria-hidden="true"></i></button>
                                                <button class="btn btn-sm btn-smn btn-info" style="float:right;margin-right: 5px;" type="button" id="proofPreviousbtn"><i class="bi bi-arrow-left-circle" aria-hidden="true"></i> Previous </button>

                                            </div>

                                            <div class="tab-pane fade" id="v-pills-others" role="tabpanel"
                                                aria-labelledby="v-pills-others-tab">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <h5>Proof of Attachment</h5>
                                                            <p id="proof_attach"></p>
                                                            <div id="attach_file"></div>  
                                                               
                                                            
                                                    </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                    <h5>Remark</h5>
                                                    <p id="rc_remark"></p>
                                                </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                    <h5>Salary Review</h5>
                                                    <p id="salary_remark"></p>
                                                </div>
                                                    </div>
                                            </div>
                                                <button class="btn btn-sm btn-smn btn-info" style="float:right;margin-right: 5px;" type="button" id="actionPreviousbtn"><i class="bi bi-arrow-left-circle" aria-hidden="true"></i> Previous </button>

                                            </div>

                                            
                                        </div>
                                    </div>
                                </div>

                            </form>
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
    <script src="../assets/pro_js/leader/cd_preview.js"></script>


    <script type="text/javascript">
    $(document).ready(function() {
        var page = "ol_leader_verify";

if (page == "ol_leader_verify") {
    $(".ol_leader_verify_m").addClass("active");
}

    });

    var get_candidate_preview_details_link = "{{url('get_candidate_preview_details_bh')}}";
    var update_cdoc_status_link = "{{url('update_cdoc_status')}}";
    
    </script>
</body>

</html>