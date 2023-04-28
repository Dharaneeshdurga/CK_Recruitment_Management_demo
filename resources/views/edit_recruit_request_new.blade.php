<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Recruit Request - {{ $siteTitle }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Common CSS -->
    @include('layouts.commoncss')
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.css'>

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
                            <h3>Edit Recruitment Request</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="view_recruit_request">View Recruitment</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Edit Recruitment</li>
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
                                                                            style="color: red;">*</b><br>Please select
                                                                        On Rolls option, if this RFH is for hiring on
                                                                        your roles</label><br>
                                                                </div>
                                                                <div class="col-sm-4 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <input type="hidden" name="res_id" id="res_id"
                                                                            class="form-control" value="">
                                                                        <input type="radio" name="rolls_option"
                                                                            id="rolls_option"
                                                                            value="Activity Outsourcing to HEPL"
                                                                            required> &nbsp; Activity Outsourcing to
                                                                        HEPL
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <input type="radio" name="rolls_option"
                                                                            id="rolls_option"
                                                                            value="Manpower Outsourcing to HEPL"
                                                                            required> &nbsp; Manpower Outsourcing to
                                                                        HEPL
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <input type="radio" name="rolls_option"
                                                                            id="rolls_option" value="On Rolls" required>
                                                                        &nbsp; On Client Rolls
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row" style="background:#eee; padding-top:10px;">
                                                                <div class="col-sm-12 mobile-inputs">
                                                                    <label><b>Request raised by:</b> <b
                                                                            style="color: red;">*</b></label><br>
                                                                </div>
                                                                <div class="col-sm-3 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="Name">Name</label>
                                                                        <input list="name_list" name="name" id="name" class="form-control js-select2 form-control"
                                                                        required>
                                                                        <datalist id="name_list">
                                                                            <option value=""><?php echo 'Name' ?></option>
                                                                        </datalist>

                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="Mobile">Mobile number</label>
                                                                        <input type="text" name="mobile" id="mobile"
                                                                            class="form-control" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="Email">Email address</label>
                                                                        <input type="text" name="email" id="email"
                                                                            class="form-control" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="Position reports to">Position
                                                                            reports to</label>
                                                                        {{-- <input type="text" name="position_reports"
                                                                            id="position_reports" class="form-control"
                                                                            required> --}}
                                                                            <select name="position_reports" id="position_reports" class="select2 form-control"  required></select>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row" style="padding-top:10px;">
                                                                <div class="col-sm-4 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="Approved by">Approved by: <b
                                                                                style="color: red;">*</b></label>
                                                                        {{-- <input type="text" name="approved_by"
                                                                            id="approved_by" class="form-control"
                                                                            required> --}}
                                                                            <select name="approved_by" id="approved_by" class="form-control js-select2"  required></select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="Approval for hire">Approval for
                                                                            hire: <b style="color: red;"></b></label>
                                                                        <input type="file" name="approval_hire"
                                                                            id="approval_hire" class="form-control"
                                                                            accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf" />
                                                                        Please attach the ticket number or mail approval
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="Ticket Number">Ticket
                                                                            Number:</label>
                                                                        <input type="text" name="ticket_number"
                                                                            id="ticket_number" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row" style="background:#eee; padding-top:10px;">
                                                                <div class="col-sm-3 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="Position Title">Position Title: <b
                                                                                style="color: red;">*</b></label>
                                                                        <input type="text" name="position_title"
                                                                            id="position_title" class="form-control"
                                                                            required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="Location"> Work Location: <b style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                                <!-- <input type="radio" name="location"  value="WFH" required > &nbsp; WFH &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                <input type="radio" name="location"  value="Work from Site" required > &nbsp; Work from Site -->
                                                                                <select name="location" id="location" class="form-control js-select2 business" required >
                                                                                 <option value="">Select</option>

                                                                              </select>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="location_preferred">Please mention location preferred/ Onsite Location preferred</label>
                                                                        <textarea rows="3" cols="3" class="form-control"
                                                                            name="location_preferred"
                                                                            id="location_preferred"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="">Employment Category:</label>
                                                                        <div class="form-group">
                                                                             <select name="emp_category" id="emp_category" class="form-control js-select2"></select>
                                                                          </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row" style="padding-top:10px;">
                                                                <div class="col-sm-3 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="Business">Business: <b
                                                                                style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                            <select name="business" id="buiss_list"
                                                                                class="form-control js-select2  business"
                                                                                required>
                                                                                <option value="">Select</option>
                                                                                {{-- <option value="CKPL">CKPL</option>
                                                                                <option value="CIPL">CIPL</option>
                                                                                <option value="Limelite">Limelite
                                                                                </option>
                                                                                <option value="Trends in vogue">Trends
                                                                                    in vogue</option>
                                                                                <option value="CK Institutions">CK
                                                                                    Institutions</option>
                                                                                <option value="Farms">Farms</option>
                                                                                <option value="HEPL">HEPL</option>
                                                                                <option value="CK's Foods">CK's Foods</option>
                                                                                <option value="Eeshu Delicacies">Eeshu Delicacies</option>                                                                     <option value="Eeshu Delicacies">Eeshu Delicacies</option> --}}

                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" name="o_buisness" id="o_buiss" placeholder="Enter the buisness name" style="display:none">
                                                                     </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="Band">Band <b
                                                                                style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                            <select name="band" id="band"
                                                                                class="form-control js-select2 form-control"
                                                                                required>
                                                                                <option value="">Select</option>
                                                                            </select>
                                                                            <!-- <input type="text" name="band" class="form-control" required> -->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 mobile-inputs" id="">
                                                                    <div class="form-group">
                                                                        <label for="department">Department <b style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                             <select name="department" id="department" class="form-control js-form-control js-select2" >
                                                                                 <option value="">Select</option>

                                                                              </select>

                                                                        </div>

                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 mobile-inputs" id="">
                                                                    <div class="form-group">
                                                                        <label for="vertical">Vertical </label>
                                                                        <div class="form-group">
                                                                     <select name="vertical" id="vertical" class="form-control js-form-control js-select2" >
                                                                                 <option value="">Select</option>
                                                                              </select>

                                                                        </div>

                                                                    </div>
                                                                </div>
                                                           </div>
                                                                <div class="col-sm-3 mobile-inputs ckpl" id="ckpl"
                                                                    style="display: none;">
                                                                    <div class="form-group">
                                                                        <label for="Devision CKPL">Division <b
                                                                                style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                            <select name="division1" id="division1"
                                                                                class="form-control js-select2 form-control">
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
                                                                                <option value="Ecom">Ecom</option>

                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row" style="background:#eee; padding-top:10px;">
                                                                <div class="col-sm-3 mobile-inputs cipl" id="cipl"
                                                                    style="display: none;">
                                                                    <div class="form-group">
                                                                        <label for="Devision">Division <b
                                                                                style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                            <select name="division2" id="division2"
                                                                                class="form-control js-select2 form-control">
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
                                                                <div class="col-sm-3 mobile-inputs ck_institution"
                                                                    id="ck_institution" style="display: none;">
                                                                    <div class="form-group">
                                                                        <label for="Devision">Division <b
                                                                                style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                            <select name="division3" id="division3"
                                                                                class="form-control js-select2 form-control">
                                                                                <option value="">Select</option>
                                                                                <option value="CK Schools">CK Schools
                                                                                </option>
                                                                                <option value="CK Colleges">CK Colleges
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 mobile-inputs ck_farms"
                                                                    id="ck_farms" style="display: none;">
                                                                    <div class="form-group">
                                                                        <label for="Devision">Division <b
                                                                                style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                            <select name="division4" id="division4"
                                                                                class="form-control js-select2 form-control">
                                                                                <option value="">Select</option>
                                                                                <option value="Bird Farm">Bird Farm
                                                                                </option>
                                                                                <option value="Animal Farm">Animal Farm
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <div class="col-sm-3 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="function">Function: <b
                                                                                style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                            <input type="text" name="function"
                                                                                id="function" class="form-control"
                                                                                required>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="no_of_positions">No. of Positions:
                                                                            <b style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                            <input type="text" name="no_of_positions"
                                                                                readonly id="no_of_positions"
                                                                                class="form-control" required>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="">JD / Roles & Responsibilities <b
                                                                                style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                            <textarea rows="3" cols="3"
                                                                                class="form-control" name="jd_roles"
                                                                                id="jd_roles" required></textarea>
                                                                            (Please list as bullet points / Please
                                                                            mention language skill sets required):
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row" style="padding-top:10px;">
                                                                <div class="col-sm-3 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="">Qualification: <b
                                                                                style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                            <input type="text" name="qualification"
                                                                                id="qualification" class="form-control"
                                                                                required>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="">Essential Skill sets: <b
                                                                                style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                            <textarea rows="3" cols="3"
                                                                                class="form-control"
                                                                                name="essential_skill"
                                                                                id="essential_skill"
                                                                                required></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="">Good to have Skill sets (if
                                                                            any):</label>
                                                                        <div class="form-group">
                                                                            <textarea rows="3" cols="3"
                                                                                class="form-control" name="good_skill"
                                                                                id="good_skill"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="">Attendance Format:</label>
                                                                        <div class="form-group">
                                                                        <select name="attendance_format" id="attendance_format" class="form-control js-select2 "  ></select>

                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row" style="background:#eee; padding-top:10px;">
                                                                <div class="col-sm-3 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="">Week Off:</label>
                                                                        <div class="form-group">
                                                                        <select class="form-control js-select2 "  name="week_off" id="week_off"></select>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="">Experience (in yrs): <b
                                                                                style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                            <!-- <input type="text" name="experience" id="experience" class="form-control" required > -->
                                                                            <select name="experience" id="experience"
                                                                                class="form-control" required>
                                                                                <option value="">Select</option>
                                                                                <option value="less than 1">less than 1
                                                                                </option>
                                                                                <option value="Fresher">Fresher</option>
                                                                                <option value="1-2">1-2</option>
                                                                                <option value="2-3">2-3</option>
                                                                                <option value="3-4">3-4</option>
                                                                                <option value="4-5">4-5</option>
                                                                                <option value="5-6">5-6</option>
                                                                                <option value="6-7">6-7</option>
                                                                                <option value="7-8">7-8</option>
                                                                                <option value="8-9">8-9</option>
                                                                                <option value="9-10">9-10</option>
                                                                                <option value="10-15">10-15</option>
                                                                                <option value="15-20">15-20</option>
                                                                                <option value=">20">&gt;20</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="">Maximum CTC(Per Month): <b
                                                                                style="color: red;">*</b></label>
                                                                        <div class="form-group">
                                                                            <input type="text" name="salary_range"
                                                                                id="salary_range" class="form-control"
                                                                                required>
                                                                            <!-- <select name="salary_range" id="salary_range" class="form-control" required>
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
                                                                <div class="col-sm-3 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="">Any other specific
                                                                            consideration(Add cvs Share to):</label>
                                                                        <div class="form-group">
                                                                            <textarea rows="3" cols="3"
                                                                                class="form-control" name="any_specific"
                                                                                id="any_specific"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="">CKPL Reporting Manager (only for People Outsourcing):</label>
                                                                        <div class="form-group">
                                                                           <textarea rows="3" cols="3" class="form-control" id="ck_supervisior"  name="ck_supervisior" ></textarea>

                                                                        </div>

                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 mobile-inputs">
                                                                    <div class="form-group">
                                                                        <label for="">CKPL Reporting Manager's Email ID:</label>
                                                                        <div class="form-group">
                                                                        <input type="text" name="ck_mail" id="ck_supervisior_mail" class="form-control">

                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12 mobile-inputs"
                                                                style="text-align:center;">
                                                                <br>
                                                                <a href="" id="export_link_id"><button type="button" class="btn btn-success"
                                                                    style="margin-top: 7px;">Export</button></a>

                                                                <button type="submit" class="btn btn-primary"
                                                                    style="margin-top: 7px;">Submit</button>
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
    {{-- <script src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js'></script> --}}
    <script src=' https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js'></script>
    <script src="../assets/pro_js/edit_recruit_request.js"></script>

    <script type="text/javascript">
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

    $(document).ready(function() {
        var page = "edit_recruit_request";

        if (page == "edit_recruit_request") {
            $(".recruit_req_m").addClass("active");
        }

    });

    var get_recruitment_edit_details_new_link = "{{url('get_recruitment_edit_details_new')}}";
    var get_band_details_link = "{{url('get_band_details')}}";
    var reqcruitment_request_editprocess_link = "{{url('reqcruitment_request_editprocess')}}";
    var reqcruitment_request_editprocess_new_link = "{{url('reqcruitment_request_editprocess_new')}}";

    var get_raisedby_list_link = "{{url('get_raisedby_list')}}";
      jQuery(".select2").select2({
                    width: '100%',

                });

    </script>
</body>

</html>
