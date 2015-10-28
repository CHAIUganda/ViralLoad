<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

//should we send the password to an email?
if($remindEmail) {
	resetPassword($remindEmail);
	go("/sentreminder/$remindEmail/");
}

if($login && $email && $pass) {
	//validate
	$email=validate($email);
	
	//authenticate
	$u=0;
	$u=mysqlquery("select * from vl_users where lower(email)='".strtolower($email)."'");
	if(mysqlnumrows($u)) {
		while($un=mysqlfetcharray($u)) {
			if(strtolower($email)==strtolower($un["email"])) {
				//email authentic
				if(vlSimpleDecrypt($un["xp"])==hash("sha256",$pass)) {
					//has this account been de-activated?
					if(!$un["active"]) {
						go("/login/in/");
					} else {
						//register session variables
						$_SESSION["VLEMAIL"]=$email;
						//log
						mysqlquery("update vl_users set lastLogin='$datetime' where email='$_SESSION[VLEMAIL]'");
						//redirect
						go("/dashboard/welcome/");
					}
				} else {
					go("/login/er/");
				}
			}
		}
	} else {
		go("/login/er/");
	}
}
?>
<p><img src="/images/spacer.gif" width="5" height="5"></p>
<table width="45%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%" bgcolor="#FFFFFF"><script language="JavaScript" type="text/javascript">		
                <!--
                function check(login) {
                    if(!document.login.email.value) {
                        alert('Please provide your email address');
                        document.login.email.focus();
                        return false;
                    }
                    if(!document.login.pass.value) {
                        alert('Please provide your password');
                        document.login.pass.focus();
                        return false;
                    }
                    document.login.Submit.disabled=true;
                    return true;
                }
                function sendPassword(login) {
                    var theEmail=document.login.email.value;
                    if(!theEmail) {
                        alert('First insert your email address then click \'forgotten password?\'');
                        document.login.email.focus();
                        return false;
                    } else {
                        //process the send
                        document.location.href='/sendreminder/'+theEmail+'/';
                    }
                    return true;
                }
                //-->
				</script>
                      <form action="/dashboard/" method="post" name="login" id="login" onsubmit="return check(this)">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td style="padding:20px 20px 40px 20px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="padding-bottom:20px; border-bottom: 1px solid #e1e8e1"><img src="/images/logo.prelogin.gif" alt="NMS Logo" border="0" /></td>
              </tr>
              <tr>
                <td style="padding-top:10px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <? if($fail) { ?>
                  <tr>
                    <td class="vl_error">Incorrect username or password!</td>
                  </tr>
                  <tr>
                    <td><img src="/images/spacer.gif" width="1" height="1" /></td>
                  </tr>
                  <? } else if($inactive) { ?>
                  <tr>
                    <td class="vl_error">Account Inactive! Kindly contact an Administrator.</td>
                  </tr>
                  <tr>
                    <td><img src="/images/spacer.gif" width="1" height="1" /></td>
                  </tr>
                  <? } else if($logout) { ?>
                  <tr>
                    <td class="vl_success">Logged out!</td>
                  </tr>
                  <tr>
                    <td><img src="/images/spacer.gif" width="1" height="1" /></td>
                  </tr>
                  <? } else if($sentTo) { ?>
                  <tr>
                    <td class="vl_success">New password sent to: <strong><?=$sentTo?></strong></td>
                  </tr>
                  <tr>
                    <td><img src="/images/spacer.gif" width="1" height="1" /></td>
                  </tr>
                  <? } else { ?>
                  <tr>
                    <td class="vl" style="padding:20px 0px 20px 0px">Login to Viral Load</td>
                  </tr>
                  <? } ?>
                  <tr>
                    <td>
                        <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="vl">
                          <tr>
                            <td style="padding: 10px 0px 5px 0px">Email/Username</td>
                          </tr>
                          <tr>
                            <td><input name="email" type="text" id="email" size="35" class="search" /></td>
                          </tr>
                          <tr>
                            <td style="padding: 10px 0px 5px 0px">Password</td>
                          </tr>
                          <tr>
                            <td><input name="pass" type="password" id="pass" size="35" class="search" /></td>
                          </tr>
                          <tr>
                            <td style="padding:5px 0px 0px 0px"><a href="#" onclick="sendPassword(this)" class="vl_brown_link">Forgotten password?</a></td>
                          </tr>
                        </table>
                      </td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td bgcolor="#cccccc" style="padding:20px; border-top: 1px #b3b3b3 solid; background-image:url(/images/login.bg.prelogin.jpg); background-repeat: no-repeat; background-position: center right"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
              <tr>
                <td><input type="submit" name="Submit" value="login" class="button" />
                  <input name="redirect" type="hidden" id="redirect" value="<?=($redirect?$redirect:vlEncrypt($_SERVER['REQUEST_URI']))?>" />
                  <input name="login" type="hidden" id="login" value="1" />
                  </td>
              </tr>
            </table></td>
          </tr>
        </table>
        			</form>
        </td>
        <td background="/images/bg.right.gif"><img src="/images/spacer.gif" width="11" height="11"></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td background="/images/bg.bottom.gif" width="100%"><img src="/images/spacer.gif" width="11" height="11"></td>
        <td background="/images/bg.bottom.right.gif"><img src="/images/spacer.gif" width="11" height="11"></td>
      </tr>
    </table></td>
  </tr>
</table>
<div style="padding:5px 0px 5px 0px" align="center" class="vls"><strong>Viral Load</strong></div>
<div style="padding:0px 0px 5px 0px" align="center" class="vl">Ministry of Health, The Republic of Uganda &copy; Copyright <?=getCurrentYear()?></div>