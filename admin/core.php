<?php
//ini_set('mbstring.substitute_character', "none");
//ini_set('mbstring.language' , "Neutral");
//ini_set('mbstring.internal_encoding' , "UTF-8");
//ini_set('mbstring.encoding_translation', "On");
//ini_set('mbstring.http_input' ,"auto");
//ini_set('mbstring.http_output' , "UTF-8");
//ini_set('mbstring.detect_order' , "auto");
//ini_set('mbstring.substitute_character' , "none");
//ini_set('default_charset' , "UTF-8");
date_default_timezone_set("Asia/Dhaka");
$current_file = $_SERVER['SCRIPT_NAME'];

if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
    $http_referer = $_SERVER['HTTP_REFERER'];
}

function getUserField($field) {
    $query = "SELECT `$field` FROM tblUsers WHERE `id`='" . $_SESSION['user_id'] . "'";
    if ($query_run = mysql_query($query)) {
        if ($query_result = mysql_result($query_run, 0, $field)) {
            return $query_result;
        }
    }
}
function getAddCode($addfield, $addid) {
    $query = "SELECT `$addfield` FROM advertise WHERE `id`='" . $addid . "'";
    if ($query_run = mysql_query($query)) {
        if ($query_result = mysql_result($query_run, 0, $addfield)) {
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