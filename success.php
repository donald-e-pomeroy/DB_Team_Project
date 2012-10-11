<?php
		session_start();
		if(!session_is_registered(myusername)){
			header("location:login.html");
		}		
?>

<html><br /><br />
<h1 style="text-align:center;">InterviewDB</h1><br />
<table width="300" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF">
<td>&nbsp;</td>
<td>&nbsp;</td>
<td><form action="search.php" method="post"><input type="submit" value="Search Questions"/></form>	
<td>&nbsp;</td>
<td>&nbsp;</td>
<td><form action="submit_form.php" method="post"><input type="submit" value="Submit Question"/></form>	
</td>		
<td>&nbsp;</td>
<td>&nbsp;</td>
<td><form action="logout.php" method="post"><input type="submit" value="Logout"/></form>	
</td>
</table>
</html>
