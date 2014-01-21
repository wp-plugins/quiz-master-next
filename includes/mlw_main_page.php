<?php
/*
Generates the support for Quiz Master Next
*/
/* 
Copyright 2014, My Local Webstop (email : fpcorso@mylocalwebstop.com)
*/


function mlw_generate_main_page()
{	
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
	<h2>Quiz Master Next Help And Support <a id="opener" href="">(?)</a></h2>
	
	<h3>Version <?php echo $mlw_quiz_version; ?></h3>
	<p>Thank you for trying out my new plugin. I hope you find it beneficial to your website. Please consider rating this plugin <a href="http://wordpress.org/support/view/plugin-reviews/quiz-master-next">here</a>.</p>
	
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
	<p>This page is the main support page for the Quiz Master Next.</p>
	<p>The first widget lists all the statistics collected so far.</p>
	<p>The second widget lists all the new features added in this update.</p>
	<p>The third widget contains a contact form for emailing the developer.</p>
	<p>The fourth widget shows news from My Local Webstop regarding our plugins</p>
	<p>The last widget is our donation widget.</p>
	</div>

	</div>
	<?php
}

function quiz_wpss_mrt_meta_box2()
{
	?>
	<div>
	<table width='100%'>
	<tr>
	<td align='left'>0.9.6 (January 21, 2014)</td>
	</tr>
	<tr>
		<td align='left'>* Added Ability To Edit Question Order When Adding Questions</td>
	</tr>
	<tr>
		<td align='left'>* Added Ability To Choose Whether Contact Info Is Asked For At Beginning Or End Of Quiz</td>
	</tr>
	<tr>
		<td align='left'>* Defaulted Question Comment Field To None On New Questions</td>
	</tr>
	<tr>
		<td align='left'>* Fixed Double Quote Bug</td>
	</tr>
	<tr>
		<td align='left'>* Enhanced Monthly Stat Widget</td>
	</tr>
	<tr>
		<td align='left'>* Minor Design Change To Quiz Total Stats Widget</td>
	</tr>
	<tr>
		<td align='left'>* Minor Design Changes To Quiz Options Page</td>
	</tr>
	</table>
	</div>
	<?php
}

function quiz_wpss_mrt_meta_box3()
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
		if ($quiz_master_email_success == 'update')
		{
			$mlw_message = "Message from ".$user_name." at ".$user_email." It says: \n \n ".$user_message."\n Version: ".$mlw_quiz_version."\n Quiz URL Provided: ".$user_quiz_url."\n User ".$current_user->display_name." from ".$current_user->user_email;
			wp_mail('fpcorso@mylocalwebstop.com' ,'Support From Quiz Master Next Plugin', $mlw_message);
			$quiz_master_email_message = "**Message Sent**";
		}
	}
	?>
	<div class='quiz_email_support'>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=mlw_quiz_support" method='post' name='emailForm' onsubmit='return mlw_validateForm()'>
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
	<p>Thank you to those who have contributed so far.</p>
	<h3>Supporters</h3>
	<ul>
		<li>Tracy B</li>
		<li>Bobby L</li>
	</ul>
	</div>
	<?php
}
function quiz_wpss_mrt_meta_box5()
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