<?php
/*
This function creates a widget for the dashboard
*/
function mlw_quiz_dashboard()
{
	echo "You are currently looking at the new dashboard widget for the Quiz Master 2.0!";
}

function mlw_add_dashboard_widget()
{
	wp_add_dashboard_widget('custom_help_widget', 'Quiz Master 2.0', 'mlw_quiz_dashboard');
}
?>
