<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="right" style="padding:0px 0px 5px 0px"><form name="cd"><input name="disp" type="text" id="txt" size="3" readonly="true" border="0" class="inputCountDown"></form></td>
  </tr>
  <tr>
    <td style="border:1px solid #C4CCCC"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top" width="75%" style="padding:30px"><table width="100%" cellpadding="0" cellspacing="0" border="0" class="vl">
          <tr>
            <td style="padding:5px 0px 10px 0px"><strong>Download Forms (PDF)</strong></td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="1%" valign="top"><img src="/images/icon.pdf.gif" width="65" height="68" alt="PDF" /></td>
                <td width="99%" style="padding:0px 0px 0px 10px">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table></td>
        <td width="25%" height="350" bgcolor="#dfe3f6" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0" class="vl">
          <tr>
            <td align="center" style="padding:15px 0px 0px 0px"><strong>Help</strong></td>
          </tr>
          <tr>
            <td align="center" style="padding:30px"><img src="/images/help.gif" width="130" height="104" alt="help" /></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
