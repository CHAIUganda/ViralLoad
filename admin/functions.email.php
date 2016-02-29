<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

/**
* EMAIL FUNCTIONS
*/

/*
* function to send out HTML emails
* @param: $to - who receives this email?
* @param: $from - who is the email coming from? info@vl.com
* @param: $fromName - vl 
* @param: $subject - the email subject
* @param: $localmessage - message
*/
function queMail($fromName,$from,$to,$subject,$message,$html) {
	global $user,$datetime;
	mysqlquery("insert into vl_daemon_email 
					(fromName,fromAddress,toAddress,subject,message,html,created,createdby) 
					values 
					('$fromName','$from','$to','".preg_replace("/'/s","\'",$subject)."','".preg_replace("/'/s","\'",$message)."','$html','$datetime','$user')");

}

/*
* function to send out HTML emails
* @param: $to - who receives this email?
* @param: $from - who is the email coming from? info@vl.com
* @param: $fromName - vl * @param: $subject - the email subject
* @param: $message - message
*/
function sendHTMLemail($to,$from,$fromName,$subject,$title,$message) {
	global $home_url;
	global $mail;
	global $user;

	$localmessage="
			<style type=\"text/css\">
			<!--
			body {
				margin-top: 0px;
				margin-bottom: 0px;
				background-color: #E3E6FF;
			}
			.vl {
				font-family: arial;
				color: #3A3A3A;
				font-size: 12px;
			}
			.vlsmall {
				font-family: arial;
				color: #3A3A3A;
				font-size: 10px;
			}
			-->\n
			</style>";	
	$localmessage .= "
		<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\" bgcolor=\"#E3E6FF\">
		<tr>
		<td>
		<!-- bounding table -->
		<table width=\"400\" border=\"0\" class=\"vl\">
		<tr>
		<td><img src=\"".$home_url."images/mail/mailheader.gif\" width=\"500\" height=\"70\"></td>
		  </tr>
			<tr>
			  <td><table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
			 <tr>
			   <td><img src=\"".$home_url."images/mail/bg_round_top.gif\" width=\"500\" height=\"11\"></td>
			 </tr>
			 <tr>
			   <td background=\"".$home_url."images/mail/bg.gif\"><table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
				 <tr>
		<td><img src=\"".$home_url."images/mail/spacer.gif\" width=\"10\" height=\"10\"></td>
		<td width=\"100%\"><table width=\"100%\"  border=\"0\" class=\"vl\">
		<!-- title -->
		  <tr class=\"vl\">
			<td><strong>$title</strong></td>
		  </tr>
		<!-- end title -->
		  <tr class=\"vl\">
			<td><img src=\"".$home_url."images/spacer.gif\" width=\"10\" height=\"10\"></td>
		  </tr>
		<!-- message -->
		  <tr class=\"vl\">
			<td>$message</td>
		  </tr>
		<!-- end message -->
		  <tr class=\"vl\">
			<td><strong><a href=\"$home_url\">vl</a></strong> </td>
		  </tr>
		</table></td>
		<td><img src=\"".$home_url."images/mail/spacer.gif\" width=\"10\" height=\"10\"></td>
				 </tr>
			   </table></td>
			 </tr>
			 <tr>
			   <td><img src=\"".$home_url."images/mail/bg_round_bottom.gif\" width=\"500\" height=\"11\"></td>
			 </tr>
		   </table></td>
		  </tr>
		  </table>
		  <!-- end bounding table -->
		  </td>
		 </tr>
		</table>";

	//$mail->IsHTML(TRUE);
	//$mail->init_mailer($fromName,$from,$to,"","",$subject,$localmessage);
	//$mail->Send();
	queMail($fromName,$from,$to,$subject,$localmessage,1);
}

/*
* function to send out Plain emails
* @param: $to - who receives this email?
* @param: $from - who is the email coming from? info@vl.com
* @param: $fromName - vl * @param: $subject - the email subject
* @param: $title - title within the email
* @param: $message - message
*/
function sendPlainEmail($to,$from,$fromName,$subject,$title,$message) {
	global $home_url;
	global $mail;
	global $user;

	//$mail->IsHTML(FALSE);
	//$mail->init_mailer($fromName,$from,$to,"","",$subject,$message);
	//$mail->Send();
	queMail($fromName,$from,$to,$subject,$message,0);
}

/*
* function to send out HTML emails to the admins
* @param: $to - who receives this email?
* @param: $from - who is the email coming from? info@vl.com
* @param: $fromName - vl * @param: $subject - the email subject
* @param: $title - title within the email
* @param: $message - message
*/
function sendHTMLemail2Admins($from,$fromName,$subject,$title,$message) {
	global $home_url;
	global $mail;
	global $user;

	$localmessage    ="
			<style type=\"text/css\">
			<!--
			body {
				margin-top: 0px;
				margin-bottom: 0px;
				background-color: #E3E6FF;
			}
			.vl {
				font-family: arial;
				color: #3A3A3A;
				font-size: 12px;
			}
			.vlsmall {
				font-family: arial;
				color: #3A3A3A;
				font-size: 10px;
			}
			-->\n
			</style>";	
	$localmessage    .= "
		<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\" bgcolor=\"#E3E6FF\">
		<tr>
		<td>
		<!-- bounding table -->
		<table width=\"400\" border=\"0\" class=\"vl\">
		<tr>
		<td><img src=\"".$home_url."images/mail/mailheader.gif\" width=\"500\" height=\"70\"></td>
		  </tr>
			<tr>
			  <td><table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
			 <tr>
			   <td><img src=\"".$home_url."images/mail/bg_round_top.gif\" width=\"500\" height=\"11\"></td>
			 </tr>
			 <tr>
			   <td background=\"".$home_url."images/mail/bg.gif\"><table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
				 <tr>
		<td><img src=\"".$home_url."images/mail/spacer.gif\" width=\"10\" height=\"10\"></td>
		<td width=\"100%\"><table width=\"100%\"  border=\"0\" class=\"vl\">
		<!-- title -->
		  <tr class=\"vl\">
			<td><strong>$title</strong></td>
		  </tr>
		<!-- end title -->
		  <tr class=\"vl\">
			<td><img src=\"".$home_url."images/spacer.gif\" width=\"10\" height=\"10\"></td>
		  </tr>
		<!-- message -->
		  <tr class=\"vl\">
			<td>$message</td>
		  </tr>
		<!-- end message -->
		  <tr class=\"vl\">
			<td><strong><a href=\"$home_url\">vl</a></strong> </td>
		  </tr>
		</table></td>
		<td><img src=\"".$home_url."images/mail/spacer.gif\" width=\"10\" height=\"10\"></td>
				 </tr>
			   </table></td>
			 </tr>
			 <tr>
			   <td><img src=\"".$home_url."images/mail/bg_round_bottom.gif\" width=\"500\" height=\"11\"></td>
			 </tr>
		   </table></td>
		  </tr>
		  </table>
		  <!--end bounding table-->
		 </td>
		</tr>
	</table>";

	//query admins
	$query=0;
	$query=mysqlquery("select email from vl_admins");
	$q=array();
	while($q=mysqlfetcharray($query)) {
		//$mail->AddAddress($q["email"]);
		queMail($fromName,$from,$q["email"],$subject,$localmessage,1);
	}
}

/*
* function to send out Plain emails to the admins
* @param: $from - who is the email coming from? info@vl.com
* @param: $fromName - vl * @param: $subject - the email subject
* @param: $title - title within the email
* @param: $message - message
*/
function sendPlainEmail2Admins($from,$fromName,$subject,$title,$message) {
	global $home_url;
	global $mail;
	global $user;

	//query admins
	$query=0;
	$query=mysqlquery("select email from vl_admins");
	$q=array();
	while($q=mysqlfetcharray($query)) {
		queMail($fromName,$from,$q["email"],$subject,$message,0);
	}
}

/*
* function to extract contact's name from their email
* if an email is orion@nebula.com, user's name is orion
* @param: $email
*/
function extractNameFromEmail($email) {
	$eArray=array();
	$eArray=explode("@",$email);
	return $eArray[0];
}

/*
* function to send out SMSes
* @param: $to - who receives this sms?
* @param: $message - sms
*/
function sendSMS($to,$message,$when) {
	global $default_smsHeader;
	if(logSMS($to,$message,$when)) {
		$curl_handle=curl_init();
		curl_setopt($curl_handle,CURLOPT_URL,"http://www.antsms.com/index.queue.php?p=remoteservices&header=$default_smsHeader&to=$to&message=".urlencode($message)."&when=".urlencode($when));
		curl_exec($curl_handle);
		curl_close($curl_handle);
	}
}

/*
* function to revoke an SMS
* @param: $to - who receives this sms?
* @param: $message - sms
*/
function revokeSMS($to,$message,$when) {
	global $default_smsHeader;
	$curl_handle=curl_init();
	curl_setopt($curl_handle,CURLOPT_URL,"http://www.antsms.com/index.revoke.php?p=remoteservices&header=$default_smsHeader&to=$to&message=".urlencode($message)."&when=".urlencode($when));
	curl_exec($curl_handle);
	curl_close($curl_handle);
}
?>