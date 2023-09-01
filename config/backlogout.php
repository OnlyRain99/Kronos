<?php
if(isset($_SERVER['HTTP_REFERER'])){
if($_SERVER['HTTP_REFERER'] == "https://kaizen.sibs-flow.info/"){
	session_start();
	if (isset($_SESSION['fus_user_id'])) {
        if($_SESSION['fus_user_type'] == "1"){
            header("location: ../c/u/logout.php");
        }else if($_SESSION['fus_user_type'] == "2"){
            header("location: ../c/s/d/logout.php");
        }else if($_SESSION['fus_user_type'] == "3"){
            header("location: ../c/ss/d/logout.php");
        }else if($_SESSION['fus_user_type'] == "4"){
            header("location: ../c/sss/logout.php");
        }else if($_SESSION['fus_user_type'] == "5"){
            header("location: ../c/v/logout.php");
        }else if($_SESSION['fus_user_type'] >= "6" && $_SESSION['fus_user_type'] <= "11"){
            header("location: ../c/sx/logout.php");
        }
        exit();
    }
header("location: https://kaizen.sibs-flow.info/");    
}
}
?>