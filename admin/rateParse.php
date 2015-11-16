<?php 
if (isset($_POST["choice"]) && isset($_POST["u"])&& isset($_POST["vid"])&& isset($_POST["cid"])) {

    $choice = preg_replace('#[^0-9]#i', '', $_POST["choice"]);
    $u = preg_replace('#[^0-9]#i', '', $_POST["u"]);
    $vid = preg_replace('#[^0-9]#i', '', $_POST["vid"]);
    $cid = preg_replace('#[^0-9]#i', '', $_POST["cid"]);
    
    if ($choice > 5) {
        echo 'Stop playing around butthole';
        exit();
    }else if ($choice < 1) {
        echo 'Stop playing around butthole';
        exit();
    }else {
        $ipaddress = getenv('REMOTE_ADDR');
        include "db.php";
        $sql_check = mysql_query("select * from tblvideo where userid='$u' and contestid ='$cid' and id='$vid' LIMIT 1");
        $num_rows = mysql_num_rows($sql_check);
        
        if ($num_rows > 0){
            echo '<p style="color:#f00;"> Sorry, You are woner of the video. !!</p>';
            exit();
        }
        
        $sql_check = mysql_query("select * from tblrating_ip where userid='$u' and contestid ='$cid' and videoid='$vid' and ipaddress='$ipaddress' LIMIT 1");
        $num_rows = mysql_num_rows($sql_check);
        
        if ($num_rows > 0){
            echo '<p style="color:#f00;"> Sorry, You have already rated the video</p>';
            exit();
        }
    
    
        $sql = mysql_query("SELECT ratings FROM tblvideo WHERE id='$vid'");
        while ($row = mysql_fetch_array($sql)) {
            $myNums = $row["ratings"];
            $kaboom = explode(",",$myNums);

            array_push($kaboom, $choice);
            $string = implode(",",$kaboom);

            $firstChar =  substr($string, 0 ,1);
            $lastChar = substr($string, -1, 1);
            if($firstChar == ","){
                $string =$choice;
            }

            if($lastChar == ","){
                $string =  substr($string, strlen($string)-1,1);
            }

            $update = mysql_query("UPDATE tblvideo SET ratings='$string' WHERE id='$vid'");
            $insert = mysql_query("INSERT INTO tblrating_ip (userid,videoid,contestid, ipaddress) VALUES('$u','$vid','$cid','$ipaddress')") or die(mysql_errno());
            echo '<p style="color:green;">Thanks! You have given this video a rating of '. $choice . '</p>';
            exit();
        }
    }
} // post condition

?>