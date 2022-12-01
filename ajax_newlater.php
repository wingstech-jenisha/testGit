<? 
session_start();  
include("connect.php");  
$email=mysql_real_escape_string(trim(addslashes($_GET['email'])));
$query1subs="select * from flagging_newsletter where email='".$email."'"; 
$ressubs=mysql_query($query1subs);
$totsubs=mysql_affected_rows();
if($totsubs>0)
{	
	echo 'Email already Subscribed!';
}
else
{
	$AddUserQry="INSERT INTO flagging_newsletter SET email='".$email."',regdate=now()";	
	mysql_query($AddUserQry);
	echo 'Email Subscribed!';
}
?> 