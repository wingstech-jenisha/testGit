<?php 
include('connect.php');
?>
<table width="100%" border="1" cellspacing="0" cellpadding="0">
<tr>
	  <th>Title</th><th>Date</th><th>Description</th>
	</tr>

<? $i=0;
$ProductQury="select vid,title,created from node where type='blog' order by created desc";
$ProductRes=mysql_query($ProductQury);
$ProductTot=mysql_affected_rows();
if($ProductTot>0)
{
	while($ProductRow=mysql_fetch_array($ProductRes))
	{	$i++;
		$vid=$ProductRow['vid'];
		$Date=$ProductRow['created'];
		$NodeQury="select vid,title,body from node_revisions where vid='".$vid."'";
		$NodeRes=mysql_query($NodeQury);
		$NodeTot=mysql_affected_rows();
		$NodeRow=mysql_fetch_array($NodeRes);
		$Title=$NodeRow['title'];
		$Description=$NodeRow['body']; 
	
		$Createdate=date("Y-m-d",$Date);
		$Description=str_replace("’","&rsquo;",$Description);
		$Description=str_replace("“","&ldquo;",$Description);
		$Description=str_replace("”","&rdquo;",$Description);
		$prodname1=strtolower(getModifiedUrlNamechangeJET($Title));
		$BlogQury="insert into flagging_blog set title='".addslashes($Title)."',description='".addslashes($Description)."',createdate='".$Createdate."',urlcomponent='".$prodname1."'";
		$BlogRes=mysql_query($BlogQury);
		?>
		<tr>
		<td align="center" valign="top"><? echo stripslashes($i); ?>&nbsp;</td>
		<td align="center" valign="top"><? echo stripslashes($Title); ?>&nbsp;</td>
		<td align="center" valign="top"><? echo date('F d, Y',$Date);?>&nbsp;</td>
		<td align="center" valign="top"><? echo stripslashes($Description); ?>&nbsp;</td>
		</tr>
<?	}?></table>
<? } ?>