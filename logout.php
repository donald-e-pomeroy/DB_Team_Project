<?php
session_start();
session_destroy();

$host="w4111c.cs.columbia.edu:1521/ADB"; // Host name
$username="ewd2106"; // Mysql username
$password="wofford1"; // Mysql password
$db_name="InterviewDB"; // Database name

ini_set('display_errors', 1);


// Connect to server and select databse.
$conn = oci_connect( $username, $password, $host);

if (!$conn) {
	echo "ERROR<br>";
	echo "<a href='login.html'> Go Back</a>";
	
    $e = oci_error(); 
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$query = "SELECT current_timestamp(6) FROM DUAL";
	$stid = oci_parse($conn,$query);
	oci_execute($stid);
	$row = oci_fetch_array($stid, OCI_NUM);
	$now = $row[0];
	$now = substr($now,0,28);
	
	$query = "INSERT into Times VALUES('$now')";
	$result = oci_parse($conn, $query);	
	oci_execute($result, OCI_COMMIT_ON_SUCCESS);
	$err = oci_error($result);
	if($err){
		oci_rollback($conn);	
		
		header("location:success.php");	
	}
	else{
		oci_commit($conn);
	}
	
	$myusername = $_SESSION['username'];
	$login = "logout";
	$query = "INSERT into LoginLogout(eventType,username,time) VALUES('$login','$myusername','$now')";
	$result = oci_parse($conn, $query);	
	oci_execute($result, OCI_COMMIT_ON_SUCCESS);
	$err = oci_error($result);
	if($err){
		oci_rollback($conn);	
	
		header("location:success.php");	
	}
	else{
		oci_commit($conn);
	}
	
	echo "<html>".$myusername." Logged Out at ".$now;
	echo "<br /><a href='login.html'> Go Back</a></html>";

?>