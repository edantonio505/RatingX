<?php
date_default_timezone_set("America/New_York");
$db_conx = mysqli_connect("10.16.112.124", "u1148742_edgar", "Ed23021989", "db1148742_showoff");
// Evaluate the connection
if (mysqli_connect_errno()) {
    echo mysqli_connect_error();
    exit();
} else {
	//echo "Successful database connection, happy coding!!!";
}

?>