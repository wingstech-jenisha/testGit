<?php session_start();
include('connect.php');
if ($_REQUEST["Type"] == "AddToWatchListSEAERCH")
{
	function validate($vehicleid, $userid)
	{
		if ($userid == "")
		{
			return "NotLoggedin";
		}
		else
		{
			$SE = "SELECT vid, userid FROM watchinglist WHERE vid='" . $vehicleid . "' AND userid='" . $userid . "'";
			$SERs = mysql_query($SE);
			$TOT = mysql_num_rows($SERs);
			if ($TOT > 0)
			{
				return "Already added";
			}
			else
			{
				$InsertNewsletterQry = "INSERT INTO watchinglist SET vid='" . $vehicleid . "', userid='" . $userid . "'";
				$InsertNewsletterQryRs = mysql_query($InsertNewsletterQry);
				return "Added";
			}
		}
	}
	$vehicleid = trim(($_REQUEST['vehicleid']));
	$userid = $_SESSION["UsErId"];
	echo validate($vehicleid, $userid);
}
else if($_REQUEST["Type"]=="AddtoCartAjax")
{
	function validate($itemid,$price,$quantity)
	{	
		$drp_1=$_GET['colors'];
		$drp_2=$_GET['sizes'];
		$drp_3=$_GET['types'];
		/*if($drp_1!=''){$drp_1=str_replace("PPPOPPP","&",$drp_1);$drp_1=str_replace("OOOPOOO","#",$drp_1);$drp_1=str_replace("OXXXOXXXO",'"',$drp_1);$drp_1=str_replace("OXXXOOXXXO","'",$drp_1);$drp_1=str_replace("OXXXOOOXXXO","�",$drp_1);}
		if($drp_2!=''){$drp_2=str_replace("PPPOPPP","&",$drp_2);$drp_2=str_replace("OOOPOOO","#",$drp_2);$drp_2=str_replace("OXXXOXXXO",'"',$drp_2);$drp_2=str_replace("OXXXOOXXXO","'",$drp_2);$drp_2=str_replace("OXXXOOOXXXO","�",$drp_2);}
		if($drp_3!=''){$drp_3=str_replace("PPPOPPP","&",$drp_3);$drp_3=str_replace("OOOPOOO","#",$drp_3);$drp_3=str_replace("OXXXOXXXO",'"',$drp_3);$drp_3=str_replace("OXXXOOXXXO","'",$drp_3);$drp_3=str_replace("OXXXOOOXXXO","�",$drp_3);}*/
		
	 	GTG_add_to_cart($itemid,$quantity,$drp_1,$drp_2,$drp_3,$price,$Mode,$_REQUEST['letters'],$_REQUEST['asigns'],"N",$_REQUEST['giftmsg']);
		$righttotcartitem = isset($_SESSION['P']) ? $_SESSION['P'] : 0; 
		$SESSION_itemid = isset($_SESSION['P']) ? $_SESSION['P'] : 0;
		$SESSION_quantity = isset($_SESSION['Q']) ? $_SESSION['Q'] : 0;
		$p_totXCV = isset($_SESSION['P']) ? $_SESSION['P'] : 0;
		$q_totXCV = isset($_SESSION['Q']) ? $_SESSION['Q'] : 0;
		if($SESSION_itemid > 0)
		{
			for($ip_tot=0;$ip_tot<count($p_totXCV);$ip_tot++)
			{
				$totalitemsXX=$totalitemsXX+$q_totXCV[$ip_tot];
			}
		}
		$Data.=($totalitemsXX);
		return 	$Data;		
	}
	echo validate(trim($_REQUEST['itemid']),trim($_REQUEST['price']),trim($_REQUEST['quantity']));
}
else if($_REQUEST["Type"]=="DisplayCartPopup")
{
	function validate($itemSKU)
	{	
	 	global $SITE_URL;global $SECURE_URL;global $IMAGEPATHH;
		$righttotcartitem = isset($_SESSION['P']) ? $_SESSION['P'] : 0; 
		$SESSION_itemid = isset($_SESSION['P']) ? $_SESSION['P'] : 0;
		$SESSION_quantity = isset($_SESSION['Q']) ? $_SESSION['Q'] : 0;
		
		$Data.='<table width="366" border="0" cellspacing="0" cellpadding="0" align="center">
		  <tr>
			<td align="left" style="padding-bottom:2px;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td align="left" width="90" height="38" valign="bottom" class="font14_blk" style="font-size:20px;"><strong>My Cart</strong></td>
					<td align="left" height="38" valign="bottom"><a href="'.$SITE_URL.'/items-in-your-cart" style="font-size:12px;color:#000000;">View full cart details</a></td>
					<td align="right" height="38" valign="bottom"><a href="#" onclick="MM_showHideLayers(\'TopFullCartAjaxBox\',\'\',\'hide\');MM_showHideLayers(\'TopFullCartAjaxBox_div\',\'\',\'hide\');return false;" style="background:#9B1964;border-radius:5px;color:#FFF;font-weight:bold;cursor:pointer;border:none;padding:5px 12px;">CLOSE</a></td>
				  </tr>
				</table>
			</td>
		  </tr>
		  <tr>
			<td align="left" ><hr/></td>
		  </tr>
		  <tr>
			<td align="left" valign="top">
				<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td style="height:6px;" colspan="2"></td></tr>';
				if($righttotcartitem>0)
				{
					$rewardpoints=0;
					for($CL=0;$CL<count($SESSION_itemid);$CL++)
					{
						$ProductQry= "select product_title,id,user_defined_sku_id,item_price  from flagging_searplusitems where id='".$SESSION_itemid[$CL]."'";
						$ProductQryRs = mysql_query($ProductQry);
						$ProductQryRow = mysql_fetch_array($ProductQryRs);
						$prod_detail=ucfirst(stripslashes($ProductQryRow['product_title']));
						
						$imgurll="";$pimage="";$Shareimage="";
						
						$PPRiceAA=number_format(($ProductQryRow['item_price']),2,".","");
						
						$totalcost = $PPRiceAA*$SESSION_quantity[$CL];
						$subtotal = $subtotal +  $totalcost;									
						$_SESSION['total'] = $subtotal;
						
						$Data.='<tr>
						<td width="60" align="left" valign="top">';
						if($imgurll!="")
						{
							$Data.='<img src="'.$imgurll.'" width="50" border="1"  /></td>';
						}
						else
						{
							$Data.='<img src="images/noimage.gif" width="50" height="50" border="0" /></td>';
						}	
						$Data.='<td align="left" valign="top">
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
									  <tr>
										<td align="left" class="font12_blk" ><strong>'.$prod_detail.'</strong></td>
									  </tr>
									  <tr>
										<td align="left" class="font12_blk" >Price: $<strong>'.$PPRiceAA.'</strong></td>
									  </tr>
									  <tr>
										<td align="left" class="font12_blk">Quantity: <strong>'.$SESSION_quantity[$CL].'</strong> </td>
									  </tr>
									</table>
								</td>
						</tr>
						<tr><td style="height:10px;" colspan="2"></td></tr>
						';
					  }
					}
				$Data.='</table>
			</td>
		  </tr>
		  <tr><td style="height:10px;"></td></tr>
		  <tr>
			<td valign="middle" align="left" height="72">
				<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center" >
				  <tr>
					<td align="right"><strong>Sub Total: $'.int_to_Decimal($_SESSION['total']).'</strong>&nbsp;</td>
					</tr>
				  <tr>
					<td colspan="3" align="left" ><hr/></td>
				  </tr>
				</table>
			</td>
		  </tr>
		  
		  <tr>
			<td align="right"><a href="'.$SITE_URL.'/" style="background:#9B1964;border-radius:5px;color:#FFF;font-weight:700;cursor:pointer;border:none;padding:5px 12px;">Continue Shopping</a>&nbsp;&nbsp;&nbsp;<a href="'.$SITE_URL.'/items-in-your-cart" style="background:#9B1964;border-radius:5px;color:#FFF;font-weight:700;cursor:pointer;border:none;padding:5px 12px;">View Cart</a>&nbsp;&nbsp;&nbsp;<a href="'.$SECURE_URL.'/checkout.php" style="background:#9B1964;border-radius:5px;color:#FFF;font-weight:700;cursor:pointer;border:none;padding:5px 12px;">Checkout</a></td>
		  </tr>
		  <tr>
			<td align="left">&nbsp;</td>
		  </tr>';
		  $Data.='<tr><td align="left">&nbsp;</td></tr></table>';
		return 	$Data;		
	}
	echo validate("test");
}
?>