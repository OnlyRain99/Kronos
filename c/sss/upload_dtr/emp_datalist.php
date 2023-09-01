<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';
	
	$typ = addslashes($_REQUEST['typ']);
	$idnm = addslashes($_REQUEST['idnm']);

	$sql="";
	$extsql = " ORDER BY `gy_emp_fullname` asc ";
	if($typ=="search" && strlen($idnm)>0){
		$sql="SELECT `gy_emp_code`,`gy_emp_fullname` FROM `gy_employee` WHERE `gy_emp_fullname` LIKE '%$idnm%' OR `gy_emp_mname` LIKE '%$idnm%' OR `gy_emp_code` LIKE '%$idnm%' ".$extsql;
	}else if($typ>0){
		$sql="SELECT `gy_emp_code`,`gy_emp_fullname` FROM `gy_employee` WHERE `gy_acc_id`=$typ ".$extsql;
	}

	$i=0; $nmlst = array(array());	
	if($sql!=""){
	$empsql=$link->query($sql);
	while($emprow=$empsql->fetch_array()){
		$nmlst[$i][0]=$emprow['gy_emp_code'];
		$nmlst[$i][1]=$emprow['gy_emp_fullname'];
		$i++;
	}
	}
	
	$link->close();

 for($i1=0;$i1<$i;$i1++){ ?>
 <option value="<?php echo $nmlst[$i1][0]; ?>"><?php echo $nmlst[$i1][1]; ?></option>
 <?php } ?>