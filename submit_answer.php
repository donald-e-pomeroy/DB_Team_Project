<?php
		session_start();
		if(!session_is_registered(myusername)){
			header("location:login.html");
		}
//		ini_set('display_errors', 1);
		
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
			$answer_text = $_REQUEST['answer_text'];
			$answer_text = str_replace("'", "''", $answer_text);
			$username = $_SESSION['username'];
			$qid = $_REQUEST['qid'];
			$query = "SELECT answerseq.nextval, current_timestamp(6) FROM DUAL";
			$stid = oci_parse($conn,$query);
			oci_execute($stid);
			$row = oci_fetch_array($stid, OCI_NUM);
			$aid = $row[0];
			$now = $row[1];
			$now = substr($now,0,28);
			$query = "INSERT INTO Answers VALUES ('$aid', '$qid', '$answer_text', 0, 0)";
			$stid = oci_parse($conn, $query);
			$result = oci_execute($stid);
			if($result){
				$query = "INSERT INTO Times VALUES (to_timestamp('$now'))";
				$stid = oci_parse($conn, $query);
				$result = oci_execute($stid);
				$query = "INSERT INTO AnswerAction VALUES ('posted',$aid,'$username',to_timestamp('$now'))";
				$stid = oci_parse($conn, $query);
				$result = oci_execute($stid);
				if($result){
					echo "Successfully posted your answer. <a href=\"view.php?qid=$qid\" >Back</a>";
				}
				else{
					echo"Failed to post your answer.";

				}
			}
			else{
				echo"Failed to post your answer.";
			}

	?>
</body>
