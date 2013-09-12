<?php
/*
Generates the support for Quiz Master Next
*/
/* 
Copyright 2013, My Local Webstop (email : fpcorso@mylocalwebstop.com)
*/


function mlw_generate_main_page()
{	
	add_meta_box("wpss_mrts", 'Quiz Master Next', "quiz_wpss_mrt_meta_box", "quiz_wpss");  
	add_meta_box("wpss_mrts", 'In This Update', "quiz_wpss_mrt_meta_box2", "quiz_wpss2"); 
	add_meta_box("wpss_mrts", 'Support', "quiz_wpss_mrt_meta_box3", "quiz_wpss3");
	add_meta_box("wpss_mrts", 'Contribution', "quiz_wpss_mrt_meta_box4", "quiz_wpss4");
	add_meta_box("wpss_mrts", 'News From My Local Webstop', "quiz_wpss_mrt_meta_box5", "quiz_wpss5");
	add_meta_box("wpss_mrts", 'Brainstorm Idea', "quiz_wpss_mrt_meta_box6", "quiz_wpss6");
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
		div.quiz_email_support {
		text-align: left;
		}
		div.quiz_email_support input[type='text'] {
		border-color:#000000;
		color:#3300CC; 
		cursor:hand;
		}
		textarea{
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
		p em {
		padding-left: 1em;
		color: #555;
		font-weight: bold;
		}
	</style>
	<div class="wrap">
	<h2>Quiz Master Next Support <a id="opener" href="">(?)</a></h2>
	
	<h3>Version 0.3</h3>
	<p>Thank you for trying out my new plugin. I hope you find it beneficial to your website.</p>
	
	<div style="float:left; width:60%;" class="inner-sidebar1">
		<?php do_meta_boxes('quiz_wpss3','advanced','');  ?>	
	</div>
	
	<div style="float:right; width:36%; " class="inner-sidebar1">
		<?php do_meta_boxes('quiz_wpss2','advanced',''); ?>	
	</div>
	
	<div style="float:right; width:36%; " class="inner-sidebar1">
		<?php do_meta_boxes('quiz_wpss5','advanced',''); ?>	
	</div>
			
	<!--<div style="clear:both"></div>-->
						
	<div style="float:left; width:60%; " class="inner-sidebar1">
		<?php do_meta_boxes('quiz_wpss4','advanced',''); ?>	
	</div>
	
	

	<div id="dialog" title="Help">
	<h3><b>Help</b></h3>
	<p>This page is the main admin page for the Quiz Master Next.</p>
	<p>The first widget lists all the statistics collected so far.</p>
	<p>The second widget lists all the new features added in this update.</p>
	<p>The third widget contains a contact form for emailing the developer.</p>
	<p>The fourth widget shows news from My Local Webstop regarding our plugins</p>
	<p>The last widget is our donation widget.</p>
	</div>

	</div>
	<?php
}

function quiz_wpss_mrt_meta_box()
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
	</table>
	</div>
	<?php
}

function quiz_wpss_mrt_meta_box2()
{
	?>
	<div>
	<table width='100%'>
	<tr>
	<td align='left'>0.3 (September 12, 2013)</td>
	</tr>
	<tr>
	<td align='left'>* Increased Scoring Systems To Three: Correct, Points, Or Not Graded</td>
	</tr>
	<tr>
	<td align='left'>* Error Fixes</td>
	</tr>
	</table>
	</div>
	<?php
}

function quiz_wpss_mrt_meta_box3()
{
	/*
	Copyright 2013, My Local Webstop
	Please do not re-use this email script without contacting me.
	*/
	$quiz_master_email_success = $_POST["action"];
	$user_name = $_POST["username"];
	$user_email = $_POST["email"];
	$user_message = $_POST["message"];
	$quiz_master_email_message = "";
	if ($quiz_master_email_success == update and $user_email != "" and $user_message != "")
	{
		wp_mail('fpcorso@mylocalwebstop.com' ,'Support From Quiz Master Next Plugin','Message from ' . $user_name . ' at ' . $user_email . " It says: " . "\n" . $user_message);
		$quiz_master_email_message = "<h3>Message Sent</h3>";
	}
	else if ($quiz_master_email_success == update and $user_email == "")
	{
		$quiz_master_email_message = "<h3>Please Enter Email</h3>";
	}
	else if ($quiz_master_email_success == update and $user_message == "")
	{
		$quiz_master_email_message = "<h3>Please Enter In Your Message</h3>";
	}
	?>
	<div class='quiz_email_support'>
	<form action="<?php echo $PHP_SELF; ?>" method='post'>
	<input type='hidden' name='action' value='update' />
	<table width='100%'>
	<tr>
	<td>If there is something you would like to suggest to add or even if you just want 
	to let me know if you like the plugin or not, feel free to use the email form below.</td>
	</tr>
	<tr>
	<td><?php echo $quiz_master_email_message; ?></td>
	</tr>
	<tr>
	<td align='left'><p>Name: <input type='text' name='username'/></p></td>
	</tr>
	<tr>
	<td align='left'><p>Email: <input type='text' name='email'/></p></td>
	</tr>
	<tr>
	<td align='left'><p>Message: </p></td>
	</tr>
	<tr>
	<td align='left'><p><TEXTAREA NAME="message" COLS=40 ROWS=6></TEXTAREA></p></td>
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

function quiz_wpss_mrt_meta_box4()
{
	?>
	<div>
	<table width='100%'>
	<tr>
	<td align='left'>
	I have spent a lot of time in development for this plugin. If you like it, please help by donating today.
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
	<input type="hidden" name="cmd" value="_donations">
	<input type="hidden" name="business" value="fpcorso@gmail.com">
	<input type="hidden" name="lc" value="US">
	<input type="hidden" name="no_note" value="0">
	<input type="hidden" name="currency_code" value="USD">
	<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
	<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>

	</p>
	</div>
	</td>
	</tr>
	</table>
	</div>
	<?php
}
function quiz_wpss_mrt_meta_box5()
{
	?>
	<div>
	<table width='100%'>
	<tr>
	<td align='left'><?php include("http://www.mylocalwebstop.com/mlw_news.html"); ?></td>
	</tr>
	</table>
	</div>
	<?php
}
function quiz_wpss_mrt_meta_box6()
{
	?>
	<div>
	<table width='100%'>
	<tr>
	<td align='left'>
	Box Six
	</td>
	</tr>
	</table>
	</div>
	<?php
}
?>