<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

/**
* OUTGOING MAIL MESSAGES SPECIFIC FUNCTION
*/

/**
* function to get the status of the invite sent to the user
* @param: $contact
*/
function getMailMessage($name) {
	$query=0;
	$query=mysqlquery("select * from vl_emails_outgoing where lower(name)='".strtolower($name)."'");
	if(mysqlnumrows($query)) {
		return mysqlresult($query,0,'description');
	}
}
?>