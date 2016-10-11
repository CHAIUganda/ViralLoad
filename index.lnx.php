<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";

/**
* INSTRUCTIONS
* ran as lnx http://www.vl.com/index.lnx.php
* task 1: dispatch unsent mail - vl_daemon_email
* task 2: remove records from vl_samples_worksheet where worksheetID not in (select id from vl_samples_worksheetcredentials);
<<<<<<< HEAD
=======
*/
>>>>>>> dev

//task 1: email
if(isQuery("select id from vl_daemon_email where sent='0'")) {
	$j=0;
	foreach(queryTableID("select id from vl_daemon_email where sent='0'") as $j) {
		//set to sent
		mysqlquery("update vl_daemon_email set sent=1 where id='$j'");
		//is html
		if(getTableInfo("vl_daemon_email",$j,"html")) {
			DsendHTMLEmail(getTableInfo("vl_daemon_email",$j,"toAddress"),getTableInfo("vl_daemon_email",$j,"ccAddress"),getTableInfo("vl_daemon_email",$j,"fromAddress"),getTableInfo("vl_daemon_email",$j,"fromName"),removeSpecialCharacters(getTableInfo("vl_daemon_email",$j,"subject")),removeSpecialCharacters(getTableInfo("vl_daemon_email",$j,"message")));
		} else {
			DsendPlainEmail(getTableInfo("vl_daemon_email",$j,"toAddress"),getTableInfo("vl_daemon_email",$j,"ccAddress"),getTableInfo("vl_daemon_email",$j,"fromAddress"),getTableInfo("vl_daemon_email",$j,"fromName"),removeSpecialCharacters(getTableInfo("vl_daemon_email",$j,"subject")),removeSpecialCharacters(getTableInfo("vl_daemon_email",$j,"message")));
		}
	}
}
<<<<<<< HEAD
*/
=======
>>>>>>> dev

//task 2: remove "hanging" records from vl_samples_worksheet
$query=0;
$query=mysqlquery("select distinct worksheetID from vl_samples_worksheet where worksheetID not in (select id from vl_samples_worksheetcredentials)");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		//remove
		mysqlquery("delete from vl_samples_worksheet where worksheetID='$q[worksheetID]'");
		mysqlquery("delete from vl_results_roche where worksheetID='$q[worksheetID]'");
		mysqlquery("delete from vl_results_abbott where worksheetID='$q[worksheetID]'");
	}
}
?>