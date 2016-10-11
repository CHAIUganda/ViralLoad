<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}

if($remove) {
	//validate
	$remove=validate($remove);
	//remove envelope
	logDataRemoval("delete from vl_envelopes where id='$remove'");
	mysqlquery("delete from vl_envelopes where id='$remove'");
}
?>
<table width="100%" border="0" class="vl">
			<? if(!getDetailedTableInfo2("vl_envelopes","id!='' limit 1","id")) { ?>
            <tr>
                <td class="vl_error">
                There are No Envelopes on the System!<br />
				<a href="/envelopes/capture/">Click Here to input the First Envelope.</a></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <? } ?>
            <tr>
              <td class="toplinks" style="padding:0px 0px 10px 0px"><a class="toplinks" href="/dashboard/">HOME</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="toplinks" href="/envelopes/">ENVELOPES</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="toplinks" href="/envelopes/manage/">Manage Envelopes</a></td>
            </tr>
            <tr>
                <td style="padding: 5px 0px 15px 0px; border-bottom: 1px dashed #dfe6e6"><strong>Manage Envelopes</strong></td>
            </tr>
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
        $query=mysqlquery("select * from vl_envelopes order by created desc limit $offset, $rowsToDisplay");
        $xquery=0;
        $xquery=mysqlquery("select * from vl_envelopes order by created desc");
		//number pages
		$numberPages=0;
		$numberPages=ceil(mysqlnumrows($xquery)/$rowsToDisplay);
		
        if(mysqlnumrows($query)) {
			//how many pages are there?
			if($numberPages>1) {
				echo "<tr><td style=\"padding:0px 0px 10px 0px\" class=\"vls_grey\"><strong>Pages:</strong> ".displayPagesLinks("/envelopes/manage/",1,$numberPages,($pg?$pg:1),$default_radius)."</td></tr>";
			}
        ?>
            <tr>
	            <td style="padding:10px 0px 10px 0px" class="vls_grey"><strong><?=mysqlnumrows($xquery)?></strong> envelope<?=(mysqlnumrows($xquery)!=1?"s":"")?></td>
            </tr>
                <tr>
                  <td>
                  <div style="height: 250px; width: 100%; overflow: auto; padding:5px; border: 1px solid #d5e6cf">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td class="vl_tdsub" width="1%"><strong>#</strong></td>
                          <td class="vl_tdsub" width="9%"><strong>Envelope&nbsp;#</strong></td>
                          <td class="vl_tdsub" width="5%"><strong>Batch&nbsp;#</strong></td>
                          <td class="vl_tdsub" width="10%"><strong>District</strong></td>
                          <td class="vl_tdsub" width="70%"><strong>Facility</strong></td>
                          <td class="vl_tdsub" width="5%"><strong>Options</strong></td>
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
                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><div class="vls_grey" style="padding:3px 0px 0px 0px"><a href="javascript:if(confirm('Are you sure?')) { document.location.href='/envelopes/manage/remove/<?=$q["id"]?>/'; }">Remove</a>&nbsp;::&nbsp;<a href="/envelopes/manage/modify/<?=$q["id"]?>/">Modify</a></div></td>
                            </tr>
                        <? } ?>
 	               </table>
				  </div>
              </td>
            </tr>
            <tr>
	            <td style="padding:20px 0px 0px 0px"><a href="/envelopes/">Return to Envelopes</a> :: <a href="/dashboard/">Return to Dashboard</a></td>
            </tr>
	    <? } ?>
		</table>
              </td>
            </tr>
          </table>