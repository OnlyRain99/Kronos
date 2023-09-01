<?php  
	include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    if (isset($_POST['submit'])) {
        $date = $_POST['date'];
        $time = $_POST['time'];

        if ($date == "" || $time == "") {
            echo "empty";
        }else{
            echo $date." ".$time; 
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Test</title>
</head>
<body>
    <form method="post" enctype="multipart/form-data">
        <input type="date" name="date">
        <input type="time" name="time">
        <button type="submit" name="submit">submit</button>
    </form>
</body>
</html>
