<? 
include("connect.php");
$TopTab="Contact";


if($_POST['HidRegUser']=="1")
{
	
	if(trim($_POST['name'])!='' && trim($_POST['email'])!='' && trim($_POST['message'])!='')
	{
		$get="SELECT * FROM flagging_contactus WHERE name='" . addslashes($_POST['name']) . "' AND email='" . addslashes($_POST['email']) . "' AND message='" . addslashes($_POST['message']) . "'";
		$getRs=mysql_query($get);
		$Totget=mysql_affected_rows();
		if($Totget<=0)
		{
			$InsertUserQry = "INSERT INTO contactus set 
			name='" . addslashes($_POST['name']) . "',
			email='" . addslashes($_POST['email']) . "',
			message='" . addslashes($_POST['message']) . "',
			productsku='" . addslashes($_REQUEST['sku']) . "',
			regdate=now()";
			mysql_query($InsertUserQry);
			
			$subject1="Contact us request at flagginmart.com";
			$mailcontent1="<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"1000\">
							<tbody>
								<tr>
									<td align=\"left\" valign=\"top\">
										<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
											<tbody>
												<tr>
													<td align=\"left\" valign=\"middle\">
														<img src=\"https://www.flagginmart.com/logo.jpg\" /></td>
												</tr>
												<tr>
													<td align=\"left\" bgcolor=\"#FFFFFF\"  valign=\"top\">
														<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
															<tbody>
																<tr>
																	<td>
																		&nbsp;</td>
																</tr>
																<tr>
																	<td align=\"left\" style=\"line-height:22px;\" valign=\"top\">
																		Contact us request has been submitted with the below details:<br /><br />
																		Name: ".$_POST['name']."<br />
																		Email: ".$_POST['email']."<br />
																		SKU: ".$_REQUEST['sku']."<br />
																		Message: ".nl2br($_POST['message'])."<br /><br />
																		URL: <a href='".Get_ProductUrl($_REQUEST['pid'])."'>".Get_ProductUrl($_REQUEST['pid'])."</a><br /></td>
																</tr>
																<tr>
																	<td>
																		&nbsp;</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>";
			//echo $subject1."<br>";echo $mailcontent1;exit;
			if($_SERVER['HTTP_HOST']!="yogs")
			{
				$headers  = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
				$headers .= "From: $SITE_TITLE <$ADMIN_MAIL>" . "\r\n";	
				mail("parth.rae@gmail.com", $subject1, $mailcontent1, $headers);
				//mail("rpaxis2@gmail.com", $subject1, $mailcontent1, $headers);
			}
			$message="Thank You! Your request has been submitted successfully!";
		}
	}
	else
	{
		$message="Please fill form.";
	}
}

if($meta_kwords==''){$meta_kwords=$pgtitle.", ".$METAKEYWORD;}
if($meta_desc==''){$meta_desc=$pgtitle.", ".$METADESCRIPTION;}
?><!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title><?=$pgtitle;?></title><meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1" name="viewport" />
<meta name="description" content="<?=$Home_name;?><?=$meta_desc;?>" />
<meta name="keywords" content="<?=$meta_kwords;?>" />
<? include("favicon.php");?>
<link type="text/css" href="css/style.css" rel="stylesheet">
<link type="text/css" href="css/responsive.css" rel="stylesheet">
<script src="js/jquery.js"></script>
<script src="js/jquery_custom.js"></script>
<script>$(document).ready(function(){$(".grid").click(function(){$("#grid-main").fadeToggle("slow");});});</script>
<? include("headermore.php");?>
<style>@media only screen and (max-width:640px){.left-panel{display:none;}}
</style>
</head>
<body style="margin:15px;background:none;">
<section id="middlewrapper" style="background:none"><div class="wrapper"><div class="tab-section" style="padding-top:0px;padding-bottom:0px;"><div class="content"><div class="about-deta"><div class="right-content"><div class="login-deta" style="padding:0px;">
			  <div class="registered_customers-deta" style="float:left;width:100%;">
				<h1 class="login-new-head" style="padding-left:0px;padding-top:0px;">Contact Us</h1><form name="FrmRegister" enctype="multipart/form-data" method="post" >
				  <div class="login-frm-deta" style="padding-left:0px;"><? if($message!=""){?><p style="color:#FF0000"><?=$message;?></p><? } ?>
					<div class="row"><label> <b>Name</b><span>*</span> </label><div class="col"><input id="name" class="logintextbox" name="name" type="text" style="width:100%;max-width:320px;"></div></div>
					<div class="row"><label> <b>Email Address</b><span>*</span> </label><div class="col"><input id="email" class="logintextbox" name="email" type="text" style="width:100%;max-width:320px;"></div></div>
					<div class="row"><label> <b>Message</b><span>*</span> </label><div class="col"><textarea id="message" class="logintextbox" name="message" style="width:100%;max-width:320px;height:120px" ></textarea></div></div>
				  </div>
				  <div class="login_btn-deta" style="padding-left:0px;margin-top:0px;padding-top:0px;border:0px;"><a style="width:100px;background: #ed1c24;color: #ffffff;font-size: 15px;padding: 10px 5px;font-weight: bold;border-radius: 6px;text-align: center;margin-top: 12px;" class="login-btn" href="#" onClick="Chkregister();return false;">Submit</a> <a style="width:100px;background: #666;color: #ffffff;font-size: 15px;padding: 10px 5px;font-weight: bold;border-radius: 6px;text-align: center;margin-top: 12px;" class="login-btn" onClick="parent.Shadowbox.close();return false;"  href="javascript:void(0)">Close</a><div class="clear"></div></div><input type="hidden" name="HidRegUser" id="HidRegUser" value="0" />
				</form>
			  </div>
			  <div class="clear"></div>
			</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section><link type="text/css" href="css/font-awesome.css" rel="stylesheet">
<script>function Chkregister(){form=document.FrmRegister;
	if(form.name.value==""){alert("Please enter name.");form.name.focus();return false;}
	if(form.email.value==""){alert("Please enter email address.");form.email.focus();return false;}
	else if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(form.email.value))){alert("Please enter a proper email address.");form.email.focus();return false;}
	if(form.message.value==""){alert("Please enter message.");form.message.focus();return false;}
	form.HidRegUser.value=1; form.submit();return true;	
}
function trim(stringToTrim){return stringToTrim.replace(/^\s+|\s+$/g,"");}
</script>
<? include("googleanalytic.php");include("dbclose.php");?></body></html>