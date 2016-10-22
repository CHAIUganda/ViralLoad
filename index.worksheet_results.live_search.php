<?php
$GLOBALS['vlDC']=true;
include "conf.php";

$sql=" SELECT id,worksheetReferenceNumber FROM vl_samples_worksheetcredentials
	   WHERE worksheetReferenceNumber LIKE '%$q%' LIMIT 10";

$res=mysqlquery($sql);
$str = "";
while($row = mysqlfetcharray($res)){
	extract($row);
	$str .= "<a href=\"javascript:windPop('/worksheet_results/print/$id/')\">$worksheetReferenceNumber</a><br>";
}
echo $str;