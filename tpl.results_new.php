<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}
?>

<div id="drop_down_container">
	<label for="search">Search worksheet:</label><br>
	<?=MyHTML::text('worksheet','',array('class'=>'input_md', 'id'=>'search', 'autocomplete'=>'off')) ?>
	<div class='live_drpdwn' style='display:none'></div>
</div>

<script type="text/javascript">
$(function(){
	drpdwn= $(".live_drpdwn");

	$("#search").keyup(function(){				
		var q = $(this).val();
		if(q && q.length>=3){			
			$.get("/worksheet_results/live_search/"+q+"/", function(data){
				drpdwn.show();
				drpdwn.html(data);
			});
		}else{
			drpdwn.hide();
			drpdwn.html("");
		}
	});

	$("#drop_down_container").mouseover(function(){ drpdwn.show(); });

	$("#drop_down_container").mouseout(function(){ drpdwn.hide(); });
});

function windPop(link) {
  window.open(link,"zzz","width=1100,height=1000,menubar=no,resizable=yes,scrollbars=yes");
 }
</script>
