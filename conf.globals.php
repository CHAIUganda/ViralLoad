<?
foreach ($_REQUEST as $key => $val) {
	global ${$key};
	${$key} = $val;
}
?>
