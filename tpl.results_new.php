<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}
//sample_results/live_search/
?>

<link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<script src="/bootstrap/js/bootstrap.min.js"></script>

<div class="row">
	<div class="drop_down_container col-lg-4">
		<label for="b">Search worksheet:</label> 
		<?=MyHTML::text('worksheet','',array('class'=>'form-control input-sm input_md', 'id'=>'b', 'autocomplete'=>'off')) ?>
		<div class='live_drpdwn' id="worksheet_dropdown" style='display:none'></div>
	</div>

	<div class="drop_down_container col-lg-4">
		<label for="s">Search sample:</label>
		<?=MyHTML::text('sample','',array('class'=>'form-control input-sm input_md', 'id'=>'s', 'autocomplete'=>'off')) ?>
		<div class='live_drpdwn'id="sample_dropdown" style='display:none'></div>
	</div>
</div>

<script type="text/javascript">
$(function(){
	drpdwn= $(".live_drpdwn");

	function get_data(q,drpdwn,link){
		if(q && q.length>=3){	
			//console.log("this is what you have just typed:"+ q+"link"+link);		
			$.get(link+q+"/", function(data){
				drpdwn.show();
				drpdwn.html(data);
			});
		}else{
			drpdwn.hide();
			drpdwn.html("");
		}
	}

	$("#b").keyup(function(){
		var q = $(this).val();
		var dd = $("#worksheet_dropdown");
		get_data(q, dd, "/worksheet_results/live_search/");
	});

	$("#s").keyup(function(){
		var q = $(this).val();
		var dd = $("#sample_dropdown");
		get_data(q, dd, "/sample_results/live_search/");
	});



	$(".drop_down_container").mouseover(function(){ drpdwn.show(); });

	$(".drop_down_container").mouseout(function(){ drpdwn.hide(); });
});

function windPop(link) {
	window.open(link,"zzz","width=1100,height=1000,menubar=no,resizable=yes,scrollbars=yes");
}
</script>
