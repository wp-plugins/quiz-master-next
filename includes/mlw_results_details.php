<?php
/*
This page allows for the viewing of the quiz results.
*/
/* 
Copyright 2013, My Local Webstop (email : fpcorso@mylocalwebstop.com)
*/

function mlw_generate_result_details()
{
	$mlw_result_id = $_GET["result_id"];
	if ($mlw_result_id != "")
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "mlw_results";
		$sql = "SELECT quiz_results FROM " . $wpdb->prefix . "mlw_results WHERE result_id=".$mlw_result_id."";
		$mlw_results_data = $wpdb->get_results($sql);
		?>
		<!-- css -->
		<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/redmond/jquery-ui.css" rel="stylesheet" />
		<!-- jquery scripts -->
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.0/jquery.min.js"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script type="text/javascript">
			var $j = jQuery.noConflict();
			// increase the default animation speed to exaggerate the effect
			$j.fx.speeds._default = 1000;
			$j(function() {
				$j('#dialog').dialog({
					autoOpen: false,
					show: 'blind',
					hide: 'explode',
					buttons: {
					Ok: function() {
						$j(this).dialog('close');
						}
					}
				});
			
				$j('#opener').click(function() {
					$j('#dialog').dialog('open');
					return false;
			}	);
			});
			$j(function() {
	   			 $j( document ).tooltip();
	 		});
			$j(function() {
				$j("button").button();
			
			});
		</script>
		<style>
	  		label {
	    		display: inline-block;
	    		width: 5em;
	  		}
	  	</style>
		<style type="text/css">
		div.mlw_quiz_options input[type='text'] {
			border-color:#000000;
			color:#3300CC; 
			cursor:hand;
			}
		</style>
		<div class="wrap">
		<div class='mlw_quiz_options'>
		<h2>Quiz Results<a id="opener" href="">(?)</a></h2>
		<?php 
			foreach($mlw_results_data as $mlw_results_info) {
			echo htmlspecialchars_decode($mlw_results_info->quiz_results, ENT_QUOTES);
			}
		?>
		<div id="dialog" title="Help">
		<h3><b>Help</b></h3>
		<p>This page shows the results from the taken quiz.</p>
		<p>The top section shows the question, the user's answer, and the correct answer.</p>
		<p>The bottom section shows the text from the comment box if enabled.</p>
		</div>	
		</div>
		</div>
		
<?php
	}
	else
	{
		?>
		<!-- css -->
		<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/redmond/jquery-ui.css" rel="stylesheet" />
		<!-- jquery scripts -->
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.0/jquery.min.js"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script type="text/javascript">
			var $j = jQuery.noConflict();
			// increase the default animation speed to exaggerate the effect
			$j.fx.speeds._default = 1000;
			$j(function() {
				$j('#dialog').dialog({
					autoOpen: false,
					show: 'blind',
					hide: 'explode',
					buttons: {
					Ok: function() {
						$j(this).dialog('close');
						}
					}
				});
			
				$j('#opener').click(function() {
					$j('#dialog').dialog('open');
					return false;
			}	);
			});
			$j(function() {
	   			 $j( document ).tooltip();
	 		});
			$j(function() {
				$j("button").button();
			
			});
		</script>
		<style>
	  		label {
	    		display: inline-block;
	    		width: 5em;
	  		}
	  	</style>
		<style type="text/css">
		div.mlw_quiz_options input[type='text'] {
			border-color:#000000;
			color:#3300CC; 
			cursor:hand;
			}
		</style>
		<div class="wrap">
		<div class='mlw_quiz_options'>
		<h2>Quiz Results<a id="opener" href="">(?)</a></h2>
		<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
		<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
		<strong>Hey!</strong> Please go to the Quiz Results page and click on the View link from the result you wish to see.</p>
		</div>
		<div id="dialog" title="Help">
		<h3><b>Help</b></h3>
		<p>You are getting this error page because this page could not find the results.</p>
		<p>You must go to the Quiz Results page and click on the result you want to see from that table.</p>
		</div>	
		</div>
		</div>
		<?php
	}
}
?>