<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    //if($myaccount == 22){
       // include 'newkaizen.php';
    //}else{
        include 'mykronos.php';
    //}
    $link->close();
?>