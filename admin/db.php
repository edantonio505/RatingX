<?php
date_default_timezone_set("America/New_York");
$mysql_hostname = "10.16.112.124";
$mysql_user = "u1148742_edgar";
$mysql_password = "Ed23021989";
$mysql_database = "db1148742_showoff";
$connection = mysql_connect($mysql_hostname, $mysql_user, $mysql_password) 
or die("Opps some thing went wrong");
mysql_select_db($mysql_database, $connection) or die("Opps some thing went wrong");
mysql_query("SET CHARACTER SET 'utf8'", $connection);
mysql_set_charset('utf8',$connection); 
?>