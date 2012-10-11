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
a.label {
	font-variant:small-caps;
	font-size:60%;
}
</style>
</head>
<body>
<h1><a href='success.php'>InterviewDB</a></h1><br />

<?php
//ini_set('display_errors', 'On');
//Create connection to Oracle
$conn = oci_connect("ewd2106", "wofford1", "//w4111c.cs.columbia.edu:1521/ADB");
if (!$conn) {
   $m = oci_error();
   echo $m['message'], "\n";
   exit;
}

$username = $_SESSION['username'];


$qid = $_REQUEST['qid'];
$query = "SELECT * FROM Questions Q WHERE qid = $qid";
$stid = oci_parse($conn, $query);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_NUM);
$qid = $row[0];
$text = $row[1];
$interview_date = $row[2];
$company = $row[3];
$position = $row[4];
$difficulty = $row[5];
$accesses = $row[6];


$query = "SELECT T.keyword FROM Questions Q, Tagged T WHERE Q.qid = $qid AND Q.qid = T.qid";
$stid = oci_parse($conn, $query);
oci_execute($stid);
$keywords = "";
if($row = oci_fetch_array($stid, OCI_NUM)){
	$k = $row[0];
	$keywords .= $k;
}
while($row = oci_fetch_array($stid, OCI_NUM)){
	$k = $row[0];
	$keywords .= ", $k";
}

$q = "SELECT Q.username, Q.time FROM QuestionAction Q WHERE Q.qid = $qid AND eventType = 'posted'";
$s = oci_parse($conn, $q);
oci_execute($s);
$r = oci_fetch_array($s, OCI_NUM);
$question_user = $r[0];
$question_time = $r[1];

?>
<br />

<table cellpadding = '5px' width = '800px'>
	<tr>
		<td>
			<h2>Question:</h2>
		</td>
	</tr>
	<tr>
		<td>
			<table>

				<col width = 200px>
				<col width = 200px>
				<col width = 200px>
				<col width = 200px>
				<tr>
					<td>
						<span class="label">employer:</span><br />
						<?php 
							echo $company; 
						?>
					</td>
					<td style="text-align:left;">
						<span class="label">position: </span><br />
						<?php
							echo $position; 
						?>
					</td>
					<td style="text-align:left;">
						<span class="label">interview date:</span><br />
						<?php
							echo $interview_date;
						?>
					</td>
					<td style="text-align:right;">
						<span class="label">difficulty: </span><br />
						<?php
							for($i = 0; $i < $difficulty; $i++){
								echo "<big>*&nbsp;&nbsp;</big>";
							}
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<hr />
			<br />
		 	<div style="margin-left:100px;">
			<?php
				echo $text;
			?>
			</div>
			<br /><br />
			<div style="text-align:right;">
			<span class="label" >tagged with:</span>&nbsp;
			<?php
				echo "$keywords";
				
				if($question_user == $username){
					echo "<br /><a class='label' href='delete_question.php?qid=$qid'>DELETE</a>";
				}
			?>
			<br>
			<form action = "tag_question.php">
			<input type = "text" value = "Tag this question!" name = "tag" >
			<input type = "hidden" value = "<?php echo $qid; ?>" name = "qid" >
			<input type = "submit" value = "Add Tag" >
			</form>
			</div>
			<hr />
		</td>
	</tr>
	<tr>
		<td>
			<table style="width:100%;"><tr>
			<td style="width:50%;">
			<div style="text-align:right;">
				Posted by 
				<?php
					echo $question_user;
				?>
				at 
				<?php
					echo $question_time;
				?>
			</div>
			</td>
			<td style="width:50%;">
			<div style="text-align:right;">
				Viewed 
				<?php
					echo $accesses;
				?>
				&nbsp;times.
			</div>
			</td>
			</tr></table>
			
		</td>
	</tr>
	<tr height="50px">
	</tr>
	<tr>
		<td>
			<h2>Answers:</h2>
		</td>
	</tr>

<?php
	$query = "SELECT * FROM Answers A WHERE A.qid = $qid ORDER BY upvotes-downvotes DESC";
	$stid = oci_parse($conn, $query);
	oci_execute($stid);
	while($row = oci_fetch_array($stid, OCI_NUM)){
		$aid = $row[0];
		$text = $row[2];
		$upvotes = $row[3];
		$downvotes = $row[4];
		
		$q = "SELECT A.username, A.time FROM AnswerAction A WHERE A.aid = $aid AND eventType = 'posted'";
		$s = oci_parse($conn, $q);
		oci_execute($s);
		$r = oci_fetch_array($s, OCI_NUM);
		$answer_user = $r[0];
		$answer_time = $r[1];
		
		echo "
		
			<tr>
				<td>
					<table>
						<col width = 200px>
						<col width = 200px>
						<col width = 200px>
						<col width = 200px>
						<tr>
							<td>
								<span class=\"label\">answered by:</span><br />
								$answer_user
							</td>
							<td style=\"text-align:left;\">
								<span class=\"label\">posted at: </span><br />
								$answer_time
							</td>
							<td style=\"text-align:right;\">
								<span class=\"label\">votes up: </span><br />
								<a href=\"vote.php?aid=$aid&qid=$qid&vote=1\">$upvotes</a>
							</td>
							<td style=\"text-align:right;\">
								<span class=\"label\">votes down: </span><br />
								<a href=\"vote.php?aid=$aid&qid=$qid&vote=2\">$downvotes</a>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<hr />
					<br />
				 	<div style=\"margin-left:100px;\">
					$text
					</div>";
	if($answer_user == $username){
		echo "<div style='text-align:right;'><a class='label' href='delete_answer.php?aid=$aid&qid=$qid'>DELETE</a></div>";
	}
	echo				"<br />
					<hr />
				</td>
			</tr>
			</tr>
			<tr height=\"50px\">
			</tr>
			";
			
		
	}
?>

<tr>
	<td>
		<h2>Submit an answer:</h2>

		<form action="submit_answer.php" method="post">
			<textarea name="answer_text" style="width:100%; height:100px;"></textarea><br />
			<input type="hidden" name="qid" value="<?php echo $qid;?>">
			<input type="submit" style="text-align:right;" value="Submit">
		</form>
	<td>
</tr>

</table>


<?php
	$query = "UPDATE Questions Q SET Q.accesses = Q.accesses + 1 WHERE Q.qid = $qid";
	$stid = oci_parse($conn, $query);
	oci_execute($stid);
	$query = "SELECT current_timestamp(6) FROM dual";
	$stid = oci_parse($conn,$query);
	oci_execute($stid);
	$row = oci_fetch_array($stid, OCI_NUM);
	$now = $row[0];
	$now = substr($now,0,28);
	
	$query = "INSERT INTO Times VALUES (to_timestamp('$now'))";
	$stid = oci_parse($conn, $query);
	$result = oci_execute($stid);
	$query = "INSERT INTO QuestionAction VALUES ('accessed',$qid,'$username',to_timestamp('$now'))";
	$stid = oci_parse($conn, $query);
	$result = oci_execute($stid);
?>

</body>
</html>

