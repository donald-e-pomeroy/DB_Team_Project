<?php
		session_start();
		if(!session_is_registered(myusername)){
			header("location:login.html");
		}		
?>

<html>
<table width="300" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
<tr>
<form name="form1" method="post" action="submit_question.php">
<td>
<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
<tr>
<td colspan="3"><strong>Question Submission </strong></td>
</tr>
<tr>
<td width="78">Company</td>
<td width="6">:</td>
<td width="294"><input name="mycompanyname" type="text" id="mycompanyname"></td>
</tr>
<tr>
<td>Position</td>
<td>:</td>
<td><input name="mypositionname" type="text" id="mypositionname"></td>
</tr>
<tr>
<td>Difficulty</td>
<td>:</td>
<td><select name="mydifficulty" id="mydifficulty">
  <option value="1">1</option>
  <option value="2">2</option>
  <option value="3">3</option>
  <option value="4">4</option>
  <option value="5">5</option>
</select> </td>	
</tr>
<tr>
<td>Month</td>
<td>:</td>
<td><select name="mymonth" id="mymonth">
  <option value="01">January</option>
  <option value="02">February</option>
  <option value="03">March</option>
  <option value="04">April</option>
  <option value="05">May</option>
  <option value="06">June</option>
  <option value="07">July</option>
  <option value="08">August</option>
  <option value="09">September</option>	
  <option value="10">October</option>
  <option value="11">November</option>
  <option value="12">November</option>
</select></td>	
</tr>
<tr>
<td>Day</td>
<td>:</td>
<td><select name="myday" id="myday">
  <option value="01">1</option>
  <option value="02">2</option>
  <option value="03">3</option>
  <option value="04">4</option>
  <option value="05">5</option>
  <option value="06">6</option>
  <option value="07">7</option>
  <option value="08">8</option>
  <option value="09">9</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
        <option value="13">13</option>
        <option value="14">14</option>
        <option value="15">15</option>
        <option value="16">16</option>
        <option value="17">17</option>
        <option value="18">18</option>
        <option value="19">19</option>
        <option value="20">20</option>
        <option value="21">21</option>
        <option value="22">22</option>
        <option value="23">23</option>
        <option value="24">24</option>
        <option value="25">25</option>
        <option value="26">26</option>
        <option value="27">27</option>
        <option value="28">28</option>
        <option value="29">29</option>
        <option value="30">30</option>
        <option value="31">31</option>
</select></td>	
</tr>
<tr>
<td>Year</td>
<td>:</td>
<td><input name="myyear" type="text" id="myyear"></td>
</tr>
<tr>
<td>Question</td>
<td>:<br></td>	
<td><textarea name="myquestiontext" rows="2" cols="20">
Enter text here
</textarea></td>	
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td><input type="submit" name="question_submit" value="Submit"></td>
</tr>
</table>
</td>
</form>
</tr>
</table>
</html>