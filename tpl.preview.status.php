<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";

//get variables
$id=getValidatedVariable("id");
$status=getValidatedVariable("status");

$outcome=0;
$outcome=getDetailedTableInfo2("vl_samples_verify","sampleID='$id' limit 1","outcome");

$outcomeReasonsID=0;
$outcomeReasonsID=getDetailedTableInfo2("vl_samples_verify","sampleID='$id' limit 1","outcomeReasonsID");

$outcomeReasons=0;
$outcomeReasons=getDetailedTableInfo2("vl_appendix_samplerejectionreason","id='$outcomeReasonsID' limit 1","appendix");

$comments=0;
$comments=getDetailedTableInfo2("vl_samples_verify","sampleID='$id' limit 1","comments");

$created=0;
$created=getDetailedTableInfo2("vl_samples_verify","sampleID='$id' limit 1","created");

$createdby=0;
$createdby=getDetailedTableInfo2("vl_samples_verify","sampleID='$id' limit 1","createdby");
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="0" class="vl">
        <tr>
            <td class="tab_active"><?=getDetailedTableInfo2("vl_samples","id='$id'","formNumber")?> (<?=$status?>)</td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td style="border:1px solid #CCCCFF; padding:20px">
	<table width="100%" border="0" class="vl">
    <tr>
        <td>
	        <div style="height: 280px; overflow: auto; padding:3px">
            	<table width="92%" border="0" class="vl">
            <tr>
                <td>
                  <fieldset style="width: 100%">
            <legend><strong>SAMPLE STATUS</strong></legend>
                        <div style="padding:5px 0px 0px 0px">
						<table width="100%" border="0" class="vl">
                            <tr>
                              <td width="30%" style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Received&nbsp;Status</td>
                              <td width="70%" style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=$outcome?></td>
                            </tr>
                        <? if($outcomeReasons) { ?>
                        <tr>
                          <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Reason</td>
                          <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=$outcomeReasons?></td>
                        </tr>
                        <? } ?>
                        <tr>
                          <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Lab&nbsp;Comments</td>
                          <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=$comments?></td>
                        </tr>
                        <tr>
                          <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Changed&nbsp;on</td>
                          <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=getFormattedDate($created)?></td>
                        </tr>
                        <tr>
                          <td style="padding:4px 0px">By</td>
                          <td style="padding:4px 0px"><?=$createdby?></td>
                        </tr>
                      </table>
                        </div>
                  </fieldset>
                </td>
            </tr>
                </table>
            </div>
        </td>
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
