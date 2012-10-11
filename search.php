<?php
		session_start();
		if(!session_is_registered(myusername)){
			header("location:login.html");
		}		
?>

<html>
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
<h1><a href='success.php'>InterviewDB</a></h1><br />
<h3>Search for interview questions:</h3>
<form action = 'results.php'>
<table>
<tr>
	<td>
		Question text:
	</td>
	<td>
		<input type = 'text' name = 'question_text'>
	</td>
</tr>
<tr>
	<td>
		Keyword:
	</td>
	<td>
		<input type = 'text' name = 'keyword'>
	</td>
</tr>
<tr>
	<td>
		Company:
	</td>
	<td>
		<input type = 'text' name = 'company'>
	</td>
</tr>
<tr>
	<td>
		Position:
	</td>
	<td>
		<input type = 'text' name = 'position'>
	</td>
</tr>
<tr>
	<td>
		Start date (YYYY-MM-DD):
	</td>
	<td>
		<input type = 'text' name = 'start_date'><br />
	</td>
</tr>
<tr>
	<td>
		End date (YYYY-MM-DD): 
	</td>
	<td>
		<input type = 'text' name = 'end_date'><br />
	</td>
</tr>
<tr><td>
	<input type = 'submit' value = 'submit'>
</td></tr>
</table>
</form>
</body></html>