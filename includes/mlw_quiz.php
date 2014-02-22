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
	$sql = "SELECT * FROM " . $wpdb->prefix . "mlw_questions" . " WHERE quiz_id=".$mlw_quiz_id." AND deleted='0' "; 
	if ($mlw_quiz_options->randomness_order == 0)
	{
		$sql .= "ORDER BY question_order ASC";
	}
	if ($mlw_quiz_options->randomness_order == 1)
	{
		$sql .= "ORDER BY rand()";
	}
	$mlw_questions = $wpdb->get_results($sql);


	//Variables to load if quiz has been taken
	$mlw_success = $_POST["complete_quiz"];
	$mlw_user_name = $_POST["mlwUserName"];
	$mlw_user_comp = $_POST["mlwUserComp"];
	$mlw_user_email = $_POST["mlwUserEmail"];
	$mlw_user_phone = $_POST["mlwUserPhone"];
	$mlw_spam_email = $_POST["email"];
	
	function mlwDisplayContactInfo($mlw_quiz_options)
	{
		$mlw_contact_display = "";
		//Check to see if user is logged in, then ask for contact if not
		if ( is_user_logged_in() )
		{
			//If this quiz does not let user edit contact information we hide this section
			if ($mlw_quiz_options->loggedin_user_contact == 1)
			{
				$mlw_contact_display .= "<div style='display:none;'>";
			}
			
			//Retrieve current user information and save into text fields for contact information
			$current_user = wp_get_current_user();
			if ($mlw_quiz_options->user_name != 2)
			{
				$mlw_contact_display .= "<span style='font-weight:bold;';>".$mlw_quiz_options->name_field_text."</span><br />";
				$mlw_contact_display .= "<input type='text' name='mlwUserName' value='".$current_user->display_name."' />";
				$mlw_contact_display .= "<br /><br />";
	
			}
			if ($mlw_quiz_options->user_comp != 2)
			{
				$mlw_contact_display .= "<span style='font-weight:bold;';>".$mlw_quiz_options->business_field_text."</span><br />";
				$mlw_contact_display .= "<input type='text' name='mlwUserComp' value='' />";
				$mlw_contact_display .= "<br /><br />";
	
			}
			if ($mlw_quiz_options->user_email != 2)
			{
				$mlw_contact_display .= "<span style='font-weight:bold;';>".$mlw_quiz_options->email_field_text."</span><br />";
				$mlw_contact_display .= "<input type='text' name='mlwUserEmail' value='".$current_user->user_email."' />";
				$mlw_contact_display .= "<br /><br />";
	
			}
			if ($mlw_quiz_options->user_phone != 2)
			{
				$mlw_contact_display .= "<span style='font-weight:bold;';>".$mlw_quiz_options->phone_field_text."</span><br />";
				$mlw_contact_display .= "<input type='text' name='mlwUserPhone' value='' />";
				$mlw_contact_display .= "<br /><br />";
	
			}
			
			//End of hidden section div
			if ($mlw_quiz_options->loggedin_user_contact == 1)
			{
				$mlw_contact_display .= "</div>";
			}
		}
		else
		{
			//See if the site wants to ask for any contact information, then ask for it
			if ($mlw_quiz_options->user_name != 2)
			{
				$mlw_contact_display .= "<span style='font-weight:bold;';>".$mlw_quiz_options->name_field_text."</span><br />";
				$mlw_contact_display .= "<input type='text' name='mlwUserName' value='' />";
				$mlw_contact_display .= "<br /><br />";
	
			}
			if ($mlw_quiz_options->user_comp != 2)
			{
				$mlw_contact_display .= "<span style='font-weight:bold;';>".$mlw_quiz_options->business_field_text."</span><br />";
				$mlw_contact_display .= "<input type='text' name='mlwUserComp' value='' />";
				$mlw_contact_display .= "<br /><br />";
	
			}
			if ($mlw_quiz_options->user_email != 2)
			{
				$mlw_contact_display .= "<span style='font-weight:bold;';>".$mlw_quiz_options->email_field_text."</span><br />";
				$mlw_contact_display .= "<input type='text' name='mlwUserEmail' value='' />";
				$mlw_contact_display .= "<br /><br />";
	
			}
			if ($mlw_quiz_options->user_phone != 2)
			{
				$mlw_contact_display .= "<span style='font-weight:bold;';>".$mlw_quiz_options->phone_field_text."</span><br />";
				$mlw_contact_display .= "<input type='text' name='mlwUserPhone' value='' />";
				$mlw_contact_display .= "<br /><br />";
	
			}
		}
		return $mlw_contact_display;
	}
	
	
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-dialog' );
	wp_enqueue_script( 'jquery-ui-button' );
	wp_enqueue_script( 'jquery-ui-accordion' );
	wp_enqueue_script( 'jquery-ui-tooltip' );
	wp_enqueue_script( 'jquery-ui-tabs' );
?>
<!-- css -->
	<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/redmond/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript">
		var $j = jQuery.noConflict();
		// increase the default animation speed to exaggerate the effect
		$j.fx.speeds._default = 1000;
		$j(function() {
   			 $j( document ).tooltip();
 		});
 	</script>
 	<style type="text/css">
 		.ui-tooltip
		{
		    /* tooltip container box */
		    max-width: 500px !important;
		}
		.ui-tooltip-content
		{
		    /* tooltip content */
		    max-width: 500px !important;
		}
 	</style>
 	<?php

	/*
	The following code is for displaying the quiz and completion screen
	*/
	
	//If there is no quiz for the shortcode provided
	if ($mlw_quiz_options->quiz_name == "")
	{
		$mlw_display .= "It appears that this quiz is not set up correctly.";
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
		
		//Form validation script
		$mlw_display .= "
		<script>
			function clear_field(field)
			{
				if (field.defaultValue == field.value) field.value = '';
			}
			
			function mlw_validateForm()
			{
		";
		if ($mlw_quiz_options->user_name == 1)
		{
		$mlw_display .= "
				var x=document.forms['quizForm']['mlwUserName'].value;
				if (x==null || x=='')
				  {
				  	  document.getElementById('mlw_error_message').innerHTML = '**Name must be filled out!**';
				  	  document.getElementById('mlw_error_message_bottom').innerHTML = '**Name must be filled out!**';
					  return false;
				  }";
		}
		if ($mlw_quiz_options->user_comp == 1)
		{
		$mlw_display .= "
				var x=document.forms['quizForm']['mlwUserComp'].value;
				if (x==null || x=='')
				  {
				  	document.getElementById('mlw_error_message').innerHTML = '**Business must be filled out!**';
				  	document.getElementById('mlw_error_message_bottom').innerHTML = '**Business must be filled out!**';
					  return false;
				  }";
		}
		if ($mlw_quiz_options->user_email == 1)
		{
		$mlw_display .= "
				var x=document.forms['quizForm']['mlwUserEmail'].value;
				if (x==null || x=='')
				  {
				  	document.getElementById('mlw_error_message').innerHTML = '**Email must be filled out!**';
				  	document.getElementById('mlw_error_message_bottom').innerHTML = '**Email must be filled out!**';
					  return false;
				  }";
		}
		if ($mlw_quiz_options->user_phone == 1)
		{
		$mlw_display .= "
				var x=document.forms['quizForm']['mlwUserPhone'].value;
				if (x==null || x=='')
				  {
				  	document.getElementById('mlw_error_message').innerHTML = '**Phone number must be filled out!**';
				  	document.getElementById('mlw_error_message_bottom').innerHTML = '**Phone number must be filled out!**';
					  return false;
				  }";
		}
		$mlw_display .= "
				if (document.forms['quizForm']['mlwUserEmail'].defaultValue != document.forms['quizForm']['mlwUserEmail'].value)
				{
					var x=document.forms['quizForm']['mlwUserEmail'].value;
					var atpos=x.indexOf('@');
					var dotpos=x.lastIndexOf('.');
					if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length)
					  {
					  	document.getElementById('mlw_error_message').innerHTML = '**Not a valid e-mail address!**';
					  	document.getElementById('mlw_error_message_bottom').innerHTML = '**Not a valid e-mail address!**';
					  return false;
					  }
				}
			}		
		</script>";
		
		
		//Begin the quiz
		$mlw_message_before = $mlw_quiz_options->message_before;
		$mlw_message_before = str_replace( "%QUIZ_NAME%" , $mlw_quiz_options->quiz_name, $mlw_message_before);
		$mlw_display .= "<span>".$mlw_message_before."</span><br />";
		$mlw_display .= "<span name='mlw_error_message' id='mlw_error_message' style='color: red;'></span><br />";
		$mlw_display .= "<form name='quizForm' action='" . $PHP_SELF . "' method='post' onsubmit='return mlw_validateForm()' >";

		if ($mlw_quiz_options->contact_info_location == 0)
		{
			$mlw_display .= mlwDisplayContactInfo($mlw_quiz_options);
		}
		
		//Display the questions
		foreach($mlw_questions as $mlw_question) {
			$mlw_display .= "<span style='font-weight:bold;'>".htmlspecialchars_decode($mlw_question->question_name, ENT_QUOTES)."</span><br />";
			if ($mlw_question->question_type == 0)
			{
				if ($mlw_question->answer_one != "")
				{
					$mlw_display .= "<input type='radio' name='question".$mlw_question->question_id."' id='question".$mlw_question->question_id."_one' value='1' /> <label for='question".$mlw_question->question_id."_one'>".htmlspecialchars_decode($mlw_question->answer_one, ENT_QUOTES)."</label>";
					$mlw_display .= "<br />";
				}
				if ($mlw_question->answer_two != "")
				{
					$mlw_display .= "<input type='radio' name='question".$mlw_question->question_id."' id='question".$mlw_question->question_id."_two' value='2' /> <label for='question".$mlw_question->question_id."_two'>".htmlspecialchars_decode($mlw_question->answer_two, ENT_QUOTES)."</label>";
					$mlw_display .= "<br />";
				}
				if ($mlw_question->answer_three != "")
				{
					$mlw_display .= "<input type='radio' name='question".$mlw_question->question_id."' id='question".$mlw_question->question_id."_three' value='3' /> <label for='question".$mlw_question->question_id."_three'>".htmlspecialchars_decode($mlw_question->answer_three, ENT_QUOTES)."</label>";
					$mlw_display .= "<br />";
				}
				if ($mlw_question->answer_four != "")
				{
					$mlw_display .= "<input type='radio' name='question".$mlw_question->question_id."' id='question".$mlw_question->question_id."_four' value='4' /> <label for='question".$mlw_question->question_id."_four'>".htmlspecialchars_decode($mlw_question->answer_four, ENT_QUOTES)."</label>";
					$mlw_display .= "<br />";
				}
				if ($mlw_question->answer_five != "")
				{
					$mlw_display .= "<input type='radio' name='question".$mlw_question->question_id."' id='question".$mlw_question->question_id."_five' value='5' /> <label for='question".$mlw_question->question_id."_five'>".htmlspecialchars_decode($mlw_question->answer_five, ENT_QUOTES)."</label>";
					$mlw_display .= "<br />";
				}
				if ($mlw_question->answer_six != "")
				{
					$mlw_display .= "<input type='radio' name='question".$mlw_question->question_id."' id='question".$mlw_question->question_id."_six' value='6' /> <label for='question".$mlw_question->question_id."_six'>".htmlspecialchars_decode($mlw_question->answer_six, ENT_QUOTES)."</label>";
					$mlw_display .= "<br />";
				}
			}
			elseif ($mlw_question->question_type == 1)
			{
				if ($mlw_question->answer_one != "")
				{
					$mlw_display .= "<input type='radio' name='question".$mlw_question->question_id."' value='1' />".htmlspecialchars_decode($mlw_question->answer_one, ENT_QUOTES);
				}
				if ($mlw_question->answer_two != "")
				{
					$mlw_display .= "<input type='radio' name='question".$mlw_question->question_id."' value='2' />".htmlspecialchars_decode($mlw_question->answer_two, ENT_QUOTES);
				}
				if ($mlw_question->answer_three != "")
				{
					$mlw_display .= "<input type='radio' name='question".$mlw_question->question_id."' value='3' />".htmlspecialchars_decode($mlw_question->answer_three, ENT_QUOTES);
				}
				if ($mlw_question->answer_four != "")
				{
					$mlw_display .= "<input type='radio' name='question".$mlw_question->question_id."' value='4' />".htmlspecialchars_decode($mlw_question->answer_four, ENT_QUOTES);
				}
				if ($mlw_question->answer_five != "")
				{
					$mlw_display .= "<input type='radio' name='question".$mlw_question->question_id."' value='5' />".htmlspecialchars_decode($mlw_question->answer_five, ENT_QUOTES);
				}
				if ($mlw_question->answer_six != "")
				{
					$mlw_display .= "<input type='radio' name='question".$mlw_question->question_id."' value='6' />".htmlspecialchars_decode($mlw_question->answer_six, ENT_QUOTES);
				}
				$mlw_display .= "<br />";
			}
			else
			{
				$mlw_display .= "<select name='question".$mlw_question->question_id."'>";
				if ($mlw_question->answer_one != "")
				{
					$mlw_display .= "<option value='1'>".htmlspecialchars_decode($mlw_question->answer_one, ENT_QUOTES)."</option>";
				}
				if ($mlw_question->answer_two != "")
				{
					$mlw_display .= "<option value='2'>".htmlspecialchars_decode($mlw_question->answer_two, ENT_QUOTES)."</option>";
				}
				if ($mlw_question->answer_three != "")
				{
					$mlw_display .= "<option value='3'>".htmlspecialchars_decode($mlw_question->answer_three, ENT_QUOTES)."</option>";
				}
				if ($mlw_question->answer_four != "")
				{
					$mlw_display .= "<option value='4'>".htmlspecialchars_decode($mlw_question->answer_four, ENT_QUOTES)."</option>";
				}
				if ($mlw_question->answer_five != "")
				{
					$mlw_display .= "<option value='5'>".htmlspecialchars_decode($mlw_question->answer_five, ENT_QUOTES)."</option>";
				}
				if ($mlw_question->answer_six != "")
				{
					$mlw_display .= "<option value='6'>".htmlspecialchars_decode($mlw_question->answer_six, ENT_QUOTES)."</option>";
				}						
				$mlw_display .= "</select>";
				$mlw_display .= "<br />";
			}
			if ($mlw_question->comments == 0)
			{
				$mlw_display .= "<input type='text' id='mlwComment".$mlw_question->question_id."' name='mlwComment".$mlw_question->question_id."' value='".$mlw_quiz_options->comment_field_text."' onclick='clear_field(this)'/>";
				$mlw_display .= "<br />";
			}
			if ($mlw_question->comments == 2)
			{
				$mlw_display .= "<textarea cols='70' rows='5' id='mlwComment".$mlw_question->question_id."' name='mlwComment".$mlw_question->question_id."' onclick='clear_field(this)'>".$mlw_quiz_options->comment_field_text."</textarea>";
				$mlw_display .= "<br />";
			}
			if ($mlw_question->hints != "")
			{
				$mlw_display .= "<span title=\"".htmlspecialchars_decode($mlw_question->hints, ENT_QUOTES)."\" style=\"text-decoration:underline;color:rgb(0,0,255);\">Hint</span>";
				$mlw_display .= "<br /><br />";
			}
			$mlw_display .= "<br />";
		}
		
		//Display comment box if needed
		if ($mlw_quiz_options->comment_section == 0)
		{
			$mlw_message_comments = $mlw_quiz_options->message_comment;
			$mlw_message_comments = str_replace( "%QUIZ_NAME%" , $mlw_quiz_options->quiz_name, $mlw_message_comments);
			$mlw_display .= "<label for='mlwQuizComments'>".$mlw_message_comments."</label>";
			$mlw_display .= "<textarea cols='70' rows='15' id='mlwQuizComments' name='mlwQuizComments' ></textarea>";
			$mlw_display .= "<br /><br />";
		}
		if ($mlw_quiz_options->contact_info_location == 1)
		{
			$mlw_display .= mlwDisplayContactInfo($mlw_quiz_options);
		}
		$mlw_display .= "<span style='display: none;'>If you are human, leave this field blank or you will be considered spam:</span>";
		$mlw_display .= "<input style='display: none;' type='text' name='email' id='email' />";
		$mlw_display .= "<input type='hidden' name='complete_quiz' value='confirmation' />";
		$mlw_display .= "<input type='submit' value='".$mlw_quiz_options->submit_button_text."' />";
		$mlw_display .= "<span name='mlw_error_message_bottom' id='mlw_error_message_bottom' style='color: red;'></span><br />";
		$mlw_display .= "</form>";
		
	}
	//Display Completion Screen
	else
	{
		if (empty($mlw_spam_email))
		{
		//Variables needed for scoring
		$mlw_points = 0;
		$mlw_correct = 0;
		$mlw_total_questions = 0;
		$mlw_total_score = 0;
		$mlw_question_answers = "";

		//Update the amount of times the quiz has been taken
		$mlw_taken = $mlw_quiz_options->quiz_taken;
		$mlw_taken += 1;
		$update = "UPDATE " . $wpdb->prefix . "mlw_quizzes" . " SET quiz_taken='".$mlw_taken."' WHERE quiz_id=".$mlw_quiz_id;
		$results = $wpdb->query( $update );

		//See which answers were correct and award points if necessary
		foreach($mlw_questions as $mlw_question) {
			$mlw_user_text;
			$mlw_correct_text;
			$mlw_total_questions += 1;
			$mlw_user_answer = $_POST["question".$mlw_question->question_id];
			if ($mlw_user_answer == $mlw_question->correct_answer)
			{
				$mlw_correct += 1;
			}
			if ($mlw_user_answer == 1) 
			{
				$mlw_points += $mlw_question->answer_one_points;
				$mlw_user_text = $mlw_question->answer_one;
			}
			if ($mlw_user_answer == 2) 
			{
				$mlw_points += $mlw_question->answer_two_points;
				$mlw_user_text = $mlw_question->answer_two;
			}
			if ($mlw_user_answer == 3) 
			{
				$mlw_points += $mlw_question->answer_three_points;
				$mlw_user_text = $mlw_question->answer_three;
			}
			if ($mlw_user_answer == 4) 
			{
				$mlw_points += $mlw_question->answer_four_points;
				$mlw_user_text = $mlw_question->answer_four;
			}
			if ($mlw_user_answer == 5) 
			{
				$mlw_points += $mlw_question->answer_five_points;
				$mlw_user_text = $mlw_question->answer_five;
			}
			if ($mlw_user_answer == 6) 
			{
				$mlw_points += $mlw_question->answer_six_points;
				$mlw_user_text = $mlw_question->answer_six;
			}
			
			if ($mlw_question->correct_answer == 1) {$mlw_correct_text = $mlw_question->answer_one;}
			if ($mlw_question->correct_answer == 2) {$mlw_correct_text = $mlw_question->answer_two;}
			if ($mlw_question->correct_answer == 3) {$mlw_correct_text = $mlw_question->answer_three;}
			if ($mlw_question->correct_answer == 4) {$mlw_correct_text = $mlw_question->answer_four;}
			if ($mlw_question->correct_answer == 5) {$mlw_correct_text = $mlw_question->answer_five;}
			if ($mlw_question->correct_answer == 6) {$mlw_correct_text = $mlw_question->answer_six;}
			
			$mlw_question_answer_display = $mlw_quiz_options->question_answer_template;
			$mlw_question_answer_display = str_replace( "%QUESTION%" , htmlspecialchars_decode($mlw_question->question_name, ENT_QUOTES), $mlw_question_answer_display);
			$mlw_question_answer_display = str_replace( "%USER_ANSWER%" , $mlw_user_text, $mlw_question_answer_display);
			$mlw_question_answer_display = str_replace( "%CORRECT_ANSWER%" , $mlw_correct_text, $mlw_question_answer_display);
			$mlw_question_answer_display = str_replace( "%USER_COMMENTS%" , $_POST["mlwComment".$mlw_question->question_id], $mlw_question_answer_display);
			$mlw_question_answer_display = str_replace( "%CORRECT_ANSWER_INFO%" , $mlw_question->question_answer_info, $mlw_question_answer_display);

			$mlw_question_answers .= $mlw_question_answer_display;
			$mlw_question_answers .= "<br />";
		}
		$mlw_total_score = round((($mlw_correct/$mlw_total_questions)*100), 2);
		
		//Prepare the after quiz message
		$mlw_message_after = $mlw_quiz_options->message_after;
		$mlw_message_after = str_replace( "%POINT_SCORE%" , $mlw_points, $mlw_message_after);
		$mlw_message_after = str_replace( "%AVERAGE_POINT%" , round(($mlw_points/$mlw_total_questions), 2), $mlw_message_after);
		$mlw_message_after = str_replace( "%AMOUNT_CORRECT%" , $mlw_correct, $mlw_message_after);
		$mlw_message_after = str_replace( "%TOTAL_QUESTIONS%" , $mlw_total_questions, $mlw_message_after);
		$mlw_message_after = str_replace( "%CORRECT_SCORE%" , $mlw_total_score, $mlw_message_after);
		$mlw_message_after = str_replace( "%QUIZ_NAME%" , $mlw_quiz_options->quiz_name, $mlw_message_after);
		$mlw_message_after = str_replace( "%USER_NAME%" , $mlw_user_name, $mlw_message_after);
		$mlw_message_after = str_replace( "%USER_BUSINESS%" , $mlw_user_comp, $mlw_message_after);
		$mlw_message_after = str_replace( "%USER_PHONE%" , $mlw_user_phone, $mlw_message_after);
		$mlw_message_after = str_replace( "%USER_EMAIL%" , $mlw_user_email, $mlw_message_after);
		$mlw_message_after = str_replace( "%QUESTIONS_ANSWERS%" , $mlw_question_answers, $mlw_message_after);
		$mlw_message_after = str_replace( "%COMMENT_SECTION%" , $_POST["mlwQuizComments"], $mlw_message_after);
		$mlw_message_after = str_replace( "\n" , "<br>", $mlw_message_after);
		$mlw_display .= $mlw_message_after;
	
		//Prepare and send the user email
		$mlw_message = "";
		if ($mlw_quiz_options->send_user_email == "0")
		{
			if ($mlw_user_email != "")
			{
				$mlw_message = $mlw_quiz_options->user_email_template;
				$mlw_message = str_replace( "%POINT_SCORE%" , $mlw_points, $mlw_message);
				$mlw_message = str_replace( "%AVERAGE_POINT%" , round(($mlw_points/$mlw_total_questions), 2), $mlw_message);
				$mlw_message = str_replace( "%AMOUNT_CORRECT%" , $mlw_correct, $mlw_message);
				$mlw_message = str_replace( "%TOTAL_QUESTIONS%" , $mlw_total_questions, $mlw_message);
				$mlw_message = str_replace( "%CORRECT_SCORE%" , $mlw_total_score, $mlw_message);
				$mlw_message = str_replace( "%QUIZ_NAME%" , $mlw_quiz_options->quiz_name, $mlw_message);
				$mlw_message = str_replace( "%USER_NAME%" , $mlw_user_name, $mlw_message);
				$mlw_message = str_replace( "%USER_BUSINESS%" , $mlw_user_comp, $mlw_message);
				$mlw_message = str_replace( "%USER_PHONE%" , $mlw_user_phone, $mlw_message);
				$mlw_message = str_replace( "%USER_EMAIL%" , $mlw_user_email, $mlw_message);
				$mlw_message = str_replace( "%QUESTIONS_ANSWERS%" , $mlw_question_answers, $mlw_message);
				$mlw_message = str_replace( "%COMMENT_SECTION%" , $_POST["mlwQuizComments"], $mlw_message);
				$mlw_message = str_replace( "<br />" , "\n", $mlw_message);
				$mlw_headers = 'From: '.$mlw_quiz_options->email_from_text.' <'.$mlw_quiz_options->admin_email.'>' . "\r\n";
				wp_mail($mlw_user_email, "Quiz Results For ".$mlw_quiz_options->quiz_name, $mlw_message, $mlw_headers);
			}
		}

		//Prepare and send the admin email
		$mlw_message = "";
		if ($mlw_quiz_options->send_admin_email == "0")
		{
			$mlw_message = $mlw_quiz_options->admin_email_template;
			$mlw_message = str_replace( "%POINT_SCORE%" , $mlw_points, $mlw_message);
			$mlw_message = str_replace( "%AVERAGE_POINT%" , round(($mlw_points/$mlw_total_questions), 2), $mlw_message);
			$mlw_message = str_replace( "%AMOUNT_CORRECT%" , $mlw_correct, $mlw_message);
			$mlw_message = str_replace( "%TOTAL_QUESTIONS%" , $mlw_total_questions, $mlw_message);
			$mlw_message = str_replace( "%CORRECT_SCORE%" , $mlw_total_score, $mlw_message);
			$mlw_message = str_replace( "%USER_NAME%" , $mlw_user_name, $mlw_message);
			$mlw_message = str_replace( "%USER_BUSINESS%" , $mlw_user_comp, $mlw_message);
			$mlw_message = str_replace( "%USER_PHONE%" , $mlw_user_phone, $mlw_message);
			$mlw_message = str_replace( "%USER_EMAIL%" , $mlw_user_email, $mlw_message);
			$mlw_message = str_replace( "%QUIZ_NAME%" , $mlw_quiz_options->quiz_name, $mlw_message);
			$mlw_message = str_replace( "%QUESTIONS_ANSWERS%" , $mlw_question_answers, $mlw_message);
			$mlw_message = str_replace( "%COMMENT_SECTION%" , $_POST["mlwQuizComments"], $mlw_message);
			$mlw_message .= " This email was generated by the Quiz Master Next script by Frank Corso";
			$mlw_message = str_replace( "<br />" , "\n", $mlw_message);
			$mlw_headers = 'From: '.$mlw_quiz_options->email_from_text.' <'.$mlw_quiz_options->admin_email.'>' . "\r\n";
			wp_mail($mlw_quiz_options->admin_email, "Quiz Results For ".$mlw_quiz_options->quiz_name, $mlw_message, $mlw_headers);
		}

		//Save the results into database
		$mlw_quiz_results = $mlw_question_answers."\n".$_POST["mlwQuizComments"];
		$mlw_quiz_results = str_replace( "\n" , "<br>", $mlw_quiz_results);
		$mlw_quiz_results = htmlspecialchars($mlw_quiz_results, ENT_QUOTES);
		global $wpdb;
		$table_name = $wpdb->prefix . "mlw_results";
		$insert = "INSERT INTO " . $table_name .
			"(result_id, quiz_id, quiz_name, quiz_system, point_score, correct_score, correct, total, name, business, email, phone, time_taken, time_taken_real, quiz_results, deleted) " .
			"VALUES (NULL , " . $mlw_quiz_id . " , '".$mlw_quiz_options->quiz_name."', ".$mlw_quiz_options->system.", ".$mlw_points.", ".$mlw_total_score.", ".$mlw_correct.", ".$mlw_total_questions.", '".$mlw_user_name."', '".$mlw_user_comp."', '".$mlw_user_email."', '".$mlw_user_phone."', '".date("h:i:s A m/d/Y")."', '".date("Y-m-d H:i:s")."', '".$mlw_quiz_results."', 0)";
		$results = $wpdb->query( $insert );
		}
		else
		{
			$mlw_display .= "Thank you.";	
		}
	}
return $mlw_display;
}
?>