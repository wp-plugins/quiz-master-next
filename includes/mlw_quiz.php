<?php
/*
This function is the very heart of the plugin. This function displays the quiz to the user as well as handles all the scripts that are part of the quiz.  Please be very careful if you are editing this script without my assistance.
*/
function mlw_quiz_shortcode($atts)
{
	extract(shortcode_atts(array(
		'quiz' => 0
	), $atts));

	/*
	Code before loading the quiz
	*/

	//Variables needed throughout script
	$mlw_quiz_id = $quiz;
	$mlw_display = "";
	global $wpdb;


	//Load quiz
	$sql = "SELECT * FROM " . $wpdb->prefix . "mlw_quizzes" . " WHERE quiz_id=".$mlw_quiz_id." AND deleted='0'";
	$mlw_quiz_options = $wpdb->get_results($sql);

	foreach($mlw_quiz_options as $mlw_eaches) {
		$mlw_quiz_options = $mlw_eaches;
		break;
	}


	//Load questions
	$sql = "SELECT * FROM " . $wpdb->prefix . "mlw_questions" . " WHERE quiz_id=".$mlw_quiz_id." AND deleted='0'";
	$mlw_questions = $wpdb->get_results($sql);


	//Variables to load if quiz has been taken
	$mlw_success = $_POST["complete_quiz"];
	$mlw_user_name = $_POST["mlwUserName"];
	$mlw_user_comp = $_POST["mlwUserComp"];
	$mlw_user_email = $_POST["mlwUserEmail"];
	$mlw_user_phone = $_POST["mlwUserPhone"];

	/*
	The following code is for displaying the quiz and completion screen
	*/
	
	//If there is no quiz for the shortcode provided
	if ($mlw_quiz_options->quiz_name == "")
	{
		$mlw_display .= "It appears this quiz is not set up correctly.";
		return $mlw_display;
	}

	//Display Quiz
	if ($mlw_success != "confirmation" AND $mlw_quiz_options->quiz_name != "")
	{
		//Update the quiz views
		$mlw_views = $mlw_quiz_options->quiz_views;
		$mlw_views += 1;
		$update = "UPDATE " . $wpdb->prefix . "mlw_quizzes" . " SET quiz_views='".$mlw_views."' WHERE quiz_id=".$mlw_quiz_id;
		$results = $wpdb->query( $update );

		//Begin the quiz
		$mlw_message_before = $mlw_quiz_options->message_before;
		$mlw_message_before = str_replace( "%QUIZ_NAME%" , $mlw_quiz_options->quiz_name, $mlw_message_before);
		$mlw_display .= "<p>".$mlw_message_before."</p>";
		$mlw_display .= "<br />";
		$mlw_display .= "<form action='" . $PHP_SELF . "' method='post'>";
		$mlw_display .= "<table>";
		$mlw_display .= "<thead>";

		//See if the site wants to ask for anything, then ask for it
		if ($mlw_quiz_options->user_name != 2)
		{
			$mlw_display .= "<tr valign='top'>";
			$mlw_display .= "<th scope='row'>".$mlw_quiz_options->name_field_text."</th>";
			$mlw_display .= "<td><input type='text' name='mlwUserName' value='' /></td>";
			$mlw_display .= "</tr>";

		}
		if ($mlw_quiz_options->user_comp != 2)
		{
			$mlw_display .= "<tr valign='top'>";
			$mlw_display .= "<th scope='row'>".$mlw_quiz_options->business_field_text."</th>";
			$mlw_display .= "<td><input type='text' name='mlwUserComp' value='' /></td>";
			$mlw_display .= "</tr>";

		}
		if ($mlw_quiz_options->user_email != 2)
		{
			$mlw_display .= "<tr valign='top'>";
			$mlw_display .= "<th scope='row'>".$mlw_quiz_options->email_field_text."</th>";
			$mlw_display .= "<td><input type='text' name='mlwUserEmail' value='' /></td>";
			$mlw_display .= "</tr>";

		}
		if ($mlw_quiz_options->user_phone != 2)
		{
			$mlw_display .= "<tr valign='top'>";
			$mlw_display .= "<th scope='row'>".$mlw_quiz_options->phone_field_text."</th>";
			$mlw_display .= "<td><input type='text' name='mlwUserPhone' value='' /></td>";
			$mlw_display .= "</tr>";

		}
		$mlw_display .= "</thead>";
		$mlw_display .= "</table>";
		$mlw_display .= "<br />";
		
		//Display the questions
		foreach($mlw_questions as $mlw_question) {
			$mlw_display .= "<p>".$mlw_question->question_name."</p>";
			if ($mlw_question->answer_one != "")
			{
				$mlw_display .= "<input type='radio' name='question".$mlw_question->question_id."' value='1' />".$mlw_question->answer_one;
				$mlw_display .= "<br />";
			}
			if ($mlw_question->answer_two != "")
			{
				$mlw_display .= "<input type='radio' name='question".$mlw_question->question_id."' value='2' />".$mlw_question->answer_two;
				$mlw_display .= "<br />";
			}
			if ($mlw_question->answer_three != "")
			{
				$mlw_display .= "<input type='radio' name='question".$mlw_question->question_id."' value='3' />".$mlw_question->answer_three;
				$mlw_display .= "<br />";
			}
			if ($mlw_question->answer_four != "")
			{
				$mlw_display .= "<input type='radio' name='question".$mlw_question->question_id."' value='4' />".$mlw_question->answer_four;
				$mlw_display .= "<br />";
			}
			if ($mlw_question->answer_five != "")
			{
				$mlw_display .= "<input type='radio' name='question".$mlw_question->question_id."' value='5' />".$mlw_question->answer_five;
				$mlw_display .= "<br />";
			}
			if ($mlw_question->answer_six != "")
			{
				$mlw_display .= "<input type='radio' name='question".$mlw_question->question_id."' value='6' />".$mlw_question->answer_six;
				$mlw_display .= "<br />";
			}
			$mlw_display .= "<br />";
		}
		$mlw_display .= "<input type='hidden' name='complete_quiz' value='confirmation' />";
		$mlw_display .= "<input type='submit' value='".$mlw_quiz_options->submit_button_text."' />";
		$mlw_display .= "</form>";
		
	}
	//Display Completion Screen
	else
	{
		//Variables needed for scoring
		$mlw_points = 0;
		$mlw_correct = 0;
		$mlw_total_questions = 0;
		$mlw_total_score = 0;

		//Update the amount of times the quiz has been taken
		$mlw_taken = $mlw_quiz_options->quiz_taken;
		$mlw_taken += 1;
		$update = "UPDATE " . $wpdb->prefix . "mlw_quizzes" . " SET quiz_taken='".$mlw_taken."' WHERE quiz_id=".$mlw_quiz_id;
		$results = $wpdb->query( $update );

		//See which answers were correct and award points if necessary
		foreach($mlw_questions as $mlw_question) {
			$mlw_total_questions += 1;
			$mlw_user_answer = $_POST["question".$mlw_question->question_id];
			if ($mlw_user_answer == $mlw_question->correct_answer)
			{
				$mlw_correct += 1;
			}
			if ($mlw_user_answer == 1) {$mlw_points += $mlw_question->answer_one_points;}
			if ($mlw_user_answer == 2) {$mlw_points += $mlw_question->answer_two_points;}
			if ($mlw_user_answer == 3) {$mlw_points += $mlw_question->answer_three_points;}
			if ($mlw_user_answer == 4) {$mlw_points += $mlw_question->answer_four_points;}
			if ($mlw_user_answer == 5) {$mlw_points += $mlw_question->answer_five_points;}
			if ($mlw_user_answer == 6) {$mlw_points += $mlw_question->answer_six_points;}
		}
		$mlw_total_score = round((($mlw_correct/$mlw_total_questions)*100), 2);

		//Prepare the after quiz message
		$mlw_message_after = $mlw_quiz_options->message_after;
		$mlw_message_after = str_replace( "%POINT_SCORE%" , $mlw_points, $mlw_message_after);
		$mlw_message_after = str_replace( "%AMOUNT_CORRECT%" , $mlw_correct, $mlw_message_after);
		$mlw_message_after = str_replace( "%TOTAL_QUESTIONS%" , $mlw_total_questions, $mlw_message_after);
		$mlw_message_after = str_replace( "%CORRECT_SCORE%" , $mlw_total_score, $mlw_message_after);
		$mlw_message_after = str_replace( "%QUIZ_NAME%" , $mlw_quiz_options->quiz_name, $mlw_message_after);
		$mlw_message_after = str_replace( "%USER_NAME%" , $mlw_user_name, $mlw_message_after);
		$mlw_message_after = str_replace( "%USER_BUSINESS%" , $mlw_user_comp, $mlw_message_after);
		$mlw_message_after = str_replace( "%USER_PHONE%" , $mlw_user_phone, $mlw_message_after);
		$mlw_message_after = str_replace( "%USER_EMAIL%" , $mlw_user_email, $mlw_message_after);
		$mlw_display .= $mlw_message_after;



		//Prepare and send the user email
		$mlw_message = "";
		if ($mlw_quiz_options->send_user_email == "0")
		{
			if ($mlw_user_email != "")
			{
				$mlw_message = $mlw_quiz_options->user_email_template;
				$mlw_message = str_replace( "%POINT_SCORE%" , $mlw_points, $mlw_message);
				$mlw_message = str_replace( "%AMOUNT_CORRECT%" , $mlw_correct, $mlw_message);
				$mlw_message = str_replace( "%TOTAL_QUESTIONS%" , $mlw_total_questions, $mlw_message);
				$mlw_message = str_replace( "%CORRECT_SCORE%" , $mlw_total_score, $mlw_message);
				$mlw_message = str_replace( "%QUIZ_NAME%" , $mlw_quiz_options->quiz_name, $mlw_message);
				$mlw_message = str_replace( "%USER_NAME%" , $mlw_user_name, $mlw_message);
				$mlw_message = str_replace( "%USER_BUSINESS%" , $mlw_user_comp, $mlw_message);
				$mlw_message = str_replace( "%USER_PHONE%" , $mlw_user_phone, $mlw_message);
				$mlw_message = str_replace( "%USER_EMAIL%" , $mlw_user_email, $mlw_message);
				wp_mail($mlw_user_email, "Quiz Results", $mlw_message);
			}
		}

		//Prepare and send the admin email
		$mlw_message = "";
		if ($mlw_quiz_options->send_admin_email == "0")
		{
			$mlw_message = $mlw_quiz_options->admin_email_template;
			$mlw_message = str_replace( "%POINT_SCORE%" , $mlw_points, $mlw_message);
			$mlw_message = str_replace( "%AMOUNT_CORRECT%" , $mlw_correct, $mlw_message);
			$mlw_message = str_replace( "%TOTAL_QUESTIONS%" , $mlw_total_questions, $mlw_message);
			$mlw_message = str_replace( "%CORRECT_SCORE%" , $mlw_total_score, $mlw_message);
			$mlw_message = str_replace( "%USER_NAME%" , $mlw_user_name, $mlw_message);
			$mlw_message = str_replace( "%USER_BUSINESS%" , $mlw_user_comp, $mlw_message);
			$mlw_message = str_replace( "%USER_PHONE%" , $mlw_user_phone, $mlw_message);
			$mlw_message = str_replace( "%USER_EMAIL%" , $mlw_user_email, $mlw_message);
			$mlw_message = str_replace( "%QUIZ_NAME%" , $mlw_quiz_options->quiz_name, $mlw_message);
			$mlw_message .= " This email was generated by the Quiz Master 2.0 script by Frank Corso";
			wp_mail($mlw_quiz_options->admin_email, "Quiz Results", $mlw_message);
		}

		//Save the results into database
		global $wpdb;
		$table_name = $wpdb->prefix . "mlw_results";
		$insert = "INSERT INTO " . $table_name .
			"(result_id, quiz_id, quiz_name, quiz_system, point_score, correct_score, correct, total, name, business, email, phone, time_taken, deleted) " .
			"VALUES (NULL , " . $mlw_quiz_id . " , '".$mlw_quiz_options->quiz_name."', ".$mlw_quiz_options->system.", ".$mlw_points.", ".$mlw_total_score.", ".$mlw_correct.", ".$mlw_total_questions.", '".$mlw_user_name."', '".$mlw_user_comp."', '".$mlw_user_email."', '".$mlw_user_phone."', '".date("h:i:s A m/d/Y")."', 0)";
		$results = $wpdb->query( $insert );
	}
return $mlw_display;
}
?>