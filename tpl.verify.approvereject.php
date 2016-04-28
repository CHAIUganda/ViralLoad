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

//encrypted samples
$searchQuery=0;
$searchQueryCurrentPosition=0;
$searchQueryNextPosition=0;
if($encryptedSample) {
	$searchQuery=validate(vlDecrypt($encryptedSample));
	$searchQueryCurrentPosition=getDetailedTableInfo3("vl_samples y","y.vlSampleID='".getDetailedTableInfo2("vl_samples","id='$id'","vlSampleID")."'","(select count(x.id) from vl_samples x where (x.formNumber='$searchQuery' or x.vlSampleID='$searchQuery' or concat(x.lrCategory,x.lrEnvelopeNumber,'/',x.lrNumericID) like '$searchQuery%') and x.vlSampleID<=y.vlSampleID order by if(x.lrCategory='',1,0),x.lrCategory, if(x.lrEnvelopeNumber='',1,0),x.lrEnvelopeNumber, if(x.lrNumericID='',1,0),x.lrNumericID,x.created desc)","position");
	if($searchQueryCurrentPosition) {
		//$searchQueryNextPosition=getDetailedTableInfo2("vl_samples","id not in (select sampleID from vl_samples_verify) and (formNumber='$searchQuery' or vlSampleID='$searchQuery' or concat(lrCategory,lrEnvelopeNumber,'/',lrNumericID) like '$searchQuery%') order by if(lrCategory='',1,0),lrCategory, if(lrEnvelopeNumber='',1,0),lrEnvelopeNumber, if(lrNumericID='',1,0),lrNumericID,created desc limit $searchQueryCurrentPosition,1","id");
		//if(!$searchQueryNextPosition) {
			$searchQueryNextPosition=getDetailedTableInfo2("vl_samples","id not in (select sampleID from vl_samples_verify) and id!='$id' and (formNumber='$searchQuery' or vlSampleID='$searchQuery' or concat(lrCategory,lrEnvelopeNumber,'/',lrNumericID) like '$searchQuery%') order by lrNumericID asc limit 1","id");
		//}
	}
}

//envelope Number From
$searchQueryFrom=0;
$searchQueryTo=0;
//$searchQueryCurrentPosition=0;
//$searchQueryNextPosition=0;
if($envelopeNumberFrom && $envelopeNumberTo) {
	$searchQueryFrom=validate(vlDecrypt($envelopeNumberFrom));
	$searchQueryTo=validate(vlDecrypt($envelopeNumberTo));
	$searchQueryCurrentPosition=getDetailedTableInfo3("vl_samples y","y.vlSampleID='".getDetailedTableInfo2("vl_samples","id='$id'","vlSampleID")."'","(select count(x.id) from vl_samples x where concat(x.lrCategory,x.lrEnvelopeNumber)>='$searchQueryFrom' and concat(x.lrCategory,x.lrEnvelopeNumber)<='$searchQueryTo' and x.vlSampleID<=y.vlSampleID order by if(x.lrCategory='',1,0),x.lrCategory, if(x.lrEnvelopeNumber='',1,0),x.lrEnvelopeNumber, if(x.lrNumericID='',1,0),x.lrNumericID,x.created desc)","position");
	if($searchQueryCurrentPosition) {
		$searchQueryNextPosition=getDetailedTableInfo2("vl_samples","id not in (select sampleID from vl_samples_verify) and concat(lrCategory,lrEnvelopeNumber)>='$searchQueryFrom' and concat(lrCategory,lrEnvelopeNumber)<='$searchQueryTo' order by if(lrCategory='',1,0),lrCategory, if(lrEnvelopeNumber='',1,0),lrEnvelopeNumber, if(lrNumericID='',1,0),lrNumericID,created desc limit $searchQueryCurrentPosition,1","id");
		if(!$searchQueryNextPosition) {
			$searchQueryNextPosition=getDetailedTableInfo2("vl_samples","id not in (select sampleID from vl_samples_verify) and id!='$id' and concat(lrCategory,lrEnvelopeNumber)>='$searchQueryFrom' and concat(lrCategory,lrEnvelopeNumber)<='$searchQueryTo' order by if(lrCategory='',1,0),lrCategory, if(lrEnvelopeNumber='',1,0),lrEnvelopeNumber, if(lrNumericID='',1,0),lrNumericID,created desc limit 1","id");
		}
	}
}

if($saveChangesReturn || $saveChangesProceed) {
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

    //MODEL:: WHERE THE DATABASE CAPTURES THE APPROVAL DETAILS .....STARTS HERE
		mysqlquery("insert into vl_samples_verify 
						(sampleID,outcome,outcomeReasonsID,comments,created,createdby) 
						values 
						('$id','$outcome','$outcomeReasonsID','$comments','$datetime','$trailSessionUser')");

    if(isset($other_reasons)){
      foreach ($other_reasons as $other_reason) {
        if(!empty($other_reason)){
          $data=array("sampleID"=>$id,
                      "reasonID"=>$other_reason,
                      "created"=>$datetime,
                      "createdby"=>$trailSessionUser);
          insertData($data,'vl_samples_verify_reasons'); 
        }      
      }
    }

    $dateOfBirth=getFormattedDateCRB($dateOfBirth);
    $collectionDate=getFormattedDateCRB($collectionDate);
    $treatmentInitiationDate=getFormattedDateCRB($treatmentInitiationDate);
    $repeatVLTestLastVLDate=getFormattedDateCRB($repeatVLTestLastVLDate);

    $patient_data=compact('artNumber','otherID','gender','dateOfBirth');
    $sample_data=compact('sampleTypeID','collectionDate','treatmentInitiationDate','repeatVLTestLastVLDate','repeatVLTestValue');
    //$patient_data=compareUpdateInfo(json_decode($prev_smpl_data),$patient_data);
    //$sample_data=compareUpdateInfo(json_decode($prev_smpl_data),$sample_data);

    updateData($patient_data,"vl_patients","id=$pat_id");
    updateData($sample_data,"vl_samples","id=$smpl_id"); 
    //MODEL:: WHERE THE DATABASE CAPTURES THE APPROVAL DETAILS ..... ENDS HERE
		//redirect to home with updates on the tracking number
		if($saveChangesProceed) {
			//proceed to next sample within the search results
			if($encryptedSample && $searchQueryCurrentPosition && $searchQueryNextPosition) {
				go("/verify/approve.reject/$searchQueryNextPosition/$pg/search/$encryptedSample/1/");
			} elseif($envelopeNumberFrom && $envelopeNumberTo && $searchQueryCurrentPosition && $searchQueryNextPosition) {
				go("/verify/approve.reject/$searchQueryNextPosition/$pg/search/$envelopeNumberFrom/$envelopeNumberTo/1/");
			}
		} elseif(!$searchQueryNextPosition || $saveChangesReturn) {
			if($encryptedSample) {
				go("/verify/search/$encryptedSample/pg/$pg/modified/");
			} elseif($envelopeNumberFrom && $envelopeNumberTo) {
				go("/verify/search/$envelopeNumberFrom/$envelopeNumberTo/pg/$pg/modified/");
			} else {
				go("/verify/$pg/modified/");
			}
		} else {
			go("/verify/$pg/modified/");
		}
	}
}

$smpl_res=mysqlquery("SELECT s.*,f.facility,d.district,h.hub,s_type.appendix AS sample_type,p.artNumber,p.otherID,p.gender,p.dateOfBirth,p.id AS pat_id
                      FROM vl_samples AS s
                      LEFT JOIN vl_facilities AS f ON s.facilityID=f.id
                      LEFT JOIN vl_districts AS d ON f.districtID=d.id
                      LEFT JOIN vl_hubs AS h ON f.hubID=h.id 
                      LEFT JOIN vl_appendix_sampletype AS s_type ON s.sampleTypeID=s_type.id
                      LEFT JOIN vl_patients AS p ON s.patientID=p.id
                      WHERE s.id=$id LIMIT 1");

$smpl_arr=mysqlfetchassoc($smpl_res);

$prev_smpl_data=json_encode($smpl_arr);

$gender_arr=['Female'=>'Female','Male'=>'Male','Left Blank'=>'Left Blank','Missing Gender'=>'Missing Gender'];

$smpl_typ_res=mysqlquery("SELECT * FROM vl_appendix_sampletype");
$sample_type_arr=array();
while($st_row=mysqlfetcharray($smpl_typ_res)){
  $sample_type_arr[$st_row['id']]=$st_row['appendix'];
}

?>


<script Language="JavaScript" Type="text/javascript">
var rejection_reasons={};
$(function(){
  <?
    $rjctn_rsns=array();
    $query=0;
    $query=mysqlquery("select * from vl_appendix_samplerejectionreason where sampleTypeID='".getDetailedTableInfo2("vl_samples","id='$id'","sampleTypeID")."' order by position");
    if(mysqlnumrows($query)) {
      while($q=mysqlfetcharray($query)) {
        $rjctn_rsns[$q['id']]=preg_replace("/'/s","\'",$q["appendix"]);
      }
    }
  ?>
  rejection_reasons=<?php echo json_encode($rjctn_rsns) ?>
});



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

function remRsn(that_val){
  delete rejection_reasons[that_val];
}

function reasonsDropDown(nme,id){
  var other_options="id='"+id+"' class='search' onchange='remRsn(this.value)'";
  return generalSelect(nme,rejection_reasons,"Select Rejection Reason","",other_options);
}

function checkOutcome() {
	if(document.samples.outcome.value=='Rejected') {
    var primary_reason="<span class='sm_txt'>primary reason:</span><br>"+reasonsDropDown("outcomeReasonsID","outcomeReasonsID");
		var other_reason="<br><br><span class='sm_txt'>more reasons:</span><div id='other_reasons'></div><span class='add_item' onclick='addOtherReasons()'>add<span>";
		document.getElementById("outcomeID").innerHTML=primary_reason+other_reason;

    //document.getElementById("outcomeID").innerHTML="<span class='sm_txt'>primary reason:<span><br>"+reasonsDropDown();
	} else {
		document.getElementById("outcomeID").innerHTML="";
	}
}

function addOtherReasons(){
  var reasons="<p>"+reasonsDropDown("other_reasons[]","")+" "+removeItemHTML()+"</p>";
  $("#other_reasons").append(reasons);
}
//-->
</script>
<!--<form name="samples" method="post" action="/verify/approve.reject/<?=$id?>/<?=$pg?>/" onsubmit="return validate(this)">-->
<form name="samples" method="post" action="/verify/approve.reject/<?=$id?>/<?=$pg?>/">
<table width="100%" border="0" class="vl">
          <? if($success) { ?>
            <tr>
                <td class="vl_success">Sample Approval/Rejection Status Captured Successfully!</td>
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
                  <fieldset style="width: 100%" class="app_sect">
            <legend><strong>APPROVE/REJECT SAMPLE</strong></legend>
                        <div style="padding:5px 0px 0px 0px">
						      <table cellspacing="0" class="vl" width="65%">
                        <tr>
                          <td width="50%" class='info-sect'>
                            <span class='hdg-sm'>Facility Details</span><br>
                            <b>Name</b>: <?=$smpl_arr['facility']?><br>
                            <b>District</b>: <?=$smpl_arr['district']?><br>
                            <b>Hub</b>: <?=$smpl_arr['hub']?><br>
                          </td>
                          <td width="50%" class='info-sect'>
                            <span class='hdg-sm'>Sample Details</span><br>
                            <b>Form No.</b>: <?=$smpl_arr['formNumber']?><br>
                            <b>Date of Collection</b>: <span class='input-sect'><?=MyHTML::text('collectionDate',getFormattedDateLessDay($smpl_arr['collectionDate']),array('class'=>'sm_input date-picker')) ?></span><br>
                            <b>Sample Type</b>: <span class='input-sect'><?=MyHTML::select("sampleTypeID",$smpl_arr['sampleTypeID'],$sample_type_arr,"none",array('class'=>'search'))?></span><br>
                          </td>
                        </tr>
                        <tr>
                          <td class='info-sect'>
                            <span class='hdg-sm'>Patient Information</span><br>
                            <b>Art No.</b>: <span class='input-sect'><?=MyHTML::text('artNumber',$smpl_arr['artNumber'],array('class'=>'sm_input')) ?></span><br>
                            <b>Other ID</b>: <span class='input-sect'><?=MyHTML::text('otherID',$smpl_arr['otherID'],array('class'=>'sm_input')) ?></span><br> 
                            <b>Gender</b>: <span class='input-sect'><?=MyHTML::select("gender",$smpl_arr['gender'],$gender_arr,"none",array('class'=>'search'))?></span><br>
                          </td>
                          <td class='info-sect'>                    
                            <b>Date of Birth</b>: <span class='input-sect'><?=MyHTML::text('dateOfBirth',getFormattedDateLessDay($smpl_arr['dateOfBirth']),array('class'=>'sm_input date-picker')) ?></span><br>
                            <b>Date of Treatment initiation</b>: <span class='input-sect'><?=MyHTML::text('treatmentInitiationDate',getFormattedDateLessDay($smpl_arr['treatmentInitiationDate']),array('class'=>'sm_input date-picker')) ?></span><br>                            
                          </td>
                        </tr>
                        <tr class='info-sect'>
                          <td colspan='2' id="hi-lite-sect">
                            <b>Sample Reference No.</b>: <?=$smpl_arr['vlSampleID'] ?><br>
                            <b>Location ID</b>: <?=$smpl_arr['lrCategory'].$smpl_arr['lrEnvelopeNumber']."/".$smpl_arr['lrNumericID'] ?><br>
                          </td>
                        </tr>

                         <tr>
                          <td width="50%" class='info-sect'>                            
                            <b>Last Viral Load Date</b>:<span class='input-sect'><?=MyHTML::text('repeatVLTestLastVLDate',getFormattedDateLessDay($smpl_arr['repeatVLTestLastVLDate']),array('class'=>'sm_input date-picker')) ?></span><br>
                            <b>Value</b>: <span class='input-sect'><?=MyHTML::text('repeatVLTestValue',$smpl_arr['repeatVLTestValue'],array('class'=>'sm_input'))  ?></span> <br>
                          </td>
                          <td width="50%" class='info-sect'>                            
                            <b>Captured by</b>: <span class='input-sect'><?=$smpl_arr['createdby'] ?><br>
                            <b>On</b>: <span class='input-sect'><?=getFormattedDateLessDay($smpl_arr['created'])?><br>
                          </td>
                        </tr>

                        <tr class='info-sect'>
                          <td colspan='2'>
                            <span class='hdg-sm'>Received Status</span><br> 
                            <select name="outcome" id="outcome" class="search" onchange="checkOutcome()">
                                <option value="">Select Outcome</option>
                                <option value="Accepted">Accepted</option>
                                <option value="Rejected">Rejected</option>
                            </select>

                          <div id="outcomeID"></div>
                          </td>
                        </tr>
                      </table>
                        </div>
                  </fieldset>
                </td>
            </tr>
            <tr>
              <td style="padding:10px 0px 0px 0px">
              	<? if($searchQueryNextPosition) { ?><!--<input type="submit" name="saveChangesProceed" id="saveChangesProceed" class="button" value="  Save Changes then proceed to next Sample (<?=getDetailedTableInfo2("vl_samples","id='$searchQueryNextPosition' limit 1","formNumber")?>)  " />--><input type="submit" name="saveChangesProceed" id="saveChangesProceed" class="button" value="  Save Changes then proceed to next Sample  " /><? } ?>
              	<input type="submit" name="saveChangesReturn" id="saveChangesReturn" class="button" value="  Save Changes and Return  " />
                <input type="hidden" name="encryptedSample" id="encryptedSample" value="<?=$encryptedSample?>" />
                <input type="hidden" name="envelopeNumberFrom" id="envelopeNumberFrom" value="<?=$envelopeNumberFrom?>" />
                <input type="hidden" name="envelopeNumberTo" id="envelopeNumberTo" value="<?=$envelopeNumberTo?>" />
                <?=MyHTML::hidden('pat_id',$smpl_arr['pat_id']) ?>
                <?=MyHTML::hidden('smpl_id',$id) ?>
                <?=MyHTML::hidden('prev_smpl_data',$prev_smpl_data) ?>
              </td>
            </tr>
            <tr>
	            <td style="padding:20px 0px 0px 0px"><a href="/verify/">Return to Samples</a> :: <a href="/dashboard/">Return to Dashboard</a></td>
            </tr>
          </table>
</form>
<script type="text/javascript">
$(document).ready(function() {  

  $( ".date-picker" ).datepicker({
     changeMonth: true,
     changeYear: true,
     maxDate: new Date(),
     dateFormat: "dd-M-yy"
  });

  //console.log(new Date("2015-8-3"));

 });


</script>