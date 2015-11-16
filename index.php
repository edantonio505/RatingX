<?php
date_default_timezone_set("America/New_York");
include_once 'admin/db.php';
include_once("admin/check_login_status.php");
include_once 'functions.php';
$loginLink = '<a href="login.php">Log In</a> &nbsp; | &nbsp; <a href="signup.php">Sign Up</a>';
if ($user_ok == true) {
    $loginLink = '<a href="user.php?u=' . $log_username . '">' . $log_username . '</a> | <a href="logout.php">Log Out</a>';    
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
        <title>The Talent Showoff</title>
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
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->
        <?php include_once 'nav.php';?>
        <div class="container">

            <div class="col-lg-12">
                <div class="page-header" id="banner">
                    <div class="row">
                        <div class="col-lg-6">
                            <h1>The Talent Showoff app</h1>
                            <p class="lead">Some text goes here</p>
                        </div>
                        <div class="col-lg-6" style="padding: 15px 15px 0 15px;">
                            <div class="well sponsor">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-12">
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading">                            
                            <h3 class="panel-title">Latest Posted video</h3>
                        </div>
                        <div class="panel-body">
                            
                            <?php
                            $select = mysql_query("select * from tblvideo WHERE aprove='yes' ORDER BY add_date DESC Limit 4");

                            while ($fetch1 = mysql_fetch_array($select)) {

                                $videoId = $fetch1['id'];
                                $video = $fetch1['name'];
                                $videoUrl = $fetch1['videourl'];
                                $videoType = $fetch1['type'];
                                $videoTitle = $fetch1['title'];
                                $videoDescription = $fetch1['description'];
                                $contestId = $fetch1['contestid'];
                                $userId = $fetch1['userid'];
                                $videoPostDate = $fetch1['add_date'];
                                
                                $timestamp = strtotime($videoPostDate); 
                                $new_date = date('d-m-Y', $timestamp);
                                ?>

                                <div class="col-sm-3 col-lg-3 col-md-3">
                                    
                                    <div class="thumbnail">
                                        <div class="video_player_box">
                                            <?php if ($videoType == 'youtube'){ 
                                                echo'<iframe width="250" height="150" src="//www.youtube.com/embed/'. $videoUrl.'" frameborder="0" allowfullscreen></iframe>';                                                                                                                                                                               
                                           } elseif ($videoType == 'vimeo'){
                                                echo '<iframe src="//player.vimeo.com/video/'.$videoUrl.'" width="250" height="150" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
                                           } else { ?>
                                                <video width="247" height="150" controls >
                                                    <!-- MP4 must be first for iPad! -->
                                                    <source src="video/<?php echo $video; ?>.MP4" type="video/mp4" /><!-- WebKit video    -->
                                                    <source src="video/<?php echo $video; ?>.webm" type="video/webm" /><!-- Chrome / Newest versions of Firefox and Opera -->
                                                    <source src="video/<?php echo $video; ?>.OGV" type="video/ogg" /><!-- Firefox / Opera -->
                                                    <!-- fallback to Flash: -->
                                                    <object width="247" height="150" type="application/x-shockwave-flash" data="<?php echo $video; ?>.SWF">
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
                                            <h2><a href="single.php?videoid=<?php echo $videoId; ?>&contestid=<?php echo $contestId; ?>"><?php echo $videoTitle;?></a> </h2>
                                            <p class="pull-right" style="margin: 0px;padding: 0px;"><small><em>Post date :<?php echo $new_date; ?></em></small></p> 
                                            <p style="margin: 0px;padding: 0px;"><small><em>Posted by: <a href="#" title=""><?php echo getUserField('username',$userId);?></a></em></small></p>
                                            <p><?php echo limit_text($videoDescription,12);?><a href="single.php?vid=<?php echo $videoId; ?>&contestid=<?php echo $contestId; ?>">Readmore</a></p>
                                        </div>
                                        <div class="ratings">
                                            
                                            <div class="videoRateing">
                                                <div id="ratings"></div>

                                                <strong><?php echo avgRating($videoId); ?></strong>

                                                <div id="rate_status"></div>  
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php } ?>
                            
                            
                        </div>
                    </div>
                </div>
            </div>
            

            <?php 
            $todayDate=  date('Y-m-d H:i:s', time());           
            //echo $todayDate;           
            $selectContests = mysql_query("select * from tblvideocontests WHERE end_date > '$todayDate' ORDER BY start_date ASC");
            while ($fetchContests = mysql_fetch_array($selectContests)) {
                $contestId = $fetchContests['id'];
            ?>
            
            <div class="col-lg-12">
                <div class="row">
                    <div class="panel panel-success">
                        <div class="panel-heading">                           
                            <p class="pull-right" style="margin-top: 0px;"><em>End date :<?php echo getContestField('end_date',$contestId);?></em></p>
                            <h1 class="panel-title"><?php echo getContestField('contestname',$contestId);?><small style="margin-left: 10px;color:#000;"><em><?php echo getContestField('description',$contestId);?></em></small></h1>                            
                        </div>
                        <div class="panel-body">
                           
                        <?php
                            $select = mysql_query("select * from tblvideo WHERE aprove='yes' AND contestid='$contestId' ORDER BY add_date DESC Limit 8");
                            while ($fetch1 = mysql_fetch_array($select)) {

                                $videoId = $fetch1['id'];
                                $video = $fetch1['name'];
                                $videoUrl = $fetch1['videourl'];
                                $videoType = $fetch1['type'];
                                $videoTitle = $fetch1['title'];
                                $videoDescription = $fetch1['description'];
                                $videoPostDate = $fetch1['add_date'];
                                
                                $timestamp = strtotime($videoPostDate); 
                                $new_date = date('d-m-Y', $timestamp);
                                ?>

                                <div class="col-sm-3 col-lg-3 col-md-3">
                                    
                                    <div class="thumbnail">
                                        <div class="video_player_box">
                                            <?php if ($videoType == 'youtube'){ 
                                                echo'<iframe width="250" height="150" src="//www.youtube.com/embed/'. $videoUrl.'" frameborder="0" allowfullscreen></iframe>';                                                                                                                                                                               
                                           } elseif ($videoType == 'vimeo'){
                                                echo '<iframe src="//player.vimeo.com/video/'.$videoUrl.'" width="250" height="150" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
                                           } else { ?>
                                                <video width="247" height="150" controls >
                                                    <!-- MP4 must be first for iPad! -->
                                                    <source src="video/<?php echo $video; ?>.MP4" type="video/mp4" /><!-- WebKit video    -->
                                                    <source src="video/<?php echo $video; ?>.mp4" type="video/mp4" /><!-- WebKit video    -->
                                                    <source src="video/<?php echo $video; ?>.webm" type="video/webm" /><!-- Chrome / Newest versions of Firefox and Opera -->
                                                    <source src="video/<?php echo $video; ?>.OGV" type="video/ogg" /><!-- Firefox / Opera -->
                                                    <!-- fallback to Flash: -->
                                                    <object width="247" height="150" type="application/x-shockwave-flash" data="<?php echo $video; ?>.SWF">
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
                                            <h2><a href="single.php?videoid=<?php echo $videoId; ?>&contestid=<?php echo $contestId; ?>"><?php echo $videoTitle;?></a> </h2>
                                            <p class="pull-right" style="margin: 0px;padding: 0px;"><small><em>Post date :<?php echo $new_date; ?></em></small></p> 
                                            <p style="margin: 0px;padding: 0px;"><small><em>Posted by: <a href="#" title=""><?php echo getUserField('username',$userId);?></em></a></small></p>
                                            <p><?php echo limit_text($videoDescription,12);?> <a href="single.php?videoid=<?php echo $videoId; ?>&contestid=<?php echo $contestId; ?>">Readmore</a></p>
                                        </div>
                                        <div class="ratings">
                                            
                                            <div class="videoRateing">
                                                <div id="ratings"></div>

                                                <strong><?php echo avgRating($videoId); ?></strong>

                                                <div id="rate_status"></div>  
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php } ?>                           
                           
                           
                        </div>
                    </div>
                    
                    
                </div>
            </div>              
            
                
            <?php       
            }          
            ?>

        </div><!--End container-->

            <!--            footer-->
            <div class="row" style="margin: 0px;background: #eb3b27;"> 
                <div class="container">
                    
                
                <div class="col-lg-12">

                    <ul class="list-unstyled footerMenu">
                        <li class="pull-right"><a href="#top">Back to top</a></li>
                        <li><a href="#">Home</a></li>
                    </ul>
                    <p>Developed by <a href="http://www.keeduf.com" rel="nofollow">Edgar  Velazquez</a>. Contact him at <a href="mailto:contact@keeduf.com">contact@keeduf.com</a>.</p>
               </div>
                </div>
            </div> 

        
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
