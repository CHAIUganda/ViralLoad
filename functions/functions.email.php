<?
/**
* EMAIL FUNCTIONS
*/

define("LIBR", "\r\n");

/*
* function to send out HTML emails
* @param: $to - who receives this email?
* @param: $from - who is the email coming from? info@vl.com
* @param: $fromName - vl 
* @param: $subject - the email subject
* @param: $localmessage - message
*/
function queMail($to,$cc,$subject,$message,$html) {
	global $trailSessionUser,$datetime;
	mysqlquery("insert into vl_daemon_email 
					(fromName,fromAddress,toAddress,ccAddress,subject,message,html,created,createdby) 
					values 
					('Issue Tracker','info@vl.com','$to','$cc','".ereg_replace("'","\'",$subject)."','".ereg_replace("'","\'",$message)."','$html','$datetime','$trailSessionUser')");
}

/*
* function to send out HTML emails
* @param: $to - who receives this email?
* @param: $subject - the email subject
* @param: $message - message
*/
function sendHTMLemail($to,$cc,$subject,$message) {
	global $trailSessionUser;

	$localmessage="
			<style type=\"text/css\">
			<!--
			body {
				margin-top: 0px;
				margin-bottom: 0px;
				background-color: #FFFFFF;
			}
			.vl {
				font-family: arial;
				color: #3A3A3A;
				font-size: 12px;
			}
			.vls {
				font-family: arial;
				color: #999999;
				font-size: 11px;
			}
			-->\n
			</style>";	
	$localmessage .= $message;

	queMail($to,$cc,$subject,$localmessage,1);
}

/*
* function to send out Plain emails
* @param: $to - who receives this email?
* @param: $subject - the email subject
* @param: $message - message
*/
function sendPlainEmail($to,$cc,$subject,$message) {
	global $home_url;
	global $mail;
	global $trailSessionUser;
	queMail($to,$cc,$subject,$message,0);
}

/**
* function to reset a user's password
* @param: $email
*/
function resetPassword($email) {
	global $datetime,$borrowercentralCuser,$home_domain;

	$query=0;
	$query=mysqlquery("select * from vl_users where email='$email'");
	if(mysqlnumrows($query)) {	
		//reset the password and mail the user
		$newPassword=0;
		$newPassword=generatePassword();
		//now reset the password		
		mysqlquery("update vl_users set 
					xp='".borrowercentralcSimpleEncrypt($newPassword)."',
					password='".borrowercentralcRencrypt($newPassword)."' 
					where email='$email'");
					
		//inform the user by email
		//subject
		$subject=0;
		$subject="Password Reset";
	
		//variables
		$password=0;
		$password=$newPassword;
				
		//the message
		$message=0;
		$message="
		Your password has been reset. 
		
		Your new password is: $password 
		
		To preserve your privacy, we recommend that you login and change your password. 
		
		Kind regards, 
		System Team";

		//mail the user
		sendPlainEmail($email,$subject,$message);
	}
}
?>