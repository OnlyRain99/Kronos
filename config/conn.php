<?php  

	date_default_timezone_set('Asia/Manila');

	include("maximus-data.php");

	// Create connection
	$link = @mysqli_connect($servername,$username,$password,$dbname);

	if (!$link) {
		echo "
			<script>
				window.alert('Server Connection Timeout');
				window.location.href = 'https://www.sibscontactcenter.com/'
			</script>
		";
	}

?>