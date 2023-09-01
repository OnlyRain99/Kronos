<?php
 
	include("../../../config/conn.php");
    include("../../../config/function.php");
    include("session.php");
 
	$sibsid=$_GET['sibsid'];

	if ($sibsid == "") {
		echo "<option></option>";
	}else{
		$res=$link->query("SELECT `gy_emp_code`,`gy_emp_fullname` FROM `gy_employee` WHERE CONCAT(`gy_emp_code`,`gy_emp_fullname`) like '%$sibsid%' ORDER BY `gy_emp_fullname` ASC LIMIT 30");
	 	$count=$res->num_rows;

		if(!$res){
			echo mysqli_error($db);
		}else if ($count == 0) {
			echo "<option value='item not found'>";
		}else{
			while($row=$res->fetch_array()){
				echo "<option value='".$row['gy_emp_code']."'>".$row['gy_emp_fullname'];
			}
		}
	}
 
	
 
?>
</option>