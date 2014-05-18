<?php
/*
This page shows the about page
*/
/* 
Copyright 2014, My Local Webstop (email : fpcorso@mylocalwebstop.com)
*/

function mlw_generate_about_page()
{
	//Page Variables
	$mlw_quiz_version = get_option('mlw_quiz_master_version');
	
	
	?>
	<!-- css -->
	<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/redmond/jquery-ui.css" rel="stylesheet" />
	<!-- jquery scripts -->
	<?php
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-dialog' );
	wp_enqueue_script( 'jquery-ui-button' );
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
	</script>
	<style>
		div.mlw_qmn_icon_wrap
		{
			background: <?php echo 'url("'.plugins_url( 'images/quiz_icon.png' , __FILE__ ).'")'; ?> no-repeat;
			background: none, <?php echo 'url("'.plugins_url( 'images/quiz_icon.png' , __FILE__ ).'")'; ?> no-repeat;
			position: absolute; 
			top: 0; 
			right: 0; 
			background-color: #0d97d8;
			color: yellow;
			background-position: center 24px;
			background-size: 85px 85px;
			font-size: 14px;
			text-align: center;
			font-weight: 600;
			margin: 5px 0 0;
			padding-top: 120px;
			height: 40px;
			display: inline-block;
			width: 150px;
			text-rendering: optimizeLegibility;
			border: 5px solid #106daa;
			-moz-border-radius: 20px;
			-webkit-border-radius: 20px;
			-khtml-border-radius: 20px;
			border-radius: 20px;
		}
	</style>
	<div class="wrap about-wrap">
	<h1>Welcome To Quiz Master Next <?php echo $mlw_quiz_version; ?><a id="opener" href="">(?)</a></h1>
	<div class="about-text">Thank you for updating!</div>
	<div class="mlw_qmn_icon_wrap">Version <?php echo $mlw_quiz_version; ?></div>
	<h2 class="nav-tab-wrapper">
		<a href="#" class="nav-tab nav-tab-active">
			What&#8217;s New In <?php echo $mlw_quiz_version; ?>	</a>
	</h2>
	<h2 style="margin: 1.1em 0 .2em;font-size: 2.4em;font-weight: 300;line-height: 1.3;text-align: center;">Set Number Of Questions Per Page</h2>
	<p>This update allows you to set the number of questions per page! Simply navigate to the Quiz Options tab and change the new "How many questions per page would you like?" option to the amount you would like. Set it to 0 to have all the quesitons on one page.</p>
	<br />
	<h2 style="margin: 1.1em 0 .2em;font-size: 2.4em;font-weight: 300;line-height: 1.3;text-align: center;">Our Premium Add-Ons!</h2>
	<p>We have several new premium add-ons in our WordPress Store: MailChimp Integration (subscribes users to your Mailchimp list), Export Results (exports your quiz results), Extra Shortcodes (gives you extra shortcodes to use), User Dashboard (allow users to see the results from all the quizzes they have taken), and Advertisement Be Gone (gets rid of blue-border ads). Visit our <a href="http://mylocalwebstop.com/shop/">WordPress Store</a> for details! </p>
	<br />
	<h3>Changelog For <?php echo $mlw_quiz_version; ?></h3>
	<ul>
		<li>Added Ability To Set Number Of Question Per Page</li>
		<li>Fixed HTML Not Showing On Results Page Bug</li>
		<li>Fixed Timer Not Disabling New Fields Bug</li>
		<li>Fixed Timer Submitting Disabled Fields Bug</li>
	</ul>
	<h3>What's Coming Soon</h3>
	<ul>
		<li>Importing Questions</li>
		<li>Stats For Each Quiz</li>
		<li>Force Login/Register Option</li>
		<li>Categories</li>
		<li>More Social Media Integration</li>
	</ul>
		
	<div id="dialog" title="Help">
	<h3><b>Help</b></h3>
	<p>This page contains the what's new section for the plugin!</p>
	</div>
	</div>	
<?php
}
?>