<?php
//security check
$GLOBALS['vlDC']=true;
include "conf.php";

date_default_timezone_set("Africa/Kampala");

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=test_outcome_report.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
extract($_GET);

$headers=["Form Number","Location ID","Sample ID","Facility","District","Hub","IP","Date of Collection"
,"Sample Type","Patient ART","Patient OtherID","Gender","Age (Years)","Phone Number","Has Patient Been on treatment for at least 6 months"
,"Date of Treatment Initiation","Current Regimen","Indication for Treatment Initiation","Which Treatment Line is Patient on"
,"Reason for Failure","Is Patient Pregnant","ANC Number","Is Patient Breastfeeding","Patient has Active TB"
,"If Yes are they on","ARV Adherence","Routine Monitoring","Last Viral Load Date"
,"Value","Sample Type ","Repeat Viral Load Test after Suspected Treatment Failure adherence counseling"
,"Last Viral Load Date ","Value ","Sample Type  ","Suspected Treatment Failure","Last Viral Load Date  ","Value  "
,"Sample Type   ","Tested","Last Worksheet","Machine Type","Result","Date/Time Approved","Date/Time Rejected"
,"Rejection Reason","Date/Time Added to Worksheet","Date/Time Latest Results Uploaded"
,"Date/Time Results Printed","Date Received at CPHL","Date/Time First Printed","Date/Time Sample was Captured"];

fputcsv($output, $headers);


$fro_s=isset($fro_s)?$fro_s-(3*60*60):date("Y-m-01 00:00:00");
$to_s=isset($to_s)?$to_s+(21*60*60)-1:date("Y-m-d 23:59:59");

$sql="  SELECT s.*,s.id AS s_id,facility,district,hub,ip,
			s_type.appendix AS sample_type,
			artNumber,otherID,gender,GROUP_CONCAT( ph.phone SEPARATOR ', ') AS phone,
			regimen.appendix AS current_regimen,
			trtmt_init.appendix AS treatment_indication,
			adhere.appendix AS arv_adherence,
			s_type_rm.appendix AS sample_type_rm,
			s_type_rp.appendix AS sample_type_rp,
			s_type_st.appendix AS sample_type_st,
			r.machine,verify.outcome AS approval_status,verify.created AS approval_time,
			rjct.appendix AS rejection_reason,
			(UNIX_TIMESTAMP(s.created)-UNIX_TIMESTAMP(p.dateOfBirth))/31536000 AS age,
			s_regimen.otherRegimen,a_regimen.appendix AS trmt_line,
			af_reason.appendix AS failure_reason,
			r.vlSampleID AS tested,print.created AS print_date,
			wksht.created AS wksht_date,GROUP_CONCAT(wksht.worksheetID SEPARATOR ',') AS worksheetID,r.created AS res_date,
			r.resultAlphanumeric
		FROM vl_samples AS s 
		LEFT JOIN vl_patients AS p ON s.patientID=p.id
		LEFT JOIN vl_results_merged AS r ON s.vlSampleID=r.vlSampleID
		LEFT JOIN vl_facilities AS f ON s.facilityID=f.id
		LEFT JOIN vl_districts AS d ON s.districtID=d.id
		LEFT JOIN vl_hubs AS h ON s.hubID=h.id
		LEFT JOIN vl_ips AS ip ON f.ipID=ip.id
		LEFT JOIN vl_appendix_sampletype AS s_type ON s.sampleTypeID=s_type.id
		LEFT JOIN vl_patients_phone AS ph ON p.id=ph.patientID
		LEFT JOIN vl_appendix_regimen AS regimen ON s.currentRegimenID=regimen.id
		LEFT JOIN vl_appendix_treatmentinitiation AS trtmt_init ON s.treatmentInitiationID=trtmt_init.id
		LEFT JOIN vl_appendix_arvadherence AS adhere ON s.arvAdherenceID=adhere.id
		LEFT JOIN vl_appendix_sampletype AS s_type_rm ON s.routineMonitoringSampleTypeID=s_type_rm.id
		LEFT JOIN vl_appendix_sampletype AS s_type_rp ON s.repeatVLTestSampleTypeID=s_type_rp.id
		LEFT JOIN vl_appendix_sampletype AS s_type_st ON s.suspectedTreatmentFailureSampleTypeID=s_type_st.id
		LEFT JOIN vl_samples_verify AS verify ON s.id=verify.sampleID
		LEFT JOIN vl_appendix_samplerejectionreason AS rjct ON verify.outcomeReasonsID=rjct.id
		LEFT JOIN vl_samples_otherregimen AS s_regimen ON s.id=s_regimen.sampleID
		LEFT JOIN vl_appendix_regimen AS a_regimen ON s_regimen.currentRegimenID=a_regimen.id
		LEFT JOIN vl_appendix_failurereason AS af_reason ON s.reasonForFailureID=af_reason.id
		LEFT JOIN vl_logs_printedresults AS print ON s.id=print.sampleID
		LEFT JOIN vl_samples_worksheet AS wksht ON s.id=wksht.sampleID
		WHERE UNIX_TIMESTAMP(s.created) BETWEEN $fro_s AND $to_s
		GROUP BY s.id";

$res = mysqlquery($sql);

// loop over the rows, outputting them
while($row=mysqlfetcharray($res)){
	extract($row);
	$row2=[];

$row2["Form Number"]=$formNumber;
$row2["Location ID"]=$vlSampleID;
$row2["Sample ID"]=$s_id;
$row2["Facility"]=$facility;
$row2["District"]=$district;
$row2["Hub"]=$hub;
$row2["IP"]=$ip;
$row2["Date of Collection"]=$collectionDate;
$row2["Sample Type"]=$sample_type;
$row2["Patient ART"]=$artNumber;
$row2["Patient OtherID"]=$otherID;
$row2["Gender"]=$gender;
$row2["Age (Years)"]=round($age,1);
$row2["Phone Number"]=$phone;
$row2["Has Patient Been on treatment for at least 6 months"]=$treatmentLast6Months;
$row2["Date of Treatment Initiation"]=$treatmentInitiationDate;
$row2["Current Regimen"]=$current_regimen;
$row2["Indication for Treatment Initiation"]=!empty($treatment_indication)?$treatment_indication:$treatmentInitiationOther;
$row2["Which Treatment Line is Patient on"]=!empty($trmt_line)?$trmt_line:$otherRegimen;
$row2["Reason for Failure"]=$failure_reason;
$row2["Is Patient Pregnant"]=$pregnant;
$row2["ANC Number"]=$pregnantANCNumber;
$row2["Is Patient Breastfeeding"]=$breastfeeding;
$row2["Patient has Active TB"]=$activeTBStatus;
$row2["If Yes are they on"]="";
$row2["ARV Adherence"]=$arv_adherence;
$row2["Routine Monitoring"]=$vlTestingRoutineMonitoring;
$row2["Last Viral Load Date"]=$routineMonitoringLastVLDate;

$row2["Value"]=$routineMonitoringValue;
$row2["Sample Type "]=$sample_type_rm;
$row2["Repeat Viral Load Test after Suspected Treatment Failure adherence counseling"]=$vlTestingRepeatTesting;
$row2["Last Viral Load Date "]=$repeatVLTestLastVLDate;
$row2["Value "]=$repeatVLTestValue;
$row2["Sample Type  "]=$sample_type_rp;
$row2["Suspected Treatment Failure"]=$vlTestingSuspectedTreatmentFailure;
$row2["Last Viral Load Date   "]=$suspectedTreatmentFailureLastVLDate;
$row2["Value  "]=$suspectedTreatmentFailureValue;
$row2["Sample Type   "]=$sample_type_st;
$row2["Tested"]=!empty($tested)?"Yes":"No";
$w_arr=explode(",", $worksheetID);
$row2["Last Worksheet"]=max($w_arr);
$row2["Machine Type"]=$machine;
$row2["Result"]=$resultAlphanumeric;
$row2["Date/Time Approved"]=$approval_status=='Accepted'?$approval_time:"";
$row2["Date/Time Rejected"]=$approval_status=='Rejected'?$approval_time:"";
$row2["Rejection Reason"]=$rejection_reason;
$row2["Date/Time Added to Worksheet"]=$wksht_date;
$row2["Date/Time Latest Results Uploaded"]=$res_date;
$row2["Date/Time Results Printed"]=$print_date;
$row2["Date Received at CPHL"]=$receiptDate;
$row2["Date/Time First Printed"]=$res_date;
$row2["Date/Time Sample was Captured"]=$created;
	$i++;
fputcsv($output, $row2);	
}	
?>