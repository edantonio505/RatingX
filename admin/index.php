<?php
date_default_timezone_set("America/New_York");
include_once("check_login_status.php");
$loginLink = '<a href="logout.php">Log Out</a>';
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>MOBILFUN.EU</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <link rel="stylesheet" href="../css/bootstrap.css">
        <link rel="stylesheet" href="../css/normalize.css">
        <link rel="stylesheet" href="../css/main.css">

        <script src="../js/vendor/jquery-1.10.2.min.js"></script>
        <script src="../js/vendor/modernizr-2.6.2.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->

        <div id="fb-root"></div>
        <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>


        <div class="header">
            <h1>MOBILFUN.EU</h1>
        </div>
        <div class="full-page">
                         
            <div class="container">
                
                
                <div class="row">
                    <div class="col-lg-12">
                        <div class="pull-right">  
                        
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <?php if ($user_ok == true) { ?>
                            <div class="bs-example">
                                <ul class="nav nav-pills nav-stacked" style="max-width: 300px;">
                                    <li class="active"><a href="index.php">Dashboard</a></li>
                                    <li><a href="add-post.php">Add post</a></li>
                                    <li><a href="edit-post.php">Edit post</a></li>
                                    <li><a href="advertise.php">Advertise code</a></li>
                                </ul>
                            </div>
                        <?php 
                        
                        } else {
                            echo '<a href="login.php" title="login">Login</a>';
                        }
                        ?>
                    </div>
                    <div class="col-lg-9">
                        <h2>Dashboard</h2>
                        <?php
                        if ($user_ok == true) {
                            $log_username = preg_replace('#[^a-z0-9]#i', '', $_SESSION['username']);
                            echo $log_username;
                            echo $loginLink;
                        }
                        ?>
                    </div>
                    
                </div>
            </div>
            
            
 
            <footer class="row" style="margin: 0px;padding: 0px;margin-top: 30px;">
                <div class="col-lg-12 footerMoto">
                    <h3>Short description of your site </h3>
                    <p>Footer content goes here</p>
                </div>
                <div class="col-lg-4 footerSingleElement">
                    <h3>Short description of your site </h3>
                    <p>Just like the title says. Need to put a script on every product page in woo commerce. This would obviously be solved by just putting the code in 1 file only. Please add your skypename and we could talk further there...</p>
                </div>
                <div class="col-lg-4 footerSingleElement">
                    <h3>Popular photos</h3>
                </div>
                <div class="col-lg-4 footerSingleElement">
                    <h3>Social media</h3>
                </div>
                <div class="col-lg-12 copyrightNote">
                    copyright &copy; 2014 MOBILFUN.EU
                </div>
            </footer>    
        </div>


        <script src="js/vendor/1.10.2/jquery.min.js"></script>
        <script>
        window.jQuery || document.write('<script src="js/vendor/jquery-1.10.2.min.js"><\/script>')
        </script>
        <script src="js/bootstrap.js"></script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
        (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
                function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
        ga('create','UA-XXXXX-X');ga('send','pageview');
        </script>
    </body>
</html>