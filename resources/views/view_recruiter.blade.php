<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Recruiter- {{ $siteTitle }}</title>
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

    body,
    .table {
        color: #000000;
    }

    .dropdown-item.active,
    .dropdown-item:active {
        color: #000 !important;
    }

    .dataTables_length {
        margin-bottom: 10px;
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
                            <h3>View Users</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="add_recruiter">Add User</a></li>

                                    <li class="breadcrumb-item active" aria-current="page">View Users</li>
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
                                        <!-- confirmation popup start -->

                                        <!-- Button trigger for info theme modal -->
                                        <button type="button" id="btnConfirm" style="display:none;"
                                            class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#info">
                                            Info
                                        </button>
                                        <!--info theme Modal -->
                                        <div class="modal fade text-left" data-bs-backdrop="true" id="info"
                                            tabindex="-1" role="dialog" aria-labelledby="myModalLabel130"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
                                                role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-info">
                                                        <h5 class="modal-title white" id="myModalLabel130">
                                                            Info
                                                        </h5>
                                                        <button type="button" id="confirmClose" class="close"
                                                            data-bs-dismiss="modal" aria-label="Close">
                                                            <i class="bi bi-x-octagon-fill"></i>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure..! You want to Reset Password?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light-secondary"
                                                            data-bs-dismiss="modal">
                                                            <i class="bx bx-x d-block d-sm-none"></i>
                                                            <span class="d-none d-sm-block">Close</span>
                                                        </button>
                                                        <button type="button" id="btnConfirmsubmit"
                                                            class="btn btn-info ml-1" data-bs-dismiss="modal">
                                                            <i class="bx bx-check d-block d-sm-none"></i>
                                                            <span class="d-none d-sm-block">Accept</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- confirmation popup end -->

                                        <!-- recruiter delete popup start-->
                                        <button type="button" class="btn btn-outline-warning" id="confirmbox"
                                            data-bs-backdrop="true" style="display:none" data-bs-toggle="modal"
                                            data-bs-target="#delete_pop_modal_div">Delete Pop Modal</button>

                                        <div class="modal fade text-left" id="delete_pop_modal_div" tabindex="-1"
                                            role="dialog" aria-labelledby="show_delete_pop_title" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
                                                role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <h4 class="modal-title" id="show_edit_pop_title"></h4> -->
                                                        <button type="button" id="close_delete_pop" class="close"
                                                            data-bs-dismiss="modal" aria-label="Close">
                                                            <i data-feather="x"></i>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h6>Are you sure you want to Delete this Record?</h6>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger cancelbtn"
                                                            id="cancelSubmit">No</button>
                                                        <button type="button" class="btn btn-success deletebtn"
                                                            id="confirmSubmit">Yes</button>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- recruiter delete popup end-->

                                        <div class="modal fade text-left" id="edit_pop_modal_div" tabindex="-1"
                                            role="dialog" aria-labelledby="show_edit_pop_title" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
                                                role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title"> Edit Recruiter Details</h4>
                                                        <button type="button" id="close_edit_pop" class="close"
                                                            data-bs-dismiss="modal" aria-label="Close">
                                                            <i class="bi bi-x-circle-fill"></i>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">

                                                            <div class="col-md-2 col-12">
                                                            </div>
                                                            <div class="col-md-8 col-12">
                                                                <div class="form-group">
                                                                    <label for="emp_name">Name </label>
                                                                    <input type="text" id="ed_emp_name"
                                                                        class="form-control" placeholder="Name"
                                                                        name="ed_emp_name" />
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="designation">Designation</label>
                                                                    <input type="text" id="ed_designation"
                                                                        class="form-control" placeholder="Designation"
                                                                        name="ed_designation" />
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="email">Email</label>
                                                                    <input type="email" id="ed_email"
                                                                        class="form-control" placeholder="Email"
                                                                        name="ed_email" />
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="team" id="ed_team_label">Team</label>
                                                                    <select name="ed_team" id="ed_team"
                                                                        class="form-control">
                                                                        <option value="">Select</option>
                                                                        <option value="CKPL">CKPL</option>
                                                                        <option value="HEPL">HEPL</option>
                                                                        <option value="RPO">RPO</option>
                                                                    </select>
                                                                </div>
                                                                <input type="hidden" name="ed_empID" id="ed_empID">
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <!-- <button type="button" class="btn btn-danger cancelbtn" id="editCancel">Cancel</button> -->
                                                        <button type="button" class="btn btn-success deletebtn"
                                                            id="editUpdate">Update</button>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                                                <div class="col-lg-4">
                                                    <label for="">Add User</label>
                                                    <br>
                                                    <a href="add_recruiter"><button class="btn btn-primary"
                                                            title="Add User"><i class="bi bi-plus-circle"></i> Add
                                                            User</button></a>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <h5>No. of Records Found: <span id="total_res_show"></span></h5>

                                        <div class="table-responsive">
                                            <br>
                                            <table class="table" id="recriter_tb" cellspacing="0" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>S.No.</th>
                                                        <th>EmpID</th>
                                                        <th>Name</th>
                                                        <th>Designation</th>
                                                        <th>Email</th>
                                                        <th>Team</th>
                                                        <th>Action</th>

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
    <script src="../assets/pro_js/view_recruiter.js"></script>


    <script type="text/javascript">
    $(document).ready(function() {
        var page = "view_recruiter";

        if (page == "view_recruiter") {
            $(".view_recruiter_m").addClass("active");
        }

    });

    var get_recruiter_list_link = "{{url('get_recruiter_list')}}";
    var reset_password_link = "{{url('reset_password')}}";
    var process_recruiter_delete_link = "{{url('process_recruiter_delete')}}";
    var get_recruiter_details_link = "{{url('get_recruiter_details')}}";
    var update_recruiter_details_link = "{{url('update_recruiter_details')}}";
    </script>
</body>

</html>