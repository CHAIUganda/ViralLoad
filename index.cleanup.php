<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";

/**
* INSTRUCTIONS
* ran as lnx http://www.vl.com/index.lnx.php
* task 1: dispatch unsent mail - vl_daemon_email
* task 2: remove records from vl_samples_worksheet where worksheetID not in (select id from vl_samples_worksheetcredentials);

/* 
* task 1: delete files within downloads to free up space on the server
*/
$directoryPath=0;
$directoryPath="C:/xampp/htdocs/viralload/downloads.forms/";

$directoryContents=array();
$directoryContents=scandir($directoryPath);

foreach($directoryContents as $dr) {
	if(is_file($directoryPath.$dr)) {
		//remove file
		unlink($directoryPath.$dr);
	}
}
?>