<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

session_start();
//session_register('VLADMIN');

if(!$_SESSION[VLADMIN]) {
	include "inc.login.php";
	exit;
}

//mailer object
include_once "mail/class.mailer.php";
$mail = new mailer();
//excel object
include_once "excel/excel.inc.php";
include_once "excel/ole.inc.php";
// ExcelFile($filename, $encoding);
$excelData = new Spreadsheet_Excel_Reader();
// Set output Encoding.
$excelData->setOutputEncoding('CP1251');
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
//$excelData->read('jxlrwtest.xls');
//include db - it's required by calendar class
include "conf.db.php";
//calendar object
include_once "class_calendar.php";

date_default_timezone_set('Africa/Nairobi');

$d = getdate(time());
if ($month == "") {
    $month = $d["mon"];
}
if ($year == "") {
    $year = $d["year"];
}
$cal = new MyCalendar;
//critical includes
//countries
include "functions.countries.php";
//datetime
include "functions.datetime.php";
//debug
include "functions.debug.php";
//email
include "functions.email.php";
//email
include "functions.files.php";
//misc
include "functions.misc.php";
//staff
include "functions.staff.php";
//strings
include "functions.strings.php";

//other conf variables
$curDate = gmdate("d-M-Y");
$icurDate = gmdate("Y-m-d");
$curTime = gmdate("H:m:s");
$datetime=0;
$datetime="$icurDate $curTime";

if($_SERVER['REMOTE_ADDR']=="127.0.0.1") {
	$home_url="http://vl.cphluganda.org/";
	$server_url="http://vl.cphluganda.org";
	$system_default_path="D:/Samuel/Projects/CHAI/Systems/viralload/Web/";
	$home_domain=0;
	$home_domain="vl.cphluganda.org";
} elseif($_SERVER['SERVER_NAME']=="nms.trailanalytics.com") {
	$home_url="http://vl.trailanalytics.com/";
	$server_url="http://vl.trailanalytics.com";
	$system_default_path="/home2/borrower/public_html/vl/";
	$home_domain=0;
	$home_domain="vl.trailanalytics.com";
} else {
	$home_url="http://192.168.0.43/";
	$server_url="http://192.168.0.43";
	$system_default_path="C:/xampp/htdocs/viralload/";
	$site_default_name=0;
	$site_default_name=$_SERVER['HTTP_HOST'];
	$site_default_name=preg_replace("/admin\//is","",$site_default_name);
	$site_default_name=preg_replace("/admin/is","",$site_default_name);
	$home_domain=0;
	$home_domain=preg_replace("/www./is","",$site_default_name);
	$home_domain=preg_replace("/admin\//is","",$home_domain);
	$home_domain=preg_replace("/admin/is","",$home_domain);
}
//site folder
$siteFolder=0;
$siteFolder="admin";

//number whats new envelopes
$default_numberNewEnvelopes=5;
//string length
$default_stringLength=30;
//rows to display
$rowsToDisplay=20;
//maximum upload size, that is 5,000KB
$default_maxUploadSize=5000000;
//default country
$default_country="Uganda";
//server GMT timezone
$default_GMTtimezone=-6;
//default lower random values
$default_randomlower="10";
//default upper random values
$default_randomupper="99999999";
//image dir 0
$image_dir0="_i34";
//image dir 1
$image_dir1="_tmD9877";
//image dir 2
$image_dir2="_vrx8";
//image dir 3
$image_dir3="_srt12";
//image dir 4
$image_dir4="_o00";
//radius 
$default_radius=5;
//sms length
$default_numberSMSCharacters=150;
//top profile search count
$default_numberSearches=50;
//sms header
$default_smsHeader="VL";
//sms preview messages
$default_previewSMS=5;
//password length
$default_passwordLength=8;
//historical passwords
$default_passwordsHistory=10;
//credit application duration
$default_creditApplicationDuration=30;
//version
$default_version="1.11";
//institution short name
$institutionShortName="VL";
//MG length
$default_mglength=12;
//system IP
$default_systemIP="127.0.0.1";

//$ipAddress = gethostbyaddr($_SERVER['REMOTE_ADDR']);
$serverAddress = $_SERVER['REMOTE_ADDR'];

if($_SESSION["VLADMIN"]) {
	$serverAddress=0;
	$serverAddress=$_SESSION["VLADMIN"];
}

//who's the user?
$user=0;
$user=($_SESSION["VLADMIN"]?"admin: ".$_SESSION["VLADMIN"]:"visitor: $ipAddress $serverAddress");

$c=0;
$c=$default_country;

/**
* date/time defaults
* at 11:00hrs hostmonster mysql time == 20:00hrs Ug time
* 20:00hrs Ug time (+3 GMT) == 17:00hrs GMT
* hostmonster == -6 GMT
*/
$datetime=0;
$datetime=getDualInfoWithAlias("now()","datetime");
//$curDate=0;
//$curDate=gmdate("d-M-Y");
$icurDate=0;
$icurDate=getRawFormattedDateLessDay($datetime);
$curTime=0;
$curTime=getFormattedTime($datetime);

//free memory
clearstatcache();
?>
