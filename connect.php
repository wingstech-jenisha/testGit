<?php error_reporting(0);
session_start();
include ("include/config.inc.php");
include ("include/functions.php");
include ("include/CryToGraphy.php");
include("include/encryption.php");	
include_once ("include/prs_function.php");
include_once ("include/gtg_functions.php");
$imagewidth="85";
$imageheight="112";
$bimagew=200;
$bimageh=300;
$db=mysql_connect($DBSERVER, $USERNAME, $PASSWORD) or die ("Couldnt connect");
mysql_select_db($DATABASENAME,$db) or die("Couldnt find database");
ini_set('memory_limit','1000M'); // set memory to prevent fatal errors
define('MEMORY_TO_ALLOCATE', '1000M');
$CSSLOAD="?a=143022";
/////////////////JET API 
//LIVE MODE
$JETAPI_user='';
$JETAPI_PASS='';
$sitepathh=$_SERVER['HTTP_HOST'];
/*if($sitepathh!="ishu")
{
	$tempp=substr($sitepathh,0,3);
	if($tempp!="www")
	{
		$urlCCCC = "http" . ((!empty($_SERVER['HTTPS'])) ? "s" : "") . "://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		$urlCCCC=str_replace("https://","https://www.",$urlCCCC);
		$urlCCCC=str_replace("http://","https://www.",$urlCCCC);
		echo "<script>window.location.href='".$urlCCCC."';</script>";
	}
	$IMGPATHURL="https://www.mutualindustries.net/";
}
else
{	
	$IMGPATHURL="https://www.mutualindustries.net/";
}*/
$siteALTTXT="Flagging Direct";
/*if($_SERVER['HTTPS']=="on")
{}
else
{
$urlCCCC = "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
echo "<script>window.location.href='".$urlCCCC."';</script>";
}*/

if($_SERVER['HTTPS']=="on")
{
    $SITE_URL = $SECURE_URL;
}

//SMTP Details
$SMTP_User="";
$SMTP_Pass="";
$SMTP_Port="80";
$SMTP_FromName="jet.com";

function Fcase($PString)
{
	return strtoupper(substr($PString,0,1)).substr($PString,1,strlen($PString));
}
function getLinkText($str)
{
	return "onmouseover=\"window.status='$str'\" onmouseout=\"window.status='$str'\" onMouseMove=\"window.status='$str'\"";
}
function generateListLinks($link,$classname,$viewallmsg,$sortmsg)
{
	$fvar_linktext_ini=getLinkText($viewallmsg);//"View All Models"
	echo "<a href='$link?order=%' class='$classname' $fvar_linktext_ini> All </a>";
	for ($fvar_i=65;$fvar_i<91;$fvar_i++)
	{
		$fvar_chr=chr($fvar_i);
		$fvar_linktext=getLinkText($sortmsg.$fvar_chr);//"Sort Model with name starting with "
		echo "<a href='$link?order=$fvar_chr' class='$classname' $fvar_linktext> $fvar_chr </a>";
	}
}
//Get All Mail Addresses
$ADMIN_MAIL=GetName1("admin","adminmail","id","1");
if($ADMIN_MAIL!="")
{
	$Sdsd=explode(",",$ADMIN_MAIL);
	$ADMIN_MAIL=$Sdsd[0];
}
$REPRESENTATIVE_MAIL=$ADMIN_MAIL;
$SERVICE_MAIL=$ADMIN_MAIL;
$FORGOTPASS_MAIL=$ADMIN_MAIL;
$ORDER_MAIL=$ADMIN_MAIL;
//$SITE_TITLE=stripslashes(GetName1("admin","page_title","id","1"));
$METATAGS=stripslashes(GetName1("admin","metatext","id","1"));

$SITE_TITLE="Flagging Direct";

///7 july 2017
$METADESCRIPTION="Flagging Direct";
$METAKEYWORD="Flagging Direct";


$MANDRILL_API_KEY=""; $SMTP_FromName="jet.com";
$ADMINPHONE="877 564-4400";

function getModifiedUrlNamechangeJET($catnm)
{
	$catnm=trim($catnm);
	$catnm1=ereg_replace(" & ","-",$catnm);
	$catnm1=ereg_replace(" / ","-",$catnm1);
	$catnm1=ereg_replace("--","-",$catnm1);
	$catnm1=ereg_replace(" ","-",$catnm1);
	$catnm1=ereg_replace("--","-",$catnm1);
	$catnm1=ereg_replace("_","-",$catnm1);
	$catnm1=ereg_replace("/","-",$catnm1);
	$catnm1=ereg_replace("--","-",$catnm1);
	$catnm1=ereg_replace(":","-",$catnm1);
	$catnm1=ereg_replace("/","-",$catnm1);
	$catnm1=ereg_replace("[^A-Za-z0-9]","-",$catnm1);
	$catnm1=ereg_replace("--","-",$catnm1);
	$catnm1=ereg_replace("--","-",$catnm1);
	return $catnm1;
}
function GetUrl_Prod($PId)
{
	global $SITE_URL;	
	$sku_id=stripslashes(getname("searplus",$PId,'user_defined_sku_id'));
	$title=stripslashes(getname("searplus",$PId,'product_title'));
	$prodname1=strtolower(getModifiedUrlNamechangeJET($title));
	$prodname1stag1=str_replace("---","-",$prodname1);
	$productname=str_replace("--","-",$prodname1stag1);
	
	$brand=stripslashes(getname("searplus",$PId,'brand'));
	/*if(trim($brand)!='')
	{
		$brand=strtolower(getModifiedUrlNamechangeJET($brand));
		$brand=str_replace("--","-",$brand);
		$productname=$brand."-".$productname;
	}	*/
	
	$sku_id=str_replace(" ","---",$sku_id);
	$McUrl=$SITE_URL."/product/".$productname."/".$sku_id."";
	return $McUrl;
}
function Get_ProductUrl($PId)
{
	global $SITE_URL;	
	$sku_id=stripslashes(getname("flagging_searplusitems",$PId,'user_defined_sku_id'));
	$title=stripslashes(getname("flagging_searplusitems",$PId,'product_title'));
	$prodname1=strtolower(getModifiedUrlNamechangeJET($title));
	$prodname1stag1=str_replace("---","-",$prodname1);
	$productname=str_replace("--","-",$prodname1stag1);
	$brand=stripslashes(getname("flagging_searplusitems",$PId,'brand'));
	/*if(trim($brand)!='')
	{
		$brand=strtolower(getModifiedUrlNamechangeJET($brand));
		$brand=str_replace("--","-",$brand);
		$productname=$brand."-".$productname;
	}	*/
	$sku_id=str_replace(" ","---",$sku_id);
	$McUrl=$SITE_URL."/product/".$productname."";
	return $McUrl;
}
function Get_CartProductUrl($PId)
{
	global $SITE_URL;	
	$sku_id=$PId;
	$title=stripslashes(GetName1("flagging_searplusitems","product_title","user_defined_sku_id",$sku_id));
	$prodname1=strtolower(getModifiedUrlNamechangeJET($title));
	$prodname1stag1=str_replace("---","-",$prodname1);
	$productname=str_replace("--","-",$prodname1stag1);
	$brand=stripslashes(GetName1("flagging_searplusitems","brand","user_defined_sku_id",$sku_id));
	/*if(trim($brand)!='')
	{
		$brand=strtolower(getModifiedUrlNamechangeJET($brand));
		$brand=str_replace("--","-",$brand);
		$productname=$brand."-".$productname;
	}	*/
	$sku_id=str_replace(" ","---",$sku_id);
	$McUrl=$SITE_URL."/product/".$productname."/".$sku_id."";
	return $McUrl;
}
function GetUrl_Catt($PId)
{
	global $SITE_URL;	
	$title=stripslashes(GetName1("flagging_category","jet_node_name",'jet_node_id',$PId));
	$prodname1=strtolower(getModifiedUrlNamechangeJET($title));
	$prodname1stag1=str_replace("---","-",$prodname1);
	$productname=str_replace("--","-",$prodname1stag1);
	$McUrl=$SITE_URL."/category/".$productname."/".$PId;
	return $McUrl;
}
function GetUrl_Blog($PId)
{
	global $SITE_URL;	
	$title=stripslashes(GetName1("flagging_blog","title",'id',$PId));
	$prodname1=strtolower(getModifiedUrlNamechangeJET($title));
	$prodname1stag1=str_replace("---","-",$prodname1);
	$productname=str_replace("--","-",$prodname1stag1);
	$McUrl=$SITE_URL."/blog/".$productname;
	return $McUrl;
}
function getExtensionXX($filename)
{
	$path_info = pathinfo($filename);
	return $path_info['extension'];
}
function make_thumbADMIN($src,$dest,$desired_width)
{
	$path_info = pathinfo($src);
	$system=$path_info['extension'];
	$source=$src;
	
	$ext=getExtensionXX($src);
	if($ext=="jpg" || $ext=="jpeg" || $ext=="JPEG" || $ext=="JPG"){
		$im=imagecreatefromjpeg($source);
	}else if($ext=="gif" || $ext=="GIF"){
		$im=imagecreatefromgif($source);
	}else if($ext=="png" || $ext=="PNG"){
		$im=imagecreatefrompng($source);
	}else if($ext=="bmp" || $ext=="BMP"){
		$im=imagecreatefromwbmp($source);
	}else{
		$im=imagecreatefromjpeg($source);
	}
  /* read the source image */
  $width = imagesx($im);
  $height = imagesy($im);
  
  /* find the "desired height" of this thumbnail, relative to the desired width  */
  $desired_height = floor($height*($desired_width/$width));
  
  /* create a new, "virtual" image */
  $virtual_image = imagecreatetruecolor($desired_width,$desired_height);
  
  /* copy source image at a resized size */
  imagecopyresampled($virtual_image,$im,0,0,0,0,$desired_width,$desired_height,$width,$height);
  
  /* create the physical thumbnail image to its destination */
  imagejpeg($virtual_image,$dest,100);
}
function make_thumbBanner($src,$dest,$desired_width)
{
	$path_info = pathinfo($src);
	$system=$path_info['extension'];
	$source=$src;
	
	$ext=getExtensionXX($src);
	if($ext=="jpg" || $ext=="jpeg" || $ext=="JPEG" || $ext=="JPG"){
		$im=imagecreatefromjpeg($source);
	}else if($ext=="gif" || $ext=="GIF"){
		$im=imagecreatefromgif($source);
	}else if($ext=="png" || $ext=="PNG"){
		$im=imagecreatefrompng($source);
	}else if($ext=="bmp" || $ext=="BMP"){
		$im=imagecreatefromwbmp($source);
	}else{
		$im=imagecreatefromjpeg($source);
	}
  /* read the source image */
  $width = imagesx($im);
  $height = imagesy($im);
  
  if($width<$desired_width)
  {
  		$width=$desired_width;
  }
  
  /* find the "desired height" of this thumbnail, relative to the desired width  */
  $desired_height = floor($height*($desired_width/$width));
  /* create a new, "virtual" image */
  $virtual_image = imagecreatetruecolor($desired_width,$desired_height);
  
  
	//added 7-1-2013
	$fill = imagecolorallocate($virtual_image, 255, 255, 255);
	imagefill($virtual_image, 0, 0, $fill);

	//compute resize ratio
	$hratio = $desired_height / imagesy($im);
	$wratio = $desired_width / imagesx($im);
	$ratio = min($hratio, $wratio);

	//if the source is smaller than the thumbnail size, 
	//don't resize -- add a margin instead
	//(that is, dont magnify images)
	if($ratio > 1.0)
		$ratio = 1.0;

	//compute sizes
	$sy = floor(imagesy($im) * $ratio);
	$sx = floor(imagesx($im) * $ratio);
	
	$m_y = floor(($desired_height - $sy) / 2); //0;//
	$m_x = floor(($desired_width - $sx) / 2);
	
	
	
  /* copy source image at a resized size */
  imagecopyresampled($virtual_image,$im,$m_x,$m_y,0,0,$sx,$desired_height,imagesx($im),imagesy($im));
  /* create the physical thumbnail image to its destination */
  imagejpeg($virtual_image,$dest,100);
}

function GetABulletsNames($Valll)
{
	$tagss="";
	if($Valll!="" && $Valll!=",")
	{
		$Ssss="SELECT id,name from options where id in (".$Valll.") order by name asc";
		$SEssLRs=mysql_query($Ssss);
		if(mysql_affected_rows()>0)
		{
			while($RsOW=mysql_fetch_object($SEssLRs)){$tagss.=stripslashes($RsOW->name).", ";}
		}
	}
	if($tagss!=""){ $tagss=substr($tagss,0,-2);}
	return $tagss;
}
function int_to_Decimal($Intnumber)
{
	if($Intnumber!='')
	{
		return number_format($Intnumber, 2, '.', ',');
	}
	else
	{
		return '';
	}
}
?>