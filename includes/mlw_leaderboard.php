<?php
/*
This function creates the leaderboard from the shortcode.
*/
function mlw_quiz_leaderboard_shortcode($atts)
{
	extract(shortcode_atts(array(
		'mlw_quiz' => 0
	), $atts));
	$mlw_quiz_id = $mlw_quiz;
	$mlw_quiz_leaderboard_display = "";
	
	
	global $wpdb;
	$sql = "SELECT * FROM " . $wpdb->prefix . "mlw_quizzes" . " WHERE quiz_id=".$mlw_quiz_id." AND deleted='0'";
	$mlw_quiz_options = $wpdb->get_results($sql);
	foreach($mlw_quiz_options as $mlw_eaches) {
		$mlw_quiz_options = $mlw_eaches;
		break;
	}
	$sql = "SELECT * FROM " . $wpdb->prefix . "mlw_results WHERE quiz_id=".$mlw_quiz_id." AND deleted='0' LIMIT 10";
	$mlw_result_data = $wpdb->get_results($sql);
	
	$mlw_quiz_leaderboard_display .= "<h3>".$mlw_quiz_options->quiz_name."'s Leaderboard</h3>";
	$leader_count = 0;
	foreach($mlw_result_data as $mlw_eaches) {
		$leader_count++;
		if ($mlw_quiz_options->system == 0)
		{
			$mlw_quiz_leaderboard_display .= "".$leader_count.".&nbsp;".$mlw_eaches->name."&nbsp; - ".$mlw_eaches->correct_score."%";
		}
		if ($mlw_quiz_options->system == 1)
		{
			$mlw_quiz_leaderboard_display .= "".$leader_count.".&nbsp;".$mlw_eaches->name."&nbsp; - ".$mlw_eaches->point_score." Points";
		}
		$mlw_quiz_leaderboard_display .= "<br />";
	}
	$mlw_quiz_leaderboard_display .= "<br />";
	return $mlw_quiz_leaderboard_display;
}
?>