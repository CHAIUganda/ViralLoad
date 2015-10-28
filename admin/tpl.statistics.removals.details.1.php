<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";
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
                        <td class="vl_tdsub" width="90%"><strong>Query</strong></td>
                        <td class="vl_tdsub" width="10%"><strong>Data</strong></td>
                    </tr>
                    <?
					//query
					$query=0;
					$query=mysqlquery("select * from vl_logs_removals where date(created)='$theDate' and createdby='$createdby' order by created desc");
					if(mysqlnumrows($query)) {
						$count=0;
						while($q=mysqlfetcharray($query)) {
							$count+=1;
							if($q["removedData"]) {
								?>
								<tr>
									<td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><a href="#" onclick="iDisplayMessage('tpl.statistics.removals.details.2.php?id=<?=$q["id"]?>')"><?=$q["sqlQuery"]?></a></td>
									<td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>">Yes</td>
								</tr>
								<?
							} else {
								?>
								<tr>
									<td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><?=$q["sqlQuery"]?></td>
									<td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>">No</td>
								</tr>
								<?
							}
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
