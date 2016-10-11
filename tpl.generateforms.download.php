<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}
?>
<table width="100%" border="0" class="vl">
			<? if(!getDetailedTableInfo2("vl_forms_clinicalrequest","id!='' limit 1","id")) { ?>
            <tr>
                <td class="vl_error">
                There are No Forms on the System!<br />
				<a href="/generateforms/">Click Here to Generate the First Set of Forms.</a></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <? } ?>
            <tr>
              <td class="toplinks" style="padding:0px 0px 10px 0px"><a class="toplinks" href="/dashboard/">HOME</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="toplinks" href="/generateforms/">GENERATE FORMS</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="toplinks" href="/generateforms/download/">Download Historical Forms</a></td>
            </tr>
            <tr>
                <td style="padding: 5px 0px 15px 0px; border-bottom: 1px dashed #dfe6e6"><strong>Download Forms</strong></td>
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
        $query=mysqlquery("select distinct refNumber from vl_forms_clinicalrequest order by created desc limit $offset, $rowsToDisplay");
        $xquery=0;
        $xquery=mysqlquery("select distinct refNumber from vl_forms_clinicalrequest order by created desc");
		//number pages
		$numberPages=0;
		$numberPages=ceil(mysqlnumrows($xquery)/$rowsToDisplay);
		
        if(mysqlnumrows($query)) {
			//how many pages are there?
			if($numberPages>1) {
				echo "<tr><td style=\"padding:0px 0px 10px 0px\" class=\"vls_grey\"><strong>Pages:</strong> ".displayPagesLinks("/generateforms/download/pg/",1,$numberPages,($pg?$pg:1),$default_radius)."</td></tr>";
			}
        ?>
            <tr>
	            <td style="padding:10px 0px 10px 0px" class="vls_grey"><strong><?=mysqlnumrows($xquery)?></strong> Batch<?=(mysqlnumrows($xquery)!=1?"es":"")?></td>
            </tr>
                <tr>
                  <td>
                  <div style="height: 250px; width: 100%; overflow: auto; padding:5px; border: 1px solid #d5e6cf">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td class="vl_tdsub" width="1%"><strong>#</strong></td>
                          <td class="vl_tdsub" width="79%"><strong>Reference&nbsp;#</strong></td>
                          <td class="vl_tdsub" width="5%"><strong>Downloaded</strong></td>
                          <td class="vl_tdsub" width="5%"><strong>Downloadable</strong></td>
                          <td class="vl_tdsub" width="10%"><strong>Options</strong></td>
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
                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>">
                                	<div>Clinical Request Form # <?=$q["refNumber"]?></div>
                                    <div style="padding:5px 0px 0px 0px" class="vls_grey"><strong><?=number_format((float)getDetailedTableInfo3("vl_forms_clinicalrequest","refNumber='$q[refNumber]'","count(id)","num"))?></strong> (<?=getDetailedTableInfo2("vl_forms_clinicalrequest","refNumber='$q[refNumber]' order by formNumber asc limit 1","formNumber")?> to <?=getDetailedTableInfo2("vl_forms_clinicalrequest","refNumber='$q[refNumber]' order by formNumber desc limit 1","formNumber")?>)</div>
                                </td>
                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>" align="center">
                                <? 
								if(getDetailedTableInfo2("vl_logs_downloadedclinicalforms","refNumber='$q[refNumber]' limit 1","id")) {
									echo "<font color=\"#009900\">Yes</font>";
								} else {
									echo "<font color=\"#FF0000\">No</font>";
								}
								?>
                                </td>
                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>" align="center">
                                <? 
								if(is_file($system_default_path."downloads.forms/Clinical.Request.Form.$q[refNumber].pdf")) {
									echo "<font color=\"#009900\">Yes</font>";
								} else {
									echo "<font color=\"#FF0000\">No</font>";
								}
								?>
                                </td>
                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><div class="vls_grey" style="padding:3px 0px 0px 0px"><!--<a href="/generateforms/download/log/<?=$q["refNumber"]?>/" target="_blank">Download&nbsp;Batch&nbsp;Form</a>&nbsp;::&nbsp;--><a href="#" onclick="iDisplayMessage('/generateforms/preview/<?=$q["refNumber"]?>/')">View/Download&nbsp;Individual&nbsp;Forms</a></div></td>
                            </tr>
                        <? } ?>
 	               </table>
				  </div>
              </td>
            </tr>
            <tr>
	            <td style="padding:20px 0px 0px 0px"><a href="/generateforms/">Return to Generate Forms</a> :: <a href="/dashboard/">Return to Dashboard</a></td>
            </tr>
	    <? } ?>
		</table>
              </td>
            </tr>
          </table>