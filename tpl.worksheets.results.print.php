<?
//security check
$GLOBALS['vlDC']=true;
include "conf.php";

$sql0 = "SELECT signaturePATH, machineType, factor FROM vl_samples_worksheetcredentials AS w 
		 LEFT JOIN vl_users AS u ON w.createdby = u.email
		 LEFT JOIN vl_results_multiplicationfactor AS fctr ON w.id=fctr.worksheetID
		 WHERE w.id= $id";

$results0 = mysqlquery($sql0);
$wrksht_row = mysqlfetcharray($results0);

$machine_type = $wrksht_row['machineType'];
$factor = $wrksht_row['factor'];
$signature_path = $wrksht_row['signaturePATH'];

$sql = "SELECT sw.sampleID, s.*, p.artNumber,p.otherID, p.gender, p.dateOfBirth,
		GROUP_CONCAT(ph.phone SEPARATOR ',') AS phone, f.facility, d.district, h.hub AS hub_name, 
		GROUP_CONCAT(res_r.Result, '|||', res_r.created SEPARATOR '::') AS roche_result,
		GROUP_CONCAT(res_a.result, '|||', res_a.created SEPARATOR '::') AS abbott_result,
		GROUP_CONCAT(res_o.result, '|||', res_o.created SEPARATOR '::') AS override_result,
		log_s.id AS repeated, v.outcome AS verify_outcome, reason.appendix AS rejection_reason
		FROM vl_samples_worksheet AS sw
		LEFT JOIN vl_samples AS s ON sw.sampleID=s.id
		LEFT JOIN vl_samples_verify AS v ON s.id=v.sampleID
		LEFT JOIN vl_appendix_samplerejectionreason AS reason ON v.outcomeReasonsID=reason.id
		LEFT JOIN vl_facilities AS f ON s.facilityID=f.id
		LEFT JOIN vl_districts AS d ON f.districtID=d.id
		LEFT JOIN vl_hubs AS h ON f.hubID=h.id
		LEFT JOIN vl_patients As p ON s.patientID=p.id
		LEFT JOIN vl_patients_phone As ph ON p.id = ph.patientID
		LEFT JOIN vl_results_roche AS res_r ON s.vlSampleID = res_r.SampleID
		LEFT JOIN vl_results_abbott AS res_a ON s.vlSampleID = res_a.SampleID
		LEFT JOIN vl_results_override AS res_o ON s.vlSampleID = res_o.sampleID
		LEFT JOIN vl_logs_samplerepeats AS log_s ON s.id = log_s.sampleID
		WHERE sw.worksheetID=$id GROUP BY s.id	ORDER BY lrEnvelopeNumber,lrNumericID																																																											
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
		$print_log_sql="";
		while($row=mysqlfetcharray($results)){
			extract($row, EXTR_PREFIX_ALL, "row");
			include "_sample_print.php";
			$print_log_sql.="('$row_sampleID','$id','$datetime','$trailSessionUser'),";
		}

		$print_log_sql = trim($print_log_sql, ",");
		mysqlquery("insert into vl_logs_printedresults 
					(sampleID,worksheetID,created,createdby) 
					values $print_log_sql;");
		//mysqlquery("START TRANSACTION; $print_log_sql COMMIT;");
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
	
