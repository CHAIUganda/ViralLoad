<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";

//the date
$theDate=0;
$theDate=getRawFormattedDateLessDay(getDetailedTableInfo2("vl_logs_removals","id='$id'","created"));

//createdby
$createdby=0;
$createdby=getDetailedTableInfo2("vl_logs_removals","id='$id'","createdby");

//removedData
$removedData=0;
$removedData=getDetailedTableInfo2("vl_logs_removals","id='$id'","removedData");
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="0" class="trailanalytics">
        <tr>
            <td class="tab_active">Removals&nbsp;by&nbsp;<?=$createdby?>&nbsp;in&nbsp;<?=getFormattedDate($theDate)?></td>
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
                        <td class="vl_tdsub" width="20%"><strong>Field&nbsp;Name</strong></td>
                        <td class="vl_tdsub" width="80%"><strong>Data</strong></td>
                    </tr>
                    <?
					//get number of fields
					$fields=array();
					$fields=explode("|",$removedData);
					if(count($fields)) {
						for($i=0;$i<count($fields);$i++) {
							$records=array();
							$records=explode("::",$fields[$i]);
							echo "<tr>
									<td class=\"".($i<(count($fields)-1)?"vl_tdstandard":"vl_tdnoborder")."\">".($records[0]?$records[0]:"&nbsp;")."</td>
									<td class=\"".($i<(count($fields)-1)?"vl_tdstandard":"vl_tdnoborder")."\">".($records[1]?$records[1]:"&nbsp;")."</td>
								</tr>";
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
    	<td style="padding: 10px 0px 0px 0px; border-top: 1px solid #999999;"><a href="#" onclick="closeMessage()" class="trailanalyticss">Close Window</a> :: <a href="#" onclick="iDisplayMessage('tpl.statistics.removals.details.1.php?theDate=<?=$theDate?>&createdby=<?=$createdby?>')">Return to Previous Window</a></td>
    </tr>
	</table>
    </td>
  </tr>
</table>
