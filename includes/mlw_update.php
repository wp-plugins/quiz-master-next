<?php
/*
This is the update function for the plugin. When the plugin gets updated, the database changes are done here. This function is placed in the init of wordpress.
*/
function mlw_quiz_update()
{
	
	//Update this variable each update. This is what is checked when the plugin is deciding to run the upgrade script or not.
	$data = "1.7.1";
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
		
		//Update 0.9.4
		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'randomness_order'") != "randomness_order")
		{
			$sql = "ALTER TABLE ".$table_name." ADD randomness_order INT NOT NULL AFTER system";
			$results = $wpdb->query( $sql );
			$update_sql = "UPDATE ".$table_name." SET randomness_order=0";
			$results = $wpdb->query( $update_sql );
		}
		
		//Update 0.9.5
		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'question_answer_template'") != "question_answer_template")
		{
			$sql = "ALTER TABLE ".$table_name." ADD question_answer_template TEXT NOT NULL AFTER comment_field_text";
			$results = $wpdb->query( $sql );
			$mlw_question_answer_default = "%QUESTION%<br /> Answer Provided: %USER_ANSWER%<br /> Correct Answer: %CORRECT_ANSWER%<br /> Comments Entered: %USER_COMMENTS%<br />";
			$update_sql = "UPDATE ".$table_name." SET question_answer_template='".$mlw_question_answer_default."'";
			$results = $wpdb->query( $update_sql );
		}
		
		//Update 0.9.6
		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'contact_info_location'") != "contact_info_location")
		{
			$sql = "ALTER TABLE ".$table_name." ADD contact_info_location INT NOT NULL AFTER send_admin_email";
			$results = $wpdb->query( $sql );
			$update_sql = "UPDATE ".$table_name." SET contact_info_location=0";
			$results = $wpdb->query( $update_sql );
		}
		
		//Update 1.0
		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'email_from_text'") != "email_from_text")
		{
			$sql = "ALTER TABLE ".$table_name." ADD email_from_text TEXT NOT NULL AFTER comment_field_text";
			$results = $wpdb->query( $sql );
			$update_sql = "UPDATE ".$table_name." SET email_from_text='Wordpress'";
			$results = $wpdb->query( $update_sql );
		}
		
		//Update 1.3.1
		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'loggedin_user_contact'") != "loggedin_user_contact")
		{
			$sql = "ALTER TABLE ".$table_name." ADD loggedin_user_contact INT NOT NULL AFTER randomness_order";
			$results = $wpdb->query( $sql );
			$update_sql = "UPDATE ".$table_name." SET loggedin_user_contact=0";
			$results = $wpdb->query( $update_sql );
		}
		
		//Update 1.5.1
		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'question_from_total'") != "question_from_total")
		{
			$sql = "ALTER TABLE ".$table_name." ADD question_from_total INT NOT NULL AFTER comment_section";
			$results = $wpdb->query( $sql );
			$update_sql = "UPDATE ".$table_name." SET question_from_total=0";
			$results = $wpdb->query( $update_sql );
		}
		
		//Update 1.6.1
		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'total_user_tries'") != "total_user_tries")
		{
			$sql = "ALTER TABLE ".$table_name." ADD total_user_tries INT NOT NULL AFTER question_from_total";
			$results = $wpdb->query( $sql );
			$update_sql = "UPDATE ".$table_name." SET total_user_tries=0";
			$results = $wpdb->query( $update_sql );
		}
		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'total_user_tries_text'") != "total_user_tries_text")
		{
			$sql = "ALTER TABLE ".$table_name." ADD total_user_tries_text TEXT NOT NULL AFTER total_user_tries";
			$results = $wpdb->query( $sql );
			$update_sql = "UPDATE ".$table_name." SET total_user_tries_text='Enter Your Text Here'";
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
		
		//Update 1.1.1
		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'question_answer_info'") != "question_answer_info")
		{
			$sql = "ALTER TABLE ".$table_name." ADD question_answer_info TEXT NOT NULL AFTER correct_answer";
			$results = $wpdb->query( $sql );
			$update_sql = "UPDATE ".$table_name." SET question_answer_info=''";
			$results = $wpdb->query( $update_sql );
		}
		
		update_option('mlw_quiz_master_version' , $data);
		if(!isset($_GET['activate-multi']))
        {
			wp_redirect( "admin.php?page=mlw_qmn_about" );
			exit;
        }
	}
}
?>