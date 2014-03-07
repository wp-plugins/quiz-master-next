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
		<li>Added Several New Widgets To Quiz Dashboard</li>
		<li>Added A Timer Mechanism To Track How Long User Takes On Quiz</li>
		<li>Added New About Update Page</li>
		<li>Fixed Saving Results Bug</li>
		<li>Fixed Random Number On Quiz Options Page Bug</li>
	</ul>
	<h3>What's Coming Soon</h3>
	<ul>
		<li>Time Limits</li>
		<li>Quiz Pagination</li>
		<li>Multiple Custom Landing Pages</li>
	</ul>
		
	<div id="dialog" title="Help">
	<h3><b>Help</b></h3>
	<p>This page contains the what's new section for the plugin!</p>
	</div>
	</div>	
<?php
}
?>