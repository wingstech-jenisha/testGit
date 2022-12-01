<? 
include("connect.php");
$TopTab="cart";
$mesgg="";
if(mysql_real_escape_string($_GET['Rid']))
{
	GTG_remove_from_cart(mysql_real_escape_string($_GET['Rid']),$_GET['Color'],$_GET['price'],$_GET['Size'],$_GET['CFLAG'],$_GET['LETER'],$_GET['ASIGN']);
	echo "<script>window.location.href='items-in-your-cart?back=".$_GET["back"]."';</script>";
}
$_SESSION['QryErrMsg']="";
if($_POST['HidQTY']==1)
{
	$ii = $_REQUEST['i'];
	for($i=0;$i<=$ii;$i++)
	{
		$val = $_POST["val".$i];
		$chk = "chk".$i;
		if(isset($val))
		{
			$update = explode('@',$val);
			$is = $update[0];
			$p = $update[1]; 
			$textname = "q".$i;			
			$q = $_POST[$textname];
			
			$size = "size".$i;
			$s = $_REQUEST["size".$i]; 	
					
			$color = "color".$i;
			$c = $_REQUEST["color".$i];
			
			$llater = "llater".$i;
			$lll = $_REQUEST["llater".$i];
			
			$aasign = "aasign".$i;
			$aaa = $_REQUEST["aasign".$i];
			
			if(is_numeric($q) && $q!=0)
			{				
				GTG_add_to_cart_individual($p,$q,$c,$s,$lll,$aaa);
			}
			else
			{
				$_SESSION['QryErrMsg']="Please enter valid quantity";				
			}
		}	
	}
	
}
$pgtitle="Shopping Cart | Flagging Direct";
if($meta_kwords==''){$meta_kwords="Shopping Cart, ".$METAKEYWORD;}
if($meta_desc==''){$meta_desc="Shopping Cart, ".$METADESCRIPTION;}
$ZipCode=GetName1("mutual_users","zip","id",$_SESSION['UsErId']);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?=$pgtitle;?></title>
    <meta name="description" content="<?=$meta_desc;?>" />
    <meta name="keywords" content="<?=$meta_kwords;?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1" name="viewport" />
    <? include("favicon.php");?>
    <link rel="stylesheet" href="css/simplemenu.css">
    <link rel="stylesheet" type="text/css" href="css/style.css<?=$CSSLOAD;?>" />
    <link rel="stylesheet" type="text/css" href="css/responsive.css<?=$CSSLOAD;?>" />
    <style>
    #shipping {
        width: 60%;
    }

    @media only screen and (max-width:767px) {
        #shipping {
            width: 100%;
        }
    }
    </style>
    <? include("headermore.php");?>
</head>

<body onLoad="<? if($ZipCode!=''){?>UpsDropDown();<? }?>">
    <? include("top.php");?>
    <section id="middlewrapper">
        <div class="wrapper">
            <div class="category-detail">
                <? include("cmsleft.php");?>
                <div class="category-detail-right">
                    <h1>Shopping Cart</h1>
                    <div class="tab-section">
                        <div class="content">
                            <div class="about-deta">
                                <form name="addprod" id="addprod" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="HidQTY" id="HidQTY" value="0" />
                                    <? $QryErrMsg_MASTER="Y";$TotWeight=0;$TotShipPrc=0;
		$p = isset($_SESSION['P']) ? $_SESSION['P'] : 0;										
		$q = isset($_SESSION['Q']) ? $_SESSION['Q'] : 0; 
		$COLOR = isset($_SESSION['COLOR']) ? $_SESSION['COLOR'] : 0; 	
		$SIZE = isset($_SESSION['SIZE']) ? $_SESSION['SIZE'] : 0;
		$CFLAG = isset($_SESSION['CFLAG']) ? $_SESSION['CFLAG'] : 0;
		$WPRICE = isset($_SESSION['PRICE']) ? $_SESSION['PRICE'] : 0;
		$LETER = isset($_SESSION['LETER']) ? $_SESSION['LETER'] : 0; 	
		$ASIGN = isset($_SESSION['ASIGNN']) ? $_SESSION['ASIGNN'] : 0;
		$GCARD = isset($_SESSION['GCARD']) ? $_SESSION['GCARD'] : 0;
		$GCARDMSG = isset($_SESSION['GCARDMSG']) ? $_SESSION['GCARDMSG'] : 0;
		if($p > 0){$cartflag = 1; 
		?>
                                    <div class="cart_table dskvew">
                                        <table class="carttabel_deta">
                                            <tbody>
                                                <tr>
                                                    <th></th>
                                                    <th style="text-align:left;width:50%">Item </th>
                                                    <th style="text-align:right;width:10%">Price </th>
                                                    <th style="text-align:center;width:10%">Quantity </th>
                                                    <th style="text-align:right;width:10%">Total </th>
                                                </tr>
                                                <? $INOOO=0;
				for($i=0;$i<count($p);$i++)
				{
					if($p[$i]!=0 && $p[$i]!="")
					{
						$queryMain="select product_title,item_price,id,user_defined_sku_id,main_image_url,shipping_weight_pounds,package_length_inches,package_width_inches,package_height_inches from mutual_searplusitems where id='".$p[$i]."' limit 0,1";
						$result=mysql_query($queryMain);
						$TotCrtItm=mysql_affected_rows();
                    
						if($TotCrtItm<=0)
						{
							$queryMain="select product_title,item_price,id,user_defined_sku_id,main_image_url,shipping_weight_pounds from searplus where id='".$p[$i]."' limit 0,1";
							$result=mysql_query($queryMain);
							$TotCrtItm=mysql_affected_rows();
						}
						if($TotCrtItm>0)
						{ 
							$row = mysql_fetch_array($result);
							$PPRiceAA=number_format($row['item_price'],2,'.','');
							$PRNMAM_NO=stripslashes($row['user_defined_sku_id']);
							$PRNMAM=stripslashes($row['product_title']); $Weight=stripslashes($row['shipping_weight_pounds']);
							$length=$row['package_length_inches'];
							 $width=$row['package_width_inches'];
							 $height=$row['package_height_inches'];
							$imgurll=stripslashes($row['main_image_url']);
							$MasterURLL=Get_ProductUrl($p[$i]);
							$image_url=$IMGPATHURL."Products/croped/".stripslashes($row['main_image_url']);
							//$image_url=stripslashes($row['main_image_url']);
							/*if(!file_exists($image_url))
							  {
									$image_url=stripslashes($row['main_image_url']);
									$image_url=str_replace("https://www.megasafetymart.com/","",$image_url);
									$image_url=str_replace("http://www.megasafetymart.com/","",$image_url);
									$image_url="goodthumbOri.php?src=".$image_url."&w=110&h=110";
							  }*/
							$grQryRs=mysql_query("SELECT sku FROM item_sizes WHERE sizesku='".stripslashes($row['user_defined_sku_id'])."' limit 0,1");
							$TotSizzez=mysql_affected_rows();
							if($TotSizzez>0)
							{
								$SizeQryRow=mysql_fetch_array($grQryRs);
								$sku=$SizeQryRow['sku'];
								$MasterURLL=Get_CartProductUrl($sku);
							}
							$TempWeight=($Weight*$q[$i]);
							$TotWeight=$TotWeight+$TempWeight;
							
							$querySHP="select shippingprice from shippingprices_walmart where merchant_sku='".$row['user_defined_sku_id']."' limit 0,1";
							$resultSHP= mysql_query($querySHP);
							if(mysql_affected_rows()>0)
							{ 
								$rowSHP=mysql_fetch_array($resultSHP);
								$ShipPrc=($rowSHP['shippingprice']*$q[$i]);
								//$TotShipPrc=$TotShipPrc+$ShipPrc;
							}
							else
							{
								$querySHP2="select shipping_charge_amount from products where  user_defined_sku_id='".$row['user_defined_sku_id']."' limit 0,1";
								$resultSHP2= mysql_query($querySHP2);
								if(mysql_affected_rows()>0)
								{ 
									$rowSHP2=mysql_fetch_array($resultSHP2);
									$ShipPrc=($rowSHP2['shipping_charge_amount']*$q[$i]);
									//$TotShipPrc=$TotShipPrc+$ShipPrc;
								}
							}
							$INOOO++;
				  ?>
                                                <input type="hidden" name="val<?=$i;?>"
                                                    value="<?=$i;?>@<?=$p[$i];?>" /><input type="hidden" name="i"
                                                    value="<?=$i;?>" /><input type="hidden" name="size<?=$i;?>"
                                                    value="<?=$SIZE[$i];?>" /><input type="hidden" name="color<?=$i;?>"
                                                    value="<?=$COLOR[$i];?>" /><input type="hidden"
                                                    name="aasign<?=$i;?>" value="<?=$ASIGN[$i];?>" /><input
                                                    type="hidden" name="llater<?=$i;?>" value="<?=$LETER[$i];?>" />
                                                <? $pppiddd=$p[$i];?>
                                                <tr>
                                                    <td class="img">
                                                        <a href="<? echo $MasterURLL;?>">
                                                            <? if($imgurll!="") {?><img src="<?=$image_url;?>"
                                                                width="110" border="0"
                                                                style="border:1px solid #cccccc" />
                                                            <? } else {?>
                                                            <img src="images/noimage.gif" border="0" width="110"
                                                                style="border:1px solid #cccccc" />
                                                            <? } ?>
                                                        </a>
                                                    </td>
                                                    <td class="desc" style="font-size: 12px;line-height:20px;"><a
                                                            href="<? echo $MasterURLL;?>"
                                                            style="color:#000000"><?=stripslashes($PRNMAM);?></a><br>
                                                        <? 
				 	if($COLOR[$i]!='')
				 	{
				 	$drp_1=$COLOR[$i];
					$drp_2=$SIZE[$i];
					$drp_3=$CFLAG[$i];
					if($drp_1!=''){$drp_1=str_replace("PPPOPPP","&",$drp_1);$drp_1=str_replace("OOOPOOO","#",$drp_1);$drp_1=str_replace("OXXXOXXXO",'"',$drp_1);$drp_1=str_replace("OXXXOOXXXO","'",$drp_1);$drp_1=str_replace("OXXXOOOXXXO","’",$drp_1);}
					if($drp_2!=''){$drp_2=str_replace("PPPOPPP","&",$drp_2);$drp_2=str_replace("OOOPOOO","#",$drp_2);$drp_2=str_replace("OXXXOXXXO",'"',$drp_2);$drp_2=str_replace("OXXXOOXXXO","'",$drp_2);$drp_2=str_replace("OXXXOOOXXXO","’",$drp_2);}
					if($drp_3!=''){$drp_3=str_replace("PPPOPPP","&",$drp_3);$drp_3=str_replace("OOOPOOO","#",$drp_3);$drp_3=str_replace("OXXXOXXXO",'"',$drp_3);$drp_3=str_replace("OXXXOOXXXO","'",$drp_3);$drp_3=str_replace("OXXXOOOXXXO","’",$drp_3);}
				 
				 	$Color = str_replace("'", "\'", $drp_1);
				 	$Color = str_replace('"', '\"', $Color);
				 	$queryPSZ="select * from mutual_product_size where name='".$Color."' and sku like '%".$WPRICE[$i]."%'";
					$resultPSZ= mysql_query($queryPSZ);
					if(mysql_affected_rows()>0)
					{ 
						$rowPSZ=mysql_fetch_array($resultPSZ);
						echo "<span>".stripslashes($rowPSZ['type'])."</span>: ".stripslashes($rowPSZ['name'])."<br>";
						$PPRiceAA=str_replace(",","",$rowPSZ['price']);
					}?>
                                                        <? 
					$Size = str_replace("'", "\'", $drp_2);
				 	$Size = str_replace('"', '\"', $Size);
					$queryPSZ="select * from mutual_product_size where name='".$Size."' and sku like '%".$WPRICE[$i]."%'";
					$resultPSZ= mysql_query($queryPSZ);
					if(mysql_affected_rows()>0)
					{ 
						$rowPSZ=mysql_fetch_array($resultPSZ);
						echo "<span>".stripslashes($rowPSZ['type'])."</span>: ".stripslashes($rowPSZ['name'])."<br>";
						$PPRiceAA=str_replace(",","",$rowPSZ['price']);
					}?>
                                                        <? 
					$Flag = str_replace("'", "\'", $drp_3);
				 	$Flag = str_replace('"', '\"', $Flag);
					$queryPSZ="select * from mutual_product_size where name='".$Flag."' and sku like '%".$WPRICE[$i]."%'";
					$resultPSZ= mysql_query($queryPSZ);
					if(mysql_affected_rows()>0)
					{ 
						$rowPSZ=mysql_fetch_array($resultPSZ);
						echo "<span>".stripslashes($rowPSZ['type'])."</span>: ".stripslashes($rowPSZ['name'])."<br>";
						$PPRiceAA=str_replace(",","",$rowPSZ['price']);
					}
					}?>
                                                        <span>SKU#</span> <?=$WPRICE[$i];?><br>
                                                        <a href="<?=$SITE_URL;?>/items-in-your-cart?Rid=<? echo $p[$i];?>&Color=<?=$COLOR[$i];?>&price=<?=$WPRICE[$i];?>&Size=<?=$SIZE[$i];?>&CFLAG=<?=$CFLAG[$i];?>&LETER=<?=$LETER[$i];?>&ASIGN=<?=$ASIGN[$i];?>&back=<? echo $_GET['back'];?>"
                                                            style="color:#000000">Remove</a>
                                                    </td>
                                                    <? $totalcost = $PPRiceAA*$q[$i];$subtotal = $subtotal +  $totalcost;$_SESSION['total'] = $subtotal; ?>
                                                    <td class="price" style="text-align:right">
                                                        $<?=number_format($PPRiceAA,2, '.', '');?></td>
                                                    <td class="qty" style="text-align:center;"><?=$q[$i];?></td>
                                                    <td class="total" style="text-align:right;">
                                                        $<?=number_format($totalcost,2, '.', '');?></td>
                                                </tr>
                                                <? } ?>
                                                <? } ?>
                                                <? } ?>
                                                <? $_SESSION['STotWeight']=$TotWeight; //$_SESSION['shippingcost']=number_format($TotShipPrc,2, '.', '');?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="cart_table mobvew">
                                        <table class="carttabel_deta">
                                            <tbody>
                                                <?  $TotWeight=0;$TotShipPrc=0;
				$p = isset($_SESSION['P']) ? $_SESSION['P'] : 0;										
				$q = isset($_SESSION['Q']) ? $_SESSION['Q'] : 0; 
				$COLOR = isset($_SESSION['COLOR']) ? $_SESSION['COLOR'] : 0; 	
				$SIZE = isset($_SESSION['SIZE']) ? $_SESSION['SIZE'] : 0;
				$CFLAG = isset($_SESSION['CFLAG']) ? $_SESSION['CFLAG'] : 0;
				$WPRICE = isset($_SESSION['PRICE']) ? $_SESSION['PRICE'] : 0;
				$LETER = isset($_SESSION['LETER']) ? $_SESSION['LETER'] : 0; 	
				$ASIGN = isset($_SESSION['ASIGNN']) ? $_SESSION['ASIGNN'] : 0;
				$GCARD = isset($_SESSION['GCARD']) ? $_SESSION['GCARD'] : 0;
				$GCARDMSG = isset($_SESSION['GCARDMSG']) ? $_SESSION['GCARDMSG'] : 0;
				$INOOO=0;
				for($i=0;$i<count($p);$i++)
				{
					if($p[$i]!=0 && $p[$i]!="")
					{
						$queryMain="select product_title,item_price,id,user_defined_sku_id,main_image_url,shipping_weight_pounds,package_length_inches,package_width_inches,package_height_inches from mutual_searplusitems where id='".$p[$i]."' limit 0,1";
						$result=mysql_query($queryMain);
						$TotCrtItm=mysql_affected_rows();
						if($TotCrtItm>0)
						{ 
							$row = mysql_fetch_array($result);
							$PPRiceAA=number_format($row['item_price'],2,'.','');
							$PRNMAM_NO=stripslashes($row['user_defined_sku_id']);
							$PRNMAM=stripslashes($row['product_title']); $Weight=stripslashes($row['shipping_weight_pounds']);
							 $length=$row['package_length_inches'];
							 $width=$row['package_width_inches'];
							 $height=$row['package_height_inches'];
							//$imgurll=stripslashes($row['main_image_url']);
							$MasterURLL=Get_ProductUrl($p[$i]);
							$imgurll=$IMGPATHURL."Products/croped/".stripslashes($row['main_image_url']);
							/*if(!file_exists($image_url))
							  {
									$image_url=stripslashes($row['main_image_url']);
									$image_url=str_replace("https://www.megasafetymart.com/","",$image_url);
									$image_url=str_replace("http://www.megasafetymart.com/","",$image_url);
									$image_url="goodthumbOri.php?src=".$image_url."&w=70&h=70";
							  }*/
							$INOOO++;
				  ?>
                                                <? $pppiddd=$p[$i];?>
                                                <tr>
                                                    <td class="img" style="vertical-align:top;width:10%!important"><a
                                                            href="<? echo $MasterURLL;?>">
                                                            <? if($imgurll!="") {?><img src="<?=$image_url;?>"
                                                                width="70" border="0"
                                                                style="border:1px solid #cccccc" />
                                                            <? } else {?><img src="images/noimage.gif" border="0"
                                                                width="70" style="border:1px solid #cccccc" />
                                                            <? } ?>
                                                        </a></td>
                                                    <td class="desc" style="font-size:12px;width:90%!important">
                                                        <a href="<? echo $MasterURLL;?>"
                                                            style="color:#000000"><?=stripslashes($PRNMAM);?></a><br><span>SKU#</span>
                                                        <?=stripslashes($PRNMAM_NO);?><br>

                                                        <? if($COLOR[$i]!='')
				 	{
				 	$drp_1=$COLOR[$i];
					$drp_2=$SIZE[$i];
					$drp_3=$CFLAG[$i];
					if($drp_1!=''){$drp_1=str_replace("PPPOPPP","&",$drp_1);$drp_1=str_replace("OOOPOOO","#",$drp_1);$drp_1=str_replace("OXXXOXXXO",'"',$drp_1);$drp_1=str_replace("OXXXOOXXXO","'",$drp_1);$drp_1=str_replace("OXXXOOOXXXO","’",$drp_1);}
					if($drp_2!=''){$drp_2=str_replace("PPPOPPP","&",$drp_2);$drp_2=str_replace("OOOPOOO","#",$drp_2);$drp_2=str_replace("OXXXOXXXO",'"',$drp_2);$drp_2=str_replace("OXXXOOXXXO","'",$drp_2);$drp_2=str_replace("OXXXOOOXXXO","’",$drp_2);}
					if($drp_3!=''){$drp_3=str_replace("PPPOPPP","&",$drp_3);$drp_3=str_replace("OOOPOOO","#",$drp_3);$drp_3=str_replace("OXXXOXXXO",'"',$drp_3);$drp_3=str_replace("OXXXOOXXXO","'",$drp_3);$drp_3=str_replace("OXXXOOOXXXO","’",$drp_3);}
				 
				 	$Color = str_replace("'", "\'", $drp_1);
				 	$Color = str_replace('"', '\"', $Color);
				 	$queryPSZ="select * from mutual_product_size where name='".$Color."' and sku like '%".$WPRICE[$i]."%'";
					$resultPSZ= mysql_query($queryPSZ);
					if(mysql_affected_rows()>0)
					{ 
						$rowPSZ=mysql_fetch_array($resultPSZ);
						echo "<span>".stripslashes($rowPSZ['type'])."</span>: ".stripslashes($rowPSZ['name'])."<br>";
						$PPRiceAA=str_replace(",","",$rowPSZ['price']);
					}?>
                                                        <? 
					$Size = str_replace("'", "\'", $drp_2);
				 	$Size = str_replace('"', '\"', $Size);
					$queryPSZ="select * from mutual_product_size where name='".$Size."' and sku like '%".$WPRICE[$i]."%'";
					$resultPSZ= mysql_query($queryPSZ);
					if(mysql_affected_rows()>0)
					{ 
						$rowPSZ=mysql_fetch_array($resultPSZ);
						echo "<span>".stripslashes($rowPSZ['type'])."</span>: ".stripslashes($rowPSZ['name'])."<br>";
						$PPRiceAA=str_replace(",","",$rowPSZ['price']);
					}?>
                                                        <? 
					$Flag = str_replace("'", "\'", $drp_3);
				 	$Flag = str_replace('"', '\"', $Flag);
					$queryPSZ="select * from mutual_product_size where name='".$Flag."' and sku like '%".$WPRICE[$i]."%'";
					$resultPSZ= mysql_query($queryPSZ);
					if(mysql_affected_rows()>0)
					{ 
						$rowPSZ=mysql_fetch_array($resultPSZ);
						echo "<span>".stripslashes($rowPSZ['type'])."</span>: ".stripslashes($rowPSZ['name'])."<br>";
						$PPRiceAA=str_replace(",","",$rowPSZ['price']);
					}
					}
					$TempWeight=($Weight*$q[$i]);
					$TotWeight=$TotWeight+$TempWeight;
					?>

                                                        <span
                                                            style="color: #ec3f1d;">$<?=number_format($PPRiceAA,2, '.', '');?>
                                                            X <?=$q[$i];?> Qty</span><br />
                                                        <a href="<?=$SITE_URL;?>/items-in-your-cart?Rid=<? echo $p[$i];?>&Color=<?=$COLOR[$i];?>&price=<?=$WPRICE[$i];?>&Size=<?=$SIZE[$i];?>&CFLAG=<?=$CFLAG[$i];?>&LETER=<?=$LETER[$i];?>&ASIGN=<?=$ASIGN[$i];?>&back=<? echo $_GET['back'];?>"
                                                            style="color:#000000;font-weight:normal">Remove</a>
                                                    </td>
                                                </tr>
                                                <? } ?>
                                                <? } ?>
                                                <? } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="cart-bot">
                                        <div class="cart-total">
                                            <div class="total">
                                                <div class="sub_total"> <span><b>Items (<?=$INOOO;?>):</b></span>
                                                    <span>$<?=number_format($_SESSION['total'],2, '.', '');?></span>
                                                </div>
                                                <div class="sub_total" id="shippmethss" style="display:none;">
                                                    <span><b>Shipping & Handling:</b></span> <span
                                                        id="shipchrg">$0.00<?php /*?>$<?=number_format($_SESSION['shippingcost'],2, '.', '');?><?php */?></span>
                                                </div>
                                                <div class="sub_total"> <span><b>Estimated Shipping:</b></span>
                                                    <span><input name="zipcode" id="zipcode" placeholder="Enter Zipcode"
                                                            onBlur="UpsDropDown();" class="c_textbox"
                                                            value="<?=GetName1("mutual_users","zip","id",$_SESSION['UsErId']);?>"
                                                            type="text" maxlength="9"></span>
                                                </div>
                                                <div class="sub_total" id="upsmethod"><span id="ups_drop"></span> </div>
                                                <div class="finel_total"> <span><b>Order Total:</b></span> <span
                                                        id="FinalTotal">$<?=number_format($_SESSION['total'],2, '.', '');?></span>
                                                </div>
                                            </div>
                                            <div class="c_but"><input class="cart-btn" value="PROCEED TO CHECKOUT"
                                                    type="button"
                                                    onClick="window.location.href='<?=$SECURE_URL;?>/checkout.php';session_price();">
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="cartbtn-row"><input class="cart-btn" value="PROCEED TO CHECKOUT"
                                                type="button"
                                                onClick="window.location.href='<?=$SECURE_URL;?>/checkout.php';session_price();">
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <input type="hidden" name="shipmeth" id="shipmeth" value="" /><input type="hidden"
                                        name="shipcharge" id="shipcharge" value="" />
                                    <input type="hidden" name="shipmethods" id="shipmethods" value="upsshhip" />
                                    <? }else{ ?>
                                    <p style="text-align:center"><strong>Shopping cart is empty.</strong></p>
                                    <? } ?>
                                </form>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </section>
    <? include("footer.php");?>
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
    <script src='js/hammer.min.js'></script>
    <script src="js/simplemenu.js"></script>
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
    <script>
    /*function changemethod(val)
{
	if(val=="upsshhip")
	{
		document.getElementById('upsmethod').style.display="block";
		document.getElementById('uspsmethod').style.display="none";
		document.getElementById('shipmethods').value=val;
		document.getElementById('usps_drop').innerHTML="";
		if(document.getElementById('zipcode').value!=""){UpsDropDown();}
	}
	else if(val=="uspsshhip")
	{
		document.getElementById('upsmethod').style.display="none";
		document.getElementById('uspsmethod').style.display="block";
		document.getElementById('shipmethods').value=val;
		document.getElementById('ups_drop').innerHTML="";
		if(document.getElementById('zipcode').value!=""){UspsDropDown();}
	}
	else
	{
		if(document.getElementById('zipcode').value!="")
		{
			document.getElementById('shippmethss').style.display="block";
			document.getElementById('upsmethod').style.display="block";
			document.getElementById('uspsmethod').style.display="none";
			document.getElementById('usps_drop').innerHTML="";
			if(document.getElementById('zipcode').value!=""){UpsDropDown();}
		}
		else
		{
			document.getElementById('shippmethss').style.display="none";
			document.getElementById('upsmethod').style.display="none";
			document.getElementById('uspsmethod').style.display="none";
			document.getElementById('usps_drop').innerHTML="";
			document.getElementById('ups_drop').innerHTML="";
			var Fintot=<?=number_format($_SESSION['total'],2, '.', '');?>;
			document.getElementById('FinalTotal').innerHTML="$"+Fintot.toFixed(2);
			document.getElementById('shipchrg').innerHTML="$0.00";
			document.getElementById('shipmeth').value="";
			document.getElementById('shipcharge').value="0.00";
			document.getElementById('shipmethods').value="";
		}
	}
}*/
    /*function UspsDropDown()
    {
    	document.getElementById("usps_drop").innerHTML = "<img src='ajaxloader.gif'>";
    	var zipcode=document.getElementById('zipcode').value;
    	var httpSizee = false;
    	if(navigator.appName == "Microsoft Internet Explorer") { httpSizee = new ActiveXObject("Microsoft.XMLHTTP");} else { httpSizee = new XMLHttpRequest();}
    	httpSizee.abort();
    	httpSizee.open("GET","ajax_usps.php?zipcode="+zipcode+"&STotWeight="+<?=$TotWeight;?>+"&length="+<?=$length;?>+"&width="+<?=$width;?>+"&height="+<?=$height;?>, true);
    	httpSizee.onreadystatechange=function()
    	{
    		  if(httpSizee.readyState == 4)
    		  {
    			  	aa=httpSizee.responseText; //alert(aa);
    				document.getElementById('usps_drop').innerHTML=httpSizee.responseText;
    				Getusps_price();
    				return false;
    		  } 
    	}
    	httpSizee.send(null);
    }*/
    function UpsDropDown() {
        document.getElementById("ups_drop").innerHTML = "<img src='ajaxloader.gif'>";
        var zipcode = document.getElementById('zipcode').value;
        var httpSizee = false;
        if (navigator.appName == "Microsoft Internet Explorer") {
            httpSizee = new ActiveXObject("Microsoft.XMLHTTP");
        } else {
            httpSizee = new XMLHttpRequest();
        }
        httpSizee.abort();
        httpSizee.open("GET", "ajax_ups.php?zipcode=" + zipcode + "&STotWeight=" + <?=$TotWeight;?> + "&length=" +
            <?=$length;?> + "&width=" + <?=$width;?> + "&height=" + <?=$height;?>, true);
        httpSizee.onreadystatechange = function() {
            if (httpSizee.readyState == 4) {
                aa = httpSizee.responseText; //alert(aa);
                document.getElementById('ups_drop').innerHTML = httpSizee.responseText;
                Getusps_price();
                return false;
            }
        }
        httpSizee.send(null);
    }

    function Getusps_price() {
        var Fina = 0;
        var UspsPrice = 0;
        if (document.getElementById('shipping').value != '') {
            bb = document.getElementById('shipping').value.split("$");
            var UspsPrice = bb[1];
            var Ftot = <?=number_format($_SESSION['total'],2, '.', '');?>;
            var Fina = parseFloat(Ftot) + parseFloat(UspsPrice);
            //document.getElementById('ftot').innerHTML=Fina.toFixed(2);
            if (bb[0] != '') {
                document.getElementById('shipmeth').value = bb[0];
            } else {
                document.getElementById('shipmeth').value = "";
            }
            document.getElementById('shippmethss').style.display = "block";
        } else {
            document.getElementById('shippmethss').style.display = "none";
        }


        if (UspsPrice != '') {
            document.getElementById('shipcharge').value = UspsPrice;
        } else {
            document.getElementById('shipcharge').value = "0.00";
        }
        if (UspsPrice != '') {
            document.getElementById('shipchrg').innerHTML = "$" + UspsPrice;
        } else {
            document.getElementById('shipchrg').innerHTML = "$0.00";
        }
        if (Fina != '') {
            document.getElementById('FinalTotal').innerHTML = "$" + Fina.toFixed(2);
        } else {
            var Fintot = <?=number_format($_SESSION['total'],2, '.', '');?>;
            document.getElementById('FinalTotal').innerHTML = "$" + Fintot.toFixed(2);
        }

    }
    </script>
    <script type="text/javascript">
    function session_price() {
        var shipmeth = document.getElementById('shipmeth').value;
        var shipcharge = document.getElementById('shipcharge').value;
        var shippmethod = document.getElementById('shipmethods').value;
        var httpSizee = false;
        if (navigator.appName == "Microsoft Internet Explorer") {
            httpSizee = new ActiveXObject("Microsoft.XMLHTTP");
        } else {
            httpSizee = new XMLHttpRequest();
        }
        httpSizee.abort();
        httpSizee.open("GET", "pass_value.php?shipmeth=" + shipmeth + "&shipcharge=" + shipcharge + "&shippmethod=" +
            shippmethod, true);
        httpSizee.onreadystatechange = function() {
            if (httpSizee.readyState == 4) {
                //aa=httpSizee.responseText; alert(aa);
                //document.getElementById('usps_drop').innerHTML=httpSizee.responseText;
                document.getElementById('shippmethss').style.display = "block";
                return false;
            }
        }
        httpSizee.send(null);
    }
    </script>
    <link rel="stylesheet" type="text/css" href="css/font-awesome.css" />
    <? include("googleanalytic.php");?>
    <? include("dbclose.php");?>

</html>