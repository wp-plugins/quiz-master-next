<?php
/*
This page shows the user how-to's for using the plugin
*/
/* 
Copyright 2013, My Local Webstop (email : fpcorso@mylocalwebstop.com)
*/

function mlw_generate_help_page()
{
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
		$j(function() {
    		$j( "#tabs" ).tabs();
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
	<h2>How-To<a id="opener" href="">(?)</a></h2>
	<div id="tabs">
		<ul>
		    <li><a href="#tabs-1">How To Create A Quiz</a></li>
		    <li><a href="#tabs-2">How To Add A Question To Your Quiz</a></li>
		</ul>
  		<div id="tabs-1">
  		In order to create a quiz, test, or survey you must first click on the Quizzes link from the side menu. Once you are on the page, you will see a Create New Quiz button. Click on this button.
  		Doing so will open a pop-up that will ask for the name of the quiz you would like to create. Once you entered the name in, click on the button that says Create Quiz. You should your
  		new quiz added to the table.
  		</div>
  		<div id="tabs-2">
  		In order to add a question, you must first click on the Quizzes link from the side menu. Once you are on the page, click the edit link on the quiz you wish to add a question to. Once you are on the
  		Quiz Options page, navigate to the Quiz Questions tab. On the Quiz Questions tab, click on the button that says Add Question. Doing so will open a pop-up that will ask for the question, answers, hint, 
  		correct answer, points, and whether you want a comment box. Once you fill in all the necessary information, click the button that says Create Question. Your question will be added to the list of questions 
  		on the Quiz Questions tab.
  		</div>
  	</div>
	<div id="dialog" title="Help">
	<h3><b>Help</b></h3>
	<p>This page contains numerous how-to's for using the plugin.</p>
	</div>	
	</div>
	</div>	
<?php
}
?>