<?
//register a globals variable for security
header("Access-Control-Allow-Origin: *");
$GLOBALS['vlDC']=true;
include "conf.php";

/*
* access this page by visiting, for example 
* http://vl.trailanalytics.com/json/district/amg299281fmlasd5dc02bd238919260fg6ad261d094zafd9/
* 
* step 1: ran a basic authentication on the user
* step 2: direct the user to the appropriate case statement
*/

//authenticate
if($token=="amg299281fmlasd5dc02bd238919260fg6ad261d094zafd9") {
	switch($option) {
		case "regions":
			//get regions
			$query=0;
			$query=mysqlquery("select * from vl_regions order by region");
			if(mysqlnumrows($query)) {
				$array=array();
				while($q=mysqlfetcharray($query)) {
					$subarray=array();
					$subarray["id"]=$q["id"];
					$subarray["region"]=$q["region"];
					$subarray["created"]=$q["created"];
					$subarray["createdby"]=$q["createdby"];
					$array[]=$subarray;
				}
				//prepare json string
				echo json_encode($array);
			}
		break;
		case "districts":
			//get districts
			$query=0;
			$query=mysqlquery("select * from vl_districts order by district");
			if(mysqlnumrows($query)) {
				$array=array();
				while($q=mysqlfetcharray($query)) {
					$subarray=array();
					$subarray["id"]=$q["id"];
					$subarray["district"]=$q["district"];
					$subarray["regionID"]=$q["regionID"];
					$subarray["created"]=$q["created"];
					$subarray["createdby"]=$q["createdby"];
					$array[]=$subarray;
				}
				//prepare json string
				echo json_encode($array);
			}
		break;
		case "hubs":
			//get hubs
			$query=0;
			$query=mysqlquery("select * from vl_hubs order by hub");
			if(mysqlnumrows($query)) {
				$array=array();
				while($q=mysqlfetcharray($query)) {
					$subarray=array();
					$subarray["id"]=$q["id"];
					$subarray["hub"]=$q["hub"];
					$subarray["ipID"]=$q["ipID"];
					$subarray["created"]=$q["created"];
					$subarray["createdby"]=$q["createdby"];
					$array[]=$subarray;
				}
				//prepare json string
				echo json_encode($array);
			}
		break;
		case "ips":
			//get ips
			$query=0;
			$query=mysqlquery("select * from vl_ips order by ip");
			if(mysqlnumrows($query)) {
				$array=array();
				while($q=mysqlfetcharray($query)) {
					$subarray=array();
					$subarray["id"]=$q["id"];
					$subarray["ip"]=$q["ip"];
					$subarray["created"]=$q["created"];
					$subarray["createdby"]=$q["createdby"];
					$array[]=$subarray;
				}
				//prepare json string
				echo json_encode($array);
			}
		break;
		case "facilities":
			//get facilities
			$query=0;
			$query=mysqlquery("select * from vl_facilities order by facility");
			if(mysqlnumrows($query)) {
				$array=array();
				while($q=mysqlfetcharray($query)) {
					$subarray=array();
					$subarray["id"]=$q["id"];
					$subarray["facility"]=$q["facility"];
					$subarray["districtID"]=$q["districtID"];
					$subarray["hubID"]=$q["hubID"];
					$subarray["ipID"]=$q["ipID"];
					$subarray["phone"]=$q["phone"];
					$subarray["email"]=$q["email"];
					$subarray["contactPerson"]=$q["contactPerson"];
					$subarray["physicalAddress"]=$q["physicalAddress"];
					$subarray["returnAddress"]=$q["returnAddress"];
					$subarray["active"]=$q["active"];
					$subarray["created"]=$q["created"];
					$subarray["createdby"]=$q["createdby"];
					$array[]=$subarray;
				}
				//prepare json string
				echo json_encode($array);
			}
		break;
		case "data":
			//get the data
			$query=0;
			$query=mysqlquery("select distinct month(a.created) theMonth,year(a.created) theYear,a.facilityID facilityID,
											CASE 
												WHEN round(datediff(now(),b.dateOfBirth)/365)<5 then 1
												WHEN round(datediff(now(),b.dateOfBirth)/365)>=5 and round(datediff(now(),b.dateOfBirth)/365)<=9 then 2
												WHEN round(datediff(now(),b.dateOfBirth)/365)>=10 and round(datediff(now(),b.dateOfBirth)/365)<=18 then 3
												WHEN round(datediff(now(),b.dateOfBirth)/365)>=19 and round(datediff(now(),b.dateOfBirth)/365)<=25 then 4
												ELSE 5
											END theAgeCategory 
												from vl_samples a,vl_patients b where a.patientID=b.id order by a.created");
			if(mysqlnumrows($query)) {
				//sample type IDs
				$dbsSampleTypeID=0;
				$dbsSampleTypeID=getDetailedTableInfo2("vl_appendix_sampletype","appendix='DBS' limit 1","id");
				$plasmaSampleTypeID=0;
				$plasmaSampleTypeID=getDetailedTableInfo2("vl_appendix_sampletype","appendix='Plasma' limit 1","id");
				
				$array=array();
				while($q=mysqlfetcharray($query)) {
					$subarray=array();
					$subarray["month"]=$q["theMonth"];
					$subarray["year"]=$q["theYear"];
					$subarray["facility_id"]=$q["facilityID"];
					$subarray["age_group_id"]=$q["theAgeCategory"];
					
					//process last portion of output based on age category
					switch($q["theAgeCategory"]) {
						case 1: //age is < 5
							//samples_received
							$subarray["samples_received"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)<5","count(a.id)","num");
							//valid_results
							$subarray["valid_results"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_results_merged c","a.patientID=b.id and a.vlSampleID=c.vlSampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)<5 and 
																				c.resultAlphanumeric!='Invalid test result. There is insufficient sample to repeat the assay.' and 
																					c.resultAlphanumeric!='There is No Result Given. The Test Failed the Quality Control Criteria. We advise you send a a new sample.' and 
																						c.resultAlphanumeric!='Failed.' and 
																							c.resultAlphanumeric!='Failed' and 
																								c.resultAlphanumeric!='Invalid' and 
																									(c.resultAlphanumeric='Not detected' or 
																										c.resultAlphanumeric='Target Not Detected' or 
																											(c.resultNumeric!='' and c.resultNumeric!='0'))","count(distinct a.id)","num");
							//rejected_samples
							$subarray["rejected_samples"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_samples_verify c","a.patientID=b.id and a.id=c.sampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)<5 and c.outcome='Rejected'","count(distinct a.id)","num");
							//suppressed
							$subarray["suppressed"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_results_merged c","a.patientID=b.id and a.vlSampleID=c.vlSampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)<5 and 
																			((a.sampleTypeID='$dbsSampleTypeID' and 
																				(c.resultAlphanumeric='Not detected' or 
																					c.resultAlphanumeric='Target Not Detected' or 
																						(c.resultNumeric!='' and c.resultNumeric!='0' and c.resultNumeric<=5000))) or 
																							(a.sampleTypeID='$plasmaSampleTypeID' and 
																								(c.resultAlphanumeric='Not detected' or 
																									c.resultAlphanumeric='Target Not Detected' or 
																										(c.resultNumeric!='' and c.resultNumeric!='0' and c.resultNumeric<=1000))))","count(distinct a.id)","num");
							//dbs_samples
							$subarray["dbs_samples"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)<5 and a.sampleTypeID='$dbsSampleTypeID'","count(distinct a.id)","num");
							//total_results
							$subarray["total_results"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)<5 and a.vlSampleID in (select vlSampleID from vl_results_merged)","count(distinct a.id)","num");
							//sample_quality_rejections
							$subarray["sample_quality_rejections"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_samples_verify c","a.patientID=b.id and a.id=c.sampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)<5 and c.outcome='Rejected' and 
																			(c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='All Dry Blood Spots on the card less than the required size i.e not filling the perforated area' limit 1","id")."' or 
																				c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='DBS Sample older than 3 weeks' limit 1","id")."' or 
																					c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='DBS sample sent on unperforated card.' limit 1","id")."' or 
																						c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='DBS sample sent on wrong Card' limit 1","id")."' or 
																							c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Dry Blood sample sent with less than 2 spots' limit 1","id")."' or 
																								c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Sample not recieved at CPHL Lab' limit 1","id")."' or 
																									c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Sent a wet sample' limit 1","id")."' or 
																										c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Specimen Heamolysed' limit 1","id")."' or 
																											c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Specimen integrity compromised e.g DBS card dirty,dry blood spots with moulds' limit 1","id")."' or 
																												c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Specimen Less than 0.75ml' limit 1","id")."' or 
																													c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Specimen not labeled' limit 1","id")."')","count(distinct a.id)","num");
							//eligibility_rejections
							$subarray["eligibility_rejections"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_samples_verify c","a.patientID=b.id and a.id=c.sampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)<5 and c.outcome='Rejected' and 
																			(c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Patient\'s last viral load result given less than 6 months ago' limit 1","id")."' or 
																				c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Patient has been on ARVs for less than 6 months' limit 1","id")."' or 
																					c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Patient not on ART' limit 1","id")."')","count(distinct a.id)","num");
							//incomplete_form_rejections
							$subarray["incomplete_form_rejections"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_samples_verify c","a.patientID=b.id and a.id=c.sampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)<5 and c.outcome='Rejected' and 
																			(c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Mismatching Specimen identifiers on request form and sample' limit 1","id")."' or 
																				c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='No date of birth' limit 1","id")."' or 
																					c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='No date of treatment initiation' limit 1","id")."' or 
																						c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Sample has wrong date of treatment initiation' limit 1","id")."' or 
																							c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Wrong requistion form with insufficient mandatory information' limit 1","id")."')","count(distinct a.id)","num");
							//cd4_less_than_500
							$subarray["cd4_less_than_500"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)<5 and a.treatmentInitiationID='".getDetailedTableInfo2("vl_appendix_treatmentinitiation","appendix='CD4<500' limit 1","id")."'","count(a.id)","num");
							//pmtct_option_b_plus
							$subarray["pmtct_option_b_plus"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)<5 and a.treatmentInitiationID='".getDetailedTableInfo2("vl_appendix_treatmentinitiation","appendix='PMTCT/Option B+' limit 1","id")."'","count(a.id)","num");
							//children_under_15
							$subarray["children_under_15"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)<5 and a.treatmentInitiationID='".getDetailedTableInfo2("vl_appendix_treatmentinitiation","appendix='Child under 15' limit 1","id")."'","count(a.id)","num");
							//other_treatment
							$subarray["other_treatment"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)<5 and a.treatmentInitiationID='".getDetailedTableInfo2("vl_appendix_treatmentinitiation","appendix='Other' limit 1","id")."'","count(a.id)","num");
							//treatment_blank_on_form
							$subarray["treatment_blank_on_form"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)<5 and (a.treatmentInitiationID='' or a.treatmentInitiationID='0')","count(a.id)","num");
						break;
						case 2: //age >= 5 && age <= 9
							//samples_received
							$subarray["samples_received"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=5 and round(datediff(now(),b.dateOfBirth)/365)<=9","count(a.id)","num");
							//valid_results
							$subarray["valid_results"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_results_merged c","a.patientID=b.id and a.vlSampleID=c.vlSampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=5 and round(datediff(now(),b.dateOfBirth)/365)<=9 and 
																				c.resultAlphanumeric!='Invalid test result. There is insufficient sample to repeat the assay.' and 
																					c.resultAlphanumeric!='There is No Result Given. The Test Failed the Quality Control Criteria. We advise you send a a new sample.' and 
																						c.resultAlphanumeric!='Failed.' and 
																							c.resultAlphanumeric!='Failed' and 
																								c.resultAlphanumeric!='Invalid' and 
																									(c.resultAlphanumeric='Not detected' or 
																										c.resultAlphanumeric='Target Not Detected' or 
																											(c.resultNumeric!='' and c.resultNumeric!='0'))","count(distinct a.id)","num");
							//rejected_samples
							$subarray["rejected_samples"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_samples_verify c","a.patientID=b.id and a.id=c.sampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=5 and round(datediff(now(),b.dateOfBirth)/365)<=9 and c.outcome='Rejected'","count(distinct a.id)","num");
							//suppressed
							$subarray["suppressed"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_results_merged c","a.patientID=b.id and a.vlSampleID=c.vlSampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=5 and round(datediff(now(),b.dateOfBirth)/365)<=9 and 
																			((a.sampleTypeID='$dbsSampleTypeID' and 
																				(c.resultAlphanumeric='Not detected' or 
																					c.resultAlphanumeric='Target Not Detected' or 
																						(c.resultNumeric!='' and c.resultNumeric!='0' and c.resultNumeric<=5000))) or 
																							(a.sampleTypeID='$plasmaSampleTypeID' and 
																								(c.resultAlphanumeric='Not detected' or 
																									c.resultAlphanumeric='Target Not Detected' or 
																										(c.resultNumeric!='' and c.resultNumeric!='0' and c.resultNumeric<=1000))))","count(distinct a.id)","num");
							//dbs_samples
							$subarray["dbs_samples"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=5 and round(datediff(now(),b.dateOfBirth)/365)<=9 and a.sampleTypeID='$dbsSampleTypeID'","count(distinct a.id)","num");
							//total_results
							$subarray["total_results"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=5 and round(datediff(now(),b.dateOfBirth)/365)<=9 and a.vlSampleID in (select vlSampleID from vl_results_merged)","count(distinct a.id)","num");
							//sample_quality_rejections
							$subarray["sample_quality_rejections"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_samples_verify c","a.patientID=b.id and a.id=c.sampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=5 and round(datediff(now(),b.dateOfBirth)/365)<=9 and c.outcome='Rejected' and 
																			(c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='All Dry Blood Spots on the card less than the required size i.e not filling the perforated area' limit 1","id")."' or 
																				c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='DBS Sample older than 3 weeks' limit 1","id")."' or 
																					c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='DBS sample sent on unperforated card.' limit 1","id")."' or 
																						c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='DBS sample sent on wrong Card' limit 1","id")."' or 
																							c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Dry Blood sample sent with less than 2 spots' limit 1","id")."' or 
																								c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Sample not recieved at CPHL Lab' limit 1","id")."' or 
																									c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Sent a wet sample' limit 1","id")."' or 
																										c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Specimen Heamolysed' limit 1","id")."' or 
																											c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Specimen integrity compromised e.g DBS card dirty,dry blood spots with moulds' limit 1","id")."' or 
																												c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Specimen Less than 0.75ml' limit 1","id")."' or 
																													c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Specimen not labeled' limit 1","id")."')","count(distinct a.id)","num");
							//eligibility_rejections
							$subarray["eligibility_rejections"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_samples_verify c","a.patientID=b.id and a.id=c.sampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=5 and round(datediff(now(),b.dateOfBirth)/365)<=9 and c.outcome='Rejected' and 
																			(c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Patient\'s last viral load result given less than 6 months ago' limit 1","id")."' or 
																				c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Patient has been on ARVs for less than 6 months' limit 1","id")."' or 
																					c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Patient not on ART' limit 1","id")."')","count(distinct a.id)","num");
							//incomplete_form_rejections
							$subarray["incomplete_form_rejections"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_samples_verify c","a.patientID=b.id and a.id=c.sampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=5 and round(datediff(now(),b.dateOfBirth)/365)<=9 and c.outcome='Rejected' and 
																			(c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Mismatching Specimen identifiers on request form and sample' limit 1","id")."' or 
																				c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='No date of birth' limit 1","id")."' or 
																					c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='No date of treatment initiation' limit 1","id")."' or 
																						c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Sample has wrong date of treatment initiation' limit 1","id")."' or 
																							c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Wrong requistion form with insufficient mandatory information' limit 1","id")."')","count(distinct a.id)","num");
							//cd4_less_than_500
							$subarray["cd4_less_than_500"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=5 and round(datediff(now(),b.dateOfBirth)/365)<=9 and a.treatmentInitiationID='".getDetailedTableInfo2("vl_appendix_treatmentinitiation","appendix='CD4<500' limit 1","id")."'","count(a.id)","num");
							//pmtct_option_b_plus
							$subarray["pmtct_option_b_plus"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=5 and round(datediff(now(),b.dateOfBirth)/365)<=9 and a.treatmentInitiationID='".getDetailedTableInfo2("vl_appendix_treatmentinitiation","appendix='PMTCT/Option B+' limit 1","id")."'","count(a.id)","num");
							//children_under_15
							$subarray["children_under_15"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=5 and round(datediff(now(),b.dateOfBirth)/365)<=9 and a.treatmentInitiationID='".getDetailedTableInfo2("vl_appendix_treatmentinitiation","appendix='Child under 15' limit 1","id")."'","count(a.id)","num");
							//other_treatment
							$subarray["other_treatment"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=5 and round(datediff(now(),b.dateOfBirth)/365)<=9 and a.treatmentInitiationID='".getDetailedTableInfo2("vl_appendix_treatmentinitiation","appendix='Other' limit 1","id")."'","count(a.id)","num");
							//treatment_blank_on_form
							$subarray["treatment_blank_on_form"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=5 and round(datediff(now(),b.dateOfBirth)/365)<=9 and (a.treatmentInitiationID='' or a.treatmentInitiationID='0')","count(a.id)","num");
						break;
						case 3: //age >= 10 && age <= 18
							//samples_received
							$subarray["samples_received"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=10 and round(datediff(now(),b.dateOfBirth)/365)<=18","count(a.id)","num");
							//valid_results
							$subarray["valid_results"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_results_merged c","a.patientID=b.id and a.vlSampleID=c.vlSampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=10 and round(datediff(now(),b.dateOfBirth)/365)<=18 and 
																				c.resultAlphanumeric!='Invalid test result. There is insufficient sample to repeat the assay.' and 
																					c.resultAlphanumeric!='There is No Result Given. The Test Failed the Quality Control Criteria. We advise you send a a new sample.' and 
																						c.resultAlphanumeric!='Failed.' and 
																							c.resultAlphanumeric!='Failed' and 
																								c.resultAlphanumeric!='Invalid' and 
																									(c.resultAlphanumeric='Not detected' or 
																										c.resultAlphanumeric='Target Not Detected' or 
																											(c.resultNumeric!='' and c.resultNumeric!='0'))","count(distinct a.id)","num");
							//rejected_samples
							$subarray["rejected_samples"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_samples_verify c","a.patientID=b.id and a.id=c.sampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=10 and round(datediff(now(),b.dateOfBirth)/365)<=18 and c.outcome='Rejected'","count(distinct a.id)","num");
							//suppressed
							$subarray["suppressed"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_results_merged c","a.patientID=b.id and a.vlSampleID=c.vlSampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=10 and round(datediff(now(),b.dateOfBirth)/365)<=18 and 
																			((a.sampleTypeID='$dbsSampleTypeID' and 
																				(c.resultAlphanumeric='Not detected' or 
																					c.resultAlphanumeric='Target Not Detected' or 
																						(c.resultNumeric!='' and c.resultNumeric!='0' and c.resultNumeric<=5000))) or 
																							(a.sampleTypeID='$plasmaSampleTypeID' and 
																								(c.resultAlphanumeric='Not detected' or 
																									c.resultAlphanumeric='Target Not Detected' or 
																										(c.resultNumeric!='' and c.resultNumeric!='0' and c.resultNumeric<=1000))))","count(distinct a.id)","num");
							//dbs_samples
							$subarray["dbs_samples"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=10 and round(datediff(now(),b.dateOfBirth)/365)<=18 and a.sampleTypeID='$dbsSampleTypeID'","count(distinct a.id)","num");
							//total_results
							$subarray["total_results"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=10 and round(datediff(now(),b.dateOfBirth)/365)<=18 and a.vlSampleID in (select vlSampleID from vl_results_merged)","count(distinct a.id)","num");
							//sample_quality_rejections
							$subarray["sample_quality_rejections"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_samples_verify c","a.patientID=b.id and a.id=c.sampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=10 and round(datediff(now(),b.dateOfBirth)/365)<=18 and c.outcome='Rejected' and 
																			(c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='All Dry Blood Spots on the card less than the required size i.e not filling the perforated area' limit 1","id")."' or 
																				c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='DBS Sample older than 3 weeks' limit 1","id")."' or 
																					c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='DBS sample sent on unperforated card.' limit 1","id")."' or 
																						c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='DBS sample sent on wrong Card' limit 1","id")."' or 
																							c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Dry Blood sample sent with less than 2 spots' limit 1","id")."' or 
																								c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Sample not recieved at CPHL Lab' limit 1","id")."' or 
																									c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Sent a wet sample' limit 1","id")."' or 
																										c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Specimen Heamolysed' limit 1","id")."' or 
																											c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Specimen integrity compromised e.g DBS card dirty,dry blood spots with moulds' limit 1","id")."' or 
																												c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Specimen Less than 0.75ml' limit 1","id")."' or 
																													c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Specimen not labeled' limit 1","id")."')","count(distinct a.id)","num");
							//eligibility_rejections
							$subarray["eligibility_rejections"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_samples_verify c","a.patientID=b.id and a.id=c.sampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=10 and round(datediff(now(),b.dateOfBirth)/365)<=18 and c.outcome='Rejected' and 
																			(c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Patient\'s last viral load result given less than 6 months ago' limit 1","id")."' or 
																				c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Patient has been on ARVs for less than 6 months' limit 1","id")."' or 
																					c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Patient not on ART' limit 1","id")."')","count(distinct a.id)","num");
							//incomplete_form_rejections
							$subarray["incomplete_form_rejections"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_samples_verify c","a.patientID=b.id and a.id=c.sampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=10 and round(datediff(now(),b.dateOfBirth)/365)<=18 and c.outcome='Rejected' and 
																			(c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Mismatching Specimen identifiers on request form and sample' limit 1","id")."' or 
																				c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='No date of birth' limit 1","id")."' or 
																					c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='No date of treatment initiation' limit 1","id")."' or 
																						c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Sample has wrong date of treatment initiation' limit 1","id")."' or 
																							c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Wrong requistion form with insufficient mandatory information' limit 1","id")."')","count(distinct a.id)","num");
							//cd4_less_than_500
							$subarray["cd4_less_than_500"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=10 and round(datediff(now(),b.dateOfBirth)/365)<=18 and a.treatmentInitiationID='".getDetailedTableInfo2("vl_appendix_treatmentinitiation","appendix='CD4<500' limit 1","id")."'","count(a.id)","num");
							//pmtct_option_b_plus
							$subarray["pmtct_option_b_plus"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=10 and round(datediff(now(),b.dateOfBirth)/365)<=18 and a.treatmentInitiationID='".getDetailedTableInfo2("vl_appendix_treatmentinitiation","appendix='PMTCT/Option B+' limit 1","id")."'","count(a.id)","num");
							//children_under_15
							$subarray["children_under_15"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=10 and round(datediff(now(),b.dateOfBirth)/365)<=18 and a.treatmentInitiationID='".getDetailedTableInfo2("vl_appendix_treatmentinitiation","appendix='Child under 15' limit 1","id")."'","count(a.id)","num");
							//other_treatment
							$subarray["other_treatment"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=10 and round(datediff(now(),b.dateOfBirth)/365)<=18 and a.treatmentInitiationID='".getDetailedTableInfo2("vl_appendix_treatmentinitiation","appendix='Other' limit 1","id")."'","count(a.id)","num");
							//treatment_blank_on_form
							$subarray["treatment_blank_on_form"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=10 and round(datediff(now(),b.dateOfBirth)/365)<=18 and (a.treatmentInitiationID='' or a.treatmentInitiationID='0')","count(a.id)","num");
						break;
						case 4: //age >= 19 && age <= 25
							//samples_received
							$subarray["samples_received"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=19 and round(datediff(now(),b.dateOfBirth)/365)<=25","count(a.id)","num");
							//valid_results
							$subarray["valid_results"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_results_merged c","a.patientID=b.id and a.vlSampleID=c.vlSampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=19 and round(datediff(now(),b.dateOfBirth)/365)<=25 and 
																				c.resultAlphanumeric!='Invalid test result. There is insufficient sample to repeat the assay.' and 
																					c.resultAlphanumeric!='There is No Result Given. The Test Failed the Quality Control Criteria. We advise you send a a new sample.' and 
																						c.resultAlphanumeric!='Failed.' and 
																							c.resultAlphanumeric!='Failed' and 
																								c.resultAlphanumeric!='Invalid' and 
																									(c.resultAlphanumeric='Not detected' or 
																										c.resultAlphanumeric='Target Not Detected' or 
																											(c.resultNumeric!='' and c.resultNumeric!='0'))","count(distinct a.id)","num");
							//rejected_samples
							$subarray["rejected_samples"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_samples_verify c","a.patientID=b.id and a.id=c.sampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=19 and round(datediff(now(),b.dateOfBirth)/365)<=25 and c.outcome='Rejected'","count(distinct a.id)","num");
							//suppressed
							$subarray["suppressed"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_results_merged c","a.patientID=b.id and a.vlSampleID=c.vlSampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=19 and round(datediff(now(),b.dateOfBirth)/365)<=25 and 
																			((a.sampleTypeID='$dbsSampleTypeID' and 
																				(c.resultAlphanumeric='Not detected' or 
																					c.resultAlphanumeric='Target Not Detected' or 
																						(c.resultNumeric!='' and c.resultNumeric!='0' and c.resultNumeric<=5000))) or 
																							(a.sampleTypeID='$plasmaSampleTypeID' and 
																								(c.resultAlphanumeric='Not detected' or 
																									c.resultAlphanumeric='Target Not Detected' or 
																										(c.resultNumeric!='' and c.resultNumeric!='0' and c.resultNumeric<=1000))))","count(distinct a.id)","num");
							//dbs_samples
							$subarray["dbs_samples"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=19 and round(datediff(now(),b.dateOfBirth)/365)<=25 and a.sampleTypeID='$dbsSampleTypeID'","count(distinct a.id)","num");
							//total_results
							$subarray["total_results"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=19 and round(datediff(now(),b.dateOfBirth)/365)<=25 and a.vlSampleID in (select vlSampleID from vl_results_merged)","count(distinct a.id)","num");
							//sample_quality_rejections
							$subarray["sample_quality_rejections"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_samples_verify c","a.patientID=b.id and a.id=c.sampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=19 and round(datediff(now(),b.dateOfBirth)/365)<=25 and c.outcome='Rejected' and 
																			(c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='All Dry Blood Spots on the card less than the required size i.e not filling the perforated area' limit 1","id")."' or 
																				c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='DBS Sample older than 3 weeks' limit 1","id")."' or 
																					c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='DBS sample sent on unperforated card.' limit 1","id")."' or 
																						c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='DBS sample sent on wrong Card' limit 1","id")."' or 
																							c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Dry Blood sample sent with less than 2 spots' limit 1","id")."' or 
																								c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Sample not recieved at CPHL Lab' limit 1","id")."' or 
																									c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Sent a wet sample' limit 1","id")."' or 
																										c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Specimen Heamolysed' limit 1","id")."' or 
																											c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Specimen integrity compromised e.g DBS card dirty,dry blood spots with moulds' limit 1","id")."' or 
																												c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Specimen Less than 0.75ml' limit 1","id")."' or 
																													c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Specimen not labeled' limit 1","id")."')","count(distinct a.id)","num");
							//eligibility_rejections
							$subarray["eligibility_rejections"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_samples_verify c","a.patientID=b.id and a.id=c.sampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=19 and round(datediff(now(),b.dateOfBirth)/365)<=25 and c.outcome='Rejected' and 
																			(c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Patient\'s last viral load result given less than 6 months ago' limit 1","id")."' or 
																				c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Patient has been on ARVs for less than 6 months' limit 1","id")."' or 
																					c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Patient not on ART' limit 1","id")."')","count(distinct a.id)","num");
							//incomplete_form_rejections
							$subarray["incomplete_form_rejections"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_samples_verify c","a.patientID=b.id and a.id=c.sampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=19 and round(datediff(now(),b.dateOfBirth)/365)<=25 and c.outcome='Rejected' and 
																			(c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Mismatching Specimen identifiers on request form and sample' limit 1","id")."' or 
																				c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='No date of birth' limit 1","id")."' or 
																					c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='No date of treatment initiation' limit 1","id")."' or 
																						c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Sample has wrong date of treatment initiation' limit 1","id")."' or 
																							c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Wrong requistion form with insufficient mandatory information' limit 1","id")."')","count(distinct a.id)","num");
							//cd4_less_than_500
							$subarray["cd4_less_than_500"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=19 and round(datediff(now(),b.dateOfBirth)/365)<=25 and a.treatmentInitiationID='".getDetailedTableInfo2("vl_appendix_treatmentinitiation","appendix='CD4<500' limit 1","id")."'","count(a.id)","num");
							//pmtct_option_b_plus
							$subarray["pmtct_option_b_plus"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=19 and round(datediff(now(),b.dateOfBirth)/365)<=25 and a.treatmentInitiationID='".getDetailedTableInfo2("vl_appendix_treatmentinitiation","appendix='PMTCT/Option B+' limit 1","id")."'","count(a.id)","num");
							//children_under_15
							$subarray["children_under_15"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=19 and round(datediff(now(),b.dateOfBirth)/365)<=25 and a.treatmentInitiationID='".getDetailedTableInfo2("vl_appendix_treatmentinitiation","appendix='Child under 15' limit 1","id")."'","count(a.id)","num");
							//other_treatment
							$subarray["other_treatment"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=19 and round(datediff(now(),b.dateOfBirth)/365)<=25 and a.treatmentInitiationID='".getDetailedTableInfo2("vl_appendix_treatmentinitiation","appendix='Other' limit 1","id")."'","count(a.id)","num");
							//treatment_blank_on_form
							$subarray["treatment_blank_on_form"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=19 and round(datediff(now(),b.dateOfBirth)/365)<=25 and (a.treatmentInitiationID='' or a.treatmentInitiationID='0')","count(a.id)","num");
						break;
						case 5: //age >= 26
						default: //age >= 26
							//samples_received
							$subarray["samples_received"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=26","count(a.id)","num");
							//valid_results
							$subarray["valid_results"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_results_merged c","a.patientID=b.id and a.vlSampleID=c.vlSampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=26 and 
																				c.resultAlphanumeric!='Invalid test result. There is insufficient sample to repeat the assay.' and 
																					c.resultAlphanumeric!='There is No Result Given. The Test Failed the Quality Control Criteria. We advise you send a a new sample.' and 
																						c.resultAlphanumeric!='Failed.' and 
																							c.resultAlphanumeric!='Failed' and 
																								c.resultAlphanumeric!='Invalid' and 
																									(c.resultAlphanumeric='Not detected' or 
																										c.resultAlphanumeric='Target Not Detected' or 
																											(c.resultNumeric!='' and c.resultNumeric!='0'))","count(distinct a.id)","num");
							//rejected_samples
							$subarray["rejected_samples"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_samples_verify c","a.patientID=b.id and a.id=c.sampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=26 and c.outcome='Rejected'","count(distinct a.id)","num");
							//suppressed
							$subarray["suppressed"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_results_merged c","a.patientID=b.id and a.vlSampleID=c.vlSampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=26 and 
																			((a.sampleTypeID='$dbsSampleTypeID' and 
																				(c.resultAlphanumeric='Not detected' or 
																					c.resultAlphanumeric='Target Not Detected' or 
																						(c.resultNumeric!='' and c.resultNumeric!='0' and c.resultNumeric<=5000))) or 
																							(a.sampleTypeID='$plasmaSampleTypeID' and 
																								(c.resultAlphanumeric='Not detected' or 
																									c.resultAlphanumeric='Target Not Detected' or 
																										(c.resultNumeric!='' and c.resultNumeric!='0' and c.resultNumeric<=1000))))","count(distinct a.id)","num");
							//dbs_samples
							$subarray["dbs_samples"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=26 and a.sampleTypeID='$dbsSampleTypeID'","count(distinct a.id)","num");
							//total_results
							$subarray["total_results"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=26 and a.vlSampleID in (select vlSampleID from vl_results_merged)","count(distinct a.id)","num");
							//sample_quality_rejections
							$subarray["sample_quality_rejections"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_samples_verify c","a.patientID=b.id and a.id=c.sampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=26 and c.outcome='Rejected' and 
																			(c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='All Dry Blood Spots on the card less than the required size i.e not filling the perforated area' limit 1","id")."' or 
																				c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='DBS Sample older than 3 weeks' limit 1","id")."' or 
																					c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='DBS sample sent on unperforated card.' limit 1","id")."' or 
																						c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='DBS sample sent on wrong Card' limit 1","id")."' or 
																							c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Dry Blood sample sent with less than 2 spots' limit 1","id")."' or 
																								c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Sample not recieved at CPHL Lab' limit 1","id")."' or 
																									c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Sent a wet sample' limit 1","id")."' or 
																										c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Specimen Heamolysed' limit 1","id")."' or 
																											c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Specimen integrity compromised e.g DBS card dirty,dry blood spots with moulds' limit 1","id")."' or 
																												c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Specimen Less than 0.75ml' limit 1","id")."' or 
																													c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Specimen not labeled' limit 1","id")."')","count(distinct a.id)","num");
							//eligibility_rejections
							$subarray["eligibility_rejections"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_samples_verify c","a.patientID=b.id and a.id=c.sampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=26 and c.outcome='Rejected' and 
																			(c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Patient\'s last viral load result given less than 6 months ago' limit 1","id")."' or 
																				c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Patient has been on ARVs for less than 6 months' limit 1","id")."' or 
																					c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Patient not on ART' limit 1","id")."')","count(distinct a.id)","num");
							//incomplete_form_rejections
							$subarray["incomplete_form_rejections"]=getDetailedTableInfo3("vl_samples a,vl_patients b,vl_samples_verify c","a.patientID=b.id and a.id=c.sampleID and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=26 and c.outcome='Rejected' and 
																			(c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Mismatching Specimen identifiers on request form and sample' limit 1","id")."' or 
																				c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='No date of birth' limit 1","id")."' or 
																					c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='No date of treatment initiation' limit 1","id")."' or 
																						c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Sample has wrong date of treatment initiation' limit 1","id")."' or 
																							c.outcomeReasonsID='".getDetailedTableInfo2("vl_appendix_samplerejectionreason","appendix='Wrong requistion form with insufficient mandatory information' limit 1","id")."')","count(distinct a.id)","num");
							//cd4_less_than_500
							$subarray["cd4_less_than_500"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=26 and a.treatmentInitiationID='".getDetailedTableInfo2("vl_appendix_treatmentinitiation","appendix='CD4<500' limit 1","id")."'","count(a.id)","num");
							//pmtct_option_b_plus
							$subarray["pmtct_option_b_plus"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=26 and a.treatmentInitiationID='".getDetailedTableInfo2("vl_appendix_treatmentinitiation","appendix='PMTCT/Option B+' limit 1","id")."'","count(a.id)","num");
							//children_under_15
							$subarray["children_under_15"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=26 and a.treatmentInitiationID='".getDetailedTableInfo2("vl_appendix_treatmentinitiation","appendix='Child under 15' limit 1","id")."'","count(a.id)","num");
							//other_treatment
							$subarray["other_treatment"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=26 and a.treatmentInitiationID='".getDetailedTableInfo2("vl_appendix_treatmentinitiation","appendix='Other' limit 1","id")."'","count(a.id)","num");
							//treatment_blank_on_form
							$subarray["treatment_blank_on_form"]=getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=26 and (a.treatmentInitiationID='' or a.treatmentInitiationID='0')","count(a.id)","num");
						break;
					}
					$array[]=$subarray;
				}
				//prepare json string
				echo json_encode($array);
			}
		break;
	}
}
?>