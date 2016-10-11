<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}
?>
                  <div style="height: 250px; width: 100%; overflow: auto; padding:5px">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="55%" valign="top">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="vl_tdsub" width="95%"><strong>Sample Statistics</strong></td>
                                    <td class="vl_tdsub" width="5%" align="center"><strong>Value</strong></td>
                                </tr>
                                <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                                    <td class="vl_tdstandard">Cumulative Number of Samples Received</td>
                                    <td class="vl_tdstandard" align="center"><?=number_format((float)getDetailedTableInfo3("vl_samples","id>0","count(id)","num"))?></td>
                                </tr>
                                <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                                    <td class="vl_tdstandard" style="padding:8px 8px 8px 20px">DBS</td>
                                    <td class="vl_tdstandard" align="center"><?=number_format((float)getDetailedTableInfo3("vl_samples","sampleTypeID='1'","count(id)","num"))?></td>
                                </tr>
                                <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                                    <td class="vl_tdstandard" style="padding:8px 8px 8px 20px">Plasma</td>
                                    <td class="vl_tdstandard" align="center"><?=number_format((float)getDetailedTableInfo3("vl_samples","sampleTypeID='2'","count(id)","num"))?></td>
                                </tr>
                                <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                                    <td class="vl_tdstandard" style="padding:8px 8px 8px 20px">Whole Blood</td>
                                    <td class="vl_tdstandard" align="center"><?=number_format((float)getDetailedTableInfo3("vl_samples","sampleTypeID='3'","count(id)","num"))?></td>
                                </tr>
                                <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                                    <td class="vl_tdstandard" style="padding:8px 8px 8px 20px">Left Blank</td>
                                    <td class="vl_tdstandard" align="center"><?=number_format((float)getDetailedTableInfo3("vl_samples","sampleTypeID='4'","count(id)","num"))?></td>
                                </tr>
                                <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                                    <td class="vl_tdstandard">Cumulative Number of Tests</td>
                                    <td class="vl_tdstandard" align="center"><?=number_format((float)getDetailedTableInfo3("vl_samples","vlSampleID in (select distinct sampleID from vl_results_abbott) or vlSampleID in (select distinct SampleID from vl_results_roche)","count(id)","num"))?></td>
                                </tr>
                                <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                                    <td class="vl_tdstandard" style="padding:8px 8px 8px 20px">Number of Tests (Abbott)</td>
                                    <td class="vl_tdstandard" align="center"><?=number_format((float)getDetailedTableInfo3("vl_samples","vlSampleID=any(select distinct sampleID from vl_results_abbott)","count(id)","num"))?></td>
                                </tr>
                                <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                                    <td class="vl_tdnoborder" style="padding:8px 8px 8px 20px">Number of Tests (Roche)</td>
                                    <td class="vl_tdnoborder" align="center"><?=number_format((float)getDetailedTableInfo3("vl_samples","vlSampleID=any(select distinct SampleID from vl_results_roche)","count(id)","num"))?></td>
                                </tr>
                           </table>
                        </td>
                        <td width="45%" valign="top" style="padding:0px 0px 0px 10px">
                        	<div>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="vl_tdsub" width="95%"><strong>Patient Statistics</strong></td>
                                        <td class="vl_tdsub" width="5%" align="center"><strong>Value</strong></td>
                                    </tr>
                                    <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                                        <td class="vl_tdstandard">Cumulative Number of Patients</td>
                                        <td class="vl_tdstandard" align="center"><?=number_format((float)getDetailedTableInfo3("vl_patients","id>0","count(id)","num"))?></td>
                                    </tr>
                                    <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                                        <td class="vl_tdstandard" style="padding:8px 8px 8px 20px">Male</td>
                                        <td class="vl_tdstandard" align="center"><?=number_format((float)getDetailedTableInfo3("vl_patients","gender='Male'","count(id)","num"))?></td>
                                    </tr>
                                    <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                                        <td class="vl_tdstandard" style="padding:8px 8px 8px 20px">Female</td>
                                        <td class="vl_tdstandard" align="center"><?=number_format((float)getDetailedTableInfo3("vl_patients","gender='Female'","count(id)","num"))?></td>
                                    </tr>
                                    <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                                        <td class="vl_tdnoborder" style="padding:8px 8px 8px 20px">Left Blank</td>
                                        <td class="vl_tdnoborder" align="center"><?=number_format((float)getDetailedTableInfo3("vl_patients","gender='Left Blank'","count(id)","num"))?></td>
                                    </tr>
                               </table>
                        	</div>
                        	<div style="padding:1px 0px 0px 0px">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="vl_tdsub" width="95%"><strong>Lab TAT Statistics</strong></td>
                                        <td class="vl_tdsub" width="5%" align="center"><strong>Value</strong></td>
                                    </tr>
                                    <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                                        <td class="vl_tdstandard">Dispatch to Facility - Receipt at Lab</td>
                                        <td class="vl_tdstandard" align="center">&nbsp;</td>
                                    </tr>
                                    <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                                        <td class="vl_tdstandard">Receipt at Lab - Processing</td>
                                        <td class="vl_tdstandard" align="center">&nbsp;</td>
                                    </tr>
                                    <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                                        <td class="vl_tdnoborder">Collection at Facility - Dispatch</td>
                                        <td class="vl_tdnoborder" align="center">&nbsp;</td>
                                    </tr>
                               </table>
                        	</div>
                        </td>
                      </tr>
                    </table>
				  </div>
