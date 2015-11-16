<?php 
date_default_timezone_set("America/New_York");
include_once 'admin/db.php';
include_once("admin/check_login_status.php");
$contestId = '';
if (isset($_GET['contestId'])){
    $contestId = $_GET['contestId'];
    $select = mysql_query("select * from tblvideocontests WHERE id='$contestId' Limit 1");
    while ($fetch1 = mysql_fetch_array($select)) {
        $endDate = $fetch1['end_date'];   
    }    
}

$targetTime = $endDate;
$currentTime = new DateTime("now", new DateTimeZone("America/New_York"));
$targetTime = new DateTime($targetTime, new DateTimeZone("America/New_York"));
$arr = array("currentTime"=>$currentTime->format("U"), "targetTime"=>$targetTime->format("U"));
echo json_encode($arr);
?>