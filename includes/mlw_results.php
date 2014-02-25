<?php
/*
This page shows all of the results from the taken quizzes.
*/
/* 
Copyright 2013, My Local Webstop (email : fpcorso@mylocalwebstop.com)
*/

function mlw_generate_quiz_results()
{
	global $wpdb;
	$mlw_hasDeletedResults = false;
	
	///Delete Results Function
	if (isset($_POST["delete_results"]) && $_POST["delete_results"] == "confirmation")
	{
		///Variables from delete result form
		$mlw_delete_results_confirmation = $_POST["delete_results"];
		$mlw_delete_results_id = $_POST["result_id"];
		$mlw_delete_results_name = $_POST["delete_quiz_name"];
		$mlw_delete_results_update_sql = "UPDATE " . $wpdb->prefix . "mlw_results" . " SET deleted=1 WHERE result_id=".$mlw_delete_results_id;
		$mlw_delete_results_results = $wpdb->query( $mlw_delete_results_update_sql );
		$mlw_hasDeletedResults = true;
		
		//Insert Action Into Audit Trail
		global $current_user;
		get_currentuserinfo();
		$table_name = $wpdb->prefix . "mlw_qm_audit_trail";
		$insert = "INSERT INTO " . $table_name .
			"(trail_id, action_user, action, time) " .
			"VALUES (NULL , '" . $current_user->display_name . "' , 'Results Has Been Deleted From: ".$mlw_delete_results_name."' , '" . date("h:i:s A m/d/Y") . "')";
		$results = $wpdb->query( $insert );		
	}

	global $wpdb;

	$sql = "SELECT * FROM " . $wpdb->prefix . "mlw_results WHERE deleted='0'";
	if (isset($_GET["quiz_id"]) && $_GET["quiz_id"] != "")
	{
		$sql .= " AND quiz_id=".$quiz_id;
	}
	$sql .= " ORDER BY result_id DESC";

	$mlw_quiz_data = $wpdb->get_results($sql);
	?>
	<!-- css -->
	<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/redmond/jquery-ui.css" rel="stylesheet" />
	<!-- jquery scripts -->
	<?php
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-dialog' );
	wp_enqueue_script( 'jquery-ui-button' );
	wp_enqueue_script( 'jquery-ui-accordion' );
	wp_enqueue_script( 'jquery-ui-tooltip' );
	wp_enqueue_script( 'jquery-ui-tabs' );
	wp_enqueue_script( 'jquery-effects-blind' );
	wp_enqueue_script( 'jquery-effects-explode' );
	?>
	<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.0/jquery.min.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>-->
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
			$j("button").button();
		
		});
		function deleteResults(id,quizName){
			$j("#delete_dialog").dialog({
				autoOpen: false,
				show: 'blind',
				hide: 'explode',
				buttons: {
				Cancel: function() {
					$j(this).dialog('close');
					}
				}
			});
			$j("#delete_dialog").dialog('open');
			var idHidden = document.getElementById("result_id");
			var idHiddenName = document.getElementById("delete_quiz_name");
			idHidden.value = id;
			idHiddenName.value = quizName;
		};
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
	<?php if ($mlw_hasDeletedResults)
		{
	?>
		<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
		<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
		<strong>Success!</strong> Your results have been deleted.</p>
	</div>
	<?php
		}
	?>
	<?php 
	$quotes_list = "";
	$display = "";
	$alternate = "";
	foreach($mlw_quiz_data as $mlw_quiz_info) {
		if($alternate) $alternate = "";
		else $alternate = " class=\"alternate\"";
		$quotes_list .= "<tr{$alternate}>";
		$quotes_list .= "<td><span style='color:green;font-size:16px;'><a href='admin.php?page=mlw_quiz_result_details&&result_id=".$mlw_quiz_info->result_id."'>View</a>|<a onclick=\"deleteResults('".$mlw_quiz_info->result_id."','".$mlw_quiz_info->quiz_name."')\" href='#'>Delete</a></span></td>";
		$quotes_list .= "<td><span style='font-size:16px;'>" . $mlw_quiz_info->quiz_name . "</span></td>";
		if ($mlw_quiz_info->quiz_system == 0)
		{
			$quotes_list .= "<td class='post-title column-title'><span style='font-size:16px;'>" . $mlw_quiz_info->correct ." out of ".$mlw_quiz_info->total." or ".$mlw_quiz_info->correct_score."%</span></td>";
		}
		if ($mlw_quiz_info->quiz_system == 1)
		{
			$quotes_list .= "<td><span style='font-size:16px;'>" . $mlw_quiz_info->point_score . " Points</span></td>";
		}
		if ($mlw_quiz_info->quiz_system == 2)
		{
			$quotes_list .= "<td><span style='font-size:16px;'>Not Graded</span></td>";
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
			<th>Actions</th>
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

	<div id="dialog" title="Help" style="display:none;">
	<h3><b>Help</b></h3>
	<p>This page shows all of the results from the taken quizzes.</p>
	<p>The table show the result id, the score from the quiz, the contact information provided, and the time the quiz was taken.</p>
	<p>To get results to a specific quiz, go to quiz page and click on results from that quiz.</p>
	</div>
	<div id="delete_dialog" title="Delete Results?" style="display:none;">
	<h3><b>Are you sure you want to delete these results?</b></h3>
	<?php
	echo "<form action='' method='post'>";
	echo "<input type='hidden' name='delete_results' value='confirmation' />";
	echo "<input type='hidden' id='result_id' name='result_id' value='' />";
	echo "<input type='hidden' id='delete_quiz_name' name='delete_quiz_name' value='' />";
	echo "<p class='submit'><input type='submit' class='button-primary' value='Delete Results' /></p>";
	echo "</form>";	
	?>
	</div>
	</div>
	</div>
<?php
}
?>