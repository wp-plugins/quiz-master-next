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
	<div class="wrap about-wrap">
	<h1>Welcome To Quiz Master Next <?php echo $mlw_quiz_version; ?><a id="opener" href="">(?)</a></h1>
	<div class="about-text">Thank you for updating!</div>
	<hr />
	<h3>What's New In <?php echo $mlw_quiz_version; ?></h3>
	<ul>
		<li>Added Ability To Show One Question At A Time Instead Of All At Once</li>
		<li>Added Ability To Allow Users To Share Results On Twitter</li>
		<li>Added Ability To Set Time Limits On Quiz</li>
		<li>Minor Design Changes To Quiz</li>
		<li>Minor Bug Fixes</li>
	</ul>
	<h3>What's Coming Soon</h3>
	<ul>
		<li>Importing Questions</li>
		<li>Stats For Each Quiz</li>
		<li>Multiple Right Answers</li>
		<li>Graded Text Answers</li>
		<li>Quiz Styling</li>
	</ul>
		
	<div id="dialog" title="Help">
	<h3><b>Help</b></h3>
	<p>This page contains the what's new section for the plugin!</p>
	</div>
	</div>	
<?php
}
?>