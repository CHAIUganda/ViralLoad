<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";

//task 1: get the logs for the form number changes
$formNumberArray=array();
$formNumberArray[]="014332/0815";
$formNumberArray[]="04301/1114";
$formNumberArray[]="1408/0011";
$formNumberArray[]="1408/0001";
$formNumberArray[]="1408/0002";
$formNumberArray[]="1408/0003";
$formNumberArray[]="1408/0005";
$formNumberArray[]="1408/0006";
$formNumberArray[]="1408/0007";
$formNumberArray[]="1408/0008";
$formNumberArray[]="1408/0010";
$formNumberArray[]="1408/0012";
$formNumberArray[]="1408/0013";
$formNumberArray[]="1408/0014";
$formNumberArray[]="1408/0015";
$formNumberArray[]="1408/0016";
$formNumberArray[]="1408/0017";
$formNumberArray[]="1408/0018";
$formNumberArray[]="1408/0019";
$formNumberArray[]="1408/0020";
$formNumberArray[]="1408/0021";
$formNumberArray[]="1408/0022";
$formNumberArray[]="1408/0023";
$formNumberArray[]="1408/0024";
$formNumberArray[]="1408/0025";
$formNumberArray[]="1408/0026";
$formNumberArray[]="1408/0027";
$formNumberArray[]="1408/0028";
$formNumberArray[]="1408/0029";
$formNumberArray[]="1408/0030";
$formNumberArray[]="1408/0031";
$formNumberArray[]="00141/0814";
$formNumberArray[]="00974/0914";
$formNumberArray[]="017177/1015";
$formNumberArray[]="017507/1015";
$formNumberArray[]="029631/0915";
$formNumberArray[]="019730/1115";
$formNumberArray[]="020770/1115";
$formNumberArray[]="021121/1115";
$formNumberArray[]="021216/1115";
$formNumberArray[]="022010/1115";
$formNumberArray[]="022106/1115";
$formNumberArray[]="022131/1115";
$formNumberArray[]="022188/1115";
$formNumberArray[]="022433/1115";
$formNumberArray[]="022552/1115";
$formNumberArray[]="022887/1115";
$formNumberArray[]="022909/1115";
$formNumberArray[]="023762/1115";
$formNumberArray[]="024397/1115";
$formNumberArray[]="025313/1115";
$formNumberArray[]="025367/1115";
$formNumberArray[]="025552/1115";
$formNumberArray[]="025895/1115";
$formNumberArray[]="027247/1115";

foreach($formNumberArray as $formNumber) {
	//log
	$id=0;
	$id=getDetailedTableInfo2("vl_samples","vlSampleID='$formNumber' limit 1","id");
	$fieldValueOld=0;
	$fieldValueOld=getDetailedTableInfo2("vl_logs_tables","tableName='vl_samples' and fieldName='formNumber' and fieldID='$id' order by created desc limit 1","fieldValueOld");
	$fieldValueNew=0;
	$fieldValueNew=getDetailedTableInfo2("vl_logs_tables","tableName='vl_samples' and fieldName='formNumber' and fieldID='$id' order by created desc limit 1","fieldValueNew");
	$created=0;
	$created=getDetailedTableInfo2("vl_logs_tables","tableName='vl_samples' and fieldName='formNumber' and fieldID='$id' order by created desc limit 1","created");
	$createdby=0;
	$createdby=getDetailedTableInfo2("vl_logs_tables","tableName='vl_samples' and fieldName='formNumber' and fieldID='$id' order by created desc limit 1","createdby");
	$url=0;
	$url=getDetailedTableInfo2("vl_logs_tables","tableName='vl_samples' and fieldName='formNumber' and fieldID='$id' order by created desc limit 1","url");
	
	echo "Form Number changed from $fieldValueOld to $fieldValueNew by $createdby on $created, through URL $url<br>";
}
?>