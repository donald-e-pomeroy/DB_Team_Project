<?php
$host="w4111c.cs.columbia.edu:1521/ADB"; // Host name
$username="ewd2106"; // Mysql username
$password="wofford1"; // Mysql password
$db_name="InterviewDB"; // Database name



// Connect to server and select databse.
$conn = oci_connect( $username, $password, $host);
if (!$conn) {
	echo "<html> ERROR <br></html>";
	echo "<html> <a href="."login.html"."> Return to Login</a> </html>";
	
    $e = oci_error(); 
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}


// username and password sent from form
$myusername=$_POST['myusername'];
$mypassword=$_POST['mypassword'];



$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);


$query="SELECT * FROM Users where username = '$myusername' and pwd = '$mypassword'";
$result=oci_parse($conn, $query);
oci_execute($result);


// Mysql_num_row is counting table row
$count=oci_fetch_all($result, $arr);
// If result matched $myusername and $mypassword, table row must be 1 row

if($count!=0){
	$timestamp=time();
// Register $myusername, $mypassword and redirect to file "login_success.php"
	session_register("myusername");
	$_SESSION['username'] = $myusername;
	session_register("mypassword");
	
	//get current time
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
		header("location:index.html");	
	}
	else{
		oci_commit($conn);
	}
	
	$login = "login";
	$query = "INSERT into LoginLogout(eventType,username,time) VALUES('$login','$myusername','$now')";
	$result = oci_parse($conn, $query);	
	oci_execute($result, OCI_COMMIT_ON_SUCCESS);
	$err = oci_error($result);
	if($err){
		oci_rollback($conn);	
		header("location:login.html");	
	}
	else{
		oci_commit($conn);
	}
		
  	oci_close($conn);	
	header("location:success.php");
}
else {
		
  	oci_close($conn);	
	echo "<html>Wrong Username or Password<br></html>";
	echo "<html> <a href="."index.html"."> Return to Login</a> </html>";
	
}
ob_end_flush();
?>