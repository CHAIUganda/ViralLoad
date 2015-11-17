<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}

//validation
$id=validate($id);
$pg=validate($pg);
$outcome=validate($outcome);
$outcomeReasonsID=validate($outcomeReasonsID);
$comments=validate($comments);

if($saveChanges) {
	//validate data
	$error=0;
	$error=checkFormFields("Received_Status::$outcome");

	//is gender male and pregnancy set to yes?
	if($outcome=="Rejected" && !$outcomeReasonsID) {
		$error.="<br /><strong>Received Status is Rejected but no Rejection Reason Provided.<br />";
	}

	//input data
	if(!$error) {
		//log status
		mysqlquery("insert into vl_samples_verify 
						(sampleID,outcome,outcomeReasonsID,comments,created,createdby) 
						values 
						('$id','$outcome','$outcomeReasonsID','$comments','$datetime','$trailSessionUser')");
		//redirect to home with updates on the tracking number
		if($encryptedSample) {
			go("/verify/search/$encryptedSample/pg/$pg/modified/");
		} else {
			go("/verify/$pg/modified/");
		}
	}
}
?>
<script Language="JavaScript" Type="text/javascript">
<!--
function validate(samples) {
	//check for missing information
	if(!document.samples.outcome.value) {
		alert('Missing Mandatory Field: Received Status');
		document.samples.outcome.focus();
		return (false);
	}
	if(document.samples.outcome.value=='Rejected' && !document.samples.outcomeReasonsID.value) {
		alert('Received Status is Rejected but Reason has not been specified');
		document.samples.outcomeReasonsID.focus();
		return (false);
	}
	return (true);
}

function checkOutcome() {
	if(document.samples.outcome.value=='Rejected') {
		var outcome='';
		outcome='<select name="outcomeReasonsID" id="outcomeReasonsID" class="search">';
		outcome+='<option value="">Select Rejection Reason</option>';
		<?
		$query=0;
		$query=mysqlquery("select * from vl_appendix_samplerejectionreason where sampleTypeID='".getDetailedTableInfo2("vl_samples","id='$id'","sampleTypeID")."' order by position");
		if(mysqlnumrows($query)) {
			while($q=mysqlfetcharray($query)) {
				echo "outcome+='<option value=\"$q[id]\">".preg_replace("/'/s","\'",$q["appendix"])."</option>';";
			}
		}
		?>
		outcome+='</select>';
		document.getElementById("outcomeID").innerHTML=outcome;
	} else {
		document.getElementById("outcomeID").innerHTML="";
	}
}
//-->
</script>
<!--<form name="samples" method="post" action="/verify/approve.reject/<?=$id?>/<?=$pg?>/" onsubmit="return validate(this)">-->
<form name="samples" method="post" action="/verify/approve.reject/<?=$id?>/<?=$pg?>/">
<table width="100%" border="0" class="vl">
          <? if($success) { ?>
            <tr>
                <td class="vl_success">Data Captured Successfully!</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
			<? } elseif($error) { ?>
            <tr>
                <td class="vl_error"><?=$error?></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <? } ?>
            <tr>
              <td class="toplinks" style="padding:0px 0px 10px 0px"><a class="toplinks" href="/dashboard/">HOME</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="toplinks" href="/verify/">VERIFY SAMPLES</a></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                  <fieldset style="width: 100%">
            <legend><strong>APPROVE/REJECT SAMPLE</strong></legend>
                        <div style="padding:5px 0px 0px 0px">
						<table width="100%" border="0" class="vl">
                        <tr>
                          <td>Sample&nbsp;Reference&nbsp;#</td>
                          <td><?=getDetailedTableInfo2("vl_samples","id='$id'","vlSampleID")?></td>
                        </tr>
                        <? if(getDetailedTableInfo2("vl_samples","id='$id'","lrNumericID")) { ?>
                        <tr>
                          <td><?=($lrCategory=="V"?"Location":"Rejection")?>&nbsp;ID</td>
                          <td><?=getDetailedTableInfo2("vl_samples","id='$id'","lrCategory").getDetailedTableInfo2("vl_samples","id='$id'","lrEnvelopeNumber")."/".getDetailedTableInfo2("vl_samples","id='$id'","lrNumericID")?></td>
                        </tr>
                        <? } ?>
                        <tr>
                          <td>Form&nbsp;#</td>
                          <td><?=getDetailedTableInfo2("vl_samples","id='$id'","formNumber")?></td>
                        </tr>
                        <tr>
                          <td>Patient's&nbsp;ART&nbsp;#</td>
                          <td><?=getDetailedTableInfo2("vl_patients","id='".getDetailedTableInfo2("vl_samples","id='$id'","patientID")."'","artNumber")?></td>
                        </tr>
                        <tr>
                          <td>Patient's&nbsp;Other&nbsp;ID</td>
                          <td><?=getDetailedTableInfo2("vl_patients","id='".getDetailedTableInfo2("vl_samples","id='$id'","patientID")."'","otherID")?></td>
                        </tr>
                        <tr>
                          <td>Facility</td>
                          <td><?=getDetailedTableInfo2("vl_facilities","id='".getDetailedTableInfo2("vl_samples","id='$id'","facilityID")."'","facility")?></td>
                        </tr>
                        <tr>
                          <td>Sample&nbsp;Type</td>
                          <td><?=getDetailedTableInfo2("vl_appendix_sampletype","id='".getDetailedTableInfo2("vl_samples","id='$id'","sampleTypeID")."' limit 1","appendix")?></td>
                        </tr>
                        <tr>
                          <td width="20%">Received&nbsp;Status</td>
                          <td width="80%">
                          <select name="outcome" id="outcome" class="search" onchange="checkOutcome()">
							<option value="">Select Outcome</option>
							<option value="Accepted">Accepted</option>
							<option value="Rejected">Rejected</option>
							<!--<option value="Repeat">Repeat</option>-->
                          </select>
                          </td>
                        </tr>
                        <tr>
                          <td></td>
                          <td id="outcomeID"></td>
                        </tr>
                        <tr>
                          <td>Lab&nbsp;Comments</td>
                          <td><textarea name="comments" id="comments" cols="40" rows="3" class="searchLarge"></textarea></td>
                        </tr>
                      </table>
                        </div>
                  </fieldset>
                </td>
            </tr>
            <tr>
              <td style="padding:10px 0px 0px 0px">
              	<input type="submit" name="saveChanges" id="saveChanges" class="button" value="  Save Changes  " />
                <input type="hidden" name="encryptedSample" id="encryptedSample" value="<?=$encryptedSample?>" />
              </td>
            </tr>
            <tr>
	            <td style="padding:20px 0px 0px 0px"><a href="/verify/">Return to Samples</a> :: <a href="/dashboard/">Return to Dashboard</a></td>
            </tr>
          </table>
</form>