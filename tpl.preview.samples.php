<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";

//get variables
$patientID=getValidatedVariable("patientID");

//query
$query=0;
$query=mysqlquery("select * from vl_samples where patientID='$patientID' order by created");
$num=0;
$num=mysqlnumrows($query);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="0" class="vl">
        <tr>
            <td class="tab_active">Samples&nbsp;from&nbsp;Patient&nbsp;<?=(getDetailedTableInfo2("vl_patients","id='$patientID'","artNumber")?getDetailedTableInfo2("vl_patients","id='$patientID'","artNumber"):getDetailedTableInfo2("vl_patients","id='$patientID'","otherID"))?></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td style="border:1px solid #CCCCFF; padding:20px">
	<table width="100%" border="0" class="vl">
    <? if($num) { ?>
    <tr>
        <td>
	        <div style="height: 280px; overflow: auto; padding:3px">
            	<table width="92%" border="0" class="vl">
            <tr>
                <td>
                  <fieldset style="width: 100%">
            <legend><strong>PATIENT SAMPLES</strong></legend>
                        <div style="padding:5px 0px 0px 0px">
						<table width="100%" border="0" class="vl" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="20%" class="vl_tdsub"><strong>Form&nbsp;#</strong></td>
                              <td width="20%" class="vl_tdsub"><strong>Sample&nbsp;Reference&nbsp;#</strong></td>
                              <td width="20%" class="vl_tdsub"><strong>Status</strong></td>
                              <td width="40%" class="vl_tdsub"><strong>Date&nbsp;Captured</strong></td>
                            </tr>
							<?
							$count=0;
							while($q=mysqlfetcharray($query)) {
								$count+=1;
								$status=0;
								$status=getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]'","outcome");
								if($count<$num) {
									echo "<tr>
												<td class=\"vl_tdstandard\">$q[formNumber]</td>
												<td class=\"vl_tdstandard\"><a href=\"/verify/find.and.edit/$q[id]/1/\">$q[vlSampleID]</a></td>
												<td class=\"vl_tdstandard\">".($status?$status:"Pending")."</td>
												<td class=\"vl_tdstandard\">".getFormattedDate($q["created"])."&nbsp;at&nbsp;".getFormattedTimeLessS($q["created"])."</td>
											</tr>";
								} else {
									echo "<tr>
												<td class=\"vl_tdnoborder\">$q[formNumber]</td>
												<td class=\"vl_tdnoborder\"><a href=\"/verify/find.and.edit/$q[id]/1/\">$q[vlSampleID]</a></td>
												<td class=\"vl_tdnoborder\">".($status?$status:"Pending")."</td>
												<td class=\"vl_tdnoborder\">".getFormattedDate($q["created"])."&nbsp;at&nbsp;".getFormattedTimeLessS($q["created"])."</td>
											</tr>";
								}
							}
							?>
                      </table>
                        </div>
                  </fieldset>
                </td>
            </tr>
                </table>
            </div>
        </td>
    </tr>
    <? } else { ?>
    <tr>
    	<td class="vl_error">No Samples Found Matching this Patient.</td>
	</tr>
    <? } ?>
    <tr>
    	<td><img src="/images/spacer.gif" width="10" height="10" /></td>
    </tr>
    <tr>
    	<td style="padding: 10px 0px 0px 0px; border-top: 1px solid #999999;"><a href="#rnd" onclick="closeMessage()" class="trailanalyticss">Close!</a></td>
    </tr>
	</table>
    </td>
  </tr>
</table>
