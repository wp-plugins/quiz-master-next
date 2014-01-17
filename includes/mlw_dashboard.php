<?php
/*
This page creates the main dashboard for the Quiz Master Next plugin
*/
/* 
Copyright 2014, My Local Webstop (email : fpcorso@mylocalwebstop.com)
*/

function mlw_generate_quiz_dashboard(){
	$mlw_quiz_version = get_option('mlw_quiz_master_version');
	
	///Creates the widgets
	add_meta_box("wpss_mrts", 'Quiz Daily Stats - Times Taken', "mlw_dashboard_box", "quiz_wpss");  
	add_meta_box("wpss_mrts", 'Help', "mlw_dashboard_box_two", "quiz_wpss2");
	add_meta_box("wpss_mrts", 'Quiz Total Stats', "mlw_dashboard_box_three", "quiz_wpss3");
	add_meta_box("wpss_mrts", 'Quiz Weekly Stats - Times Taken', "mlw_dashboard_box_four", "quiz_wpss4");
	add_meta_box("wpss_mrts", 'Quiz Monthly Stats - Times Taken', "mlw_dashboard_box_five", "quiz_wpss5");
	?>
	<!-- css -->
	<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/redmond/jquery-ui.css" rel="stylesheet" />
	<!-- jquery scripts -->
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.0/jquery.min.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo plugin_dir_url( $file ); ?>quiz-master-next/includes/jquery_sparkline.js"></script>
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
        	$j('.inlinesparkline').sparkline('html', {type: 'line', width: '400', height: '200'}); 
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
	<p>Thank you for trying out this plugin. I hope you find it beneficial to your website. If it is, please consider donating from the support page. Or, please consider rating this plugin <a href="http://wordpress.org/support/view/plugin-reviews/quiz-master-next">here</a>.</p>
	
	<div style="float:left; width:60%;" class="inner-sidebar1">
		<?php do_meta_boxes('quiz_wpss','advanced','');  ?>	
	</div>
	
	<div style="float:right; width:36%;" class="inner-sidebar1">
		<?php do_meta_boxes('quiz_wpss3','advanced','');  ?>	
	</div>
			
	<!--<div style="clear:both"></div>-->
	
	<div style="float:left; width:60%;" class="inner-sidebar1">
		<?php do_meta_boxes('quiz_wpss4','advanced','');  ?>	
	</div>
	
	<div style="float:right; width:36%; " class="inner-sidebar1">
		<?php do_meta_boxes('quiz_wpss2','advanced',''); ?>	
	</div>
	
	<!--<div style="clear:both"></div>-->
	
	<div style="float:left; width:60%;" class="inner-sidebar1">
		<?php do_meta_boxes('quiz_wpss5','advanced','');  ?>	
	</div>
	
	<!--<div style="clear:both"></div>-->
	
	<div id="dialog" title="Help">
	<h3><b>Help</b></h3>
	<p>This page is the main admin page for the Quiz Master Next.</p>
	<p>The first widget shows the times all quizzes have been taken over the last week.</p>
	<p>The second widget gives tips to better use the plugin.</p>
	<p>The third widget lists the total quiz statistics.</p>
	</div>

	</div>
	<?php
}

function mlw_dashboard_box()
{
	//Gather the weekly stats, one variable for each day for the graph
	global $wpdb;
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".date("Y-m-d")." 00:00:00' AND '".date("Y-m-d")." 23:59:59')";
	$mlw_quiz_taken_today = $wpdb->get_results($sql);
	$mlw_quiz_taken_today = $wpdb->num_rows;
	
	$mlw_yesterday =  mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"));
	$mlw_yesterday = date("Y-m-d", $mlw_yesterday);
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_yesterday." 00:00:00' AND '".$mlw_yesterday." 23:59:59')";
	$mlw_quiz_taken_yesterday = $wpdb->get_results($sql);
	$mlw_quiz_taken_yesterday = $wpdb->num_rows;
	
	$mlw_three_days_ago =  mktime(0, 0, 0, date("m")  , date("d")-2, date("Y"));
	$mlw_three_days_ago = date("Y-m-d", $mlw_three_days_ago);
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_three_days_ago." 00:00:00' AND '".$mlw_three_days_ago." 23:59:59')";
	$mlw_quiz_taken_three_days = $wpdb->get_results($sql);
	$mlw_quiz_taken_three_days = $wpdb->num_rows;
	
	$mlw_four_days_ago =  mktime(0, 0, 0, date("m")  , date("d")-3, date("Y"));
	$mlw_four_days_ago = date("Y-m-d", $mlw_four_days_ago);
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_four_days_ago." 00:00:00' AND '".$mlw_four_days_ago." 23:59:59')";
	$mlw_quiz_taken_four_days = $wpdb->get_results($sql);
	$mlw_quiz_taken_four_days = $wpdb->num_rows;
	
	$mlw_five_days_ago =  mktime(0, 0, 0, date("m")  , date("d")-4, date("Y"));
	$mlw_five_days_ago = date("Y-m-d", $mlw_five_days_ago);
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_five_days_ago." 00:00:00' AND '".$mlw_five_days_ago." 23:59:59')";
	$mlw_quiz_taken_five_days = $wpdb->get_results($sql);
	$mlw_quiz_taken_five_days = $wpdb->num_rows;
	
	$mlw_six_days_ago =  mktime(0, 0, 0, date("m")  , date("d")-5, date("Y"));
	$mlw_six_days_ago = date("Y-m-d", $mlw_six_days_ago);
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_six_days_ago." 00:00:00' AND '".$mlw_six_days_ago." 23:59:59')";
	$mlw_quiz_taken_six_days = $wpdb->get_results($sql);
	$mlw_quiz_taken_six_days = $wpdb->num_rows;
	
	$mlw_last_week =  mktime(0, 0, 0, date("m")  , date("d")-6, date("Y"));
	$mlw_last_week = date("Y-m-d", $mlw_last_week);
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_last_week." 00:00:00' AND '".$mlw_last_week." 23:59:59')";
	$mlw_quiz_taken_week = $wpdb->get_results($sql);
	$mlw_quiz_taken_week = $wpdb->num_rows;
	?>
	<div>
	<span class="inlinesparkline"><?php echo $mlw_quiz_taken_week.",".$mlw_quiz_taken_six_days.",".$mlw_quiz_taken_five_days.",".$mlw_quiz_taken_four_days.",".$mlw_quiz_taken_three_days.",".$mlw_quiz_taken_yesterday.",".$mlw_quiz_taken_today; ?></span>
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
	<td align='left'>If the help does not answer your question, take a look at the How-To section from the menu.</td>
	</tr>
	<tr>
	<td align='left'>If you still are having trouble, feel free to use the support section from the support page to contact me.</td>
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
function mlw_dashboard_box_three()
{
	//Gather some other useful stats
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
	
	$sql = "SELECT AVG(quiz_views) AS AvgViews FROM " . $wpdb->prefix . "mlw_quizzes";
	$mlw_average_views = $wpdb->get_results($sql);

	foreach($mlw_average_views as $mlw_eaches) {
		$mlw_average_views = $mlw_eaches->AvgViews;
		break;
	}
	
	$sql = "SELECT AVG(quiz_taken) AS AvgTaken FROM " . $wpdb->prefix . "mlw_quizzes";
	$mlw_average_taken = $wpdb->get_results($sql);

	foreach($mlw_average_taken as $mlw_eaches) {
		$mlw_average_taken = $mlw_eaches->AvgTaken;
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
	<td align='left'>Average Views Per Quiz</td>
	<td align='right'><?php echo $mlw_average_views; ?></td>
	</tr>
	<tr>
	<td align='left'>Average Times Taken Per Quiz</td>
	<td align='right'><?php echo $mlw_average_taken; ?></td>
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
function mlw_dashboard_box_four()
{
	//Gather the weekly stats, one variable for each day for the graph
	global $wpdb;	
	$mlw_this_week =  mktime(0, 0, 0, date("m")  , date("d")-6, date("Y"));
	$mlw_this_week = date("Y-m-d", $mlw_this_week);
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_this_week." 00:00:00' AND '".date("Y-m-d")." 23:59:59')";
	$mlw_quiz_taken_this_week = $wpdb->get_results($sql);
	$mlw_quiz_taken_this_week = $wpdb->num_rows;
	
	$mlw_last_week_first =  mktime(0, 0, 0, date("m")  , date("d")-13, date("Y"));
	$mlw_last_week_first = date("Y-m-d", $mlw_last_week_first);
	$mlw_last_week_last =  mktime(0, 0, 0, date("m")  , date("d")-7, date("Y"));
	$mlw_last_week_last = date("Y-m-d", $mlw_last_week_last);
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_last_week_first." 00:00:00' AND '".$mlw_last_week_last." 23:59:59')";
	$mlw_quiz_taken_last_week = $wpdb->get_results($sql);
	$mlw_quiz_taken_last_week = $wpdb->num_rows;
	
	$mlw_two_week_first =  mktime(0, 0, 0, date("m")  , date("d")-20, date("Y"));
	$mlw_two_week_first = date("Y-m-d", $mlw_two_week_first);
	$mlw_two_week_last =  mktime(0, 0, 0, date("m")  , date("d")-14, date("Y"));
	$mlw_two_week_last = date("Y-m-d", $mlw_two_week_last);
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_two_week_first." 00:00:00' AND '".$mlw_two_week_last." 23:59:59')";
	$mlw_quiz_taken_two_week = $wpdb->get_results($sql);
	$mlw_quiz_taken_two_week = $wpdb->num_rows;
	
	$mlw_three_week_first =  mktime(0, 0, 0, date("m")  , date("d")-27, date("Y"));
	$mlw_three_week_first = date("Y-m-d", $mlw_three_week_first);
	$mlw_three_week_last =  mktime(0, 0, 0, date("m")  , date("d")-21, date("Y"));
	$mlw_three_week_last = date("Y-m-d", $mlw_three_week_last);
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_three_week_first." 00:00:00' AND '".$mlw_three_week_last." 23:59:59')";
	$mlw_quiz_taken_three_week = $wpdb->get_results($sql);
	$mlw_quiz_taken_three_week = $wpdb->num_rows;
	?>
	<div>
	<span class="inlinesparkline"><?php echo $mlw_quiz_taken_three_week.",".$mlw_quiz_taken_two_week.",".$mlw_quiz_taken_last_week.",".$mlw_quiz_taken_this_week; ?></span>
	</div>
	<?php
}
function mlw_dashboard_box_five()
{
	//Gather the monthly stats, one variable for each day for the graph
	global $wpdb;	
	$mlw_this_month =  mktime(0, 0, 0, date("m")  , date("d")-30, date("Y"));
	$mlw_this_month = date("Y-m-d", $mlw_this_month);
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_this_month." 00:00:00' AND '".date("Y-m-d")." 23:59:59')";
	$mlw_quiz_taken_this_month = $wpdb->get_results($sql);
	$mlw_quiz_taken_this_month = $wpdb->num_rows;
	
	$mlw_last_month_first =  mktime(0, 0, 0, date("m")  , date("d")-60, date("Y"));
	$mlw_last_month_first = date("Y-m-d", $mlw_last_month_first);
	$mlw_last_month_last =  mktime(0, 0, 0, date("m")  , date("d")-31, date("Y"));
	$mlw_last_month_last = date("Y-m-d", $mlw_last_month_last);
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_last_month_first." 00:00:00' AND '".$mlw_last_month_last." 23:59:59')";
	$mlw_quiz_taken_last_month = $wpdb->get_results($sql);
	$mlw_quiz_taken_last_month = $wpdb->num_rows;
	
	$mlw_two_month_first =  mktime(0, 0, 0, date("m")  , date("d")-60, date("Y"));
	$mlw_two_month_first = date("Y-m-d", $mlw_two_month_first);
	$mlw_two_month_last =  mktime(0, 0, 0, date("m")  , date("d")-31, date("Y"));
	$mlw_two_month_last = date("Y-m-d", $mlw_two_month_last);
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_two_month_first." 00:00:00' AND '".$mlw_two_month_last." 23:59:59')";
	$mlw_quiz_taken_two_month = $wpdb->get_results($sql);
	$mlw_quiz_taken_two_month = $wpdb->num_rows;
	
	?>
	<div>
	<span class="inlinesparkline"><?php echo $mlw_quiz_taken_two_month.",".$mlw_quiz_taken_last_month.",".$mlw_quiz_taken_this_month; ?></span>
	</div>
	<?php
}
?>