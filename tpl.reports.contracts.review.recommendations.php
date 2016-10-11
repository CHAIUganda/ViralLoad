<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}
?>
<table width="100%" border="0" class="vl">
            <tr>
              <td class="toplinks" style="padding:0px 0px 10px 0px"><a class="toplinks" href="/dashboard/">HOME</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="toplinks" href="/reports/">REPORTS</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="toplinks" href="/reports/contracts.review.recommendations/">CONTRACTS BY REVIEW RECOMMENDATIONS</a></td>
            </tr>
            <tr>
                <td style="padding: 5px 0px 15px 0px; border-bottom: 1px dashed #dfe6e6"><strong>Contracts by Review Recommendations</strong></td>
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
						$numContract=getDetailedTableInfo3("vl_contracts","id!=''","count(id)","num");
						$numContract=number_format((float)$numContract);
						?>
                        <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                            <td class="vl_tdstandard">1</td>
                            <td class="vl_tdstandard">All Contracts</td>
                            <td class="vl_tdstandard" align="center"><?=$numContract?></td>
                        </tr>
						<?
						//contracts
						$numContract=0;
						$numContract=getDetailedTableInfo3("vl_contracts,vl_contracts_reviews","vl_contracts.id=vl_contracts_reviews.contractID and 
																								vl_contracts_reviews.reviewRecommendation='Proceed with Contract'","count(distinct vl_contracts.id)","num");
						$numContract=number_format((float)$numContract);
						?>
                        <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                            <td class="vl_tdstandard">2</td>
                            <td class="vl_tdstandard">Proceed with Contract</td>
                            <td class="vl_tdstandard" align="center"><?=$numContract?></td>
                        </tr>
						<?
						//contracts
						$numContract=0;
						$numContract=getDetailedTableInfo3("vl_contracts,vl_contracts_reviews","vl_contracts.id=vl_contracts_reviews.contractID and 
																								vl_contracts_reviews.reviewRecommendation='Renew'","count(distinct vl_contracts.id)","num");
						$numContract=number_format((float)$numContract);
						?>
                        <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                            <td class="vl_tdstandard">3</td>
                            <td class="vl_tdstandard">Renew</td>
                            <td class="vl_tdstandard" align="center"><?=$numContract?></td>
                        </tr>
						<?
						//contracts
						$numContract=0;
						$numContract=getDetailedTableInfo3("vl_contracts,vl_contracts_reviews","vl_contracts.id=vl_contracts_reviews.contractID and 
																								vl_contracts_reviews.reviewRecommendation='Terminate Immediately'","count(distinct vl_contracts.id)","num");
						$numContract=number_format((float)$numContract);
						?>
                        <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                            <td class="vl_tdnoborder">4</td>
                            <td class="vl_tdnoborder">Terminate Immediately</td>
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