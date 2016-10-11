<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

if(!$_SESSION[VLADMIN]) {
	go("?act=login");
} else {
	go("?act=today");
}
?>