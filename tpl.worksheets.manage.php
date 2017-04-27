<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}

if($remove) {
	//validate
	$remove=validate($remove);
	//remove worksheet
	logDataRemoval("delete from vl_samples_worksheetcredentials where id='$remove'");
	mysqlquery("delete from vl_samples_worksheetcredentials where id='$remove'");

	logDataRemoval("delete from vl_samples_worksheet where worksheetID='$remove'");
	mysqlquery("delete from vl_samples_worksheet where worksheetID='$remove'");

	logDataRemoval("delete from vl_results_roche where worksheetID='$remove'");
	mysqlquery("delete from vl_results_roche where worksheetID='$remove'");

	logDataRemoval("delete from vl_results_abbott where worksheetID='$remove'");
	mysqlquery("delete from vl_results_abbott where worksheetID='$remove'");
}

//encrypted samples
if($encryptedSample) {
	$searchQuery=validate(vlDecrypt($encryptedSample));
}
?>
<table width="100%" border="0" class="vl">
			<? if(!getDetailedTableInfo2("vl_samples_worksheetcredentials","id!='' limit 1","id")) { ?>
            <tr>
                <td class="vl_error">
                There are No Worksheets on the System!<br />
				Click Here to input the First <a href="/worksheets/capture.1.abbott/">Abbott</a> or <a href="/worksheets/capture.1.roche/">Roche</a> Worksheet.</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <? } elseif($changed) { ?>
            <tr>
                <td class="vl_success">Changes made to Worksheet Successfully!</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <? } elseif($print) { ?>
            <tr>
                <td class="vl_success">
                    <div><strong>Worksheet Created Successfully!</strong></div>
                    <div class="vls_grey" style="padding:3px 0px"><a href="#vl" onclick="window.open('/worksheets/print.detail/<?=$worksheetID?>/', 'printWorksheet', 'width=1000, height=600, toolbar=no, location=no, directories=no, resizable=no, status=yes, scrollbars=yes')">Click Here to Print this Worksheet</a>
                        <br>
                        <?= "<a href=\"javascript:windPop('/worksheets/print_bar_codes/$worksheetID/')\">Print bar codes</a>"; ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <? } ?>
            <tr>
              <td class="toplinks" style="padding:0px 0px 10px 0px"><a class="toplinks" href="/dashboard/">HOME</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="toplinks" href="/worksheets/">WORKSHEETS</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="toplinks" href="/worksheets/manage/">Manage Worksheets</a></td>
            </tr>
            <tr>
                <td style="padding: 5px 0px 15px 0px; border-bottom: 1px dashed #dfe6e6"><strong>Manage Worksheets</strong></td>
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
        $xquery=0;
		if(!$searchQuery) {
			$query=mysqlquery("select * from vl_samples_worksheetcredentials order by created desc limit $offset, $rowsToDisplay");
			$xquery=mysqlquery("select * from vl_samples_worksheetcredentials order by created desc");
		} else {
			$query=mysqlquery("select distinct vl_samples_worksheetcredentials.* from 
									vl_samples_worksheetcredentials 
										left join vl_samples_worksheet on vl_samples_worksheetcredentials.id=vl_samples_worksheet.worksheetID 
										left join vl_samples on vl_samples_worksheet.sampleID=vl_samples.id 
											where 
												(vl_samples_worksheetcredentials.worksheetReferenceNumber like '%$searchQuery%' or 
													vl_samples.vlSampleID like '%$searchQuery%' or 
														vl_samples.formNumber like '%$searchQuery%' or 
															 concat(vl_samples.lrCategory,vl_samples.lrEnvelopeNumber,'/',vl_samples.lrNumericID) like '%$searchQuery%') 
																order by created desc limit $offset, $rowsToDisplay");
			$xquery=mysqlquery("select distinct vl_samples_worksheetcredentials.* from 
									vl_samples_worksheetcredentials 
										left join vl_samples_worksheet on vl_samples_worksheetcredentials.id=vl_samples_worksheet.worksheetID 
										left join vl_samples on vl_samples_worksheet.sampleID=vl_samples.id 
											where 
												(vl_samples_worksheetcredentials.worksheetReferenceNumber like '%$searchQuery%' or 
													vl_samples.vlSampleID like '%$searchQuery%' or 
														vl_samples.formNumber like '%$searchQuery%' or 
															concat(vl_samples.lrCategory,vl_samples.lrEnvelopeNumber,'/',vl_samples.lrNumericID) like '%$searchQuery%') 
																order by created desc");
		}
		//number pages
		$numberPages=0;
		$numberPages=ceil(mysqlnumrows($xquery)/$rowsToDisplay);
		
        if(mysqlnumrows($query)) {
			//how many pages are there?
			if($numberPages>1) {
				echo "<tr><td style=\"padding:0px 0px 10px 0px\" class=\"vls_grey\"><strong>Pages:</strong> ".displayPagesLinks("/worksheets/manage/",1,$numberPages,($pg?$pg:1),$default_radius)."</td></tr>";
			}
        ?>
            <tr>
	            <td style="padding:10px 0px 10px 0px" class="vls_grey"><strong><?=mysqlnumrows($xquery)?></strong> Worksheet<?=(mysqlnumrows($xquery)!=1?"s":"")?></td>
            </tr>
                <tr>
                  <td>
                  <div style="height: 250px; width: 100%; overflow: auto; padding:5px; border: 1px solid #d5e6cf">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td class="vl_tdsub" width="1%"><strong>#</strong></td>
                          <!-- <td class="vl_tdsub" width="9%"><strong>Worksheet&nbsp;Name</strong></td> -->
                          <td class="vl_tdsub" width="5%"><strong>Reference&nbsp;#</strong></td>
                          <td class="vl_tdsub" width="5%"><strong>Samples</strong></td>
                          <td class="vl_tdsub" width="5%"><strong>Results</strong></td>
                          <td class="vl_tdsub" width="10%"><strong>Worksheet&nbsp;Type</strong></td>
                          <td class="vl_tdsub" width="59%"><strong>Machine&nbsp;Type</strong></td>
                          <td class="vl_tdsub" width="5%"><strong>x&nbsp;Factor</strong></td>
                          <td class="vl_tdsub" width="5%"><strong>Printed</strong></td>
                          <td class="vl_tdsub" width="5%"><strong>Options</strong></td>
                        </tr>
                    	<?
                        $count=0;
                        $count=$offset;
                        $q=array();
                        while($q=mysqlfetcharray($query)) {
                            $count+=1;
							$worksheet=0;
							if($type=="roche") {
								$worksheet="<a href=\"#vl\" onclick=\"window.open('/worksheets/print.detail/$q[id]/', 'printWorksheet', 'width=1000, height=600, toolbar=no, location=no, directories=no, resizable=no, status=yes, scrollbars=yes')\">Print&nbsp;Worksheet</a>";
							} else {
								$worksheet="<a href=\"#vl\" onclick=\"window.open('/worksheets/print.detail/$q[id]/', 'printWorksheet', 'width=1000, height=600, toolbar=no, location=no, directories=no, resizable=no, status=yes, scrollbars=yes')\">Print&nbsp;Worksheet</a>";
								//$worksheet="<a href=\"/worksheets/print.detail/$worksheetID/\" target=\"_blank\">Print&nbsp;Worksheet</a>";
							}
							//printed?
							$printed=0;
							if(getDetailedTableInfo2("vl_logs_worksheetsamplesprinted","worksheetID='$q[id]' limit 1","id")) {
								$printed="<font color=\"#009900\">Yes</font>";
							} else {
								$printed="<font color=\"#FF0000\">No</font>";
							}
							//factor
							$factor=0;
							$factor=getDetailedTableInfo2("vl_results_multiplicationfactor","worksheetID='$q[id]' limit 1","factor");
							if(!$factor) {
								$factor=1;
							}
                        ?>
                            <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><?=$count?></td>
                                <!-- <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><?=$q["worksheetName"]?></td> -->
                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><?=$q["worksheetReferenceNumber"]?></td>
                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><?=number_format((float)getDetailedTableInfo3("vl_samples_worksheet","worksheetID='$q[id]'","count(id)","num"))?></td>
                                <? if(getDetailedTableInfo3(($q["machineType"]=="abbott"?"vl_results_abbott":"vl_results_roche"),"worksheetID='$q[id]'","count(id)","num")) { ?>
                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><a href="/results/<?=$q["machineType"]?>/pg/1/<?=$q["id"]?>/"><?=number_format((float)getDetailedTableInfo3(($q["machineType"]=="abbott"?"vl_results_abbott":"vl_results_roche"),"worksheetID='$q[id]'","count(id)","num"))?></a></td>
                                <? } else { ?>
                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><?=number_format((float)getDetailedTableInfo3(($q["machineType"]=="abbott"?"vl_results_abbott":"vl_results_roche"),"worksheetID='$q[id]'","count(id)","num"))?></td>
                                <? } ?>
                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><?=getDetailedTableInfo2("vl_appendix_sampletype","id='$q[worksheetType]' limit 1","appendix")?></td>
                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>"><?=$q["machineType"]?></td>
                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>" align="center"><?=$factor?></td>
                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>" align="center"><?=$printed?></td>
                                <td class="<?=($count<mysqlnumrows($xquery)?"vl_tdstandard":"vl_tdnoborder")?>">
                                    <?php $print_barcodes = "<a href=\"javascript:windPop('/worksheets/print_bar_codes/$q[id]/')\">barcodes</a>"; ?>
                                <? if(!getDetailedTableInfo3("vl_samples_worksheet","worksheetID='$q[id]'","count(id)","num")) { ?>
                                <div class="vls_grey" style="padding:3px 0px 0px 0px"><a href="javascript:if(confirm('Are you sure?')) { document.location.href='/worksheets/manage/remove/<?=$q["id"]?>/'; }">Remove</a>&nbsp;::<?=$print_barcodes?>&nbsp;<a href="/worksheets/manage/modify/<?=$q["id"]?>/">&nbsp;::Modify&nbsp;Worksheet&nbsp;Credentials</a>::&nbsp;<a href="/worksheets/capture.2/<?=$q["id"]?>/1/">Modify&nbsp;Worksheet&nbsp;Samples</a></div>
								<? } else { ?>
								<div class="vls_grey" style="padding:3px 0px 0px 0px"><a href="javascript:if(confirm('Are you sure?')) { document.location.href='/worksheets/manage/remove/<?=$q["id"]?>/'; }">Remove</a>&nbsp;::<?=$print_barcodes?>&nbsp;<a href="/worksheets/view.detail/<?=$q["id"]?>/">&nbsp;::View&nbsp;Detail</a>&nbsp;::&nbsp;<?=$worksheet?>&nbsp;::&nbsp;<a href="/worksheets/upload.results/<?=$q["id"]?>/">Upload&nbsp;Results</a></div>
                                <? } ?>
                                </td>
                            </tr>
                        <? } ?>
 	               </table>
				  </div>
              </td>
            </tr>
            <tr>
	            <td style="padding:20px 0px 0px 0px"><a href="/worksheets/">Return to Worksheets</a> :: <a href="/dashboard/">Return to Dashboard</a></td>
            </tr>
	    <? } else { ?>
            <tr>
                <td class="vl_error">There are No Worksheets or Samples in Worksheets matching the Search Criteria <strong><?=$searchQuery?></strong>!</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
	    <? } ?>
		</table>
              </td>
            </tr>
          </table>
          <script type="text/javascript">
          function windPop(link) {
            window.open(link,"zzz","width=1100,height=1000,menubar=no,resizable=yes,scrollbars=yes");
          } 
          </script>