<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}
?>
<table width="100%" border="0" class="vl">
            <tr>
              <td>
	<table width="100%" border="0" class="vl" cellspacing="0" cellpadding="0">
		<?
		//how many pages are there?
		if($numberPages>1) {
			echo "<tr><td style=\"padding:0px 0px 10px 0px\" class=\"vls_grey\"><strong>Pages:</strong> ".displayPagesLinks("/reports/$statisticsType/pg/",1,$numberPages,($pg?$pg:1),$default_radius)."</td></tr>";
		}
        ?>
                <tr>
                  <td><table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <? if(!$statisticsType || $statisticsType=="national") { ?>
                          <td class="bluetab_active">National Statistics</td>
                          <? } else { ?>
                          <td class="bluetab_inactive"><a href="/reports/national/">National Statistics</a></td>
                          <? } ?>
                          <td bgcolor="#C4CCCC"><img src="/images/spacer.gif" width="1" height="1" /></td>
                          <? if($statisticsType=="district") { ?>
                          <td class="bluetab_active">District Statistics</td>
                          <? } else { ?>
                          <td class="bluetab_inactive"><a href="/reports/district/">District Statistics</a></td>
                          <? } ?>
                          <td bgcolor="#C4CCCC"><img src="/images/spacer.gif" width="1" height="1" /></td>
                          <? if($statisticsType=="downloads") { ?>
                          <td class="bluetab_active">Downloads</td>
                          <? } else { ?>
                          <td class="bluetab_inactive"><a href="/reports/downloads/">Downloads</a></td>
                          <? } ?>
                          <td bgcolor="#C4CCCC"><img src="/images/spacer.gif" width="1" height="1" /></td>
                        </tr>
                      </table></td>
                </tr>
                <tr>
                  <td style="border: 1px solid #d5e6cf; padding:20px">
<?
					switch($statisticsType) {
						case "downloads":
							include "tpl.reports.default.downloads.php";
						break;
						case "national":
						default:
							include "tpl.reports.default.national.php";
						break;
						
					}
?>
                  </td>
            </tr>
            <tr>
	            <td style="padding:10px 0px 0px 0px"><a href="/dashboard/">Return to Dashboard</a></td>
            </tr>
		</table>
              </td>
            </tr>
          </table>
