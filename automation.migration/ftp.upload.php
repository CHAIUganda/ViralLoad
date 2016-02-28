<?
//includes
include "ftp.functions.php";

/*
* INSTRUCTIONS
**************************************************
* Set up an FTP username and password on the destination server for the two accounts
* Configure the default FTP path for that username test the connection using a conventional FTP client
**************************************************
*/

//Global Variables
$serverIP="54.213.163.238";
$serverPort="21";
$timeoutInSeconds="3600";

/* ----------------------------------------------------------------------------------------------------------- */
/* Oracle FTP related transfers. Set the ftp user/password for Oracle here */
$ftpUsernameOracle="changamka_oracle";
$ftpPasswordOracle="[h4n94mk40r4cl3";

$connection=0;
$connection=ftp_connect($serverIP,$serverPort,$timeoutInSeconds);

$login=0;
$login=ftp_login($connection,$ftpUsernameOracle,$ftpPasswordOracle);

ftp_pasv($connection,true);

//die if unable to connect
if(!$connection || !$login) { 
	mysqlquery("insert into synch_logs (activity,created) values ('FTP Connection Attempt For Oracle Dump Upload Failed!',now())"); 

}else{

  //log start
  mysqlquery("insert into synch_logs (activity,created) values ('Synchronization For Oracle Dump Initiated.',now())");

  /*
  * oracle dmp transfers
  * look within H:/ORACLE_BACKUP/dumpDIR/
  * look for any rar file and transfer that file to amazon
  */
  $pathToLocalDirectory=0;
  $pathToLocalDirectory="H:/ORACLE_BACKUP/dumpDIR/";

  $filename=0;
  $directory=0;
  $directory=opendir($pathToLocalDirectory);
  while(false!==($filesInDirectory=readdir($directory))) {
	  if(strtolower(getFileExtension($filesInDirectory))=="rar") {
	      $filename=$filesInDirectory;
	  }
  }

  $pathToLocalFile=0;
  $pathToLocalFile=$pathToLocalDirectory.$filename;

	//effect the FTP upload
	if($filename) {
		$upload=0;
		$upload=ftp_put($connection,$filename,$pathToLocalFile,FTP_BINARY);
		if(!$upload) { 
			//log failure
			mysqlquery("insert into synch_logs (activity,filesize,created) values ('Oracle Synchronization Failed ($pathToLocalFile, ".number_format((float)filesize($pathToLocalFile)/1024000,2)." Mb)','".filesize($pathToLocalFile)."',now())");
		} else {
			//log success
			mysqlquery("insert into synch_logs (activity,filesize,created) values ('Oracle Synchronization Completed ($pathToLocalFile, ".number_format((float)filesize($pathToLocalFile)/1024000,2)." Mb)','".filesize($pathToLocalFile)."',now())");
		}
	}
}
//close connection for Oracle Dump Upload
ftp_close($connection); 

/* ----------------------------------------------------------------------------------------------------------- */
/* MySQL FTP related transfers. Set the ftp user/password for MySQL here */
$ftpUsernameMySQL="changamka_mysql";
$ftpPasswordMySQL="[h4n94mk4_my5ql";

$connection=0;
$connection=ftp_connect($serverIP,$serverPort,$timeoutInSeconds);

$login=0;
$login=ftp_login($connection,$ftpUsernameMySQL,$ftpPasswordMySQL);

ftp_pasv($connection,true);

//die if unable to connect
if(!$connection || !$login) { 
	mysqlquery("insert into synch_logs (activity,created) values ('FTP Connection Attempt For MySQL Dump Upload Failed!',now())"); 

}else{

  //log start
  mysqlquery("insert into synch_logs (activity,created) values ('Synchronization For MySQL Dump Initiated.',now())");

  /*
  * mysql dmp transfers
  * look within H:/MYSQL_BACKUP/dumpDIR/
  * look for any rar file and transfer that file to amazon
  */
  $pathToLocalDirectory=0;
  $pathToLocalDirectory="H:/MYSQL_BACKUP/dumpDIR/";

  $filename=0;
  $directory=0;
  $directory=opendir($pathToLocalDirectory);
  while(false!==($filesInDirectory=readdir($directory))) {
	  if(strtolower(getFileExtension($filesInDirectory))=="rar") {
	      $filename=$filesInDirectory;
	  }
  }

  $pathToLocalFile=0;
  $pathToLocalFile=$pathToLocalDirectory.$filename;

	//effect the FTP upload
	if($filename) {
		$upload=0;
		$upload=ftp_put($connection,"db.mysql.".getFormattedDateSimple(getCurrentDate()).".".getFormattedHour(getCurrentDate()).".$fileext",$pathToLocalFile,FTP_BINARY);
		if(!$upload) { 
			//log failure
			mysqlquery("insert into synch_logs (activity,filesize,created) values ('MySQL Synchronization Failed ($pathToLocalFile, ".number_format((float)filesize($pathToLocalFile)/1024000,2)." Mb)','".filesize($pathToLocalFile)."',now())");
		} else {
			//log success
			mysqlquery("insert into synch_logs (activity,filesize,created) values ('MySQL Synchronization Completed ($pathToLocalFile, ".number_format((float)filesize($pathToLocalFile)/1024000,2)." Mb)','".filesize($pathToLocalFile)."',now())");
		}
	}
}
//close connection for MySQL Dump Upload
ftp_close($connection); 
?>