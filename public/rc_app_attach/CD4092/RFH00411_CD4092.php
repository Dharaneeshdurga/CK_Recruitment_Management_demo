<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Recruiter - {{ $siteTitle }}</title>
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
                            <h3>Add User</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="view_recruiter">View User</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Add User</li>
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
                                        <form class="form" id="addRecruiterForm" method="post"
                                            action="javascript:void(0)">

                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="EmpID">EmpID </label>
                                                        <input type="text" id="empID" class="form-control"
                                                            placeholder="EmpID" name="empID" required />
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="Name">Name </label>
                                                        <input type="text" id="emp_name" class="form-control"
                                                            placeholder="Name" name="emp_name" required />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="Designation">Designation</label>
                                                        <select id="designation" class="form-control"
                                                            placeholder="Designation" name="designation" required>
                                                            <option value="">Select</option>
                                                            <option value="Backend Coordinator">Backend Coordinator
                                                            </option>
                                                            <option value="Recruiter">Recruiter</option>
                                                            <option value="Virtual Audit">Virtual Audit</option>
                                                        </select>
                                                        <!-- <input type="text" id="designation" class="form-control" placeholder="Designation" name="designation" required/> -->
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="Email">Email</label>
                                                        <input type="email" id="email" class="form-control"
                                                            placeholder="Email" name="email" required />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12" id="team_div">
                                                    <div class="form-group">
                                                        <label for="team">Team</label>
                                                        <select name="team" id="team" class="form-control" required>
                                                            <option value="">Select</option>
                                                            <option value="CKPL">CKPL</option>
                                                            <option value="HEPL">HEPL</option>
                                                            <option value="RPO">RPO</option>
                                                        </select>
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

    <script src="../assets/pro_js/add_recruiter.js"></script>

    <script type="text/javascript">
    $(document).ready(function() {
        var page = "view_recruiter";

        if (page == "view_recruiter") {
            $(".view_recruiter_m").addClass("active");
        }

    });

    var add_recruiter_process_link = "{{url('add_recruiter_process')}}";
    </script>
</body>

</html>