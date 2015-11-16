<?php
session_start();
// If user is logged in, header them away
if (isset($_SESSION["username"])) {
    header("location: message.php?msg=NO to that weenis");
    exit();
}
?><?php
// Ajax calls this NAME CHECK code to execute
if (isset($_POST["usernamecheck"])) {
    include_once("admin/db_conx.php");
    $username = preg_replace('#[^a-z0-9]#i', '', $_POST['usernamecheck']);
    $sql = "SELECT id FROM tblusers WHERE username='$username' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $uname_check = mysqli_num_rows($query);
    if (strlen($username) < 3 || strlen($username) > 16) {
        echo '<strong style="color:#F00;">3 - 16 characters please</strong>';
        exit();
    }
    if (is_numeric($username[0])) {
        echo '<strong style="color:#F00;">Usernames must begin with a letter</strong>';
        exit();
    }
    if ($uname_check < 1) {
        echo '<strong style="color:#009900;">' . $username . ' is OK</strong>';
        exit();
    } else {
        echo '<strong style="color:#F00;">' . $username . ' is taken</strong>';
        exit();
    }
}
?><?php
// Ajax calls this REGISTRATION code to execute
if (isset($_POST["u"])) {
    // CONNECT TO THE DATABASE
    include_once("admin/db_conx.php");
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $u = preg_replace('#[^a-z0-9]#i', '', $_POST['u']);
    $e = mysqli_real_escape_string($db_conx, $_POST['e']);
    $p = $_POST['p'];
    $g = preg_replace('#[^a-z]#', '', $_POST['g']);
    $c = preg_replace('#[^a-z ]#i', '', $_POST['c']);
    $fn = preg_replace('#[^a-z ]#i', '', $_POST['fn']);
    $urole = preg_replace('#[^a-z]#i', '', $_POST['urole']);
    
    
    // GET USER IP ADDRESS
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
    // DUPLICATE DATA CHECKS FOR USERNAME AND EMAIL
    $sql = "SELECT id FROM tblusers WHERE username='$u' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $u_check = mysqli_num_rows($query);
    // -------------------------------------------
    $sql = "SELECT id FROM tblusers WHERE email='$e' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $e_check = mysqli_num_rows($query);
    // FORM DATA ERROR HANDLING
    if ($u == "" || $e == "" || $p == "" || $g == "" || $c == "" || $urole== "") {
        echo "The form submission is missing values.";
        exit();
    } else if ($u_check > 0) {
        echo "The username you entered is alreay taken";
        exit();
    } else if ($e_check > 0) {
        echo "That email address is already in use in the system";
        exit();
    } else if (strlen($u) < 3 || strlen($u) > 16) {
        echo "Username must be between 3 and 16 characters";
        exit();
    } else if (is_numeric($u[0])) {
        echo 'Username cannot begin with a number';
        exit();
    } else {
        // END FORM DATA ERROR HANDLING
        // Begin Insertion of data into the database
        // Hash the password and apply your own mysterious unique salt
        $cryptpass = crypt($p);
        include_once ("admin/randStrGen.php");
        //$p_hash = randStrGen(20)."$cryptpass".randStrGen(20);
        $p_hash = md5($p);
        // Add user info into the database table for the main site table
        $sql = "INSERT INTO tblusers (username, email, password, gender, country,userlevel, ip, signup, lastlogin, notescheck)       
		        VALUES('$u','$e','$p_hash','$g','$c','$urole','$ip',now(),now(),now())";
        $query = mysqli_query($db_conx, $sql);
        $uid = mysqli_insert_id($db_conx);

        // Establish their row in the useroptions table
//        $sql = "INSERT INTO useroptions (id, username, background) VALUES ('$uid','$u','original')";
//        $query = mysqli_query($db_conx, $sql);


        // Create directory(folder) to hold each user's files(pics, MP3s, etc.)
        if (!file_exists("user/$u")) {
            mkdir("user/$u", 0755);
        }
        // Email the user their activation link
        $to = "$e";
        $from = "contac@keeduf.com";
        $subject = 'Showoff Account Activation';
        $message = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Showoff Message</title></head><body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;"><div style="padding:10px; background:#333; font-size:24px; color:#CCC;"><a href="http://www.pakizagroup.com/DevPortfolio/"><img src="http://www.pakizagroup.com/DevPortfolio/images/logo.png" width="50" height="60" alt="yoursitename" style="border:none; float:left;"></a>Edgar505 Account Activation</div><div style="padding:24px; font-size:17px;">Hello ' . $u . ',<br /><br />Click the link below to activate your account when ready:<br /><br /><a href="http://bdwebdeveloper.com/edgar505/activation.php?id=' . $uid . '&u=' . $u . '&e=' . $e . '&p=' . $p_hash . '">Click here to activate your account now</a><br /><br />Login after successful activation using your:<br />* E-mail Address: <b>' . $e . '</b></div></body></html>';
        $headers = "From: $from\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\n";
        mail($to, $subject, $message, $headers);
        echo "signup_success";
 
        exit();
    }
    exit();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Sitename | Sign Up</title>
        <link rel="icon" href="favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="css/bootstrap.dark.css">
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
        <style type="text/css">
            #signupform{
                margin-top:24px;	
            }
            #signupform > div {
                margin-top: 12px;	
            }
            #signupform > input,select {
                width: 200px;
                padding: 3px;
                background: #F3F9DD;
            }
            #signupbtn {
                font-size:18px;
                padding: 12px;
            }
            #terms {
                border:#CCC 1px solid;
                background: #F5F5F5;
                padding: 12px;
            }
            .navbar {
                border: none;
                background: none;
            }
        </style>
        <script src="js/main.js"></script>
        <script src="js/ajax.js"></script>
        <script>
            function restrict(elem){
                var tf = _(elem);
                var rx = new RegExp;
                if(elem == "email"){
                    rx = /[' "]/gi;
                } else if(elem == "username"){
                    rx = /[^a-z0-9]/gi;
                }
                tf.value = tf.value.replace(rx, "");
            }
            function emptyElement(x){
                _(x).innerHTML = "";
            }
            function checkusername(){
                var u = _("username").value;
                if(u != ""){
                    _("unamestatus").innerHTML = 'checking ...';
                    var ajax = ajaxObj("POST", "signup.php");
                    ajax.onreadystatechange = function() {
                        if(ajaxReturn(ajax) == true) {
                            _("unamestatus").innerHTML = ajax.responseText;
                        }
                    }
                    ajax.send("usernamecheck="+u);
                }
            }
            function signup(){
                var u = _("username").value;
                var e = _("email").value;
                var p1 = _("pass1").value;
                var p2 = _("pass2").value;
                var c = _("country").value;
                var g = _("gender").value;
                var fn = _("fn").value;
                var urole = _("urole").value;
                var status = _("status");
                
                if(u == "" || e == "" || p1 == "" || p2 == "" || c == "" || g == "" || urole == ""){
                    status.innerHTML = "Fill out all of the form data";
                } else if(p1 != p2){
                    status.innerHTML = "Your password fields do not match";
                } else if( _("terms").style.display == "none"){
                    status.innerHTML = "Please view the terms of use";
                } else {
                    _("signupbtn").style.display = "none";
                    status.innerHTML = 'please wait ...';
                    var ajax = ajaxObj("POST", "signup.php");
                    ajax.onreadystatechange = function() {
                        if(ajaxReturn(ajax) == true) {
                            if(ajax.responseText != "signup_success"){
                                status.innerHTML = ajax.responseText;
                                _("signupbtn").style.display = "block";
                            } else {
                                window.scrollTo(0,0);
                                _("signupform").innerHTML = "OK "+u+", check your email inbox and junk mail box at <u>"+e+"</u> in a moment to complete the sign up process by activating your account. You will not be able to do anything on the site until you successfully activate your account.";
                            }
                        }
                    }
                    ajax.send("u="+u+"&e="+e+"&p="+p1+"&c="+c+"&g="+g+"&fn="+fn+"&urole="+urole);
                }
            }
            function openTerms(){
                _("terms").style.display = "block";
                emptyElement("status");
            }
            /* function addEvents(){
                _("elemID").addEventListener("click", func, false);
        }
        window.onload = addEvents; */
        </script>
    </head>
    <body>

        <div class="navbar navbar-default navbar-fixed-top" style="background-color: #eb3b27;">
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
                        <li>
                            <a href="index.php" id="themes">Home </a>
                        </li>
                        
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
                <h3 style="margin-bottom: 0px;">Register now</h3>
                <p style="margin-top: 0px;"><small>Some instruction goes here </small></p>
                
                <form name="signupform" id="signupform" onsubmit="return false;" style="margin-bottom: 1em;color:#000;">
                    <div class="row">
                    <div class="col-md-6">

                        <p style="margin: 0px ;padding: 0px;"><label for="username">Username </label> <span id="unamestatus"></span></p>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span> 
                            <input style="max-width: 400px;" id="username"  class="form-control"type="text" onblur="checkusername()" onkeyup="restrict('username')" maxlength="16">
                        </div>
                        

                        <label for="email">Email Address</label>                           
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span> 
                            <input style="max-width: 400px;" id="email"  class="form-control" type="text" onfocus="emptyElement('status')" onkeyup="restrict('email')" maxlength="88">
                        </div>

                        <label for="pass1">Create Password</label>                            
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span> 
                            <input style="max-width: 400px;" id="pass1" class="form-control" type="password" onfocus="emptyElement('status')" maxlength="16">
                        </div>

                        <label for="pass2">Confirm Password</label>                            
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span> 
                            <input style="max-width: 400px;" id="pass2" class="form-control" type="password" onfocus="emptyElement('status')" maxlength="16">
                        </div>

                        <br />
                        
                        <div>
                            <a href="#" onclick="return false" onmousedown="openTerms()">
                                View the Terms Of Use
                            </a>
                        </div>

                        <div id="terms" style="display:none; margin: 20px 0px; padding:0; border: none; max-width: 440px;">
                            <div class="alert alert-success"> 
                                <h3 style="margin-top:0px;">DevPortfolio Terms Of Use</h3>
                                <p>1. Play nice here.</p>
                                <p>2. Take a bath before you visit.</p>
                                <p>3. Brush your teeth before bed.</p>      
                            </div>
                        </div>                        
                        
                        <br />
                        <button class="btn btn-default button" id="signupbtn" onclick="signup()"><span class="glyphicon glyphicon-user"></span> Create Account</button>
                        <span id="status"></span>

                    </div>

                    <div class="col-md-6">

                        <label for="fn">First name</label>
                        <div class="input-group">                       
                            <span class="input-group-addon"><span class="glyphicon glyphicon-book"></span></span> 
                            <input style="max-width: 400px;" id="fn" name="fn"  class="form-control"type="text" maxlength="45">
                        </div>
                        <span id="fnamestatus"></span>

                        <label for="urole">Role</label>        
                        <div class="input-group">                        
                            <span class="input-group-addon"><span class="glyphicon glyphicon-certificate"></span></span> 
                            <select style="max-width: 200px;" id="urole" name="urole"class="form-control">
                                <option value="">--Select Role--</option>
                                <option value="a">Viewers</option>
                                <option value="c">Performers</option>
                            </select>
                        </div>                    

                        <label for="gender">Gender</label>
                        <div class="input-group">                        
                            <span class="input-group-addon"><span class="glyphicon glyphicon-heart"></span></span>
                            <select style="max-width: 200px;" id="gender" class="form-control">
                                <option value=""></option>
                                <option value="m">Male</option>
                                <option value="f">Female</option>
                            </select>
                        </div>
                        <label for="country">Country</label>
                        <div class="input-group">                        
                            <span class="input-group-addon"><span class="glyphicon glyphicon-flag"></span></span>
                            <select style="max-width: 200px;" id="country" class="form-control">
                                <option value="United States">United States</option>
                                <?php include_once("admin/template_country_list.php"); ?>
                            </select>
                        </div>
                        
                    </div>

                </div>
                </form>
                
            </div>

        </div>   

        <script src="js/jquery.js"></script>
        <script src="js/vendor/jquery-1.10.2.min.js"></script>
    </body>
</html>