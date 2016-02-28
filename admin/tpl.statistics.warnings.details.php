<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="0" class="trailanalytics">
        <tr>
            <td class="tab_active">Changes&nbsp;by&nbsp;<?=$createdby?>&nbsp;in&nbsp;<?=getFormattedDate($theDate)?></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td style="border:1px solid #CCCCFF; padding:20px">
	<table width="100%" border="0" class="trailanalytics">
                <tr>
                  <td>
                  <div style="height: 270px; overflow: auto; padding:3px">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="vl_tdsub" width="90%"><strong>Post-Warning Submission Details</strong></td>
                        <td class="vl_tdsub" width="10%"><strong>Time</strong></td>
                    </tr>
                    <?
					//query
					$query=0;
					$query=mysqlquery("select * from vl_logs_warnings where date(created)='$theDate' and createdby='$createdby' order by created desc");
					if(mysqlnumrows($query)) {
						$count=0;
						while($q=mysqlfetcharray($query)) {
							$count+=1;
							$warningType=0;
							switch($q["logCategory"]) {
								case "changedPatientsGender":
									$warningType="Change in patient's gender";
								break;
								case "capturedRepeatVLTest":
									$warningType="Capture of a repeat VL test";
								break;
							}
							?>
							<tr>
								<td class="<?=($count<mysqlnumrows($query)?"vl_tdstandard":"vl_tdnoborder")?>">
									<div><?=$q["logDetails"]?></div>
                                    <div class="vls_grey" style="padding:5px 0px 0px 0px"><strong>Warning Type:</strong> <?=$warningType?></div>
                                </td>
								<td class="<?=($count<mysqlnumrows($query)?"vl_tdstandard":"vl_tdnoborder")?>"><?=getFormattedTimeLessS($q["created"])?></td>
							</tr>
							<?
						}
					}
					?>
 	               </table>
				  </div>
              </td>
            </tr>
    <tr>
    	<td><img src="/images/spacer.gif" width="10" height="10" /></td>
    </tr>
    <tr>
    	<td style="padding: 10px 0px 0px 0px; border-top: 1px solid #999999;"><a href="#" onclick="closeMessage()" class="trailanalyticss">Close Window</a></td>
    </tr>
	</table>
    </td>
  </tr>
</table>
