<?php
		session_start();
		if(!session_is_registered(myusername)){
			header("location:login.html");
		}
		
		ini_set('display_errors', 1);
		
		
		//Create connection to Oracle
		$conn = oci_connect("ewd2106", "wofford1", "//w4111c.cs.columbia.edu:1521/ADB");
		if (!$conn) {
		   $m = oci_error();
		   echo $m['message'], "\n";
		   exit;
		}
		
		$qid = $_REQUEST['qid'];
		
		
		$query = "DELETE FROM Questions WHERE qid = $qid";
		$stid = oci_parse($conn,$query);
		$result = oci_execute($stid);
		
		if($result){
			header('Location: success.php');
		}
		else{
			echo "Failed";
		}
		
?>