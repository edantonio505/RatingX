<?php
// Ajax calls this REGISTRATION code to execute
if (isset($_POST["cn"])) {
    // CONNECT TO THE DATABASE
    include_once("db_conx.php");
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    
    $cn =  $_POST['cn'];
    $sd =  $_POST['sd'];
    $ed =  $_POST['ed'];
    $d =  $_POST['d'];

    if ($cn == "" || $sd == "" || $ed == "") {
        echo "The form submission is missing values.";
        exit();
    } else {
        // Add user info into the database table for the main site table
        $sql = "INSERT INTO tblvideocontests (contestname, start_date, end_date, description, live)       
                                       VALUES('$cn','$sd','$ed','$d','no')";
        $query = mysqli_query($db_conx, $sql);

        echo "contest_add_success";
        exit();
    }
    exit();
}
?>