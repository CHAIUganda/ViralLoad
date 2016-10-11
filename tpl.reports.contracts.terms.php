<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}
?>
<table width="100%" border="0" class="vl">
            <tr>
              <td class="toplinks" style="padding:0px 0px 10px 0px"><a class="toplinks" href="/dashboard/">HOME</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="toplinks" href="/reports/">REPORTS</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="toplinks" href="/reports/contracts.terms/">CONTRACTS BY TERM</a></td>
            </tr>
            <tr>
                <td style="padding: 5px 0px 15px 0px; border-bottom: 1px dashed #dfe6e6"><strong>Contracts by Term</strong></td>
            </tr>
            <tr>
              <td>
				<table width="100%" border="0" class="vl">
                <tr>
                  <td style="padding:10px 0px 0px 0px">
                  <div style="height: 250px; width: 100%; overflow: auto; padding:5px; border: 1px solid #d5e6cf">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td class="vl_tdsub" width="1%"><strong>#</strong></td>
                          <td class="vl_tdsub" width="94%"><strong>&nbsp;</strong></td>
                          <td class="vl_tdsub" width="5%"><strong>Totals</strong></td>
                        </tr>
						<?
						//contracts
						$numContract=0;
						$numContract=getDetailedTableInfo3("vl_contracts","contractTerm='6 Months'","count(id)","num");
						$numContract=number_format((float)$numContract);
						?>
                        <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                            <td class="vl_tdstandard">1</td>
                            <td class="vl_tdstandard">6 Months</td>
                            <td class="vl_tdstandard" align="center"><?=$numContract?></td>
                        </tr>
						<?
						//contracts
						$numContract=0;
						$numContract=getDetailedTableInfo3("vl_contracts","contractTerm='1 Year'","count(id)","num");
						$numContract=number_format((float)$numContract);
						?>
                        <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                            <td class="vl_tdstandard">2</td>
                            <td class="vl_tdstandard">1 Year</td>
                            <td class="vl_tdstandard" align="center"><?=$numContract?></td>
                        </tr>
						<?
						//contracts
						$numContract=0;
						$numContract=getDetailedTableInfo3("vl_contracts","contractTerm='2 Years'","count(id)","num");
						$numContract=number_format((float)$numContract);
						?>
                        <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                            <td class="vl_tdstandard">3</td>
                            <td class="vl_tdstandard">2 Years</td>
                            <td class="vl_tdstandard" align="center"><?=$numContract?></td>
                        </tr>
						<?
						//contracts
						$numContract=0;
						$numContract=getDetailedTableInfo3("vl_contracts","contractTerm='3 Years'","count(id)","num");
						$numContract=number_format((float)$numContract);
						?>
                        <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                            <td class="vl_tdnoborder">4</td>
                            <td class="vl_tdnoborder">3 Years</td>
                            <td class="vl_tdnoborder" align="center"><?=$numContract?></td>
                        </tr>
						<?
						//contracts
						$numContract=0;
						$numContract=getDetailedTableInfo3("vl_contracts","contractTerm='4 Years'","count(id)","num");
						$numContract=number_format((float)$numContract);
						?>
                        <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                            <td class="vl_tdnoborder">5</td>
                            <td class="vl_tdnoborder">4 Years</td>
                            <td class="vl_tdnoborder" align="center"><?=$numContract?></td>
                        </tr>
						<?
						//contracts
						$numContract=0;
						$numContract=getDetailedTableInfo3("vl_contracts","contractTerm='Indefinite'","count(id)","num");
						$numContract=number_format((float)$numContract);
						?>
                        <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                            <td class="vl_tdnoborder">6</td>
                            <td class="vl_tdnoborder">Indefinite</td>
                            <td class="vl_tdnoborder" align="center"><?=$numContract?></td>
                        </tr>
 	               </table>
				  </div>
              </td>
            </tr>
            <tr>
	            <td style="padding:20px 0px 0px 0px"><a href="/reports/">Return to Reports</a> :: <a href="/dashboard/">Return to Dashboard</a></td>
            </tr>
			</table>
              </td>
            </tr>
          </table>