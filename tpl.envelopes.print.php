<?
//608174
//security check
$GLOBALS['vlDC']=true;
include "conf.php";

$sql = "SELECT f.facility, f.contactPerson, f.phone, d.district, h.hub  
		FROM vl_logs_worksheetsamplesprinted AS p
		LEFT JOIN vl_samples AS s ON p.sampleID = s.id
		LEFT JOIN vl_facilities AS f ON s.facilityID = f.id
		LEFT JOIN vl_districts AS d ON f.districtID=d.id
		LEFT JOIN vl_hubs AS h ON f.hubID=h.id
		WHERE date(p.created)='$date_printed'
		GROUP BY f.id
		ORDER BY h.hub,f.facility ASC																																																									
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
		<script src="/js/jquery.qrcode.min.js" type="text/javascript"></script>
	</head>
	<body>
		<div id="print-btn-div" style='text-align:center; padding:20px;'>
			<button id="print-env-btn" class='btn btn-primary' >PRINT ENVELOPES</button>
		</div>

		<?php 
		while ($env = mysqlfetcharray($results)) {
		?>
		<page class="env-container" style="font-weight:bolder;" size="A4">
			<div style="height:50%">
				<h1 style="text-align:right"><?=$env['hub']?></h1>
				<br>
				<h2><?=$env['facility']?></h2>
				<h2>District: <?=$env['district']?></h2>
				<h2><?=$env['contactPerson'] ?></h2>
				<h2><?=$env['phone'] ?></h2>
				<h4>c/o:<h4>
				<h4>Viral Load Results<h4>
			</div>
		</page>
		<?php
		}
		?>

		<script type="text/javascript">
		$('#print-env-btn').click(function(){
			$('#print-btn-div').hide();
			window.print();
		});

		</script>


		
	</body>
</html>
	
