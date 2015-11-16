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
?>
<?php
$videoTitle = "";
$videoApprove = '';
$id='';
if (isset($_GET["id"])){   
    if (preg_match('%^[0-9]%',  stripcslashes(trim($_GET["id"])))) {
        $id = $_GET["id"] ;    
        if (!empty($id)) {      

            $sql = "SELECT * FROM tblvideo WHERE `id`='$id' LIMIT 1";
            $query = mysql_query($sql,$connection);
            $p_check = mysql_num_rows($query);
            if ($p_check > 0) {
                while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
                    $videoTitle = $row["title"];
                    $videoApprove = $row["aprove"] ;
                }
            }
        }
    } else {    
        echo '<p style="color:red;">Don\'t fuck ass hole !!</p>';    
    }
}

$message='';
if (isset($_POST['title']) && isset($_POST['approve'])) {
    $title = $_POST['title'];
    $approve = $_POST['approve'];
    $postId = $_POST['postid'];
    if (!empty($title) && !empty($approve)) {
        
        if (!check_utf8($title)){
            echo 'Not a valid utf8 string';
            exit();
        }        
        
        mysql_query("SET NAMES 'utf8'");
        $query = "UPDATE tblvideo SET title='$title', aprove='$approve' WHERE `id`= '$postId'";
        if ($query_run = mysql_query($query)) {
            $message.= '<p style="color:green;">One video approve successfull.</p>';
        } else {
            $message.= '<p style="color:red;">We could not approve you at this time</p>';
        }
    } else {
        $message.= '<p style="color:red;">Title, approve field requird</p>';
    }
}

// find out how many rows are in the table 
$sql = "SELECT COUNT(*) FROM tblvideo";
$result = mysql_query($sql, $connection) or trigger_error("SQL", E_USER_ERROR);
$r = mysql_fetch_row($result);
$numrows = $r[0];

// number of rows to show per page
$rowsperpage = 5;
// find out total pages
$totalpages = ceil($numrows / $rowsperpage);

// get the current page or set a default
if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
   // cast var as int
   $currentpage = (int) $_GET['currentpage'];
} else {
   // default page num
   $currentpage = 1;
} // end if

// if current page is greater than total pages...
if ($currentpage > $totalpages) {
   // set current page to last page>
   $currentpage = $totalpages;
} // end if
// if current page is less than first page...
if ($currentpage < 1) {
   // set current page to first page
   $currentpage = 1;
} // end if

// the offset of the list, based on current page 
$offset = ($currentpage - 1) * $rowsperpage;

// get the info from the db 
$sql = "SELECT * FROM tblvideo LIMIT $offset, $rowsperpage";
$result = mysql_query($sql, $connection) or trigger_error("SQL", E_USER_ERROR);

// while there are rows to be fetched...
$posts = '';

$posts .= '<table class="table">';
$posts .= '<th>Serial no</th><th>Video Title</th><th>Post Date</th><th>Approve</th><th>Edit</th>';
$serial=0;
while ($list = mysql_fetch_assoc($result)) {
   $serial++;
   $posts .= "<tr>";
   $posts .= '<td>' . $serial .'</td>';
   $posts .= '<td>' . $list["title"] .'</td>';
   $posts .= '<td>' . $list["add_date"] .'</td>';
   $posts .= '<td>' . $list["aprove"] .'</td>';
   if ($list["aprove"]=='no'){
       $posts .= '<td><a href="publish-video.php?id='.$list["id"].'#editPost" title=""><span class="glyphicon glyphicon-ok-sign"></span></a></td>';
   }else{
       $posts .= '<td><a href="publish-video.php?id='.$list["id"].'#editPost" title=""><span class="glyphicon glyphicon-remove-sign"></span></a></td>';
   }
   
   echo "</tr>";
} // end while
$posts .= '</table>';

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
                    if ($userlavel == 'a'){
                        echo '<li><a href="index.php">Dashboard</a></li>';
                        echo '<li><a href="add-video.php">Add Video</a></li>';
                    }elseif ($userlavel == 'b'){   
                        echo '<li><a href="index.php">Dashboard</a></li>';
                        echo '<li><a href="add-video.php">Add Video</a></li>';
                        echo '<li class="divider"></li>';
                        echo '<li><a href="add-contest.php">Add Contest</a></li>';
                        echo '<li  class="active"><a href="publish-video.php">Publish Video</a></li>';
                    } 
                    echo '</ul>';
                    echo '</div>';
                }
                ?>                
            </div>
            <div class="col-lg-9">
                
                        <div class="">
                            <?php 
                            echo $message;
                            ?>
                            <h2>Video List</h2>
                            <?php
                                echo $posts;
                                echo '<br/>';
                                /******  build the pagination links ******/
                                // range of num links to show
                                $range = 3;

                                // if not on page 1, don't show back links
                                if ($currentpage > 1) {
                                   // show << link to go back to page 1
                                   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=1'><<</a> ";
                                   // get previous page num
                                   $prevpage = $currentpage - 1;
                                   // show < link to go back to 1 page
                                   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$prevpage'><</a> ";
                                } // end if 

                                // loop to show links to range of pages around current page<br>
                                for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
                                   // if it's a valid page number...
                                   if (($x > 0) && ($x <= $totalpages)) {
                                      // if we're on current page...
                                      if ($x == $currentpage) {
                                         // 'highlight' it but don't make a link
                                         echo " [<b>$x</b>] ";
                                      // if not current page...
                                      } else {
                                         // make it a link
                                         echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$x'>$x</a> ";
                                      } // end else
                                   } // end if 
                                } // end for

                                // if not on last page, show forward and last page links   
                                if ($currentpage != $totalpages) {
                                   // get next page
                                   $nextpage = $currentpage + 1;
                                    // echo forward link for next page 
                                   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage'>></a> ";
                                   // echo forward link for lastpage
                                   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$totalpages'>>></a> ";
                                } // end if
                                /****** end build pagination links ******/
                            ?>
                        </div>
                        
                        <?php                            
                            
                            if ($user_ok == true && $id != '') {
                                echo '<h2 id="editPost">Edit post</h2>';
                                                               
                                ?>

                                    <div class="">
                                        <form accept-charset="UTF-8" id="postAddform" action="publish-video.php" method="post">
                                                <div>Video title: <span style="color: red;">*</span></div>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-edit"></span></span>    
                                                    <input class="form-control" name="title" type="text" value="<?php echo $videoTitle;?>" id="title" maxlength="100" />                       
                                                </div>
                                                <div>Approve: <span style="color: red;">*</span></div>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-picture"></span></span>
                                                    <select class="form-control" name="approve" id="approve">
                                                        <option value="<?php echo $videoApprove;?>"><?php echo $videoApprove;?></option>
                                                        <?php 
                                                            if ($videoApprove == yes) {
                                                                echo '<option value="no">no</option>';
                                                            } else{
                                                                echo '<option value="yes">yes</option>';
                                                            }                                                           
                                                        ?>
                                                    </select>                                                   
                                                </div>
                                                <input type="hidden" name="postid" value="<?php echo $id;?>">
                                                <div style="margin-top: 10px;">
                                                    <input class="btn btn-primary" type="submit" value="Publish Video">      
                                                </div>
                                
                                                <p id="status"></p>
                                            </form>
                                    </div>
                                    

                                <?php
                                } else if ($user_ok == false) {
                                      echo 'You need to loged in first';
                                }
                                ?> 
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