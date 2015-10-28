<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

//session management
session_start();
//session_register("TRAILANALYTICSISSUES");

//database requirements
include_once("conf.db.php");
//default variables
include_once("conf.variables.php");
//htaccess variables
include_once("conf.variables.htaccess.php");
//default functions
include_once("conf.functions.php");

//load all vl modules
loadModules("",0);

//mailer object
$mail = new mailer();
//vlDC object
$vlDC = new vlDC();
//$vlDC->debugOn();
$vlDC->sRequestURI=$home_url;
// ExcelFile($filename, $encoding);
$excelData = new Spreadsheet_Excel_Reader();
// Set output Encoding.
$excelData->setOutputEncoding('CP1251');
//load PDF object
$dompdf = new DOMPDF();

/***
* if you want you can change 'iconv' to mb_convert_encoding:
* $data->setUTFEncoder('mb');
*
* By default rows & cols indeces start with 1
* For change initial index use:
* $data->setRowColOffset(0);
*
* Some function for formatting output.
* $data->setDefaultFormat('%.2f');
* setDefaultFormat - set format for columns with unknown formatting
*
* $data->setColumnFormat(4, '%.3f');
* setColumnFormat - set format for column (apply only to number fields)
*
**/

//standard user variable
$trailSessionUser=0;
$trailSessionUserID=0;
if($_SESSION["VLEMAIL"]) {
	$trailSessionUser=$_SESSION["VLEMAIL"];
} else {
	$trailSessionUser="visitor (".($ipAddress?$ipAddress:$serverAddress).($c?"/$c":"").")";
}

//load all functions
loadFunctions();
//load some static files
include_once("conf.xajax.php");

/**
* date/time defaults
* at 11:00hrs hostmonster mysql time == 20:00hrs Ug time
* 20:00hrs Ug time (+3 GMT) == 17:00hrs GMT
* hostmonster == -6 GMT
*/
$datetime=0;
//$datetime=getDualInfoWithAlias("convert_tz(now(),'$default_GMTtimezone:00','".getDetailedTableInfo2("vl_timezones",($c?"lower(country)='".strtolower($c)."'":"country='$default_country'"),"timezone")."')","datetime");
$datetime=getDualInfoWithAlias("now()","datetime");
$date=0;
$date=getRawFormattedDateLessDay($datetime);
$icurDate=0;
$icurDate=getRawFormattedDateLessDay($datetime);
$curTime=0;
$curTime=getFormattedTime($datetime);

/*
* default envelope number
* added 27/Feb/15 to aid staff in the entry of the Location/Rejection ID
*/
$default_envelopeNumber=0;
$default_envelopeNumber=getFormattedDateYearShort($datetime).getFormattedDateMonth($datetime)."-";

//if the user has been idle for a certain period ($default_systemIdleWait mins), require them to login afresh.
if($_SESSION["VLEMAIL"] && !$directlogin) {
	//get time since last activity hh:mm:ss 00:00:00
	$time=0;
	$time=getDetailedTableInfo2("vl_logs_pagehits","createdby='$trailSessionUser' order by created desc limit 1","timediff(now(),created)");
	//split this variable
	$timeA=array();
	$timeA=explode(":",trim($time));
	$tHour=0;
	$tMinute=0;
	$tSecond=0;
	$tHour=$timeA[0]/1;
	$tMinute=$timeA[1]/1;
	$tSecond=$timeA[2]/1;
	if(count($timeA)) {
		if((!$tHour && $tMinute>$default_systemIdleWait) || $tHour) {
			go("/logout/");
		}
	}
}
?>