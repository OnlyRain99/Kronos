<?php

	if(!isset($_SESSION['fus_user_id'])){
        header("location: ../index");
    }else if (!$_SESSION['fus_user_type']) {
        header("location: ../index");
    }

?>