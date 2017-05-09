<?
//608174
//security check
$GLOBALS['vlDC']=true;
include "conf.php";
$cals = array('A','B','C','D','E','F','G');
$calibs = getDetailedTableInfo('vl_samples_worksheetcredentials','id',$worksheetID,'includeCalibrators');

$sql = "SELECT  s.vlSampleID
		FROM vl_samples_worksheet AS sw
		INNER JOIN vl_samples AS s ON sw.sampleID=s.id
		WHERE worksheetID=$worksheetID																																																										
		";
//echo $sql;
$results = mysqlquery($sql);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>Viral Load</title>
		<link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="/css/vl2.css" rel="stylesheet" type="text/css">

		<script src="/js/jquery-2.1.3.min.js"></script>
		<script src="/bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/js/jquery-barcode/jquery-barcode.js"></script>
	</head>
	<body>		
		<page size='barcode'><div class='barcode-wrapper'><div class='smple_barcodes' value='HIV_NEG'></div>HIV_NEG</div></page>
		<page size='barcode'><div class='barcode-wrapper'><div class='smple_barcodes' value='HIV_LOPOS'></div>HIV_LOPOS</div></page>
		<page size='barcode'><div class='barcode-wrapper'><div class='smple_barcodes' value='HIV_HIPOS'></div>HIV_HIPOS</div></page>		

		<?php 
		if($calibs==1){
			foreach ($cals as $cal) {
				echo "<div class='barcode-wrapper'><div class='smple_barcodes' value='HIV_CAL$cal'></div>HIV_CAL$cal</div>";
			}
		}
		while ($row = mysqlfetcharray($results)) {
			extract($row);
			echo "<page size='barcode'><div class='barcode-wrapper'><div class='smple_barcodes' value='$vlSampleID'></div>$vlSampleID</div></page>";
		}
		
		?>

		<script type="text/javascript">
		$(".smple_barcodes").each(function (index){
            var val = $(this).attr("value");
            $(this).barcode(val, "code128",{showHRI: false,posX: 4});
        });	

        //barHeight:20	
		
		//$(function(){ window.print(); });

		</script>

		<style type="text/css">
		page {
		  display: block;
		  margin: 0 auto;
		  margin-bottom: 8mm;
		  box-shadow: 0 0 2mm rgba(0,0,0,0.5);
		  margin-top: 5mm;
		}
		page[size="barcode"] {  
		  width: 43mm;
		  height: 20mm; 
		}
		.barcode-wrapper{
			text-align:center;
			width:130px;
			font-size: 10px;
		}

		</style>


		
	</body>
</html>
	
