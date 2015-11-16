<?php
    include_once("check_login_status.php");
    $sql = "SELECT * FROM countries";
    $query = mysqli_query($db_conx, $sql);
    $country_list = "";
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        $cn = $row["country_name"];
        $cc = $row["country_code"];
        $country_list .='<option value="'.$cn.'">'.$cn.'</option>';
    }
    echo $country_list;
?>
 