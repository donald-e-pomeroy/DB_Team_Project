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

$fail = 0;
// username and password sent from form
$mynewusername=$_POST['mynewusername'];
$mynewpassword=$_POST['mynewpassword'];
$mynewpassword2=$_POST['mynewpassword2'];

if($mynewpassword != $mynewpassword2){
	
	$fail = 1;
}

if((strlen($mynewusername)==0) or (strlen($mynewpassword)==0)){
	
	$fail=1;
}



$query="SELECT * FROM Users where username = '$mynewusername'";
$result=oci_parse($conn, $query);
oci_execute($result);

$count=oci_fetch_all($result, $arr);

if($count != 0){
	
	$fail = 1;
}

if($fail == 0)
{
	$query = "Insert into Users (username,pwd) values ('$mynewusername','$mynewpassword') ";
	$result = oci_parse($conn, $query);
	oci_execute($result);
	$err = oci_error($result);
	if($err){
		
		$fail =1;	
		oci_rollback($conn);
	}
	else{	
	oci_commit($conn);
	}
	
}

if($fail==0){
	echo "</html> <a href = \"login.html\">User Registered - Login</a></html>";
}else{
	echo "</html> <a href = \"login.html\">Failed to Register - Go Back</a></html>";
}

?>