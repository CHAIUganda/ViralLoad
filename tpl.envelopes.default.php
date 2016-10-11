<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}
?>
<table width="100%" border="0" class="vl">
			<? 
			if($success) { 
				if($addedEnvelopes || $modifiedEnvelopes) {
					?>
					<tr>
						<td colspan="2" class="vl_success"><? echo "<strong>".number_format((float)$addedEnvelopes)."</strong> Envelope".($addedEnvelopes==1?"":"s")." Added, <strong>".number_format((float)$modifiedEnvelopes)."</strong> Envelope".($modifiedEnvelopes==1?"":"s")." Modified"; ?></td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<? 
				} else {
					?>
					<tr>
						<td colspan="2" class="vl_success">Envelope Captured Successfully!</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<? 
				}
			} elseif($modified) { 
			?>
            <tr>
                <td colspan="2" class="vl_success">Envelope Changes Captured Successfully!</td>
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
                <td width="5%" align="center"><a href="/envelopes/" class="vll_grey"><img src="/images/icon.envelopes.gif" border="0" /></a></td>
                <td width="95%" style="padding:0px 0px 0px 30px"><table width="100%" cellpadding="0" cellspacing="0" border="0" class="vl">
                  <tr>
                    <td><strong>Envelopes</strong></td>
                  </tr>
                  <tr>
                    <td class="vls_grey">Envelopes Management</td>
                  </tr>
                  <tr>
                    <td style="padding:10px 0px 0px 0px"><a href="/envelopes/capture/">Input a New Envelope</a></td>
                  </tr>
                  <tr>
                    <td style="padding:5px 0px 0px 0px"><a href="/envelopes/manage/">Manage Envelopes</a></td>
                  </tr>
                </table></td>
            </tr>
          </table>