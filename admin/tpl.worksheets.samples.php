<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}
?>
  <table width="100%" border="0">
    <tr>
      <td width="65%" valign="top">
      
		<? if($option=="remove") { ?>
            <table width="100%" border="0" class="vl">
                <tr>
                    <td class="vl_success">Samples Removed from Available List!</td>
                </tr>
                <tr>
                    <td><img src="/admin/images/spacer.gif" width="3" height="3" /></td>
                </tr>
            </table>
        <? } ?>
		<?
        switch($option) {
            case remove:
				//delete from vl_samples_verify
				if(count($samplesID)) {
					foreach($samplesID as $sID) {
						logDataRemoval("delete from vl_samples_verify where sampleID='$sID'");
						mysqlquery("delete from vl_samples_verify where sampleID='$sID'");
					}
				}
            break;
            default:
                if($modify) {
                    $task="modify";
                }
            break;
		}
		
		//set task
		if(!$task) {
			$task="add";
		}
?>
		<? if($added) { ?>
            <table width="100%" border="0" class="vl">
                <tr>
                  <td class="vl_success"><?=number_format((float)$added)?> Permission<?=($added>1?"s":"")?> added/altered for <?=getDetailedTableInfo2("vl_users","id='$id'","names")?>!</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
            </table>
		<? } ?>

		<?
		$query=0;
		$query=mysqlquery("select distinct sampleID from vl_samples_verify,vl_samples where vl_samples.id=vl_samples_verify.sampleID and vl_samples_verify.sampleID not in (select distinct sampleID from vl_samples_worksheet) order by vl_samples_verify.created desc");
		$num=0;
		$num=mysqlnumrows($query);
        ?>
        <script Language="JavaScript" Type="text/javascript">
		<!--
        function checkForm(adminsForm) {
			if(confirm('Are you sure?')) {
	            return (true);
			} else {
	            return (false);
			}
        }
        //-->
        </script>
        
        <form name="adminsForm" method="post" action="?act=worksheetSamples&nav=datamanagement" onsubmit="return checkForm(this)">
          <table width="100%" border="0" class="vl">
            <tr>
              <td style="border-bottom:1px solid #cccccc; padding-bottom:10px">Available Sample<?=($num==1?"":"s")?> (<?=number_format((float)$num)?>)</td>
            </tr>
            <tr> 
              <td style="padding:10px 0px 10px 0px">
              
              <div style="height: 300px; border: 1px solid #ccccff; overflow: auto">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
                    <tr>
                      <td width="1%" class="vl_tdsub"><input type="checkbox" name="checkall" onClick="checkUncheckAll(this);"></td>
                      <td width="99%" class="vl_tdsub">Sample Details</td>
                    </tr>
<?
if($num) {
	$count=0;
	while($q=mysqlfetcharray($query)) {
		$count+=1;
		$class=0;
		if($count<$num) {
			$class="vl_tdstandard";
		} else {
			$class="vl_tdnoborder";
		}
		$patientID=0;
		$patientID=getDetailedTableInfo2("vl_samples","id='$q[sampleID]'","patientID");
		$artNumber=0;
		$artNumber=getDetailedTableInfo2("vl_patients","id='$patientID'","artNumber");
		$otherID=0;
		$otherID=getDetailedTableInfo2("vl_patients","id='$patientID'","otherID");
		$sampleID=0;
		$sampleID=getDetailedTableInfo2("vl_samples","id='$q[sampleID]'","vlSampleID");
		$created=0;
		$created=getDetailedTableInfo2("vl_samples_verify","sampleID='$q[sampleID]'","created");
		$createdby=0;
		$createdby=getDetailedTableInfo2("vl_samples_verify","sampleID='$q[sampleID]'","createdby");
?>
        <tr>
          <td class="<?=$class?>"><input name="samplesID[]" type="checkbox" id="samplesID[]" value="<?=$q["sampleID"]?>" /></td>
          <td class="<?=$class?>">
		  	<div><strong><?=($artNumber?"ART Number":"")?><?=($artNumber && $otherID?"/":"")?><?=($otherID?"OtherID":"")?>:</strong> <?=($artNumber?$artNumber:"")?><?=($artNumber && $otherID?"/":"")?><?=($otherID?$otherID:"")?></div>
		  	<div class="vls_grey" style="padding:3px 0px 0px 0px"><strong>SampleID:</strong> <?=$sampleID?></div>
		  	<div class="vls_grey" style="padding:3px 0px 0px 0px"><strong>Approved:</strong> <?=getFormattedDate($created)?> by <?=$createdby?></div>
          </td>
        </tr>
<?
	}
}
?>
                  </table>
                  </div>
                                
              </td>
            </tr>
            <tr> 
              <td style="border-top:1px solid #cccccc; padding-top:10px"> 
              <input type="submit" name="button" id="button" value="   Remove   " /> 
              <? if($task=="modify") { ?>
              <button type="button" id="button" name="button" value="button" onclick="document.location.href='?act=worksheetSamples&nav=datamanagement'">&nbsp;&nbsp;Cancel&nbsp;&nbsp;</button>
              <input name="id" type="hidden" id="id" value="<?=$id?>"> 
              <? } ?>
              <input name="act" type="hidden" id="act" value="worksheetSamples">
              <input name="option" type="hidden" id="option" value="remove">
              </td>
            </tr>
          </table>
        </form>
      
      </td>
      <td width="35%" valign="top" style="padding:3px 0px 0px 12px">
        <table border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #d5d5d5" width="100%">
          <tr>
            <td style="padding:10px"><table width="100%" border="0" class="vl">
              <tr>
                <td><strong>REMOVE WORKSHEET SAMPLES</strong></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td bgcolor="#d5d5d5" style="padding:10px">Check the sample to be removed from the list of available samples for inclusion into a Worksheet</td>
              </tr>
            </table></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>