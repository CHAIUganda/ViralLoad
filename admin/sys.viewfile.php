<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";

//just so we know it is broken
//error_reporting(E_ALL);
set_time_limit(360);

//get the image from the db
$sql = "SELECT file,type,mimeType,name,size FROM vl_filenames WHERE id=$wR";
 
//the result of the query
$result = mysqlquery("$sql") or die("incorrect query: " . mysqlerror());
 
//log file downloaded
$p=0;
$p="download";
$a=0;
$a=mysqlresult($result,0,'name');
//logHit();

//set the header for the file
if(getFileTypeFromExtension(mysqlresult($result,0,'type'))=="img") {
	//images
	header("Content-type: ".(mysqlresult($result,0,'mimeType')?mysqlresult($result,0,'mimeType'):getMIME(mysqlresult($result,0,'name'))));
} else {
	//other binary data
	header("Content-length: ".mysqlresult($result,0,'size'));
	header("Content-type: ".(mysqlresult($result,0,'mimeType')?mysqlresult($result,0,'mimeType'):getMIME(mysqlresult($result,0,'name'))));
	header("Content-Disposition: attachment; filename=".mysqlresult($result,0,'name'));
}
echo mysqlresult($result,0,'file');
?>