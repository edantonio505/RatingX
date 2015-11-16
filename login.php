<?php
    include_once("admin/check_login_status.php");
    // If user is already logged in, header that weenis away
    if ($user_ok == true) {
        header("location: index.php?u=" . $_SESSION["username"]);
        exit();
    }
?>
<?php
// AJAX CALLS THIS LOGIN CODE TO EXECUTE
if (isset($_POST["e"])) {
    // CONNECT TO THE DATABASE
    include_once("admin/db_conx.php");
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES AND SANITIZE
    $e = mysqli_real_escape_string($db_conx, $_POST['e']);
    $p = md5($_POST['p']);
    // GET USER IP ADDRESS
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
    // FORM DATA ERROR HANDLING
    if ($e == "" || $p == "") {
        echo "login_failed";
        exit();
    } else {
        // END FORM DATA ERROR HANDLING
        $sql = "SELECT id, username, password FROM tblusers WHERE username='$e' AND activated='1' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $row = mysqli_fetch_row($query);
        $db_id = $row[0];
        $db_username = $row[1];
        $db_pass_str = $row[2];
        if ($p != $db_pass_str) {
            echo "login_failed";
            exit();
        } else {
            // CREATE THEIR SESSIONS AND COOKIES
            $_SESSION['userid'] = $db_id;
            $_SESSION['username'] = $db_username;
            $_SESSION['password'] = $db_pass_str;
            
            setcookie("id", $db_id, strtotime('+30 days'), "/", "", "", TRUE);
            setcookie("user", $db_username, strtotime('+30 days'), "/", "", "", TRUE);
            setcookie("pass", $db_pass_str, strtotime('+30 days'), "/", "", "", TRUE);
            
            // UPDATE THEIR "IP" AND "LASTLOGIN" FIELDS
            $sql = "UPDATE users SET ip='$ip', lastlogin=now() WHERE username='$db_username' LIMIT 1";
            $query = mysqli_query($db_conx, $sql);
            echo $db_username;
            exit();
        }
    }
    exit();
}
?>   	
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Sitename | Login</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <link rel="stylesheet" href="css/bootstrap.dark.css">
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="js/vendor/jquery-1.10.2.min.js"></script>
        <script src="js/vendor/modernizr-2.6.2.min.js"></script>
        <script src="js/main.js"></script>
        <script src="js/ajax.js"></script>
        <script>
            function emptyElement(x){
                _(x).innerHTML = "";
            }
            function login(){
                var e = _("email").value;
                var p = _("password").value;
                if(e == "" || p == ""){
                    _("status").innerHTML = "Fill out all of the form data";
                } else {
                    _("loginbtn").style.display = "none";
                    _("status").innerHTML = 'please wait ...';
                    var ajax = ajaxObj("POST", "login.php");
                    ajax.onreadystatechange = function() {
                        if(ajaxReturn(ajax) == true) {
                            if(ajax.responseText == "login_failed"){
                                _("status").innerHTML = "Login unsuccessful, please try again.";
                                _("loginbtn").style.display = "block";
                            } else {
                                window.location = "index.php?u="+ajax.responseText;
                            }
                        }
                    }
                    ajax.send("e="+e+"&p="+p);
                }
            }
        </script>        
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->

        <div class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <a href="index.php" class="navbar-brand">The Talent Showoff</a>
                    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="navbar-collapse collapse" id="navbar-main">
                    <ul class="nav navbar-nav">
                        <li><a href="index.php" id="themes">Home </a></li>                        
                    </ul>   
                    <ul class="nav navbar-nav pull-right">
                        <li><a href="login.php">Log In</a></li>
                        <li><a href="signup.php">Signup</a></li>
                    </ul>
                   
                </div>
            </div>
        </div>  
                         
            <div class="container" style="margin-top:50px;">                
                

                    <div class="col-lg-3">
                        
                    </div>
                    <div class="col-lg-9">
                            
                       <h2>User login</h2>

                        <!-- LOGIN FORM -->
                        <form id="loginform" onsubmit="return false;">
                            <div>Username:</div>
                            <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>    
                            <input style="width: 300px;" class="form-control" type="text" value="" id="email" onfocus="emptyElement('status')" maxlength="88">                       
                            </div>
                            <br>
                            <div>Password:</div>
                            <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
                            <input style="width: 300px;" class="form-control" type="password" value="" id="password" onfocus="emptyElement('status')" maxlength="100">
                            </div>
                            <br/>
                            <button id="loginbtn" class="btn btn-default button" onclick="login()">Log In <span class="glyphicon glyphicon-user"></span></button> 
                            <p id="status"></p>
                        </form>
                        
                    </div>

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
