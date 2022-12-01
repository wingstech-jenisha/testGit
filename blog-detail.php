<? include('connect.php');
$TopTab="Blog";
$id=$_REQUEST['id'];
$HomeBnrQuery1=mysql_query("select * from flagging_blog where urlcomponent='".mysql_real_escape_string(trim($_REQUEST['id']))."'");
$total1=mysql_affected_rows();
if($total1>0)
{
	$HomeBnrRow1=mysql_fetch_object($HomeBnrQuery1);
	$Title=stripslashes($HomeBnrRow1->title);
	$Description=stripslashes($HomeBnrRow1->description);
	$Date=$HomeBnrRow1->createdate;
	$MetaPageTitle=$Title;	
	$MetaDescription_JOIN=$Title;
	$MetaKeyword_JOIN=$Title;	
}
else
{
	header("location:$SITE_URL");
}
?>
<!DOCTYPE html>
<html lang="en">
<head><base href="<? echo $SITE_URL;?>/">
<meta charset="utf-8">
<title><?=$MetaPageTitle;?> | <?=$SITE_TITLE;?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1" name="viewport" />
<meta content="Blogs, <?=$MetaDescription_JOIN;?>" name="description" />
<meta content="Blogs, <?=$MetaKeyword_JOIN;?>" name="keywords" />
<? include("favicon.php");?>
<link rel="stylesheet" href="css/simplemenu.css">
<link rel="stylesheet" href="css/carouseller.css">
<link rel="stylesheet" type="text/css" href="css/slider.css">
<link rel="stylesheet" type="text/css" href="css/entypo.css">
<link rel="stylesheet" type="text/css" href="css/style.css<?=$CSSLOAD;?>" />
<link rel="stylesheet" type="text/css" href="css/responsive.css<?=$CSSLOAD;?>" />
<style>.left-blog-box-img img{max-width:100px;}.left-blog-box-img{text-align:center;}</style>
<? include("headermore.php");?>
</head>
<body style="background: #ffffff;">
<? include("top.php");?>
<div class="middlewrapper">
  <div class="wrapper">
    <div class="flagging_category-detail"> 
	  <? if($HomeBnrRow1->featured==''){?>
	  <? include("blogleft.php");?>
	  <? }else{?>
	  <div class="category-detail-left">
		  <div class="dropdowns">
			<button onClick="myFunction()" class="dropbtn">Related Products<span class="responsive-icon"><i class="fa fa-angle-down" aria-hidden="true"></i></span></button>
			<div id="myDropdown" class="dropdown-content"> 
				<?php if($HomeBnrRow1->featured){?>
				<?php $Option=explode(",",$HomeBnrRow1->featured);
						for($TT=0;$TT<count($Option);$TT++)
						 {	
							if(trim($Option[$TT])!='')
							$get_temp="select product_title,item_price,user_defined_sku_id,main_image_url,id from flagging_searplusitems where id='".$Option[$TT]."'"; 
							$gets=mysql_query($get_temp);
							$tot= mysql_affected_rows();
							if($tot>0){
							$SelMovieObj=mysql_fetch_object($gets);
							 $tittl=stripslashes($SelMovieObj->product_title);
							  $tittl=str_replace("&quot;",'"',$tittl);
							  $tittlXX=$tittl;
							$image_url=$IMGPATHURL."Products/croped/".stripslashes($SelMovieObj->main_image_url);
							$ItmUrl=Get_ProductUrl($SelMovieObj->id);
							$imageALT=stripslashes($SelMovieObj->user_defined_sku_id).", ".stripslashes($SelMovieObj->product_title).", ".$siteALTTXT;
							$imageALT=str_replace('"','',$imageALT);
							
						 
					  ?>
				<div class="left-blog-box">
				<div class="left-blog-box-img"><a href="<?=$ItmUrl;?>"><? if($image_url!=''){?><img src="<?=stripslashes($image_url);?>"   alt="<?=stripslashes($imageALT);?>" title="<?=stripslashes($imageALT);?>" /><? }else{ ?><img src="noimg.jpg"  style="max-height:170px;" alt="<?=stripslashes($imageALT);?>" title="<?=stripslashes($imageALT);?>"><? } ?></a></div>
					<div class="left-blog-box-title product" style="text-align:center;"><a href="<?=$ItmUrl;?>"><?=stripslashes($tittl);?></a></div>
				  </div>
				  <? } }}?>
			 </div>
		  </div>
		</div><? }?>
      <div class="category-detail-right">
        <div class="blog-in-main">
          <h1 class="blog-in-title"><?php echo $Title;?></h1>
          <i><? echo date('F d, Y',strtotime($Date));?></i>
          <p><?php echo $Description;?></p>
        </div>
      </div>
      <div class="clear"></div>
    </div>
  </div>
</div>
<? include("footer.php");?>
<script src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/scriptbreaker-multiple-accordion-1.js"></script>
<script language="JavaScript">
$(document).ready(function() {
	$(".topnav").accordion({
		accordion:false,
		speed: 500,
		closedSign: '<i class="fa fa-plus"></i>',
		openedSign: '<i class="fa fa-minus"></i>'
	});
});		
</script>
<script>
function myFunction() {
  document.getElementById("myDropdown").classList.toggle("shows");
}
function myFunction1() {
  document.getElementById("myDropdown1").classList.toggle("shows");
}
function myFunction2() {
  document.getElementById("myDropdown2").classList.toggle("shows");
}
function myFunction3() {
  document.getElementById("myDropdown3").classList.toggle("shows");
}
function myFunction4() {
  document.getElementById("myDropdown4").classList.toggle("shows");
}
</script>
<script src='js/hammer.min.js'></script>
<script src="js/simplemenu.js"></script>
<link rel="stylesheet" type="text/css" href="css/font-awesome.css" />
<? include("googleanalytic.php");?>
<? include("dbclose.php");?>
</body>
</html>
