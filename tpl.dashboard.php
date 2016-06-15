<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}

if($success) { 
?>
    <div class="vl_success">Data Captured Successfully!</div>
    <div>&nbsp;</div>
<? } ?>

<script Language="JavaScript" Type="text/javascript">
<!--
function showMessage(icon) {
	switch(icon) {
		case "envelopes":
			var message = "Input Data on Inbound Envelopes";
		break;
		case "samples":
			var message = "Load Sample Data into the System";
		break;
		case "verifysamples":
			var message = "Verify Loaded Sample Data";
		break;
		case "worksheets":
			var message = "Print Sample Worksheets";
		break;
		case "results":
			var message = "Dispatch Results";
		break;
		case "reports":
			var message = "View Reports";
		break;
		case "generateforms":
			var message = "Generate Clinical Requisition Forms";
		break;
	}
	document.getElementById('messageID').innerHTML=message;
}
function hideMessage() {
	document.getElementById('messageID').innerHTML="";
}
//-->
</script>

<table width="70%" border="0" cellspacing="0" cellpadding="0" class="vl">
  <tr>
    <td style="border-bottom: 1px dashed #e7e7e7; padding:10px 20px 30px 20px">
     <table width="10%" border="0" cellspacing="0" cellpadding="0">
      <tr>
      	<? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='samples' limit 1","id")) { ?>
        <td width="15%" align="center" onmouseover="showMessage('samples')" onmouseout="hideMessage()"><a href="/samples/" class="vll_grey"><img src="/images/icon.samples.gif" border="0" /></a></td>
        <? } ?>
      	<? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='verifySamples' limit 1","id")) { ?>
        <td width="15%" align="center" onmouseover="showMessage('verifysamples')" onmouseout="hideMessage()"><a href="/verify/" class="vll_grey"><img src="/images/icon.samples.verify.gif" border="0" /></a></td>
        <? } ?>
      	<? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='worksheets' limit 1","id")) { ?>
        <td width="15%" align="center" onmouseover="showMessage('worksheets')" onmouseout="hideMessage()"><a href="/worksheets/" class="vll_grey"><img src="/images/icon.worksheets.gif" border="0" /></a></td>
        <? } ?>
      	<? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='generateForms' limit 1","id")) { ?>
        <td width="15%" align="center" onmouseover="showMessage('generateforms')" onmouseout="hideMessage()"><a href="/generateforms/" class="vll_grey"><img src="/images/icon.form.gif" border="0" /></a></td>
        <? } ?>
      	<? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='results' limit 1","id")) { ?>
        <td width="15%" align="center" onmouseover="showMessage('results')" onmouseout="hideMessage()"><a href="/results/" class="vll_grey"><img src="/images/icon.results.gif" border="0" /></a></td>
        <? } ?>
      	<? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='reports' limit 1","id")) { ?>
        <td width="25%" align="center" onmouseover="showMessage('reports')" onmouseout="hideMessage()" style="padding:0px 10px"><a href="/reports/" class="vll_grey"><img src="/images/icon.reports.gif" border="0" /></a></td>
        <? } ?>
      </tr>
      <tr>
      	<? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='samples' limit 1","id")) { ?>
        <td align="center" class="vll_grey" onmouseover="showMessage('samples')" onmouseout="hideMessage()" style="padding:0px 20px"><a href="/samples/" class="vll_grey">Samples</a></td>
        <? } ?>
      	<? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='verifySamples' limit 1","id")) { ?>
        <td align="center" class="vll_grey" onmouseover="showMessage('verifysamples')" onmouseout="hideMessage()" style="padding:0px 20px"><a href="/verify/" class="vll_grey">Verify&nbsp;Samples</a></td>
        <? } ?>
      	<? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='worksheets' limit 1","id")) { ?>
        <td align="center" class="vll_grey" onmouseover="showMessage('worksheets')" onmouseout="hideMessage()" style="padding:0px 20px"><a href="/worksheets/" class="vll_grey">Worksheets</a></td>
        <? } ?>
      	<? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='generateForms' limit 1","id")) { ?>
        <td align="center" class="vll_grey" onmouseover="showMessage('generateforms')" onmouseout="hideMessage()" style="padding:0px 20px"><a href="/generateforms/" class="vll_grey">Generate&nbsp;Forms</a></td>
        <? } ?>
      	<? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='results' limit 1","id")) { ?>
        <td align="center" class="vll_grey" onmouseover="showMessage('results')" onmouseout="hideMessage()" style="padding:0px 20px"><a href="/results/" class="vll_grey">Results</a></td>
        <? } ?>
      	<? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='reports' limit 1","id")) { ?>
        <td align="center" class="vll_grey" onmouseover="showMessage('reports')" onmouseout="hideMessage()" style="padding:0px 20px"><a href="/reports/" class="vll_grey">Reports</a></td>
        <? } ?>
      </tr>
    </table>
</td>
  </tr>
  <tr>
    <td style="padding:20px 0px"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
      <tr>
        <td width="35%" style="border: 1px solid #cccccc; padding:20px; height: 150px" bgcolor="#f0f0f0" id="messageID">&nbsp;</td>
        <td width="65%" valign="top" style="padding:0px 0px 20px 30px">
        	<div style="border-bottom: 1px solid #cccccc; padding:0px 0px 20px 0px">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
                  <tr>
                    <td width="1%" style="padding:0px 10px 0px 0px"><img src="/images/arrow_right_2.gif" border="0" /></td>
                    <td width="99%"><strong>UPDATES</strong></td>
                  </tr>
                </table>
            </div>
            <div><img src="/images/spacer.gif" width="10" height="10" /></div>
            <div class="vls_grey" style="padding:5px; border: 1px solid #f0d7b4; background-color: #fffee6">Samples in Database (Total): <?=number_format((float)getDetailedTableInfo3("vl_samples","id>0","count(id)","num"))?></div>
            <div><img src="/images/spacer.gif" width="3" height="3" /></div>
            <div class="vls_grey" style="padding:5px; border: 1px solid #f0d7b4; background-color: #fffee6">Pending Samples (All): <?=number_format((float)getDetailedTableInfo3("vl_samples","verified=0","count(id)","num"))?></div>
            <div><img src="/images/spacer.gif" width="3" height="3" /></div>
            <div class="vls_grey" style="padding:5px; border: 1px solid #f0d7b4; background-color: #fffee6">Pending Samples (DBS): <?=number_format((float)getDetailedTableInfo3("vl_samples","verified=0 and sampleTypeID='".getDetailedTableInfo2("vl_appendix_sampletype","appendix='DBS' limit 1","id")."'","count(id)","num"))?></div>
            <div><img src="/images/spacer.gif" width="3" height="3" /></div>
            <div class="vls_grey" style="padding:5px; border: 1px solid #f0d7b4; background-color: #fffee6">Pending Samples (Plasma): <?=number_format((float)getDetailedTableInfo3("vl_samples","verified=0 and sampleTypeID='".getDetailedTableInfo2("vl_appendix_sampletype","appendix='Plasma' limit 1","id")."'","count(id)","num"))?></div>
           <div><img src="/images/spacer.gif" width="3" height="3" /></div>
            <div class="vls_grey" style="padding:5px; border: 1px solid #f0d7b4; background-color: #fffee6">Printed Worksheets: <?=number_format((float)getDetailedTableInfo3("vl_logs_worksheetsamplesprinted,vl_samples_worksheetcredentials","vl_logs_worksheetsamplesprinted.worksheetID=vl_samples_worksheetcredentials.id","count(distinct vl_logs_worksheetsamplesprinted.worksheetID)","num"))?></div>
            <div><img src="/images/spacer.gif" width="3" height="3" /></div>
            <div class="vls_grey" style="padding:5px; border: 1px solid #f0d7b4; background-color: #fffee6">Total Worksheets: <?=number_format((float)getDetailedTableInfo3("vl_samples_worksheetcredentials","id>0","count(id)","num"))?></div>
        </td>
      </tr>
    </table></td>
  </tr>
</table>