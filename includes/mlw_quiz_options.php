<?php
/*
This page allows for the editing of quizzes selected from the quiz admin page.
*/
/* 
Copyright 2014, My Local Webstop (email : fpcorso@mylocalwebstop.com)
*/

function mlw_generate_quiz_options()
{
	$quiz_id = $_GET["quiz_id"];
	global $wpdb;
	$table_name = $wpdb->prefix . "mlw_questions";
	$is_new_quiz = 0;
	$hasUpdatedLeaderboardOptions = false;
	$hasCreatedQuestion = false;
	$hasUpdatedOptions = false;
	$hasUpdatedTemplates = false;
	$hasDeletedQuestion = false;
	$hasUpdatedQuestion = false;
	$mlw_hasResetQuizStats = false;
	$mlw_qmn_isQueryError = false;
	$mlw_qmn_error_code = '0000';
	
	/*
	Code for quiz questions tab
	*/

	//Edit question
	if ( isset($_POST["edit_question"]) && $_POST["edit_question"] == "confirmation")
	{
		//Variables from edit question form
		$edit_question_name = trim(preg_replace('/\s+/',' ', nl2br(htmlspecialchars($_POST["edit_question_name"], ENT_QUOTES))));
		$edit_answer_one = htmlspecialchars($_POST["edit_answer_one"], ENT_QUOTES);
		$edit_answer_one_points = intval($_POST["edit_answer_one_points"]);
		echo $edit_answer_one_points;
		$edit_answer_two = htmlspecialchars($_POST["edit_answer_two"], ENT_QUOTES);
		$edit_answer_two_points = intval($_POST["edit_answer_two_points"]);
		$edit_answer_three = htmlspecialchars($_POST["edit_answer_three"], ENT_QUOTES);
		$edit_answer_three_points = intval($_POST["edit_answer_three_points"]);
		$edit_answer_four = htmlspecialchars($_POST["edit_answer_four"], ENT_QUOTES);
		$edit_answer_four_points = intval($_POST["edit_answer_four_points"]);
		$edit_answer_five = htmlspecialchars($_POST["edit_answer_five"], ENT_QUOTES);
		$edit_answer_five_points = intval($_POST["edit_answer_five_points"]);
		$edit_answer_six = htmlspecialchars($_POST["edit_answer_six"], ENT_QUOTES);
		$edit_answer_six_points = intval($_POST["edit_answer_six_points"]);
		$edit_correct_answer = $_POST["edit_correct_answer"];
		$edit_question_answer_info = $_POST["edit_correct_answer_info"];
		$mlw_edit_question_id = intval($_POST["edit_question_id"]);
		$mlw_edit_question_type = $_POST["edit_question_type"];
		$edit_comments = htmlspecialchars($_POST["edit_comments"], ENT_QUOTES);
		$edit_hint = htmlspecialchars($_POST["edit_hint"], ENT_QUOTES);
		$edit_question_order = intval($_POST["edit_question_order"]);
		$quiz_id = $_POST["quiz_id"];
		
		$update = "UPDATE " . $wpdb->prefix . "mlw_questions" . " SET question_name='".$edit_question_name."', answer_one='".$edit_answer_one."', answer_one_points='".$edit_answer_one_points."', answer_two='".$edit_answer_two."', answer_two_points='".$edit_answer_two_points."', answer_three='".$edit_answer_three."', answer_three_points='".$edit_answer_three_points."', answer_four='".$edit_answer_four."', answer_four_points='".$edit_answer_four_points."', answer_five='".$edit_answer_five."', answer_five_points='".$edit_answer_five_points."', answer_six='".$edit_answer_six."', answer_six_points='".$edit_answer_six_points."', correct_answer='".$edit_correct_answer."', question_answer_info='".$edit_question_answer_info."', comments='".$edit_comments."', hints='".$edit_hint."', question_order='".$edit_question_order."', question_type='".$mlw_edit_question_type."' WHERE question_id=".$mlw_edit_question_id;
		$results = $wpdb->query( $update );
		if ($results != false)
		{
			$hasUpdatedQuestion = true;
		
			//Insert Action Into Audit Trail
			global $current_user;
			get_currentuserinfo();
			$table_name = $wpdb->prefix . "mlw_qm_audit_trail";
			$insert = "INSERT INTO " . $table_name .
				"(trail_id, action_user, action, time) " .
				"VALUES (NULL , '" . $current_user->display_name . "' , 'Question Has Been Edited: ".$edit_question_name."' , '" . date("h:i:s A m/d/Y") . "')";
			$results = $wpdb->query( $insert );
		}
		else
		{
			$mlw_qmn_isQueryError = true;
			$mlw_qmn_error_code = '0004';
		}
	}

	//Delete question from quiz
	if ( isset($_POST["delete_question"]) && $_POST["delete_question"] == "confirmation")
	{
		//Variables from delete question form
		$mlw_question_id = intval($_POST["question_id"]);
		$quiz_id = $_POST["quiz_id"];
		
		$update = "UPDATE " . $wpdb->prefix . "mlw_questions" . " SET deleted=1 WHERE question_id=".$mlw_question_id;
		$results = $wpdb->query( $update );
		if ($results != false)
		{
			$hasDeletedQuestion = true;
		
			//Insert Action Into Audit Trail
			global $current_user;
			get_currentuserinfo();
			$table_name = $wpdb->prefix . "mlw_qm_audit_trail";
			$insert = "INSERT INTO " . $table_name .
				"(trail_id, action_user, action, time) " .
				"VALUES (NULL , '" . $current_user->display_name . "' , 'Question Has Been Deleted: ".$mlw_question_id."' , '" . date("h:i:s A m/d/Y") . "')";
			$results = $wpdb->query( $insert );
		}
		else
		{
			$mlw_qmn_isQueryError = true;
			$mlw_qmn_error_code = '0002';
		}
	}		

	//Submit new question into database
	if ( isset($_POST["create_question"]) && $_POST["create_question"] == "confirmation")
	{
		//Variables from new question form
		$question_name = trim(preg_replace('/\s+/',' ', nl2br(htmlspecialchars($_POST["question_name"], ENT_QUOTES))));
		$answer_one = htmlspecialchars($_POST["answer_one"], ENT_QUOTES);
		$answer_one_points = intval($_POST["answer_one_points"]);
		$answer_two = htmlspecialchars($_POST["answer_two"], ENT_QUOTES);
		$answer_two_points = intval($_POST["answer_two_points"]);
		$answer_three = htmlspecialchars($_POST["answer_three"], ENT_QUOTES);
		$answer_three_points = intval($_POST["answer_three_points"]);
		$answer_four = htmlspecialchars($_POST["answer_four"], ENT_QUOTES);
		$answer_four_points = intval($_POST["answer_four_points"]);
		$answer_five = htmlspecialchars($_POST["answer_five"], ENT_QUOTES);
		$answer_five_points = intval($_POST["answer_five_points"]);
		$answer_six = htmlspecialchars($_POST["answer_six"], ENT_QUOTES);
		$answer_six_points = intval($_POST["answer_six_points"]);
		$correct_answer = $_POST["correct_answer"];
		$question_answer_info = $_POST["correct_answer_info"];
		$question_type = $_POST["question_type"];
		$comments = htmlspecialchars($_POST["comments"], ENT_QUOTES);
		$hint = htmlspecialchars($_POST["hint"], ENT_QUOTES);
		$new_question_order = intval($_POST["new_question_order"]);
		$quiz_id = $_POST["quiz_id"];
		$table_name = $wpdb->prefix . "mlw_questions";
		$insert = "INSERT INTO " . $table_name .
			" (question_id, quiz_id, question_name, answer_one, answer_one_points, answer_two, answer_two_points, answer_three, answer_three_points, answer_four, answer_four_points, answer_five, answer_five_points, answer_six, answer_six_points, correct_answer, question_answer_info, comments, hints, question_order, question_type, deleted) VALUES (NULL , ".$quiz_id.", '" . $question_name . "' , '" . $answer_one . "', ".$answer_one_points.", '" . $answer_two . "', ".$answer_two_points.", '" . $answer_three . "', ".$answer_three_points.", '" . $answer_four . "', ".$answer_four_points.", '" . $answer_five . "', ".$answer_five_points.", '" . $answer_six . "', ".$answer_six_points.", ".$correct_answer.", '".$question_answer_info."', '".$comments."', '".$hint."', ".$new_question_order.", '".$question_type."', 0)";
		$results = $wpdb->query( $insert );
		if ($results != false)
		{
			$hasCreatedQuestion = true;
		
			//Insert Action Into Audit Trail
			global $current_user;
			get_currentuserinfo();
			$table_name = $wpdb->prefix . "mlw_qm_audit_trail";
			$insert = "INSERT INTO " . $table_name .
				"(trail_id, action_user, action, time) " .
				"VALUES (NULL , '" . $current_user->display_name . "' , 'Question Has Been Added: ".$question_name."' , '" . date("h:i:s A m/d/Y") . "')";
			$results = $wpdb->query( $insert );
		}
		else
		{
			$mlw_qmn_isQueryError = true;
			$mlw_qmn_error_code = '0006';
		}
	}

	//Get table of questions for this quiz
	if ($quiz_id != "")
	{
		global $wpdb;
		$mlw_qmn_table_limit = 10;
		$mlw_qmn_question_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(question_id) FROM " . $wpdb->prefix . "mlw_questions WHERE quiz_id=%d AND deleted='0'", $quiz_id ) );
		
		if( isset($_GET{'mlw_question_page'} ) )
		{
		   $mlw_qmn_question_page = $_GET{'mlw_question_page'} + 1;
		   $mlw_qmn_question_begin = $mlw_qmn_table_limit * $mlw_qmn_question_page ;
		}
		else
		{
		   $mlw_qmn_question_page = 0;
		   $mlw_qmn_question_begin = 0;
		}
		$mlw_qmn_question_left = $mlw_qmn_question_count - ($mlw_qmn_question_page * $mlw_qmn_table_limit);
		
		$mlw_question_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "mlw_questions WHERE quiz_id=%d AND deleted='0' 
			ORDER BY question_order ASC LIMIT %d, %d", $quiz_id, $mlw_qmn_question_begin, $mlw_qmn_table_limit ) );
		$is_new_quiz = $wpdb->num_rows;
	}

	/*
	Code for Quiz Text tab
	*/

	//Submit saved templates into database
	if ( isset($_POST["save_templates"]) && $_POST["save_templates"] == "confirmation")
	{
		//Variables for save templates form
		$mlw_before_message = $_POST["mlw_quiz_before_message"];
		$mlw_after_message = $_POST["mlw_quiz_after_message"];
		$mlw_user_tries_text = $_POST["mlw_quiz_total_user_tries_text"];
		$mlw_user_email_template = $_POST["mlw_quiz_user_email_template"];
		$mlw_admin_email_template = $_POST["mlw_quiz_admin_email_template"];
		$mlw_submit_button_text = $_POST["mlw_submitText"];
		$mlw_name_field_text = $_POST["mlw_nameText"];
		$mlw_business_field_text = $_POST["mlw_businessText"];
		$mlw_email_field_text = $_POST["mlw_emailText"];
		$mlw_phone_field_text = $_POST["mlw_phoneText"];
		$mlw_before_comments = $_POST["mlw_quiz_before_comments"];
		$mlw_comment_field_text = $_POST["mlw_commentText"];
		$mlw_email_from_text = $_POST["emailFromText"];
		$mlw_question_answer_template = $_POST["mlw_quiz_question_answer_template"];
		$quiz_id = $_POST["quiz_id"];
		
		$update = "UPDATE " . $wpdb->prefix . "mlw_quizzes" . " SET message_before='".$mlw_before_message."', message_comment='".$mlw_before_comments."', comment_field_text='".$mlw_comment_field_text."', email_from_text='".$mlw_email_from_text."', question_answer_template='".$mlw_question_answer_template."', submit_button_text='".$mlw_submit_button_text."', name_field_text='".$mlw_name_field_text."', business_field_text='".$mlw_business_field_text."', email_field_text='".$mlw_email_field_text."', phone_field_text='".$mlw_phone_field_text."', message_after='".$mlw_after_message."', user_email_template='".$mlw_user_email_template."', admin_email_template='".$mlw_admin_email_template."', total_user_tries_text='".$mlw_user_tries_text."' WHERE quiz_id=".$quiz_id;
		$results = $wpdb->query( $update );
		if ($results != false)
		{
			$hasUpdatedTemplates = true;
		
			//Insert Action Into Audit Trail
			global $current_user;
			get_currentuserinfo();
			$table_name = $wpdb->prefix . "mlw_qm_audit_trail";
			$insert = "INSERT INTO " . $table_name .
				"(trail_id, action_user, action, time) " .
				"VALUES (NULL , '" . $current_user->display_name . "' , 'Templates Have Been Edited For Quiz Number ".$quiz_id."' , '" . date("h:i:s A m/d/Y") . "')";
			$results = $wpdb->query( $insert );
		}
		else
		{
			$mlw_qmn_isQueryError = true;
			$mlw_qmn_error_code = '0007';
		}
	}
	

	/*
	Code for Quiz Options tab
	*/

	//Submit saved options into database
	if ( isset($_POST["save_options"]) && $_POST["save_options"] == "confirmation")
	{
		//Variables for save options form
		$mlw_system = $_POST["system"];
		$mlw_qmn_questions_from_total = $_POST["question_from_total"];
		$mlw_randomness_order = $_POST["randomness_order"];
		$mlw_total_user_tries = intval($_POST["total_user_tries"]);
		$mlw_send_user_email = $_POST["sendUserEmail"];
		$mlw_send_admin_email = $_POST["sendAdminEmail"];
		$mlw_contact_location = $_POST["contact_info_location"];
		$mlw_user_name = $_POST["userName"];
		$mlw_user_comp = $_POST["userComp"];
		$mlw_user_email = $_POST["userEmail"];
		$mlw_user_phone = $_POST["userPhone"];
		$mlw_admin_email = $_POST["adminEmail"];
		$mlw_comment_section = $_POST["commentSection"];
		$mlw_qmn_loggedin_contact = $_POST["loggedin_user_contact"];
		$quiz_id = $_POST["quiz_id"];
		
		$update = "UPDATE " . $wpdb->prefix . "mlw_quizzes" . " SET system='".$mlw_system."', send_user_email='".$mlw_send_user_email."', send_admin_email='".$mlw_send_admin_email."', loggedin_user_contact='".$mlw_qmn_loggedin_contact."', contact_info_location=".$mlw_contact_location.", user_name='".$mlw_user_name."', user_comp='".$mlw_user_comp."', user_email='".$mlw_user_email."', user_phone='".$mlw_user_phone."', admin_email='".$mlw_admin_email."', comment_section='".$mlw_comment_section."', randomness_order='".$mlw_randomness_order."', question_from_total=".$mlw_qmn_questions_from_total.", total_user_tries=".$mlw_total_user_tries." WHERE quiz_id=".$quiz_id;
		$results = $wpdb->query( $update );
		if ($results != false)
		{
			$hasUpdatedOptions = true;
			
			//Insert Action Into Audit Trail
			global $current_user;
			get_currentuserinfo();
			$table_name = $wpdb->prefix . "mlw_qm_audit_trail";
			$insert = "INSERT INTO " . $table_name .
				"(trail_id, action_user, action, time) " .
				"VALUES (NULL , '" . $current_user->display_name . "' , 'Options Have Been Edited For Quiz Number ".$quiz_id."' , '" . date("h:i:s A m/d/Y") . "')";
			$results = $wpdb->query( $insert );
		}
		else
		{
			$mlw_qmn_isQueryError = true;
			$mlw_qmn_error_code = '0008';
		}
	}
	
	/*
	Code For Leaderboard Options tab
	*/
	
	///Submit saved leaderboard template into database
	if ( isset($_POST["save_leaderboard_options"]) && $_POST["save_leaderboard_options"] == "confirmation")
	{
		///Variables for save leaderboard options form
		$mlw_leaderboard_template = $_POST["mlw_quiz_leaderboard_template"];
		$mlw_leaderboard_quiz_id = $_POST["leaderboard_quiz_id"];
		$update = "UPDATE " . $wpdb->prefix . "mlw_quizzes" . " SET leaderboard_template='".$mlw_leaderboard_template."' WHERE quiz_id=".$mlw_leaderboard_quiz_id;
		$results = $wpdb->query( $update );
		if ($results != false)
		{
			$hasUpdatedLeaderboardOptions = true;
			
			//Insert Action Into Audit Trail
			global $current_user;
			get_currentuserinfo();
			$table_name = $wpdb->prefix . "mlw_qm_audit_trail";
			$insert = "INSERT INTO " . $table_name .
				"(trail_id, action_user, action, time) " .
				"VALUES (NULL , '" . $current_user->display_name . "' , 'Leaderboard Options Have Been Edited For Quiz Number ".$mlw_leaderboard_quiz_id."' , '" . date("h:i:s A m/d/Y") . "')";
			$results = $wpdb->query( $insert );
		}
		else
		{
			$mlw_qmn_isQueryError = true;
			$mlw_qmn_error_code = '0009';
		}
	}
	
	
	/*
	Code For Quiz Tools Tab
	*/
	
	//Update Quiz Table
	if (isset($_POST["mlw_reset_quiz_stats"]) && $_POST["mlw_reset_quiz_stats"] == "confirmation")
	{
		//Variables from reset stats form
		$mlw_reset_stats_quiz_id = $_POST["mlw_reset_quiz_id"];
		$mlw_reset_update_sql = "UPDATE " . $wpdb->prefix . "mlw_quizzes" . " SET quiz_views=0, quiz_taken=0 WHERE quiz_id=".$mlw_reset_stats_quiz_id;
		$mlw_reset_sql_results = $wpdb->query( $mlw_reset_update_sql );
		if ($mlw_reset_sql_results != false)
		{
			$mlw_hasResetQuizStats = true;
			
			//Insert Action Into Audit Trail
			global $current_user;
			get_currentuserinfo();
			$table_name = $wpdb->prefix . "mlw_qm_audit_trail";
			$insert = "INSERT INTO " . $table_name .
				"(trail_id, action_user, action, time) " .
				"VALUES (NULL , '" . $current_user->display_name . "' , 'Quiz Stats Have Been Reset For Quiz Number ".$mlw_leaderboard_quiz_id."' , '" . date("h:i:s A m/d/Y") . "')";
			$results = $wpdb->query( $insert );	
		}
		else
		{
			$mlw_qmn_isQueryError = true;
			$mlw_qmn_error_code = '0010';
		}
	}


	/*
	Code for entire page
	*/

	//Load all quiz data
	if ($quiz_id != "")
	{
		$sql = "SELECT * FROM " . $wpdb->prefix . "mlw_quizzes" . " WHERE quiz_id=".$quiz_id;
		$mlw_quiz_options = $wpdb->get_results($sql);
	
		foreach($mlw_quiz_options as $testing) {
			$mlw_quiz_options = $testing;
			break;
		}
	}
	?>
	<!-- css -->
	<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/redmond/jquery-ui.css" rel="stylesheet" />
	<!-- jquery scripts -->
	<?php
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-dialog' );
	wp_enqueue_script( 'jquery-ui-button' );
	wp_enqueue_script( 'jquery-ui-accordion' );
	wp_enqueue_script( 'jquery-ui-tooltip' );
	wp_enqueue_script( 'jquery-ui-tabs' );
	wp_enqueue_script( 'jquery-effects-blind' );
	wp_enqueue_script( 'jquery-effects-explode' );
	?>
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
			$j('#questions_help_dialog').dialog({
				autoOpen: false,
				show: 'blind',
				width:700,
				hide: 'explode',
				buttons: {
				Ok: function() {
					$j(this).dialog('close');
					}
				}
			});
		
			$j('#question_tab_help').click(function() {
				$j('#questions_help_dialog').dialog('open');
				return false;
		}	);
		});
		$j(function() {
			$j('#templates_help_dialog').dialog({
				autoOpen: false,
				show: 'blind',
				width:700,
				hide: 'explode',
				buttons: {
				Ok: function() {
					$j(this).dialog('close');
					}
				}
			});
		
			$j('#template_tab_help').click(function() {
				$j('#templates_help_dialog').dialog('open');
				return false;
		}	);
		});
		$j(function() {
			$j('#options_help_dialog').dialog({
				autoOpen: false,
				show: 'blind',
				width:700,
				hide: 'explode',
				buttons: {
				Ok: function() {
					$j(this).dialog('close');
					}
				}
			});
		
			$j('#options_tab_help').click(function() {
				$j('#options_help_dialog').dialog('open');
				return false;
		}	);
		});
		$j(function() {
			$j('#leaderboard_help_dialog').dialog({
				autoOpen: false,
				show: 'blind',
				width:700,
				hide: 'explode',
				buttons: {
				Ok: function() {
					$j(this).dialog('close');
					}
				}
			});
		
			$j('#leaderboard_tab_help').click(function() {
				$j('#leaderboard_help_dialog').dialog('open');
				return false;
		}	);
		});
		$j(function() {
			$j('#mlw_reset_stats_dialog').dialog({
				autoOpen: false,
				show: 'blind',
				width:700,
				hide: 'explode',
				buttons: {
				Ok: function() {
					$j(this).dialog('close');
					}
				}
			});
		
			$j('#mlw_reset_stats_button').click(function() {
				$j('#mlw_reset_stats_dialog').dialog('open');
				return false;
		}	);
		});
		$j(function() {
    			$j( "#tabs" ).tabs();
  		});
		$j(function() {
   			 $j( document ).tooltip();
 		});
		$j(function() {
			$j("#accordion").accordion({
				heightStyle: "content"
			});

		});
		$j(function() {
    			$j( "#system" ).buttonset();
  		});
  		$j(function() {
    			$j( "#randomness_order" ).buttonset();
  		});
  		$j(function() {
  				$j( "#loggedin_user_contact" ).buttonset();	
  		});
		$j(function() {
    			$j( "#sendUserEmail" ).buttonset();
  		});
		$j(function() {
    			$j( "#sendAdminEmail" ).buttonset();
  		});
  		$j(function() {
    			$j( "#contact_info_location" ).buttonset();
  		});
		$j(function() {
    			$j( "#userName" ).buttonset();
  		});
		$j(function() {
    			$j( "#userComp" ).buttonset();
  		});
		$j(function() {
    			$j( "#userEmail" ).buttonset();
  		});
		$j(function() {
    			$j( "#userPhone" ).buttonset();
  		});
  		$j(function() {
  				$j( "#commentSection" ).buttonset();
  		});
  		$j(function() {
  				$j( "#comments" ).buttonset();
  		});
  		$j(function() {
  				$j( "#question_type" ).buttonset();
  		});
  		$j(function() {
  				$j( "#edit_question_type" ).buttonset();
  		});
  		$j(function() {
  				$j( "#edit_comments" ).buttonset();
  		});
		$j(function() {
			$j("button, #prev_page, #next_page").button();
		
		});
		$j(function() {
			$j('#new_question_dialog').dialog({
				autoOpen: false,
				show: 'blind',
				width:800,
				hide: 'explode',
				buttons: {
				Cancel: function() {
					$j(this).dialog('close');
					}
				}
			});
		
			$j('#new_question_button').click(function() {
				$j('#new_question_dialog').dialog('open');
				document.getElementById("question_name").focus();
				return false;
		}	);
			$j('#new_question_button_two').click(function() {
				$j('#new_question_dialog').dialog('open');
				document.getElementById("question_name").focus();
				return false;
		}	);
		});
		function deleteQuestion(id){
			$j("#delete_dialog").dialog({
				autoOpen: false,
				show: 'blind',
				hide: 'explode',
				buttons: {
				Cancel: function() {
					$j(this).dialog('close');
					}
				}
			});
			$j("#delete_dialog").dialog('open');
			var idText = document.getElementById("delete_question_id");
			var idHidden = document.getElementById("question_id");
			idText.innerHTML = id;
			idHidden.value = id;		
		};
		function editQuestion(id, question, answerOne, answerOnePoints, answerTwo, answerTwoPoints, answerThree, answerThreePoints, answerFour, answerFourPoints, answerFive, answerFivePoints, answerSix, answerSixPoints, correctAnswer, answer_info, comments, hint, question_order, question_type){
			$j("#edit_question_dialog").dialog({
				autoOpen: false,
				show: 'blind',
				width:800,
				hide: 'explode',
				buttons: {
				Cancel: function() {
					$j(this).dialog('close');
					}
				}
			});
			$j("#edit_question_dialog").dialog('open');
			var idHidden = document.getElementById("edit_question_id");
			idHidden.value = id;
			document.getElementById("edit_question_name").value = question;
			document.getElementById("edit_answer_one").value = answerOne;	
			document.getElementById("edit_answer_two").value = answerTwo;
			document.getElementById("edit_answer_three").value = answerThree;	
			document.getElementById("edit_answer_four").value = answerFour;
			document.getElementById("edit_answer_five").value = answerFive;	
			document.getElementById("edit_answer_six").value = answerSix;
			document.getElementById("edit_answer_one_points").value = answerOnePoints;
			document.getElementById("edit_answer_two_points").value = answerTwoPoints;
			document.getElementById("edit_answer_three_points").value = answerThreePoints;
			document.getElementById("edit_answer_four_points").value = answerFourPoints;
			document.getElementById("edit_answer_five_points").value = answerFivePoints;
			document.getElementById("edit_answer_six_points").value = answerSixPoints;
			document.getElementById("edit_correct_answer_info").value = answer_info;
			document.getElementById("edit_hint").value = hint;
			document.getElementById("edit_question_order").value = question_order;
			if (correctAnswer == 1) document.getElementById("edit_correct_one").checked = true;
			if (correctAnswer == 2) document.getElementById("edit_correct_two").checked = true;
			if (correctAnswer == 3) document.getElementById("edit_correct_three").checked = true;
			if (correctAnswer == 4) document.getElementById("edit_correct_four").checked = true;
			if (correctAnswer == 5) document.getElementById("edit_correct_five").checked = true;
			if (correctAnswer == 6) document.getElementById("edit_correct_six").checked = true;
			if (question_type == 0) $j('#editTypeRadio1').attr('checked', true).button('refresh');
			if (question_type == 1) $j('#editTypeRadio2').attr('checked', true).button('refresh');
			if (question_type == 2) $j('#editTypeRadio3').attr('checked', true).button('refresh');
			/*
			if (question_type == 0) document.getElementById("editTypeRadio1").checked = true;
			if (question_type == 1) document.getElementById("editTypeRadio2").checked = true;
			if (question_type == 2) document.getElementById("editTypeRadio3").checked = true;
			*/
			if (comments == 0) $j('#editCommentRadio1').attr('checked', true).button('refresh');
			if (comments == 1) $j('#editCommentRadio2').attr('checked', true).button('refresh');
			if (comments == 2) $j('#editCommentRadio3').attr('checked', true).button('refresh');
			/*
			if (comments == 0) document.getElementById("editCommentRadio1").checked = true;
			if (comments == 1) document.getElementById("editCommentRadio2").checked = true;
			if (comments == 2) document.getElementById("editCommentRadio3").checked = true;
			*/
		};
	</script>
	<div class="wrap">
	<div class='mlw_quiz_options'>
	<?php
		if ($quiz_id != "")
	{
	?>
	<h2>Quiz Options For <?php echo $mlw_quiz_options->quiz_name; ?><a id="opener" href="">(?)</a></h2>
	<?php if ($hasUpdatedLeaderboardOptions)
		{
	?>
		<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
		<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
		<strong>Success!</strong> Your leaderboard options for this quiz have been saved.</p>
	</div>
	<?php
		}
	?>
	<?php if ($mlw_qmn_isQueryError)
		{
	?>
		<div class="ui-state-error ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
		<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
		<strong>Uh-Oh!</strong> There has been an error in this action! Please share this with the developer: Error Code <?php echo $mlw_qmn_error_code; ?></p>
	</div>
	<?php
		}
		if ($hasCreatedQuestion)
		{
	?>
		<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
		<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
		<strong>Success!</strong> Your new question has been created successfully.</p>
	</div>
	<?php
		}
	?>
	<?php if ($hasUpdatedTemplates)
		{
	?>
		<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
		<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
		<strong>Success!</strong> Your templates for this quiz have been saved.</p>
	</div>
	<?php
		}
	?>
	<?php if ($hasUpdatedOptions)
		{
	?>
		<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
		<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
		<strong>Success!</strong> Your options for this quiz have been saved.</p>
	</div>
	<?php
		}
	?>
	<?php if ($hasDeletedQuestion)
		{
	?>
		<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
		<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
		<strong>Success!</strong> The question has been deleted from this quiz.</p>
	</div>
	<?php
		}
	?>
	<?php if ($hasUpdatedQuestion)
		{
	?>
		<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
		<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
		<strong>Success!</strong> The question has been updated.</p>
	</div>
	<?php
		}
	?>
	<?php if ($mlw_hasResetQuizStats)
		{
	?>
		<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
		<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
		<strong>Success!</strong> The stats for this quiz has been reset!</p>
	</div>
	<?php
		}
	?>
	<div id="tabs">
		<ul>
		    <li><a href="#tabs-1">Quiz Questions</a></li>
		    <li><a href="#tabs-2">Quiz Text</a></li>
		    <li><a href="#tabs-3">Quiz Options</a></li>
		    <li><a href="#tabs-4">Quiz Leaderboard</a></li>		   
		    <li><a href="#tabs-5">Quiz Tools</a></li>
		</ul>
  		<div id="tabs-1">
  			<button id="new_question_button_two">Add Question</button><button id="question_tab_help">Help</button>
  			<br />
			<?php
			$question_list = "";
			$display = "";
			$alternate = "";
			foreach($mlw_question_data as $mlw_question_info) {
				if($alternate) $alternate = "";
				else $alternate = " class=\"alternate\"";
				$question_list .= "<tr{$alternate}>";
				$question_list .= "<td><span style='font-size:16px;'>" . $mlw_question_info->question_order . "</span></td>";
				$question_list .= "<td class='post-title column-title'><span style='font-size:16px;'>" . $mlw_question_info->question_name ."</span><div><span style='color:green;font-size:12px;'><a onclick=\"editQuestion('".$mlw_question_info->question_id."','".str_replace('"', '&quot;', str_replace("'", "\'", htmlspecialchars_decode($mlw_question_info->question_name, ENT_QUOTES)))."', '".str_replace('"', '&quot;', str_replace("'", "\'", htmlspecialchars_decode($mlw_question_info->answer_one, ENT_QUOTES)))."','".$mlw_question_info->answer_one_points."','".str_replace('"', '&quot;', str_replace("'", "\'", htmlspecialchars_decode($mlw_question_info->answer_two, ENT_QUOTES)))."','".$mlw_question_info->answer_two_points."','".str_replace('"', '&quot;', str_replace("'", "\'", htmlspecialchars_decode($mlw_question_info->answer_three, ENT_QUOTES)))."','".$mlw_question_info->answer_three_points."','".str_replace('"', '&quot;', str_replace("'", "\'", htmlspecialchars_decode($mlw_question_info->answer_four, ENT_QUOTES)))."','".$mlw_question_info->answer_four_points."','".str_replace('"', '&quot;', str_replace("'", "\'", htmlspecialchars_decode($mlw_question_info->answer_five, ENT_QUOTES)))."','".$mlw_question_info->answer_five_points."','".str_replace('"', '&quot;', str_replace("'", "\'", htmlspecialchars_decode($mlw_question_info->answer_six, ENT_QUOTES)))."','".$mlw_question_info->answer_six_points."','".$mlw_question_info->correct_answer."', '".$mlw_question_info->question_answer_info."', '".$mlw_question_info->comments."','".str_replace("'", "\'", htmlspecialchars_decode($mlw_question_info->hints, ENT_QUOTES))."', '".$mlw_question_info->question_order."', '".$mlw_question_info->question_type."')\" href='#'>Edit</a> | <a onclick=\"deleteQuestion('".$mlw_question_info->question_id."')\" href='#'>Delete</a></span></div></td>";
				$question_list .= "</tr>";
			}
			
			if( $mlw_qmn_question_page > 0 )
			{
			   	$mlw_qmn_previous_page = $mlw_qmn_question_page - 2;
			   	$display .= "<a id=\"prev_page\" href=\"$_PHP_SELF?page=mlw_quiz_options&&mlw_question_page=$mlw_qmn_previous_page&&quiz_id=$quiz_id\">Previous 10 Questions</a>";
			   	if( $mlw_qmn_question_left > $mlw_qmn_table_limit )
			   	{
					$display .= "<a id=\"next_page\" href=\"$_PHP_SELF?page=mlw_quiz_options&&mlw_question_page=$mlw_qmn_question_page&&quiz_id=$quiz_id\">Next 10 Questions</a>";
			   	}
			}
			else if( $mlw_qmn_question_page == 0 )
			{
			   if( $mlw_qmn_question_left > $mlw_qmn_table_limit )
			   {
					$display .= "<a id=\"next_page\" href=\"$_PHP_SELF?page=mlw_quiz_options&&mlw_question_page=$mlw_qmn_question_page&&quiz_id=$quiz_id\">Next 10 Questions</a>";
			   }
			}
			else if( $mlw_qmn_question_left < $mlw_qmn_table_limit )
			{
			   $mlw_qmn_previous_page = $mlw_qmn_question_page - 2;
			   $display .= "<a id=\"prev_page\" href=\"$_PHP_SELF?page=mlw_quiz_options&&mlw_question_page=$mlw_qmn_previous_page&&quiz_id=$quiz_id\">Previous 10 Questions</a>";
			}

			$display .= "<table class=\"widefat\">";
				$display .= "<thead><tr>
					<th>Question Order</th>
					<th>Question Name</th>
				</tr></thead>";
				$display .= "<tbody id=\"the-list\">{$question_list}</tbody>";
				$display .= "<tfoot><tr>
					<th>Question Order</th>
					<th>Question Name</th>
				</tr></tfoot>";
				$display .= "</table>";
			echo $display;
			?>
			<button id="new_question_button">Add Question</button>
			<div id="new_question_dialog" title="Create New Question" style="display:none;">
			
			<?php
			echo "<form action='' method='post'>";
			echo "<input type='hidden' name='create_question' value='confirmation' />";
			echo "<input type='hidden' name='quiz_id' value='".$quiz_id."' />";
			?>		
			<table>
			<tr>
			<td><span style='font-weight:bold;'>Question<a href="#" title="Enter the question here. Feel free to use HTML, embed Youtube videos, link to images, etc...">?</a></span></td>
			<td colspan="3">
				<textarea name="question_name" id="question_name" style="width: 500px; height: 150px;"></textarea>
			</td>
			</tr>
			<tr valign="top">
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			</tr>
			<tr valign="top">
			<td>&nbsp;</td>
			<td><span style='font-weight:bold;'>Answers<a href='#' title="Enter the question's answers here. If you are using this quiz as a survey or form, you can leave all the answers blank to only show the comment field.">?</a></span></td>
			<td><span style='font-weight:bold;'>Points Worth<a href="#" title="If you have your quiz set up using the point system, enter how many points this answer is worth here. If you are not using the point system, leave this as 0.">?</a></span></td>
			<td><span style='font-weight:bold;'>Correct Answer<a href="#" title="Select the correct answer.">?</a></span></td>
			</tr>
			<tr valign="top">
			<td><span style='font-weight:bold;'>Answer One</span></td>
			<td>
			<input type="text" name="answer_one" value="" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;
				width: 250px;"/>
			</td>
			<td>
			<input type="text" name="answer_one_points" value="0" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;"/>
			</td>
			<td><input type="radio" name="correct_answer" checked="checked" value=1 /></td>
			</tr>
			<tr valign="top">
			<td><span style='font-weight:bold;'>Answer Two</span></td>
			<td>
			<input type="text" name="answer_two" value="" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;
				width: 250px;"/>
			</td>
			<td>
			<input type="text" name="answer_two_points" value="0" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;"/>
			</td>
			<td><input type="radio" name="correct_answer" value=2 /></td>
			</tr>
			<tr valign="top">
			<td><span style='font-weight:bold;'>Answer Three</span></td>
			<td>
			<input type="text" name="answer_three" value="" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;
				width: 250px;"/>
			</td>
			<td>
			<input type="text" name="answer_three_points" value="0" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;"/>
			</td>
			<td><input type="radio" name="correct_answer" value=3 /></td>
			</tr>
			<tr valign="top">
			<td><span style='font-weight:bold;'>Answer Four</span></td>
			<td>
			<input type="text" name="answer_four" value="" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;
				width: 250px;"/>
			</td>
			<td>
			<input type="text" name="answer_four_points" value="0" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;"/>
			</td>
			<td><input type="radio" name="correct_answer" value=4 /></td>
			</tr>
			<tr valign="top">
			<td><span style='font-weight:bold;'>Answer Five</span></td>
			<td>
			<input type="text" name="answer_five" value="" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;
				width: 250px;"/>
			</td>
			<td>
			<input type="text" name="answer_five_points" value="0" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;"/>
			</td>
			<td><input type="radio" name="correct_answer" value=5 /></td>
			</tr>
			<tr valign="top">
			<td><span style='font-weight:bold;'>Answer Six</span></td>
			<td>
			<input type="text" name="answer_six" value="" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;
				width: 250px;"/>
			</td>
			<td>
			<input type="text" name="answer_six_points" value="0" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;"/>
			</td>
			<td><input type="radio" name="correct_answer" value=6 /></td>
			</tr>
			<tr>
				<td><span style='font-weight:bold;'>Correct Answer Info<a href="#" title="Enter in the reason why the correct answer is correct. Add this to the %QUESTIONS_ANSWERS% template using the new %CORRECT_ANSWER_INFO% variable.">?</a></span></td>
				<td colspan="3"><input type="text" name="correct_answer_info" value="" id="correct_answer_info" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;
				width:550px;"/></td>
			</tr>
			<tr valign="top">
			<td><span style='font-weight:bold;'>Hint<a href="#" title="Enter the question's hint." >?</a></span></td>
			<td colspan="3">
			<input type="text" name="hint" value="" id="hint" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;
				width:550px;"/>
			</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr valign="top">
			<td><span style='font-weight:bold;'>Question Type<a href="#" title="The normal setting will show the question as it would normally; the horizontal setting will show the answers going across rather than down; the drop down setting will show the answers in a drop down menu instead of the raidio button." >?</a></span></td>
			<td colspan="3"><div id="question_type">
				<input type="radio" id="typeRadio1" name="question_type" checked="checked" value=0 /><label for="typeRadio1">Normal (Vertical Radio)</label>
				<input type="radio" id="typeRadio2" name="question_type" value=1 /><label for="typeRadio2">Horizontal Radio</label>
				<input type="radio" id="typeRadio3" name="question_type" value=2 /><label for="typeRadio3">Drop Down</label>
			</div></td>
			</tr>
			<tr valign="top">
			<td><span style='font-weight:bold;'>Comment Field<a href="#" title="The small text field setting will show a small field similar to the answer field above; the large text field setting will show a large text area; the none setting will now show any comment section for this question." >?</a></span></td>
			<td colspan="3"><div id="comments">
				<input type="radio" id="commentsRadio1" name="comments" value=0 /><label for="commentsRadio1">Small Text Field</label>
				<input type="radio" id="commentsRadio3" name="comments" value=2 /><label for="commentsRadio3">Large Text Field</label>
				<input type="radio" id="commentsRadio2" name="comments" checked="checked" value=1 /><label for="commentsRadio2">None</label>
			</div></td>
			</tr>
			<tr valign="top">
			<td><span style='font-weight:bold;'>Question Order<a href="#" title="Enter the place of the question in the quiz. If you do not have a certain order, you can leave this as 1.">?</a></span></td>
			<td>
			<input type="number" step="1" min="1" name="new_question_order" value="1" id="new_question_order" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;"/>
			</td>
			</tr>
			</table>
			<?php
			echo "<p class='submit'><input type='submit' class='button-primary' value='Create Question' /></p>";
			echo "</form>";
			?>
			</div>

			
			<div id="edit_question_dialog" title="Edit Question" style="display:none;">
			<?php
			echo "<form action='' method='post'>";
			echo "<input type='hidden' name='edit_question' value='confirmation' />";
			echo "<input type='hidden' id='edit_question_id' name='edit_question_id' value='' />";
			echo "<input type='hidden' name='quiz_id' value='".$quiz_id."' />";
			?>
			<table class="wide" style="text-align: left; white-space: nowrap;">
			<tr>
			<td><span style='font-weight:bold;'>Question</span></td>
			<td colspan="3">
				<textarea name="edit_question_name" id="edit_question_name" style="width: 500px; height: 150px;"></textarea>
			</td>
			</tr>
			<tr valign="top">
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			</tr>
			<tr valign="top">
			<td>&nbsp;</td>
			<td><span style='font-weight:bold;'>Answers</span></td>
			<td><span style='font-weight:bold;'>Points Worth</span></td>
			<td><span style='font-weight:bold;'>Correct Answer</span></td>
			</tr>
			<tr valign="top">
			<td><span style='font-weight:bold;'>Answer One</span></td>
			<td>
			<input type="text" name="edit_answer_one" id="edit_answer_one" value="" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;
				width: 250px;"/>
			</td>
			<td>
			<input type="text" name="edit_answer_one_points" id="edit_answer_one_points" value="0" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;"/>
			</td>
			<td><input type="radio" id="edit_correct_one" name="edit_correct_answer" checked="checked" value=1 /></td>
			</tr>
			<tr valign="top">
			<td><span style='font-weight:bold;'>Answer Two</span></td>
			<td>
			<input type="text" name="edit_answer_two" id="edit_answer_two" value="" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;
				width: 250px;"/>
			</td>
			<td>
			<input type="text" name="edit_answer_two_points" id="edit_answer_two_points" value="0" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;"/>
			</td>
			<td><input type="radio" id="edit_correct_two" name="edit_correct_answer" value=2 /></td>
			</tr>
			<tr valign="top">
			<td><span style='font-weight:bold;'>Answer Three</span></td>
			<td>
			<input type="text" name="edit_answer_three" id="edit_answer_three" value="" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;
				width: 250px;"/>
			</td>
			<td>
			<input type="text" name="edit_answer_three_points" id="edit_answer_three_points" value="0" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;"/>
			</td>
			<td><input type="radio" id="edit_correct_three" name="edit_correct_answer" value=3 /></td>
			</tr>
			<tr valign="top">
			<td><span style='font-weight:bold;'>Answer Four</span></td>
			<td>
			<input type="text" name="edit_answer_four" value="" id="edit_answer_four" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;
				width: 250px;"/>
			</td>
			<td>
			<input type="text" name="edit_answer_four_points" id="edit_answer_four_points" value="0" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;"/>
			</td>
			<td><input type="radio" id="edit_correct_four" name="edit_correct_answer" value=4 /></td>
			</tr>
			<tr valign="top">
			<td><span style='font-weight:bold;'>Answer Five</span></td>
			<td>
			<input type="text" name="edit_answer_five" value="" id="edit_answer_five" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;
				width: 250px;"/>
			</td>
			<td>
			<input type="text" name="edit_answer_five_points" id="edit_answer_five_points" value="0" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;"/>
			</td>
			<td><input type="radio" id="edit_correct_five" name="edit_correct_answer" value=5 /></td>
			</tr>
			<tr valign="top">
			<td><span style='font-weight:bold;'>Answer Six</span></td>
			<td>
			<input type="text" name="edit_answer_six" value="" id="edit_answer_six" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;
				width: 250px;"/>
			</td>
			<td>
			<input type="text" name="edit_answer_six_points" id="edit_answer_six_points" value="0" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;"/>
			</td>
			<td><input type="radio" id="edit_correct_six" name="edit_correct_answer" value=6 /></td>
			</tr>
			<tr>
				<td><span style='font-weight:bold;'>Correct Answer Info:</span></td>
				<td colspan="3"><input type="text" name="edit_correct_answer_info" value="" id="edit_correct_answer_info" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;
				width:550px;"/></td>
			</tr>
			<tr valign="top">
			<td><span style='font-weight:bold;'>Hint</span></td>
			<td colspan="3">
			<input type="text" name="edit_hint" value="" id="edit_hint" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;
				width:550px;"/>
			</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr valign="top">
			<td><span style='font-weight:bold;'>Question Type?</span></td>
			<td colspan="3"><div id="edit_question_type">
				<input type="radio" id="editTypeRadio1" name="edit_question_type" checked="checked" value=0 /><label for="editTypeRadio1">Normal (Vertical Radio)</label>
				<input type="radio" id="editTypeRadio2" name="edit_question_type" value=1 /><label for="editTypeRadio2">Horizontal Radio</label>
				<input type="radio" id="editTypeRadio3" name="edit_question_type" value=2 /><label for="editTypeRadio3">Drop Down</label>
			</div></td>
			</tr>
			<tr valign="top">
			<td><span style='font-weight:bold;'>Comment Field?</span></td>
			<td colspan="3"><div id="edit_comments">
				<input type="radio" id="editCommentRadio1" name="edit_comments" value=0 /><label for="editCommentRadio1">Small Text Field</label>
				<input type="radio" id="editCommentRadio3" name="edit_comments" value=2 /><label for="editCommentRadio3">Large Text Field</label>
				<input type="radio" id="editCommentRadio2" name="edit_comments" value=1 /><label for="editCommentRadio2">None</label>
			</div></td>
			</tr>
			<tr valign="top">
			<td><span style='font-weight:bold;'>Question Order</span></td>
			<td>
			<input type="number" step="1" min="1" name="edit_question_order" value="" id="edit_question_order" style="border-color:#000000;
				color:#3300CC; 
				cursor:hand;"/>
			</td>
			</tr>
			</table>
			<?php
			echo "<p class='submit'><input type='submit' class='button-primary' value='Edit Question' /></p>";
			echo "</form>";
			?>
			</div>	

  		</div>
  		<div id="tabs-2">
			<h3>Template Variables</h3>
			<table class="form-table">
			<tr>
				<td><strong>%POINT_SCORE%</strong> - Score for the quiz when using points</td>
				<td><strong>%AVERAGE_POINT%</strong> - The average amount of points user had per question</td>
			</tr>
	
			<tr>
				<td><strong>%AMOUNT_CORRECT%</strong> - The number of correct answers the user had</td>
				<td><strong>%TOTAL_QUESTIONS%</strong> - The total number of questions in the quiz</td>
			</tr>
			
			<tr>
				<td><strong>%CORRECT_SCORE%</strong> - Score for the quiz when using correct answers</td>
			</tr>
	
			<tr>
				<td><strong>%USER_NAME%</strong> - The name the user entered before the quiz</td>
				<td><strong>%USER_BUSINESS%</strong> - The business the user entered before the quiz</td>
			</tr>
			
			<tr>
				<td><strong>%USER_PHONE%</strong> - The phone number the user entered before the quiz</td>
				<td><strong>%USER_EMAIL%</strong> - The email the user entered before the quiz</td>
			</tr>
			
			<tr>
				<td><strong>%QUIZ_NAME%</strong> - The name of the quiz</td>
				<td><strong>%QUESTIONS_ANSWERS%</strong> - Shows the question, the answer the user provided, and the correct answer</td>
			</tr>
			
			<tr>
				<td><strong>%COMMENT_SECTION%</strong> - The comments the user entered into comment box if enabled</td>
				<td><strong>%QUESTION%</strong> - The question that the user answered</td>
			</tr>
			
			<tr>
				<td><strong>%USER_ANSWER%</strong> - The answer the user gave for the question</td>
				<td><strong>%CORRECT_ANSWER%</strong> - The correct answer for the question</td>
			</tr>
			
			<tr>
				<td><strong>%USER_COMMENTS%</strong> - The comments the user provided in the comment field for the question</td>
				<td><strong>%CORRECT_ANSWER_INFO%</strong> - Reason why the correct answer is the correct answer</td>
			</tr>
			</table>
			<button id="save_template_button" onclick="javascript: document.quiz_template_form.submit();">Save Templates</button><button id="template_tab_help">Help</button>
			<?php
			echo "<form action='' method='post' name='quiz_template_form'>";
			echo "<input type='hidden' name='save_templates' value='confirmation' />";
			echo "<input type='hidden' name='quiz_id' value='".$quiz_id."' />";
			?>
    			<div id="accordion">
			<h3><a href="#">Message Template</a></h3>
			<div>
			<table class="form-table">
				<tr>
					<td width="30%">
						<strong>Message Displayed Before Quiz</strong>
						<br />
						<p>Allowed Variables: </p>
						<p style="margin: 2px 0">- %QUIZ_NAME%</p>
					</td>
					<td><textarea cols="80" rows="15" id="mlw_quiz_before_message" name="mlw_quiz_before_message"><?php echo $mlw_quiz_options->message_before; ?></textarea>
					</td>
				</tr>
				<tr>
					<td width="30%">
						<strong>Message Displayed Before Comments Box If Enabled</strong>
						<br />
						<p>Allowed Variables: </p>
						<p style="margin: 2px 0">- %QUIZ_NAME%</p>
					</td>
					<td><textarea cols="80" rows="15" id="mlw_quiz_before_comments" name="mlw_quiz_before_comments"><?php echo $mlw_quiz_options->message_comment; ?></textarea>
					</td>
				</tr>
				<tr>
					<td width="30%">
						<strong>Message Displayed After Quiz</strong>
						<br />
						<p>Allowed Variables: </p>
						<p style="margin: 2px 0">- %POINT_SCORE%</p>
						<p style="margin: 2px 0">- %AVERAGE_POINT%</p>
						<p style="margin: 2px 0">- %AMOUNT_CORRECT%</p>
						<p style="margin: 2px 0">- %TOTAL_QUESTIONS%</p>
						<p style="margin: 2px 0">- %CORRECT_SCORE%</p>
						<p style="margin: 2px 0">- %QUIZ_NAME%</p>
						<p style="margin: 2px 0">- %USER_NAME%</p>
						<p style="margin: 2px 0">- %USER_BUSINESS%</p>
						<p style="margin: 2px 0">- %USER_PHONE%</p>
						<p style="margin: 2px 0">- %USER_EMAIL%</p>
						<p style="margin: 2px 0">- %COMMENT_SECTION%</p>
						<p style="margin: 2px 0">- %QUESTIONS_ANSWERS%</p>
					</td>
					<td><textarea cols="80" rows="15" id="mlw_quiz_after_message" name="mlw_quiz_after_message"><?php echo $mlw_quiz_options->message_after; ?></textarea>
					</td>
				</tr>
				<tr>
					<td width="30%">
						<strong>Message Displayed If User Has Tried Quiz Too Many Times</strong>
						<br />
						<p>Allowed Variables: </p>
						<p style="margin: 2px 0">- %QUIZ_NAME%</p>
					</td>
					<td><textarea cols="80" rows="15" id="mlw_quiz_total_user_tries_text" name="mlw_quiz_total_user_tries_text"><?php echo $mlw_quiz_options->total_user_tries_text; ?></textarea>
					</td>
				</tr>
			</table>

			</div>
			<h3><a href="#">Email Template</a></h3>
			<div>
			<table class="form-table">
				<tr>
					<td width="30%">
						<strong>Email sent to user after completion (If turned on in options)</strong>
						<br />
						<p>Allowed Variables: </p>
						<p style="margin: 2px 0">- %POINT_SCORE%</p>
						<p style="margin: 2px 0">- %AVERAGE_POINT%</p>
						<p style="margin: 2px 0">- %AMOUNT_CORRECT%</p>
						<p style="margin: 2px 0">- %TOTAL_QUESTIONS%</p>
						<p style="margin: 2px 0">- %CORRECT_SCORE%</p>
						<p style="margin: 2px 0">- %QUIZ_NAME%</p>
						<p style="margin: 2px 0">- %USER_NAME%</p>
						<p style="margin: 2px 0">- %USER_BUSINESS%</p>
						<p style="margin: 2px 0">- %USER_PHONE%</p>
						<p style="margin: 2px 0">- %USER_EMAIL%</p>
						<p style="margin: 2px 0">- %COMMENT_SECTION%</p>
						<p style="margin: 2px 0">- %QUESTIONS_ANSWERS%</p>
					</td>
					<td><textarea cols="80" rows="15" id="mlw_quiz_user_email_template" name="mlw_quiz_user_email_template"><?php echo $mlw_quiz_options->user_email_template; ?></textarea>
					</td>
				</tr>
				<tr>
					<td width="30%">
						<strong>Email sent to admin after completion (If turned on in options)</strong>
						<br />
						<p>Allowed Variables: </p>
						<p style="margin: 2px 0">- %POINT_SCORE%</p>
						<p style="margin: 2px 0">- %AVERAGE_POINT%</p>
						<p style="margin: 2px 0">- %AMOUNT_CORRECT%</p>
						<p style="margin: 2px 0">- %TOTAL_QUESTIONS%</p>
						<p style="margin: 2px 0">- %CORRECT_SCORE%</p>
						<p style="margin: 2px 0">- %USER_NAME%</p>
						<p style="margin: 2px 0">- %USER_BUSINESS%</p>
						<p style="margin: 2px 0">- %USER_PHONE%</p>
						<p style="margin: 2px 0">- %USER_EMAIL%</p>
						<p style="margin: 2px 0">- %QUIZ_NAME%</p>
						<p style="margin: 2px 0">- %COMMENT_SECTION%</p>
						<p style="margin: 2px 0">- %QUESTIONS_ANSWERS%</p>
					</td>
					<td><textarea cols="80" rows="15" id="mlw_quiz_admin_email_template" name="mlw_quiz_admin_email_template"><?php echo $mlw_quiz_options->admin_email_template; ?></textarea>
					</td>
				</tr>
			</table>

			</div>
			<h3><a href="#">Other Template</a></h3>
			<div>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="mlw_submitText">Text for submit button</label></th>
					<td><input name="mlw_submitText" type="text" id="mlw_submitText" value="<?php echo $mlw_quiz_options->submit_button_text; ?>" class="regular-text" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="mlw_nameText">Text for name field</label></th>
					<td><input name="mlw_nameText" type="text" id="mlw_nameText" value="<?php echo $mlw_quiz_options->name_field_text; ?>" class="regular-text" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="mlw_businessText">Text for business field</label></th>
					<td><input name="mlw_businessText" type="text" id="mlw_businessText" value="<?php echo $mlw_quiz_options->business_field_text; ?>" class="regular-text" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="mlw_emailText">Text for email field</label></th>
					<td><input name="mlw_emailText" type="text" id="mlw_emailText" value="<?php echo $mlw_quiz_options->email_field_text; ?>" class="regular-text" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="mlw_phoneText">Text for phone number field</label></th>
					<td><input name="mlw_phoneText" type="text" id="mlw_phoneText" value="<?php echo $mlw_quiz_options->phone_field_text; ?>" class="regular-text" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="mlw_commentText">Text for comments field</label></th>
					<td><input name="mlw_commentText" type="text" id="mlw_commentText" value="<?php echo $mlw_quiz_options->comment_field_text; ?>" class="regular-text" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="emailFromText">What is the From Name for the email sent to users and admin?</label></th>
					<td><input name="emailFromText" type="text" id="emailFromText" value="<?php echo $mlw_quiz_options->email_from_text; ?>" class="regular-text" /></td>
				</tr>
			</table>
			<table class="form-table">
				<tr>
					<td width="30%">
						<strong>%QUESTIONS_ANSWERS% Text</strong>
						<br />
						<p>Allowed Variables: </p>
						<p style="margin: 2px 0">- %QUESTION%</p>
						<p style="margin: 2px 0">- %USER_ANSWER%</p>
						<p style="margin: 2px 0">- %CORRECT_ANSWER%</p>
						<p style="margin: 2px 0">- %USER_COMMENTS%</p>
						<p style="margin: 2px 0">- %CORRECT_ANSWER_INFO%</p>
					</td>
					<td><textarea cols="80" rows="15" id="mlw_quiz_question_answer_template" name="mlw_quiz_question_answer_template"><?php echo $mlw_quiz_options->question_answer_template; ?></textarea>
					</td>
				</tr>
			</table>
			
			</div>
			</div>
			<button id="save_template_button" onclick="javascript: document.quiz_template_form.submit();">Save Templates</button>
			<?php echo "</form>"; ?>
  		</div>
  		<div id="tabs-3">
		<button id="save_options_button" onclick="javascript: document.quiz_options_form.submit();">Save Options</button><button id="options_tab_help">Help</button>
		<?php
		echo "<form action='' method='post' name='quiz_options_form'>";
		echo "<input type='hidden' name='save_options' value='confirmation' />";
		echo "<input type='hidden' name='quiz_id' value='".$quiz_id."' />";
		?>
		<table class="form-table" style="width: 100%;">
			<tr valign="top">
				<th scope="row"><label for="system">Which system is this quiz graded on?</label></th>
				<td><div id="system">
				    <input type="radio" id="radio1" name="system" <?php if ($mlw_quiz_options->system == 0) {echo 'checked="checked"';} ?> value='0' /><label for="radio1">Correct/Incorrect</label>
				    <input type="radio" id="radio2" name="system" <?php if ($mlw_quiz_options->system == 1) {echo 'checked="checked"';} ?> value='1' /><label for="radio2">Points</label>
				    <input type="radio" id="radio3" name="system" <?php if ($mlw_quiz_options->system == 2) {echo 'checked="checked"';} ?> value='2' /><label for="radio3">Not Graded</label>
				</div></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="question_from_total">How many questions should be loaded for quiz? (Leave 0 to load all questions)</label></th>
				<td>
				    <input name="question_from_total" type="number" step="1" min="0" id="question_from_total" value="<?php echo $mlw_quiz_options->question_from_total; ?>" class="regular-text" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="randomness_order">Are the questions random? (Question Order will not apply if this is yes)</label></th>
				<td><div id="randomness_order">
				    <input type="radio" id="radio23" name="randomness_order" <?php if ($mlw_quiz_options->randomness_order == 0) {echo 'checked="checked"';} ?> value='0' /><label for="radio23">No</label>
				    <input type="radio" id="radio24" name="randomness_order" <?php if ($mlw_quiz_options->randomness_order == 1) {echo 'checked="checked"';} ?> value='1' /><label for="radio24">Yes</label>
				</div></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="total_user_tries">How many times can a user take this quiz? (Leave 0 for as many times as the user wants to. Currently only works for registered users)</label></th>
				<td>
				    <input name="total_user_tries" type="number" step="1" min="0" id="total_user_tries" value="<?php echo $mlw_quiz_options->total_user_tries; ?>" class="regular-text" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="contact_info_location">Would you like to ask for the contact information at the beginning or at the end of the quiz?</label></th>
				<td><div id="contact_info_location">
				    <input type="radio" id="radio25" name="contact_info_location" <?php if ($mlw_quiz_options->contact_info_location == 0) {echo 'checked="checked"';} ?> value='0' /><label for="radio25">Beginning</label>
				    <input type="radio" id="radio26" name="contact_info_location" <?php if ($mlw_quiz_options->contact_info_location == 1) {echo 'checked="checked"';} ?> value='1' /><label for="radio26">End</label>
				</div></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="loggedin_user_contact">If a logged-in user takes the quiz, would you like them to be able to edit contact information? If set to no, the fields will not show up for logged in users; however, the users information will be saved for the fields.</label></th>
				<td><div id="loggedin_user_contact">
				    <input type="radio" id="radio27" name="loggedin_user_contact" <?php if ($mlw_quiz_options->loggedin_user_contact == 0) {echo 'checked="checked"';} ?> value='0' /><label for="radio27">Yes</label>
				    <input type="radio" id="radio28" name="loggedin_user_contact" <?php if ($mlw_quiz_options->loggedin_user_contact == 1) {echo 'checked="checked"';} ?> value='1' /><label for="radio28">No</label>
				</div></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="userName">Should we ask for user's name?</label></th>
				<td><div id="userName">
				    <input type="radio" id="radio7" name="userName" <?php if ($mlw_quiz_options->user_name == 0) {echo 'checked="checked"';} ?> value='0' /><label for="radio7">Yes</label>
				    <input type="radio" id="radio8" name="userName" <?php if ($mlw_quiz_options->user_name == 1) {echo 'checked="checked"';} ?> value='1' /><label for="radio8">Require</label>
				    <input type="radio" id="radio9" name="userName" <?php if ($mlw_quiz_options->user_name == 2) {echo 'checked="checked"';} ?> value='2' /><label for="radio9">No</label>
				</div></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="userComp">Should we ask for user's business?</label></th>
				<td><div id="userComp">
				    <input type="radio" id="radio10" name="userComp" <?php if ($mlw_quiz_options->user_comp == 0) {echo 'checked="checked"';} ?> value='0' /><label for="radio10">Yes</label>
				    <input type="radio" id="radio11" name="userComp" <?php if ($mlw_quiz_options->user_comp == 1) {echo 'checked="checked"';} ?> value='1' /><label for="radio11">Require</label>
				    <input type="radio" id="radio12" name="userComp" <?php if ($mlw_quiz_options->user_comp == 2) {echo 'checked="checked"';} ?> value='2' /><label for="radio12">No</label>
				</div></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="userEmail">Should we ask for user's email?</label></th>
				<td><div id="userEmail">
				    <input type="radio" id="radio13" name="userEmail" <?php if ($mlw_quiz_options->user_email == 0) {echo 'checked="checked"';} ?> value='0' /><label for="radio13">Yes</label>
				    <input type="radio" id="radio14" name="userEmail" <?php if ($mlw_quiz_options->user_email == 1) {echo 'checked="checked"';} ?> value='1'/><label for="radio14">Require</label>
				    <input type="radio" id="radio15" name="userEmail" <?php if ($mlw_quiz_options->user_email == 2) {echo 'checked="checked"';} ?> value='2' /><label for="radio15">No</label>
				</div></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="userPhone">Should we ask for user's phone number?</label></th>
				<td><div id="userPhone">
				    <input type="radio" id="radio16" name="userPhone" <?php if ($mlw_quiz_options->user_phone == 0) {echo 'checked="checked"';} ?> value='0' /><label for="radio16">Yes</label>
				    <input type="radio" id="radio17" name="userPhone" <?php if ($mlw_quiz_options->user_phone == 1) {echo 'checked="checked"';} ?> value='1' /><label for="radio17">Require</label>
				    <input type="radio" id="radio18" name="userPhone" <?php if ($mlw_quiz_options->user_phone == 2) {echo 'checked="checked"';} ?> value='2' /><label for="radio18">No</label>
				</div></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="commentSection">Would you like a place for the user to enter comments?</label></th>
				<td><div id="commentSection">
				    <input type="radio" id="radio21" name="commentSection" <?php if ($mlw_quiz_options->comment_section == 0) {echo 'checked="checked"';} ?> value='0' /><label for="radio21">Yes</label>
				    <input type="radio" id="radio22" name="commentSection" <?php if ($mlw_quiz_options->comment_section == 1) {echo 'checked="checked"';} ?> value='1' /><label for="radio22">No</label>
				</div></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="sendUserEmail">Send user email upon completion?</label></th>
				<td><div id="sendUserEmail">
				    <input type="radio" id="radio5" name="sendUserEmail" <?php if ($mlw_quiz_options->send_user_email == 0) {echo 'checked="checked"';} ?> value='0' /><label for="radio5">Yes</label>
				    <input type="radio" id="radio6" name="sendUserEmail" <?php if ($mlw_quiz_options->send_user_email == 1) {echo 'checked="checked"';} ?> value='1' /><label for="radio6">No</label>
				</div></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="sendAdminEmail">Send admin email upon completion?</label></th>
				<td><div id="sendAdminEmail">
				    <input type="radio" id="radio19" name="sendAdminEmail" <?php if ($mlw_quiz_options->send_admin_email == 0) {echo 'checked="checked"';} ?> value='0' /><label for="radio19">Yes</label>
				    <input type="radio" id="radio20" name="sendAdminEmail" <?php if ($mlw_quiz_options->send_admin_email == 1) {echo 'checked="checked"';} ?> value='1' /><label for="radio20">No</label>
				</div></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="adminEmail">What email should we send the admin email to?</label></th>
				<td><input name="adminEmail" type="email" id="adminEmail" value="<?php echo $mlw_quiz_options->admin_email; ?>" class="regular-text" /></td>
			</tr>
		</table>
		<button id="save_options_button" onclick="javascript: document.quiz_options_form.submit();">Save Options</button>
		<?php echo "</form>"; ?>
  		</div>
	<div id="tabs-4">
		<h3>Template Variables</h3>
		<table class="form-table">
			<tr>
				<td><strong>%FIRST_PLACE_NAME%</strong> - The name of the user who is in first place</td>
				<td><strong>%FIRST_PLACE_SCORE%</strong> - The score from the first place's quiz</td>
			</tr>
		
			<tr>
				<td><strong>%SECOND_PLACE_NAME%</strong> - The name of the user who is in second place</td>
				<td><strong>%SECOND_PLACE_SCORE%</strong> - The score from the second place's quiz</td>
			</tr>
		
			<tr>
				<td><strong>%THIRD_PLACE_NAME%</strong> - The name of the user who is in third place</td>
				<td><strong>%THIRD_PLACE_SCORE%</strong> - The score from the third place's quiz</td>
			</tr>
			
			<tr>
				<td><strong>%FOURTH_PLACE_NAME%</strong> - The name of the user who is in fourth place</td>
				<td><strong>%FOURTH_PLACE_SCORE%</strong> - The score from the fourth place's quiz</td>
			</tr>
			
			<tr>
				<td><strong>%FIFTH_PLACE_NAME%</strong> - The name of the user who is in fifth place</td>
				<td><strong>%FIFTH_PLACE_SCORE%</strong> - The score from the fifth place's quiz</td>
			</tr>
			
			<tr>
				<td><strong>%QUIZ_NAME%</strong> - The name of the quiz</td>
			</tr>
		</table>
		<button id="save_template_button" onclick="javascript: document.quiz_leaderboard_options_form.submit();">Save Leaderboard Options</button><button id="leaderboard_tab_help">Help</button>
		<?php
			echo "<form action='' method='post' name='quiz_leaderboard_options_form'>";
			echo "<input type='hidden' name='save_leaderboard_options' value='confirmation' />";
			echo "<input type='hidden' name='leaderboard_quiz_id' value='".$quiz_id."' />";
		?>
    	<table class="form-table">
			<tr>
				<td width="30%">
					<strong>Leaderboard Template</strong>
					<br />
					<p>Allowed Variables: </p>
					<p style="margin: 2px 0">- %QUIZ_NAME%</p>
					<p style="margin: 2px 0">- %FIRST_PLACE_NAME%</p>
					<p style="margin: 2px 0">- %FIRST_PLACE_SCORE%</p>
					<p style="margin: 2px 0">- %SECOND_PLACE_NAME%</p>
					<p style="margin: 2px 0">- %SECOND_PLACE_SCORE%</p>
					<p style="margin: 2px 0">- %THIRD_PLACE_NAME%</p>
					<p style="margin: 2px 0">- %THIRD_PLACE_SCORE%</p>
					<p style="margin: 2px 0">- %FOURTH_PLACE_NAME%</p>
					<p style="margin: 2px 0">- %FOURTH_PLACE_SCORE%</p>
					<p style="margin: 2px 0">- %FIFTH_PLACE_NAME%</p>
					<p style="margin: 2px 0">- %FIFTH_PLACE_SCORE%</p>
				</td>
				<td><textarea cols="80" rows="15" id="mlw_quiz_leaderboard_template" name="mlw_quiz_leaderboard_template"><?php echo $mlw_quiz_options->leaderboard_template; ?></textarea>
				</td>
			</tr>
		</table>
		<button id="save_template_button" onclick="javascript: document.quiz_leaderboard_options_form.submit();">Save Leaderboard Options</button>
		</form>
	</div>
	<div id="tabs-5">
		<p>Use this button to reset all the stats collected for this quiz (Quiz Views and Times Quiz Has Been Taken). </p>
		<button id="mlw_reset_stats_button">Reset Quiz Views And Taken Stats</button>
		<div id="mlw_reset_stats_dialog" title="Reset Stats For This Quiz" style="display:none;">
		<p>Are you sure you want to reset the stats to 0? All views and taken stats for this quiz will be reset. This is permanent and cannot be undone.</p>
		<?php
			echo "<form action='' method='post'>";
			echo "<input type='hidden' name='mlw_reset_quiz_stats' value='confirmation' />";
			echo "<input type='hidden' name='mlw_reset_quiz_id' value='".$quiz_id."' />";
			echo "<p class='submit'><input type='submit' class='button-primary' value='Reset All Stats For Quiz' /></p>";
			echo "</form>";
		?>
		</div>		
	</div>
	</div>

	<div id="delete_dialog" title="Delete Question?" style="display:none;">
	<h3><b>Are you sure you want to delete Question <span id="delete_question_id"></span>?</b></h3>
	<?php
	echo "<form action='' method='post'>";
	echo "<input type='hidden' name='delete_question' value='confirmation' />";
	echo "<input type='hidden' id='question_id' name='question_id' value='' />";
	echo "<input type='hidden' name='quiz_id' value='".$quiz_id."' />";
	echo "<p class='submit'><input type='submit' class='button-primary' value='Delete Question' /></p>";
	echo "</form>";	
	?>
	</div>
	<div id="dialog" title="Help" style="display:none;">
	<h3><b>Help</b></h3>
	<p>This page is used edit the questions and options for your quiz.  Use the help buttons on each tab for assistance.</p>
	</div>
	<div id="questions_help_dialog" title="Help" style="display:none;">
	<p>The question table lists the order the question appears in and the question itself.</p>
	<p>To edit a question, use the Edit link below the question.</p>
	<p>To add a question, click on the Add Question button. This will open a window for you to add a question. The window will ask for the question and up to 6 answers. If you are using the points system, enter in the amount of points each answer is worth. If you are using the correct system, check the answer that is the correct answer. 
	You can then choose which style of question you would like by selecting an option for the "Question Type?" option. You can choose if you would like a comment field after the question by selecting an option to the "Comment Field?" question. You can also have a hint displayed to the user. You can then choose the order which the question is 
	asked by editing the "Question Order" option. Click create question when you are finished.</p>
	</div>
	<div id="templates_help_dialog" title="Help" style="display:none;">
	<p>This tab is used to edit the different messages the user and admin may see.</p>
	<p>The Message Displayed Before Quiz text is shown to the user at the beginning of the quiz.</p>
	<p>The Message Display Before Comment Box is shown to the user right before the section the user can type in comments if that option is enabled.</p>
	<p>The Message Displayed After Quiz text is show to the user after the quiz has been taken.</p>
	<p>The Email sent to user after completion text is the email that is sent to the user after completing the quiz. (This is only used if you have turned on the option on the options tab.)</p>
	<p>The Email sent to admin after completion text is the email that is sent to the admin after the quiz has been completed.</p>
	<p>The other templates section is for customizing the text on the submit button as well as the fields where are user can input his or her information.</p>
	<p>The %QUESTIONS_ANSWERS% Text area is where you can change the test shown in place of the %QUESTIONS_ANSWERS% variable.</p>
	<p>Some templates are able to have variables inside the text. When the quiz is run, these variables will change to their values.</p>
	</div>
	<div id="options_help_dialog" title="Help" style="display:none;">
	<p>This tab is used to edit the different options for the quiz.</p>
	<p>The system option allows you to have the quiz be graded using a correct/incorrect system or the quiz can have each answer worth different amount of points.</p>
	<p>Are the questions random? -> If set to yes, the questions will be random. If set to no, the questions will be shown in the order you have set using the Question Order option.</p>
	<p>Would you like to ask for the contact information at the beginning or at the end of the quiz? -> This option will allow you to choose when to ask for contact information if asked.</p>
	<p>Should we ask for -> The next four options asks whether you want the quiz to ask for the user's name, business, email, and phone number.</p>
	<p>Would you like a place for the user to enter comments? -> If set to yes, a comment section will appear at the end of the quiz for the user to fill out. Customize the text shown above the field by editing 
	the "Message Display Before Comment Box" field on the "Quiz Text" tab.</p>
	<p>Send user email upon completion?-> If set to yes, the user will be sent an email after taking the quiz. To customize the text of the email, edit the "Email sent to user after completion" 
	field on the "Quiz Text" tab.</p>
	<p>Send admin email upon completion? -> If set to yes, the admin will be sent an email when a quiz has been taken. To customize the text of the email, edit the "Email sent to admin after completion" 
	field on the "Quiz Text" tab.</p>
	<p>What email should we send the admin email to? -> This field allows you to set what email address to send the admin emails to.</p>
	</div>
	<div id="leaderboard_help_dialog" title="Help" style="display:none;">
	<p>This tab is used to edit the options for the leaderboard for this quiz.</p>
	<p>Currently, you can edit the template for the leaderboard.</p>
	<p>The template is able to have variables inside the text. When the quiz is run, these variables will change to their values.</p>
	</div>

	<?php
	}
	else
	{
	?>
	<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
	<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
	<strong>Hey!</strong> Please go to the quizzes page and click on the Edit link from the quiz you wish to edit.</p>
	<?php
	}
	?>
	</div>
	</div>
<?php
}
?>