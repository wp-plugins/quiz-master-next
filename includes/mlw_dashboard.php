<?php
/*
This page creates the main dashboard for the Quiz Master Next plugin
*/
/* 
Copyright 2013, My Local Webstop (email : fpcorso@mylocalwebstop.com)
*/

function mlw_generate_quiz_dashboard(){
	$mlw_quiz_version = get_option('mlw_quiz_master_version');
	add_meta_box("wpss_mrts", 'Quiz Stats', "mlw_dashboard_box", "quiz_wpss");  
	add_meta_box("wpss_mrts", 'Help', "mlw_dashboard_box_two", "quiz_wpss2");
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
	</script>
	<style type="text/css">
		textarea{
		border-color:#000000;
		color:#3300CC; 
		cursor:hand;
		}
		p em {
		padding-left: 1em;
		color: #555;
		font-weight: bold;
		}
	</style>
	<div class="wrap">
	<h2>Quiz Master Next Dashboard<a id="opener" href="">(?)</a></h2>
	
	<h3>Version <?php echo $mlw_quiz_version; ?></h3>
	<p>Thank you for trying out my new plugin. I hope you find it beneficial to your website.</p>
	
	<div style="float:left; width:60%;" class="inner-sidebar1">
		<?php do_meta_boxes('quiz_wpss','advanced','');  ?>	
	</div>
	
	<div style="float:right; width:36%; " class="inner-sidebar1">
		<?php do_meta_boxes('quiz_wpss2','advanced',''); ?>	
	</div>
			
	<!--<div style="clear:both"></div>-->

	<div id="dialog" title="Help">
	<h3><b>Help</b></h3>
	<p>This page is the main admin page for the Quiz Master Next.</p>
	<p>The first widget lists all the statistics collected so far.</p>
	<p>The second widget gives tips to better use the plugin.</p>
	</div>

	</div>
	<?php
}

function mlw_dashboard_box()
{
	global $wpdb;
	$sql = "SELECT SUM(quiz_views) AS QuizViews FROM " . $wpdb->prefix . "mlw_quizzes";
	$mlw_quiz_views = $wpdb->get_results($sql);

	foreach($mlw_quiz_views as $mlw_eaches) {
		$mlw_quiz_views = $mlw_eaches->QuizViews;
		break;
	}

	$sql = "SELECT SUM(quiz_taken) AS QuizTaken FROM " . $wpdb->prefix . "mlw_quizzes";
	$mlw_quiz_taken = $wpdb->get_results($sql);

	foreach($mlw_quiz_taken as $mlw_eaches) {
		$mlw_quiz_taken = $mlw_eaches->QuizTaken;
		break;
	}
	
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_quizzes ORDER BY quiz_views DESC LIMIT 1";
	$mlw_quiz_most_viewed = $wpdb->get_results($sql);

	foreach($mlw_quiz_most_viewed as $mlw_eaches) {
		$mlw_quiz_most_viewed = $mlw_eaches->quiz_name;
		break;
	}
	
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_quizzes ORDER BY quiz_taken DESC LIMIT 1";
	$mlw_quiz_most_taken = $wpdb->get_results($sql);

	foreach($mlw_quiz_most_taken as $mlw_eaches) {
		$mlw_quiz_most_taken = $mlw_eaches->quiz_name;
		break;
	}
	?>
	<div>
	<table width='100%'>
	<tr>
	<td align='left'>Total Times All Quizzes Have Been Viewed</td>
	<td align='right'><?php echo $mlw_quiz_views; ?></td>
	</tr>
	<tr>
	<td align='left'>Total Times All Quizzes Have Been Taken</td>
	<td align='right'><?php echo $mlw_quiz_taken; ?></td>
	</tr>
	<tr>
	<td align='left'>Quiz That Has Been Viewed The Most</td>
	<td align='right'><?php echo $mlw_quiz_most_viewed; ?></td>
	</tr>
	<tr>
	<td align='left'>Quiz That Has Been Taken The Most</td>
	<td align='right'><?php echo $mlw_quiz_most_taken; ?></td>
	</tr>
	</table>
	</div>
	<?php
}

function mlw_dashboard_box_two()
{
	?>
	<div>
	<table width='100%'>
	<tr>
	<td align='left'>There is a (?) next to the title of each page.  Click on it to bring up the help for that page.</td>
	</tr>
	<tr>
	<td align='left'></td>
	</tr>
	<tr>
	<td align='left'></td>
	</tr>
	<tr>
	<td align='left'></td>
	</tr>
	<tr>
	<td align='left'></td>
	</tr>
	<tr>
	<td align='left'></td>
	</tr>
	<tr>
	<td align='left'></td>
	</tr>
	</table>
	</div>
	<?php
}
?>