<?php
		session_start();
		if(!session_is_registered(myusername)){
			header("location:login.html");
		}
		
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<style type="text/css">
	* {
		font-family:helvetica;
	}
	.label {
		font-variant:small-caps;
		font-size:100%;
	}
	</style>
	</head>
	<body>
		<?php
				//Create connection to Oracle
				$conn = oci_connect("ewd2106", "wofford1", "//w4111c.cs.columbia.edu:1521/ADB");
				if (!$conn) {
				   $m = oci_error();
				   echo $m['message'], "\n";
				   exit;
				}
				$aid = $_REQUEST['aid'];
				$qid = $_REQUEST['qid'];
				$username = $_SESSION['username'];
				$vote = $_REQUEST['vote'];
				
				$query = "SELECT current_timestamp(6) FROM dual";
				$stid = oci_parse($conn,$query);
				oci_execute($stid);
				$row = oci_fetch_array($stid, OCI_NUM);
				$now = $row[0];
				$now = substr($now,0,28);
				
				if($vote == 1){
					$query = "INSERT INTO Times VALUES (to_timestamp('$now'))";
					$stid = oci_parse($conn, $query);
					$result = oci_execute($stid);
					$query = "INSERT INTO AnswerAction VALUES ('upvote',$aid,'$username',to_timestamp('$now'))";
					$stid = oci_parse($conn, $query);
					$result = oci_execute($stid);
					
					
					$query = "UPDATE Answers A SET A.upvotes = A.upvotes + 1 WHERE aid = $aid";
					$stid = oci_parse($conn,$query);
					oci_execute($stid);
					
				}
				else if($vote == 2){
					$query = "INSERT INTO Times VALUES (to_timestamp('$now'))";
					$stid = oci_parse($conn, $query);
					$result = oci_execute($stid);
					$query = "INSERT INTO AnswerAction VALUES ('downvote',$aid,'$username',to_timestamp('$now'))";
					$stid = oci_parse($conn, $query);
					$result = oci_execute($stid);
					
					
					$query = "UPDATE Answers A SET A.downvotes = A.downvotes + 1 WHERE aid = $aid";
					$stid = oci_parse($conn,$query);
					oci_execute($stid);
				}

				header('Location: view.php?qid='.$qid);
				
		?>
	</body>