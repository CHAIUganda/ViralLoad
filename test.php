<?
$lrEnvelopeNumber="1510-1348";

$lrEnvelopeNumberArray=array();
$lrEnvelopeNumberArray=explode("-",$lrEnvelopeNumber);

$envelopeNumber=0;
$envelopeNumber=$lrEnvelopeNumberArray[1]/1;

echo "lrEnvelopeNumber: $lrEnvelopeNumber, envelopeNumber: $envelopeNumber";
?>