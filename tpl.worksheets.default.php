<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}

$type=validate($type);
if($printID) {
	$printID=validate($printID);
}

if($type=="roche") {
	$printType="printIDRoche";
} else {
	$printType="printIDAbbott";
	$type="abbott";
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
						<td colspan="2" class="vl_success">Worksheet Captured Successfully!</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<? 
				}
			} elseif($modified) { 
			?>
            <tr>
                <td colspan="2" class="vl_success">Worksheet Changes Captured Successfully!</td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
          <? } elseif($print) { ?>
            <tr>
                <td colspan="2" class="vl_success">
                	<div>Worksheet Credentials Captured Successfully!</div>
                    <div class="vls" style="padding:5px 0px 0px 0px"><a href="#vl" onclick="window.open('/worksheets/print.detail/<?=$type?>/<?=$printID?>/', 'printWorksheet', 'width=1000, height=600, toolbar=no, location=no, directories=no, resizable=no, status=yes, scrollbars=yes')" class="vls">Click here to Print Worksheet.</a></div>
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <? } ?>
            <tr>
                <td width="5%" align="center" valign="top"><a href="/samples/" class="vll_grey"><img src="/images/icon.worksheets.gif" border="0" /></a></td>
                <td width="95%" style="padding:0px 0px 0px 30px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td valign="top" width="70%" style="padding:0px 10px 0px 0px"><table width="100%" cellpadding="0" cellspacing="0" border="0" class="vl">
                      <tr>
                        <td><strong>Samples</strong></td>
                      </tr>
                      <tr>
                        <td class="vls_grey">Samples Management</td>
                      </tr>
                      <tr>
                        <td style="padding:20px 0px 10px 0px; border-bottom: 1px dashed #dfe6e6"><a href="/worksheets/capture.1.abbott/">Create a New Abbott Worksheet</a>
                          <div class="vls_grey" style="padding:2px 0px 0px 0px">Select Samples for inclusion within the Worksheet.</div></td>
                      </tr>
                      <tr>
                        <td style="padding:10px 0px 10px 0px; border-bottom: 1px dashed #dfe6e6"><a href="/worksheets/capture.1.roche/">Create a New Roche Worksheet</a>
                        <div class="vls_grey" style="padding:2px 0px 0px 0px">Select Samples for inclusion within the Worksheet.</div></td>
                      </tr>
                      <tr>
                        <td style="padding:10px 0px 0px 0px"><a href="/worksheets/manage/">Print/Preview/Manage a Worksheet</a>
                          <div class="vls_grey" style="padding:2px 0px 0px 0px">Edit Data on Previously Captured Patients</div></td>
                      </tr>
                    </table></td>
                    <td valign="top" width="30%">
                    	<div class="vls_grey" style="padding:5px"><strong>Supporting Information</strong></div>
                    	<div><img src="/images/spacer.gif" width="3" height="3" /></div>
                    	<div class="vls_grey" style="padding:5px; border: 1px solid #f0d7b4; background-color: #fffee6">Samples in Database (Total): <?=number_format((float)getDetailedTableInfo3("vl_samples","id>0","count(id)","num"))?></div>
                    	<div><img src="/images/spacer.gif" width="3" height="3" /></div>
                    	<div class="vls_grey" style="padding:5px; border: 1px solid #f0d7b4; background-color: #fffee6">Pending Samples (All): <?=number_format((float)getDetailedTableInfo3("vl_samples_verify","id>0 and sampleID not in (select distinct sampleID from vl_samples_worksheet)","count(id)","num"))?></div>
                    	<div><img src="/images/spacer.gif" width="3" height="3" /></div>
                    	<div class="vls_grey" style="padding:5px; border: 1px solid #f0d7b4; background-color: #fffee6">Pending Samples (DBS): <?=number_format((float)getDetailedTableInfo3("vl_samples_verify,vl_samples","vl_samples.id=vl_samples_verify.sampleID and vl_samples.sampleTypeID='".getDetailedTableInfo2("vl_appendix_sampletype","appendix='DBS' limit 1","id")."' and vl_samples_verify.sampleID not in (select distinct sampleID from vl_samples_worksheet)","count(vl_samples_verify.id)","num"))?></div>
                    	<div><img src="/images/spacer.gif" width="3" height="3" /></div>
                    	<div class="vls_grey" style="padding:5px; border: 1px solid #f0d7b4; background-color: #fffee6">Pending Samples (Plasma): <?=number_format((float)getDetailedTableInfo3("vl_samples_verify,vl_samples","vl_samples.id=vl_samples_verify.sampleID and vl_samples.sampleTypeID='".getDetailedTableInfo2("vl_appendix_sampletype","appendix='Plasma' limit 1","id")."' and vl_samples_verify.sampleID not in (select distinct sampleID from vl_samples_worksheet)","count(vl_samples_verify.id)","num"))?></div>
                    	<div><img src="/images/spacer.gif" width="3" height="3" /></div>
                    	<div class="vls_grey" style="padding:5px; border: 1px solid #f0d7b4; background-color: #fffee6">Pending Samples (Whole Blood): <?=number_format((float)getDetailedTableInfo3("vl_samples_verify,vl_samples","vl_samples.id=vl_samples_verify.sampleID and vl_samples.sampleTypeID='".getDetailedTableInfo2("vl_appendix_sampletype","appendix='Whole blood' limit 1","id")."' and vl_samples_verify.sampleID not in (select distinct sampleID from vl_samples_worksheet)","count(vl_samples_verify.id)","num"))?></div>
                    	<div><img src="/images/spacer.gif" width="3" height="3" /></div>
                    	<div class="vls_grey" style="padding:5px; border: 1px solid #f0d7b4; background-color: #fffee6">Pending Samples (Left Blank): <?=number_format((float)getDetailedTableInfo3("vl_samples_verify,vl_samples","vl_samples.id=vl_samples_verify.sampleID and vl_samples.sampleTypeID='".getDetailedTableInfo2("vl_appendix_sampletype","appendix='Left Blank' limit 1","id")."' and vl_samples_verify.sampleID not in (select distinct sampleID from vl_samples_worksheet)","count(vl_samples_verify.id)","num"))?></div>
                    	<div><img src="/images/spacer.gif" width="3" height="3" /></div>
                    	<div class="vls_grey" style="padding:5px; border: 1px solid #f0d7b4; background-color: #fffee6">Failed Available Samples (Abbott, DBS): <?=number_format((float)getDetailedTableInfo3("vl_results_abbott,vl_samples","vl_samples.vlSampleID=vl_results_abbott.sampleID and vl_samples.sampleTypeID='".getDetailedTableInfo2("vl_appendix_sampletype","appendix='DBS' limit 1","id")."' and vl_results_abbott.sampleID not in (select distinct sampleID from vl_results_abbott where 
						(trim(result)!='-1.00' and 
							trim(result)!='3153 There is insufficient volume in the vessel to perform an aspirate or dispense operation.' and 
								trim(result)!='3109 A no liquid detected error was encountered by the Liquid Handler.' and 
									trim(result)!='A no liquid detected error was encountered by the Liquid Handler.' and 
										trim(result)!='Unable to process result, instrument response is invalid.' and 
											trim(result)!='3118 A clot limit passed error was encountered by the Liquid Handler.' and 
												trim(result)!='3130 A less liquid than expected error was encountered by the Liquid Handler.' and 
													trim(result)!='3131 A more liquid than expected error was encountered by the Liquid Handler.' and 
														trim(result)!='3152 The specified submerge position for the requested liquid volume exceeds the calibrated Z bottom' and 
															trim(result)!='4455 Unable to process result, instrument response is invalid.' and 
																trim(result)!='A no liquid detected error was encountered by the Liquid Handler.' and 
																	trim(result)!='Failed          Internal control cycle number is too high. Valid range is [18.48, 22.48].' and 
																		trim(result)!='Failed          Failed            Internal control cycle number is too high. Valid range is [18.48,' and 
																			trim(result)!='Failed          Failed          Internal control cycle number is too high. Valid range is [18.48, 2' and 
																				trim(result)!='OPEN' and 
																					trim(result)!='There is insufficient volume in the vessel to perform an aspirate or dispense operation.' and 
																						trim(result)!='Unable to process result, instrument response is invalid.' and 
																							substr(flags,1,47)!='4442 Internal control cycle number is too high.')) 
						and 
						(trim(vl_results_abbott.result)='-1.00' or 
							trim(vl_results_abbott.result)='3153 There is insufficient volume in the vessel to perform an aspirate or dispense operation.' or 
								trim(vl_results_abbott.result)='3109 A no liquid detected error was encountered by the Liquid Handler.' or 
									trim(vl_results_abbott.result)='A no liquid detected error was encountered by the Liquid Handler.' or 
										trim(vl_results_abbott.result)='Unable to process result, instrument response is invalid.' or 
											trim(vl_results_abbott.result)='3118 A clot limit passed error was encountered by the Liquid Handler.' or 
												trim(vl_results_abbott.result)='3130 A less liquid than expected error was encountered by the Liquid Handler.' or 
													trim(vl_results_abbott.result)='3131 A more liquid than expected error was encountered by the Liquid Handler.' or 
														trim(vl_results_abbott.result)='3152 The specified submerge position for the requested liquid volume exceeds the calibrated Z bottom' or 
															trim(vl_results_abbott.result)='4455 Unable to process result, instrument response is invalid.' or 
																trim(vl_results_abbott.result)='A no liquid detected error was encountered by the Liquid Handler.' or 
																	trim(vl_results_abbott.result)='Failed          Internal control cycle number is too high. Valid range is [18.48, 22.48].' or 
																		trim(vl_results_abbott.result)='Failed          Failed            Internal control cycle number is too high. Valid range is [18.48,' or 
																			trim(vl_results_abbott.result)='Failed          Failed          Internal control cycle number is too high. Valid range is [18.48, 2' or 
																				trim(vl_results_abbott.result)='OPEN' or 
																					trim(vl_results_abbott.result)='There is insufficient volume in the vessel to perform an aspirate or dispense operation.' or 
																						trim(vl_results_abbott.result)='Unable to process result, instrument response is invalid.' or 
																							substr(vl_results_abbott.flags,1,47)='4442 Internal control cycle number is too high.')","count(distinct vl_results_abbott.sampleID)","num"))?></div>
                    	<div><img src="/images/spacer.gif" width="3" height="3" /></div>
                    	<div class="vls_grey" style="padding:5px; border: 1px solid #f0d7b4; background-color: #fffee6">Failed Available Samples (Abbott, Plasma): <?=number_format((float)getDetailedTableInfo3("vl_results_abbott,vl_samples","vl_samples.vlSampleID=vl_results_abbott.sampleID and vl_samples.sampleTypeID='".getDetailedTableInfo2("vl_appendix_sampletype","appendix='Plasma' limit 1","id")."' and vl_results_abbott.sampleID not in (select distinct sampleID from vl_results_abbott where 
						(trim(result)!='-1.00' and 
							trim(result)!='3153 There is insufficient volume in the vessel to perform an aspirate or dispense operation.' and 
								trim(result)!='3109 A no liquid detected error was encountered by the Liquid Handler.' and 
									trim(result)!='A no liquid detected error was encountered by the Liquid Handler.' and 
										trim(result)!='Unable to process result, instrument response is invalid.' and 
											trim(result)!='3118 A clot limit passed error was encountered by the Liquid Handler.' and 
												trim(result)!='3130 A less liquid than expected error was encountered by the Liquid Handler.' and 
													trim(result)!='3131 A more liquid than expected error was encountered by the Liquid Handler.' and 
														trim(result)!='3152 The specified submerge position for the requested liquid volume exceeds the calibrated Z bottom' and 
															trim(result)!='4455 Unable to process result, instrument response is invalid.' and 
																trim(result)!='A no liquid detected error was encountered by the Liquid Handler.' and 
																	trim(result)!='Failed          Internal control cycle number is too high. Valid range is [18.48, 22.48].' and 
																		trim(result)!='Failed          Failed            Internal control cycle number is too high. Valid range is [18.48,' and 
																			trim(result)!='Failed          Failed          Internal control cycle number is too high. Valid range is [18.48, 2' and 
																				trim(result)!='OPEN' and 
																					trim(result)!='There is insufficient volume in the vessel to perform an aspirate or dispense operation.' and 
																						trim(result)!='Unable to process result, instrument response is invalid.' and 
																							substr(flags,1,47)!='4442 Internal control cycle number is too high.')) 
						and 
						(trim(vl_results_abbott.result)='-1.00' or 
							trim(vl_results_abbott.result)='3153 There is insufficient volume in the vessel to perform an aspirate or dispense operation.' or 
								trim(vl_results_abbott.result)='3109 A no liquid detected error was encountered by the Liquid Handler.' or 
									trim(vl_results_abbott.result)='A no liquid detected error was encountered by the Liquid Handler.' or 
										trim(vl_results_abbott.result)='Unable to process result, instrument response is invalid.' or 
											trim(vl_results_abbott.result)='3118 A clot limit passed error was encountered by the Liquid Handler.' or 
												trim(vl_results_abbott.result)='3130 A less liquid than expected error was encountered by the Liquid Handler.' or 
													trim(vl_results_abbott.result)='3131 A more liquid than expected error was encountered by the Liquid Handler.' or 
														trim(vl_results_abbott.result)='3152 The specified submerge position for the requested liquid volume exceeds the calibrated Z bottom' or 
															trim(vl_results_abbott.result)='4455 Unable to process result, instrument response is invalid.' or 
																trim(vl_results_abbott.result)='A no liquid detected error was encountered by the Liquid Handler.' or 
																	trim(vl_results_abbott.result)='Failed          Internal control cycle number is too high. Valid range is [18.48, 22.48].' or 
																		trim(vl_results_abbott.result)='Failed          Failed            Internal control cycle number is too high. Valid range is [18.48,' or 
																			trim(vl_results_abbott.result)='Failed          Failed          Internal control cycle number is too high. Valid range is [18.48, 2' or 
																				trim(vl_results_abbott.result)='OPEN' or 
																					trim(vl_results_abbott.result)='There is insufficient volume in the vessel to perform an aspirate or dispense operation.' or 
																						trim(vl_results_abbott.result)='Unable to process result, instrument response is invalid.' or 
																							substr(vl_results_abbott.flags,1,47)='4442 Internal control cycle number is too high.')","count(distinct vl_results_abbott.sampleID)","num"))?></div>
                    	<div><img src="/images/spacer.gif" width="3" height="3" /></div>
                    	<div class="vls_grey" style="padding:5px; border: 1px solid #f0d7b4; background-color: #fffee6">Failed Available Samples (Abbott, Whole Blood): <?=number_format((float)getDetailedTableInfo3("vl_results_abbott,vl_samples","vl_samples.vlSampleID=vl_results_abbott.sampleID and vl_samples.sampleTypeID='".getDetailedTableInfo2("vl_appendix_sampletype","appendix='Whole blood' limit 1","id")."' and vl_results_abbott.sampleID not in (select distinct sampleID from vl_results_abbott where 
						(trim(result)!='-1.00' and 
							trim(result)!='3153 There is insufficient volume in the vessel to perform an aspirate or dispense operation.' and 
								trim(result)!='3109 A no liquid detected error was encountered by the Liquid Handler.' and 
									trim(result)!='A no liquid detected error was encountered by the Liquid Handler.' and 
										trim(result)!='Unable to process result, instrument response is invalid.' and 
											trim(result)!='3118 A clot limit passed error was encountered by the Liquid Handler.' and 
												trim(result)!='3130 A less liquid than expected error was encountered by the Liquid Handler.' and 
													trim(result)!='3131 A more liquid than expected error was encountered by the Liquid Handler.' and 
														trim(result)!='3152 The specified submerge position for the requested liquid volume exceeds the calibrated Z bottom' and 
															trim(result)!='4455 Unable to process result, instrument response is invalid.' and 
																trim(result)!='A no liquid detected error was encountered by the Liquid Handler.' and 
																	trim(result)!='Failed          Internal control cycle number is too high. Valid range is [18.48, 22.48].' and 
																		trim(result)!='Failed          Failed            Internal control cycle number is too high. Valid range is [18.48,' and 
																			trim(result)!='Failed          Failed          Internal control cycle number is too high. Valid range is [18.48, 2' and 
																				trim(result)!='OPEN' and 
																					trim(result)!='There is insufficient volume in the vessel to perform an aspirate or dispense operation.' and 
																						trim(result)!='Unable to process result, instrument response is invalid.' and 
																							substr(flags,1,47)!='4442 Internal control cycle number is too high.')) 
						and 
						(trim(vl_results_abbott.result)='-1.00' or 
							trim(vl_results_abbott.result)='3153 There is insufficient volume in the vessel to perform an aspirate or dispense operation.' or 
								trim(vl_results_abbott.result)='3109 A no liquid detected error was encountered by the Liquid Handler.' or 
									trim(vl_results_abbott.result)='A no liquid detected error was encountered by the Liquid Handler.' or 
										trim(vl_results_abbott.result)='Unable to process result, instrument response is invalid.' or 
											trim(vl_results_abbott.result)='3118 A clot limit passed error was encountered by the Liquid Handler.' or 
												trim(vl_results_abbott.result)='3130 A less liquid than expected error was encountered by the Liquid Handler.' or 
													trim(vl_results_abbott.result)='3131 A more liquid than expected error was encountered by the Liquid Handler.' or 
														trim(vl_results_abbott.result)='3152 The specified submerge position for the requested liquid volume exceeds the calibrated Z bottom' or 
															trim(vl_results_abbott.result)='4455 Unable to process result, instrument response is invalid.' or 
																trim(vl_results_abbott.result)='A no liquid detected error was encountered by the Liquid Handler.' or 
																	trim(vl_results_abbott.result)='Failed          Internal control cycle number is too high. Valid range is [18.48, 22.48].' or 
																		trim(vl_results_abbott.result)='Failed          Failed            Internal control cycle number is too high. Valid range is [18.48,' or 
																			trim(vl_results_abbott.result)='Failed          Failed          Internal control cycle number is too high. Valid range is [18.48, 2' or 
																				trim(vl_results_abbott.result)='OPEN' or 
																					trim(vl_results_abbott.result)='There is insufficient volume in the vessel to perform an aspirate or dispense operation.' or 
																						trim(vl_results_abbott.result)='Unable to process result, instrument response is invalid.' or 
																							substr(vl_results_abbott.flags,1,47)='4442 Internal control cycle number is too high.')","count(distinct vl_results_abbott.sampleID)","num"))?></div>
                    	<div><img src="/images/spacer.gif" width="3" height="3" /></div>
                    	<div class="vls_grey" style="padding:5px; border: 1px solid #f0d7b4; background-color: #fffee6">Failed Available Samples (Roche, DBS):  <?=number_format((float)getDetailedTableInfo3("vl_results_roche,vl_samples","vl_samples.vlSampleID=vl_results_roche.SampleID and vl_samples.sampleTypeID='".getDetailedTableInfo2("vl_appendix_sampletype","appendix='DBS' limit 1","id")."' and vl_results_roche.SampleID not in (select distinct SampleID from vl_results_roche where (Result!='Failed' and Result!='Invalid')) and (vl_results_roche.Result='Failed' or vl_results_roche.Result='Invalid')","count(distinct vl_results_roche.SampleID)","num"))?></div>
                    	<div><img src="/images/spacer.gif" width="3" height="3" /></div>
                    	<div class="vls_grey" style="padding:5px; border: 1px solid #f0d7b4; background-color: #fffee6">Failed Available Samples (Roche, Plasma):  <?=number_format((float)getDetailedTableInfo3("vl_results_roche,vl_samples","vl_samples.vlSampleID=vl_results_roche.SampleID and vl_samples.sampleTypeID='".getDetailedTableInfo2("vl_appendix_sampletype","appendix='Plasma' limit 1","id")."' and vl_results_roche.SampleID not in (select distinct SampleID from vl_results_roche where (Result!='Failed' and Result!='Invalid')) and (vl_results_roche.Result='Failed' or vl_results_roche.Result='Invalid')","count(distinct vl_results_roche.SampleID)","num"))?></div>
                    	<div><img src="/images/spacer.gif" width="3" height="3" /></div>
                    	<div class="vls_grey" style="padding:5px; border: 1px solid #f0d7b4; background-color: #fffee6">Failed Available Samples (Roche, Whole Blood):  <?=number_format((float)getDetailedTableInfo3("vl_results_roche,vl_samples","vl_samples.vlSampleID=vl_results_roche.SampleID and vl_samples.sampleTypeID='".getDetailedTableInfo2("vl_appendix_sampletype","appendix='Whole blood' limit 1","id")."' and vl_results_roche.SampleID not in (select distinct SampleID from vl_results_roche where (Result!='Failed' and Result!='Invalid')) and (vl_results_roche.Result='Failed' or vl_results_roche.Result='Invalid')","count(distinct vl_results_roche.SampleID)","num"))?></div>
                    </td>
                  </tr>
                </table></td>
            </tr>
          </table>