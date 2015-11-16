<?php
$GLOBALS['page_render_error'] = '';
date_default_timezone_set("Asia/Dhaka");
include "admin/db.php";
include_once("admin/check_login_status.php");
function avgRating($videoId){
    $rating='';
    $sql = mysql_query("SELECT ratings FROM tblvideo where id='$videoId'");
    while ($row = mysql_fetch_array($sql)) {
        $myNums = $row["ratings"];
        $kaboom = explode(",",$myNums);
        $count = count($kaboom);
        $sum = array_sum($kaboom);
        $avg = $sum / $count;
        $roundit = floor($avg);

        if ($roundit == 0){
            $rating .='<p style="font-size: .8em; font-weight: normal;margin-bottom:0px;padding:0px;">Yet not rated.</p>';
            $rating .='<p style="color: grey;margin:0px">
                        <span class="glyphicon glyphicon-star-empty" style="color: #ccc; font-size: 1.5em;"></span>
                        <span class="glyphicon glyphicon-star-empty" style="color: #ccc; font-size: 1.5em;"></span>
                        <span class="glyphicon glyphicon-star-empty" style="color: #ccc; font-size: 1.5em;"></span>
                        <span class="glyphicon glyphicon-star-empty" style="color: #ccc; font-size: 1.5em;"></span>
                        <span class="glyphicon glyphicon-star-empty" style="color: #ccc; font-size: 1.5em;"></span>
                        </p>';
        } else if ($roundit == 1){
            $rating .='<p class="">Avg rating'. $roundit.' Rated by '.$count.' people.</p>';

        } else if ($roundit > 1){
            $rating .='<p style="font-size: .8em; font-weight: normal; margin-bottom: 0;">Avg rating '.$roundit.' Rated by '.$count.' people.</p>';
            for ($i=1 ; $i<=5; $i++){
                if ($roundit>= $i){
                    $rating .='<span class="glyphicon glyphicon-star" style="color: #ea4f35; font-size: 1.5em; "></span>';
                } else {
                    $rating .='<span class="glyphicon glyphicon-star-empty" style="color: #ea4f35; font-size: 1.5em; "></span>';
                }
            }
            $rating .='</p>';

        } else {
            $rating .='Sorry There is an error in the system.. Please try refreshing the page';
        }
        return $rating;
    }
    mysql_close();
}

function getContestField($field, $contestid) {
    $query = "SELECT `$field` FROM tblvideocontests WHERE `id`='" . $contestid . "'";
    if ($query_run = mysql_query($query)) {
        if ($query_result = mysql_result($query_run, 0, $field)) {
            return $query_result;
        }
    }
}
function getUserField($field, $userId) {
    $query = "SELECT `$field` FROM tblusers WHERE `id`='" . $userId . "'";
    if ($query_run = mysql_query($query)) {
        if ($query_result = mysql_result($query_run, 0, $field)) {
            return $query_result;
        }
    }
}
function limit_text($text, $limit) {
    $strings = $text;
      if (strlen($text) > $limit) {
          $words = str_word_count($text, 2);
          $pos = array_keys($words);
          if(sizeof($pos) >$limit)
          {
            $text = substr($text, 0, $pos[$limit]) . '...';
          }
          return $text;
      }
      return $text;
    }
function check_utf8($str) { 
    $len = strlen($str); 
    for($i = 0; $i < $len; $i++){ 
        $c = ord($str[$i]); 
        if ($c > 128) { 
            if (($c > 247)) return false; 
            elseif ($c > 239) $bytes = 4; 
            elseif ($c > 223) $bytes = 3; 
            elseif ($c > 191) $bytes = 2; 
            else return false; 
            if (($i + $bytes) > $len) return false; 
            while ($bytes > 1) { 
                $i++; 
                $b = ord($str[$i]); 
                if ($b < 128 || $b > 191) return false; 
                $bytes--; 
            } 
        } 
    } 
    return true; 
}        
?>