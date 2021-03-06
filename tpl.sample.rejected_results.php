<?
//608174
//security check
$GLOBALS['vlDC']=true;
include "conf.php";

$sql = "SELECT  s.*, p.artNumber,p.otherID, p.gender, p.dateOfBirth,
		GROUP_CONCAT(ph.phone SEPARATOR ',') AS phone, f.facility, d.district, h.hub AS hub_name, 		
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
		<div id="print-btn-div" style='text-align:center; padding:20px;'><button id="print-btn" class='btn btn-primary' >PRINT</button></div>
	
		<?php 
		$machine_type = "";
		$factor = "";
		$signature_path = "";
		$log_sql = "";
		while($row = mysqlfetcharray($results)){
			extract($row, EXTR_PREFIX_ALL, "row");
			include "_sample_print.php";

			$log_sql .= "('$row_id', '$datetime', '$trailSessionUser'),";
		}

		$log_sql = trim($log_sql, ",");


		if(!empty($log_sql)) mysqlquery("insert into vl_logs_printedrejectedresults (sampleID, created, createdby )  values $log_sql ");
		
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

		</script>


		
	</body>
</html>
	
