<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Recruit Request - {{ $siteTitle }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Common CSS -->
    @include('layouts.commoncss')
    <!-- Common CSS -->
    <style>
    body {
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
                            <h3>View Recruitment Request</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="view_task_detail">View Task Details</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">View Recruitment Request</li>
                                </ol>
                            </nav>
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
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <!-- Basic Form Inputs card start -->
                                                <div class="card">
                                                    <div class="card-block">
                                                        <form class="form" id="requestEditForm" method="post"
                                                            action="javascript:void(0)" enctype="multipart/form-data"
                                                            data-parsley-validate
                                                            class="form_validate form-horizontal form-label-left"
                                                            autocomplete="off">
                                                            <div class="row">
                                                                <div class="col-sm-12 mobile-inputs">
                                                                    <label><b>Request for hire:</b> <b
                                                                            style="color: red;">*</b></label><br>
                                                                </div>
                                                                <div class="col-sm-4 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <input type="hidden" name="res_id" id="res_id"
                                                                            class="form-control" value="">
                                                                        <input type="radio" name="rolls_option"
                                                                            id="rolls_option"
                                                                            value="Activity Outsourcing to HEPL"
                                                                            disabled> &nbsp; Activity Outsourcing to
                                                                        HEPL
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <input type="radio" name="rolls_option"
                                                                            id="rolls_option"
                                                                            value="Manpower Outsourcing to HEPL"
                                                                            disabled> &nbsp; Manpower Outsourcing to
                                                                        HEPL
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <input type="radio" name="rolls_option"
                                                                            id="rolls_option" value="On Rolls" disabled>
                                                                        &nbsp; On Rolls
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row" style=" padding-top:10px;">
                                                                <div class="col-sm-12 mobile-inputs">
                                                                    <label><b>Request raised by:</b> <b
                                                                            style="color: red;">*</b></label><br>
                                                                </div>
                                                                <div class="col-sm-3 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="Name">Name</label>
                                                                        <input type="text" name="name" id="name"
                                                                            class="form-control" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="Mobile">Mobile number</label>
                                                                        <input type="text" name="mobile" id="mobile"
                                                                            class="form-control" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="Email">Email address</label>
                                                                        <input type="text" name="email" id="email"
                                                                            class="form-control" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="Position reports to">Position
                                                                            reports to</label>
                                                                        <input type="text" name="position_reports"
                                                                            id="position_reports" class="form-control"
                                                                            readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row" style="padding-top:10px;">
                                                                <div class="col-sm-4 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="Approved by">Approved by: <b
                                                                                style="color: red;">*</b></label>
                                                                        <input type="text" name="approved_by"
                                                                            id="approved_by" class="form-control"
                                                                            readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 mobile-inputs"
                                                                    style="display:none;">
                                                                    <div class="form-group">
                                                                        <label for="Approval for hire">Approval for
                                                                            hire: <b style="color: red;"></b></label>
                                                                        <input type="file" name="approval_hire"
                                                                            id="approval_hire" class="form-control"
                                                                            accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf"
                                                                            readonly />
                                                                        Please attach the ticket number or mail approval
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="Ticket Number">Ticket
                                                                            Number:</label>
                                                                        <input type="text" name="ticket_number"
                                                                            id="ticket_number" class="form-control"
                                                                            readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row" style=" padding-top:10px;">
                                                                <div class="col-sm-4 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="Position Title">Position Title: <b
                                                                                style="color: red;">*</b></label>
                                                                        <input type="text" name="position_title"
                                                                            id="position_title" class="form-control"
                                                                            readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="Location">Location: <b
                                                                                style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                            <input type="radio" name="location"
                                                                                id="location" value="WFH" disabled>
                                                                            &nbsp; WFH
                                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                            <input type="radio" name="location"
                                                                                id="location" value="Work from Site"
                                                                                disabled> &nbsp; Work from Site
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="location_preferred">Please mention
                                                                            location preferred</label>
                                                                        <textarea rows="3" cols="3" class="form-control"
                                                                            name="location_preferred"
                                                                            id="location_preferred" readonly></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row" style="padding-top:10px;">
                                                                <div class="col-sm-4 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="Business">Business: <b
                                                                                style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                            <select name="business" id="business"
                                                                                class="js-example-basic-single form-control business"
                                                                                readonly>
                                                                                <option value="">Select</option>
                                                                                <option value="CKPL">CKPL</option>
                                                                                <option value="CIPL">CIPL</option>
                                                                                <option value="Limelite">Limelite
                                                                                </option>
                                                                                <option value="Trends in vogue">Trends
                                                                                    in vogue</option>
                                                                                <option value="CK Institutions">CK
                                                                                    Institutions</option>
                                                                                <option value="Farms">Farms</option>
                                                                                <option value="HEPL">HEPL</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="Band">Band <b
                                                                                style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                            <select name="band" id="band"
                                                                                class="js-example-basic-single form-control"
                                                                                readonly>
                                                                                <option value="">Select</option>
                                                                            </select>
                                                                            <!-- <input type="text" name="band" class="form-control" required> -->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 mobile-inputs ckpl" id="ckpl"
                                                                    style="display: none;">
                                                                    <div class="form-group">
                                                                        <label for="Devision CKPL">Division <b
                                                                                style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                            <select name="division1" id="division1"
                                                                                class="js-example-basic-single form-control"
                                                                                readonly>
                                                                                <option value="">Select</option>
                                                                                <option value="Personal Care">Personal
                                                                                    Care</option>
                                                                                <option value="Ambient & Beverage">
                                                                                    Ambient & Beverage</option>
                                                                                <option value="Cold Chain">Cold Chain
                                                                                </option>
                                                                                <option value="Foods & Snacks">Foods &
                                                                                    Snacks</option>
                                                                                <option value="International Business">
                                                                                    International Business</option>
                                                                                <option value="Raaga">Raaga</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 mobile-inputs cipl" id="cipl"
                                                                    style="display: none;">
                                                                    <div class="form-group">
                                                                        <label for="Devision">Division <b
                                                                                style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                            <select name="division2" id="division2"
                                                                                class="js-example-basic-single form-control"
                                                                                readonly>
                                                                                <option value="">Select</option>
                                                                                <option value="E-Com Special Projects">
                                                                                    E-Com Special Projects</option>
                                                                                <option value="Corporate">Corporate
                                                                                </option>
                                                                                <option value="Projects (CIPL)">Projects
                                                                                    (CIPL) </option>
                                                                                <option value="Sanchu Animal Hospital">
                                                                                    Sanchu Animal Hospital</option>
                                                                                <option value="Redbelly">Redbelly
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 mobile-inputs ck_institution"
                                                                    id="ck_institution" style="display: none;">
                                                                    <div class="form-group">
                                                                        <label for="Devision">Division <b
                                                                                style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                            <select name="division3" id="division3"
                                                                                class="js-example-basic-single form-control"
                                                                                readonly>
                                                                                <option value="">Select</option>
                                                                                <option value="CK Schools">CK Schools
                                                                                </option>
                                                                                <option value="CK Colleges">CK Colleges
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 mobile-inputs ck_farms"
                                                                    id="ck_farms" style="display: none;">
                                                                    <div class="form-group">
                                                                        <label for="Devision">Division <b
                                                                                style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                            <select name="division4" id="division4"
                                                                                class="js-example-basic-single form-control"
                                                                                readonly>
                                                                                <option value="">Select</option>
                                                                                <option value="Bird Farm">Bird Farm
                                                                                </option>
                                                                                <option value="Animal Farm">Animal Farm
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row" style=" padding-top:10px;">
                                                                <div class="col-sm-4 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="function">Function: <b
                                                                                style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                            <input type="text" name="function"
                                                                                id="function" class="form-control"
                                                                                readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="no_of_positions">No. of Positions:
                                                                            <b style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                            <input type="text" name="no_of_positions"
                                                                                readonly id="no_of_positions"
                                                                                class="form-control" readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="">JD / Roles & Responsibilities <b
                                                                                style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                            <textarea rows="3" cols="3"
                                                                                class="form-control" name="jd_roles"
                                                                                id="jd_roles" readonly></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row" style="padding-top:10px;">
                                                                <div class="col-sm-4 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="">Qualification: <b
                                                                                style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                            <input type="text" name="qualification"
                                                                                id="qualification" class="form-control"
                                                                                readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="">Essential Skill sets: <b
                                                                                style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                            <textarea rows="3" cols="3"
                                                                                class="form-control"
                                                                                name="essential_skill"
                                                                                id="essential_skill"
                                                                                readonly></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="">Good to have Skill sets (if
                                                                            any):</label>
                                                                        <div class="form-group">
                                                                            <textarea rows="3" cols="3"
                                                                                class="form-control" name="good_skill"
                                                                                id="good_skill" readonly></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row" style=" padding-top:10px;">
                                                                <div class="col-sm-4 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="">Experience (in yrs): <b
                                                                                style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                            <!-- <input type="text" name="experience" id="experience" class="form-control" readonly > -->
                                                                            <select name="experience" id="experience"
                                                                                class="form-control" readonly>
                                                                                <option value="">Select</option>
                                                                                <option value="less than 1">less than 1
                                                                                </option>
                                                                                <option value="1-2">1-2</option>
                                                                                <option value="2-3">2-3</option>
                                                                                <option value="3-4">3-4</option>
                                                                                <option value="4-5">4-5</option>
                                                                                <option value="5-6">5-6</option>
                                                                                <option value="6-7">6-7</option>
                                                                                <option value="7-8">7-8</option>
                                                                                <option value="8-9">8-9</option>
                                                                                <option value="9-10">9-10</option>
                                                                                <option value=">10">>10</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="">Maximum CTC(Per Month): <b
                                                                                style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                            <input type="text" name="salary_range"
                                                                                id="salary_range" class="form-control"
                                                                                readonly>
                                                                            <!-- <select name="salary_range" id="salary_range" class="form-control" readonly>
                                                                                    <option value="">Select</option>
                                                                                    <option value="1L-2L">1L-2L</option>
                                                                                    <option value="2L-3L">2L-3L</option>
                                                                                    <option value="3L-4L">3L-4L</option>
                                                                                    <option value="4L-5L">4L-5L</option>
                                                                                    <option value="5L-6L">5L-6L</option>
                                                                                    <option value="6L-7L">6L-7L</option>
                                                                                    <option value="7L-8L">7L-8L</option>
                                                                                    <option value="8L-9L">8L-9L</option>
                                                                                    <option value="9L-10L">9L-10L</option>
                                                                                    <option value="10L-11L">10L-11L</option>
                                                                                    <option value="11L-12L">11L-12L</option>
                                                                                    <option value="12L-13L">12L-13L</option>
                                                                                    <option value="13L-14L">13L-14L</option>
                                                                                    <option value="14L-15L">14L-15L</option>
                                                                                </select> -->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="">Any other specific
                                                                            consideration:</label>
                                                                        <div class="form-group">
                                                                            <textarea rows="3" cols="3"
                                                                                class="form-control" name="any_specific"
                                                                                id="any_specific" readonly></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                    </div>
                                                    </form>
                                                </div>
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
        </div>
        <!-- Footer -->
        @include('layouts.footer')
        <!-- /Footer -->
    </div>
    </div>
    <!-- Common JS -->
    @include('layouts.commonscript')
    <!-- Common JS -->
    <script src="../assets/pro_js/view_recrequest.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        var page = "view_recruit_request_new";

        if (page == "view_recruit_request_new") {
            $(".task_detail_m").addClass("active");
        }

    });
    $(document).ready(function() {

        $('.ckpl').hide();
        $('.cipl').hide();
        $('.ck_institution').hide();
        $('.ck_farms').hide();

        $('.business').on('change', function() {
            var val = $('option:selected', this).val();
            if (val == 'CKPL') {
                $('.ckpl').show();
                $('.cipl').hide();
                $('.ck_institution').hide();
                $('.ck_farms').hide();
            } else if (val == 'CIPL') {
                $('.cipl').show();
                $('.ckpl').hide();
                $('.ck_institution').hide();
                $('.ck_farms').hide();
            } else if (val == 'CK Institutions') {
                $('.ck_institution').show();
                $('.ckpl').hide();
                $('.cipl').hide();
                $('.ck_farms').hide();
            } else if (val == 'Farms') {
                $('.ck_farms').show();
                $('.ck_institution').hide();
                $('.ckpl').hide();
                $('.cipl').hide();
            } else {
                //$('.pay1_ele').show();
                $('.ckpl').hide();
                $('.cipl').hide();
                $('.ck_institution').hide();
                $('.ck_farms').hide();
            }

        }); //end


    });



    var get_recruitment_view_details_new_link = "{{url('get_recruitment_view_details_new')}}";
    var get_band_details_link = "{{url('get_band_details')}}";
    </script>
</body>

</html>