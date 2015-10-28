<?
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