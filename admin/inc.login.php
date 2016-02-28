<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

if($x) {
	include "conf.db.php";
	include_once("functions.datetime.php");
	include_once("functions.strings.php");
	include_once("functions.debug.php");
	
	if($vl_name && $vl_pass) {
		$u=0;
		$u=mysqlquery("select * from vl_admins where username='$vl_name'");	
		if(mysqlnumrows($u)) {
			while($un=mysqlfetcharray($u)) {
				if($vl_name==$un["username"]) {
					//username authentic
					if(vlSimpleDecrypt($un["password"])==hash("sha256",$vl_pass)) {
						//get the users email
						$_SESSION["VLADMIN"] = $un["email"];
						go("?");
					} else {
						echo "<script>alert('Invalid Credentials');document.location.href='?';</script>";
					}
				}
			}
		} else {
			echo "<script>alert('Invalid Credentials');document.location.href='?';</script>";		
		}
	} else {
		echo "<SCRIPT>alert('Missing Credentials');document.location.href='?';</SCRIPT>";
	}			
}
?>
	<script Language="JavaScript" Type="text/javascript"><!--
	function evalValidate(login) {
	  if (login.vl_name.value == "") {
		alert("your username ... ?");
		login.vl_name.focus();
		return (false);
	  }
	
	  if (login.vl_pass.value == "") {
		alert("your password ... ?");
		login.vl_pass.focus();
		return (false);
	  }
	  return (true);
	}
	//--></script>
<link href="css/vl.css" rel="stylesheet" type="text/css">
<body onLoad="login.vl_name.focus();">
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<form action="../admin/?" method="post" name="login" id="login" onSubmit="return evalValidate(this)">
  <table width="10%"  border="0" align="center" cellpadding="0" cellspacing="0" class="vl">
    <tr>
      <td><img src="images/rounded_top.gif" width="388" height="12"></td>
    </tr>
    <tr>
      <td background="images/rounded_bg.gif"><img src="/images/spacer.gif" width="5" height="5"></td>
    </tr>
    <tr>
      <td background="images/rounded_bg.gif"><table width="100%"  border="0" class="vl">
          <tr>
            <td width="27%" align="right" valign="top"><img src="images/login.jpg" width="90" height="85"></td>
            <td width="73%" valign="top"><table width="100%" align="center" class="vl">
                <tr class="vl">
                  <td align="right">&nbsp;<strong>Login</strong></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td width="22%" height="26" align="right"><strong>username:</strong></td>

                  <td width="65%"><input name="vl_name" type="text" class="search" id="vl_name" size="20" maxlength="40">
                  </td>
                </tr>
                <tr>
                  <td width="22%" align="right"><strong>password:</strong></td>
                  <td width="65%"><input name="vl_pass" type="password" class="search" id="vl_pass" size="20" maxlength="40">
                  </td>
                </tr>
                <tr>
                  <td width="22%">&nbsp;</td>
                  <td width="78%"><input type="submit" name="Submit" value="     login     ">
                    <input name="x" type="hidden" id="x" value="x">
                  </td>
                </tr>
            </table></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td background="images/rounded_bg.gif"><img src="/images/spacer.gif" width="5" height="5"></td>
    </tr>
    <tr>
      <td><img src="images/rounded_bottom.gif" width="388" height="12"></td>
    </tr>
  </table>
	  
</form>
</body>
