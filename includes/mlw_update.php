<?php

function mlw_quiz_update()
{
	$data = "0.9.2";
	if ( ! get_option('mlw_quiz_master_version'))
	{
		add_option('mlw_quiz_master_version' , $data);
	}
	elseif (get_option('mlw_quiz_master_version') != $data)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "mlw_quizzes";
		//Update 0.5
		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'comment_section'") != "comment_section")
		{
			$sql = "ALTER TABLE ".$table_name." ADD comment_field_text TEXT NOT NULL AFTER phone_field_text";
			$results = $wpdb->query( $sql );
			$sql = "ALTER TABLE ".$table_name." ADD comment_section INT NOT NULL AFTER admin_email";
			$results = $wpdb->query( $sql );
			$sql = "ALTER TABLE ".$table_name." ADD message_comment TEXT NOT NULL AFTER message_after";
			$results = $wpdb->query( $sql );
			$update_sql = "UPDATE ".$table_name." SET comment_field_text='Comments', comment_section=1, message_comment='Enter You Text Here'";
			$results = $wpdb->query( $update_sql );
		}
		
		//Update 0.9.2
		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'leaderboard_template'") != "leaderboard_template")
		{
			$sql = "ALTER TABLE ".$table_name." ADD leaderboard_template TEXT NOT NULL AFTER comment_field_text";
			$results = $wpdb->query( $sql );
			$mlw_leaderboard_default = "<h3>Leaderboard for %QUIZ_NAME%</h3>
			1. %FIRST_PLACE_NAME%-%FIRST_PLACE_SCORE%<br />
			2. %SECOND_PLACE_NAME%-%SECOND_PLACE_SCORE%<br />
			3. %THIRD_PLACE_NAME%-%THIRD_PLACE_SCORE%<br />
			4. %FOURTH_PLACE_NAME%-%FOURTH_PLACE_SCORE%<br />
			5. %FIFTH_PLACE_NAME%-%FIFTH_PLACE_SCORE%<br />";
			$update_sql = "UPDATE ".$table_name." SET leaderboard_template='".$mlw_leaderboard_default."'";
			$results = $wpdb->query( $update_sql );
		}
		
		
		global $wpdb;
		$table_name = $wpdb->prefix . "mlw_questions";
		//Update 0.5
		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'comments'") != "comments")
		{
			$sql = "ALTER TABLE ".$table_name." ADD comments INT NOT NULL AFTER correct_answer";
			$results = $wpdb->query( $sql );
			$sql = "ALTER TABLE ".$table_name." ADD hints TEXT NOT NULL AFTER comments";
			$results = $wpdb->query( $sql );
			$update_sql = "UPDATE ".$table_name." SET comments=1, hints=''";
			$results = $wpdb->query( $update_sql );
		}
		//Update 0.8	
		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'question_order'") != "question_order")
		{
			$sql = "ALTER TABLE ".$table_name." ADD question_order INT NOT NULL AFTER hints";
			$results = $wpdb->query( $sql );
			$update_sql = "UPDATE ".$table_name." SET question_order=0";
			$results = $wpdb->query( $update_sql );
		}
		
		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'question_type'") != "question_type")
		{
			$sql = "ALTER TABLE ".$table_name." ADD question_type INT NOT NULL AFTER question_order";
			$results = $wpdb->query( $sql );
			$update_sql = "UPDATE ".$table_name." SET question_type=0";
			$results = $wpdb->query( $update_sql );
		}
		update_option('mlw_quiz_master_version' , $data);
	}
}
?>