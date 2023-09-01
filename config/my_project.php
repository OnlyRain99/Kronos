<?php  

	include 'conn.php';

	//select in the database
	$find_my_data=$link->query("Select * From `gy_my_project` Where `gy_project`='1'");
	$my_project_detail=$find_my_data->fetch_array();

	$my_project_name = $my_project_detail['gy_project_name'];
	$my_project_address = $my_project_detail['gy_project_address'];
	$my_project_title = $my_project_detail['gy_system_title'];
	$my_project_origin = $my_project_detail['gy_year_origin'];
?>