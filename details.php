<?
include("connect.php");$SCHEMAA="Y";
$TopTabb="Products";
$urlname=addslashes(str_replace("_"," ",$_GET["id"]));
$urlname=addslashes(str_replace("---"," ",$urlname));
$get_temp="select * from mutual_serplushitems where urlsafename='".$urlname."'";
$gets=mysql_query($get_temp);
if(mysql_affected_rows()>0)
{
	$ProRow=mysql_fetch_array($gets);
	$ProID=stripslashes($ProRow["id"]);
}
else
{
	header("location:".$SITE_URL."/");exit;
}
$SITE_TITLE_X=stripslashes($ProRow["product_title"]);
$mesgg="";
//FB URL
$urlCCCC = "http" . ((!empty($_SERVER['HTTPS'])) ? "s" : "") . "://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$FB_urlCCCC=$urlCCCC;
$FB_urlCCCC=str_replace("://","%3A%2F%2F",$FB_urlCCCC);
$FB_urlCCCC=str_replace("/","%2F",$FB_urlCCCC);
$sizesku=$ProRow["main_image_url"];
$imgurll=stripslashes($ProRow["main_image_url"]);
$image_url=$IMGPATHURL."Products/".stripslashes($ProRow['user_defined_sku_id']).".jpg";
$image_url=$IMGPATHURL."Products/thumb/".stripslashes($ProRow['main_image_url']);
//$image_url=stripslashes($ProRow['main_image_url']);
////added 13/6/2018 ygs
/*if(!file_exists($image_url))
{
	$image_url=stripslashes($ProRow['main_image_url']);
	$image_url=str_replace("https://www.mutualindustries.net/","",$image_url);
	$image_url=str_replace("http://www.mutualindustries.net/","",$image_url);
	$image_url="goodthumbOri.php?src=".$image_url."&w=395&h=395";
}*/
////added 13/6/2018 ygs

/*$grQryRs=mysql_query("SELECT sizesku FROM item_sizes WHERE sku='".$ProRow['user_defined_sku_id']."' order by name asc limit 0,1");
if(mysql_affected_rows()>0)
{
	$SizeQryRow=mysql_fetch_array($grQryRs);
	$sizesku=stripslashes($SizeQryRow['sizesku']);
	$image_url=$IMGPATHURL."Products/thumb/".stripslashes($sizesku).".jpg";
}*/
if($imgurll!="")
{
	$SCHMIMG=$SITE_URL."/Products/".stripslashes($ProRow['main_image_url']);
}
//$SelRat="SELECT COUNT(id) as totreview, AVG(rating) as totrating FROM flagging_reviews where vid='".addslashes($ProID)."' and status='Y' ";
//$SelRatRes=mysql_query($SelRat);
//$RatRow=mysql_fetch_object($SelRatRes);
//$averageMain=$RatRow->totrating;

if($_SESSION['UsErId']!="")
{
	$SelUserQry="SELECT firstname,email FROM flagging_users WHERE id='".$_SESSION['UsErId']."'";
	$SelUserQryRs=mysql_query($SelUserQry);
	$SelUserQryRow=mysql_fetch_array($SelUserQryRs);
	$RevwNm=stripslashes($SelUserQryRow["firstname"]);
	$RevwEml=stripslashes($SelUserQryRow["email"]);
}

$CatBrThb="";
if($ProRow['JetBrowseNodeID']!='')
{
  $tagsarray1 = explode(",",$ProRow['JetBrowseNodeID']);
  for($TT=0;$TT<count($tagsarray1);$TT++)
  {	
	if(trim($tagsarray1[$TT])!='')
	$Catid=$tagsarray1[$TT];
  }
}
if($Catid!='')
{
	$CattQry="SELECT jet_node_name,parent_id,jet_node_id FROM flagging_category WHERE jet_node_id='".$Catid."'";
	$CattRes=mysql_query($CattQry);
	if(mysql_affected_rows()>0)
	{
		$CattRow=mysql_fetch_array($CattRes);
		$CatNm=stripslashes($CattRow["jet_node_name"]);$CatPID=stripslashes($CattRow["parent_id"]);		
		$CatBrThb='<li><a class="title-brad" href="'.GetUrl_Catt($Catid).'">'.$CatNm.'</a></li>';
		if($CatPID>0)
		{
			$CattQry2="SELECT jet_node_name,parent_id,jet_node_id FROM flagging_category WHERE jet_node_id='".$CatPID."'";
			$CattRes2=mysql_query($CattQry2);
			if(mysql_affected_rows()>0)
			{
				$CattRow2=mysql_fetch_array($CattRes2);
				$CatNm=stripslashes($CattRow2["jet_node_name"]);$CatPID=stripslashes($CattRow2["parent_id"]);
				$CatBrThb='<li><a class="title-brad" href="'.GetUrl_Catt($CattRow2['jet_node_id']).'">'.$CatNm.'</a></li>'.$CatBrThb;
				if($CatPID>0)
				{
					$CattQry3="SELECT jet_node_name,parent_id,jet_node_id FROM flagging_category WHERE jet_node_id='".$CatPID."'";
					$CattRes3=mysql_query($CattQry3);
					if(mysql_affected_rows()>0)
					{
						$CattRow3=mysql_fetch_array($CattRes3);
						$CatNm=stripslashes($CattRow3["jet_node_name"]);$CatPID=stripslashes($CattRow3["parent_id"]);
						$CatBrThb='<li><a class="title-brad" href="'.GetUrl_Catt($CattRow3['jet_node_id']).'">'.$CatNm.'</a></li>'.$CatBrThb;
					}
				}
			}
		}
	}
}

$brandurl=stripslashes($ProRow["brand"]);
$brandurl=str_replace(' ','+',$brandurl);

if($ProRow["item_price"]>0){$EPRC=number_format($ProRow["item_price"],2,'.','');}else{$EPRC="0.00";}


$mfr=stripslashes($ProRow["mfr_part_number"]);
$mfr=str_replace('"','',$mfr);
$upc=stripslashes($ProRow["standard_product_codes"]);
$upc=str_replace('"','',$upc);
				 

$dessc=stripslashes($ProRow["Product Description"]);
$dessc=str_replace('http://','https://',$dessc);
$dessc=str_replace('<img ','<img alt="'.$SITENAME.'" title="'.$SITENAME.'" ',$dessc);


$bullets="";

$MetaTitle_JOIN.=ucfirst($SITE_TITLE_X)." | ";

if(stripslashes($ProRow["user_defined_sku_id"])!='')
{
	$MetaDescription_JOIN=stripslashes($ProRow["user_defined_sku_id"])." - ";
	$MetaKeyword_JOIN=stripslashes($ProRow["user_defined_sku_id"]).", ";
}
if($upc!='')
{
	$MetaDescription_JOIN.=stripslashes($upc)." - ";
	$MetaKeyword_JOIN.=stripslashes($upc).", ";
}	
if(stripslashes($ProRow["brand"])!='')
{
	$MetaDescription_JOIN.=stripslashes($ProRow["brand"])." - ";
	$MetaKeyword_JOIN.=stripslashes($ProRow["brand"]).", ";
	$MetaTitle_JOIN.=stripslashes($ProRow["brand"])." | ";
}
$MetaTitle_JOIN.=stripslashes($ProRow["user_defined_sku_id"]);
if($SITE_TITLE_X!='')
{
	$MetaDescription_JOIN.=stripslashes($SITE_TITLE_X)." - ";
	$MetaKeyword_JOIN.=stripslashes($SITE_TITLE_X).", ";
}

$title_dis=$MetaDescription_JOIN." - ".$SITENAME;
$t_len=strlen($title_dis);
/*if($t_len>75)
{
$title_dis=substr($title_dis,0,72)."...";
}*/
if($title_dis==''){$title_dis="View Product - ".$MetaDescription_JOIN." ".$SITENAME;}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <base href="<?=$SITE_URL;?>/" />
    <title><?=stripslashes($ProRow["pagetitle"]);?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1" name="viewport" />
    <meta name="keywords" content="<?=str_replace('"','',stripslashes($ProRow["metakeyword"]));?>">
    <meta name="description" content="<?=str_replace('"','',stripslashes($ProRow["metadescription"]));?>">
    <? include("favicon.php");?>
    <link rel="stylesheet" href="css/easyzoom.css?a=123" />
    <link rel="stylesheet" href="css/simplemenu.css">
    <link rel="stylesheet" href="css/carouseller.css">
    <link rel="stylesheet" type="text/css" href="css/slider.css">
    <link rel="stylesheet" type="text/css" href="css/entypo.css">
    <link rel="stylesheet" type="text/css" href="css/style.css<?=$CSSLOAD;?>" />
    <link rel="stylesheet" type="text/css" href="css/responsive.css<?=$CSSLOAD;?>" />
    <? include("headermore.php");?>
    <script type="application/ld+json">
    {
        "@context": "http://schema.org/",
        "@type": "Product",
        "name": "<?=str_replace('"','',$SITE_TITLE_X);?>",
        "image": "<?=stripslashes($image_url);?>",
        "description": "<?=str_replace('"','',stripslashes($ProRow["Product Description"]));?>",
        "brand": "<? echo str_replace('"
        ','
        ',stripslashes($ProRow['
        brand ']));?>",
        "sku": "<?=str_replace('"','',stripslashes($ProRow['user_defined_sku_id']));?>",
        "offers": {
            "@type": "Offer",
            "priceCurrency": "USD",
            "price": "<?=$ProRow["item_price"];?>",
            "url": "<?=$SITE_URL;?><? echo $_SERVER['REQUEST_URI'];?>",
            "availability": "http://schema.org/InStock",
            "itemCondition": "http://schema.org/NewCondition"
        }
    }
    </script>
    <script type="application/ld+json">
    {
        "@context": "http://schema.org/",
        "@type": "BreadcrumbList",
        "itemListElement": [{
                "@type": "ListItem",
                "position": "1",
                "item": {
                    "@id": "https://www.flaggingdirect.com/",
                    "name": "Home"
                }
            }, <
            ?
            $yyyy = 1;
            $ExplodedCatBrThb = explode('href="', $CatBrThb);
            for ($eppp = 1; $eppp < count($ExplodedCatBrThb); $eppp++) {
                $yyyy++;
                $ExplodedCatBrThb2 = explode('"', $ExplodedCatBrThb[$eppp]);
                $ExplodedCatBrThb3 = explode('">', $ExplodedCatBrThb[$eppp]);
                $ExplodedCatBrThb4 = explode('<i', $ExplodedCatBrThb3[1]); ?
                >
                {
                    "@type": "ListItem",
                    "position": "<?=$yyyy;?>",
                    "item": {
                        "@id": "<?=$ExplodedCatBrThb2[0]?>",
                        "name": "<?=$ExplodedCatBrThb4[0]?>"
                    }
                } < ?
                if ($eppp < (count($ExplodedCatBrThb) - 1)) {
                    ?
                    >
                    , < ?
                } ? >
                <
                ?
            } ? >
        ]
    }
    </script>
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?=str_replace('"','',stripslashes($ProRow["pagetitle"]));?>" />
    <meta property="og:description" content="<?=str_replace('"','',stripslashes($ProRow["metadescription"]));?>" />
    <meta property="og:url" content="<? echo $SITE_URL.$_SERVER['REQUEST_URI']; ?>" />
    <meta property="og:site_name" content="Flagging Direct" />
    <meta property="og:image" content="<?=stripslashes($image_url);?>" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?=str_replace('"','',stripslashes($ProRow["pagetitle"]));?>" />
    <meta name="twitter:description" content="<?=str_replace('"','',stripslashes($ProRow["metadescription"]));?>" />
    <meta name="twitter:image" content="<?=stripslashes($image_url);?>" />
</head>

<body onLoad="loadDropDown();">
    <? include("top.php");?>
    <div class="middlewrapper">
        <div class="pro-detail-deta">
            <div class="wrapper">
                <div class="main-pro-detail">
                    <ul class="breadcrumb">
                        <li><a href="<?=$SITE_URL;?>">Home</a></li><?=$CatBrThb;?><li>
                            <?=stripslashes($ProRow["product_title"]);?></li>
                    </ul>
                    <h2><a href="#" onclick="return false;"><?=stripslashes($ProRow["product_title"]);?></a></h2>
                    <div class="main-pro-detail-left">
                        <div class="easyzoom easyzoom--overlay easyzoom--with-thumbnails is-ready">
                            <? if($imgurll!='' ){  ?>
                            <a href="<?=stripslashes($image_url);?>"> <img class="main-img"
                                    src="<?=stripslashes($image_url);?>"
                                    alt="<?=stripslashes($ProRow["user_defined_sku_id"]);?>, <?=str_replace('"','',stripslashes($SITE_TITLE_X));?>, <?=$siteALTTXT;?>"
                                    title="<?=stripslashes($ProRow["user_defined_sku_id"]);?>, <?=str_replace('"','',stripslashes($SITE_TITLE_X));?>, <?=$siteALTTXT;?>"
                                    style="max-width:98%" /> </a>
                            <? }?>
                        </div>
                        <?  $strQueryPerPage="select imgpath,id,sku from flagging_product_img where sku='".$ProRow["user_defined_sku_id"]."' and title='".$ProRow["product_title"]."'";
		   $strResultPerPage=mysql_query($strQueryPerPage);
		   $strResultTot=mysql_affected_rows();
		   if($strResultTot>0)
		   { if($strResultTot!=1){?>
                        <ul class="thumbnails">
                            <? while($strResultPerPageRow=mysql_fetch_array($strResultPerPage))
		{$k++;$image_url1=$IMGPATHURL."Products_img/thumb/".stripslashes($strResultPerPageRow['imgpath']);?>
                            <li> <a href="<?=stripslashes($image_url1);?>"
                                    data-standard="<?=stripslashes($image_url1);?>"> <img
                                        src="<?=stripslashes($image_url1);?>"
                                        alt="<?=stripslashes($ProRow["user_defined_sku_id"]);?>, <?=str_replace('"','',stripslashes($SITE_TITLE_X));?>, <?=$siteALTTXT;?>"
                                        title="<?=stripslashes($ProRow["user_defined_sku_id"]);?>, <?=str_replace('"','',stripslashes($SITE_TITLE_X));?>, <?=$siteALTTXT;?>" /></a>
                            </li>
                            <? }?>
                        </ul>
                        <? }}?>
                    </div>
                    <div class="main-pro-detail-left">
                        <div class="pro-detial-right">
                            <h3>SKU: <span id="SKUID"><?=$ProRow['user_defined_sku_id'];?></span></h3>
                            <p>
                                <? $dessc2=str_replace('id="stcpDiv"','',stripslashes($ProRow["Product Description"]));?><?=strip_tags($dessc2,"<br><img><a><b><strong><u><i><p>");?>
                            </p>
                            <?
				$inventory='0';
				$getJetErrorQryRs=mysql_query("SELECT `Inventory` FROM products_inventory WHERE user_defined_sku_id='".stripslashes($ProRow['user_defined_sku_id'])."'");
				$TotgetJetError=mysql_affected_rows();
				if($TotgetJetError>0)
				{
					$getJetErrorQryRow=mysql_fetch_array($getJetErrorQryRs);
					$inventory=$getJetErrorQryRow['Inventory'];
				}
				else
				{
					$inventory=150;
				}
				?>
                            <? $PriceQury="select * from flagging_price where sku='".$ProRow['user_defined_sku_id']."' and title='".$ProRow['product_title']."'";
				   $PriceRes=mysql_query($PriceQury);
				   $PriceTot=mysql_affected_rows();
				   if($PriceTot>0){
				   $PriceRow=mysql_fetch_array($PriceRes);$case_value=$PriceRow['case_value']; ?>
                            <span id="drop_value" style="display:none;">
                                <h4 class="price-deta"><span id="eachprice"> each</span> </h4>
                                <p>Sold by the <?=$PriceRow['price_value'];?> of <?=$case_value;?> units each for a
                                    Total Price of : <span id="Price"><?=stripslashes($ProRow['item_price']);?></span>
                                </p>
                            </span>
                            <span id="drop_value1" style="display:block;">
                                <?php $eachpr=$ProRow['item_price']/$PriceRow['case_value'];?>
                                <h4 class="price-deta">$<?=number_format($eachpr,2,'.','');?> each </h4>
                                <p>Sold by the <?=$PriceRow['price_value'];?> of <?=$case_value;?> units each for a
                                    Total Price of : $<?=stripslashes($ProRow['item_price']);?></p>
                            </span>
                            <? }else{?>
                            <h4 class="price-deta" id="Price">$<?=stripslashes($ProRow['item_price']);?></h4>
                            <? }?>
                            <? if($inventory>0){?>
                            <div class="extra_filed">
                                <span id="DropDown"></span>
                            </div>
                            <? if($inventory>0) {?>
                            <div class="pro-specification-box" style="padding:0px;">
                                <h4><span style="font-weight:bold;">Availability:</span> In Stock & Ready to Ship</h4>
                            </div>
                            <? }?>
                            <div style="clear:both;"></div>
                            <div class="quantity-detail">
                                <input class="itemData hoverInfo" id="qtyy" name="qtyy" value="1" maxlength="3"
                                    type="text" onBlur="fillinbixxx(this.value);"
                                    onKeyUp="fillinbixxx(this.value);"><?php /*?><a href="#" class="add-to-cart"
                                    onClick="gogtotoSize();return false;">Add to Cart</a><?php */?>
                                <? }?>
                                <? if($inventory>0){?>
                                <div class="pro-detial-right-btn"> <a class="add-cart-btn" href="#"
                                        onClick="gogtoto(<?=$ProRow["id"];?>);return false;">Add to Cart</a> <a
                                        class="buy-btn" href="#"
                                        onClick="gogtoto(<?=$ProRow["id"];?>);return false;">Buy Now</a> </div>
                            </div>
                            <? } else{?>
                            <span id="DropDown"></span>
                            <div class="pro-detial-right-btn"> <a class="add-cart-btn" href="#"
                                    onClick="return false;">Out Of Stock</a> <a class="buy-btn" href="#"
                                    onClick="return false;">Buy Now</a> </div>
                            <? }?>
                        </div>
                        <div class="shipping-deta"> <a
                                href="shipping_quote.php?sku=<?=$ProRow["user_defined_sku_id"];?>"
                                class="shadowbox[html];height=550;width=650;"><img src="images/shipping-icon.png"
                                    alt="Calculate Shipping and Options">Calculate Shipping and Options</a>
                            <div class="pro-contact-btn"> <a
                                    href="contact-us-popup.php?sku=<?=$ProRow["user_defined_sku_id"];?>&pid=<?=$ProRow["id"];?>"
                                    id='clickherecontact' class="shadowbox;height=440;width=400">Click here to Contact
                                    Us!</a> </div>
                            <input name="SKUID-Main" id="SKUID-Main" value="<?=$ProRow["user_defined_sku_id"];?>"
                                type="text" />
                            <input name="SKUID-Main" id="drp_1" value="" type="text" />
                            <input name="SKUID-Main" id="drp_2" value="" type="text" />
                            <input name="SKUID-Main" id="drp_3" value="" type="text" />
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="product-specification-detail">
                <div class="wrapper">
                    <div class="pro-specification">
                        <?
		$ArtributQur="select description from flagging_product_feature where user_defined_sku_id='".$ProRow["user_defined_sku_id"]."'";
		$ArtributRes=mysql_query($ArtributQur);
		$ArtributTot=mysql_affected_rows();
		if($ArtributTot>0)
		{$ArtributRow=mysql_fetch_array($ArtributRes);?>
                        <div class="pro-specification-box">
                            <h3 class="middle-head">Product Features</h3>
                            <h4> <?php echo strip_tags(stripslashes($ArtributRow['description']),"<br><img><a><b><strong><u><i><p>");?>
                            </h4>
                        </div>
                        <? }?>
                        <?
		$SpecQur="select * from flagging_product_specification where user_defined_sku_id='".$ProRow["user_defined_sku_id"]."'";
		$SpecRes=mysql_query($SpecQur);
		$SpecTot=mysql_affected_rows();
		if($SpecTot>0)
		{?>
                        <div class="pro-specification-box speci-link" style="width:100%;">
                            <h3 class="middle-head">Product Specifications</h3>
                            <h4>
                                <? while($SpecRow=mysql_fetch_array($SpecRes)){
		  $FileNm=getModifiedUrlNamechangeJET(trim(stripslashes($ProRow['product_title']))).".pdf";
		  //$FileNm=str_replace("-pdf",".pdf",$FileNm);$FileNm=str_replace("-PDF",".PDF",$FileNm);  ?>
                                <?php echo strip_tags(stripslashes($SpecRow['description']));?>
                                <? if($SpecRow['filename']!=''){?>
                                <p style="line-height:20px;"><img alt="application/pdf icon"
                                        src="images/application-pdf.png" style="vertical-align: middle;"><a
                                        href="files/<?php echo stripslashes($SpecRow['filename']);?>"
                                        type="application/pdf"><?php echo $FileNm;?></a></p>
                                <? }}?>
                            </h4>
                        </div>
                        <? }?>
                        <?
		$PriceQur="select description from  flagging_product_pricedesc where user_defined_sku_id='".$ProRow["user_defined_sku_id"]."'";
		$PriceRes=mysql_query($PriceQur);
		$PriceTot=mysql_affected_rows();
		if($PriceTot>0)
		{$PriceRow=mysql_fetch_array($PriceRes);?>
                        <div class="pro-specification-box" style="width:100%;">
                            <h3 class="middle-head">Product Pricing Description</h3>
                            <h4> <?php echo trim(stripslashes($PriceRow['description']));?></h4>
                        </div>
                        <? }?>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?
  /*$category=explode(",",$ProRow['JetBrowseNodeID']);
  for($i=0;$i<=$category;$i++)
  {
  	echo $category1.="and concat(',',concat(JetBrowseNodeID,','))  like '%,".$category[$i].",%'";
  }*/
  if($ProRow['JetBrowseNodeID']!='')
  {
	  $tagsarray = explode(",",$ProRow['JetBrowseNodeID']);
	  for($TT=0;$TT<count($tagsarray);$TT++)
	  {	
		if(trim($tagsarray[$TT])!='')
		$category1.="concat(',',concat(JetBrowseNodeID,','))  like '%,".$tagsarray[$TT].",%' or ";
	  }
  }
  if($category1!=''){$category1=substr($category1,0,-3);}
  $get_tempRE="select product_title,item_price,user_defined_sku_id,main_image_url,id from flagging_searplusitems where main_image_url!='' and main_image_url is not null AND user_defined_sku_id!='".$ProRow['user_defined_sku_id']."' AND parent_id=0 and ($category1) limit 0,4"; 
  $getsRe=mysql_query($get_tempRE);
  $totRe=mysql_affected_rows();
  if($totRe<=0)
  {
	  $get_tempRE="select product_title,item_price,user_defined_sku_id,main_image_url,id from flagging_searplusitems where main_image_url!='' and main_image_url is not null AND user_defined_sku_id!='".$ProRow['user_defined_sku_id']."' AND parent_id=0 and ($category1) limit 0,4"; 
	  $getsRe=mysql_query($get_tempRE);
	  $totRe=mysql_affected_rows();
  }
  if($totRe>0){?>
    <div class="related-item-deta">
        <div class="wrapper">
            <h3 class="middle-head">RELATED ITEMS</h3>
            <div class="related-item-detail">
                <? while($RowRe=mysql_fetch_object($getsRe)){  
			$image_url=$IMGPATHURL."Products/croped/".stripslashes($RowRe->main_image_url); 
			//$image_url=stripslashes($RowRe->main_image_url); 
			/*if(!file_exists($image_url))
			  {
					$image_url=stripslashes($RowRe->main_image_url);
					$image_url=str_replace("https://www.mutualindustries.net/","",$image_url);
					$image_url=str_replace("http://www.mutualindustries.net/","",$image_url);
					$image_url="goodthumbOri.php?src=".$image_url."&w=250&h=190";
			  }*/
			/*$grQryRs=mysql_query("SELECT sizesku FROM item_sizes WHERE sku='".$RowRe->user_defined_sku_id."' order by name asc limit 0,1");
			if(mysql_affected_rows()>0)
			{
				$SizeQryRow=mysql_fetch_array($grQryRs);
				$sizesku=stripslashes($SizeQryRow['sizesku']);
				//$image_url=$IMGPATHURL."Products/thumb/".stripslashes($sizesku).".jpg";
				$image_url=stripslashes($RowRe->main_image_url); 
			}*/
			?>
                <div class="related-item-box">
                    <div class="related-item-box-in">
                        <div class="related-item-img">
                            <? if($RowRe->main_image_url!='' ){ ?><a href="<?=Get_ProductUrl($RowRe->id);?>"><img
                                    src="<?=stripslashes($image_url);?>"
                                    alt="<?=stripslashes($RowRe->user_defined_sku_id);?>, <?=str_replace('"','',stripslashes($RowRe->product_title));?>, <?=$siteALTTXT;?>"
                                    title="<?=stripslashes($RowRe->user_defined_sku_id);?>, <?=str_replace('"','',stripslashes($RowRe->product_title));?>, <?=$siteALTTXT;?>" /></a>
                            <? }else{ ?><img src="noimg.jpg"
                                alt="<?=stripslashes($RowRe->user_defined_sku_id);?>, <?=str_replace('"','',stripslashes($RowRe->product_title));?>, <?=$siteALTTXT;?>"
                                title="<?=stripslashes($RowRe->user_defined_sku_id);?>, <?=str_replace('"','',stripslashes($RowRe->product_title));?>, <?=$siteALTTXT;?>">
                            <? } ?>
                        </div>
                        <div class="related-item-img-detail">
                            <h4><a href="<?=Get_ProductUrl($RowRe->id);?>"><?=stripslashes($RowRe->product_title);?></a>
                            </h4>
                            <p>SKU# <?=stripslashes($RowRe->user_defined_sku_id);?> </p>
                            <h6 class="price">$<?=stripslashes($RowRe->item_price);?></h6>
                        </div>
                    </div>
                </div>
                <? }?>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <? }?>
    <?
		  $get_tempRE3="select product_title,item_price,user_defined_sku_id,main_image_url,id from flagging_searplusitems where main_image_url!='' and main_image_url is not null AND parent_id=0 order by rand() limit 0,4"; 
		  $getsRe3=mysql_query($get_tempRE3);$totRe3=mysql_affected_rows();if($totRe3>0){?>
    <div class="customers-viewed-detail">
        <div class="wrapper">
            <h3 class="middle-head">CUSTOMERS ALSO VIEWED</h3>
            <div class="related-item-detail">
                <? while($RowRe=mysql_fetch_object($getsRe3)){
						//$SelRat="SELECT COUNT(id) as totreview, AVG(rating) as totrating FROM flagging_reviews where vid='".addslashes($RowRe->id)."' and status='Y' ";
						//$SelRatRes=mysql_query($SelRat);
						//$RatRow=mysql_fetch_object($SelRatRes);
						//$average=$RatRow->totrating;
						
						  $tittl=stripslashes($RowRe->product_title);
						  $tittl=str_replace("&quot;",'"',$tittl);
						  $tittlXX=$tittl;
						  $tittl=substr($tittl,0,60);
						  if(strlen($tittlXX)>60){$tittl=$tittl."...";}
						  
							$image_url=$IMGPATHURL."Products/croped/".stripslashes($RowRe->main_image_url);
							//$image_url=stripslashes($RowRe->main_image_url); 
							/*if(!file_exists($image_url))
							  {
									$image_url=stripslashes($RowRe->main_image_url);
									$image_url=str_replace("https://www.mutualindustries.net/","",$image_url);
									$image_url=str_replace("http://www.mutualindustries.net/","",$image_url);
									$image_url="goodthumbOri.php?src=".$image_url."&w=243&h=190";
							  }*/
							/*$grQryRs=mysql_query("SELECT sizesku FROM item_sizes WHERE sku='".$RowRe->user_defined_sku_id."' order by name asc limit 0,1");
							if(mysql_affected_rows()>0)
							{
								$SizeQryRow=mysql_fetch_array($grQryRs);
								$sizesku=stripslashes($SizeQryRow['sizesku']);
								$image_url=$IMGPATHURL."Products/thumb/".stripslashes($sizesku).".jpg";
							}*/
					?>

                <div class="related-item-box">
                    <div class="related-item-box-in">
                        <div class="related-item-img">
                            <? if($RowRe->main_image_url!='' ){ ?><a href="<?=Get_ProductUrl($RowRe->id);?>"><img
                                    src="<?=stripslashes($image_url);?>"
                                    alt="<?=stripslashes($RowRe->user_defined_sku_id);?>, <?=str_replace('"','',stripslashes($RowRe->product_title));?>, <?=$siteALTTXT;?>"
                                    title="<?=stripslashes($RowRe->user_defined_sku_id);?>, <?=str_replace('"','',stripslashes($RowRe->product_title));?>, <?=$siteALTTXT;?>" /></a>
                            <? }else{ ?><img src="noimg.jpg"
                                alt="<?=stripslashes($RowRe->user_defined_sku_id);?>, <?=str_replace('"','',stripslashes($RowRe->product_title));?>, <?=$siteALTTXT;?>"
                                title="<?=stripslashes($RowRe->user_defined_sku_id);?>, <?=str_replace('"','',stripslashes($RowRe->product_title));?>, <?=$siteALTTXT;?>">
                            <? } ?>
                        </div>
                        <div class="related-item-img-detail">
                            <h4><a href="<?=Get_ProductUrl($RowRe->id);?>"><?=stripslashes($tittl);?></a></h4>
                            <p>SKU#
                                <? echo stripslashes($RowRe->user_defined_sku_id);?>
                            </p>
                            <h6 class="price">$
                                <? echo stripslashes($RowRe->item_price);?>
                            </h6>
                        </div>
                    </div>
                </div>
                <? } ?>

                <div class="clear"></div>
            </div>
        </div>
    </div>
    <? } ?>
    </div>
    <? include("footer.php");?>
    <script src="js/jquery-3.2.0.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/scriptbreaker-multiple-accordion-1.js"></script>
    <script language="JavaScript">
    $(document).ready(function() {
        $(".topnav").accordion({
            accordion: false,
            speed: 500,
            closedSign: '<i class="fa fa-plus"></i>',
            openedSign: '<i class="fa fa-minus"></i>'
        });
    });
    </script>
    <script src="js/simplemenu.js"></script>
    <script src="js/easyzoom.js"></script>
    <link rel="stylesheet" type="text/css" href="css/font-awesome.css" />
    <script>
    // Instantiate EasyZoom instances
    var $easyzoom = $('.easyzoom').easyZoom();

    // Setup thumbnails example
    var api1 = $easyzoom.filter('.easyzoom--with-thumbnails').data('easyZoom');

    $('.thumbnails').on('click', 'a', function(e) {
        var $this = $(this);

        e.preventDefault();

        // Use EasyZoom's `swap` method
        api1.swap($this.data('standard'), $this.attr('href'));
    });
    </script>
    <script>
    function fillinbixxx(valll) {
        valll = valll.replace(/[^0-9]+/g, '');
        document.getElementById("qtyy").value = valll;
    }

    function gogtoto(id) {
        qtyyX = document.getElementById("qtyy").value;
        SkuId = document.getElementById("SKUID-Main").value;
        var drp_1 = document.getElementById('drp_1').value;
        var drp_2 = document.getElementById('drp_2').value;
        var drp_3 = document.getElementById('drp_3').value;

        AddtoCartAjaxDtl(id, id, SkuId, qtyyX, drp_1, drp_2, drp_3);
    }

    function gogtotoSize() {
        qtyyX = document.getElementById("qtyy").value;
        id = document.getElementById("selitmid").value;
        SkuId = document.getElementById("SKUID-Main").value;
        AddtoCartAjaxDtl(id, id, SkuId, qtyyX);
    }

    function review() {}

    function AddToWatchList(vehicleid) {
        var http2SF = false;
        if (navigator.appName == "Microsoft Internet Explorer") {
            http2SF = new ActiveXObject("Microsoft.XMLHTTP");
        } else {
            http2SF = new XMLHttpRequest();
        }
        http2SF.abort();
        http2SF.open("GET", "ajax_validation.php?Type=AddToWatchListSEAERCH&vehicleid=" + vehicleid, true);
        document.getElementById("AddToWatchListID" + vehicleid).innerHTML = "Please wait...";
        http2SF.onreadystatechange = function() {
            if (http2SF.readyState == 4) {
                if (http2SF.responseText == "Added") {
                    document.getElementById("AddToWatchListID" + vehicleid).innerHTML = "Added to watch list.";
                    return false;
                }
                if (http2SF.responseText == "Already added") {
                    document.getElementById("AddToWatchListID" + vehicleid).innerHTML = "Added to watch list.";
                    return false;
                }
                if (http2SF.responseText == "NotLoggedin") {
                    document.getElementById("AddToWatchListID" + vehicleid).innerHTML =
                        "Please login to add in watch list.";
                    return false;
                }
            }
        }
        http2SF.send(null);
    }

    function getRandom(length) {
        return Math.floor(Math.pow(10, length - 1) + Math.random() * 9 * Math.pow(10, length - 1));
    }

    function AddtoCartAjaxDtl(AddtoCartAjax_ID, itemid, price, quantity, Drop1, Drop2, Drop3) {
        var http7333 = false;
        if (navigator.appName == "Microsoft Internet Explorer") {
            http7333 = new ActiveXObject("Microsoft.XMLHTTP");
        } else {
            http7333 = new XMLHttpRequest();
        }
        http7333.abort();
        http7333.open("GET", "ajax_validation.php?Type=AddtoCartAjax&itemid=" + itemid + "&price=" + price +
            "&quantity=" + quantity + "&colors=" + Drop1 + "&sizes=" + Drop2 + "&types=" + Drop3, true);
        http7333.onreadystatechange = function() {
            if (http7333.readyState == 4) {
                window.location.href = 'items-in-your-cart';
                return false;
            }
        }
        http7333.send(null);
    }
    </script>
    <? 
$ProductQury223="SELECT * FROM flagging_product_size WHERE `user_sku`='".addslashes($ProRow['user_defined_sku_id'])."' and `title`='".addslashes($ProRow['product_title'])."'";
$ProductRes223=mysql_query($ProductQury223);
$tot223=mysql_affected_rows();
if($tot223>0)
{
?>
    <script>
    function loadDropDown() {
        var httpSizee = false;
        if (navigator.appName == "Microsoft Internet Explorer") {
            httpSizee = new ActiveXObject("Microsoft.XMLHTTP");
        } else {
            httpSizee = new XMLHttpRequest();
        }
        httpSizee.abort();
        httpSizee.open("GET", "loaddropdown.php?itemsku=<?=$ProRow['user_defined_sku_id'];?>", true);
        httpSizee.onreadystatechange = function() {
            if (httpSizee.readyState == 4) {
                aa = httpSizee.responseText; //alert(aa);
                document.getElementById('DropDown').innerHTML = httpSizee.responseText;
                Getsku('1', '1');
                return false;
            }
        }
        httpSizee.send(null);
    }
    </script>
    <? }?>
    <script>
    function Getsku(id, no) {
        //alert(no);
        var drp_1 = document.getElementById('drp_1');
        if (typeof(drp_1) != 'undefined' && drp_1 != null) {
            var drp_1 = document.getElementById('drp_1').value;
            //drp_1 = drp_1.replace("&","PPPOPPP");drp_1 = drp_1.replace('"',"XXXOXXX");drp_1 = drp_1.replace("#","OOOPOOO");

        } else {
            var drp_1 = "";
        }
        var drp_2 = document.getElementById('drp_2');
        if (typeof(drp_2) != 'undefined' && drp_2 != null) {
            var drp_2 = document.getElementById('drp_2').value;
            //drp_2 = drp_2.replace("&","PPPOPPP");drp_2 = drp_2.replace('"',"XXXOXXX");drp_2 = drp_2.replace("#","OOOPOOO");
        } else {
            var drp_2 = "";
        }
        var drp_3 = document.getElementById('drp_3');
        if (typeof(drp_3) != 'undefined' && drp_3 != null) {
            var drp_3 = document.getElementById('drp_3').value;
            //drp_3 = drp_3.replace("&","PPPOPPP");drp_3 = drp_3.replace('"',"XXXOXXX");drp_3 = drp_3.replace("#","OOOPOOO");
        } else {
            var drp_3 = "";
        }
        //var drp_1=document.getElementById('drp_1').value;
        //	drp_1 = drp_1.replace("&","PPPOPPP");drp_1 = drp_1.replace('"',"XXXOXXX");drp_1 = drp_1.replace("#","OOOPOOO");
        //	var drp_2=document.getElementById('drp_2').value;
        //	drp_2 = drp_2.replace("&","PPPOPPP");drp_2 = drp_2.replace('"',"XXXOXXX");drp_2 = drp_2.replace("#","OOOPOOO");
        //	var drp_3=document.getElementById('drp_3').value;
        //	drp_3 = drp_3.replace("&","PPPOPPP");drp_3 = drp_3.replace('"',"XXXOXXX");drp_3 = drp_3.replace("#","OOOPOOO");
        var main_sku = '<? echo $ProRow['
        user_defined_sku_id '];?>';
        //alert(drp_2);

        var httpSizee = false;
        if (navigator.appName == "Microsoft Internet Explorer") {
            httpSizee = new ActiveXObject("Microsoft.XMLHTTP");
        } else {
            httpSizee = new XMLHttpRequest();
        }
        httpSizee.abort();
        httpSizee.open("GET", "loadsku.php?drp_1=" + drp_1 + "&drp_2=" + drp_2 + "&drp_3=" + drp_3 + "&main_sku=" +
            main_sku, true);
        httpSizee.onreadystatechange = function() {
            if (httpSizee.readyState == 4) {
                aa = httpSizee.responseText; //alert(aa);
                if (aa != '') {
                    bb = httpSizee.responseText.split("OOPOO");
                    SkuId = bb[0];
                    Price = bb[1];
                    if (SkuId != '') {
                        document.getElementById('SKUID').innerHTML = SkuId;
                        document.getElementById('SKUID-Main').value = SkuId;
                    } else {
                        document.getElementById('SKUID').innerHTML = '<?=$ProRow['user_defined_sku_id'];?>';
                        document.getElementById('SKUID-Main').value = '<?=$ProRow['user_defined_sku_id'];?>';
                    }
                    if (Price != '') {
                        document.getElementById('Price').innerHTML = "$" + Price; <
                        ?
                        if ($case_value != '') {
                            ?
                            >
                            var x = Price;
                            var y = <?=$case_value;?>;
                            var res = x / y;
                            document.getElementById('eachprice').innerHTML = "$" + res.toFixed(2) + " each";
                            document.getElementById('drop_value').style.display = "block";
                            document.getElementById('drop_value1').style.display = "none"; < ?
                        } ? >
                    } else {
                        document.getElementById('Price').innerHTML = "$" + <?=$EPRC;?>; <
                        ?
                        if ($case_value != '') {
                            ?
                            >
                            var Price = <?=$EPRC;?>;
                            var x = Price;
                            var y = <?=$case_value;?>;
                            var res = x / y;
                            document.getElementById('eachprice').innerHTML = "$" + res.toFixed(2) + " each";
                            document.getElementById('drop_value').style.display = "block";
                            document.getElementById('drop_value1').style.display = "none"; < ?
                        } ? >
                    }
                }
                return false;
            }
        }
        httpSizee.send(null);
    }
    </script>
    <link href="shadowbox/shadowbox.css" rel="stylesheet" type="text/css" />
    <script src="shadowbox/shadowbox2.js"></script>
    <script>
    Shadowbox.init({
        language: 'en',
        players: ['img', 'html', 'iframe', 'qt', 'wmp', 'swf', 'flv']
    });
    </script>
    <? include("googleanalytic.php");?>
    <? include("dbclose.php");?>
</body>

</html>