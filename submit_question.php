<?php
		session_start();
		if(!session_is_registered(myusername)){
			header("location:login.html");
		}
		
$host="w4111c.cs.columbia.edu:1521/ADB"; // Host name
$username="ewd2106"; // Mysql username
$password="wofford1"; // Mysql password




// Connect to server and select databse.
$conn = oci_connect( $username, $password, $host);
if (!$conn) {
	echo "<html> ERROR <br></html>";
	echo "<html> <a href="."index.html"."> Return to Login</a> </html>";
	
    $e = oci_error(); 
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$mytext = $_POST['myquestiontext'];
$myinterviewday = $_POST['myday'];
$myinterviewmonth = $_POST['mymonth'];
$myinterviewyear = $_POST['myyear'];
$mycompany = $_POST['mycompanyname'];
$myposition = $_POST['mypositionname'];
$mydifficulty = $_POST['mydifficulty'];

//Date Format:  YYYY-MM-DD
//remove from final version

$myinterviewdate = 	$myinterviewyear."-".$myinterviewmonth."-".$myinterviewday;

$failure = 0;


$sql = "Insert INTO Questions (qid,text, interview_date, company, position, difficulty, accesses) 
VALUES (questionseq.nextval,'$mytext', to_date('$myinterviewdate' , 'YYYY-MM-DD'),'$mycompany','$myposition',$mydifficulty, 0)";


$result = oci_parse($conn, $sql);
oci_execute($result);
$err = oci_error($result);
if($err){
	$failure =1;
	/*
	echo htmlentities($err['code']);
    print "\n<pre>\n";
    print htmlentities($err['sqltext']);
    printf("\n%".($err['offset']+1)."s", "^");
    print  "\n</pre>\n";*/
	oci_rollback($conn);
	
	//echo "<html><a href = \"submit_form.php\">Entry Failed - Go Back </a></html>";
	
}
else{
	
	oci_commit($conn);
}


$query = "SELECT current_timestamp(6) FROM dual";
$stid = oci_parse($conn,$query);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_NUM);
$now = $row[0];
$now = substr($now,0,28);

$sessusername = $_SESSION['username'];


$result = oci_parse($conn, "Insert into Times(time) values ('$now')");
oci_execute($result);
$err = oci_error($result);
if($err){
	$failure =1;
	/*
	echo htmlentities($err['code']);
    print "\n<pre>\n";
    print htmlentities($err['sqltext']);
    printf("\n%".($err['offset']+1)."s", "^");
    print  "\n</pre>\n";*/
	oci_rollback($conn);
	
	//echo "<html><a href = \"submit_form.php\">Entry Failed - Go Back </a></html>";	
	//header("location:submit_form.php");		
}
else{
	
	oci_commit($conn);
}	

$result = oci_parse($conn, "INSERT into QuestionAction(eventType,qid,username,time) values ('posted', questionseq.currval,'$sessusername', '$now')");
oci_execute($result);
$err = oci_error($result);
if($err){
	$failure =1;
	/*
	echo "<html>failed QuestionAction</html>";
	echo htmlentities($err['code']);
    print "\n<pre>\n";
    print htmlentities($err['sqltext']);
    printf("\n%".($err['offset']+1)."s", "^");
    print  "\n</pre>\n";*/
	oci_rollback($conn);
	
	//echo "<html><a href = \"submit_form.php\">Entry Failed - Go Back </a></html>";
	//header("location:submit_form.php");	
}
else{
	
	oci_commit($conn);
	echo "<html><a href = \"success.php\">Entry Successful - Go Back </a></html>";
	//echo "<script language=javascript>alert(' Entered Successfully ')</script>";
	//header("location:success.php");	
}


if($failure == 1){
	echo "<html><a href = \"submit_form.php\">Entry Failed - Go Back </a></html>";
}


				
?>