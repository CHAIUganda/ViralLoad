<?
//security check
$GLOBALS['vlDC']=true;
include "conf.php";

$sql = "SELECT sampleID, s.*, p.artNumber,p.otherID, p.gender, p.dateOfBirth,
		GROUP_CONCAT(ph.phone SEPARATOR ',') AS phone, f.facility, d.district 
		FROM vl_samples_worksheet AS sw
		LEFT JOIN vl_samples AS s ON sw.sampleID=s.id
		LEFT JOIN vl_facilities AS f ON s.facilityID=f.id
		LEFT JOIN vl_districts AS d ON f.districtID=d.id
		LEFT JOIN vl_patients As p ON s.patientID=p.id
		LEFT JOIN vl_patients_phone As ph ON p.id = ph.patientID
		WHERE worksheetID=$id GROUP BY s.id																																																												
		";

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
	</head>
	<body>
		<?php 
		while($row=mysqlfetcharray($results)){
			extract($row, EXTR_PREFIX_ALL, "row");
			include "_sample_print.php";
		}
		?>
	</body>
</html>
	
