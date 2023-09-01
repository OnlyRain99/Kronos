<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$acc = $_REQUEST['acc'];
$colord = explode(',', $_REQUEST['colord']);
$brkcol = explode(',', $_REQUEST['brkcol']);

if(isset($_FILES['file']['name']) && isset($_FILES['bile']['name'])){
	$filename = "lilo_".date("YmdHis").".csv";
	$location = ''.$filename;
	$orgfname = $_FILES['file']['name'];
	$orgloc = ''.$orgfname;

	$bilename = "bibo_".date("YmdHis").".csv";
	$bloc = ''.$bilename;
	$orgbfname = $_FILES['file']['name'];
	$orgbloc = ''.$orgbfname;

	$file_extension = pathinfo($orgloc, PATHINFO_EXTENSION);
   $file_extension = strtolower($file_extension);
	$bile_extension = pathinfo($orgbloc, PATHINFO_EXTENSION);
   $bile_extension = strtolower($bile_extension);

$valid_ext = array("csv");
 if(in_array($file_extension,$valid_ext) && in_array($bile_extension,$valid_ext)){
      move_uploaded_file($_FILES['file']['tmp_name'],$location);
      move_uploaded_file($_FILES['bile']['tmp_name'],$bloc);

$row=0; $num=0; $title = array(); $newnm=""; $mrow=0;
$r1=0; $cont1 = array(array());
$a3=0; $csv=array(array()); $lgin=array(array()); $lgout=array(array()); $lgotd=array(array());
if(($handle = fopen($filename, "r")) !== FALSE){
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE){
	if($data[0]!=""&&$data[1]!=""&&$data[2]!=""&&$data[3]!=""&&$data[4]!=""){
		if($row==0&&$data[5]!=""){ $title=$data; $num = count($data); $row++; }
		else{
			for($c=0;$c<$num;$c++){ $cont1[$r1][$c] = $data[$c]; } $r1++;
		}
	}
	}
fclose($handle);
}
$brow=0; $bnum=0; $cont2=array(array()); $r2=0; //$brkin=array(array());
if(($handle = fopen($bloc, "r")) !== FALSE){
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE){
	if($data[0]!=""&&$data[1]!=""&&$data[2]!=""&&$data[3]!=""&&$data[4]!=""){
		if($brow==0){ $btitle=$data; $bnum=count($data); $brow++; }
		else{
			for($c=0;$c<$bnum;$c++){ $cont2[$r2][$c] = $data[$c]; } $r2++;
		}
	}
	}
fclose($handle);
}

unlink($location);
unlink($bloc);

$sort = array();
foreach($cont1 as $k=>$v){
    $sort[$colord[1]][$k] = $v[$colord[1]];
    $sort[$colord[2]][$k] = $v[$colord[2]];
}
array_multisort($sort[$colord[1]], SORT_ASC, $sort[$colord[2]], SORT_ASC,$cont1);

foreach($cont2 as $k=>$v){
    $sort[$brkcol[0]][$k] = $v[$brkcol[0]];
    $sort[$brkcol[1]][$k] = $v[$brkcol[1]];
    $sort[$brkcol[2]][$k] = $v[$brkcol[2]];
}
array_multisort($sort[$brkcol[1]], SORT_ASC, $sort[$brkcol[0]], SORT_ASC, $sort[$brkcol[2]], SORT_ASC,$cont2);

$newnm="";
for($a0=0;$a0<$r1;$a0++){
	if($cont1[$a0][$colord[1]]!=$newnm){ $mrow=0;
		for($a1=0;$a1<$num;$a1++){
			$csv[$a3][$a1] = $cont1[$a0][$a1];
		} $a3++;
	}
	$lgin[$a3-1][$mrow]=$cont1[$a0][$colord[3]]; $lgout[$a3-1][$mrow]=$cont1[$a0][$colord[4]]; $lgotd[$a3-1][$mrow]=$cont1[$a0][$colord[4]+1]; $mrow++;
	$newnm = $cont1[$a0][$colord[1]];
}
$break = $cont2;

$row1=0; $dbnmarr = array(array());
$sltsql=$link->query("SELECT `gy_employee`.`gy_emp_code` AS `empcode`,`gy_employee`.`gy_emp_fullname` AS `empfn` From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_employee`.`gy_acc_id`='$acc' AND `gy_user`.`gy_user_status`=0 ");
	while($sltrow=$sltsql->fetch_array()){
		$dbnmarr[$row1][0] = $sltrow['empcode'];
		$dbnmarr[$row1][1] = $sltrow['empfn'];
		$row1++;
	}
?>

<div class="col-md-12">
	<div class="card">
		<div class="card-header"><strong class="card-title mb-3"><center>Review Before Uploading <span id="4loading"></span></center></strong></div>
		<div class="card-body">
			<div class="row">
				<div class="col-md-12" id="b4utbl">
					<div class="table-responsive">
						<table class="table table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
							<thead>
								<tr>
									<th scope="col" style="padding: 0px;">
										<div class="form-floating">
                  					<select class="form-select" id="b4u_code" onchange="updtbl()" required>
                  					<?php for($i=0;$i<count($title);$i++){ ?>
                     					<option class="bg-info" value="<?php echo $i; ?>" <?php if($i==$colord[0]){echo "selected";}?>><?php echo $title[$i]; ?></option>
                    					<?php } ?>
                  					</select>
                  					<label class="b4u_code">SiBS ID</label>
               					</div>
									</th>
									<th scope="col" style="padding: 0px;">
										<div class="form-floating">
                  					<select class="form-select" id="b4u_name" onchange="updtbl()" required>
                  					<?php for($i=0;$i<count($title);$i++){ ?>
                     					<option class="bg-info" value="<?php echo $i; ?>" <?php if($i==$colord[1]){echo "selected";}?>><?php echo $title[$i]; ?></option>
                    					<?php } ?>
                  					</select>
                  					<label class="b4u_name">Log Name</label>
               					</div>										
									</th>
									<th scope="col" style="padding: 0px;">
										<div class="form-floating">
                  					<select class="form-select" id="b4u_date" onchange="updtbl()" required>
                  					<?php for($i=0;$i<count($title);$i++){ ?>
                     					<option class="bg-info" value="<?php echo $i; ?>" <?php if($i==$colord[2]){echo "selected";}?>><?php echo $title[$i]; ?></option>
                    					<?php } ?>
                  					</select>
                  				<label class="b4u_date">Log Date</label>
               					</div>
									</th>
									<th scope="col" style="padding: 0px;">
										<div class="form-floating">
                  					<select class="form-select" id="b4u_logi" onchange="updtbl()" required>
                  					<?php for($i=0;$i<count($title);$i++){ ?>
                     					<option class="bg-info" value="<?php echo $i; ?>" <?php if($i==$colord[3]){echo "selected";}?>><?php echo $title[$i]; ?></option>
                    					<?php } ?>
                  					</select>
                  				<label class="b4u_logi">Log In</label>
               					</div>
									</th>
									<th scope="col" style="padding: 0px;">
										<div class="form-floating">
                  					<select class="form-select" id="b4u_logo" onchange="updtbl()" required>
                  					<?php for($i=0;$i<count($title);$i++){ ?>
                     					<option class="bg-info" value="<?php echo $i; ?>" <?php if($i==$colord[4]){echo "selected";}?>><?php echo $title[$i]; ?></option>
                    					<?php } ?>
                  					</select>
                  				<label class="b4u_logo">Log Out</label>
               					</div>
									</th>
									<th scope="col" style="padding: 0px;">
										<div class="form-floating">
                  					<select class="form-select" id="b4u_bout" onchange="updtbl()" required>
                  						<?php for($i=0;$i<count($btitle);$i++){ ?>
                     					<option class="bg-info" value="<?php echo $i; ?>" <?php if($i==$brkcol[2]){echo "selected";}?>><?php echo $btitle[$i]; ?></option>
                    						<?php } ?>
                  					</select>
                  					<label class="b4u_bout">Break Out</label>
               					</div>
									</th>
									<th scope="col" style="padding: 0px;">
										<div class="form-floating">
                  					<select class="form-select" id="b4u_bin" onchange="updtbl()" required>
                  					<?php for($i=0;$i<count($btitle);$i++){ ?>
                     					<option class="bg-info" value="<?php echo $i; ?>" <?php if($i==$brkcol[3]){echo "selected";}?>><?php echo $btitle[$i]; ?></option>
                    					<?php } ?>
                  					</select>
                  					<label class="b4u_bin">Break In</label>
              						</div>
									</th>
									<th scope="col" class="text-center text-nowrap ">Status</th>
								</tr>
							</thead>
							<tbody>
									<datalist id="dtlstopt">
										<?php for($i=0;$i<$row1;$i++){ ?>
											<option value="<?php echo $dbnmarr[$i][0]; ?>"><?php echo $dbnmarr[$i][1]; ?></option>
										<?php } ?>
									</datalist>
									<?php for($i=0;$i<$a3;$i++){$clrrw="false"; if(array_search($csv[$i][$colord[0]], array_column($dbnmarr, 0))>=0){$clrrw="true";} ?>
								<tr id="<?php echo "tridx_".$i; ?>">
									<td class="text-center text-nowrap " style="padding: 0px;">
										<input class="form-control" value="<?php echo $csv[$i][$colord[0]]; ?>" list="dtlstopt" id="inpid_<?php echo $i;?>" onkeyup="chkvld(this)">
									</td>
									<td class="text-center text-nowrap " id="lognm_<?php echo $i;?>"><?php echo$csv[$i][$colord[1]]; ?></td>
									<td class="text-center text-nowrap "><?php echo explode(" ",explode(" - ", $csv[$i][$colord[2]])[0])[0]; ?></td>
									<td class="text-center text-nowrap " style="padding: 0px;">
										<select class="form-control" disabled id="selli_<?php echo $i; ?>" required>
											<?php $i1=0; while(isset($lgin[$i][$i1])==true){ ?>
											 <option value="<?php echo$lgin[$i][$i1];?>"class="bg-info"><?php echo chktime($lgin[$i][$i1]);?></option>
											<?php $i1++; } ?>
										</select>
									</td>
									<td class="text-center text-nowrap " style="padding: 0px;">
										<select class="form-control" disabled id="sello_<?php echo $i; ?>" required>
											<?php $i1=0; while(isset($lgout[$i][$i1])==true){ ?>
											 <option class="bg-info" value="<?php echo $lgout[$i][$i1];?>" title="<?php echo $lgotd[$i][$i1]; ?>" selected><?php echo chktime($lgout[$i][$i1]); ?></option>
											<?php $i1++; } ?>
										</select>
									</td>
									<td class="text-center text-nowrap " style="padding: 0px;">
										<select class="form-control" disabled id="brkout_<?php echo $i; ?>" required>
										<?php
											for($a0=0;$a0<$r2;$a0++){
												if(isset($break[$a0][$brkcol[1]])!==false){ if($csv[$i][$colord[1]]==$break[$a0][$brkcol[1]] && $break[$a0][$brkcol[3]+1]=="Lunch Break"){
													?><option class="bg-info" <?php if($break[$a0][$brkcol[3]+1]=="Lunch Break"){echo"selected";} ?> value="<?php echo date("Y-m-d H:i:s", strtotime($break[$a0][$brkcol[0]]." ".$break[$a0][$brkcol[2]])) ?>" title="<?php echo $break[$a0][$brkcol[3]+1]; ?>"><?php echo chktime($break[$a0][$brkcol[2]]); ?></option><?php
													for($a1=0;$a1<$bnum;$a1++){ unset($break[$a0][$a1]); }
												}}
											}
										?>
										</select>
									</td>
									<td class="text-center text-nowrap " style="padding: 0px;">
										<select class="form-control" disabled id="brkin_<?php echo $i; ?>" required>
										<?php
											for($a0=0;$a0<$r2;$a0++){
												if(isset($cont2[$a0][$brkcol[1]])!==false){if($csv[$i][$colord[1]]==$cont2[$a0][$brkcol[1]] && $cont2[$a0][$brkcol[3]+1]=="Lunch Break"){
													?><option class="bg-info" <?php if($cont2[$a0][$brkcol[3]+1]=="Lunch Break"){echo"selected";} ?> value="<?php echo date("Y-m-d H:i:s", strtotime($cont2[$a0][$brkcol[0]]." ".$cont2[$a0][$brkcol[3]])) ?>" title="<?php echo $cont2[$a0][$brkcol[3]+1]; ?>"><?php echo chktime($cont2[$a0][$brkcol[3]]); ?></option><?php
													for($a1=0;$a1<$bnum;$a1++){ unset($cont2[$a0][$a1]); }
												}}
											}
										?>
										</select>
									</td>
									<td style="padding: 0px;" id="btnstt_<?php echo $i; ?>"><?php if($clrrw=="true"){ ?><button class="btn btn-success btn-block" title="Ready for Upload" id="btnupd_<?php echo $i; ?>" onclick="updttrk(this)"> Upload <i class="fa-solid fa-circle-arrow-up"></i></button><?php }else{ ?><button class="btn btn-secondary btn-block" title="Row Value not Acceptable"> Invalid <i class="fa-solid fa-triangle-exclamation"></i></button><?php } ?></td>
								</tr>
								<input type="hidden" id="hiddate_<?php echo $i;?>" value="<?php echo explode(" ",explode(" - ", $csv[$i][$colord[2]])[0])[0]; ?>">
									<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="card-footer text-muted"><button class="btn btn-success btn-block" onclick="uplallval(<?php echo $a3; ?>)"><span id="spnupall"><i class="fa-solid fa-file-arrow-up"></i></span> Upload All Valid DTR</button></div>
	</div>
</div>
<?php
}else{ echo "File Extension Not Valid!"; }
} $link->close();
exit; ?>