<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";

//get variables
$type=getValidatedVariable("type");
$printID=getValidatedVariable("printID");
$controls=0;

if($type=="roche") {
	$printType="printIDRoche";
	$controls=$default_numberControlsRoche;
} else {
	$printType="printIDAbbott";
	$type="abbott";
	$controls=$default_numberControlsAbbott;
}

//prepare the contents
$contents=array();

for($i=1;$i<=$controls;$i++) {
	if($i==1) {
    	$pc="<div align=\"center\" class=\"vl\">1</div>
				<div align=\"center\">Negative Control<br><strong>NC</strong></div>";
	} elseif($i==2) {
		$pc="<div align=\"center\" class=\"vl\">2</div>
				<div align=\"center\">Positive Control<br><strong>PC</strong></div>";
	} elseif($i==3) {
		$pc="<div align=\"center\" class=\"vl\">3</div>
				<div align=\"center\"><strong>HPC</strong></div>";
	}
	$contents[]=$pc;
}

//how many samples are to be printed?
$numberSamples=0;
$numberSamples=getDetailedTableInfo3("vl_samples_verify","$printType='$printID'","count(id)","num");
for($i=1;$i<=$numberSamples;$i++) {
	//key variables
	$sampleID=0;
	$sampleID=getDetailedTableInfo2("vl_samples_verify","$printType='$printID' order by created limit ".($i-1).",1","sampleID");
	$patientID=0;
	$patientID=getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","patientID");
	$sampleNumber=0;
	$sampleNumber=getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","vlSampleID");
	$patientART=0;
	$patientART=getDetailedTableInfo2("vl_patients","id='$patientID' limit 1","artNumber");
	
	//log viewed status
	if(!getDetailedTableInfo2("vl_logs_worksheetsamplesprinted","sampleID='$sampleID' and worksheetType='$type' limit 1","id")) {
		mysqlquery("insert into vl_logs_worksheetsamplesprinted 
						(sampleID,worksheetType,created,createdby) 
						values 
						('$sampleID','$type','$datetime','$trailSessionUser')");
	}
	
	$contents[]="<div align=\"center\" class=\"vls\">".($i+$controls)."</div>
					<div align=\"center\" class=\"vls\" style=\"padding:3px 0px 0px 0px\">Patient ART #: $patientART</div> 
					<div align=\"center\" class=\"vls\" style=\"padding:1px 0px 0px 0px\">Sample #: $sampleNumber</div> 
					<div align=\"center\" style=\"padding:5px 0px\"><img src=\"$barcodes_url".vlEncrypt($sampleNumber).".jpg\" /></div>";
}

//generate form
$html=0;
$html="<style type=\"text/css\">
<!--
.vl {
	font-family:Arial,Helvetica,sans-serif;
	font-size:12px;
	color:#3c3c3c;
}
.vl_red {
	font-family:Arial,Helvetica,sans-serif;
	font-size:12px;
	color:#F00;
}
.vl11 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #333333;
}
.vl11_grey {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #CCC;
}
.vl10 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #333333;
}
.vl10_grey {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #CCC;
}
-->
</style>

<link href=\"/css/vl.css\" rel=\"stylesheet\" type=\"text/css\">

<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
  <tr>
    <td style=\"padding:20px; border: 1px solid #666666\">
        <div class=\"vl\" style=\"padding:0px 0px 10px 0px; border-bottom: 1px dashed #cccccc\">
            <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vls\">
              <tr>
                <td style=\"padding:0px 0px 3px 0px\"><strong>Machine:</strong> ".($type=="abbott"?"Abbott":"Roche")."</td>
              </tr>
              <tr>
                <td style=\"padding:0px 0px 3px 0px\"><strong>Worksheet Reference Number:</strong> $printID</td>
              </tr>";

		if($type=="roche") {
			$html.="<tr>
					<td style=\"padding:5px 0px 5px 0px; border-top: 1px dashed #cccccc\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vls\">
					  <tr style=\"background-color:#e8e8e8\">
						<td width=\"50%\" align=\"center\" style=\"padding: 5px; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc\"><strong>HI2CAP</strong></td>
						<td width=\"50%\" align=\"center\" style=\"padding: 5px; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc; border-right: 1px solid #ccc\"><strong>Bulk Lysis Buffer</strong></td>
					  </tr>
					  <tr>
						<td align=\"center\" style=\"padding: 5px; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc\">".(getDetailedTableInfo2("vl_samples_worksheetcredentials","worksheetType='$type' and worksheetID='$printID' limit 1","id")?getDetailedTableInfo2("vl_samples_worksheetcredentials","worksheetType='$type' and worksheetID='$printID' limit 1","samplePrep"):"&nbsp;")."</td>
						<td align=\"center\" style=\"padding: 5px; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc; border-right: 1px solid #ccc\">".(getDetailedTableInfo2("vl_samples_worksheetcredentials","worksheetType='$type' and worksheetID='$printID' limit 1","id")?getDetailedTableInfo2("vl_samples_worksheetcredentials","worksheetType='$type' and worksheetID='$printID' limit 1","bulkLysisBuffer"):"&nbsp;")."</td>
					  </tr>
					</table></td>
				  </tr>";
		} elseif($type=="abbott") {
			$html.="<tr>
					<td style=\"padding:5px 0px 5px 0px; border-top: 1px dashed #cccccc\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vls\">
					  <tr style=\"background-color:#e8e8e8\">
						<td width=\"20%\" align=\"center\" style=\"padding: 5px; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc\"><strong>Sample Prep</strong></td>
						<td width=\"20%\" align=\"center\" style=\"padding: 5px; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc\"><strong>Bulk Lysis Buffer</strong></td>
						<td width=\"20%\" align=\"center\" style=\"padding: 5px; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc\"><strong>Control</strong></td>
						<td width=\"20%\" align=\"center\" style=\"padding: 5px; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc\"><strong>Calibrator</strong></td>
						<td width=\"20%\" align=\"center\" style=\"padding: 5px; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc; border-right: 1px solid #ccc\"><strong>Amplification Kit</strong></td>
					  </tr>
					  <tr>
						<td align=\"center\" style=\"padding: 5px; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc\">".(getDetailedTableInfo2("vl_samples_worksheetcredentials","worksheetType='$type' and worksheetID='$printID' limit 1","id")?getDetailedTableInfo2("vl_samples_worksheetcredentials","worksheetType='$type' and worksheetID='$printID' limit 1","samplePrep"):"&nbsp;")."</td>
						<td align=\"center\" style=\"padding: 5px; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc\">".(getDetailedTableInfo2("vl_samples_worksheetcredentials","worksheetType='$type' and worksheetID='$printID' limit 1","id")?getDetailedTableInfo2("vl_samples_worksheetcredentials","worksheetType='$type' and worksheetID='$printID' limit 1","bulkLysisBuffer"):"&nbsp;")."</td>
						<td align=\"center\" style=\"padding: 5px; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc\">".(getDetailedTableInfo2("vl_samples_worksheetcredentials","worksheetType='$type' and worksheetID='$printID' limit 1","id")?getDetailedTableInfo2("vl_samples_worksheetcredentials","worksheetType='$type' and worksheetID='$printID' limit 1","control"):"&nbsp;")."</td>
						<td align=\"center\" style=\"padding: 5px; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc\">".(getDetailedTableInfo2("vl_samples_worksheetcredentials","worksheetType='$type' and worksheetID='$printID' limit 1","id")?getDetailedTableInfo2("vl_samples_worksheetcredentials","worksheetType='$type' and worksheetID='$printID' limit 1","calibrator"):"&nbsp;")."</td>
						<td align=\"center\" style=\"padding: 5px; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc; border-right: 1px solid #ccc\">".(getDetailedTableInfo2("vl_samples_worksheetcredentials","worksheetType='$type' and worksheetID='$printID' limit 1","id")?getDetailedTableInfo2("vl_samples_worksheetcredentials","worksheetType='$type' and worksheetID='$printID' limit 1","amplificationKit"):"&nbsp;")."</td>
					  </tr>
					</table></td>
				  </tr>";
		}
		 
    	$html.="</table>
        </div>
        <div class=\"vl\" style=\"padding:10px 0px 0px 0px\">
			<table border=\"0\" class=\"vl\">";
		
		//abbott has 12 columns, so start by getting total number of rows
		$numberRows=0;
		$numberRows=ceil(count($contents)/12);
		$dataCounter=0;
		
		for($i=1;$i<=$numberRows;$i++) {
			//start row
			$html.="<tr style=\"background:#dddddd\">";
			//populate the cells
			for($j=1;$j<=12;$j++) {
				if($dataCounter<count($contents)) {
					$html.="<td bgcolor=\"#dddddd\" style=\"padding:5px\">$contents[$dataCounter]</td>";
				} else {
					$html.="<td bgcolor=\"#dddddd\" style=\"padding:5px\">&nbsp;</td>";
				}
				$dataCounter+=1;
			}
			//end row
			$html.="</tr>";
		}
		
		$html.="</table>
        </div>
	</td>
  </tr>
</table>";

//filename
$filename=0;
$filename="Worksheet.$type.$printID.pdf";

/*
//load PDF object
$pdf = new DOMPDF();
$pdf->load_html($html);
$pdf->set_paper("letter", "landscape");
//$pdf->set_paper("letter", "portrait");
$pdf->render();
$pdf->stream($filename, array("Attachment" => false));
*/
echo $html;
?>