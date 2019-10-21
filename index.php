<?php
<!DOCTYPE html>
<html lang="en">
<meta name="viewport" content="width=device-width, initial-scale=1">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

  <title>Powerstove Admin Portal</title>

	<!-- CSS -->
	<link rel="stylesheet" href="/app/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="/app/assets/css/vendor/icon-sets.css">
	<link rel="stylesheet" href="/app/assets/css/main.css">
	<!-- FOR DEMO PURPOSES ONLY. You should remove this in your project -->
	<link rel="stylesheet" href="/app/assets/css/demo.css">
	<!-- GOOGLE FONTS -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
	<!-- ICONS -->
	<link rel="icon" type="image/png" sizes="96x96" href="app/assets/img/company_img/powerstove-logo.png"> 

</head>
	<body>
    <!--Begin Wrapper-->
    <div id="wrapper">
    <!--Begin Hide Nav View-->
    <div id="hide-nav-view">
        <!--Begin sidebar Control-->
        <div id="side-activate">
            <!-- SIDEBAR -->
            <div class="sidebar">
                <div class="brand">
                    <a href="index.html"><img src="app/assets/img/logo.png" alt="Powerstove Logo" class="img-responsive logo"></a>
                </div>
                <div class="sidebar-scroll">
                    <nav>
                        <ul class="nav">
                            <li><a href="" class="" data-navigo id="home"><i class="lnr lnr-home"></i> <span>Dashboard</span></a></li>
                            <li><a href="#update-profile" class="" data-navigo id="update-profile"><i class="lnr lnr-code"></i> <span>Update profile</span></a></li>
                            <li><a href="#login" class="" data-navigo id="logout"><i class="lnr lnr-chart-bars"></i> <span>Logout</span></a></li>
                            <li><a href="#login" class="" data-navigo id="login"><i class="lnr lnr-cog"></i> <span>Login</span></a></li>
                            <li><a href="#signup" class="" data-navigo id="signup"><i class="lnr lnr-alarm"></i> <span>Signup</span></a></li>
                            <li>
                                <a href="#subPages" data-toggle="collapse" class="collapsed"><i class="lnr lnr-file-empty"></i> <span>Pages</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
                                <div id="subPages" class="collapse ">
                                    <ul class="nav">
                                        <li><a href="page-profile.html" class="">Profile</a></li>
                                        <li><a href="page-lockscreen.html" class="">Lockscreen</a></li>
                                        <li><a href="page-login.html" class="">Logout</a></li>
                                    </ul>
                                </div>
                            </li>
                            <!--<li><a href="tables.html" class=""><i class="lnr lnr-dice"></i> <span>Tables</span></a></li>
                            <li><a href="typography.html" class=""><i class="lnr lnr-text-format"></i> <span>Typography</span></a></li>
                            <li><a href="icons.html" class=""><i class="lnr lnr-linearicons"></i> <span>Icons</span></a></li>-->
                        </ul>
                    </nav>
                </div>
                <a class="footer" href="http://twitter.com/share?url=https://goo.gl/1dt1MR&amp;text=So cool! Free Bootstrap dashboard template by @thedevelovers. Download at&amp;hashtags=free,bootstrap,dashboard" title="Twitter share" target="_blank"><i class="fa fa-twitter"></i> <span>SHARE POWERSTOVE</span></a>
            </div>
            <!-- END SIDEBAR -->
        </div>
        <!--End Sidebar control-->
        <!--Begin Main-->
        <div class="main">
            <!--Begin Nav Activate-->
            <div id="nav-activate">
                <!-- NAVBAR -->
			    <nav class="navbar navbar-default">
                    <div class="container-fluid">
                        <div class="navbar-btn">
                            <button type="button" class="btn-toggle-fullwidth"><i class="lnr lnr-arrow-left-circle"></i></button>
                        </div>
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-menu">
                                <span class="sr-only">Toggle Navigation</span>
                                <i class="fa fa-bars icon-nav"></i>
                            </button>
                        </div>
                        <div id="navbar-menu" class="navbar-collapse collapse">
                            <form class="navbar-form navbar-left hidden-xs">
                                <div class="input-group">
                                    <input type="text" value="" class="form-control" placeholder="Search dashboard...">
                                    <span class="input-group-btn"><button type="button" class="btn btn-primary">Go</button></span>
                                </div>
                            </form>
                            <ul class="nav navbar-nav navbar-right">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle icon-menu" data-toggle="dropdown">
                                        <i class="lnr lnr-alarm"></i>
                                        <span class="badge bg-danger">5</span>
                                    </a>
                                    <ul class="dropdown-menu notifications">
                                        <li><a href="#" class="notification-item"><span class="dot bg-warning"></span>System space is almost full</a></li>
                                        <li><a href="#" class="notification-item"><span class="dot bg-danger"></span>You have 9 unfinished tasks</a></li>
                                        <li><a href="#" class="notification-item"><span class="dot bg-success"></span>Monthly report is available</a></li>
                                        <li><a href="#" class="notification-item"><span class="dot bg-warning"></span>Weekly meeting in 1 hour</a></li>
                                        <li><a href="#" class="notification-item"><span class="dot bg-success"></span>Your request has been approved</a></li>
                                        <li><a href="#" class="more">See all notifications</a></li>
                                    </ul>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="lnr lnr-question-circle"></i> <span>Help</span> <i class="icon-submenu lnr lnr-chevron-down"></i></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">Basic Use</a></li>
                                        <li><a href="#">Working With Data</a></li>
                                        <li><a href="#">Security</a></li>
                                        <li><a href="#">Troubleshooting</a></li>
                                    </ul>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="app/assets/img/user.png" class="img-circle" alt="Avatar"> <span>Samuel</span> <i class="icon-submenu lnr lnr-chevron-down"></i></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#"><i class="lnr lnr-user"></i> <span>My Profile</span></a></li>
                                        <li><a href="#"><i class="lnr lnr-envelope"></i> <span>Message</span></a></li>
                                        <li><a href="#"><i class="lnr lnr-cog"></i> <span>Settings</span></a></li>
                                        <li><a href="#"><i class="lnr lnr-exit"></i> <span>Logout</span></a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <!-- END NAVBAR -->
            </div>
            <!--End Nav control-->
            <!--Main Content will be injected here-->
            <div id="content">

            </div><br>
            <!-- END MAIN CONTENT -->
			<footer>
                <div class="container-fluid">
                    <p class="copyright"><div>&copy; 2016. Designed &amp; Crafted by</div> <a href="https://www.powerstove.com.ng">The Develovers</a></p>
                </div>
            </footer>
        </div>
        <!--End Main-->
    </div>
    <!--End hide Navs-->
    </div>
    <!--End Wrapper-->

    <!--Javascript-->
    <!-- jQuery & Bootstrap 4 JavaScript libraries -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>    
    <script src="app/app.js"></script>
    <script src=""></script>
    <!-- bootbox for confirm pop up -->
    <script src="app/assets/js/bootbox.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>        
	<script src="app/assets/js/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
	<script src="app/assets/js/plugins/jquery-easypiechart/jquery.easypiechart.min.js"></script>
	<script src="app/assets/js/plugins/chartist/chartist.min.js"></script>
    <script src="app/assets/js/klorofil.min.js"></script>
    <!--Model Scripts-->
    <script src="./app/models/default_model.js"></script>
    <script src="./app/models/auth/login_model.js"></script>
    <script src="./app/models/auth/update_profile_model.js"></script>
    <script src="./app/models/auth/signup_model.js"></script>
    <script src="./app/models/auth/forgot_password_model.js"></script>
    <script src="./app/models/auth/reset_password_model.js"></script>
    <script src="./app/models/auth/verify_account_model.js"></script>
    <!--ViewModel Scripts-->
    <script src="./app/viewmodels/default_vmodel.js"></script>
    <script src="./app/viewmodels/auth/login_vmodel.js"></script>
    <script src="./app/viewmodels/auth/update_profile_vmodel.js"></script>
    <script src="./app/viewmodels/auth/signup_vmodel.js"></script>
    <script src="./app/viewmodels/auth/forgot_password_vmodel.js"></script>
    <script src="./app/viewmodels/auth/reset_password_vmodel.js"></script>
    <script src="./app/viewmodels/auth/verify_account_vmodel.js"></script>
    <!--Library Scripts-->
    <script src="./app/lib/navigo.js"></script>
    <script src="./app/lib/router.js"></script>
    <script>
        router.updatePageLinks();
    </script>
</body>
</html>