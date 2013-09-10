<?php
/*
This page shows all of the results from the taken quizzes.
*/
/* 
Copyright 2013, My Local Webstop (email : fpcorso@mylocalwebstop.com)
*/

function mlw_generate_quiz_results()
{
	$quiz_id = $_GET["quiz_id"];

	global $wpdb;

	$sql = "SELECT * FROM " . $wpdb->prefix . "mlw_results WHERE deleted='0'";
	if ($quiz_id != "")
	{
		$sql .= " AND quiz_id=".$quiz_id;
	}
	$sql .= " ORDER BY result_id DESC";

	$mlw_quiz_data = $wpdb->get_results($sql);
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
	$quotes_list = "";
	$display = "";
	foreach($mlw_quiz_data as $mlw_quiz_info) {
		if($alternate) $alternate = "";
		else $alternate = " class=\"alternate\"";
		$quotes_list .= "<tr{$alternate}>";
		$quotes_list .= "<td><span style='font-size:16px;'>" . $mlw_quiz_info->result_id . "</span></td>";
		$quotes_list .= "<td><span style='font-size:16px;'>" . $mlw_quiz_info->quiz_name . "</span></td>";
		if ($mlw_quiz_info->quiz_system == 0)
		{
			$quotes_list .= "<td class='post-title column-title'><span style='font-size:16px;'>" . $mlw_quiz_info->correct ." out of ".$mlw_quiz_info->total." or ".$mlw_quiz_info->correct_score."%</span></td>";
		}
		else
		{
		$quotes_list .= "<td><span style='font-size:16px;'>" . $mlw_quiz_info->point_score . " Points</span></td>";
		}
		$quotes_list .= "<td><span style='font-size:16px;'>" . $mlw_quiz_info->name ."</span></td>";
		$quotes_list .= "<td><span style='font-size:16px;'>" . $mlw_quiz_info->business ."</span></td>";
		$quotes_list .= "<td><span style='font-size:16px;'>" . $mlw_quiz_info->email ."</span></td>";
		$quotes_list .= "<td><span style='font-size:16px;'>" . $mlw_quiz_info->phone ."</span></td>";
		$quotes_list .= "<td><span style='font-size:16px;'>" . $mlw_quiz_info->time_taken ."</span></td>";
		$quotes_list .= "</tr>";
	}

	$display .= "<table class=\"widefat\">";
		$display .= "<thead><tr>
			<th>Result ID</th>
			<th>Quiz Name</th>
			<th>Score</th>
			<th>Name</th>
			<th>Business</th>
			<th>Email</th>
			<th>Phone</th>
			<th>Time Taken</th>
		</tr></thead>";
		$display .= "<tbody id=\"the-list\">{$quotes_list}</tbody>";
		$display .= "</table>";
	echo $display;
	?>

	<div id="dialog" title="Help">
	<h3><b>Help</b></h3>
	<p>This page shows all of the results from the taken quizzes.</p>
	<p>The table show the result id, the score from the quiz, the contact information provided, and the time the quiz was taken.</p>
	<p>To get results to a specific quiz, go to quiz page and click on results from that quiz.</p>
	</div>	
	</div>
	</div>
<?php
}
?>