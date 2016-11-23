<?php
$GLOBALS['vlDC']=true;
include "conf.php";

$sql=" SELECT s.id AS s_id, lrCategory, lrEnvelopeNumber, lrNumericID, vlSampleID, formNumber  
	   FROM vl_samples AS s
	   WHERE (formNumber LIKE '%$q%') 
	   LIMIT 10";

$res=mysqlquery($sql);
$str = "<table width=100%>";
$str .= "<tr><th>VL Testing #</th><th> Form Number</th><th>Location ID</th></tr>";
while($row = mysqlfetcharray($res)){
	extract($row);
	$link = "href=\"javascript:windPop('/sample_results/print/$s_id/')\"";
	$l_smpl_id = "<a $link>$vlSampleID</a>";
	$l_form = "<a $link>$formNumber</a>";
	$l_locator = "<a $link>$lrCategory$lrEnvelopeNumber$lrNumericID</a>";
	$str .= "<tr><td>$l_smpl_id</td><td>$l_form</td><td>$l_locator</td></tr>";
}
$str .= "</table>";
echo $str;
