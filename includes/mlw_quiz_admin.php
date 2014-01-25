<?php
/*
This page lists all the quizzes currently on the website and allows you to create more quizzes.
*/
/* 
Copyright 2013, My Local Webstop (email : fpcorso@mylocalwebstop.com)
*/

function mlw_generate_quiz_admin()
{
	global $wpdb;
	$table_name = $wpdb->prefix . "mlw_quizzes";
	$success = $_POST["create_quiz"];
	$quiz_name = $_POST["quiz_name"];
	$hasCreatedQuiz = false;
	$hasDeletedQuiz = false;

	//Create new quiz
	if ($success == "confirmation")
	{
		//Insert New Quiz Into Table
		$mlw_leaderboard_default = "<h3>Leaderboard for %QUIZ_NAME%</h3>
			1. %FIRST_PLACE_NAME%-%FIRST_PLACE_SCORE%<br />
			2. %SECOND_PLACE_NAME%-%SECOND_PLACE_SCORE%<br />
			3. %THIRD_PLACE_NAME%-%THIRD_PLACE_SCORE%<br />
			4. %FOURTH_PLACE_NAME%-%FOURTH_PLACE_SCORE%<br />
			5. %FIFTH_PLACE_NAME%-%FIFTH_PLACE_SCORE%<br />";
		$mlw_question_answer_default = "%QUESTION%<br /> Answer Provided: %USER_ANSWER%<br /> Correct Answer: %CORRECT_ANSWER%<br /> Comments Entered: %USER_COMMENTS%<br />";
		$insert = "INSERT INTO " . $table_name .
			"(quiz_id, quiz_name, message_before, message_after, message_comment, user_email_template, admin_email_template, submit_button_text, name_field_text, business_field_text, email_field_text, phone_field_text, comment_field_text, question_answer_template, leaderboard_template, system, randomness_order, show_score, send_user_email, send_admin_email, user_name, user_comp, user_email, user_phone, admin_email, comment_section, quiz_views, quiz_taken, deleted) " .
			"VALUES (NULL , '" . $quiz_name . "' , 'Enter your text here', 'Enter your text here', 'Enter your text here', 'Enter your text here', 'Enter your text here', 'Submit Quiz', 'Name', 'Business', 'Email', 'Phone Number', 'Comments', '".$mlw_question_answer_default."', '".$mlw_leaderboard_default."', 0, 0, 0, 0, 0, 0, 0, 0, 0, '".get_option( 'admin_email', 'Enter email' )."', 0, 0, 0, 0)";
		$results = $wpdb->query( $insert );
		$hasCreatedQuiz = true;
		
		//Insert Action Into Audit Trail
		global $current_user;
		get_currentuserinfo();
		$table_name = $wpdb->prefix . "mlw_qm_audit_trail";
		$insert = "INSERT INTO " . $table_name .
			"(trail_id, action_user, action, time) " .
			"VALUES (NULL , '" . $current_user->display_name . "' , 'New Quiz Has Been Created: ".$quiz_name."' , '" . date("h:i:s A m/d/Y") . "')";
		$results = $wpdb->query( $insert );
	}

	//Variables from delete question form
	$delete_quiz_success = $_POST["delete_quiz"];
	$mlw_quiz_id = $_POST["quiz_id"];
	$quiz_name = $_POST["delete_quiz_name"];

	//Delete quiz
	if ($delete_quiz_success == "confirmation")
	{
		$quiz_id = $_POST["quiz_id"];
		$update = "UPDATE " . $wpdb->prefix . "mlw_quizzes" . " SET deleted=1 WHERE quiz_id=".$mlw_quiz_id;
		$results = $wpdb->query( $update );
		$update = "UPDATE " . $wpdb->prefix . "mlw_questions" . " SET deleted=1 WHERE quiz_id=".$mlw_quiz_id;
		$results = $wpdb->query( $update );
		$hasDeletedQuiz = true;
		
		//Insert Action Into Audit Trail
		global $current_user;
		get_currentuserinfo();
		$table_name = $wpdb->prefix . "mlw_qm_audit_trail";
		$insert = "INSERT INTO " . $table_name .
			"(trail_id, action_user, action, time) " .
			"VALUES (NULL , '" . $current_user->display_name . "' , 'Quiz Has Been Deleted: ".$quiz_name."' , '" . date("h:i:s A m/d/Y") . "')";
		$results = $wpdb->query( $insert );
	}	

	//Edit Quiz Name
	if ($_POST["quiz_name_editted"] == "confirmation")
	{
		$mlw_edit_quiz_id = $_POST["edit_quiz_id"];
		$mlw_edit_quiz_name = $_POST["edit_quiz_name"];
		$mlw_update_quiz_table = "UPDATE " . $wpdb->prefix . "mlw_quizzes" . " SET quiz_name='".$mlw_edit_quiz_name."' WHERE quiz_id=".$mlw_edit_quiz_id;
		$results = $wpdb->query( $mlw_update_quiz_table );
		$hasUpdatedQuizName = true;
		
		//Insert Action Into Audit Trail
		global $current_user;
		get_currentuserinfo();
		$table_name = $wpdb->prefix . "mlw_qm_audit_trail";
		$insert = "INSERT INTO " . $table_name .
			"(trail_id, action_user, action, time) " .
			"VALUES (NULL , '" . $current_user->display_name . "' , 'Quiz Name Has Been Edited: ".$mlw_edit_quiz_name."' , '" . date("h:i:s A m/d/Y") . "')";
		$results = $wpdb->query( $insert );
		
		
	}

	//Retrieve list of quizzes
	global $wpdb;
	$sql = "SELECT quiz_id, quiz_name, quiz_views, quiz_taken
		FROM " . $wpdb->prefix . "mlw_quizzes WHERE deleted='0'";
	$sql .= "ORDER BY quiz_id DESC";

	$mlw_quiz_data = $wpdb->get_results($sql);
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
			$j('#new_quiz_dialog').dialog({
				autoOpen: false,
				show: 'blind',
				width:700,
				hide: 'explode',
				buttons: {
				Cancel: function() {
					$j(this).dialog('close');
					}
				}
			});
		
			$j('#new_quiz_button').click(function() {
				$j('#new_quiz_dialog').dialog('open');
				return false;
		}	);
			$j('#new_quiz_button_two').click(function() {
				$j('#new_quiz_dialog').dialog('open');
				return false;
		}	);
		});
		function deleteQuiz(id,quizName){
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
			var idText = document.getElementById("delete_quiz_id");
			var idHidden = document.getElementById("quiz_id");
			var idHiddenName = document.getElementById("delete_quiz_name");
			idText.innerHTML = id;
			idHidden.value = id;
			idHiddenName.value = quizName;
		};
		function editQuizName(id, quizName){
			$j("#edit_dialog").dialog({
				autoOpen: false,
				show: 'blind',
				hide: 'explode',
				buttons: {
				Cancel: function() {
					$j(this).dialog('close');
					}
				}
			});
			$j("#edit_dialog").dialog('open');
			document.getElementById("edit_quiz_name").value = quizName;
			document.getElementById("edit_quiz_id"). value = id;			
		}
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
	<h2>Quizzes<a id="opener" href="">(?)</a></h2>
	<?php if ($hasCreatedQuiz)
		{
	?>
		<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
		<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
		<strong>Hey!</strong> Your new quiz has been created successfully. To begin editing options and adding questions to your quiz, click on the edit link for that quiz.</p>
	</div>
	<?php
		}
	?>
	<?php if ($hasDeletedQuiz)
		{
	?>
		<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
		<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
		<strong>Hey!</strong> The quiz has been deleted.</p>
	</div>
	<?php
		}
	?>
	<?php if ($hasUpdatedQuizName)
		{
	?>
		<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
		<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
		<strong>Hey!</strong> The quiz name has been updated.</p>
	</div>
	<?php
		}
	?>
	<button id="new_quiz_button_two">Create New Quiz</button>
	<?php 
	$quotes_list = "";
	$display = "";
	foreach($mlw_quiz_data as $mlw_quiz_info) {
		if($alternate) $alternate = "";
		else $alternate = " class=\"alternate\"";
		$quotes_list .= "<tr{$alternate}>";
		$quotes_list .= "<td><span style='font-size:16px;'>" . $mlw_quiz_info->quiz_id . "</span></td>";
		$quotes_list .= "<td class='post-title column-title'><span style='font-size:16px;'>" . $mlw_quiz_info->quiz_name ." </span><span style='color:green;font-size:12px;'><a onclick=\"editQuizName('".$mlw_quiz_info->quiz_id."','".$mlw_quiz_info->quiz_name."')\" href='#'>(Edit Name)</a></span><div><span style='color:green;font-size:12px;'><a href='admin.php?page=mlw_quiz_options&&quiz_id=".$mlw_quiz_info->quiz_id."'>Edit</a> | <a onclick=\"deleteQuiz('".$mlw_quiz_info->quiz_id."','".$mlw_quiz_info->quiz_name."')\" href='#'>Delete</a> | <a href='admin.php?page=mlw_quiz_results&&quiz_id=".$mlw_quiz_info->quiz_id."'>Results</a></span></div></td>";
		$quotes_list .= "<td><span style='font-size:16px;'>[mlw_quizmaster quiz=".$mlw_quiz_info->quiz_id."]</span></td>";
		$quotes_list .= "<td><span style='font-size:16px;'>[mlw_quizmaster_leaderboard mlw_quiz=".$mlw_quiz_info->quiz_id."]</span></td>";
		$quotes_list .= "<td><span style='font-size:16px;'>" . $mlw_quiz_info->quiz_views . "</span></td>";
		$quotes_list .= "<td><span style='font-size:16px;'>" . $mlw_quiz_info->quiz_taken ."</span></td>";
		$quotes_list .= "</tr>";
	}

	$display .= "<table class=\"widefat\">";
		$display .= "<thead><tr>
			<th>Quiz ID</th>
			<th>Quiz Name</th>
			<th>Quiz Shortcode</th>
			<th>Leaderboard Shortcode</th>
			<th>Quiz Views</th>
			<th>Quiz Taken</th>
		</tr></thead>";
		$display .= "<tbody id=\"the-list\">{$quotes_list}</tbody>";
		$display .= "</table>";
	echo $display;
	?>

	<button id="new_quiz_button">Create New Quiz</button>
	<div id="new_quiz_dialog" title="Create New Quiz" style="display:none;">
	<table class="wide" style="text-align: left; white-space: nowrap;">
	<thead>
	
	<tr valign="top">
	<th scope="row">&nbsp;</th>
	<td></td>
	</tr>
	<?php
	echo "<form action='" . $PHP_SELF . "' method='post'>";
	echo "<input type='hidden' name='create_quiz' value='confirmation' />";
	?>
	
	
	<tr valign="top">
	<th scope="row"><h3>Create New Quiz</h3></th>
	<td></td>
	</tr>
	
	<tr valign="top">
	<th scope="row">Quiz Name</th>
	<td>
	<input type="text" name="quiz_name" value="" style="border-color:#000000;
		color:#3300CC; 
		cursor:hand;"/>
	</td>
	</tr>
	</thead>
	</table>
	<?php
	echo "<p class='submit'><input type='submit' class='button-primary' value='Create Quiz' /></p>";
	echo "</form>";
	?>
	</div>	
	<div id="edit_dialog" title="Edit Quiz Name" style="display:none;">
		<h3>Quiz Name:</h3><br />
		<?php
			echo "<form action='" . $PHP_SELF . "' method='post'>";
		?>
		<input type="text" id="edit_quiz_name" name="edit_quiz_name" />
		<input type="hidden" id="edit_quiz_id" name="edit_quiz_id" />
		<input type='hidden' name='quiz_name_editted' value='confirmation' />
		<input type="submit" class="button-primary" value="Edit" />
		</form>
	</div>
	<div id="delete_dialog" title="Delete Quiz?" style="display:none;">
	<h3><b>Are you sure you want to delete Quiz <span id="delete_quiz_id"></span>?</b></h3>
	<?php
	echo "<form action='" . $PHP_SELF . "' method='post'>";
	echo "<input type='hidden' name='delete_quiz' value='confirmation' />";
	echo "<input type='hidden' id='quiz_id' name='quiz_id' value='' />";
	echo "<input type='hidden' id='delete_quiz_name' name='delete_quiz_name' value='' />";
	echo "<p class='submit'><input type='submit' class='button-primary' value='Delete Quiz' /></p>";
	echo "</form>";	
	?>
	</div>
	<div id="dialog" title="Help">
	<h3><b>Help</b></h3>
	<p>This page shows all of the quizzes currently on your website.</p>
	<p>The table shows the quiz id, the name of your quiz, the shortcode to use on your post or page to add the quiz, the shortcode to use on your post or page to add the leaderboard, the amount of views the quiz has had, and the amount of times the quiz was finished</p>
	<p>To create a new quiz, click the Create New Quiz button and fill out the name.</p>
	<p>To edit a quiz, click the Edit link underneath the name of the quiz.</p>
	<p>To edit a quiz's name, click the Edit Name link next to the name of the quiz.</p>
	<p>To delete a quiz, click the Delete link underneath the name of the quiz.</p>
	<p>To view the results of a quiz, click the Results link underneath the name of the quiz.</p>
	</div>	
	</div>
	</div>
<?php
}
?>