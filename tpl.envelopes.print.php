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
		
		<?php 
		while ($env = mysqlfetcharray($results)) {
		?>
		<page size="A4">
			<div style="margin-left:20px;"> 
				<h2>From: CPHL</h2>
				
				<div class="row">
					<div class="col-xs-1"><h2>To:</h2></div>
					<div class="col-xs-7">
						<div style="display:inline-block; margin-left:100px;">
							<img src="/images/hub_bike.png">
							<span style="font-weight:bolder; font-size:32px;vertical-align: bottom;"><?=$env['hub']?></span>
						</div>
						
						<h2><?=$env['facility']?></h2>
						<h2>District: <?=$env['district']?></h2><br><br><br>
						<h3 style="font-weight:bold;">Viral Load Results</h3>
					</div>
					
					<h4 style="margin-top:100px;margin-left:600px;margin-right:-100px;transform:rotate(90deg);"> <?=$env['hub']?> &nbsp; <?=$env['facility']?></h4>
					

				</div>	
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
	
