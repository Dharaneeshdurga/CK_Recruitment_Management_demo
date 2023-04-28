<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - {{ $siteTitle }}</title>
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
                            <h3>Change Password</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item active" aria-current="page">Change Password</li>
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
                                        <form class="form" id="changePassForm" method="post"
                                            action="javascript:void(0)">

                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="New Password">New Password </label>
                                                        <input type="text" id="new_password" class="form-control"
                                                            placeholder="New Password" name="new_password" required />


                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">

                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="Confirm Password">Confirm Password</label>
                                                        <input type="text" id="confirm_password" class="form-control"
                                                            placeholder="Confirm Password" name="confirm_password"
                                                            required />
                                                        <br>
                                                        <span id="err_message"></span>

                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">

                                                </div>
                                                <div class="col-6 d-flex">

                                                    <button type="submit" class="btn btn-primary me-1 mb-1"
                                                        id="btnSubmit">Submit</button>
                                                    <!-- <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button> -->
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
    <script src="../assets/pro_js/change_password.js"></script>


    <script type="text/javascript">
    $(document).ready(function() {
        var page = "change_password";

        if (page == "change_password") {
            $(".change_password_m").addClass("active");
        }

    });

    var change_password_process_link = "{{url('change_password_process')}}";

    $('#confirm_password').on('keyup', function() {
        if ($('#new_password').val() == $('#confirm_password').val()) {
            $('#err_message').html('Matching..!').css('color', 'green');
            $('#btnSubmit').prop("disabled", false);

        } else {
            $('#err_message').html('Not Matching..!').css('color', 'red');
            $('#btnSubmit').attr("disabled", 'disabled');

        }
    });

    $('#new_password').on('keyup', function() {

        if ($('#confirm_password').val() != '') {
            if ($('#new_password').val() == $('#confirm_password').val()) {
                $('#err_message').html('Matching..!').css('color', 'green');
                $('#btnSubmit').prop("disabled", false);

            } else {
                $('#err_message').html('Not Matching..!').css('color', 'red');
                $('#btnSubmit').attr("disabled", 'disabled');

            }
        }
    });
    </script>
</body>

</html>