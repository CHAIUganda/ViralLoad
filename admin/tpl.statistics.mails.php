<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}
?>
<table width="100%" border="0" class="vl">
  <tr> 
    <td><span style='font-weight: bold'>Emails sent by the site</span></td>
  </tr>
  <tr>
    <td><img src="images/horizontal_400.gif" width="400" height="1" vspace="3"></td>
  </tr>
<?
switch($soption) {
	case details:
	?>
		  <tr>
			<td><span style='font-weight: bold'>Emails for the date: <font color="#FF0000"><?=getFormattedDate($xdetails)?></font></span></td>
		  </tr>
		  <tr>
			<td><table width="95%" cellpadding="0" cellspacing="0" class="vl">
		<?
		$squery=0;
		$squery=mysqlquery("select * from vl_daemon_email where date(created)='$xdetails' order by created desc");
		if(mysqlnumrows($squery)) {
			$sq=array();
			while($sq=mysqlfetcharray($squery)) {
		?>
			  <tr>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
			    <td><img src="images/spacer.gif" width="8" height="8" /></td>
		      </tr>
			  <tr>
				<td style="border: 1px solid #CCE3FE; padding:10px"><?
				echo "<span style='font-weight: bold'>Recepient:</span> $sq[toAddress]<br>
						<span style='font-weight: bold'>Subject:</span> $sq[subject]<br>
						<span style='font-weight: bold'>Status:</span> ".($sq["sent"]?"Sent":"<font class=\"vl_red\">Pending</font>");
				?>
                <br /><img src="images/horizontal_400.gif" width="400" height="1" vspace="10" /><br /><?=preg_replace("/\n/s","<br />",$sq["message"])?></td>
			  </tr>
		<? 
			}
		} else { 
		?>
			  <tr>
				<td colspan="2">No emails sent for the month of <span style='font-weight: bold'><?=$xmonth?>, <?=$xyear?></span>.</td>
			  </tr>
		<? } ?>
			  <tr>
			    <td colspan="2">&nbsp;</td>
		      </tr>
			  <tr>
				<td colspan="2">&nbsp;<a href="?act=mails&nav=statistics&soption=monthdays&xmonth=<?=$xmonth?>&xyear=<?=$xyear?>">return to month selection</a> :: <a href="?act=mails&nav=statistics">return to emails</a></td>
			  </tr>
			</table></td>
		  </tr>
	<?
	break;
	case monthdays:
	?>
		  <tr>
			<td><table width="70%"  border="1" class="vl">
				<tr bgcolor="#ECECFF">
				  <td width="100%"><span style='font-weight: bold'>Date</span></td>
				</tr>
		<?
		$squery=0;
		$squery=mysqlquery("select date(created) dcreated, created, count(*) num from vl_daemon_email where monthname(created)='$xmonth' and year(created)='$xyear' group by date(created) order by created");
		if(mysqlnumrows($squery)) {
			$sq=array();
			$stotal=0;
			$scount=1;
			while($sq=mysqlfetcharray($squery)) {
				if($scount%2) {
					$scolor="#FFFFFF";
				} else {
					$scolor="beige";
				}
		?>
				<tr bgcolor="<?=$scolor?>">
				  <td><a href="?act=mails&nav=statistics&soption=details&xdetails=<?=$sq["dcreated"]?>&xmonth=<?=$xmonth?>&xyear=<?=$xyear?>"><?=$sq["created"]." (".number_format((float)$sq["num"]).")"?></a></td>
				</tr>
				<? 
				$stotal+=$sq["stat"];
				$scount++;
			}
		} else { 
		?>
				<tr>
				  <td>No emails have yet been sent from the site.</td>
				</tr>
				<? } ?>
			</table></td>
		  </tr>
<?
	break;
	default:
	?>
		  <tr>
			<td><table width="70%"  border="1" class="vl">
				<tr bgcolor="#ECECFF">
				  <td width="100%"><span style='font-weight: bold'>Month, Year</span></td>
				</tr>
				<?
		$squery=0;
		$squery=mysqlquery("select distinct monthname(created) xmonthname,year(created) xyear from vl_daemon_email order by created");
		if(mysqlnumrows($squery)) {
			$sq=array();
			$stotal=0;
			$scount=1;
			while($sq=mysqlfetcharray($squery)) {
				if($scount%2) {
					$scolor="#FFFFFF";
				} else {
					$scolor="beige";
				}
		?>
				<tr bgcolor="<?=$scolor?>">
				  <td><a href="?act=mails&nav=statistics&soption=monthdays&xmonth=<?=$sq["xmonthname"]?>&xyear=<?=$sq["xyear"]?>"><?=$sq["xmonthname"]?>, <?=$sq["xyear"]?></a></td>
				</tr>
				<? 
				$stotal+=$sq["stat"];
				$scount++;
			}
		} else { 
		?>
				<tr>
				  <td>No emails have yet been sent from the site.</td>
				</tr>
				<? } ?>
			</table></td>
		  </tr>
<?
	break;
}
?>
</table>