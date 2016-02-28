<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
	  <td><? include_once("inc.tabs.php"); ?></td>
  </tr>
  <tr>
    <td style="border-right:1px solid #99cc99; border-bottom:1px solid #99cc99; border-left:1px solid #99cc99; padding:20px 30px 30px 30px">
          <table width="100%" border="0" class="vl">
            <tr>
                <td width="5%" align="center" style="padding: 0px 0px 30px 5px"><img src="/images/icon.support.gif" width="55" height="55" alt="support" border="0" /></td>
                <td width="95%" style="padding:0px 0px 30px 20px"><table width="100%" cellpadding="0" cellspacing="0" border="0" class="vl">
                  <tr>
                      <td><strong>Support</strong></td>
                  </tr>
                    <tr>
                      <td class="vls_grey">For Support, Contact ...</td>
                    </tr>
                    <tr>
                      <td style="padding:20px 0px 0px 0px" class="vls_grey">Email Information and Support</td>
                    </tr>
                    <tr>
                      <td style="padding:10px 0px 0px 0px"><strong>Technical:</strong> <a href="mailto: samuel@vl.com">info@vl.go.ug</a></td>
                    </tr>
                </table></td>
            </tr>
          </table>
    </td>
  </tr>
</table>
