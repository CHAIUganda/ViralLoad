<?
//608174
//security check
$GLOBALS['vlDC']=true;
include "conf.php";

$sql = "SELECT  s.*, p.artNumber,p.otherID, p.gender, p.dateOfBirth,
		GROUP_CONCAT(ph.phone SEPARATOR ',') AS phone, f.facility, f.contactPerson,f.phone AS facility_phone, d.district, h.hub AS hub_name, 		
		v.outcome AS verify_outcome, reason.appendix AS rejection_reason
		FROM vl_samples AS s
		LEFT JOIN vl_facilities AS f ON s.facilityID=f.id
		LEFT JOIN vl_districts AS d ON f.districtID=d.id
		LEFT JOIN vl_hubs AS h ON f.hubID=h.id
		LEFT JOIN vl_patients As p ON s.patientID=p.id
		LEFT JOIN vl_patients_phone As ph ON p.id = ph.patientID
		LEFT JOIN vl_samples_verify AS v ON s.id=v.sampleID				
		LEFT JOIN vl_appendix_samplerejectionreason AS reason ON v.outcomeReasonsID=reason.id
		WHERE date(v.created)='$date_rejected' AND v.outcome = 'Rejected'
		GROUP BY s.id																																																									
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
			<button id="print-btn" class='btn btn-primary' >PRINT</button>
			<button id="print-env-btn" class='btn btn-primary' >PRINT ENVELOPES</button>
		</div>
	
		<?php 
		$machine_type = "";
		$factor = "";
		$signature_path = "";
		$log_sql = "";
		$envs = [];
		while($row = mysqlfetcharray($results)){
			extract($row, EXTR_PREFIX_ALL, "row");
			include "_sample_print.php";

			$log_sql .= "('$row_id', '$datetime', '$trailSessionUser'),";
			
			$envs[$row_facilityID] = array(
				"hub"=>$row_hub, 
				"facility" => $row_facility,
				"district" => $row_district,
				"cp" => $row_contactPerson,
				"phone" => $row_facility_phone );
		}

		$log_sql = trim($log_sql, ",");


		if(!empty($log_sql)) mysqlquery("insert into vl_logs_printedrejectedresults (sampleID, created, createdby )  values $log_sql ");
		
		?>

		<?php 
		foreach ($envs AS $env) {
		?>
		<page class="env-container" style="display:none;font-weight:bolder;" size="A4">
			<div style="height:50%">
				<h1 style="text-align:right"><?=$env['hub']?></h1>
				<h2><?=$env['facility']?></h2>
				<h2>District: <?=$env['district']?></h2>
				<h2><?=$env['cp'] ?></h2>
				<h2><?=$env['phone'] ?></h2>
				<h4>c/o:<h4>
				<h4>Viral Load Results<h4>
			</div>
		</page>
		<?php
		}
		?>

		<script type="text/javascript">
		jQuery(function(){
			jQuery('.qrcode-output').each(function (index){
				var val = $(this).attr("value");
				$(this).qrcode({
					text: val,
					width: 75,
					height:75
				});
			});			
		});

		$('#print-btn').click(function(){
			$('#print-btn-div').hide();
			window.print();
		});

		$('#print-env-btn').click(function(){
			$('.result-container').hide();
			$('#print-btn-div').hide();
			$('.env-container').show();
			//$('page').attr('size','A5');
			window.print();			
			$('.result-container').show();
			$('#print-btn-div').show();
			$('.env-container').hide();
			//$('page').attr('size','A4');
			
		});

		</script>


		
	</body>
</html>
	
