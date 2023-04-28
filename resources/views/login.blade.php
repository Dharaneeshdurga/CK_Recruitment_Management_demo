<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.9,maximum-scale = 0.9, shrink-to-fit=no">
    <title>Login - {{ $siteTitle }}</title>
	<meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="stylesheet" href="assets/css/pages/auth.css">
    <link rel="stylesheet" href="assets/css/common_style.css">

    <link rel="stylesheet" href="assets/vendors/toastify/toastify.css">
    <link rel="stylesheet" href="assets/vendors/perfect-scrollbar/perfect-scrollbar.css">


</head>

<body>
    <div id="auth">

        <div class="row">
            <div class="col-md-4">
                <!-- <img src="assets/images/signup.jpg" alt="" style="width:100%"> -->
            </div>
            <div class="col-md-5">
                <div id="auth-left">
                    <!-- <div class="auth-logo">
                        <a href="index.html"><img src="assets/images/logo/logo.png" alt="Logo"></a>
                    </div> -->
                    <img style="margin-left:30px;" src="assets/images/logo/logo.jpg" alt="">
                    <!-- <h1 class="auth-title">Log in.</h1> -->
                    <!-- <div class="col-12 col-md-4">
                                            <button id="close" class="btn btn-outline-primary btn-block btn-lg">Close
                                                Button</button>
                                        </div> -->
                    <form id="loginForm" method="post" action="javascript:void(0)">
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" name="employee_id" id="employee_id" oninput="employeeid_valid();" class="form-control form-control-xl" placeholder="Employee ID">
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" name="login_password" id="login_password" oninput="password_valid();" class="form-control form-control-xl" placeholder="Password">
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>
                       
                        <button class="btn btn-primary btn-block btn-lg shadow-lg" id="btnLogin">Log in</button>
                    </form>
                   
                </div>
            </div>
            
        </div>

    </div>

    <script src="assets/jquery/jquery.min.js"></script>
    <script src="assets/pro_js/login.js"></script>

    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/vendors/toastify/toastify.js"></script>

    <script>
        $( document ).ready(function() {
            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
        });
        
		var login_check_process_link = "{{url('index.php/login_check_process')}}";

        
    </script>
</body>

</html>