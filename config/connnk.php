<?php
    $servername = "localhost";
    $username = "u292602927_kronosadmin";
    $password = "Vhv1|olw#0q";
    $dbname = "u292602927_newkaizen";
    $dbticket = @mysqli_connect($servername,$username,$password,$dbname);
   //if (!$dbticket) {
    //   echo "Server Connection Error ". mysqli_connect_error(); exit();
   //}
    if (!$dbticket) { echo "Server Connection Error"; exit(); }
?>