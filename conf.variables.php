<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

/**
* CRITICAL VARIABLE DECLARATIONS
*/

//default system path and name
$system_default_path=0;
$site_default_name=0;
if($_SERVER['REMOTE_ADDR']=="127.0.0.1") {
	$system_default_path=$_SERVER['DOCUMENT_ROOT']."/";
} elseif($_SERVER['SERVER_NAME']=="vl.trailanalytics.com") {
	$system_default_path="/home2/borrower/public_html/vl/";
} else {
	$system_default_path="C:/xampp/htdocs/viralload/";
}
date_default_timezone_set('Africa/Nairobi');
$site_default_name=$_SERVER['HTTP_HOST'];
$home_url=0;
$home_url="http://$site_default_name/";
$home_domain=0;
$home_domain=preg_replace("/www./is","",$site_default_name);
$server_url=0;
$server_url="http://".$_SERVER['HTTP_HOST'];
$server_ip=0;
$server_ip="127.0.0.1";
//path to modules
$modules_path=0;
$modules_path=$system_default_path."modules/";
//path to functions
$functions_path=0;
$functions_path=$system_default_path."functions/";
//path to barcodes
$barcodes_path=0;
$barcodes_path=$system_default_path."downloads.barcodes/";
//url to barcodes
$barcodes_url=0;
$barcodes_url=$server_url."/downloads.barcodes/";
//server details
$server_path=0;
$server_path=$system_default_path;
//institution short name
$institutionShortName="CPHL";

//date/time defaults
$datetime=0;
$datetime=gmdate("Y-m-d")." ".gmdate("H:i:s");

//number whats new envelopes
$default_numberNewEnvelopes=5;
//string length
$default_stringLength=10;
//number length
$default_numberLength=5;
//rows to display
$rowsToDisplay=50;
//menus to display
$menusToDisplay=5;
//maximum upload size, that is 5,000KB
$default_maxUploadSize=5000000;
//default country
$default_country="Uganda";
//default number of days someone may be logged in
$default_numberDays=15;
//server GMT timezone
$default_GMTtimezone=-3;
//number of entries
$default_numberCharacters=500;
//default lower random values
$default_randomlower="10";
//default upper random values
$default_randomupper="99999999";
//image dir 0
$image_dir0="_r983";
//image dir 1
$image_dir1="_or7i7";
//image dir 2
$image_dir2="_kx";
//image dir 3
$image_dir3="_sr";
//image dir 4
$image_dir4="_0o0";
//radius 
$default_radius=20;
//number users on TRAILANALYTICSISSUES
$default_numberUsersTRAILANALYTICSISSUES=50;
//header
$default_smsHeader="NMS";
//Institution Name
$default_institutionName="Ministry of Health, The Republic of Uganda";
//system idle time in minutes
$default_systemIdleWait=15;
//password length
$default_passwordLength=8;
//historical passwords
$default_passwordsHistory=10;
//password expiry in days
$default_passwordExpiryDuration=30;
//system IP
$default_systemIP="localhost";
//last 100 loan applications
$default_lastLoanApplications=100;
//institution email domain
$default_institutionEmailDomain="@vl.go.ug";
//default support email
$default_supportEmail="info@vl.go.ug";
//number of days within which an issue will be flagged as a duplicate if another with a similar title is detected
$default_duplicateDuration=2;
//number of samples to be captured
$default_numberSamples=10;
//abbott machine configuration
$default_numberPrintsAbbott=96;
$default_numberControlsAbbott=3;
$default_numberCalibratorsAbbott=8;
//roche machine configuration
$default_numberPrintsRoche=24;
$default_numberControlsRoche=3;
$default_numberCalibratorsRoche=8;
//number of worksheets to display in drop down
$default_numberWorksheets=50;
//number of forms per book
$default_formsPerBook=50;
//viral load repeat test window (in months)
$default_viralLoadRepeatTestWindow=5;

//$ipAddress = gethostbyaddr($_SERVER['REMOTE_ADDR']);
$serverAddress = $_SERVER['REMOTE_ADDR'];

//free memory
clearstatcache();
?>
