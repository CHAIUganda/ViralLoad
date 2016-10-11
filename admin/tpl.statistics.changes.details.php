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
                        <td class="vl_tdsub" width="10%"><strong>ID</strong></td>
                        <td class="vl_tdsub" width="15%"><strong>Table&nbsp;Name</strong></td>
                        <td class="vl_tdsub" width="15%"><strong>Field&nbsp;Name</strong></td>
                        <td class="vl_tdsub" width="30%"><strong>Old&nbsp;Value</strong></td>
                        <td class="vl_tdsub" width="30%"><strong>New&nbsp;Value</strong></td>
                    </tr>
                    <?
					//query
					$query=0;
					$query=mysqlquery("select * from vl_logs_tables where date(created)='$theDate' and createdby='$createdby' order by created desc");
					if(mysqlnumrows($query)) {
						$count=0;
						while($q=mysqlfetcharray($query)) {
							$count+=1;
							?>
							<tr>
								<td class="<?=($count<mysqlnumrows($query)?"vl_tdstandard":"vl_tdnoborder")?>"><?=$q["id"]?></td>
								<td class="<?=($count<mysqlnumrows($query)?"vl_tdstandard":"vl_tdnoborder")?>"><?=($q["tableName"]?$q["tableName"]:"&nbsp;")?></td>
								<td class="<?=($count<mysqlnumrows($query)?"vl_tdstandard":"vl_tdnoborder")?>"><?=($q["fieldName"]?$q["fieldName"]:"&nbsp;");?></td>
								<td class="<?=($count<mysqlnumrows($query)?"vl_tdstandard":"vl_tdnoborder")?>"><?=($q["fieldValueOld"]?$q["fieldValueOld"]:"&nbsp;")?></td>
								<td class="<?=($count<mysqlnumrows($query)?"vl_tdstandard":"vl_tdnoborder")?>"><?=($q["fieldValueNew"]?$q["fieldValueNew"]:"&nbsp;")?></td>
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
