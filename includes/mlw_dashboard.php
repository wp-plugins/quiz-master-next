<?php
/*
This page creates the main dashboard for the Quiz Master Next plugin
*/
/* 
Copyright 2014, My Local Webstop (email : fpcorso@mylocalwebstop.com)
*/

function mlw_generate_quiz_dashboard(){
	
	echo "
		<script>
		function mlw_validateForm()
		{
			var x=document.forms['emailForm']['email'].value;
			if (x==null || x=='')
			{
				document.getElementById('mlw_support_message').innerHTML = '**Email must be filled out!**';
				return false;
			};
			var x=document.forms['emailForm']['username'].value;
			if (x==null || x=='')
			{
				document.getElementById('mlw_support_message').innerHTML = '**Name must be filled out!**';
				return false;
			};
			var x=document.forms['emailForm']['message'].value;
			if (x==null || x=='')
			{
				document.getElementById('mlw_support_message').innerHTML = '**There must be a message to send!**';
				return false;
			};
			var x=document.forms['emailForm']['email'].value;
			var atpos=x.indexOf('@');
			var dotpos=x.lastIndexOf('.');
			if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length)
			{
				document.getElementById('mlw_support_message').innerHTML = '**Not a valid e-mail address!**';
				return false;
			}
		}
	</script>
	";
	$mlw_quiz_version = get_option('mlw_quiz_master_version');
	
	///Creates the widgets
	add_meta_box("wpss_mrts", 'Quiz Daily Stats - Times Taken', "mlw_dashboard_box", "quiz_wpss");  
	add_meta_box("wpss_mrts", 'Quiz Total Stats', "mlw_dashboard_box_three", "quiz_wpss3");
	add_meta_box("wpss_mrts", 'Quiz Weekly Stats - Times Taken', "mlw_dashboard_box_four", "quiz_wpss4");
	add_meta_box("wpss_mrts", 'Quiz Monthly Stats - Times Taken', "mlw_dashboard_box_five", "quiz_wpss5");
	add_meta_box("wpss_mrts", 'In This Update', "mlw_dashboard_box_six", "quiz_wpss6"); 
	add_meta_box("wpss_mrts", 'Support', "mlw_dashboard_box_seven", "quiz_wpss7");
	add_meta_box("wpss_mrts", 'Contribution', "mlw_dashboard_box_eight", "quiz_wpss8");
	add_meta_box("wpss_mrts", 'News From My Local Webstop', "mlw_dashboard_box_nine", "quiz_wpss9");
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
		div.quiz_email_support {
		text-align: left;
		}
		div.quiz_email_support input[type='text'] {
		border-color:#000000;
		color:#3300CC; 
		cursor:hand;
		}
		div.donation {
		border-width: 1px;
		border-style: solid;
		padding: 0 0.6em;
		margin: 5px 0 15px;
		-moz-border-radius: 3px;
		-khtml-border-radius: 3px;
		-webkit-border-radius: 3px;
		border-radius: 3px;
		background-color: #ffffe0;
		border-color: #e6db55;
		text-align: center;
		}
		donation.p {	margin: 0.5em 0;
		line-height: 1;
		padding: 2px;
		}
	</style>
	<div class="wrap">
	<h2>Quiz Master Next <?php _e("Dashboard", "mlw_qmn_text_domain"); ?><a id="opener" href="">(?)</a></h2>
	
	<h3>Version <?php echo $mlw_quiz_version; ?></h3>
	<p><?php _e("Thank you for trying out this plugin. I hope you find it beneficial to your website. If it is, please consider donating Or, please consider rating this plugin ", "mlw_qmn_text_domain"); ?><a href="http://wordpress.org/support/view/plugin-reviews/quiz-master-next"><?php _e("here", "mlw_qmn_text_domain"); ?></a>.</p>
	
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
		<?php do_meta_boxes('quiz_wpss6','advanced',''); ?>	
	</div>
	
	<!--<div style="clear:both"></div>-->
	
	<div style="float:left; width:60%;" class="inner-sidebar1">
		<?php do_meta_boxes('quiz_wpss5','advanced','');  ?>	
	</div>
	
	<div style="float:right; width:36%; " class="inner-sidebar1">
		<?php do_meta_boxes('quiz_wpss7','advanced',''); ?>	
	</div>
	
	<!--<div style="clear:both"></div>-->
	
	<div style="float:left; width:60%;" class="inner-sidebar1">
		<?php do_meta_boxes('quiz_wpss9','advanced','');  ?>	
	</div>
	
	<div style="float:right; width:36%; " class="inner-sidebar1">
		<?php do_meta_boxes('quiz_wpss8','advanced',''); ?>	
	</div>
	
	<!--<div style="clear:both"></div>-->
	
	<div id="dialog" title="Help">
	<h3><b>Help</b></h3>
	<p>This page is the main admin dashboard for the Quiz Master Next. It contains many useful widgets for the admin.</p>
	<p>Quiz Daily Stats -> This widget shows the times all quizzes have been taken each day over the last week.</p>
	<p>Quiz Weekly Stats -> This widget shows the times all quizzes have been taken each week over the last few weeks.</p>
	<p>Quiz Monthly Stats -> This widget shows the times all quizzes have been taken each month over the last few months.</p>
	<p>Quiz Total Stats -> This widget shows several different stats that has been collected.</p>
	<p>In This Update -> This widget shows what is new in the most recent update of the plugin.</p>
	<p>Support -> This widget allows you to send a message to the developer of the plugin.</p>
	<p>News From My Local Webstop -> This widget allows you to keep up with the latest news from My Local Webstop, the developer behind Quiz Master Next.</p>
	<p>Contribution -> This widget allows you to make a contribution to the developer.</p>
	</div>

	</div>
	<?php
}

function mlw_dashboard_box()
{
	//Gather the weekly stats, one variable for each day for the graph
	global $wpdb;
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".date("Y-m-d")." 00:00:00' AND '".date("Y-m-d")." 23:59:59') AND deleted=0";
	$mlw_quiz_taken_today = $wpdb->get_results($sql);
	$mlw_quiz_taken_today = $wpdb->num_rows;
	
	$mlw_yesterday =  mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"));
	$mlw_yesterday = date("Y-m-d", $mlw_yesterday);
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_yesterday." 00:00:00' AND '".$mlw_yesterday." 23:59:59') AND deleted=0";
	$mlw_quiz_taken_yesterday = $wpdb->get_results($sql);
	$mlw_quiz_taken_yesterday = $wpdb->num_rows;
	
	$mlw_three_days_ago =  mktime(0, 0, 0, date("m")  , date("d")-2, date("Y"));
	$mlw_three_days_ago = date("Y-m-d", $mlw_three_days_ago);
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_three_days_ago." 00:00:00' AND '".$mlw_three_days_ago." 23:59:59') AND deleted=0";
	$mlw_quiz_taken_three_days = $wpdb->get_results($sql);
	$mlw_quiz_taken_three_days = $wpdb->num_rows;
	
	$mlw_four_days_ago =  mktime(0, 0, 0, date("m")  , date("d")-3, date("Y"));
	$mlw_four_days_ago = date("Y-m-d", $mlw_four_days_ago);
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_four_days_ago." 00:00:00' AND '".$mlw_four_days_ago." 23:59:59') AND deleted=0";
	$mlw_quiz_taken_four_days = $wpdb->get_results($sql);
	$mlw_quiz_taken_four_days = $wpdb->num_rows;
	
	$mlw_five_days_ago =  mktime(0, 0, 0, date("m")  , date("d")-4, date("Y"));
	$mlw_five_days_ago = date("Y-m-d", $mlw_five_days_ago);
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_five_days_ago." 00:00:00' AND '".$mlw_five_days_ago." 23:59:59') AND deleted=0";
	$mlw_quiz_taken_five_days = $wpdb->get_results($sql);
	$mlw_quiz_taken_five_days = $wpdb->num_rows;
	
	$mlw_six_days_ago =  mktime(0, 0, 0, date("m")  , date("d")-5, date("Y"));
	$mlw_six_days_ago = date("Y-m-d", $mlw_six_days_ago);
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_six_days_ago." 00:00:00' AND '".$mlw_six_days_ago." 23:59:59') AND deleted=0";
	$mlw_quiz_taken_six_days = $wpdb->get_results($sql);
	$mlw_quiz_taken_six_days = $wpdb->num_rows;
	
	$mlw_last_week =  mktime(0, 0, 0, date("m")  , date("d")-6, date("Y"));
	$mlw_last_week = date("Y-m-d", $mlw_last_week);
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_last_week." 00:00:00' AND '".$mlw_last_week." 23:59:59') AND deleted=0";
	$mlw_quiz_taken_week = $wpdb->get_results($sql);
	$mlw_quiz_taken_week = $wpdb->num_rows;
	?>
	<div>
	<span class="inlinesparkline"><?php echo $mlw_quiz_taken_week.",".$mlw_quiz_taken_six_days.",".$mlw_quiz_taken_five_days.",".$mlw_quiz_taken_four_days.",".$mlw_quiz_taken_three_days.",".$mlw_quiz_taken_yesterday.",".$mlw_quiz_taken_today; ?></span>
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
	
	$sql = "SELECT ROUND(AVG(quiz_views), 0) AS AvgViews FROM " . $wpdb->prefix . "mlw_quizzes";
	$mlw_average_views = $wpdb->get_results($sql);

	foreach($mlw_average_views as $mlw_eaches) {
		$mlw_average_views = $mlw_eaches->AvgViews;
		break;
	}
	
	$sql = "SELECT ROUND(AVG(quiz_taken), 0) AS AvgTaken FROM " . $wpdb->prefix . "mlw_quizzes";
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
	<td align='left'>Average Amount Each Quiz Has Been Viewed</td>
	<td align='right'><?php echo $mlw_average_views; ?></td>
	</tr>
	<tr>
	<td align='left'>Average Amount Each Quiz Has Been Taken</td>
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
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_this_week." 00:00:00' AND '".date("Y-m-d")." 23:59:59') AND deleted=0";
	$mlw_quiz_taken_this_week = $wpdb->get_results($sql);
	$mlw_quiz_taken_this_week = $wpdb->num_rows;
	
	$mlw_last_week_first =  mktime(0, 0, 0, date("m")  , date("d")-13, date("Y"));
	$mlw_last_week_first = date("Y-m-d", $mlw_last_week_first);
	$mlw_last_week_last =  mktime(0, 0, 0, date("m")  , date("d")-7, date("Y"));
	$mlw_last_week_last = date("Y-m-d", $mlw_last_week_last);
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_last_week_first." 00:00:00' AND '".$mlw_last_week_last." 23:59:59') AND deleted=0";
	$mlw_quiz_taken_last_week = $wpdb->get_results($sql);
	$mlw_quiz_taken_last_week = $wpdb->num_rows;
	
	$mlw_two_week_first =  mktime(0, 0, 0, date("m")  , date("d")-20, date("Y"));
	$mlw_two_week_first = date("Y-m-d", $mlw_two_week_first);
	$mlw_two_week_last =  mktime(0, 0, 0, date("m")  , date("d")-14, date("Y"));
	$mlw_two_week_last = date("Y-m-d", $mlw_two_week_last);
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_two_week_first." 00:00:00' AND '".$mlw_two_week_last." 23:59:59') AND deleted=0";
	$mlw_quiz_taken_two_week = $wpdb->get_results($sql);
	$mlw_quiz_taken_two_week = $wpdb->num_rows;
	
	$mlw_three_week_first =  mktime(0, 0, 0, date("m")  , date("d")-27, date("Y"));
	$mlw_three_week_first = date("Y-m-d", $mlw_three_week_first);
	$mlw_three_week_last =  mktime(0, 0, 0, date("m")  , date("d")-21, date("Y"));
	$mlw_three_week_last = date("Y-m-d", $mlw_three_week_last);
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_three_week_first." 00:00:00' AND '".$mlw_three_week_last." 23:59:59') AND deleted=0";
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
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_this_month." 00:00:00' AND '".date("Y-m-d")." 23:59:59') AND deleted=0";
	$mlw_quiz_taken_this_month = $wpdb->get_results($sql);
	$mlw_quiz_taken_this_month = $wpdb->num_rows;
	
	$mlw_last_month_first =  mktime(0, 0, 0, date("m")  , date("d")-60, date("Y"));
	$mlw_last_month_first = date("Y-m-d", $mlw_last_month_first);
	$mlw_last_month_last =  mktime(0, 0, 0, date("m")  , date("d")-31, date("Y"));
	$mlw_last_month_last = date("Y-m-d", $mlw_last_month_last);
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_last_month_first." 00:00:00' AND '".$mlw_last_month_last." 23:59:59') AND deleted=0";
	$mlw_quiz_taken_last_month = $wpdb->get_results($sql);
	$mlw_quiz_taken_last_month = $wpdb->num_rows;
	
	$mlw_two_month_first =  mktime(0, 0, 0, date("m")  , date("d")-60, date("Y"));
	$mlw_two_month_first = date("Y-m-d", $mlw_two_month_first);
	$mlw_two_month_last =  mktime(0, 0, 0, date("m")  , date("d")-31, date("Y"));
	$mlw_two_month_last = date("Y-m-d", $mlw_two_month_last);
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_two_month_first." 00:00:00' AND '".$mlw_two_month_last." 23:59:59') AND deleted=0";
	$mlw_quiz_taken_two_month = $wpdb->get_results($sql);
	$mlw_quiz_taken_two_month = $wpdb->num_rows;
	
	$mlw_three_month_first =  mktime(0, 0, 0, date("m")  , date("d")-90, date("Y"));
	$mlw_three_month_first = date("Y-m-d", $mlw_three_month_first);
	$mlw_three_month_last =  mktime(0, 0, 0, date("m")  , date("d")-61, date("Y"));
	$mlw_three_month_last = date("Y-m-d", $mlw_three_month_last);
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_three_month_first." 00:00:00' AND '".$mlw_three_month_last." 23:59:59') AND deleted=0";
	$mlw_quiz_taken_three_month = $wpdb->get_results($sql);
	$mlw_quiz_taken_three_month = $wpdb->num_rows;
	
	$mlw_four_month_first =  mktime(0, 0, 0, date("m")  , date("d")-120, date("Y"));
	$mlw_four_month_first = date("Y-m-d", $mlw_four_month_first);
	$mlw_four_month_last =  mktime(0, 0, 0, date("m")  , date("d")-91, date("Y"));
	$mlw_four_month_last = date("Y-m-d", $mlw_four_month_last);
	$sql = "SELECT quiz_name FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_four_month_first." 00:00:00' AND '".$mlw_four_month_last." 23:59:59') AND deleted=0";
	$mlw_quiz_taken_four_month = $wpdb->get_results($sql);
	$mlw_quiz_taken_four_month = $wpdb->num_rows;
	
	?>
	<div>
	<span class="inlinesparkline"><?php echo $mlw_quiz_taken_four_month.",".$mlw_quiz_taken_three_month.",".$mlw_quiz_taken_two_month.",".$mlw_quiz_taken_last_month.",".$mlw_quiz_taken_this_month; ?></span>
	</div>
	<?php
}

function mlw_dashboard_box_six()
{
	?>
	<div>
	<table width='100%'>
	<tr>
	<td align='left'>0.9.9 (January 30, 2014)</td>
	</tr>
	<tr>
		<td align='left'>* Begun Creating New Advanced Help System</td>
	</tr>
	<tr>
		<td align='left'>* Added New Question Editor</td>
	</tr>
	<tr>
		<td align='left'>* Added Ability To Have Media In Questions</td>
	</tr>
	<tr>
		<td align='left'>* Minor Design Changes To Quiz Options</td>
	</tr>
	</table>
	</div>
	<?php
}

function mlw_dashboard_box_seven()
{
	$quiz_master_email_message = "";
	$mlw_quiz_version = get_option('mlw_quiz_master_version');
	if(isset($_POST["action"]))
	{
		$quiz_master_email_success = $_POST["action"];
		$user_name = $_POST["username"];
		$user_email = $_POST["email"];
		$user_message = $_POST["message"];
		$user_quiz_url = $_POST["quiz_url"];
		$current_user = wp_get_current_user();
		$mlw_site_name = get_bloginfo('name');
		$mlw_site_url = get_bloginfo('url');
		$mlw_site_version = get_bloginfo('version');
		$mlw_site_info = $mlw_site_name." ".$mlw_site_url." ".$mlw_site_version;
		if ($quiz_master_email_success == 'update')
		{
			$mlw_message = "Message from ".$user_name." at ".$user_email." It says: \n \n ".$user_message."\n Version: ".$mlw_quiz_version."\n Quiz URL Provided: ".$user_quiz_url."\n User ".$current_user->display_name." from ".$current_user->user_email."\n Wordpress Info: ".$mlw_site_info;
			wp_mail('fpcorso@mylocalwebstop.com' ,'Support From Quiz Master Next Plugin', $mlw_message);
			$quiz_master_email_message = "**Message Sent**";
		}
	}
	?>
	<div class='quiz_email_support'>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=quiz-master-next/mlw_quizmaster2.php" method='post' name='emailForm' onsubmit='return mlw_validateForm()'>
	<input type='hidden' name='action' value='update' />
	<table>
	<tr>
	<td>If there is something you would like to suggest to add or even if you just want 
	to let me know if you like the plugin or not, feel free to use the email form below.</td>
	</tr>
	<tr>
	<td><span name='mlw_support_message' id='mlw_support_message' style="color: red;"><?php echo $quiz_master_email_message; ?></span></td>
	</tr>
	<tr>
	<td align='left'><span style='font-weight:bold;';>Name (Required): </span></td>
	</tr>
	<tr>
	<td><input type='text' name='username' value='' /></td>
	</tr>
	<tr>
	<td align='left'><span style='font-weight:bold;';>Email (Required): </span></td>
	</tr>
	<tr>
	<td><input type='text' name='email' value='' /></td>
	</tr>
	<tr>
	<td align='left'><span style='font-weight:bold;';>URL To Quiz (Not Required): </span></td>
	</tr>
	<tr>
	<td><input type='text' name='quiz_url' value='' /></td>
	</tr>
	<tr>
	<td align='left'><span style='font-weight:bold;';>Message (Required): </span></td>
	</tr>
	<tr>
	<td align='left'><TEXTAREA NAME="message" COLS=40 ROWS=6></TEXTAREA></td>
	</tr>
	<tr>
	<td align='left'><input type='submit' value='Send Email' /></td>
	</tr>
	<tr>
	<td align='left'></td>
	</tr>
	</table>
	</form>
	</div>
	<?php
}

function mlw_dashboard_box_eight()
{
	?>
	<div>
	<table width='100%'>
	<tr>
	<td align='left'>
	Quiz Master Next is and always will be a free plugin. I have spent a lot of time and effort developing and maintaining this plugin. If it has been beneficial to your site, please consider supporting this plugin by making a donation.
	</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td></td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td>
	<div class="donation">
	<p>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="RTGYAETX36ZQJ">
	<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>
	</p>
	</div>
	</td>
	</tr>
	</table>
	<p>Thank you to those who have contributed so far!</p>
	<h3>Supporters</h3>
	<ul>
		<li>Tracy B</li>
		<li>Bobby L</li>
		<li>Kristal P</li>
	</ul>
	</div>
	<?php
}
function mlw_dashboard_box_nine()
{
	?>
	<div>
	<table width='100%'>
	<tr>
	<td align='left'><iframe src="http://www.mylocalwebstop.com/mlw_news.html?cache=<?php echo rand(); ?>" seamless="seamless" style="width: 100%; height: 550px;"></iframe></td>
	</tr>
	</table>
	</div>
	<?php
}
?>