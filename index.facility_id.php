<?php
$GLOBALS['vlDC']=true;
include "conf.php";

$sql=" SELECT facilityID FROM vl_forms_clinicalrequest_dispatch AS a
	   LEFT JOIN vl_forms_clinicalrequest AS b ON a.refNumber=b.refNumber
	   WHERE b.formNUmber='$formNumber'";

$res=mysqlquery($sql);
$row=mysqlfetcharray($res);
echo $row[facilityID];