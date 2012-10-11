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
table, th, td{
	border: 1px solid black;
}
table{
	border-collapse:collapse;
}
th{
	font-size:1.1em;
	text-align:left;
	padding-top:5px;
	padding-bottom:4px;
	background-color:#98AFC7;
	color:#ffffff;
}
td, th{
	font-size:1em;
	border:1px solid #87AFC7;
	padding:3px 7px 2px 7px;
}
tr.alt td {
	color:#000000;
	background-color:#B4CFEC;
}
</style>
</head>
<body>
<h1><a href='success.php'>InterviewDB</a></h1><br />
<h2>Search Results</h2>
<?php

//ini_set('display_errors', 'On');

//Create connection to Oracle
$conn = oci_connect("ewd2106", "wofford1", "//w4111c.cs.columbia.edu:1521/ADB");
if (!$conn) {
   $m = oci_error();
   echo $m['message'], "\n";
   exit;
}

$query = "SELECT Q.company, Q.position, Q.text, Q.qid FROM Questions Q ";
$prev = false;
if(!empty($_REQUEST['question_text'])){
	if($prev){
		$query .= "AND ";
	}else{
		$query .= "WHERE ";
		$prev = True;
	}
	
	$question_text = $_REQUEST['question_text'];
	$query .= "REGEXP_LIKE(text,'$question_text','i') ";
}

if(!empty($_REQUEST['keyword'])){
	if($prev){
		$query .= "AND ";
	}else{
		$query .= "WHERE ";
		$prev = True;
	}
	$keyword = $_REQUEST['keyword'];
//	$q = "SELECT Q. FROM Questions Q, Tagged T WHERE Q.qid = T.qid AND REGEXP_LIKE(keyword,'$keyword','i')";
//	$s = oci_parse($conn, $q);
//	oci_execute($s);
//	$row = oci_fetch_array($s, OCI_BOTH);
//	$count = $row[0];
//	$query .= "$count > 5 ";
	$query .= "Q.qid IN (SELECT Q.qid FROM Questions Q, Tagged T WHERE Q.qid = T.qid AND REGEXP_LIKE(keyword,'$keyword','i'))";
}

if(!empty($_REQUEST['company'])){
	if($prev){
		$query .= "AND ";
	}else{
		$query .= "WHERE ";
		$prev = True;
	}
	$company = $_REQUEST['company'];
	$query .= "REGEXP_LIKE(company,'$company','i') ";
}

if(!empty($_REQUEST['position'])){
	if($prev){
		$query .= "AND ";
	}else{
		$query .= "WHERE ";
		$prev = True;
	}
	$position = $_REQUEST['position'];
	$query .= "REGEXP_LIKE(position,'$position','i') ";
}


if(!empty($_REQUEST['start_date'])){
	if($prev){
		$query .= "AND ";
	}else{
		$query .= "WHERE ";
		$prev = True;
	}
	$start_date = $_REQUEST['start_date'];
	$query .= "Q.interview_date >= to_date('$start_date', 'YYYY-MM-DD') ";
}


if(!empty($_REQUEST['end_date'])){

	if($prev){
		$query .= "AND ";
	}else{
		$query .= "WHERE ";
		$prev = True;
	}
	$end_date = $_REQUEST['end_date'];
	$query .= "Q.interview_date <= to_date('$end_date', 'YYYY-MM-DD') ";
	
}

$query .= "ORDER BY Q.accesses DESC, Q.company ASC";
$stid = oci_parse($conn, $query);
oci_execute($stid);

?>

<table cellpadding = "5px" border = "1" >
	<col width = 150px>
	<col width = 150px>
	<col width = 400px>
	<col>
	<thead>
		<th>Company</th>
		<th>Position</th>
		<th>Question</th>
		<th></th>
	</thead>
<?php
while (($row = oci_fetch_array($stid, OCI_NUM))) {
    echo "<tr>\n";
	
	$company = $row[0];
	$position = $row[1];
	$text = $row[2];
	if(strlen($text) > 50){
		$text = substr($row[2],0,50) . "...";
	}
	$qid = $row[3];
	
    echo "    <td>" . $company . "</td>\n";
    echo "    <td>" . $position . "</td>\n";
    echo "    <td>" . $text . "</td>\n";
	echo "	<td><a href='view.php?qid=$qid'>View</a></td>";
    echo "</tr>\n";

	if(($row = oci_fetch_array($stid, OCI_NUM))){
		echo "<tr class = 'alt' >\n";

		$company = $row[0];
		$position = $row[1];
		$text = $row[2];
		if(strlen($text) > 50){
			$text = substr($row[2],0,50) . "...";
		}
		$qid = $row[3];

	    echo "    <td>" . $company . "</td>\n";
	    echo "    <td>" . $position . "</td>\n";
	    echo "    <td>" . $text . "</td>\n";
		echo "	<td><a href='view.php?qid=$qid'>View</a></td>";
	    echo "</tr>\n";
	}
}
echo "</table>\n";


?>
</body>
</html>
