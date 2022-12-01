<?php
include("connect.php");error_reporting(0);
function getExtensionXXZZZ($filename)
{
	$path_info = pathinfo($filename);
	return $path_info['extension'];
}
function thumbnail_box($img, $box_w, $box_h) 
{
	$new = @imagecreatetruecolor($box_w, $box_h);
	if($new === false) {
		return null;
	}
	$fill = @imagecolorallocate($new, 255, 255, 255);
	@imagefill($new, 0, 0, $fill);
	$hratio = $box_h / @imagesy($img);
	$wratio = $box_w / @imagesx($img);
	$ratio = @min($hratio, $wratio);
	if($ratio > 1.0)
		$ratio = 1.0;
	$sy = @floor(@imagesy($img) * $ratio);
	$sx = @floor(@imagesx($img) * $ratio);
	$m_y = @floor(($box_h - $sy) / 2);
	$m_x = @floor(($box_w - $sx) / 2);
	if(!@imagecopyresampled($new, $img,
		$m_x, $m_y, //dest x, y (margins)
		0, 0, //src x, y (0,0 means top left)
		$sx, $sy,//dest w, h (resample to this size (computed above)
		@imagesx($img), @imagesy($img)) //src w, h (the full size of the original)
	) {
		@imagedestroy($new);
		return null;
	}
	return $new;
}

$page=$_REQUEST['page'];
if($page=='')
{
	$pagefrom=0;
}
else
{
	$pagefrom=$page*10;
}


$strQueryPerPage="select main_image_url,id,user_defined_sku_id from flagging_searplusitems where main_image_url!='' limit $pagefrom,10";
$strResultPerPage=mysql_query($strQueryPerPage);
while($strResultPerPageRow=mysql_fetch_array($strResultPerPage))
{
	echo "<br> == >".$main_image_url=$strResultPerPageRow['main_image_url'];
	echo " == >".$FileName=str_replace("http://flaggingdirect.com/sites/default/files/","",$strResultPerPageRow['main_image_url']);
	//echo " == >".$FileName=trim($strResultPerPageRow['user_defined_sku_id']).".jpg";
	
	$FileName=preg_replace("[^A-Za-z0-9]","_",$FileName);
	
	$ImgPath=$main_image_url;
	
	$dest2="Products/".$FileName;
	copy($ImgPath,$dest2);
	//$ImgPath="Products/".$FileName;
	
	$dest2="Products/thumb/".$FileName;
	$i = @imagecreatefromjpeg($ImgPath);
	$thumb = thumbnail_box($i, 400,400);
	@imagedestroy($i);
	if($thumb!=NULL && $thumb!='')
	{
		@imagejpeg($thumb,$dest2,100);
	}
	
	$dest2="Products/croped/".$FileName;
	$i = @imagecreatefromjpeg($ImgPath);
	$thumb = thumbnail_box($i, 200,200);
	@imagedestroy($i);
	if($thumb!=NULL && $thumb!='')
	{
		@imagejpeg($thumb,$dest2,100);
	}
	
echo "<br>=======>".$strQueryPerPage2="update flagging_searplusitems set main_image_url='".$FileName."' where id='".$strResultPerPageRow['id']."'";
	$strResultPerPage2=mysql_query($strQueryPerPage2);
}
$page=$page+1;

?>
<script>
setTimeout("runurl()",300);
function runurl()
{
	window.location.href='create_thumb3.php?page=<?=$page;?>';
}
</script>