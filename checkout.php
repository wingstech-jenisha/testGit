<? include("connect.php");
$p = isset($_SESSION['P']) ? $_SESSION['P'] : 0;
//echo $_SESSION['STotWeight'];
if($p > 0)
{}
else
{
	echo "<script>window.location.href='".$SITE_URL."/';</script>";
}
if($_SESSION['UsErId']==""){if($_SESSION['CHECKOUT_UsErId']==""){echo "<script>window.location.href='".$SECURE_URL."/checkout_guest.php';</script>";} } 
$montharr=array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");

//Save Info only
if($_POST["hidchkout"]=="1")
{
	//CC INFO
	$_SESSION["SESS_PayTyp"]=$_POST['paytype'];
	
	$_SESSION['total']=($_POST['FinalTotal']);
	
	$_SESSION["CHK_PMT_CCTYPE"]=($_POST['cardtype']);
	$_SESSION["CHK_PMT_CCNAME"]=($_POST['ccname']);
	$_SESSION["CHK_PMT_CCNO"]=($_POST['cardno']);
	$_SESSION["CHK_PMT_CCCCV"]=($_POST['securitycode']);
	$_SESSION["CHK_PMT_CCMONTH"]=($_POST['expmonth']);
	$_SESSION["CHK_PMT_CCTEAR"]=($_POST['expyear']);	
	$E_cctype=ENCRYPT_DECRYPT(($_POST['cardtype']));
	$E_cardno=ENCRYPT_DECRYPT(($_POST['cardno']));
	$E_securitycode=ENCRYPT_DECRYPT(($_POST['securitycode']));
	$E_expmonth=ENCRYPT_DECRYPT(($_POST['expmonth']));
	$E_expyear=ENCRYPT_DECRYPT(($_POST['expyear']));
	
	$_SESSION['SESS_ordernotes']=($_POST['comments']);	
	
	//Shipping & Billing
	$_SESSION["SESS_sfirstname"]=($_POST['s_fname']);
	$_SESSION["SESS_slastname"]=($_POST['s_lname']);
	$_SESSION["SESS_scompany"]=($_POST['s_company']);
	$_SESSION["SESS_bcompany"]=($_POST['company']);
	$_SESSION["SESS_saddress1"]=($_POST['s_address1']);
	$_SESSION["SESS_saddress2"]=($_POST['s_address2']);
	$_SESSION["SESS_scity"]=($_POST['s_city']);
	$_SESSION["SESS_scountry"]=($_POST['s_country']);
	$_SESSION["SESS_sstate"]=($_POST['s_state']);
	$_SESSION["SESS_spostal"]=($_POST['s_zipcode']);
	$_SESSION["SESS_sdayphone"]=($_POST['s_phone']);
	$_SESSION["SESS_sfax"]=($_POST['s_fax']);
	$_SESSION["SESS_email"]=($_POST['email']);
	$_SESSION["SESS_bfirstname"]=($_POST['fname']);
	$_SESSION["SESS_blastname"]=($_POST['lname']);
	$_SESSION["SESS_baddress1"]=($_POST['address1']);
	$_SESSION["SESS_baddress2"]=($_POST['address2']);
	$_SESSION["SESS_bcity"]=($_POST['city']);
	$_SESSION["SESS_bcountry"]=($_POST['country']);
	$_SESSION["SESS_bstate"]=($_POST['state']);
	$_SESSION["SESS_bpostal"]=($_POST['zipcode']);
	$_SESSION["SESS_bdayphone"]=($_POST['phone']);
	$_SESSION["SESS_bfax"]=($_POST['fax']);
	$_SESSION["SESS_email_ord"]="Y";
	$_SESSION["SESS_SAMEBILL"]=($_POST['samebill']);
	
	//Shipping method
	$_SESSION['shipmethod']=trim($_POST['shipmeth']);//$_POST['shipmeth'];
	$_SESSION['shippingcost']=trim($_POST['shipchrg']);
	echo "<script>window.location.href='".$SECURE_URL."/review_order.php';</script>";
}
if($_SESSION['CHK_PMT_CCTYPE']!="")
{
	$cardtype=$_SESSION['CHK_PMT_CCTYPE'];
	$ccname=$_SESSION['CHK_PMT_CCNAME'];
	$cardno=$_SESSION['CHK_PMT_CCNO'];
	$securitycode=$_SESSION['CHK_PMT_CCCCV'];
	$expmonth=$_SESSION['CHK_PMT_CCMONTH'];
	$expyear=$_SESSION['CHK_PMT_CCTEAR'];
}
$_SESSION['tax']=0;
if($_SESSION["SESS_bfirstname"]!="")
{
	$s_fname=stripslashes($_SESSION['SESS_sfirstname']);
	$s_lname=stripslashes($_SESSION['SESS_slastname']);
	$s_company=stripslashes($_SESSION['SESS_scompany']);
	$company=stripslashes($_SESSION['SESS_bcompany']);
	$s_address1=stripslashes($_SESSION['SESS_saddress1']);
	$s_address2=stripslashes($_SESSION['SESS_saddress2']);
	$s_city=stripslashes($_SESSION['SESS_scity']);
	$s_country=stripslashes($_SESSION['SESS_scountry']);
	$s_state=stripslashes($_SESSION['SESS_sstate']);
	$s_zipcode=stripslashes($_SESSION['SESS_spostal']);
	$s_phone=stripslashes($_SESSION['SESS_sdayphone']);
	$s_fax=stripslashes($_SESSION['SESS_sfax']);
	$email=stripslashes($_SESSION['SESS_email']);
	$fname=stripslashes($_SESSION['SESS_bfirstname']);
	$lname=stripslashes($_SESSION['SESS_blastname']);
	$address1=stripslashes($_SESSION['SESS_baddress1']);
	$address2=stripslashes($_SESSION['SESS_baddress2']);
	$city=stripslashes($_SESSION['SESS_bcity']);
	$country=stripslashes($_SESSION['SESS_bcountry']);
	$state=stripslashes($_SESSION['SESS_bstate']);
	$zipcode=stripslashes($_SESSION['SESS_bpostal']);
	$phone=stripslashes($_SESSION['SESS_bdayphone']);
	$fax=stripslashes($_SESSION['SESS_bfax']);
	
}
else
{
	$_SESSION["SESS_email_ord"]="Y";
	if($_SESSION['UsErId']!="")
	{
		$UserQry="SELECT * FROM flagging_users WHERE id='".$_SESSION['UsErId']."'";
		$UserQryRs=mysql_query($UserQry);
		if(mysql_affected_rows()>0)
		{
			$omrowB=mysql_fetch_array($UserQryRs);
			$email=stripslashes($omrowB['email']);
			$s_fname=stripslashes($omrowB['firstname']);
			if($s_fname!="")
			{
				$sdfs=explode(" ",$s_fname);
				$s_fname=trim($sdfs[0]); $s_lname=trim($sdfs[1]);
			}
			$s_company=stripslashes($omrowB['company']);
			$s_address1=stripslashes($omrowB['address1']);
			$s_address2=stripslashes($omrowB['address2']);
			$s_city=stripslashes($omrowB['city']);
			$s_country=stripslashes($omrowB['country']);
			$s_state=stripslashes($omrowB['state']);
			$s_zipcode=stripslashes($omrowB['zip']);
			$s_phone=stripslashes($omrowB['hometel']);
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Checkout | <?=$SITE_TITLE;?></title>
    <meta name="description" content="Checkout, <?=$METADESCRIPTION;?>" />
    <meta name="keywords" content="Checkout, <?=$METAKEYWORD;?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1" name="viewport" />
    <? include("favicon.php");?>
    <link rel="stylesheet" href="css/simplemenu.css">
    <link rel="stylesheet" type="text/css" href="css/style.css<?=$CSSLOAD;?>" />
    <link rel="stylesheet" type="text/css" href="css/responsive.css<?=$CSSLOAD;?>" />
    <? include("headermore.php");?>
</head>

<body onLoad="UpsDropDown();">
    <? include("top.php");?>
    <section id="middlewrapper">
        <div class="wrapper">
            <div class="category-detail">
                <div class="category-detail-right" style="width:100%;margin-bottom:15px;">
                    <h1>Checkout</h1>
                    <? if($_GET["msg"]!="") { ?>
                    <p style="padding-bottom:10px;"><strong style="color:#FF0000"><?=($_GET["msg"]);?></strong></p>
                    <? } ?>
                    <div class="category-pro-detail">
                        <div class="login-deta">
                            <div class="tab-section">
                                <div class="content">
                                    <div class="checkout-deta">
                                        <form name="frmShipInfo" id="frmShipInfo" method="post"
                                            enctype="multipart/form-data">
                                            <input type="hidden" name="Final_GTotal" id="Final_GTotal"
                                                value="<?=number_format(($_SESSION['total']+$_SESSION['shippingcost']+$_SESSION['tax']-$_SESSION['discount']-$_SESSION['GC_discount']-$_SESSION['SC_discount']),2,'.','');?>" />
                                            <input type="hidden" name="HidSubmitAddUser" id="HidSubmitAddUser"
                                                value="0" /><input type="hidden" name="HidViewcart2"
                                                id="HidViewcart2" /><input type="hidden" name="hidchkout"
                                                id="hidchkout" />
                                            <div class="checkout-left checkoutfrm-deta">
                                                <h6>Shipping Information</h6>
                                                <div class="c_row"><label>First Name<span>*</span></label>
                                                    <div class="c_col"><input name="s_fname" id="s_fname"
                                                            value="<?=$s_fname;?>"
                                                            onKeyUp="vallsds('s_fname',this.value);"
                                                            onBlur="vallsds('s_fname',this.value);" class="c_textbox"
                                                            type="text"></div>
                                                </div>
                                                <div class="c_row"><label>Last Name<span>*</span></label>
                                                    <div class="c_col"><input name="s_lname" id="s_lname"
                                                            value="<?=$s_lname;?>"
                                                            onKeyUp="vallsds('s_lname',this.value);"
                                                            onBlur="vallsds('s_lname',this.value);" class="c_textbox"
                                                            type="text"></div>
                                                </div>
                                                <div class="c_row"><label>Company</label>
                                                    <div class="c_col"><input name="s_company" id="s_company"
                                                            value="<?=$s_company;?>" class="c_textbox" type="text">
                                                    </div>
                                                </div>
                                                <div class="c_row"><label>Country<span>*</span></label>
                                                    <div class="c_col"><select name="s_country" id="s_country"
                                                            class="c_textbox"
                                                            onChange="vallsds('s_country',this.value);">
                                                            <? 
					$rs=mysql_query("select * from country order by display_number asc,country_name ASC");$tot=mysql_affected_rows();for($m=0;$m<$tot;$m++){
					$gr=mysql_fetch_object($rs);
					?>
                                                            <option value="<?=$gr->country_name?>" <?
                                                                if($s_country==$gr->country_name){ echo "selected";}?> >
                                                                <? if($gr->country_name=="USA") { echo "United States"; } else { echo $gr->country_name; } ?>
                                                            </option>
                                                            <? } ?>
                                                        </select></div>
                                                </div>
                                                <div class="c_row"><label>Street Address<span>*</span></label>
                                                    <div class="c_col"><input name="s_address1" id="s_address1"
                                                            value="<?=$s_address1;?>"
                                                            onKeyUp="vallsds('s_address1',this.value);"
                                                            onBlur="vallsds('s_address1',this.value);" class="c_textbox"
                                                            type="text"></div>
                                                </div>
                                                <div class="c_row"><label>Apt or Suit Number</label>
                                                    <div class="c_col"><input name="s_address2" id="s_address2"
                                                            value="<?=$s_address2;?>" class="c_textbox" type="text">
                                                    </div>
                                                </div>
                                                <div class="c_row"><label>City<span>*</span></label>
                                                    <div class="c_col"><input name="s_city" id="s_city"
                                                            value="<?=$s_city;?>"
                                                            onKeyUp="vallsds('s_city',this.value);"
                                                            onBlur="vallsds('s_city',this.value);" class="c_textbox"
                                                            type="text"></div>
                                                </div>
                                                <div class="c_row"><label>State<span>*</span></label>
                                                    <div class="c_col"><input name="s_state" id="s_state"
                                                            value="<?=$s_state;?>"
                                                            onKeyUp="vallsds('s_state',this.value);"
                                                            onBlur="vallsds('s_state',this.value);" class="c_textbox"
                                                            type="text"></div>
                                                </div>
                                                <div class="c_row"><label>Zip/Postal Code<span>*</span></label>
                                                    <div class="c_col"><input name="s_zipcode" id="s_zipcode"
                                                            value="<?=$s_zipcode;?>"
                                                            onKeyUp="vallsds('s_zipcode',this.value);UpsDropDown();"
                                                            onBlur="vallsds('s_zipcode',this.value);UpsDropDown();"
                                                            class="c_textbox" type="text" maxlength="9"></div>
                                                </div>
                                                <div class="c_row"><label>Phone<span>*</span></label>
                                                    <div class="c_col"><input name="s_phone" id="s_phone"
                                                            value="<?=$s_phone;?>"
                                                            onKeyUp="vallsds('s_phone',this.value);"
                                                            onBlur="vallsds('s_phone',this.value);" type="text"
                                                            maxlength="15" class="c_textbox"></div>
                                                </div>
                                                <div class="c_row c_row_fullwidth"><label>Email Receipt
                                                        to<span>*</span></label>
                                                    <div class="c_col"><input name="email" id="email"
                                                            value="<?=$email;?>" onkeyup="vallsds('email',this.value);"
                                                            onBlur="vallsds('email',this.value);" class="c_textbox"
                                                            type="text"></div>
                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="checkout-right checkoutfrm-deta">
                                                <h6>Billing Information <span><input value="Y" name="samebill"
                                                            id="samebill1" type="checkbox" onClick="chk_ship();">
                                                        Same as Shipping</span> </h6>
                                                <div class="c_row"><label>First Name<span>*</span></label>
                                                    <div class="c_col"><input name="fname" id="fname"
                                                            value="<?=$fname;?>" onKeyUp="vallsds('fname',this.value);"
                                                            onBlur="vallsds('fname',this.value);" class="c_textbox"
                                                            type="text"></div>
                                                </div>
                                                <div class="c_row"><label>Last Name<span>*</span></label>
                                                    <div class="c_col"><input name="lname" id="lname"
                                                            value="<?=$lname;?>" onKeyUp="vallsds('lname',this.value);"
                                                            onBlur="vallsds('lname',this.value);" class="c_textbox"
                                                            type="text"></div>
                                                </div>
                                                <div class="c_row"><label>Company</label>
                                                    <div class="c_col"><input name="company" id="company"
                                                            value="<?=$company;?>" class="c_textbox" type="text"></div>
                                                </div>
                                                <div class="c_row"><label>Country<span>*</span></label>
                                                    <div class="c_col"><select name="country" id="country"
                                                            class="c_textbox" onBlur="vallsds('country',this.value);">
                                                            <? 
					$rs=mysql_query("select * from country order by display_number asc,country_name ASC");
					$tot=mysql_affected_rows();
					for($m=0;$m<$tot;$m++)
					{
					$gr=mysql_fetch_object($rs);
					?>
                                                            <option value="<?=$gr->country_name?>" <? if($country==$gr->
                                                                country_name){ echo "selected";}?> >
                                                                <? if($gr->country_name=="USA") { echo "United States"; } else { echo $gr->country_name; } ?>
                                                            </option>
                                                            <? } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="c_row"><label>Street Address<span>*</span></label>
                                                    <div class="c_col"><input name="address1" id="address1"
                                                            value="<?=$address1;?>"
                                                            onKeyUp="vallsds('address1',this.value);"
                                                            onBlur="vallsds('address1',this.value);" class="c_textbox"
                                                            type="text"></div>
                                                </div>
                                                <div class="c_row"><label>Apt or Suit Number</label>
                                                    <div class="c_col"><input name="address2" id="address2"
                                                            value="<?=$address2;?>" class="c_textbox" type="text"></div>
                                                </div>
                                                <div class="c_row"><label>City<span>*</span></label>
                                                    <div class="c_col"><input name="city" id="city" value="<?=$city;?>"
                                                            onKeyUp="vallsds('city',this.value);"
                                                            onBlur="vallsds('city',this.value);" class="c_textbox"
                                                            type="text"></div>
                                                </div>
                                                <div class="c_row"><label>State<span>*</span></label>
                                                    <div class="c_col"><input name="state" id="state"
                                                            value="<?=$state;?>" onKeyUp="vallsds('state',this.value);"
                                                            onBlur="vallsds('state',this.value);" class="c_textbox"
                                                            type="text"></div>
                                                </div>
                                                <div class="c_row"><label>Zip/Postal Code<span>*</span></label>
                                                    <div class="c_col"><input name="zipcode" id="zipcode"
                                                            value="<?=$zipcode;?>"
                                                            onKeyUp="vallsds('zipcode',this.value);"
                                                            onBlur="vallsds('zipcode',this.value);" class="c_textbox"
                                                            type="text" maxlength="9"></div>
                                                </div>
                                                <div class="c_row"><label>Phone<span>*</span></label>
                                                    <div class="c_col"><input name="phone" id="phone"
                                                            value="<?=$phone;?>" onKeyUp="vallsds('phone',this.value);"
                                                            onBlur="vallsds('phone',this.value);" class="c_textbox"
                                                            maxlength="15" type="text"></div>
                                                </div>
                                            </div>
                                            <div class="clear"></div>
                                            <div class="checkout-left checkoutfrm-deta">
                                                <h6>Estimated Shipping Cost</h6>
                                                <div class="c_row c_row_fullwidth"><label>Shipping
                                                        Cost<span>*</span></label>
                                                    <div class="c_col">
                                                        <span id="upsmethod"> <span id="ups_drop"></span></span>
                                                    </div>
                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="clear"></div>
                                            <div class="checkout-left checkoutfrm-deta">
                                                <h6>Payment Information</h6>
                                                <div class="c_row c_row_fullwidth Credit-deta"><label> Payment Method*
                                                        <input id="paytype1" checked="checked" name="paytype"
                                                            value="Credit Card" type="radio"><img src="images/visa.png"
                                                            title="Visa Card Payment"> <img src="images/mastercard.png"
                                                            title="Master Card Payment"> <img
                                                            src="images/american_express.png"
                                                            title="American Express Card Payment"> <img
                                                            src="images/discover.png" title="Discover Card Payment">
                                                    </label></div>
                                                <div class="c_row"><label>Credit Card Type<span>*</span></label>
                                                    <div class="c_col">
                                                        <select name="cardtype" id="cardtype" class="c_textbox"
                                                            onChange="vallsds2('cardtype',this.value);">
                                                            <option value="">Please Select</option>
                                                            <? 
					$rs=mysql_query("select * from cardtype");
					if(mysql_affected_rows()>0)
					{
					while($gr=mysql_fetch_array($rs))
					{
					?>
                                                            <option
                                                                value="<?=htmlentities(stripslashes($gr['cardtype'])); ?>"
                                                                <? if($gr['cardtype']==$cardtype) echo 'selected' ; ?>
                                                                ><?=htmlentities(stripslashes($gr['cardtype'])); ?>
                                                            </option>
                                                            <? } } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="c_row"><label>Credit Card Number<span>*</span></label>
                                                    <div class="c_col"><input name="cardno" value="<?=$cardno;?>"
                                                            autocomplete="off" maxlength="16" id="cardno"
                                                            onKeyUp="fillinbixxxcc(this.value,'cardno');vallsds2('cardno',this.value);"
                                                            onBlur="fillinbixxxcc(this.value,'cardno');vallsds2('cardno',this.value);"
                                                            class="c_textbox" type="text"></div>
                                                </div>
                                                <div class="c_row"><label>Expiration Date<span>*</span></label>
                                                    <div class="c_col"><select id="expmonth" class="c_textbox exp-deta"
                                                            name="expmonth" onChange="vallsds2('expmonth',this.value);">
                                                            <option value="">Month</option>
                                                            <?  foreach($montharr as $k=>$v) { 
					$mvall=$k+1;
					if(strlen($mvall)==1) { $mvall="0".$mvall; }
					?>
                                                            <option value="<?=$mvall;?>" <? if($mvall==$expmonth){
                                                                echo "selected" ;} ?> /><?=$v;?> (<?=$mvall;?>)</option>
                                                            <? } ?>
                                                        </select> <select name="expyear" id="expyear"
                                                            class="c_textbox exp-deta"
                                                            onChange="vallsds2('expyear',this.value);">
                                                            <option value="">Year</option>
                                                            <?
					  $cy=date("Y");
					  for($y=$cy; $y<=($cy+20); $y++)
					  { ?>
                                                            <option value="<?=$y;?>" <? if($y==$expyear) echo 'selected'
                                                                ; ?> /><?=$y;?></option>
                                                            <? } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="c_row"><label>Card Verification Number<span>*</span></label>
                                                    <div class="c_col"><input name="securitycode"
                                                            value="<?=$securitycode;?>" autocomplete="off" maxlength="4"
                                                            onKeyUp="fillinbixxxcc(this.value,'securitycode');vallsds2('securitycode',this.value);"
                                                            onBlur="fillinbixxxcc(this.value,'securitycode');vallsds2('securitycode',this.value);"
                                                            id="securitycode" class="c_textbox securitycodea"
                                                            type="password">
                                                        <a href="#"
                                                            onClick="window.open('ShowInfo.htm','_blank','width=600,height=400,status=on,toolbar=off,location=off,scrollbars=yes'); return false;">What's
                                                            This?</a>
                                                    </div>
                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="checkout-right checkoutfrm-deta">
                                                <h6>&nbsp;</h6>
                                                <div class="c_row c_row_fullwidth" id="TRRMS"><label> <span>*</span>
                                                        <input name="terms" id="terms" value="Y" type="checkbox"> By
                                                        checking this box you acknowledge that you have read and agree
                                                        to our <a href="terms-and-conditions" target="_blank">terms of
                                                            use</a> on all sales, including but not limited to, preorder
                                                        items, sale items and regular priced items. </label>
                                                </div>
                                            </div>
                                            <div class="clear"></div>
                                            <div class="checkout-btn"> <a class="back-btn"
                                                    href="items-in-your-cart">BACK</a> <a class="continue-btn" href="#"
                                                    onClick="Chklogin();return false;">CONTINUE</a> </div>
                                            <? $QryErrMsg_MASTER="Y";
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
                                            <div class="your-order-deta">
                                                <div class="order_deta" style="border-bottom:1px solid #ccd3d4;">
                                                    <div class="O_name"> <b>Your Order</b> </div>
                                                    <div class="O_total"> <b>Total</b> </div>
                                                    <div class="clear"></div>
                                                </div>
                                                <? $INOOO=0;
				for($i=0;$i<count($p);$i++)
				{
					if($p[$i]!=0 && $p[$i]!="")
					{
						$queryMain="select product_title,item_price,id,user_defined_sku_id,main_image_url,shipping_weight_pounds,package_length_inches,package_width_inches,package_height_inches from flagging_searplusitems where id='".$p[$i]."' limit 0,1";
						$result=mysql_query($queryMain);
						$TotCrtItm=mysql_affected_rows();
						if($TotCrtItm<=0)
						{
							$queryMain="select product_title,item_price,id,user_defined_sku_id,main_image_url from searplus where id='".$p[$i]."' limit 0,1";
							$result=mysql_query($queryMain);
							$TotCrtItm=mysql_affected_rows();
						}
						if($TotCrtItm>0)
						{ 
							$row = mysql_fetch_array($result);
							$PPRiceAA=number_format($row['item_price'],2,'.','');
							$PRNMAM_NO=stripslashes($row['user_defined_sku_id']);
							$PRNMAM=stripslashes($row['product_title']);
							$imgurll=stripslashes($row['main_image_url']);
							
							$Weight=stripslashes($row['shipping_weight_pounds']);
							$length=$row['package_length_inches'];
							$width=$row['package_width_inches'];
							$height=$row['package_height_inches'];
							
							$Colornm="";$Flagnm="";$Sizenm="";
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
								$queryPSZ="select * from flagging_product_size where name='".$Color."' and sku like '%".$WPRICE[$i]."%'";
								$resultPSZ= mysql_query($queryPSZ);
								if(mysql_affected_rows()>0)
								{ 
									$rowPSZ=mysql_fetch_array($resultPSZ);
									 $Colornm="<span>".stripslashes($rowPSZ['type'])."</span>: ".stripslashes($rowPSZ['name'])."<br>";
									$PPRiceAA=str_replace(",","",$rowPSZ['price']);
								}
								$Size = str_replace("'", "\'", $drp_2);
								$Size = str_replace('"', '\"', $Size);
								$queryPSZ="select * from flagging_product_size where name='".$Size."' and sku like '%".$WPRICE[$i]."%'";
								$resultPSZ= mysql_query($queryPSZ);
								if(mysql_affected_rows()>0)
								{ 
									$rowPSZ=mysql_fetch_array($resultPSZ);
									$Sizenm="<span>".stripslashes($rowPSZ['type'])."</span>: ".stripslashes($rowPSZ['name'])."<br>";
									$PPRiceAA=str_replace(",","",$rowPSZ['price']);
								}
								
								$Flag = str_replace("'", "\'", $drp_3);
								$Flag = str_replace('"', '\"', $Flag);
								$queryPSZ="select * from flagging_product_size where name='".$Flag."' and sku like '%".$WPRICE[$i]."%'";
								$resultPSZ= mysql_query($queryPSZ);
								if(mysql_affected_rows()>0)
								{ 
									$rowPSZ=mysql_fetch_array($resultPSZ);
									$Flagnm="<span>".stripslashes($rowPSZ['type'])."</span>: ".stripslashes($rowPSZ['name'])."<br>";
									$PPRiceAA=str_replace(",","",$rowPSZ['price']);
								}
							}
					$totalcost = $PPRiceAA*$q[$i]; $INOOO++;
					$TempWeight=($Weight*$q[$i]);
				    $TotWeight=$TotWeight+$TempWeight;
				  ?>
                                                <div class="order_deta" style="padding-top:10px;">
                                                    <div class="O_name"><?=stripslashes($PRNMAM);?><br>
                                                        <? if($Colornm!=''){echo $Colornm;}?>
                                                        <? if($Sizenm!=''){echo $Sizenm;}?>
                                                        <? if($Flagnm!=''){echo $Flagnm;}?><span>SKU#:
                                                            <?=stripslashes($WPRICE[$i]);?></span><br><span>Quantity:
                                                            <?=stripslashes($q[$i]);?></span>
                                                    </div>
                                                    <div class="O_total">
                                                        <b>$<?=number_format($totalcost,2, '.', '');?></b> </div>
                                                    <div class="clear"></div>
                                                </div>
                                                <? } ?>
                                                <? } ?>
                                                <? } ?>
                                                <div class="order_deta">
                                                    <div class="O_name"> <b>Items (<?=$INOOO;?>):</b> </div>
                                                    <div class="O_total">$<span
                                                            id="ctotal"><?=number_format($_SESSION['total'],2, '.', '');?></span>
                                                    </div>
                                                    <div class="clear"></div>
                                                </div>
                                                <div class="order_deta">
                                                    <div class="O_name"> <b>Shipping & Handling:</b> </div>
                                                    <div class="O_total">$<span
                                                            id="stot"><?=number_format($_SESSION['shippingcost'],2, '.', '');?></span>
                                                    </div>
                                                    <div class="clear"></div>
                                                </div>
                                                <div class="order_deta">
                                                    <div class="O_name"> <b>Order Total:</b> </div>
                                                    <div class="O_total"><b>$<span
                                                                id="ftot"><?=number_format($_SESSION['total']+$_SESSION['shippingcost'],2, '.', '');?></span></b>
                                                    </div>
                                                    <div class="clear"></div>
                                                </div>

                                                <input name="shipchrg" id="shipchrg" autocomplete="off"
                                                    value="<?=number_format($_SESSION['shippingcost'],2, '.', '');?>"
                                                    type="hidden">
                                                <input name="FinalTotal" id="FinalTotal" autocomplete="off"
                                                    value="<?=number_format($_SESSION['total']+$_SESSION['shippingcost'],2, '.', '');?>"
                                                    type="hidden">
                                                <input name="shipmeth" id="shipmeth" autocomplete="off" value=""
                                                    type="hidden">
                                            </div>
                                            <? } ?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="clear"></div>
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
    <link rel="stylesheet" type="text/css" href="css/font-awesome.css" />
    <script>
    function chk_ship() {
        form = document.frmShipInfo;
        if (document.getElementById("samebill1").checked == true) {
            form.fname.value = form.s_fname.value;
            form.lname.value = form.s_lname.value;
            form.address1.value = form.s_address1.value;
            form.address2.value = form.s_address2.value;
            form.city.value = form.s_city.value;
            form.state.value = form.s_state.value;
            form.zipcode.value = form.s_zipcode.value;
            form.phone.value = form.s_phone.value;
            form.company.value = form.s_company.value;
            form.country.selectedIndex = form.s_country.selectedIndex;
        } else {
            form.fname.value = "";
            form.lname.value = "";
            form.address1.value = "";
            form.address2.value = "";
            form.city.value = "";
            form.zipcode.value = "";
            form.phone.value = "";
            form.company.value = "";
            form.state.value = "";
            form.country.selectedIndex = 0;
        }
        vallsds('fname', form.fname.value);
        vallsds('lname', form.lname.value);
        vallsds('address1', form.address1.value);
        vallsds('city', form.city.value);
        vallsds('zipcode', form.zipcode.value);
        vallsds('phone', form.phone.value);
        vallsds('state', form.state.value);
        vallsds('country', form.country.value);
    }

    function vallsds(adssad, vallla) {
        if (vallla == "") {
            document.getElementById(adssad).style.border = "1px solid #FF0000";
        } else {
            document.getElementById(adssad).style.border = "1px solid #CCCCCC";
        }
    }

    function vallsds2(adssad, vallla) {
        if (vallla == "") {
            document.getElementById(adssad).style.border = "1px solid #FF0000";
        } else {
            document.getElementById(adssad).style.border = "1px solid #CCCCCC";
        }
    }

    function Chklogin() {
        form = document.frmShipInfo;
        document.getElementById("TRRMS").style.border = "0px solid #CCCCCC";
        var Msgg = "N";
        if (form.s_fname.value.split(" ").join("") == "") {
            form.s_fname.style.border = "1px solid #FF0000";
            Msgg = "Y";
        }
        if (form.s_lname.value.split(" ").join("") == "") {
            form.s_lname.style.border = "1px solid #FF0000";
            Msgg = "Y";
        }
        if (form.s_address1.value.split(" ").join("") == "") {
            form.s_address1.style.border = "1px solid #FF0000";
            Msgg = "Y";
        }
        if (form.s_city.value.split(" ").join("") == "") {
            form.s_city.style.border = "1px solid #FF0000";
            Msgg = "Y";
        }
        if (form.s_state.value == "") {
            form.s_state.style.border = "1px solid #FF0000";
            Msgg = "Y";
        }
        if (form.s_zipcode.value.split(" ").join("") == "") {
            form.s_zipcode.style.border = "1px solid #FF0000";
            Msgg = "Y";
        }
        if (form.s_country.value == "") {
            form.s_country.style.border = "1px solid #FF0000";
            Msgg = "Y";
        }
        if (form.s_phone.value == "") {
            form.s_phone.style.border = "1px solid #FF0000";
            Msgg = "Y";
        }
        if (form.fname.value.split(" ").join("") == "") {
            form.fname.style.border = "1px solid #FF0000";
            Msgg = "Y";
        }
        if (form.lname.value.split(" ").join("") == "") {
            form.lname.style.border = "1px solid #FF0000";
            Msgg = "Y";
        }
        if (form.address1.value.split(" ").join("") == "") {
            form.address1.style.border = "1px solid #FF0000";
            Msgg = "Y";
        }
        if (form.city.value.split(" ").join("") == "") {
            form.city.style.border = "1px solid #FF0000";
            Msgg = "Y";
        }
        if (form.state.value == "") {
            form.state.style.border = "1px solid #FF0000";
            Msgg = "Y";
        }
        if (form.zipcode.value.split(" ").join("") == "") {
            form.zipcode.style.border = "1px solid #FF0000";
            Msgg = "Y";
        }
        if (form.country.value == "") {
            form.country.style.border = "1px solid #FF0000";
            Msgg = "Y";
        }
        if (form.phone.value == "") {
            form.phone.style.border = "1px solid #FF0000";
            Msgg = "Y";
        }
        if (form.email.value == "") {
            form.email.style.border = "1px solid #FF0000";
            Msgg = "Y";
        } else if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(form.email.value))) {
            form.email.style.border = "1px solid #FF0000";
            Msgg = "Y";
        }
        if (form.shipping.value == "") {
            form.shipping.style.border = "1px solid #FF0000";
            Msgg = "Y";
        }

        if (document.getElementById("cardtype").value == "") {
            form.cardtype.style.border = "1px solid #FF0000";
            Msgg = "Y";
        }
        if (form.cardno.value.split(" ").join("") == "") {
            form.cardno.style.border = "1px solid #FF0000";
            Msgg = "Y";
        }
        if (form.cardno.value.length < 13 || form.cardno.value.length > 16) {
            form.cardno.style.border = "1px solid #FF0000";
            Msgg = "Y";
        }
        if (form.securitycode.value.split(" ").join("") == "") {
            form.securitycode.style.border = "1px solid #FF0000";
            Msgg = "Y";
        }
        if (form.expmonth.value.split(" ").join("") == "") {
            form.expmonth.style.border = "1px solid #FF0000";
            Msgg = "Y";
        }
        if (form.expyear.value.split(" ").join("") == "") {
            form.expyear.style.border = "1px solid #FF0000";
            Msgg = "Y";
        }
        if (form.terms.checked == false) {
            form.terms.style.border = "1px solid #FF0000";
            Msgg = "Y";
            document.getElementById("TRRMS").style.border = "1px solid #FF0000";
        }
        var MsggGft = "N";
        if (Msgg == "Y" || MsggGft == "Y") {
            alert("Our Customers need to fill the highlighted fields.");
            return false;
        } else {
            form.hidchkout.value = 1;
            form.submit();
            return true;
        }
    }

    function setSelectedIndex(s, v) {
        for (var i = 0; i < s.options.length; i++) {
            if (s.options[i].value == v) {
                s.options[i].selected = true;
                return;
            }
        }
    }

    function shwhidpay(sadasdasd) {
        form = document.frmShipInfo;
        vallsds2('cardtype', form.cardtype.value);
        vallsds2('cardno', form.cardno.value);
        vallsds2('expmonth', form.expmonth.value);
        vallsds2('expyear', form.expyear.value);
        vallsds2('securitycode', form.securitycode.value);
    }
    </script>
    <script>
    /*changemethod(<?=$_SESSION['shippingmethod'];?>);
function changemethod(val)
{
	if(val=="upsshhip")
	{
		document.getElementById('upsmethod').style.display="block";
		document.getElementById('uspsmethod').style.display="none";
		document.getElementById('usps_drop').innerHTML="";
		UpsDropDown();
	}
	else if(val=="uspsshhip")
	{
		document.getElementById('upsmethod').style.display="none";
		document.getElementById('uspsmethod').style.display="block";
		document.getElementById('ups_drop').innerHTML="";
		UspsDropDown();
	}

}*/
    function UpsDropDown() {
        document.getElementById("ups_drop").innerHTML =
            "<select name='shipping' id='shipping' class='c_textbox' disabled='disabled'><option value=''>Loading...</option></select>";
        var zipcode = document.getElementById('s_zipcode').value;
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
                return false;
            }
        }
        httpSizee.send(null);
    }
    /*function UspsDropDown()
    {
    	document.getElementById("usps_drop").innerHTML = "<select name='shipping' id='shipping' class='c_textbox' disabled='disabled'><option value=''>Loading...</option></select>";
    	var zipcode=document.getElementById('s_zipcode').value;
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
    				return false;
    		  } 
    	}
    	httpSizee.send(null);
    }*/
    function Getusps_price() {
        var Fina = 0;
        var UspsPrice = 0;
        if (document.getElementById('shipping').value != '') {
            bb = document.getElementById('shipping').value.split("$");
            var UspsPrice = bb[1];
            var Ftot = <?=number_format($_SESSION['total'],2, '.', '');?>;
            var Fina = parseFloat(Ftot) + parseFloat(UspsPrice);
        }


        document.getElementById('shipmeth').value = bb[0];
        if (UspsPrice != '') {
            document.getElementById('stot').innerHTML = UspsPrice;
        } else {
            document.getElementById('stot').innerHTML = "0.00";
        }
        if (Fina != '') {
            document.getElementById('ftot').innerHTML = Fina.toFixed(2);
        } else {
            var Fintot = <?=number_format($_SESSION['total'],2, '.', '');?>;
            document.getElementById('ftot').innerHTML = Fintot.toFixed(2);
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
            document.getElementById('FinalTotal').innerHTML = Fintot.toFixed(2);
        }
    }
    </script>
    <? include("googleanalytic.php");?>
    <? include("dbclose.php");?>
</body>

</html>