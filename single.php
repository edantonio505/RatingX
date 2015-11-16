<?php
date_default_timezone_set("America/New_York");
include_once 'admin/db.php';
include_once("admin/check_login_status.php");
include_once 'functions.php';
$loginLink = '<a href="login.php">Log In</a> &nbsp; | &nbsp; <a href="signup.php">Sign Up</a>';
if ($user_ok == true) {
    $loginLink = '<a href="user.php?u=' . $log_username . '">' . $log_username . '</a> | <a href="logout.php">Log Out</a>';
    $u = $log_id;
}
if (isset($_GET['videoid'])) {

    $videoId = preg_replace('#[^a-z0-9]#i', '', $_GET['videoid']);
    $contestId = preg_replace('#[^a-z0-9]#i', '', $_GET['contestid']);
    $contestname = getContestField('contestname', $contestId);
}

$select = mysql_query("select * from tblvideo WHERE id='$videoId' Limit 1");

while ($fetch1 = mysql_fetch_array($select)) {

    $contestId = $fetch1['contestid'];
    $videoId = $fetch1['id'];
    $video = $fetch1['name'];
    $videoUrl = $fetch1['videourl'];
    $videoType = $fetch1['type'];
    $videoTitle = $fetch1['title'];
    $videoDescription = $fetch1['description'];
?>  
    <!DOCTYPE html>
    <!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
    <!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
    <!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
    <!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <title><?php echo $contestname; ?></title>
            <meta name="description" content="">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <style type="text/css">

            </style>
            <script>
                
            </script>
            <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

            <link rel="stylesheet" href="css/bootstrap.dark.css">
            <link rel="stylesheet" href="css/normalize.css">
            <link rel="stylesheet" href="css/main.css">

            <script src="js/vendor/modernizr-2.6.2.min.js"></script>
            <script src="js/jquery.js"></script>
            <script type="text/javascript">
                function ratings (elem){
                    var x= new XMLHttpRequest();
                    var url = "admin/rateParse.php";
                    var a = document.getElementById(elem).value;
                    var vars = "choice="+a;
                    var u = "<?php echo $u; ?>";
                    var videoId = "<?php echo $videoId; ?>";
                    var contestId = "<?php echo $contestId; ?>";
                    
                    //alert(product_id);
                    x.open("POST",url,true);
                    x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    x.onreadystatechange = function (){
                        if(x.readyState == 4 && x.status == 200){
                            var return_data= x.responseText;
                            document.getElementById("rate_status").innerHTML = return_data;
                        }
                    }
                    x.send("choice="+vars+"&u="+u+"&vid="+videoId+"&cid="+contestId);
                    document.getElementById("rate_status").innerHTML = "processing....";					
                }    
            </script>
        </head>
        <body>
            <!--[if lt IE 7]>
                <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
            <![endif]-->

            <!-- Add your site or application content here -->
            <?php include_once 'nav.php'; ?>        
            <div class="container">
                <input type="hidden" id="jsonContestId" name="jsonContestId" value="<?php echo $contestId;?>">
                <div class="col-lg-12">
                    <div class="page-header" id="banner">
                        <div class="row">
                            <div class="col-lg-6">
                                <h2><?php echo getContestField('contestname', $contestId); ?></h2>                            
                                <p class=""><?php echo getContestField('description', $contestId); ?></p>
                                <p class=""><em>End date :<?php echo getContestField('end_date', $contestId); ?></em></p>
                            </div>
                            <div class="col-lg-6" style="padding: 15px 15px 0 15px;">
                                <h2 style="color: red;"><p id="timeDisplay" class="countDown pull-right"></p></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="row">

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Video name: <?php echo $videoTitle; ?></h3>
                            </div>
                            <div class="panel-body">




                                <div class="col-sm-8 col-lg-8 col-md-8">

                                    <div class="thumbnail">
                                        <div class="">
                                            <?php
                                            if ($videoType == 'youtube') {
                                                echo'<iframe width="100%" height="400" src="//www.youtube.com/embed/' . $videoUrl . '" frameborder="0" allowfullscreen></iframe>';
                                            } elseif ($videoType == 'vimeo') {
                                                echo '<iframe src="//player.vimeo.com/video/' . $videoUrl . '" width="100%" height="400" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
                                            } else {
                                                ?>
                                                <video width="100%" height="auto" controls >
                                                    <!-- MP4 must be first for iPad! -->
                                                    <source src="video/<?php echo $video; ?>.mp4" type="video/mp4" /><!-- WebKit video    -->
                                                    <source src="video/<?php echo $video; ?>.MP4" type="video/mp4" /><!-- WebKit video    -->
                                                    <source src="video/<?php echo $video; ?>.webm" type="video/webm" /><!-- Chrome / Newest versions of Firefox and Opera -->
                                                    <source src="video/<?php echo $video; ?>.OGV" type="video/ogg" /><!-- Firefox / Opera -->
                                                    <!-- fallback to Flash: -->
                                                    <object width="100%" height="auto" type="application/x-shockwave-flash" data="<?php echo $video; ?>.SWF">
                                                        <!-- Firefox uses the `data` attribute above, IE/Safari uses the param below -->
                                                        <param name="movie" value="<?php echo $video; ?>.SWF" />
                                                        <param name="flashvars" value="image=__POSTER__.JPG&amp;file=<?php echo $video; ?>.MP4" />
                                                        <!-- fallback image. note the title field below, put the title of the video there -->
                                                        <img src="<?php echo $video; ?>.JPG" width="640" height="360" alt="__TITLE__"
                                                             title="No video playback capabilities, please download the video below" />
                                                    </object>
                                                </video>   
                                        <?php } ?>                                           
                                        </div>
                                        <div class="caption">                                                
                                            <p><?php echo $videoDescription; ?></p>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-sx-4 col-md-4 col-lg-4">
                                    <div class="ratings">

                                        <div id="ratings"></div>
                                        <h4 style="margin-top:10px;">Rate this video</h4>
                                        <?php
                                        if ($user_ok == true) {
                                            ?>
                                            <strong>On a scale of 1 to 5</strong><br />
                                            <input id="star1" class="round" type="button" value="" onclick="ratings('1');" title="1 of 5">
                                            <input type="hidden" name="choice" id="1" value="1">

                                            <input id="star2" class="round" type="button" value="" onclick="ratings('2');" title="2 of 5">
                                            <input type="hidden" name="choice" id="2" value="2">

                                            <input id="star3" class="round" type="button" value="" onclick="ratings('3');" title="3 of 5">
                                            <input type="hidden" name="choice" id="3" value="3">

                                            <input id="star4" class="round" type="button" value="" onclick="ratings('4');" title="4 of 5">
                                            <input type="hidden" name="choice" id="4" value="4">

                                            <input id="star5" class="round" type="button" value="" onclick="ratings('5');" title="5 of 5">
                                            <input type="hidden" name="choice" id="5" value="5">
                                            <?php
                                        } else {
                                            echo $loginLink = '<a href="login.php" title="Login">Login </a><a href="signup.php" title="Register">Register </a>';
                                        }
                                        ?>
                                        <br>
                                        <strong><?php echo avgRating($videoId); ?></strong>
                                        <div id="rate_status"></div>  

                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div><!--container-->
            <?php } ?>                            

        <!--            footer-->
        <div class="row" style="margin: 0px;background: #485563;"> 
            <div class="container">

                <div class="col-lg-12">

                    <ul class="list-unstyled footerMenu">
                        <li class="pull-right"><a href="#top">Back to top</a></li>
                        <li><a href="#" onclick="pageTracker._link(this.href); return false;">Blog</a></li>
                        <li><a href="#">RSS</a></li>
                        <li><a href="#">Twitter</a></li>
                        <li><a href="#">GitHub</a></li>
                        <li><a href="#">API</a></li>
                        <li><a href="#">Donate</a></li>
                    </ul>
                    <p>Developed by <a href="#" rel="nofollow">Sanjoy Debnath</a>. Contact him at <a href="mailto:s4sanjoy@gmail.com">s4sanjoy@gmail.com</a>.</p>
                </div>
            </div>
        </div> 


        <script src="js/bootstrap.js"></script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
        <script type="text/javascript">

            // Star Rateing Image Swap
            $( document ).ready(function() {   
                $("#star5").hover(function(){
                    $('#star1').css({
                        "background": "url('img/star.png')"
                    });
                    $('#star2').css({
                        "background": "url('img/star.png')"
                    });
                    $('#star3').css({
                        "background": "url('img/star.png')"
                    });
                    $('#star4').css({
                        "background": "url('img/star.png')"
                    });
                },function(){
                    $('#star1').css({
                        "background": "url('img/star-g.png')"
                    });
                    $('#star2').css({
                        "background": "url('img/star-g.png')"
                    });
                    $('#star3').css({
                        "background": "url('img/star-g.png')"
                    });
                    $('#star4').css({
                        "background": "url('img/star-g.png')"
                    });
                });

                $("#star4").hover(function(){
                    $('#star1').css({
                        "background": "url('img/star.png')"
                    });
                    $('#star2').css({
                        "background": "url('img/star.png')"
                    });
                    $('#star3').css({
                        "background": "url('img/star.png')"
                    });
                    $('#star4').css({
                        "background": "url('img/star.png')"
                    });
                },function(){
                    $('#star1').css({
                        "background": "url('img/star-g.png')"
                    });
                    $('#star2').css({
                        "background": "url('img/star-g.png')"
                    });
                    $('#star3').css({
                        "background": "url('img/star-g.png')"
                    });
                    $('#star4').css({
                        "background": "url('img/star-g.png')"
                    });
                });

                $("#star3").hover(function(){
                    $('#star1').css({
                        "background": "url('img/star.png')"
                    });
                    $('#star2').css({
                        "background": "url('img/star.png')"
                    });
                    $('#star3').css({
                        "background": "url('img/star.png')"
                    });

                },function(){
                    $('#star1').css({
                        "background": "url('img/star-g.png')"
                    });
                    $('#star2').css({
                        "background": "url('img/star-g.png')"
                    });
                    $('#star3').css({
                        "background": "url('img/star-g.png')"
                    });
                });

                $("#star2").hover(function(){
                    $('#star1').css({
                        "background": "url('img/star.png')"
                    });
                    $('#star2').css({
                        "background": "url('img/star.png')"
                    });

                },function(){
                    $('#star1').css({
                        "background": "url('img/star-g.png')"
                    });
                    $('#star2').css({
                        "background": "url('img/star-g.png')"
                    });
                });
                $("#star1").hover(function(){
                    $('#star1').css({
                        "background": "url('img/star.png')"
                    });

                },function(){
                    $('#star1').css({
                        "background": "url('img/star-g.png')"
                    });
                });

            });
            // End Star Rateing Image Swap                        
        </script>            
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
