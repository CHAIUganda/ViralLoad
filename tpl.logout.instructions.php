<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="0" class="vl">
        <tr>
            <td class="tab_active">Session&nbsp;Timeout</td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td style="border:1px solid #CCCCFF; padding:20px">
	<table width="100%" border="0" class="vl">
        <tr>
            <td class="vls_grey" style="padding:15px 20px 15px 20px; background-color:#ebebeb; border: 1px solid #b6b6b6"><strong>Your Session has timed out due to Inactivity on your Browser!</strong><br />              
              <a href="/dashboard/">Login to access Viral Load</a></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
                <tr>
                  <td>
                  <div style="height: 190px; overflow: auto; padding:3px"><img src="/images/session.timeout.gif" alt="session timeout" /></div>
              </td>
            </tr>
    <tr>
    	<td><img src="/images/spacer.gif" width="10" height="10" /></td>
    </tr>
    <tr>
    	<td style="padding: 10px 0px 0px 0px; border-top: 1px solid #999999;"><a href="/dashboard/" class="vls">Login</a></td>
    </tr>
	</table>
    </td>
  </tr>
</table>
