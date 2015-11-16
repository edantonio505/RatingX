<?php
include_once("admin/check_login_status.php");
include_once 'functions.php';
// If user is logged in, header them away
$loginLink = '<a href="login.php">Log In</a> &nbsp; | &nbsp; <a href="signup.php">Sign Up</a>';
if ($user_ok == true) {
    $loginLink = '<a href="user.php?u=' . $log_username . '">' . $log_username . '</a> | <a href="logout.php">Log Out</a>';    
}else{
    header("location: login.php");
    exit();
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
           
        </style>        
        <script>
            function addContest(){
                var cn = _("contestName").value;
                var sd = _("sdate").value;
                var ed = _("edate").value;
                var d = _("description").value;
                var status = _("status");

                if(cn == "" || sd == "" || ed == ""){
                    status.innerHTML = "Fill out all of the form data";
                } else {
                    _("addContestbtn").style.display = "none";
                    status.innerHTML = 'please wait ...';
                    var ajax = ajaxObj("POST", "admin/add-contest-parse.php");
                    ajax.onreadystatechange = function() {
                        if(ajaxReturn(ajax) == true) {
                            if(ajax.responseText != "contest_add_success"){
                                status.innerHTML = ajax.responseText;
                                _("addContestbtn").style.display = "block";
                            } else {
                                status.innerHTML = "Contest Added Name : "+cn;
                            }
                        }
                    }
                    ajax.send("cn="+cn+"&sd="+sd+"&ed="+ed+"&d="+d);
                }
            }
        </script>
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
                        echo '<li><a href="dashboard.php">Dashboard</a></li>';
                        echo '<li><a href="add-video.php">Add Video</a></li>';
                    }elseif ($userlavel == 'b'){   
                        echo '<li><a href="index.php">Dashboard</a></li>';
                        echo '<li><a href="add-video.php">Add Video</a></li>';
                        echo '<li class="divider"></li>';
                        echo '<li class="active"><a href="add-contest.php">Add Contest</a></li>';
                        echo '<li><a href="publish-video.php">Publish Video</a></li>';
                    } 
                    echo '</ul>';
                    echo '</div>';
                }
                ?>                
            </div>
            <div class="col-lg-9">
                <h3 style="margin-bottom: 0px;">Create New Contest</h3>
                <p style="margin-top: 0px;"><small>Some instruction goes here </small></p>
                <form name="addContestform" id="signupform" onsubmit="return false;" style="margin-bottom: 1em;color:#fff;">
                    <div class="row">
                        <div class="col-md-6">
                            <p style="margin: 0px ;padding: 0px;"><label for="contestName">Contest Name </label> <span id="unamestatus"></span></p>
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-send"></span></span> 
                                <input style="max-width: 400px;" id="contestName"  class="form-control"type="text" onblur="checkusername()" onkeyup="restrict('username')" maxlength="145">
                            </div>
                            <label for="sdate">Start Date</label>
                            <div class="input-group">                       
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> 
                                <input style="max-width: 400px;" id="sdate" name="sdate"  class="form-control"type="text" maxlength="40">
                            </div>
                            <span id="fnamestatus"></span>
                            <label for="edate">End Date</label>        
                            <div class="input-group">                        
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> 
                                <input style="max-width: 400px;" id="edate" name="edate" class="form-control" type="text" maxlength="40">
                            </div>                        
                            <label for="description">Description</label>                           
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th-list"></span></span> 
                                <textarea name="description" id="description" cols="30" rows="5" style="width: 100%"></textarea>
                            </div>
                            <span id="status"></span>
                            <br>                            
                            <button class="btn btn-default button" id="addContestbtn" onclick="addContest()"><span class="glyphicon glyphicon-save"></span> &nbsp;Add Contest &nbsp;</button>                           
                        </div>

                        <div class="col-md-6">                       

                        </div>
                    </div>
                </form>

            </div>

        </div>   

        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.js"></script>
        <script src="js/bootstrap-datepicker.js"></script>
        <script src="js/plugins.js"></script>
        <script src="js/ajax.js"></script>
        <script src="js/main.js"></script>
        <script>
            $(document).ready(function() {
                $("#sdate").datepicker({
                    format : 'yyyy-mm-dd'
                });
                $("#edate").datepicker({
                    format : 'yyyy-mm-dd'
                });
                
                $('#sdate').datepicker()
                .on(picker_event, function(e){
                   // code goes here
                });
                
            });
        </script>               
    </body>
</html>