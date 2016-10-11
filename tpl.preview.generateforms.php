<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";

//get variables
$refNumber=getValidatedVariable("refNumber");

$query=0;
$query=mysqlquery("select * from vl_forms_clinicalrequest where refNumber='$refNumber'");
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="0" class="vl">
        <tr>
            <td class="tab_active">Clinical&nbsp;Request&nbsp;Form&nbsp;#:&nbsp;<?=$refNumber?></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td style="border:1px solid #CCCCFF; padding:20px">
	<table width="100%" border="0" class="vl">
    <tr>
    <? if(mysqlnumrows($query)) { ?>
        <td>
	        <div style="height: 280px; overflow: auto; padding:3px">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td class="vl_tdsub" width="1%"><strong>#</strong></td>
                          <td class="vl_tdsub" width="89%"><strong>Form&nbsp;#</strong></td>
                          <td class="vl_tdsub" width="10%"><strong>Options</strong></td>
                        </tr>
                    	<?
                        $count=0;
                        $count=$offset;
                        $q=array();
                        while($q=mysqlfetcharray($query)) {
                            $count+=1;
                        ?>
                            <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                                <td class="<?=($count<mysqlnumrows($query)?"vl_tdstandard":"vl_tdnoborder")?>"><?=$count?></td>
                                <td class="<?=($count<mysqlnumrows($query)?"vl_tdstandard":"vl_tdnoborder")?>"><?=$q["formNumber"]?></td>
                                <td class="<?=($count<mysqlnumrows($query)?"vl_tdstandard":"vl_tdnoborder")?>"><div class="vls_grey" style="padding:3px 0px 0px 0px"><a href="/generateforms/executesingular/<?=$q["formNumber"]?>/" target="_blank">Download&nbsp;Clinical&nbsp;Request&nbsp;Form</a></div></td>
                            </tr>
                        <? } ?>
 	               </table>
            </div>
        </td>
	<? } else { ?>
    	<td class="vl_error">Incorrect Reference Number <strong><?=$refNumber?></strong>!</td>
	<? } ?>
    </tr>
    <tr>
    	<td><img src="/images/spacer.gif" width="10" height="10" /></td>
    </tr>
    <tr>
    	<td style="padding: 10px 0px 0px 0px; border-top: 1px solid #999999;"><a href="#rnd" onclick="closeMessage()" class="trailanalyticss">Close!</a></td>
    </tr>
	</table>
    </td>
  </tr>
</table>
