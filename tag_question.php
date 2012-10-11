<?php
		
		
		session_start();
		if(!session_is_registered(myusername)){
			header("location:login.html");
		}		
		
//		ini_set('display_errors', 1);
		
		
		
		//Create connection to Oracle
		$conn = oci_connect("ewd2106", "wofford1", "//w4111c.cs.columbia.edu:1521/ADB");
		if (!$conn) {
		   $m = oci_error();
		   echo $m['message'], "\n";
		   exit;
		}
		
		$qid = $_REQUEST['qid'];
		$tag = $_REQUEST['tag'];
		$username = $_SESSION['username'];
		
		
		$query = "SELECT current_timestamp(6) FROM DUAL";
		$stid = oci_parse($conn,$query);
		oci_execute($stid);
		$row = oci_fetch_array($stid, OCI_NUM);
		$now = $row[0];
		$now = substr($now,0,28);
		$query = "INSERT INTO Keywords VALUES ('$tag')";
		$stid = oci_parse($conn, $query);
		$result = oci_execute($stid);
		$query = "INSERT INTO Tagged VALUES ($qid,'$tag')";
		$stid = oci_parse($conn, $query);
		$result = oci_execute($stid);
		
			$query = "INSERT INTO Times VALUES (to_timestamp('$now'))";
			$stid = oci_parse($conn, $query);
			$result = oci_execute($stid);
			$query = "INSERT INTO QuestionAction VALUES ('tagged',$qid,'$username',to_timestamp('$now'))";
			$stid = oci_parse($conn, $query);
			$result = oci_execute($stid);
			if($result){
				echo "Successfully posted your answer. <a href=\"view.php?qid=$qid\" >Back</a>";
			}
			else{
				echo"Failed to post your answer.";

			}
		
		
?>