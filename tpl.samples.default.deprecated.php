<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}
?>
<table width="100%" border="0" class="vl">
			<? 
			if($success) { 
				if($addedSamples || $modifiedSamples) {
					?>
					<tr>
						<td colspan="2" class="vl_success"><? echo "<strong>".number_format((float)$addedSamples)."</strong> Sample".($addedSamples==1?"":"s")." Added, <strong>".number_format((float)$modifiedSamples)."</strong> Sample".($modifiedSamples==1?"":"s")." Modified"; ?></td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<? 
				} else {
					?>
					<tr>
						<td colspan="2" class="vl_success">Sample Captured Successfully!</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<? 
				}
			} elseif($modified) { 
			?>
            <tr>
                <td colspan="2" class="vl_success">Sample Changes Captured Successfully!</td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
          <? } elseif($reviewed) { ?>
            <tr>
                <td colspan="2" class="vl_success">Review Notes Captured Successfully!</td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <? } ?>
            <tr>
                <td width="5%" align="center" valign="top"><a href="/samples/" class="vll_grey"><img src="/images/icon.samples.gif" border="0" /></a></td>
                <td width="95%" style="padding:0px 0px 0px 30px"><table width="100%" cellpadding="0" cellspacing="0" border="0" class="vl">
                  <tr>
                    <td><strong>Samples</strong></td>
                  </tr>
                  <tr>
                    <td class="vls_grey">Samples Management</td>
                  </tr>
                  <tr>
                    <td style="padding:10px 0px 0px 0px">
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
                        $query=mysqlquery("select * from vl_envelopes order by created desc limit $offset, $rowsToDisplay");
                        $xquery=0;
                        $xquery=mysqlquery("select * from vl_envelopes order by created desc");
                        //number pages
                        $numberPages=0;
                        $numberPages=ceil(mysqlnumrows($xquery)/$rowsToDisplay);
                        
                        if(mysqlnumrows($query)) {
                            //how many pages are there?
                            if($numberPages>1) {
                                echo "<tr><td style=\"padding:0px 0px 10px 0px\" class=\"vls_grey\"><strong>Pages:</strong> ".displayPagesLinks("/samples/",1,$numberPages,($pg?$pg:1),$default_radius)."</td></tr>";
                            }
                        ?>
                                <tr>
                                  <td>
                                  <div style="height: 250px; width: 100%; overflow: auto; padding:5px; border: 1px solid #d5e6cf">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td class="vl_tdsub" width="1%"><strong>#</strong></td>
                                          <td class="vl_tdsub" width="9%"><strong>Envelope&nbsp;#</strong></td>
                                          <td class="vl_tdsub" width="5%"><strong>Batch&nbsp;#</strong></td>
                                          <td class="vl_tdsub" width="5%"><strong>District</strong></td>
                                          <td class="vl_tdsub" width="65%"><strong>Facility</strong></td>
                                          <td class="vl_tdsub" width="10%"><strong>Samples</strong></td>
                                          <td class="vl_tdsub" width="5%"><strong>Actions</strong></td>
                                        </tr>
                                        <?
                                        $count=0;
                                        $count=$offset;
                                        $q=array();
                                        while($q=mysqlfetcharray($query)) {
                                            $count+=1;
                                        ?>
                                            <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><?=$count?></td>
                                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><?=$q["envelopeNumber"]?></td>
                                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><?=$q["batchNumber"]?></td>
                                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><?=getDetailedTableInfo2("vl_districts","id='$q[districtID]'","district")?></td>
                                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><?=getDetailedTableInfo2("vl_facilities","id='$q[facilityID]'","facility")?></td>
                                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>">0</td>
                                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><div class="vls_grey" style="padding:3px 0px 0px 0px"><a href="/samples/capture/<?=$q["id"]?>/">Input&nbsp;Samples</a>&nbsp;::&nbsp;<a href="/samples/modify/<?=$q["id"]?>/">Modify&nbsp;Samples</a>&nbsp;::&nbsp;<a href="javascript:if(confirm('Are you sure?')) { document.location.href='/samples/remove/<?=$q["id"]?>/'; }">Remove&nbsp;Samples</a></div></td>
                                            </tr>
                                        <? } ?>
                                   </table>
                                  </div>
                              </td>
                            </tr>
                        <? } ?>
                        </table>
                    </td>
                  </tr>
                </table></td>
            </tr>
          </table>