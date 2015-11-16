<?php
date_default_timezone_set("Asia/Dhaka");
require 'admin/db.php';
include_once("admin/check_login_status.php");
include_once 'functions.php';
$loginLink = '<a href="login.php">Log In</a> &nbsp; | &nbsp; <a href="signup.php">Sign Up</a>';
if ($user_ok == true) {
    $loginLink = '<a href="user.php?u=' . $log_username . '">' . $log_username . '</a> | <a href="logout.php">Log Out</a>';    
}else{
    header("location: login.php");
    exit();
}
$loginLink = '<a href="login.php">Log In</a> &nbsp; | &nbsp; <a href="signup.php">Sign Up</a>';
if ($user_ok == true) {
    $loginLink = '<a href="user.php?u=' . $log_username . '">' . $log_username . '</a> | <a href="logout.php">Log Out</a>';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Sitename | Add Contest</title>
        <link rel="icon" href="favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/bootstrap.dark.css">
        <link rel="stylesheet" href="css/datepicker.css">
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">

    </head>
    <body>

        <?php include_once 'nav.php';?>  

        <div class="container" style="margin-top:50px;">
            <div class="col-lg-3">
                <?php if ($user_ok == true) { 
                    $userlavel = getUserField('userlevel',$userId);
                    echo '<div class="bs-example">';
                    echo '<ul class="nav nav-pills nav-stacked" style="max-width: 300px;">';
                    if ($userlavel == 'c'){
                        echo '<li class="active"><a href="dashboard.php">Dashboard</a></li>';
                        echo '<li><a href="add-video.php">Add Video</a></li>';
                    }elseif ($userlavel == 'b'){   
                        echo '<li class="active"><a href="index.php">Dashboard</a></li>';
                        echo '<li><a href="add-video.php">Add Video</a></li>';
                        echo '<li class="divider"></li>';
                        echo '<li><a href="add-contest.php">Add Contest</a></li>';
                        echo '<li><a href="publish-video.php">Publish Video</a></li>';
                    } 
                    echo '</ul>';
                    echo '</div>';
                }
                ?>                
            </div>
                        
            <div class="col-lg-9">     
                <h2>Contest List</h2>
            </div>

        </div>   

        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.js"></script>
        <script src="js/plugins.js"></script>
        <script src="js/ajax.js"></script>
        <script src="js/main.js"></script>
               
    </body>
</html>