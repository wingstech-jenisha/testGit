<? 
include("connect.php");
$TopTab="Login";$ShWSubMenu="Y";
$mesgg="";
if($_POST['HidRegUser']=="1")
{
	$password = EnCry_Decry($_POST['password']);
	$query1="select id from flagging_users where email='".trim(addslashes(($_POST['email'])))."' AND password='".trim(addslashes(($password)))."'  ";
	$res=mysql_query($query1);
	$tot=mysql_affected_rows();
	if($tot>0)
	{
		$UpdateUserrq=mysql_fetch_object($res);
		$InserId=stripslashes($UpdateUserrq->id);
		
		$_SESSION['UsErId']=$InserId;
		
		//Coocke
		setcookie("UsErId_COKI",$row["id"],time()+(3600*168));
		
		header("location:".$SECURE_URL."/checkout.php");exit;
	}
	else
	{
		$message="Invalid login details. Try again.";
	}
}
if($_POST['HidRegUser2']=="1")
{
	if($_POST['guestreg']=="Register")
	{
		$_SESSION['CHECKOUT_UsErId']="";
		header("location:".$SITE_URL."/register?back=checkout");exit;
	}
	else
	{
		$_SESSION['CHECKOUT_UsErId']="Yes";
		header("location:".$SECURE_URL."/checkout.php");exit;
	}
}
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Login or Create an Account | <?=$SITE_TITLE;?></title>
<meta name="description" content="Login or Create an Account | Flagging Direct" />
<meta name="keywords" content="Login or Create an Account | Flagging Direct" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1" name="viewport" />
<? include("favicon.php");?>
<link rel="stylesheet" href="css/simplemenu.css">
<link rel="stylesheet" type="text/css" href="css/style.css<?=$CSSLOAD;?>" />
<link rel="stylesheet" type="text/css" href="css/responsive.css<?=$CSSLOAD;?>" />
<? include("headermore.php");?></head>
<body>
<? include("top.php");?>
<section id="middlewrapper">
  <div class="wrapper">
    <div class="category-detail">
	  <div class="category-detail-right" style="width:100%">
        <h1>Login or Create an Account</h1>
        <div class="category-pro-detail" >
          <div class="login-deta">
          <div class="registered_customers-deta">
            <h2 class="login-new-head">Registered Customers</h2>
            <form name="FrmRegister" enctype="multipart/form-data" method="post" >
              <div class="login-frm-deta">
                <? if($message!=""){?><p align="left"><strong style="color:#FF0000"><?=$message;?></strong></p><? } ?>
				<p>If you have an account with us, please log in.</p>
                <div class="row"><label> <b>Email Address</b><span>*</span> </label><div class="col"><input id="email" class="logintextbox" name="email" type="text"></div></div>
                <div class="row"><label> <b>Password</b><span>*</span> </label><div class="col"><input id="password" class="logintextbox" name="password" type="password"></div></div>
                <div class="row"> <span style="margin:0;">*</span> Required Fields </div>
              </div>
              <div class="login_btn-deta"> <a class="f_pass" href="forgot-password" title="Forgot Your Password?">Forgot Your Password?</a> <span class="allbutton"> <a style="float:right;margin-top:0px;" href="#" onClick="Chkregister();return false;">Login</a></span>
                <div class="clear"></div>
              </div><input type="hidden" name="HidRegUser" id="HidRegUser" value="0" />
            </form>
          </div>
		  <form name="FrmRegister2" enctype="multipart/form-data" method="post"  >
          <div class="new_customers">
            <h2 class="login-new-head">New Customers</h2>
            <div class="customersdeta-detail">
              <h4>Register with us for future convenience:</h4><br>
              <div class="btm_btn"><br>
			  <input type="radio" value="Guest" name="guestreg" id="g11"> Checkout as Guest <br><br>
			  <input type="radio" value="Register" name="guestreg" id="g22"> Register 
			  </div>
              <div class="login_btn-deta"><span class="allbutton">
				<a style="float:right;margin-top:0px;" href="#" onClick="Chkregister2();return false;" title="Continue">Continue</a></span>
                <div class="clear"></div>
              </div>
            </div>
          </div><input type="hidden" name="HidRegUser2" id="HidRegUser2" value="0" />
		  </form>
          <div class="clear"></div>
        </div>
          <div class="clear">&nbsp;</div>
        </div>
      </div>
      <div class="clear">&nbsp;</div>
    </div>
  </div>
</section>
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
<script src='js/hammer.min.js'></script>
<script src="js/simplemenu.js"></script>
<link rel="stylesheet" type="text/css" href="css/font-awesome.css" />
<script>
function Chkregister()
{
	form=document.FrmRegister;
	if(form.email.value=="")
	{
		alert("Please enter your email address.")
		form.email.focus();
		return false;
	}
	else if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(form.email.value)))
	{
		alert("Please enter a proper email address.");
		form.email.focus();
		return false;
	}
	else if(form.password.value=="")
	{
		alert("Please enter password.")
		form.password.focus();
		return false;
	}
	else
	{
		form.HidRegUser.value=1; form.submit();return true;	
	}
}
function Chkregister2()
{
	form=document.FrmRegister2;
	if(document.getElementById("g11").checked==false && document.getElementById("g22").checked==false)
	{
		alert("Please select an option.");
		return false;
	}
	else
	{
		form.HidRegUser2.value=1; form.submit();return true;	
	}
}
</script>
<? include("googleanalytic.php");?>
<? include("dbclose.php");?>
</body>
</html>