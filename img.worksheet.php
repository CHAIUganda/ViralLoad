<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";

//declarations
$code="code128";
$o=2;
$dpi=50;
$t=50;
$r=1;
$rot=0;
$text=vlDecrypt(getValidatedVariable("sampleID"));
$f1="Arial.ttf";
$f2=0;
$a1=0;
$a2="B";
$a3=0;

$filename = $system_temp_array2[0];

require("modules.raw/barcodes/BCGColor.php");
require("modules.raw/barcodes/BCGBarcode.php");
require("modules.raw/barcodes/BCGDrawing.php");
require("modules.raw/barcodes/BCGFont.php");

if(include("modules.raw/barcodes/BCG".$code.".barcode.php")) {
	if($f1 && $f1 !== '-1' && intval($f2) >= 1) {
		$font = new BCGFont("modules.raw/barcodes/font/".$f1, intval($f2));
	} else {
		$font = 0;
	}
	$color_black = new BCGColor(0, 0, 0);
	$color_white = new BCGColor(255, 255, 255);
	$codebar = "BCG".$code;
	$code_generated = new $codebar();
	if($a1 && intval($a1) === 1) {
		$code_generated->setChecksum(true);
	}
	if($a2 && !empty($a2)) {
		$code_generated->setStart($a2);
	}
	if(isset($a3) && !empty($a3)) {
		$code_generated->setLabel($a3);
	}
	$code_generated->setThickness($t);
	$code_generated->setScale($r);
	$code_generated->setBackgroundColor($color_white);
	$code_generated->setForegroundColor($color_black);
	$code_generated->setFont($font);
	$code_generated->parse($text);
	
	$drawing = new BCGDrawing('', $color_white);
	$drawing->setBarcode($code_generated);
	$drawing->setRotationAngle($rot);
	$drawing->setDPI($dpi == 'null' ? null : (int)$dpi);
	$drawing->draw();
	if(intval($o) === 1) {
		header('Content-Type: image/png');
	} elseif(intval($o) === 2) {
		header('Content-Type: image/jpeg');
	} elseif(intval($o) === 3) {
		header('Content-Type: image/gif');
	}
	$drawing->finish(intval($o));
} else {
	header('Content-Type: image/png');
	readfile('error.png');
}
?>