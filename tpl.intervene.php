<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}
//sample_results/live_search/
$res = mysqlquery(
		"SELECT fp.sample_id, comments, s.*, rr.result, rr.suppressed FROM vl_facility_printing AS fp 
		INNER JOIN vl_samples AS s ON fp.sample_id = s.id
		INNER JOIN vl_results_released AS rr ON rr.sample_id = s.id
		WHERE ready = 'NO' LIMIT 1000");
?>

<link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<script src="/bootstrap/js/bootstrap.min.js"></script>
<script src="/js/jquery.dataTables.min.js"></script>

<table id="results-table" class="table table-condensed table-striped table-bordered">
	<thead>
		<td>Location ID</td>
		<th>Form Number</th>
		<th>Comments</th>
		<th>Result</th>
		<th>Suppressed</th>
		<th>Created</th>
	</thead>
	<tbody>
		
		<?php
		while($row = mysqlfetcharray($res)){
			extract($row);
			$e_form = vlEncrypt($formNumber);
			$lnk = "/samples/find.and.edit/intervene/$e_form/";
			echo "<tr>
					<td>$lrCategory$lrEnvelopeNumber/$lrNumericID </td>
					<td><a href='$lnk'>$formNumber</a></td>
					<td>$comments</td>
					<td>$result</td>
					<td>$suppressed</td>
					<td>$createdby</td>
				  </tr>";
		}
		?>
	</tbody>

</table>

<script type="text/javascript">
$(function() {
    $('#results-table').DataTable();
});
</script>