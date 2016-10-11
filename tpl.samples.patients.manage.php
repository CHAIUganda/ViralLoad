<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}

//validate
$patientID=0;
if($encryptedSample) {
	$patientID=validate(vlDecrypt($encryptedSample));
}

if($envelopeNumberFrom && $envelopeNumberTo) {
	$patientID=validate(vlDecrypt($envelopeNumberFrom));
}
?>
<table width="100%" border="0" class="vl">
			<? if(!getDetailedTableInfo2("vl_patients","id!='' limit 1","id")) { ?>
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
            <? } ?>
            <tr>
              <td>
	<table width="100%" border="0" class="vl">
		<?
		//pages
		if(!$pg) {
			$pg=1;
		}
		
		$offset=0;
		$offset=($pg-1)*$rowsToDisplay;

		//proceed with query
        $query=0;
        $query=mysqlquery("select * from vl_patients ".($patientID?"where uniqueID like '%$patientID%' or artNumber like '%$patientID%' or otherID like '%$patientID%'":"")." order by created desc limit $offset, $rowsToDisplay");
        $xquery=0;
        $xquery=mysqlquery("select * from vl_patients ".($patientID?"where uniqueID like '%$patientID%' or artNumber like '%$patientID%' or otherID like '%$patientID%'":"")." order by created desc");
		//number pages
		$numberPages=0;
		$numberPages=ceil(mysqlnumrows($xquery)/$rowsToDisplay);
		
        if(mysqlnumrows($query)) {
			//how many pages are there?
			if($numberPages>1) {
				echo "<tr><td style=\"padding:0px 0px 10px 0px\" class=\"vls_grey\"><strong>Pages:</strong> ".displayPagesLinks("/samples/manage.patients/pg/",1,$numberPages,($pg?$pg:1),$default_radius)."</td></tr>";
			}
        ?>
            <tr>
	            <td style="padding:0px 0px 10px 0px" class="vls_grey"><strong><?=mysqlnumrows($xquery)?></strong> Patient<?=(mysqlnumrows($xquery)!=1?"s":"")?> to Manage</td>
            </tr>
                <tr>
                  <td>
                  <div style="height: 250px; width: 100%; overflow: auto; padding:5px; border: 1px solid #d5e6cf">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td class="vl_tdsub" width="1%"><strong>#</strong></td>
                          <td class="vl_tdsub" width="10%"><strong>ART&nbsp;#</strong></td>
                          <td class="vl_tdsub" width="10%"><strong>Other&nbsp;ID</strong></td>
                          <td class="vl_tdsub" width="5%" align="center"><strong>Samples</strong></td>
                          <td class="vl_tdsub" width="70%"><strong>Facility</strong></td>
                          <td class="vl_tdsub" width="4%"><strong>Options</strong></td>
                        </tr>
                    	<?
                        $count=0;
                        $count=$offset;
                        $q=array();
                        while($q=mysqlfetcharray($query)) {
                            $count+=1;
							$numberSamples=0;
							$numberSamples=getDetailedTableInfo3("vl_samples","patientID='$q[id]'","count(id)","num");
							$sampleURL=0;
							if($numberSamples==1) {
								$sampleURL="href=\"/samples/find.and.edit/search/".vlEncrypt(getDetailedTableInfo2("vl_samples","patientID='$q[id]'","vlSampleID"))."/\"";
							} elseif($numberSamples>1) {
								$sampleURL="href=\"#rn\" onclick=\"iDisplayMessage('/samples/preview/$q[id]/')\"";
							}
                        ?>
                            <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><?=$count?></td>
                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><?=$q["artNumber"]?></td>
                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><?=$q["otherID"]?></td>
                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>" align="center"><a <?=$sampleURL?>><?=number_format((float)$numberSamples)?></a></td>
                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><?=getDetailedTableInfo2("vl_facilities","id='".getDetailedTableInfo2("vl_samples","patientID='$q[id]'","facilityID")."'","facility")?></td>
                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><div class="vls_grey" style="padding:3px 0px 0px 0px"><a href="/samples/manage.patients/modify/<?=$q["id"]?>/">Edit&nbsp;Patient&nbsp;Information</a></div></td>
                            </tr>
                        <? } ?>
 	               </table>
				  </div>
              </td>
            </tr>
            <tr>
	            <td style="padding:20px 0px 0px 0px"><a href="/samples/">Return to Samples</a> :: <a href="/dashboard/">Return to Dashboard</a></td>
            </tr>
	    <? } else { ?>
            <tr>
                <td class="vl_error">There are No Patients matching the Search Criteria <strong><?=$patientID?></strong>!</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
	    <? } ?>
		</table>
              </td>
            </tr>
          </table>