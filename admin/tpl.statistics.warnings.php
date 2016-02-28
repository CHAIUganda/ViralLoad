<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

?>
<table width="100%" border="0">
  <tr>
    <td width="65%" valign="top"><table width="100%" border="0" class="vl">
  <tr> 
    <td><strong>Data submissions even after warnings were displayed</strong></td>
  </tr>
  <tr>
    <td><img src="images/horizontal_400.gif" width="400" height="1" vspace="3"></td>
  </tr>
<?
switch($soption) {
	case submissionsByUser:
	?>
		  <tr>
			<td><strong>Data Submissions for the date: <font color="#FF0000"><?=getFormattedDate($theDate)?></font></strong></td>
		  </tr>
		  <tr>
			<td><table width="100%"  border="1" class="vl">
			  <tr bgcolor="#ECECFF">
				<td width="74%"><strong>Submissions by User</strong> (click to view)</td>
				<td width="26%"><strong>Submissions</strong></td>
			  </tr>
		<?
		$squery=0;
		$squery=mysqlquery("select createdby, count(*) stat from vl_logs_warnings where date(created)='$theDate' group by createdby order by stat desc");
		if(mysqlnumrows($squery)) {
			$sq=array();
			$stotal=0;
			$scount=1;
			while($sq=mysqlfetcharray($squery)) {
				if($scount%2) {
					$scolor="#FFFFFF";
				} else {
					$scolor="#F4F4F4";
				}
		?>
			  <tr bgcolor="<?=$scolor?>">
				<td><a href="#" onclick="iDisplayMessage('tpl.statistics.warnings.details.php?theDate=<?=$theDate?>&createdby=<?=$sq["createdby"]?>')"><? echo $sq["createdby"]; ?></a></td>
				<td><?=number_format((float)$sq["stat"])?></td>
			  </tr>
		<? 
				$stotal+=$sq["stat"];
				$scount++;
			}
		} else { 
		?>
			  <tr>
				<td colspan="2">No statistics available for this query.</td>
			  </tr>
		<? } ?>
			  <tr>
				<td align="right"><strong>Grand total</strong></td>
				<td><?=number_format((float)$stotal)?></td>
			  </tr>
			  <tr>
				<td colspan="2">&nbsp;[ <a href="../admin/?act=statisticswarnings&nav=statistics">return to statistics</a> ]</td>
			  </tr>
			</table></td>
		  </tr>
	<?
	break;
	case recordsInMonth:
	?>
		  <tr>
			<td><strong>Data Submissions within the Month of: <font color="#FF0000"><?=$theMonth?>, <?=$theYear?></font></strong></td>
		  </tr>
		  <tr>
			<td><table width="100%"  border="1" class="vl">
			  <tr bgcolor="#ECECFF">
				<td width="74%"><strong>Submissions by Day</strong> (click to view)</td>
				<td width="26%"><strong>Submissions</strong></td>
			  </tr>
		<?
		$squery=0;
		$squery=mysqlquery("select date(created) datecreated,createdby,count(*) stat from vl_logs_warnings where monthname(created)='$theMonth' and year(created)='$theYear' group by date(created) order by created");
		if(mysqlnumrows($squery)) {
			$sq=array();
			$stotal=0;
			$scount=1;
			while($sq=mysqlfetcharray($squery)) {
				if($scount%2) {
					$scolor="#FFFFFF";
				} else {
					$scolor="#F4F4F4";
				}
		?>
			  <tr bgcolor="<?=$scolor?>">
				<td><a href="../admin/?act=statisticswarnings&nav=statistics&soption=submissionsByUser&theDate=<?=$sq["datecreated"]?>"><?=getFormattedDate($sq["datecreated"])?></a></td>
				<td><?=number_format((float)$sq["stat"])?></td>
			  </tr>
		<? 
				$stotal+=$sq["stat"];
				$scount++;
			}
		} else { 
		?>
			  <tr>
				<td colspan="2">No statistics available for this query.</td>
			  </tr>
		<? } ?>
			  <tr>
				<td align="right"><strong>Grand total</strong></td>
				<td><?=number_format((float)$stotal)?></td>
			  </tr>
			  <tr>
				<td colspan="2">&nbsp;[ <a href="../admin/?act=statisticswarnings&nav=statistics">return to statistics</a> ]</td>
			  </tr>
			</table></td>
		  </tr>
	<?
	break;
	default:
?>
		  <tr>
			<td><strong>Total Data Submissions since November-2015: </strong></td>
		  </tr>
		  <tr>
			<td><strong>Submissions per Month: </strong></td>
		  </tr>
		  <tr>
			<td><table width="100%"  border="1" class="vl">
				<tr bgcolor="#ECECFF">
				  <td width="74%"><strong>Month, Year</strong></td>
				  <td width="26%"><strong>Submissions</strong></td>
				</tr>
				<?
		$squery=0;
		$squery=mysqlquery("select monthname(created) theMonth, year(created) theYear, count(*) stat from vl_logs_warnings group by theMonth, theYear order by created");
		if(mysqlnumrows($squery)) {
			$sq=array();
			$stotal=0;
			$scount=1;
			while($sq=mysqlfetcharray($squery)) {
				if($scount%2) {
					$scolor="#FFFFFF";
				} else {
					$scolor="#F4F4F4";
				}
		?>
				<tr bgcolor="<?=$scolor?>">
				  <td><a href="../admin/?act=statisticswarnings&nav=statistics&soption=recordsInMonth&theMonth=<?=$sq["theMonth"]?>&theYear=<?=$sq["theYear"]?>"><?=$sq["theMonth"]?>, <?=$sq["theYear"]?></a></td>
				  <td><?=number_format((float)$sq["stat"])?></td>
				</tr>
				<? 
				$stotal+=$sq["stat"];
				$scount++;
			}
		} else { 
		?>
				<tr>
				  <td colspan="2">No statistics available for this query.</td>
				</tr>
				<? } ?>
				<tr>
				  <td align="right"><strong>Grand total</strong></td>
				  <td><?=number_format((float)$stotal)?></td>
				</tr>
			</table></td>
		  </tr>
		  <tr>
            <td>&nbsp;</td>
  </tr>
<?
	break;
}
?>
</table>

    </td>
    <td width="35%" valign="top" style="padding:3px 0px 0px 12px"><table border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #d5d5d5" width="100%">
      <tr>
        <td style="padding:10px"><table width="100%" border="0" class="vl">
          <tr>
            <td><strong>EXPORT DATA</strong></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td bgcolor="#d5d5d5" style="padding:10px">Use the options below to export the data into a &quot;.CSV&quot; File</td>
          </tr>
          <tr>
            <td style="padding:5px">
              <form name="export" method="post" action="tpl.statistics.warnings.export.php">
					<table width="100%" border="0" class="vl">
                      <tr>
                        <td>From:</td>
                      </tr>
                      <tr>
                        <td width="80%"><table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                          <tr>
                            <td><select name="fromDay" id="fromDay" class="search">
                              <?
								for($j=1;$j<=31;$j++) {
									echo "<option value=\"".($j<10?"0$j":$j)."\">$j</option>";
								}
								?>
                              </select></td>
                            <td style="padding:0px 0px 0px 5px"><select name="fromMonth" id="fromMonth" class="search">
                              <? echo "<option value=\"".getFormattedDateMonth(getDualInfoWithAlias("last_day(now())","lastmonth"))."\" selected=\"selected\">".getFormattedDateMonthname(getDualInfoWithAlias("last_day(now())","lastmonth"))."</option>"; ?>
                              <option value="01">January</option>
                              <option value="02">February</option>
                              <option value="03">March</option>
                              <option value="04">April</option>
                              <option value="05">May</option>
                              <option value="06">June</option>
                              <option value="07">July</option>
                              <option value="08">August</option>
                              <option value="09">September</option>
                              <option value="10">October</option>
                              <option value="11">November</option>
                              <option value="12">December</option>
                              </select></td>
                            <td style="padding:0px 0px 0px 5px"><select name="fromYear" id="fromYear" class="search">
                              <?
									for($j=getFormattedDateYear(getDualInfoWithAlias("last_day(now())","lastmonth"));$j>=(getCurrentYear()-10);$j--) {
										echo "<option value=\"$j\">$j</option>";
									}
									?>
                              </select></td>
                            </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td style="padding:10px 0px 0px 0px">To:</td>
                      </tr>
                      <tr>
                        <td><table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                          <tr>
                            <td><select name="toDay" id="toDay" class="search">
                              <?
								for($j=1;$j<=31;$j++) {
									echo "<option value=\"".($j<10?"0$j":$j)."\" ".($j==getFormattedDateDay(getDualInfoWithAlias("last_day(now())","lastmonth"))?"selected=\"selected\"":"").">$j</option>";
								}
								?>
                              </select></td>
                            <td style="padding:0px 0px 0px 5px"><select name="toMonth" id="toMonth" class="search">
                              <? echo "<option value=\"".getFormattedDateMonth(getDualInfoWithAlias("last_day(now())","lastmonth"))."\" selected=\"selected\">".getFormattedDateMonthname(getDualInfoWithAlias("last_day(now())","lastmonth"))."</option>"; ?>
                              <option value="01">January</option>
                              <option value="02">February</option>
                              <option value="03">March</option>
                              <option value="04">April</option>
                              <option value="05">May</option>
                              <option value="06">June</option>
                              <option value="07">July</option>
                              <option value="08">August</option>
                              <option value="09">September</option>
                              <option value="10">October</option>
                              <option value="11">November</option>
                              <option value="12">December</option>
                              </select></td>
                            <td style="padding:0px 0px 0px 5px"><select name="toYear" id="toYear" class="search">
                              <?
									for($j=getFormattedDateYear(getDualInfoWithAlias("last_day(now())","lastmonth"));$j>=(getCurrentYear()-10);$j--) {
										echo "<option value=\"$j\">$j</option>";
									}
									?>
                              </select></td>
                            </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td style="padding:10px 0px 0px 0px"><input type="submit" name="export" id="export" value="   Export (Excel)   " class="button" /></td>
                      </tr>
                    </table>
                    </form>
            </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>