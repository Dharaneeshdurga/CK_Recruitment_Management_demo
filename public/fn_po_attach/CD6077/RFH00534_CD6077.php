<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Poco admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Poco admin template, dashboard template, flat admin template, responsive admin template, web app (Laravel 8)">
    <meta name="author" content="pixelstrap">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="../assets/images/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="../assets/images/favicon.png" type="image/x-icon">
    <title>BUDGIE</title>
    @include('layouts.simple.css')
    @yield('style')

  </head>
  <style type="text/css">
    
.accept {
  color: #fff;
  background: #44cc44;
  padding: 15px 20px;
  box-shadow: 0 4px 0 0 #2ea62e;
}
.accept:hover {
  background: #6fe76f;
  box-shadow: 0 4px 0 0 #7ed37e;
}
.deny {
  color: #fff;
  background: tomato;
  padding: 15px 20px;
  box-shadow: 0 4px 0 0 #cb4949;
}
.deny:hover {
  background: rgb(255, 147, 128);
  box-shadow: 0 4px 0 0 #ef8282;
}

  </style>
  <body>
    <!-- Loader starts-->
    <div class="loader-wrapper">
      <div class="typewriter">
        <h1>BUDGIE Loading...</h1>
      </div>
    </div>
    <!-- Loader ends-->
    @include('layouts.simple.header')
      <div class="container-fluid p-0 m-0">
         <div class="comingsoon comingsoon-bgimg">
            <div class="container-fluid">
               <div class="row">
                  <div class="col-sm-12">
                     <!-- <form method="POST" action="javascript:void(0)" id="pro_hire_accept_form" class="ajax-form" enctype="multipart/form-data"> -->
                        <center>
                      <a class="btn accept" id="ctc_prohire" target="_blank">Show PDF<span class="fa fa-check"></span></a>
                      <br>
                          <br>
                           <button class="btn accept" id="btn0" onclick="inFunction(this)" >ACCEPT<span class="fa fa-check"></span></button>
                           <button  class="btn deny" id="btn1" onclick="inFunction(this)" >DENY<span class="fa fa-close"></span></button>
                        </center>
                     <!-- </form> -->
                  </div>
               </div>
            </div>
         </div>
      </div>
    @include('layouts.simple.script')
  </body>
</html>
 <!-- custom js -->
   <script src="../assets/pro_js/pro_hire_accept.js"></script>

<footer class="footer">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-6 footer-copyright">
         <p class="mb-0">Copyright © {{ date('Y') }}  HRMS. All rights reserved.</p>
      </div>
      <div class="col-md-6">
        <p class="pull-right mb-0" style="margin-right: 83px;">Design & Developed by HEMAS <i class="fa fa-heart"></i></p>
      </div>
    </div>
  </div>
</footer>

