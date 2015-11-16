<?php
include_once("admin/check_login_status.php");
include_once 'functions.php';
$error="";
$success="";
if ($user_ok == TRUE){    
    $userId = preg_replace('#[^a-z0-9]#i', '', $log_id);
}else{
    header("location: login.php");
    exit();
}
$loginLink = '<a href="login.php">Log In</a> &nbsp; | &nbsp; <a href="signup.php">Sign Up</a>';
if ($user_ok == true) {
    $loginLink = '<a href="user.php?u=' . $log_username . '">' . $log_username . '</a> | <a href="logout.php">Log Out</a>';
}

if (isset($_REQUEST['upload'])) {
    
    $name ='';
    $contestId = $_POST['contestName'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $url = $_POST['url'];
    $name = $_FILES['uploadvideo']['name'];
    $type = $_FILES['uploadvideo']['type'];
    //$size=$_FILES['uploadvideo']['size'];
    $cname = str_replace(" ", "_", $name);
    
    
    
    if(empty($contestId)){
        //echo "<script>alert('Select contest name')</script>";
        $error = "Select contest name";
    } elseif (empty($url) && empty($name)){
        //echo "<script>alert('You must input url or file name')</script>";
        $error = "You must input url or file name for video";
    } elseif (empty($title)){
        //echo "<script>alert('You must input video title')</script>";
        $error = "You must input video title";
    }
    
    if (!empty($url) && (!empty($title))){        
        
        $videoHost = videoType($url);
        if ($videoHost == 'youtube'){
                $videoId = parse_youtube($url);
        } elseif ($videoHost == 'vimeo'){
                $videoId = parse_vimeo($url);
        } else{
                $videoId = '';
        }
            
        $sql = "INSERT INTO tblvideo(name,type,videourl,title,description,length,ratings,add_date,aprove,userid,contestid) VALUE('" . $cname . "','".$videoHost."','" . $videoId . "','" . $title . "','" . $description . "','none','',now(),'no','" . $userId . "','" . $contestId . "')";
        $result = mysqli_query($db_conx, $sql);
        //echo "Your video has been successfully uploaded";
        if ($result){
            //echo "<script>alert('One New Video Inserted Succesful')</script>";
            $success = "Your video has been successfully added";
        }
    } elseif((!empty($name) && empty($url)) && (!empty($title))) {
        
        $tmp_name = $_FILES['uploadvideo']['tmp_name'];
        $target_path = "video/";
        
        $target_path = $target_path . basename($cname);
        $path_parts = pathinfo($cname);
        $cname = $path_parts['filename']; // since PHP 5.2.0
        if (move_uploaded_file($_FILES['uploadvideo']['tmp_name'], $target_path)) {
            $sql = "INSERT INTO tblvideo(name,type,videourl,title,description,length,ratings,add_date,aprove,userid,contestid) VALUE('" . $cname . "','".$type."','" . $url . "','" . $title . "','" . $description . "','none','',now(),'no','" . $userId . "','" . $contestId . "')";
            $result = mysqli_query($db_conx, $sql);
            //echo "<script>alert('Inserted Succesful and file uploaded.')</script>";
            $success = "Your video " . $cname . " has been successfully uploaded";
        }
    }
   
}

function videoType($url) {
    if (strpos($url, 'youtube') > 0) {
        return 'youtube';
    } elseif (strpos($url, 'vimeo') > 0) {
        return 'vimeo';
    } else {
        return 'unknown';
    }
}
function parse_youtube($link){
 
        $regexstr = '~
            # Match Youtube link and embed code
            (?:                             # Group to match embed codes
                (?:<iframe [^>]*src=")?       # If iframe match up to first quote of src
                |(?:                        # Group to match if older embed
                    (?:<object .*>)?        # Match opening Object tag
                    (?:<param .*</param>)*  # Match all param tags
                    (?:<embed [^>]*src=")?  # Match embed tag to the first quote of src
                )?                          # End older embed code group
            )?                              # End embed code groups
            (?:                             # Group youtube url
                https?:\/\/                 # Either http or https
                (?:[\w]+\.)*                # Optional subdomains
                (?:                         # Group host alternatives.
                youtu\.be/                  # Either youtu.be,
                | youtube\.com              # or youtube.com
                | youtube-nocookie\.com     # or youtube-nocookie.com
                )                           # End Host Group
                (?:\S*[^\w\-\s])?           # Extra stuff up to VIDEO_ID
                ([\w\-]{11})                # $1: VIDEO_ID is numeric
                [^\s]*                      # Not a space
            )                               # End group
            "?                              # Match end quote if part of src
            (?:[^>]*>)?                       # Match any extra stuff up to close brace
            (?:                             # Group to match last embed code
                </iframe>                   # Match the end of the iframe
                |</embed></object>          # or Match the end of the older embed
            )?                              # End Group of last bit of embed code
            ~ix';
 
        preg_match($regexstr, $link, $matches);
 
        return $matches[1];
 
}
function parse_vimeo($link){
 
        $regexstr = '~
            # Match Vimeo link and embed code
            (?:<iframe [^>]*src=")?     # If iframe match up to first quote of src
            (?:                         # Group vimeo url
                https?:\/\/             # Either http or https
                (?:[\w]+\.)*            # Optional subdomains
                vimeo\.com              # Match vimeo.com
                (?:[\/\w]*\/videos?)?   # Optional video sub directory this handles groups links also
                \/                      # Slash before Id
                ([0-9]+)                # $1: VIDEO_ID is numeric
                [^\s]*                  # Not a space
            )                           # End group
            "?                          # Match end quote if part of src
            (?:[^>]*></iframe>)?        # Match the end of the iframe
            (?:<p>.*</p>)?              # Match any title information stuff
            ~ix';
 
        preg_match($regexstr, $link, $matches);
 
        return $matches[1];
 
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
        <title>Edgar505</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/bootstrap.dark.css">
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="js/vendor/modernizr-2.6.2.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->


        <?php include_once 'nav.php';?>
        
        <div class="container" style="margin-top:50px;">
            <div class="col-lg-3">

                <?php if ($user_ok == true) { 
                    $userlavel = getUserField('userlevel',$userId);
                    echo '<div class="bs-example">';
                    echo '<ul class="nav nav-pills nav-stacked" style="max-width: 300px;">';
                    if ($userlavel == 'c'){
                        echo '<li><a href="dashboard.php">Dashboard</a></li>';
                        echo '<li class="active"><a href="add-video.php">Add Video</a></li>';
                    }elseif ($userlavel == 'b'){   
                        echo '<li><a href="index.php">Dashboard</a></li>';
                        echo '<li class="active"><a href="add-video.php">Add Video</a></li>';
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
            <!-- LOGIN FORM -->

            <h3 style="margin-bottom: 0px;">Add video for contest</h3>
            <p style="margin-top: 0px;"><small>Some instruction goes here </small></p>
            <p style="color:red;"><?php echo $error;?></p>
            <p style="color:green;"><?php echo $success;?></p>
            <form id="loginform" name="video" enctype="multipart/form-data" method="post" action="add-video.php">
                
                <p style="margin-bottom: 0px;">Contest Name:</p>
                <select class="form-control" name="contestName">
                    <option value="">--Select Contest--</option>
                    <?php 
                    $todayDate=  date('Y-m-d H:i:s', time());                     
                    $selectContests = mysql_query("select * from tblvideocontests WHERE end_date > '$todayDate' ORDER BY start_date ASC");
                    while ($fetchContests = mysql_fetch_array($selectContests)) {
                        $contestId = $fetchContests['id'];  
                        $contestName = $fetchContests['contestname'];
                        echo '<option value="'.$contestId.'">'.$contestName.'</option>';                     
                     }
                     ?>       
                </select>
                
                <p style="margin-bottom: 0px;">Video url:</p>
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-link"></span></span>    
                    <input style="" class="form-control" type="text" name="url" id="url" onfocus="emptyElement('status')" maxlength="145">                       
                </div>

                <p  style="margin-bottom: 0px;">Browse file:</p>
                <div class="input-group">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-facetime-video" style="line-height: 0;"></span></button>
                    </span>
                    <input type="file" class="form-control" name="uploadvideo">
                </div><!-- /input-group -->

                <p style="margin-bottom: 0px;">Video title:</p>
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>    
                    <input style="" class="form-control" type="text" name="title" id="title" maxlength="100">                       
                </div>
                <p style="margin-bottom: 0px;">Description:</p>
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-comment"></span></span>                           
                    <textarea name="description" id="description" cols="30" rows="5" style="width:100%;"></textarea>
                </div>
                <br />
                <p id="status"></p>

                <input name="MAX_FILE_SIZE" value="100000000000000"  type="hidden"/>               
                <input class="btn btn-default button" type="submit" name="upload" value="Add Video" />               

                <br /><br />
            </form>           
            </div>       

        </div><!--container-->

        <script src="js/vendor/jquery-1.10.2.min.js"></script>
        <script>
            window.jQuery || document.write('<script src="js/vendor/jquery-1.10.2.min.js"><\/script>')
        </script>
        <script src="js/bootstrap.js"></script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

        <!--    Google Analytics: change UA-XXXXX-X to be your site's ID. 
                <script>
                    (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
                            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
                        e=o.createElement(i);r=o.getElementsByTagName(i)[0];
                        e.src='//www.google-analytics.com/analytics.js';
                        r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
                    ga('create','UA-XXXXX-X');ga('send','pageview');
                </script>-->
    </body>
</html>
