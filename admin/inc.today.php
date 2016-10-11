<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

?>	
<table width="80%" border="0" class="vl">
  <tr> 
    <td>
<?
		//read the required details
		echo "<b>Welcome $_SESSION[VLADMIN]</b><br>";
		echo "Last login: ".getFormattedDate(getDetailedTableInfo2("vl_admins","email='$_SESSION[VLADMIN]'","login"))." at ".getFormattedTime(getDetailedTableInfo2("vl_admins","email='$_SESSION[VLADMIN]'","login"))."<br>";
		echo "Current Date, Time: ".getFormattedDate($datetime).", ".getFormattedTime($datetime)."<br>";
?>
    </td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
    <tr>
        <td class="vl_tdsub" style="padding-left:14px"><strong>Appendices</strong></td>
    </tr>
  <tr> 
    <td>
    <div style="height: 300px; border: 1px solid #ccccff; overflow: auto; padding:0px 0px 0px 5px">
		<table width="95%" border="0" cellspacing="0" cellpadding="0" class="trailanalytics">
            <tr>
              <td class="vl_tdstandard"><a href="?act=aarvadherence">ARV Adherence</a></td>
            </tr>
            <tr>
              <td class="vl_tdstandard"><a href="?act=aregimen">Current Regimen</a></td>
            </tr>
            <tr>
              <td class="vl_tdstandard"><a href="?act=afailurereason">Failure Reason</a></td>
            </tr>
            <tr>
              <td class="vl_tdstandard"><a href="?act=asampletype">Sample Type</a></td>
            </tr>
            <tr>
              <td class="vl_tdstandard"><a href="?act=atbtreatmentphase">TB Treatment Phase</a></td>
            </tr>
            <tr>
              <td class="vl_tdstandard"><a href="?act=atreatmentinitiation">Treatment Initiation (Indication)</a></td>
            </tr>
            <tr>
              <td class="vl_tdstandard"><a href="?act=atreatmentstatus">Treatment Line</a></td>
            </tr>
            <tr>
              <td class="vl_tdstandard"><a href="?act=aviralloadtesting">Viral Load Testing (Indication)</a></td>
            </tr>
            <tr>
              <td class="vl_tdnoborder"><a href="?act=asamplerejectionreasons">Sample Rejection Reasons</a></td>
            </tr>
        </table>
       </div>
     </td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
</table>
