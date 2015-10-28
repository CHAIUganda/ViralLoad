<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

/**
* EMAIL DAEMON FUNCTIONS
*/

/*
* function to send out HTML emails
* @param: $to - who receives this email?
* @param: $from - who is the email coming from? info@vl.com
* @param: $fromName - vl 
* @param: $subject - the email subject
* @param: $message - message
*/
function DsendHTMLEmail($to,$cc,$from,$fromName,$subject,$message) {
	global $mail;

	$mail->IsHTML(TRUE);
	$mail->init_mailer($fromName,$from,$to,$cc,"",$subject,$message);
	$mail->Send();
}

/*
* function to send out Plain emails
* @param: $to - who receives this email?
* @param: $from - who is the email coming from? info@vl.com
* @param: $fromName - vl * @param: $subject - the email subject
* @param: $message - message
*/
function DsendPlainEmail($to,$cc,$from,$fromName,$subject,$message) {
	global $mail;

	$mail->IsHTML(FALSE);
	$mail->init_mailer($fromName,$from,$to,$cc,"",$subject,$message);
	$mail->Send();
}
?>