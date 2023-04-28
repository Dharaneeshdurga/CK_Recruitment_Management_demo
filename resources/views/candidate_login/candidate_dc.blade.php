<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Dashboard | Pro Hire</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/vendors/choices.js/choices.min.css" />
    <link rel="stylesheet" href="../../assets/vendors/toastify/toastify.css">

    <link rel="stylesheet" href="../../assets/css/bootstrap.css">

    <link rel="stylesheet" href="../../assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="../../assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/app.css">
    <link href="../../assets/sweet-alert/sweet-alert.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css"
        rel="stylesheet">
    <link rel="shortcut icon" href="../../assets/images/favicon.svg" type="image/x-icon">
    <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script type="text/javascript">
    // window.alert = function() {};
    // var defaultCSS = document.getElementById('bootstrap-css');

    // function changeCSS(css) {
    //     if (css) $('head > link').filter(':first').replaceWith('<link rel="stylesheet" href="' + css +
    //         '" type="text/css" />');
    //     else $('head > link').filter(':first').replaceWith(defaultCSS);
    // }
    // $(document).ready(function() {
    //     var iframe_height = parseInt($('html').height());
    //     window.parent.postMessage(iframe_height, 'https://bootsnipp.com');
    // });
    </script>
    <style>
    #main {
        margin-left: unset !important;
    }

    .card_header {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, .125);
        /* border-radius: 0.7rem; */
        margin-bottom: 2.2rem;
        border: none;
    }

    /* .choices__list--single{
        padding: unset;
    } */
    .choices__list--dropdown .choices__list {
        max-height: 250px !important;
    }

    [class^="bi-"]::before,
    [class*=" bi-"]::before {
        vertical-align: middle;
    }

    .btn-group-sm>.btn,
    .btn-smn {
        padding: 0.13rem 0.5rem !important;
    }
    </style>
</head>

<body>
    <div id="app">

        <div id="main" class='layout-navbar'>
            <header class='mb-3 card_header'>
                <nav class="navbar navbar-expand navbar-light ">
                    <div class="container-fluid">

                        <img src="../../assets/images/logo/logo.jpg" alt="Logo" srcset="" style="width: 17%;">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                            </ul>
                            <div class="dropdown">
                                <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user-menu d-flex">
                                        <div class="user-name text-end me-3">
                                        </div>
                                        <div class="user-img d-flex align-items-center">
                                            <div class="avatar avatar-md">
                                                <img src="../../assets/images/faces/1.jpg">
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">

                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>
            <div id="main-content">

                <div class="page-heading">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last">
                                <h3>Candidate Dashboard</h3>
                                <p></p>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-xl-3">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-xl">
                                            <img src="../../assets/images/faces/1.jpg" alt="Face 1">
                                        </div>
                                        <div class="ms-3 name">
                                            <h5 class="font-bold" id="show_candidate_name">
                                                </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                        aria-orientation="vertical">
                                        <a class="nav-link active" id="v-pills-cbd-tab" data-bs-toggle="pill"
                                            href="#v-pills-cbd" role="tab" aria-controls="v-pills-cbd"
                                            aria-selected="true">Basic Details</a>
                                        <a class="nav-link" id="v-pills-edu-tab" data-bs-toggle="pill"
                                            href="#v-pills-edu" role="tab" aria-controls="v-pills-edu"
                                            aria-selected="false">Education</a>
                                        <a class="nav-link" id="v-pills-exp-tab" data-bs-toggle="pill"
                                            href="#v-pills-exp" role="tab" aria-controls="v-pills-exp"
                                            aria-selected="false" >Experience</a>

                                        <a class="nav-link" id="v-pills-compensation-tab" data-bs-toggle="pill"
                                            href="#v-pills-compensation" role="tab" aria-controls="v-pills-compensation"
                                            aria-selected="false">Proof of Compensation & Benefits Received</a>

                                        <a class="nav-link" id="v-pills-proofdoc-tab" data-bs-toggle="pill"
                                            href="#v-pills-proofdoc" role="tab" aria-controls="v-pills-proofdoc"
                                            aria-selected="false">Other Proof Documents</a>


                                        <a class="nav-link" id="v-pills-orel-tab" data-bs-toggle="pill"
                                            href="#v-pills-orel" role="tab" aria-controls="v-pills-orel"
                                            aria-selected="false" style="display:none;">Offer Released</a>


                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-xl-9">

                            <div class="card">

                                <div class="card-body">
                                <input type="hidden" name="candidate_id" id="candidate_id">
                                <input type="hidden" name="rfh_no" id="rfh_no">
                                <input type="hidden" name="hepl_recruitment_ref_number" id="hepl_recruitment_ref_number">
                                <input type="hidden" name="candidate_type_cmn" id="candidate_type_cmn">

                                    <div class="tab-content" id="v-pills-tabContent">
                                        <div class="tab-pane fade show active" id="v-pills-cbd" role="tabpanel"
                                            aria-labelledby="v-pills-cbd-tab">



                                        </div>
                                        <div class="tab-pane fade" id="v-pills-edu" role="tabpanel"
                                            aria-labelledby="v-pills-edu-tab">



                                        </div>

                                        <div class="tab-pane fade" id="v-pills-exp" role="tabpanel"
                                            aria-labelledby="v-pills-exp-tab">


                                        </div>

                                        <div class="tab-pane fade" id="v-pills-compensation" role="tabpanel"
                                            aria-labelledby="v-pills-compensation-tab">

                                        </div>

                                        <div class="tab-pane fade" id="v-pills-proofdoc" role="tabpanel"
                                            aria-labelledby="v-pills-proofdoc-tab">
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Tax Entity Proof</label>
                                                        <small class="text-muted">Eg.<i>(PAN CARD bearing Name, PAN
                                                                Number details)</i></small>
                                                        <input class="form-control form-control-sm" type="file"
                                                            id="tax_proof" name="tax_proof">

                                                    </div>
                                                    <div class="form-group">
                                                        <label for="">Proof Of Relieving</label>
                                                        <small class="text-muted">Eg.<i>(Relieving Letter/such
                                                                documentation from Current/Latest
                                                                Employer.)</i></small>

                                                        <input class="form-control form-control-sm" type="file"
                                                            id="proof_of_relieving" name="proof_of_relieving">

                                                    </div>
                                                    <div class="form-group">
                                                        <label for="">Proof Of Vaccination</label>
                                                        <small class="text-muted">Eg.<i>(Final Vaccination
                                                                Certificate as downloaded from Govt
                                                                Portal)</i></small>

                                                        <input class="form-control form-control-sm" type="file"
                                                            id="proof_of_vaccine" name="proof_of_vaccine">

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Proof Of Date Of Birth</label>
                                                        <small class="text-muted">Eg.<i>(Birth Certificate| SSLC
                                                                Marksheet | PAN Card etc)</i></small>

                                                        <input class="form-control form-control-sm" type="file"
                                                            id="proof_of_dob" name="proof_of_dob">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="">Proof Of Blood Group</label>
                                                        <small class="text-muted">Eg.<i>(Medical Certificate or
                                                                Blood Donation Card bearing details)</i></small>

                                                        <input class="form-control form-control-sm" type="file"
                                                            id="proof_of_bg" name="proof_of_bg">

                                                    </div>
                                                    <div class="form-group">
                                                        <label for="">Proof Of Bank Account</label>
                                                        <small class="text-muted">Eg.<i>(Cancelled CHECK LEAF | Bank
                                                                Passbook Photo Identity Page with Account
                                                                Details)</i></small>

                                                        <input class="form-control form-control-sm" type="file"
                                                            id="proof_of_ba" name="proof_of_ba">

                                                    </div>
                                                </div>
                                                <div class="col-md-12">

                                                    <div style="text-align:center;display:none;">
                                                        <button type="submit" class="btn btn-info"
                                                            id="docSubmitbtn">Submit</button>
                                                    </div>
                                                    <div style="text-align:center;">
                                                        <button type="submit" class="btn btn-info"
                                                            id="docSubmitbtn">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="v-pills-orel" role="tabpanel"
                                            aria-labelledby="v-pills-orel-tab">

                                            <a href="../../"
                                                target="_blank">
                                                <button type="button" class="btn btn-sm btn-smn btn-primary">Preview
                                                    Offer Letter</button>
                                            </a>
                                            <br>
                                            <br>
                                            <h4>Terms & Conditions</h4>
                                            <ul>
                                                <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
                                                <li>Aliquam tincidunt mauris eu risus.</li>
                                                <li>Vestibulum auctor dapibus neque.</li>
                                                <li>Nunc dignissim risus id metus.</li>

                                            </ul>

                                            <!-- <form class="form" id="Form" method="post" action="javascript:void(0)"> -->
                                            <div class="row">
                                                <div class="col-md-8 offset-md-2">
                                                    <div class="form-check">
                                                        <div class="checkbox">
                                                            <input type="checkbox" id="checkbox1"
                                                                class="form-check-input" name="confirm_box" checked=""
                                                                required>
                                                            <label for="checkbox1">I acknowledge that i have read
                                                                and agree
                                                                to the above terms and conditions</label>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                            <div class="row">

                                                <div class="col-md-4 offset-md-4">

                                                    <div class="form-group">

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-check form-check-success">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="offer_action" id="offer_st1" checked=""
                                                                        value="2">
                                                                    <label class="form-check-label" for="offer_st1">
                                                                        Accept
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-check form-check-danger">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="offer_action" id="offer_st1" value="3">
                                                                    <label class="form-check-label" for="offer_st2">
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col-md-6 offset-md-5">
                                                    <div class="form-group">
                                                        <span id="err_span" style="display:none;">Please Agree Terms
                                                            & Conditions</span>

                                                        <button class="btn btn-sm btn-smn btn-info" type="button"
                                                            id="offerFormbtn">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- </form> -->
                                            <!-- <embed src=""  type="application/pdf"   height="500px" width="100%"> -->
                                        </div>
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
    </div>
    <script src="../../assets/sweet-alert/sweet-alert.min.js"></script>
    <script src="../../assets/sweet-alert/sweet-alert.init.js"></script>
    <script src="../../assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/pro_js/candidate_dc.js"></script>
    <script src="../../assets/vendors/choices.js/choices.min.js"></script>
    <script src="../../assets/vendors/toastify/toastify.js"></script>

    {{-- <script src="../../assets/js/main.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js">
    </script>
    <script>
    var get_candidate_details_exist_link = "{{url('get_candidate_details_exist')}}";
    var candidate_basic_document_link = "{{url('candidate_basic_doc_upload')}}";

    var candidate_edu_document_link = "{{url('candidate_edu_document')}}";
    var remove_education_fields_exist_link = "{{url('remove_education_fields_exist')}}";

    var candidate_exp_document_link = "{{url('candidate_exp_document')}}";
    var remove_experience_fields_exist_link = "{{url('remove_experience_fields_exist')}}";

    var candidate_benefit_document_link = "{{url('candidate_benefit_document')}}";
    var remove_compensation_fields_exist_link = "{{url('remove_compensation_fields_exist')}}";

    var candidate_proof_document_link = "{{url('candidate_proof_document')}}";

    var candidate_document_link = "{{url('candidate_document_upload')}}";
    var offer_response_candidate_link = "{{url('offer_response_candidate')}}";
    var offer_reject_candidate_link = "{{url('update_reject_status')}}";

var test_link = "{{url('test')}}";

    var room = 1;
    var room_exf = 1;
    var room_com = 1;

    function education_fields() {

        room++;
        var objTo = document.getElementById('education_fields')
        var divtest = document.createElement("div");
        divtest.setAttribute("class", "form-group removeclass" + room);
        var rdiv = 'removeclass' + room;
        divtest.innerHTML ='<div class="row"><input type="hidden" name="c_edu_row_id[]" id="c_edu_row_id" value=""><div class="col-md-6"><div class="form-group"><label for="">Qualification<code>*</code></label><input type="text" class="form-control form-control-sm" id="degree" name="degree[]" value="" placeholder="" required></div><div class="form-group"> <div class="row"> <div class="col-md-6"> <label for="">From <code>*</code></label> <input type="text" class="form-control datepicker" name="edu_start_month[]" id="edu_start_month" required value="@php echo date('M-Y'); @endphp" /></div> <div class="col-md-6"> <label for="">To <code>*</code></label> <input type="text" class="form-control datepicker" name="edu_end_month[]" id="edu_end_month" required value="@php echo date('M-Y'); @endphp" /></div></div></div></div><div class="col-md-6"><div class="form-group"> <label for="">School / University <code>*</code></label> <input type="text" class="form-control form-control-sm" id="university" name="university[]" value="" placeholder="" required> </div><div class="form-group"> <div class="row"><div class="col-md-9"><label for="">Certificate Upload <code>*</code></label><input class="form-control form-control-sm" type="file" id="edu_certificate" name="edu_certificate[]" required></div><div class="col-md-3"><label for=""></label><div class="input-group-btn"><button class="btn btn-sm btn-smn btn-danger" type="button" onclick="remove_education_fields(' + room + ');"><i class="bi bi-trash"> </i> Remove</button></div></div></div></div></div><div class="clear"></div></div><div class="clear"></div><hr>';

        objTo.appendChild(divtest);
    get_datepicker();

    }

    function remove_education_fields(rid) {
        $('.removeclass' + rid).remove();
    }

    function remove_education_fields_exist(rid,cdID){
        $('.removeclass_st' + rid).remove();

        $.ajax({
            type: "POST",
            url: remove_education_fields_exist_link,
            data: {"id":rid, },

            success: function (data) {

                if(data =='success'){
                    Toastify({
                            text: "Deleted Successfully",
                            duration: 3000,
                            close:true,
                            backgroundColor: "#dc3545",
                        }).showToast();

                        setTimeout(
                            function() {
                                get_candidate_details_exist(cdID)
                                // $("#v-pills-tabContent").load(location.href + " #v-pills-tabContent");
                                document.getElementById('v-pills-edu-tab').click(); // Works!
                                return false;

                            }, 2000);

                }else{
                    Toastify({
                        text: "Request Failed..! Try Again",
                        duration: 3000,
                        close:true,
                        backgroundColor: "#f3616d",
                    }).showToast();

                    setTimeout(
                        function() {
                            document.getElementById('v-pills-edu-tab').click(); // Works!

                        }, 2000);

                }
            }
        });


    }

    function remove_experience_fields_exist(rid,cdID){
        $('.removeclass_est' + rid).remove();

        $.ajax({
            type: "POST",
            url: remove_experience_fields_exist_link,
            data: {"id":rid, },

            success: function (data) {

                if(data =='success'){
                    Toastify({
                            text: "Deleted Successfully",
                            duration: 3000,
                            close:true,
                            backgroundColor: "#dc3545",
                        }).showToast();

                        setTimeout(
                            function() {
                                get_candidate_details_exist(cdID)
                                // $("#v-pills-tabContent").load(location.href + " #v-pills-tabContent");
                                document.getElementById('v-pills-exp-tab').click(); // Works!

                            }, 2000);

                }else{
                    Toastify({
                        text: "Request Failed..! Try Again",
                        duration: 3000,
                        close:true,
                        backgroundColor: "#f3616d",
                    }).showToast();

                    setTimeout(
                        function() {
                            document.getElementById('v-pills-exp-tab').click(); // Works!


                        }, 2000);

                }
            }
        });


    }

    function experience_fields() {

        room_exf++;
        var objTo = document.getElementById('experience_fields')
        var divtest = document.createElement("div");
        divtest.setAttribute("class", "form-group removeclass_exf" + room_exf);
        var rdiv = 'removeclass_exf' + room_exf;

        // divtest.innerHTML ='<div class="row"><div class="col-md-6"><div class="form-group"><label for="">Job Title <code>*</code></label><input type="text" class="form-control form-control-sm" id="job_title" name="job_title[]" value="" placeholder="" required></div><div class="form-group"> <div class="row"> <label for="">Start Date <code>*</code></label> <div class="col-md-6"> <select class="form-control form-control-sm cur_month" name="exp_start_month[]" id="exp_start_month" required> <option value="">Select</option> <option value="January">January</option> <option value="February">February</option> <option value="March">March</option> <option value="April">April</option> <option value="May">May</option> <option value="June">June</option> <option value="July">July</option> <option value="August">August</option> <option value="September">September</option> <option value="October">October</option> <option value="November">November</option> <option value="December">December</option> </select> </div><div class="col-md-6"> <select class="form-control form-control-sm cur_year" name="exp_start_year[]" id="exp_start_year" required> <option value="">Select</option> <option value="2030">2030</option> <option value="2029">2029</option> <option value="2028">2028</option> <option value="2027">2027</option> <option value="2026">2026</option> <option value="2025">2025</option> <option value="2024">2024</option> <option value="2023">2023</option> <option value="2022">2022</option> <option value="2021">2021</option> <option value="2020">2020</option> <option value="2019">2019</option> <option value="2018">2018</option> <option value="2017">2017</option> <option value="2016">2016</option> <option value="2015">2015</option> <option value="2014">2014</option> <option value="2013">2013</option> <option value="2012">2012</option> <option value="2011">2011</option> <option value="2010">2010</option> <option value="2009">2009</option> <option value="2008">2008</option> <option value="2007">2007</option> <option value="2006">2006</option> <option value="2005">2005</option> <option value="2004">2004</option> <option value="2003">2003</option> <option value="2002">2002</option> <option value="2001">2001</option> <option value="2000">2000</option> <option value="1999">1999</option> <option value="1998">1998</option> <option value="1997">1997</option> <option value="1996">1996</option> <option value="1995">1995</option> <option value="1994">1994</option> <option value="1993">1993</option> <option value="1992">1992</option> <option value="1991">1991</option> <option value="1990">1990</option> <option value="1989">1989</option> <option value="1988">1988</option> <option value="1987">1987</option> <option value="1986">1986</option> <option value="1985">1985</option> <option value="1984">1984</option> <option value="1983">1983</option> <option value="1982">1982</option> <option value="1981">1981</option> <option value="1980">1980</option> <option value="1979">1979</option> <option value="1978">1978</option> <option value="1977">1977</option> <option value="1976">1976</option> <option value="1975">1975</option> <option value="1974">1974</option> <option value="1973">1973</option> <option value="1972">1972</option> <option value="1971">1971</option> <option value="1970">1970</option> <option value="1969">1969</option> <option value="1968">1968</option> <option value="1967">1967</option> <option value="1966">1966</option> <option value="1965">1965</option> <option value="1964">1964</option> <option value="1963">1963</option> <option value="1962">1962</option> <option value="1961">1961</option> <option value="1960">1960</option> </select> </div></div></div><div class="form-group"> <label for="">Certificate Upload <code>*</code></label> <input class="form-control form-control-sm" type="file" id="exp_certificate" name="exp_certificate[]" required> </div></div><div class="col-md-6"> <div class="form-group"> <label for="">Company Name <code>*</code></label> <input type="text" class="form-control form-control-sm" id="company_name" name="company_name[]" value="" placeholder="" required> </div><div class="form-group"> <div class="row"> <label for="">To Date <code>*</code></label> <div class="col-md-6"> <select class="form-control form-control-sm cur_month" name="exp_end_month[]" id="exp_end_month" required> <option value="">Select</option> <option value="January">January</option> <option value="February">February</option> <option value="March">March</option> <option value="April">April</option> <option value="May">May</option> <option value="June">June</option> <option value="July">July</option> <option value="August">August</option> <option value="September">September</option> <option value="October">October</option> <option value="November">November</option> <option value="December">December</option> </select> </div><div class="col-md-6"> <select class="form-control form-control-sm cur_year" name="exp_end_year[]" id="exp_end_year" required> <option value="">Select</option> <option value="2030">2030</option> <option value="2029">2029</option> <option value="2028">2028</option> <option value="2027">2027</option> <option value="2026">2026</option> <option value="2025">2025</option> <option value="2024">2024</option> <option value="2023">2023</option> <option value="2022">2022</option> <option value="2021">2021</option> <option value="2020">2020</option> <option value="2019">2019</option> <option value="2018">2018</option> <option value="2017">2017</option> <option value="2016">2016</option> <option value="2015">2015</option> <option value="2014">2014</option> <option value="2013">2013</option> <option value="2012">2012</option> <option value="2011">2011</option> <option value="2010">2010</option> <option value="2009">2009</option> <option value="2008">2008</option> <option value="2007">2007</option> <option value="2006">2006</option> <option value="2005">2005</option> <option value="2004">2004</option> <option value="2003">2003</option> <option value="2002">2002</option> <option value="2001">2001</option> <option value="2000">2000</option> <option value="1999">1999</option> <option value="1998">1998</option> <option value="1997">1997</option> <option value="1996">1996</option> <option value="1995">1995</option> <option value="1994">1994</option> <option value="1993">1993</option> <option value="1992">1992</option> <option value="1991">1991</option> <option value="1990">1990</option> <option value="1989">1989</option> <option value="1988">1988</option> <option value="1987">1987</option> <option value="1986">1986</option> <option value="1985">1985</option> <option value="1984">1984</option> <option value="1983">1983</option> <option value="1982">1982</option> <option value="1981">1981</option> <option value="1980">1980</option> <option value="1979">1979</option> <option value="1978">1978</option> <option value="1977">1977</option> <option value="1976">1976</option> <option value="1975">1975</option> <option value="1974">1974</option> <option value="1973">1973</option> <option value="1972">1972</option> <option value="1971">1971</option> <option value="1970">1970</option> <option value="1969">1969</option> <option value="1968">1968</option> <option value="1967">1967</option> <option value="1966">1966</option> <option value="1965">1965</option> <option value="1964">1964</option> <option value="1963">1963</option> <option value="1962">1962</option> <option value="1961">1961</option> <option value="1960">1960</option> </select> </div></div></div><div class="form-group"> <label for="">Remove</label> <div class="input-group-btn"> <button class="btn btn-sm btn-danger" type="button" onclick="remove_experience_fields(' +room_exf + ');"> <i class="bi bi-trash"></i> </button> </div></div></div><div class="clear"></div>';

        divtest.innerHTML ='<div class="row"><input type="hidden" name="c_exp_row_id[]" id="c_exp_row_id" value=""><div class="col-md-6"><div class="form-group"><label for="">Job Title <code>*</code></label><input type="text" class="form-control form-control-sm" id="job_title" name="job_title[]" value="" placeholder="" required></div><div class="form-group"> <div class="row"> <div class="col-md-6"> <label for="">From <code>*</code></label> <input type="text" class="form-control datepicker" name="exp_start_month[]" id="exp_start_month" required value="@php echo date('M-Y'); @endphp" /></div> <div class="col-md-6"> <label for="">To <code>*</code></label> <input type="text" class="form-control datepicker" name="exp_end_month[]" id="exp_end_month" required value="@php echo date('M-Y'); @endphp" /></div></div></div></div><div class="col-md-6"><div class="form-group"> <label for="">Company Name <code>*</code></label> <input type="text" class="form-control form-control-sm" id="company_name" name="company_name[]" value="" placeholder="" required> </div><div class="form-group"> <div class="row"><div class="col-md-9"><label for="">Appoinment Letter <code>*</code></label><input class="form-control form-control-sm" type="file" id="exp_certificate" name="exp_certificate[]" required></div><div class="col-md-3"><label for=""></label><div class="input-group-btn"><button class="btn btn-sm btn-smn btn-danger" type="button" onclick="remove_experience_fields(' + room_exf + ');"><i class="bi bi-trash"> </i> Remove</button></div></div></div></div></div><div class="clear"></div></div><div class="clear"></div><hr>';

        objTo.appendChild(divtest);
        get_datepicker();

    }

    function remove_experience_fields(rid) {
        $('.removeclass_exf' + rid).remove();
    }

    function compensation_fields() {

        room_com++;
        var objTo = document.getElementById('compensation_fields')
        var divtest = document.createElement("div");
        divtest.setAttribute("class", "form-group removeclass_com" + room_com);
        var rdiv = 'removeclass_com' + room_com;
        divtest.innerHTML =
            '<div class="row"><input type="hidden" name="c_benefits_row_id[]" id="c_benefits_row_id" value=""><div class="col-md-6"> <div class="form-group"> <label for="">Document Type</label> <code>*</code> <input type="text" name="doc_type[]" id="doc_type" class="form-control form-control-sm" required> </div></div><div class="col-md-6"> <div class="row"> <div class="col-md-9"> <div class="form-group"> <label for=""></label> <input class="form-control form-control-sm" type="file" name="doc_type_file[]" id="doc_type_file" required>  </div></div><div class="col-md-3"> <div class="form-group" style="margin-bottom: unset;"> <label for=""></label> <div class="input-group-btn"> <button class="btn btn-sm btn-danger" type="button" onclick="remove_compensation_fields(' +
            room_com + ');"> <i class="bi bi-trash"></i>Remove</button> </div></div></div></div><div class="clear"></div>';

        objTo.appendChild(divtest)
    }

    function remove_compensation_fields(rid) {
        $('.removeclass_com' + rid).remove();
    }
    function remove_compensation_fields_exist(rid,cdID){
        $('.removeclass_bst' + rid).remove();

        $.ajax({
            type: "POST",
            url: remove_compensation_fields_exist_link,
            data: {"id":rid, },

            success: function (data) {

                if(data =='success'){
                    Toastify({
                            text: "Deleted Successfully",
                            duration: 3000,
                            close:true,
                            backgroundColor: "#dc3545",
                        }).showToast();

                        setTimeout(
                            function() {
                                get_candidate_details_exist(cdID)
                                // $("#v-pills-tabContent").load(location.href + " #v-pills-tabContent");
                                document.getElementById('v-pills-compensation-tab').click(); // Works!

                            }, 2000);

                }else{
                    Toastify({
                        text: "Request Failed..! Try Again",
                        duration: 3000,
                        close:true,
                        backgroundColor: "#f3616d",
                    }).showToast();

                    setTimeout(
                        function() {
                            document.getElementById('v-pills-compensation-tab').click(); // Works!


                        }, 2000);

                }
            }
        });


    }


    </script>
</body>

</html>
