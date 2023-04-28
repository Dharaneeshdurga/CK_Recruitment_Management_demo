<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Recruit Request - {{ $siteTitle }}</title>
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
                                <!-- <div class="card-header">
										<h4 class="card-title">Multiple Column</h4>
										</div> -->
                                <div class="card-content">
                                    <div class="card-body">
                                        <form class="form" id="requestEditForm" method="post"
                                            action="javascript:void(0)">

                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="rfh_no">RFH No <span class="text-success">(From
                                                                Zoho)</span></label>
                                                        <input type="text" id="rfh_no" class="form-control"
                                                            placeholder="RFH No" name="rfh_no" readonly required />


                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="position_title">Position Title <span
                                                                class="text-success">(From Zoho)</span></label>
                                                        <input type="text" id="position_title" class="form-control"
                                                            placeholder="Position Title" name="position_title"
                                                            required />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="no_of_position">No. of Position</label>
                                                        <input type="text" id="no_of_position" class="form-control"
                                                            placeholder="No. of Position" name="no_of_position" readonly
                                                            required />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="band">Band</label>

                                                        <select name="band" id="band" class="form-control" required>
                                                            <option value="">Select</option>

                                                        </select>
                                                    </div>
                                                </div>


                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="open_date">Open date</label>
                                                        <input type="date" id="open_date" class="form-control"
                                                            placeholder="Open date" name="open_date"
                                                            value="@php echo date('Y-m-d');@endphp" required />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="critical_position">Critical Position</label>
                                                        <select name="critical_position" id="critical_position"
                                                            class=" form-control" required>
                                                            <option value="">Critical Position</option>
                                                            <option value="Yes">Yes</option>
                                                            <option value="No">No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="business">Buisness <span class="text-success">(From
                                                                Zoho)</span></label>
                                                        <select name="business" id="business" class=" form-control"
                                                            required>
                                                            <option value="">Buisness</option>
                                                            <option value="Buisness 1">Buisness 1</option>
                                                            <option value="Buisness 2">Buisness 2</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="division">Division <span class="text-success">(From
                                                                Zoho)</span></label>
                                                        <select name="division" id="division" class=" form-control"
                                                            required>
                                                            <option value="">Division</option>
                                                            <option value="Division 1">Division 1</option>
                                                            <option value="Division 2">Division 2</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="function">Function <span class="text-success">(From
                                                                Zoho)</span></label>
                                                        <input type="text" id="function" class="form-control"
                                                            name="function" placeholder="Function" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="location">Location <span class="text-success">(From
                                                                Zoho)</span></label>
                                                        <input type="text" id="location" class="form-control"
                                                            name="location" placeholder="Location" required />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="billing_status">Billing Status</label>
                                                        <select name="billing_status" id="billing_status"
                                                            class=" form-control" required>
                                                            <option value="">Billing Status</option>
                                                            <option value="Hiring For HEPL">Hiring For HEPL</option>
                                                            <option value="Hiring for Client"> Hiring for Client
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="interviewer">Interviewer <span
                                                                class="text-success">(From Zoho)</span></label>
                                                        <input type="text" id="interviewer" class="form-control"
                                                            name="interviewer" placeholder="Interviewer" required />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="salary_range">Maximum CTC(Per Month) <span
                                                                class="text-success">(From Zoho)</span></label>
                                                        <!-- <input type="text" id="salary_range" class="form-control"
																name="salary_range" placeholder="Maximum CTC(Per Month)"> -->

                                                        <select name="salary_range" id="salary_range"
                                                            class="form-control" required>
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
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12 d-flex justify-content-end">
                                                    <button type="submit" class="btn btn-primary me-1 mb-1"
                                                        id="btnUpdate">Update</button>
                                                    <button type="reset"
                                                        class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                                </div>
                                            </div>
                                        </form>

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
    <script src="../assets/pro_js/edit_recruit_request.js"></script>

    <script type="text/javascript">
    $(document).ready(function() {
        var page = "edit_recruit_request";

        if (page == "edit_recruit_request") {
            $(".recruit_req_m").addClass("active");
        }

    });

    var get_recruitment_edit_details_link = "{{url('get_recruitment_edit_details')}}";
    var get_band_details_link = "{{url('get_band_details')}}";
    var reqcruitment_request_editprocess_link = "{{url('reqcruitment_request_editprocess')}}";
    </script>
</body>

</html>