<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}

//encrypted samples
$searchQuery=0;
if($encryptedSample) {
	$searchQuery=validate(vlDecrypt($encryptedSample));
	$approvedstatus="search";
}

if($encryptedSampleUnverified) {
	$searchQuery=validate(vlDecrypt($encryptedSampleUnverified));
	$approvedstatus="reverse";
}

//assign approvedstatus
if(!$approvedstatus) {
	$approvedstatus="pending";
}

//process reversals
if($reverseApprovalRejection) {
	//were any samples checked
	if(count($sampleVerifyCheckbox)) {
		//reverse the approvals/rejections
		foreach($sampleVerifyCheckbox as $sv) {
			/*
			* delete from the following 
			* vl_samples_verify.sampleID = vl_samples.id
			* vl_samples_worksheet.sampleID = vl_samples.id
			* vl_results_roche.SampleID = vl_samples.vlSampleID
			* vl_results_abbott.sampleID = vl_samples.vlSampleID
			* vl_results_override.sampleID = vl_samples.vlSampleID
			* vl_logs_worksheetsamplesviewed.sampleID = vl_samples.id
			* vl_logs_worksheetsamplesprinted.sampleID = vl_samples.id
			* vl_logs_samplerepeats.sampleID = vl_samples.id
			* vl_logs_printedresults.sampleID = vl_samples.id
			* vl_logs_printedrejectedresults.sampleID = vl_samples.id
			* vl_logs_dispatchedresults.sampleID = vl_samples.id
			*/
			
			//get vlSampleID
			$vlSampleID=0;
			$vlSampleID=getDetailedTableInfo2("vl_samples","id='$sv' limit 1","vlSampleID");
			
			//implement the removals
			logDataRemoval("delete from vl_samples_verify where sampleID='$sv'");
			mysqlquery("delete from vl_samples_verify where sampleID='$sv'");
			logDataRemoval("delete from vl_samples_worksheet where sampleID='$sv'");
			mysqlquery("delete from vl_samples_worksheet where sampleID='$sv'");
			logDataRemoval("delete from vl_results_roche where SampleID='$vlSampleID'");
			mysqlquery("delete from vl_results_roche where SampleID='$vlSampleID'");
			logDataRemoval("delete from vl_results_abbott where sampleID='$vlSampleID'");
			mysqlquery("delete from vl_results_abbott where sampleID='$vlSampleID'");
			logDataRemoval("delete from vl_results_override where sampleID='$vlSampleID'");
			mysqlquery("delete from vl_results_override where sampleID='$vlSampleID'");
			logDataRemoval("delete from vl_logs_worksheetsamplesviewed where sampleID='$sv'");
			mysqlquery("delete from vl_logs_worksheetsamplesviewed where sampleID='$sv'");
			logDataRemoval("delete from vl_logs_worksheetsamplesprinted where sampleID='$sv'");
			mysqlquery("delete from vl_logs_worksheetsamplesprinted where sampleID='$sv'");
			logDataRemoval("delete from vl_logs_samplerepeats where sampleID='$sv'");
			mysqlquery("delete from vl_logs_samplerepeats where sampleID='$sv'");
			logDataRemoval("delete from vl_logs_printedresults where sampleID='$sv'");
			mysqlquery("delete from vl_logs_printedresults where sampleID='$sv'");
			logDataRemoval("delete from vl_logs_printedrejectedresults where sampleID='$sv'");
			mysqlquery("delete from vl_logs_printedrejectedresults where sampleID='$sv'");
			logDataRemoval("delete from vl_logs_dispatchedresults where sampleID='$sv'");
			mysqlquery("delete from vl_logs_dispatchedresults where sampleID='$sv'");
			
			//log this reversal
			mysqlquery("insert into vl_logs_reversals_sampleapprovalsrejections 
							(sampleID,created,createdby) 
							values 
							('$sv','$datetime','$trailSessionUser')");
			
		}
		//reversed flag
		$reversed=count($sampleVerifyCheckbox);
	} else {
		$notreversed=1;
	}
}
?>
<table width="100%" border="0" class="vl">
			<? if(!getDetailedTableInfo2("vl_samples","id!='' limit 1","id")) { ?>
            <tr>
                <td class="vl_error">
                There are No Samples on the System!<br />
				<a href="/samples/capture/">Click Here to input the First Sample.</a></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <? 
			}

          	if($modified) { 
			?>
            <tr>
                <td class="vl_success">Sample Changes Modified Successfully!</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <? 
			}

          	if($approved) { 
			?>
            <tr>
                <td class="vl_success">Sample Approved!</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <? 
			}

          	if($rejected) { 
			?>
            <tr>
                <td class="vl_success">Sample Rejected!</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <? 
			}

          	if($reversed) { 
			?>
            <tr>
                <td class="vl_success"><?=number_format((float)$reversed)?> Approval<?=($reversed==1?"":"s")?>/Rejection<?=($reversed==1?"":"s")?> Reversed Successfully!</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <? 
			}

          	if($notreversed) { 
			?>
            <tr>
                <td class="vl_error">
                	<div style="padding:0px 0px 2px 0px"><strong>No Approvals/Rejections have been Reversed!</strong></div>
                    <div style="padding:2px 0px 0px 0px">Ensure you have checked/selected some samples before clicking "Reverse"</div>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <? } ?>
            <tr>
              <td>
        <? if($approvedstatus=="reverse") { ?>
		<script Language="JavaScript" Type="text/javascript">
        <!--
        function validate(results) {
            //ammend the form action variable
            if(document.pressed == '  Reverse Approval/Rejection  ') {
				if(confirm('Are you sure you wish to Proceed?')) {
	                document.results.action = "/verify/reverse/";
		            return (true);
				} else {
					return (false);
				}
            }
        }
        //-->
        </script>
        <!--<form name="results" method="post" action="/verify/reverse/" onsubmit="return validate(this)">-->
        <form name="results" method="post" action="/verify/reverse/">
        <? } ?>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="vl">
                <?
                //pages
                if(!$pg) {
                    $pg=1;
                }
                
                $offset=0;
                $offset=($pg-1)*$rowsToDisplay;
        
                //proceed with query
                $query=0;
                $xquery=0;
                if($searchQuery) {
					if($approvedstatus=="search") {
						$query=mysqlquery("select * from vl_samples where formNumber='$searchQuery' or vlSampleID='$searchQuery' or concat(lrCategory,lrEnvelopeNumber,'/',lrNumericID) like '$searchQuery%' order by if(lrCategory='',1,0),lrCategory, if(lrEnvelopeNumber='',1,0),lrEnvelopeNumber, if(lrNumericID='',1,0),lrNumericID,created desc limit $offset, $rowsToDisplay");
						$xquery=mysqlquery("select * from vl_samples where formNumber='$searchQuery' or vlSampleID='$searchQuery' or concat(lrCategory,lrEnvelopeNumber,'/',lrNumericID) like '$searchQuery%' order by if(lrCategory='',1,0),lrCategory, if(lrEnvelopeNumber='',1,0),lrEnvelopeNumber, if(lrNumericID='',1,0),lrNumericID,created desc");
					} elseif($approvedstatus=="reverse") {
						$query=mysqlquery("select * from vl_samples where id in (select sampleID from vl_samples_verify) and formNumber='$searchQuery' or vlSampleID='$searchQuery' or concat(lrCategory,lrEnvelopeNumber,'/',lrNumericID) like '$searchQuery%' order by if(lrCategory='',1,0),lrCategory, if(lrEnvelopeNumber='',1,0),lrEnvelopeNumber, if(lrNumericID='',1,0),lrNumericID,created desc limit $offset, $rowsToDisplay");
						$xquery=mysqlquery("select * from vl_samples where id in (select sampleID from vl_samples_verify) and formNumber='$searchQuery' or vlSampleID='$searchQuery' or concat(lrCategory,lrEnvelopeNumber,'/',lrNumericID) like '$searchQuery%' order by if(lrCategory='',1,0),lrCategory, if(lrEnvelopeNumber='',1,0),lrEnvelopeNumber, if(lrNumericID='',1,0),lrNumericID,created desc");
					}
				} else {
                    if(!$approvedstatus || $approvedstatus=="pending") {
                        $query=mysqlquery("select * from vl_samples where id not in (select sampleID from vl_samples_verify) order by if(lrCategory='',1,0),lrCategory, if(lrEnvelopeNumber='',1,0),lrEnvelopeNumber, if(lrNumericID='',1,0),lrNumericID,created desc limit $offset, $rowsToDisplay");
                        $xquery=mysqlquery("select * from vl_samples where id not in (select sampleID from vl_samples_verify) order by if(lrCategory='',1,0),lrCategory, if(lrEnvelopeNumber='',1,0),lrEnvelopeNumber, if(lrNumericID='',1,0),lrNumericID,created desc");
                    } elseif($approvedstatus=="processed" || $approvedstatus=="reverse") {
                        $query=mysqlquery("select * from vl_samples where id in (select sampleID from vl_samples_verify) order by if(lrCategory='',1,0),lrCategory, if(lrEnvelopeNumber='',1,0),lrEnvelopeNumber, if(lrNumericID='',1,0),lrNumericID,created desc limit $offset, $rowsToDisplay");
                        $xquery=mysqlquery("select * from vl_samples where id in (select sampleID from vl_samples_verify) order by if(lrCategory='',1,0),lrCategory, if(lrEnvelopeNumber='',1,0),lrEnvelopeNumber, if(lrNumericID='',1,0),lrNumericID,created desc");
                    }
                }
                //number pages
                $numberPages=0;
                $numberPages=ceil(mysqlnumrows($xquery)/$rowsToDisplay);
                
                if(mysqlnumrows($query)) {
                    //how many pages are there?
                    if($numberPages>1) {
                        echo "<tr><td style=\"padding:0px 0px 10px 0px\" class=\"vls_grey\"><strong>Pages:</strong> ".displayPagesLinks("/verify/".($approvedstatus=="search"?"search/$encryptedSample":$approvedstatus)."/pg/",1,$numberPages,($pg?$pg:1),$default_radius)."</td></tr>";
                    }
                    
                    $numberOfRelevantSamples=0;
                    $numberOfRelevantSamples=getDetailedTableInfo3("vl_samples","id not in (select sampleID from vl_samples_verify)","count(id)","num");
                    
                    $resultsPending=0;
                    $resultsPending=$numberOfRelevantSamples;
                    
                    $resultsProcessed=0;
                    $resultsProcessed=getDetailedTableInfo3("vl_samples","id in (select sampleID from vl_samples_verify)","count(id)","num");
                    
                    $resultsSearch=0;
                    $resultsSearch=mysqlnumrows($query);
                ?>
                        <tr>
                            <td>
                                <table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <? if($approvedstatus=="pending") { ?>
                                  <td class="bluetab_active"><?="Pending&nbsp;(".number_format((float)$resultsPending).")"?></td>
                                  <? } else { ?>
                                  <td class="bluetab_inactive"><a href="/verify/pending/">
                                    <?="Pending&nbsp;(".number_format((float)$resultsPending).")"?>
                                  </a></td>
                                  <? } ?>
                                  <td bgcolor="#C4CCCC"><img src="/images/spacer.gif" width="1" height="1" /></td>
                                  <? if($approvedstatus=="processed") { ?>
                                  <td class="bluetab_active"><?="Accepted/Rejected&nbsp;(".number_format((float)$resultsProcessed).")"?></td>
                                  <? } else { ?>
                                  <td class="bluetab_inactive"><a href="/verify/processed/">
                                    <?="Accepted/Rejected&nbsp;(".number_format((float)$resultsProcessed).")"?>
                                  </a></td>
                                  <? } ?>
                                  <td bgcolor="#C4CCCC"><img src="/images/spacer.gif" width="1" height="1" /></td>
                                  <? if($approvedstatus=="search") { ?>
                                  <td class="bluetab_active"><?="Search&nbsp;Results&nbsp;(".number_format((float)mysqlnumrows($xquery)).")"?></td>
                                  <td bgcolor="#C4CCCC"><img src="/images/spacer.gif" width="1" height="1" /></td>
                                  <? } ?>
                                  <? 
                                    if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='unVerifySamples' limit 1","id")) { 
                                        if($approvedstatus=="reverse") {
                                  ?>
                                      <td class="bluetab_active"><?="Reverse&nbsp;Approval&nbsp;of&nbsp;Samples&nbsp;(".number_format((float)$resultsProcessed).")"?></td>
                                      <? } else { ?>
                                      <td class="bluetab_inactive"><a href="/verify/reverse/">
                                        <?="Reverse&nbsp;Approval&nbsp;of&nbsp;Samples&nbsp;".number_format((float)$resultsProcessed).")"?>
                                      </a></td>
                                      <? } ?>
                                  <? } ?>
                                </tr>
                              </table>
                            </td>
                        </tr>
                        <tr>
                          <td>
                          <div style="height: 250px; width: 100%; overflow: auto; padding:5px; border: 1px solid #d5e6cf">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                <? 
                                if($approvedstatus=="reverse") { 
                                    echo "<td class=\"vl_tdsub\" width=\"1%\"><input type=\"checkbox\" name=\"checkall\" onclick=\"checkUncheckAll(this);\"></td>";
                                } else {
                                    echo "<td class=\"vl_tdsub\" width=\"1%\"><strong>#</strong></td>";
                                }
                                ?>
                                  <td class="vl_tdsub" width="5%"><strong>Form&nbsp;#</strong></td>
                                  <td class="vl_tdsub" width="5%"><strong>Location&nbsp;ID</strong></td>
                                  <td class="vl_tdsub" width="5%"><strong>Sample&nbsp;Reference&nbsp;#</strong></td>
                                  <td class="vl_tdsub" width="5%"><strong>Patient&nbsp;ART&nbsp;#</strong></td>
                                  <td class="vl_tdsub" width="5%"><strong>Patient&nbsp;Other&nbsp;ID</strong></td>
                                  <td class="vl_tdsub" width="5%"><strong>District</strong></td>
                                  <td class="vl_tdsub" width="60%"><strong>Facility..</strong></td>
                                  <td class="vl_tdsub" width="5%"><strong>Status</strong></td>
                                  <td class="vl_tdsub" width="4%"><strong>Options</strong></td>
                                </tr>
                                <?
                                $count=0;
                                $count=$offset;
                                $q=array();
                                while($q=mysqlfetcharray($query)) {
                                    $count+=1;
                                    $status=0;
                                    $status=getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]'","outcome");
                                ?>
                                    <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                                        <? 
                                        if($approvedstatus=="reverse") { 
                                            echo "<td class=\"".($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")."\"><input name=\"sampleVerifyCheckbox[]\" type=\"checkbox\" id=\"sampleVerifyCheckbox[]\" value=\"$q[id]\"></td>";
                                        } else {
                                            echo "<td class=\"".($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")."\">$count</td>";
                                        }
                                        ?>
                                        <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><a href="#" onclick="iDisplayMessage('/verify/preview/<?=$q["id"]?>/<?=$pg?>/<?=($status?"noedit/":($approvedstatus=="search"?"search/$encryptedSample/":""))?>')"><?=$q["formNumber"]?></a></td>
                                        <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><?=($q["lrNumericID"]?$q["lrCategory"].$q["lrEnvelopeNumber"]."/".$q["lrNumericID"]:"&nbsp;")?></td>
                                        <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><?=$q["vlSampleID"]?></td>
                                        <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><?=getDetailedTableInfo2("vl_patients","id='$q[patientID]'","artNumber")?></td>
                                        <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><?=getDetailedTableInfo2("vl_patients","id='$q[patientID]'","otherID")?></td>
                                        <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><?=getDetailedTableInfo2("vl_districts","id='$q[districtID]'","district")?></td>
                                        <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><?=getDetailedTableInfo2("vl_facilities","id='$q[facilityID]'","facility")?></td>
                                        <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><?=($status?"<a href=\"#\" onclick=\"iDisplayMessage('/verify/status/$q[id]/$status/')\">$status</a>":"Pending")?></td>
                                        <!--<td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><div class="vls_grey" style="padding:3px 0px 0px 0px"><a href="#" onclick="iDisplayMessage('/verify/preview/<?=$q["id"]?>/<?=$pg?>/<?=($status?"noedit/":($approvedstatus=="search"?"search/$encryptedSample/":""))?>')">Approve</a></div></td>-->
                                        <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><div class="vls_grey" style="padding:3px 0px 0px 0px"><a <?=(!getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]'","outcome")?"href=\"/verify/approve.reject/$q[id]/$pg/".($encryptedSample?"search/$encryptedSample/":"")."\"":"href=\"#\" onclick=\"iDisplayMessage('/verify/preview/$q[id]/$pg/".($status?"noedit/":($approvedstatus=="search"?"search/$encryptedSample/":""))."')\"")?>>Approve</a></div></td>
                                    </tr>
                                <? } ?>
                           </table>
                          </div>
                      </td>
                    </tr>
                    <? if($approvedstatus=="reverse") { ?>
                    <tr>
                      <td style="padding:10px 0px 10px 0px">
                        <input type="submit" name="reverseApprovalRejection" id="reverseApprovalRejection" class="button" value="  Reverse Approval/Rejection  " onclick="document.pressed=this.value" /> 
                      </td>
                    </tr>
                    <? } ?>
                    <tr>
                        <td style="padding:20px 0px 0px 0px"><a href="/verify/">Return to Verify</a> :: <a href="/dashboard/">Return to Dashboard</a></td>
                    </tr>
                <? } else { ?>
                    <tr>
                        <td class="vl_error">There are No Samples matching the Search Criteria <strong><?=$searchQuery?></strong>!</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                <? } ?>
                </table>
                <? if($approvedstatus=="reverse") { ?>
		        </form>
                <? } ?>
				</td>
            </tr>
          </table>