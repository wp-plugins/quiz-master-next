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
	$mlw_qmn_isAllowed = true;
	$mlw_qmn_section_count = 1;
	$mlw_qmn_section_limit = 0;


	//Load quiz
	$sql = "SELECT * FROM " . $wpdb->prefix . "mlw_quizzes" . " WHERE quiz_id=".$mlw_quiz_id." AND deleted='0'";
	$mlw_quiz_options = $wpdb->get_results($sql);

	foreach($mlw_quiz_options as $mlw_eaches) {
		$mlw_quiz_options = $mlw_eaches;
		break;
	}
	
	//Check to see if there is limit on the amount of tries
	if ( $mlw_quiz_options->total_user_tries != 0 && is_user_logged_in() )
	{
		$current_user = wp_get_current_user();
		$mlw_qmn_user_try_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM ".$wpdb->prefix."mlw_results WHERE email='%s' AND deleted='0' AND quiz_id=%d", $current_user->user_email, $mlw_quiz_id ) );
		if ($mlw_qmn_user_try_count >= $mlw_quiz_options->total_user_tries) { $mlw_qmn_isAllowed = false; }
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
	if ($mlw_quiz_options->question_from_total != 0)
	{
		$sql .= " LIMIT ".$mlw_quiz_options->question_from_total;
	}
	$mlw_questions = $wpdb->get_results($sql);


	//Variables to load if quiz has been taken
	if (isset($_POST["complete_quiz"]) && $_POST["complete_quiz"] == "confirmation")
	{
		$mlw_success = $_POST["complete_quiz"];
		$mlw_user_name = isset($_POST["mlwUserName"]) ? $_POST["mlwUserName"] : 'None';
		$mlw_user_comp = isset($_POST["mlwUserComp"]) ? $_POST["mlwUserComp"] : 'None';
		$mlw_user_email = isset($_POST["mlwUserEmail"]) ? $_POST["mlwUserEmail"] : 'None';
		$mlw_user_phone = isset($_POST["mlwUserPhone"]) ? $_POST["mlwUserPhone"] : 'None';
		$mlw_qmn_timer = isset($_POST["timer"]) ? $_POST["timer"] : 0;
		$mlw_spam_email = $_POST["email"];
	}
	
	wp_enqueue_script( 'json2' );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-effects-core' );
	wp_enqueue_script( 'jquery-effects-slide' );
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
	if (!isset($_POST["complete_quiz"]) && $mlw_quiz_options->quiz_name != "" && $mlw_qmn_isAllowed)
	{
		$mlw_qmn_total_questions = 0;
		//Calculate number of pages if pagination is turned on
		if ($mlw_quiz_options->pagination != 0)
		{
			$mlw_qmn_section_limit = 2 + $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM " . $wpdb->prefix . "mlw_questions WHERE quiz_id=%d AND deleted=0", $mlw_quiz_id ) );
			if ($mlw_quiz_options->comment_section == 0)
			{
				$mlw_qmn_section_limit = $mlw_qmn_section_limit + 1;
			}
			
			//Gather text for pagination buttons
			$mlw_qmn_pagination_text = "";
			$mlw_qmn_pagination_text = @unserialize($mlw_quiz_options->pagination_text);
			if (!is_array($mlw_qmn_pagination_text)) {
		        $mlw_qmn_pagination_text = array('Previous', $mlw_quiz_options->pagination_text);
		    }
			?>
			<script type="text/javascript">
				var $j = jQuery.noConflict();
				$j(function() {
				$j( ".quiz_section" ).hide();
				$j( ".quiz_section" ).append( "<br />" );
				$j( ".quiz_section" ).not( ".quiz_begin" ).append( "<a class=\"mlw_qmn_quiz_link\" href='#' onclick=\"prevSlide();\"><?php echo $mlw_qmn_pagination_text[0]; ?></a>" );
				$j( ".quiz_section" ).not( ".quiz_end" ).append( "<a class=\"mlw_qmn_quiz_link\" href='#' onclick=\"nextSlide();\"><?php echo $mlw_qmn_pagination_text[1]; ?></a>" );
				window.mlw_quiz_slide = 0;
				window.mlw_quiz_total_slides = <?php echo $mlw_qmn_section_limit; ?>;
				nextSlide();
				});
				function nextSlide()
				{
					window.mlw_quiz_slide++;
				    if (window.mlw_quiz_slide == window.mlw_quiz_total_slides)
				    {
				        $j(".quiz_link").html("Submit");
				    } 
				    if (window.mlw_quiz_slide > window.mlw_quiz_total_slides)
				    {
				    	document.quizForm.submit();
				        exit();
				    }
				    y = window.mlw_quiz_slide-1;
				    $j( ".quiz_section.slide"+y ).hide();
				    $j( ".quiz_section.slide"+window.mlw_quiz_slide ).show( "slide", {direction: "right"}, 300 );
				    
				}
				function prevSlide()
				{
					window.mlw_quiz_slide--;
					if (window.mlw_quiz_slide == window.mlw_quiz_total_slides)
				    {
				        $j(".quiz_link").html("Submit");
				    } 
				    if (window.mlw_quiz_slide > window.mlw_quiz_total_slides)
				    {
				    	document.quizForm.submit();
				        exit();
				    }
				    y = window.mlw_quiz_slide+1;
				    $j( ".quiz_section.slide"+y ).hide();
				    $j( ".quiz_section.slide"+window.mlw_quiz_slide ).show( "slide", {direction: "left"}, 300 );				
				}
			</script>
			<style type="text/css">
				div.mlw_qmn_quiz input[type=submit],
				a.mlw_qmn_quiz_link
				{
					    border-radius: 4px;
					    position: relative;
					    background-image: linear-gradient(#fff,#dedede);
						background-color: #eee;
						border: #ccc solid 1px;
						color: #333;
						text-shadow: 0 1px 0 rgba(255,255,255,.5);
						box-sizing: border-box;
					    display: inline-block;
					    padding: 5px 5px 5px 5px;
   						margin: auto;
				}
			</style>
			<?php
		}
		if ($mlw_quiz_options->timer_limit != 0)
		{
			?>
			<div id="mlw_qmn_timer"></div>
			<script type="text/javascript">
				var minutes = <?php echo $mlw_quiz_options->timer_limit; ?>;
				window.amount = (minutes*60);
				window.titleText = window.document.title;
				document.getElementById("mlw_qmn_timer").innerHTML = minToSec(window.amount);
				window.counter=setInterval(timer, 1000); //1000 will  run it every 1 second
				function timer()
				{
					window.amount=window.amount-1;
				    document.getElementById("mlw_qmn_timer").innerHTML = minToSec(window.amount);
				    window.document.title = minToSec(window.amount) + " " + window.titleText;
				  	if (window.amount <= 0)
				  	{
				    	clearInterval(window.counter);
				    	document.quizForm.submit();
				     	return;
				  	}
				}
				function minToSec(amount)
				{
				    var minutes = Math.floor(amount/60);
				    var seconds = amount - (minutes * 60);
				    if (seconds == '0') 
				    { 
				        seconds = "00"; 
				    }
				    else if (seconds < 10)
				    {
				        seconds = '0' + seconds;
				    }
				    return minutes+":"+seconds;
				}
			</script>
			<style type="text/css">
				#mlw_qmn_timer {
					position:fixed;
					top:200px;
					right:0px;
					width:130px;
					color:#00CCFF;
					border-radius: 15px;
					background:#000000;
					text-align: center;
					padding: 15px 15px 15px 15px
				}
			</style>
			<?php
		}
		
		?>
		<script type="text/javascript">
			var myVar=setInterval("mlwQmnTimer();",1000);
	 		function mlwQmnTimer()
	 		{
	 			var x = +document.getElementById("timer").value;
	 			x = x + 1;
	 			document.getElementById("timer").value = x;
	 		}
	 		
		</script>
		<style type="text/css">
			div.mlw_qmn_quiz input[type=radio],
			div.mlw_qmn_quiz input[type=submit],
			div.mlw_qmn_quiz label {
				cursor: pointer;
			}
			div.mlw_qmn_quiz input:not([type=submit]):focus,
			div.mlw_qmn_quiz textarea:focus {
				background: #eaeaea;
			}
			div.mlw_qmn_quiz_section
			{
			
			}
		</style>
		<?php
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
		$mlw_display .= "<div class='mlw_qmn_quiz'>";
		$mlw_display .= "<form name='quizForm' action='' method='post' class='mlw_quiz_form' onsubmit='return mlw_validateForm()' >";
		$mlw_display .= "<div class='quiz_section  quiz_begin slide".$mlw_qmn_section_count."'>";
		$mlw_message_before = htmlspecialchars_decode($mlw_quiz_options->message_before, ENT_QUOTES);
		$mlw_message_before = str_replace( "%QUIZ_NAME%" , $mlw_quiz_options->quiz_name, $mlw_message_before);
		$mlw_message_before = str_replace( "%CURRENT_DATE%" , date("F jS Y"), $mlw_message_before);
		$mlw_display .= "<span>".$mlw_message_before."</span><br />";
		$mlw_display .= "<span name='mlw_error_message' id='mlw_error_message' style='color: red;'></span><br />";

		if ($mlw_quiz_options->contact_info_location == 0)
		{
			$mlw_display .= mlwDisplayContactInfo($mlw_quiz_options);
		}
		$mlw_display .= "</div>";
		
		//Display the questions
		foreach($mlw_questions as $mlw_question) {
			$mlw_qmn_section_count = $mlw_qmn_section_count + 1;
			$mlw_qmn_total_questions = $mlw_qmn_total_questions + 1;
			$mlw_display .= "<div class='quiz_section slide".$mlw_qmn_section_count."'>";
			$mlw_display .= "<span style='font-weight:bold;'>".htmlspecialchars_decode($mlw_question->question_name, ENT_QUOTES)."</span><br />";
			if ($mlw_question->question_type == 0)
			{
				if ($mlw_question->answer_one != "")
				{
					$mlw_display .= "<input type='radio' required name='question".$mlw_question->question_id."' id='question".$mlw_question->question_id."_one' value='1' /> <label for='question".$mlw_question->question_id."_one'>".htmlspecialchars_decode($mlw_question->answer_one, ENT_QUOTES)."</label>";
					$mlw_display .= "<br />";
				}
				if ($mlw_question->answer_two != "")
				{
					$mlw_display .= "<input type='radio' required name='question".$mlw_question->question_id."' id='question".$mlw_question->question_id."_two' value='2' /> <label for='question".$mlw_question->question_id."_two'>".htmlspecialchars_decode($mlw_question->answer_two, ENT_QUOTES)."</label>";
					$mlw_display .= "<br />";
				}
				if ($mlw_question->answer_three != "")
				{
					$mlw_display .= "<input type='radio' required name='question".$mlw_question->question_id."' id='question".$mlw_question->question_id."_three' value='3' /> <label for='question".$mlw_question->question_id."_three'>".htmlspecialchars_decode($mlw_question->answer_three, ENT_QUOTES)."</label>";
					$mlw_display .= "<br />";
				}
				if ($mlw_question->answer_four != "")
				{
					$mlw_display .= "<input type='radio' required name='question".$mlw_question->question_id."' id='question".$mlw_question->question_id."_four' value='4' /> <label for='question".$mlw_question->question_id."_four'>".htmlspecialchars_decode($mlw_question->answer_four, ENT_QUOTES)."</label>";
					$mlw_display .= "<br />";
				}
				if ($mlw_question->answer_five != "")
				{
					$mlw_display .= "<input type='radio' required name='question".$mlw_question->question_id."' id='question".$mlw_question->question_id."_five' value='5' /> <label for='question".$mlw_question->question_id."_five'>".htmlspecialchars_decode($mlw_question->answer_five, ENT_QUOTES)."</label>";
					$mlw_display .= "<br />";
				}
				if ($mlw_question->answer_six != "")
				{
					$mlw_display .= "<input type='radio' required name='question".$mlw_question->question_id."' id='question".$mlw_question->question_id."_six' value='6' /> <label for='question".$mlw_question->question_id."_six'>".htmlspecialchars_decode($mlw_question->answer_six, ENT_QUOTES)."</label>";
					$mlw_display .= "<br />";
				}
			}
			elseif ($mlw_question->question_type == 1)
			{
				if ($mlw_question->answer_one != "")
				{
					$mlw_display .= "<input type='radio' required name='question".$mlw_question->question_id."' value='1' />".htmlspecialchars_decode($mlw_question->answer_one, ENT_QUOTES);
				}
				if ($mlw_question->answer_two != "")
				{
					$mlw_display .= "<input type='radio' required name='question".$mlw_question->question_id."' value='2' />".htmlspecialchars_decode($mlw_question->answer_two, ENT_QUOTES);
				}
				if ($mlw_question->answer_three != "")
				{
					$mlw_display .= "<input type='radio' required name='question".$mlw_question->question_id."' value='3' />".htmlspecialchars_decode($mlw_question->answer_three, ENT_QUOTES);
				}
				if ($mlw_question->answer_four != "")
				{
					$mlw_display .= "<input type='radio' required name='question".$mlw_question->question_id."' value='4' />".htmlspecialchars_decode($mlw_question->answer_four, ENT_QUOTES);
				}
				if ($mlw_question->answer_five != "")
				{
					$mlw_display .= "<input type='radio' required name='question".$mlw_question->question_id."' value='5' />".htmlspecialchars_decode($mlw_question->answer_five, ENT_QUOTES);
				}
				if ($mlw_question->answer_six != "")
				{
					$mlw_display .= "<input type='radio' required name='question".$mlw_question->question_id."' value='6' />".htmlspecialchars_decode($mlw_question->answer_six, ENT_QUOTES);
				}
				$mlw_display .= "<br />";
			}
			else
			{
				$mlw_display .= "<select required name='question".$mlw_question->question_id."'>";
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
				$mlw_display .= "<input type='text' x-webkit-speech id='mlwComment".$mlw_question->question_id."' name='mlwComment".$mlw_question->question_id."' value='".esc_attr(htmlspecialchars_decode($mlw_quiz_options->comment_field_text, ENT_QUOTES))."' onclick='clear_field(this)'/>";
				$mlw_display .= "<br />";
			}
			if ($mlw_question->comments == 2)
			{
				$mlw_display .= "<textarea cols='70' rows='5' id='mlwComment".$mlw_question->question_id."' name='mlwComment".$mlw_question->question_id."' onclick='clear_field(this)'>".htmlspecialchars_decode($mlw_quiz_options->comment_field_text, ENT_QUOTES)."</textarea>";
				$mlw_display .= "<br />";
			}
			if ($mlw_question->hints != "")
			{
				$mlw_display .= "<span title=\"".htmlspecialchars_decode($mlw_question->hints, ENT_QUOTES)."\" style=\"text-decoration:underline;color:rgb(0,0,255);\">Hint</span>";
				$mlw_display .= "<br /><br />";
			}
			$mlw_display .= "</div>";
			if ( $mlw_quiz_options->pagination == 0) { $mlw_display .= "<br />"; }
		}
		
		//Display comment box if needed
		if ($mlw_quiz_options->comment_section == 0)
		{
			$mlw_qmn_section_count = $mlw_qmn_section_count + 1;
			$mlw_display .= "<div class='quiz_section slide".$mlw_qmn_section_count."'>";
			$mlw_message_comments = htmlspecialchars_decode($mlw_quiz_options->message_comment, ENT_QUOTES);
			$mlw_message_comments = str_replace( "%QUIZ_NAME%" , $mlw_quiz_options->quiz_name, $mlw_message_comments);
			$mlw_message_comments = str_replace( "%CURRENT_DATE%" , date("F jS Y"), $mlw_message_comments);
			$mlw_display .= "<label for='mlwQuizComments' style='font-weight:bold;'>".$mlw_message_comments."</label><br />";
			$mlw_display .= "<textarea cols='70' rows='15' id='mlwQuizComments' name='mlwQuizComments' ></textarea>";
			$mlw_display .= "</div>";
			if ( $mlw_quiz_options->pagination == 0) { $mlw_display .= "<br /><br />"; }
		}
		$mlw_qmn_section_count = $mlw_qmn_section_count + 1;
		$mlw_display .= "<div class='quiz_section slide".$mlw_qmn_section_count." quiz_end'>";
		if ($mlw_quiz_options->message_end_template != '')
		{
			$mlw_message_end = htmlspecialchars_decode($mlw_quiz_options->message_end_template, ENT_QUOTES);
			$mlw_message_end = str_replace( "%QUIZ_NAME%" , $mlw_quiz_options->quiz_name, $mlw_message_end);
			$mlw_message_end = str_replace( "%CURRENT_DATE%" , date("F jS Y"), $mlw_message_end);
			$mlw_display .= "<span>".$mlw_message_end."</span>";
			$mlw_display .= "<br /><br />";
		}
		if ($mlw_quiz_options->contact_info_location == 1)
		{
			$mlw_display .= mlwDisplayContactInfo($mlw_quiz_options);
		}
		$mlw_display .= "<span style='display: none;'>If you are human, leave this field blank or you will be considered spam:</span>";
		$mlw_display .= "<input style='display: none;' type='text' name='email' id='email' />";
		$mlw_display .= "<input type='hidden' name='total_questions' id='total_questions' value='".$mlw_qmn_total_questions."'/>";
		$mlw_display .= "<input type='hidden' name='timer' id='timer' value='0'/>";
		$mlw_display .= "<input type='hidden' name='complete_quiz' value='confirmation' />";
		$mlw_display .= "<input type='submit' value='".esc_attr(htmlspecialchars_decode($mlw_quiz_options->submit_button_text, ENT_QUOTES))."' />";
		$mlw_display .= "<span name='mlw_error_message_bottom' id='mlw_error_message_bottom' style='color: red;'></span><br />";
		$mlw_display .= "</form>";
		$mlw_display .= "</div>";
		$mlw_display .= "</div>";
		
	}
	//Display Completion Screen
	else
	{
		if (empty($mlw_spam_email) && $mlw_qmn_isAllowed)
		{
		
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
	
		//Variables needed for scoring
		$mlw_points = 0;
		$mlw_correct = 0;
		$mlw_total_questions = 0;
		$mlw_total_score = 0;
		$mlw_question_answers = "";
		isset($_POST["total_questions"]) ? $mlw_total_questions = intval($_POST["total_questions"]) : $mlw_total_questions = 0;

		//Update the amount of times the quiz has been taken
		$mlw_taken = $mlw_quiz_options->quiz_taken;
		$mlw_taken += 1;
		$update = "UPDATE " . $wpdb->prefix . "mlw_quizzes" . " SET quiz_taken='".$mlw_taken."' WHERE quiz_id=".$mlw_quiz_id;
		$results = $wpdb->query( $update );

		//See which answers were correct and award points if necessary
		$mlw_user_text = "";
		$mlw_correct_text = "";
		$mlw_qmn_answer_array = array();
		foreach($mlw_questions as $mlw_question) {
			if (isset($_POST["question".$mlw_question->question_id]) || isset($_POST["mlwComment".$mlw_question->question_id]))
			{
				//$mlw_total_questions += 1;
				if (isset($_POST["question".$mlw_question->question_id]))
				{
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
				}
				if (isset($_POST["mlwComment".$mlw_question->question_id]))
				{
					$mlw_qm_question_comment = $_POST["mlwComment".$mlw_question->question_id];
				}
				else
				{
					$mlw_qm_question_comment = "";
				}
				
				$mlw_question_answer_display = htmlspecialchars_decode($mlw_quiz_options->question_answer_template, ENT_QUOTES);
				$mlw_question_answer_display = str_replace( "%QUESTION%" , htmlspecialchars_decode($mlw_question->question_name, ENT_QUOTES), $mlw_question_answer_display);
				$mlw_question_answer_display = str_replace( "%USER_ANSWER%" , $mlw_user_text, $mlw_question_answer_display);
				$mlw_question_answer_display = str_replace( "%CORRECT_ANSWER%" , $mlw_correct_text, $mlw_question_answer_display);
				$mlw_question_answer_display = str_replace( "%USER_COMMENTS%" , $mlw_qm_question_comment, $mlw_question_answer_display);
				$mlw_question_answer_display = str_replace( "%CORRECT_ANSWER_INFO%" , htmlspecialchars_decode($mlw_question->question_answer_info, ENT_QUOTES), $mlw_question_answer_display);
	
				$mlw_qmn_answer_array[] = array($mlw_question->question_name, htmlspecialchars($mlw_user_text, ENT_QUOTES), htmlspecialchars($mlw_correct_text, ENT_QUOTES), htmlspecialchars(stripslashes($mlw_qm_question_comment), ENT_QUOTES));
				
				$mlw_question_answers .= $mlw_question_answer_display;
				$mlw_question_answers .= "<br />";
			}
		}
		
		//Calculate Total Percent Score And Average Points Only If Total Questions Doesn't Equal Zero To Avoid Division By Zero Error
		if ($mlw_total_questions != 0)
		{
			$mlw_total_score = round((($mlw_correct/$mlw_total_questions)*100), 2);
			$mlw_average_points = round(($mlw_points/$mlw_total_questions), 2);
		}
		else
		{
			$mlw_total_score = 0;
			$mlw_average_points = 0;
		}
		
		//Prepare comment section if set
		if (isset($_POST["mlwQuizComments"]))
		{
			$mlw_qm_quiz_comments = $_POST["mlwQuizComments"];
		}
		else
		{
			$mlw_qm_quiz_comments = "";
		}
		
		
		//Prepare Certificate
		$mlw_certificate_link = "";
		$mlw_certificate_options = unserialize($mlw_quiz_options->certificate_template);
		if (!is_array($mlw_certificate_options)) {
	        // something went wrong, initialize to empty array
	        $mlw_certificate_options = array('Enter title here', 'Enter text here', '', '', 1);
	    }
	    if ($mlw_certificate_options[4] == 0)
	    {
			$mlw_message_certificate = $mlw_certificate_options[1];
			$mlw_message_certificate = str_replace( "%POINT_SCORE%" , $mlw_points, $mlw_message_certificate);
			$mlw_message_certificate = str_replace( "%AVERAGE_POINT%" , $mlw_average_points, $mlw_message_certificate);
			$mlw_message_certificate = str_replace( "%AMOUNT_CORRECT%" , $mlw_correct, $mlw_message_certificate);
			$mlw_message_certificate = str_replace( "%TOTAL_QUESTIONS%" , $mlw_total_questions, $mlw_message_certificate);
			$mlw_message_certificate = str_replace( "%CORRECT_SCORE%" , $mlw_total_score, $mlw_message_certificate);
			$mlw_message_certificate = str_replace( "%QUIZ_NAME%" , $mlw_quiz_options->quiz_name, $mlw_message_certificate);
			$mlw_message_certificate = str_replace( "%USER_NAME%" , $mlw_user_name, $mlw_message_certificate);
			$mlw_message_certificate = str_replace( "%USER_BUSINESS%" , $mlw_user_comp, $mlw_message_certificate);
			$mlw_message_certificate = str_replace( "%USER_PHONE%" , $mlw_user_phone, $mlw_message_certificate);
			$mlw_message_certificate = str_replace( "%USER_EMAIL%" , $mlw_user_email, $mlw_message_certificate);
			$mlw_message_certificate = str_replace( "%QUESTIONS_ANSWERS%" , $mlw_question_answers, $mlw_message_certificate);
			$mlw_message_certificate = str_replace( "%COMMENT_SECTION%" , $mlw_qm_quiz_comments, $mlw_message_certificate);
			$mlw_message_certificate = str_replace( "%TIMER%" , $mlw_qmn_timer, $mlw_message_certificate);
			$mlw_message_certificate = str_replace( "%CURRENT_DATE%" , date("F jS Y"), $mlw_message_certificate);
			$mlw_message_certificate = str_replace( "\n" , "<br>", $mlw_message_certificate);
			$mlw_plugindirpath = plugin_dir_path( __FILE__ );
			$plugindirpath=plugin_dir_path( __FILE__ );
			$mlw_qmn_certificate_file=<<<EOC
<?php
include("$plugindirpath/WriteHTML.php");
\$pdf=new PDF_HTML();
\$pdf->AddPage('L');
EOC;
			$mlw_qmn_certificate_file.=$mlw_certificate_options[3] != '' ? '$pdf->Image("'.$mlw_certificate_options[3].'",0,0,$pdf->w, $pdf->h);' : '';
			$mlw_qmn_certificate_file.=<<<EOC
\$pdf->Ln(20);
\$pdf->SetFont('Arial','B',24);
\$pdf->MultiCell(280,20,'$mlw_certificate_options[0]',0,'C');
\$pdf->Ln(15);
\$pdf->SetFont('Arial','',16);
\$pdf->WriteHTML("<p align='center'>$mlw_message_certificate</p>");
EOC;
			$mlw_qmn_certificate_file.=$mlw_certificate_options[2] != '' ? '$pdf->Image("'.$mlw_certificate_options[2].'",110,130);' : '';
			$mlw_qmn_certificate_file.=<<<EOC
\$pdf->Output('mlw_qmn_certificate.pdf','D');
unlink(__FILE__);
EOC;
			$mlw_qmn_certificate_filename = str_replace(home_url()."/", '', plugin_dir_url( __FILE__ ))."certificates/mlw_qmn_quiz".date("YmdHis").$mlw_qmn_timer.".php";
			file_put_contents($mlw_qmn_certificate_filename, $mlw_qmn_certificate_file);
			$mlw_qmn_certificate_filename = plugin_dir_url( __FILE__ )."certificates/mlw_qmn_quiz".date("YmdHis").$mlw_qmn_timer.".php";
			$mlw_certificate_link = "<a href='".$mlw_qmn_certificate_filename."' style='color: blue;'>Download Certificate</a>";
	    }
	    
		/*
			Prepare the landing page
			-First, unserialize message_after column
			-Second, check for array in case user has not updated
			Message array = (array( bottomvalue, topvalue, text),array( bottomvalue, topvalue, text), etc..., array(0,0,text))
		*/
		$mlw_message_after_array = @unserialize($mlw_quiz_options->message_after);
		if (is_array($mlw_message_after_array))
		{
			//Cycle through landing pages
			foreach($mlw_message_after_array as $mlw_each)
			{
				//Check to see if default
				if ($mlw_each[0] == 0 && $mlw_each[1] == 0)
				{
					$mlw_message_after = htmlspecialchars_decode($mlw_each[2], ENT_QUOTES);
					$mlw_message_after = str_replace( "%POINT_SCORE%" , $mlw_points, $mlw_message_after);
					$mlw_message_after = str_replace( "%AVERAGE_POINT%" , $mlw_average_points, $mlw_message_after);
					$mlw_message_after = str_replace( "%AMOUNT_CORRECT%" , $mlw_correct, $mlw_message_after);
					$mlw_message_after = str_replace( "%TOTAL_QUESTIONS%" , $mlw_total_questions, $mlw_message_after);
					$mlw_message_after = str_replace( "%CORRECT_SCORE%" , $mlw_total_score, $mlw_message_after);
					$mlw_message_after = str_replace( "%QUIZ_NAME%" , $mlw_quiz_options->quiz_name, $mlw_message_after);
					$mlw_message_after = str_replace( "%USER_NAME%" , $mlw_user_name, $mlw_message_after);
					$mlw_message_after = str_replace( "%USER_BUSINESS%" , $mlw_user_comp, $mlw_message_after);
					$mlw_message_after = str_replace( "%USER_PHONE%" , $mlw_user_phone, $mlw_message_after);
					$mlw_message_after = str_replace( "%USER_EMAIL%" , $mlw_user_email, $mlw_message_after);
					$mlw_message_after = str_replace( "%QUESTIONS_ANSWERS%" , $mlw_question_answers, $mlw_message_after);
					$mlw_message_after = str_replace( "%COMMENT_SECTION%" , $mlw_qm_quiz_comments, $mlw_message_after);
					$mlw_message_after = str_replace( "%TIMER%" , $mlw_qmn_timer, $mlw_message_after);
					$mlw_message_after = str_replace( "%CERTIFICATE_LINK%" , $mlw_certificate_link, $mlw_message_after);
					$mlw_message_after = str_replace( "%CURRENT_DATE%" , date("F jS Y"), $mlw_message_after);
					$mlw_message_after = str_replace( "\n" , "<br>", $mlw_message_after);
					$mlw_display .= $mlw_message_after;
					break;
				}
				else
				{
					//Check to see if points fall in correct range
					if ($mlw_points >= $mlw_each[0] && $mlw_points <= $mlw_each[1])
					{
						$mlw_message_after = htmlspecialchars_decode($mlw_each[2], ENT_QUOTES);
						$mlw_message_after = str_replace( "%POINT_SCORE%" , $mlw_points, $mlw_message_after);
						$mlw_message_after = str_replace( "%AVERAGE_POINT%" , $mlw_average_points, $mlw_message_after);
						$mlw_message_after = str_replace( "%AMOUNT_CORRECT%" , $mlw_correct, $mlw_message_after);
						$mlw_message_after = str_replace( "%TOTAL_QUESTIONS%" , $mlw_total_questions, $mlw_message_after);
						$mlw_message_after = str_replace( "%CORRECT_SCORE%" , $mlw_total_score, $mlw_message_after);
						$mlw_message_after = str_replace( "%QUIZ_NAME%" , $mlw_quiz_options->quiz_name, $mlw_message_after);
						$mlw_message_after = str_replace( "%USER_NAME%" , $mlw_user_name, $mlw_message_after);
						$mlw_message_after = str_replace( "%USER_BUSINESS%" , $mlw_user_comp, $mlw_message_after);
						$mlw_message_after = str_replace( "%USER_PHONE%" , $mlw_user_phone, $mlw_message_after);
						$mlw_message_after = str_replace( "%USER_EMAIL%" , $mlw_user_email, $mlw_message_after);
						$mlw_message_after = str_replace( "%QUESTIONS_ANSWERS%" , $mlw_question_answers, $mlw_message_after);
						$mlw_message_after = str_replace( "%COMMENT_SECTION%" , $mlw_qm_quiz_comments, $mlw_message_after);
						$mlw_message_after = str_replace( "%TIMER%" , $mlw_qmn_timer, $mlw_message_after);
						$mlw_message_after = str_replace( "%CERTIFICATE_LINK%" , $mlw_certificate_link, $mlw_message_after);
						$mlw_message_after = str_replace( "%CURRENT_DATE%" , date("F jS Y"), $mlw_message_after);
						$mlw_message_after = str_replace( "\n" , "<br>", $mlw_message_after);
						$mlw_display .= $mlw_message_after;
						break;
					}
					//Check to see if score fall in correct range
					if ($mlw_total_score >= $mlw_each[0] && $mlw_total_score <= $mlw_each[1])
					{
						$mlw_message_after = htmlspecialchars_decode($mlw_each[2], ENT_QUOTES);
						$mlw_message_after = str_replace( "%POINT_SCORE%" , $mlw_points, $mlw_message_after);
						$mlw_message_after = str_replace( "%AVERAGE_POINT%" , $mlw_average_points, $mlw_message_after);
						$mlw_message_after = str_replace( "%AMOUNT_CORRECT%" , $mlw_correct, $mlw_message_after);
						$mlw_message_after = str_replace( "%TOTAL_QUESTIONS%" , $mlw_total_questions, $mlw_message_after);
						$mlw_message_after = str_replace( "%CORRECT_SCORE%" , $mlw_total_score, $mlw_message_after);
						$mlw_message_after = str_replace( "%QUIZ_NAME%" , $mlw_quiz_options->quiz_name, $mlw_message_after);
						$mlw_message_after = str_replace( "%USER_NAME%" , $mlw_user_name, $mlw_message_after);
						$mlw_message_after = str_replace( "%USER_BUSINESS%" , $mlw_user_comp, $mlw_message_after);
						$mlw_message_after = str_replace( "%USER_PHONE%" , $mlw_user_phone, $mlw_message_after);
						$mlw_message_after = str_replace( "%USER_EMAIL%" , $mlw_user_email, $mlw_message_after);
						$mlw_message_after = str_replace( "%QUESTIONS_ANSWERS%" , $mlw_question_answers, $mlw_message_after);
						$mlw_message_after = str_replace( "%COMMENT_SECTION%" , $mlw_qm_quiz_comments, $mlw_message_after);
						$mlw_message_after = str_replace( "%TIMER%" , $mlw_qmn_timer, $mlw_message_after);
						$mlw_message_after = str_replace( "%CERTIFICATE_LINK%" , $mlw_certificate_link, $mlw_message_after);
						$mlw_message_after = str_replace( "%CURRENT_DATE%" , date("F jS Y"), $mlw_message_after);
						$mlw_message_after = str_replace( "\n" , "<br>", $mlw_message_after);
						$mlw_display .= $mlw_message_after;
						break;
					}
				}				
			}			
		}
		else
		{
			//Prepare the after quiz message
			$mlw_message_after = htmlspecialchars_decode($mlw_quiz_options->message_after, ENT_QUOTES);
			$mlw_message_after = str_replace( "%POINT_SCORE%" , $mlw_points, $mlw_message_after);
			$mlw_message_after = str_replace( "%AVERAGE_POINT%" , $mlw_average_points, $mlw_message_after);
			$mlw_message_after = str_replace( "%AMOUNT_CORRECT%" , $mlw_correct, $mlw_message_after);
			$mlw_message_after = str_replace( "%TOTAL_QUESTIONS%" , $mlw_total_questions, $mlw_message_after);
			$mlw_message_after = str_replace( "%CORRECT_SCORE%" , $mlw_total_score, $mlw_message_after);
			$mlw_message_after = str_replace( "%QUIZ_NAME%" , $mlw_quiz_options->quiz_name, $mlw_message_after);
			$mlw_message_after = str_replace( "%USER_NAME%" , $mlw_user_name, $mlw_message_after);
			$mlw_message_after = str_replace( "%USER_BUSINESS%" , $mlw_user_comp, $mlw_message_after);
			$mlw_message_after = str_replace( "%USER_PHONE%" , $mlw_user_phone, $mlw_message_after);
			$mlw_message_after = str_replace( "%USER_EMAIL%" , $mlw_user_email, $mlw_message_after);
			$mlw_message_after = str_replace( "%QUESTIONS_ANSWERS%" , $mlw_question_answers, $mlw_message_after);
			$mlw_message_after = str_replace( "%COMMENT_SECTION%" , $mlw_qm_quiz_comments, $mlw_message_after);
			$mlw_message_after = str_replace( "%TIMER%" , $mlw_qmn_timer, $mlw_message_after);
			$mlw_message_after = str_replace( "%CERTIFICATE_LINK%" , $mlw_certificate_link, $mlw_message_after);
			$mlw_message_after = str_replace( "%CURRENT_DATE%" , date("F jS Y"), $mlw_message_after);
			$mlw_message_after = str_replace( "\n" , "<br>", $mlw_message_after);
			$mlw_display .= $mlw_message_after;
		}
		
		if ($mlw_quiz_options->social_media == 1)
		{
			$mlw_social_message = str_replace( "%POINT_SCORE%" , $mlw_points, $mlw_quiz_options->social_media_text);
			$mlw_social_message = str_replace( "%AVERAGE_POINT%" , $mlw_average_points, $mlw_social_message);
			$mlw_social_message = str_replace( "%AMOUNT_CORRECT%" , $mlw_correct, $mlw_social_message);
			$mlw_social_message = str_replace( "%TOTAL_QUESTIONS%" , $mlw_total_questions, $mlw_social_message);
			$mlw_social_message = str_replace( "%CORRECT_SCORE%" , $mlw_total_score, $mlw_social_message);
			$mlw_social_message = str_replace( "%QUIZ_NAME%" , $mlw_quiz_options->quiz_name, $mlw_social_message);
			$mlw_social_message = str_replace( "%TIMER%" , $mlw_qmn_timer, $mlw_social_message);
			$mlw_social_message = str_replace( "%CURRENT_DATE%" , date("F jS Y"), $mlw_social_message);
			$mlw_display .= "<br />
			<a href=\"https://twitter.com/share\" data-size=\"large\" data-text=\"".esc_attr($mlw_social_message)."\" class=\"twitter-share-button\" data-lang=\"en\">Tweet</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=\"https://platform.twitter.com/widgets.js\";fjs.parentNode.insertBefore(js,fjs);}}(document,\"script\",\"twitter-wjs\");</script>
			<br />";
		}
		
		//Switch email type to HTML
		add_filter( 'wp_mail_content_type', 'mlw_qmn_set_html_content_type' );
	
		//Prepare and send the user email
		$mlw_message = "";
		if ($mlw_quiz_options->send_user_email == "0")
		{
			if ($mlw_user_email != "")
			{
				$mlw_message = htmlspecialchars_decode($mlw_quiz_options->user_email_template, ENT_QUOTES);
				$mlw_message = str_replace( "%POINT_SCORE%" , $mlw_points, $mlw_message);
				$mlw_message = str_replace( "%AVERAGE_POINT%" , $mlw_average_points, $mlw_message);
				$mlw_message = str_replace( "%AMOUNT_CORRECT%" , $mlw_correct, $mlw_message);
				$mlw_message = str_replace( "%TOTAL_QUESTIONS%" , $mlw_total_questions, $mlw_message);
				$mlw_message = str_replace( "%CORRECT_SCORE%" , $mlw_total_score, $mlw_message);
				$mlw_message = str_replace( "%QUIZ_NAME%" , $mlw_quiz_options->quiz_name, $mlw_message);
				$mlw_message = str_replace( "%USER_NAME%" , $mlw_user_name, $mlw_message);
				$mlw_message = str_replace( "%USER_BUSINESS%" , $mlw_user_comp, $mlw_message);
				$mlw_message = str_replace( "%USER_PHONE%" , $mlw_user_phone, $mlw_message);
				$mlw_message = str_replace( "%USER_EMAIL%" , $mlw_user_email, $mlw_message);
				$mlw_message = str_replace( "%QUESTIONS_ANSWERS%" , $mlw_question_answers, $mlw_message);
				$mlw_message = str_replace( "%COMMENT_SECTION%" , $mlw_qm_quiz_comments, $mlw_message);
				$mlw_message = str_replace( "%TIMER%" , $mlw_qmn_timer, $mlw_message);
				$mlw_message = str_replace( "%CERTIFICATE_LINK%" , $mlw_certificate_link, $mlw_message);
				$mlw_message = str_replace( "%CURRENT_DATE%" , date("F jS Y"), $mlw_message);
				if ( get_option('mlw_advert_shows') == 'true' ) {$mlw_message .= "<br>This email was generated by the Quiz Master Next plugin.";}
				$mlw_message = str_replace( "\n" , "<br>", $mlw_message);
				$mlw_message = str_replace( "<br/>" , "<br>", $mlw_message);
				$mlw_message = str_replace( "<br />" , "<br>", $mlw_message);
				$mlw_headers = 'From: '.$mlw_quiz_options->email_from_text.' <'.$mlw_quiz_options->admin_email.'>' . "\r\n";
				wp_mail($mlw_user_email, "Quiz Results For ".$mlw_quiz_options->quiz_name, $mlw_message, $mlw_headers);
			}
		}

		//Prepare and send the admin email
		$mlw_message = "";
		if ($mlw_quiz_options->send_admin_email == "0")
		{
			$mlw_message = htmlspecialchars_decode($mlw_quiz_options->admin_email_template, ENT_QUOTES);
			$mlw_message = str_replace( "%POINT_SCORE%" , $mlw_points, $mlw_message);
			$mlw_message = str_replace( "%AVERAGE_POINT%" , $mlw_average_points, $mlw_message);
			$mlw_message = str_replace( "%AMOUNT_CORRECT%" , $mlw_correct, $mlw_message);
			$mlw_message = str_replace( "%TOTAL_QUESTIONS%" , $mlw_total_questions, $mlw_message);
			$mlw_message = str_replace( "%CORRECT_SCORE%" , $mlw_total_score, $mlw_message);
			$mlw_message = str_replace( "%USER_NAME%" , $mlw_user_name, $mlw_message);
			$mlw_message = str_replace( "%USER_BUSINESS%" , $mlw_user_comp, $mlw_message);
			$mlw_message = str_replace( "%USER_PHONE%" , $mlw_user_phone, $mlw_message);
			$mlw_message = str_replace( "%USER_EMAIL%" , $mlw_user_email, $mlw_message);
			$mlw_message = str_replace( "%QUIZ_NAME%" , $mlw_quiz_options->quiz_name, $mlw_message);
			$mlw_message = str_replace( "%QUESTIONS_ANSWERS%" , $mlw_question_answers, $mlw_message);
			$mlw_message = str_replace( "%COMMENT_SECTION%" , $mlw_qm_quiz_comments, $mlw_message);
			$mlw_message = str_replace( "%TIMER%" , $mlw_qmn_timer, $mlw_message);
			$mlw_message = str_replace( "%CERTIFICATE_LINK%" , $mlw_certificate_link, $mlw_message);
			$mlw_message = str_replace( "%CURRENT_DATE%" , date("F jS Y"), $mlw_message);
			if ( get_option('mlw_advert_shows') == 'true' ) {$mlw_message .= "<br>This email was generated by the Quiz Master Next script by Frank Corso";}
			$mlw_message = str_replace( "\n" , "<br>", $mlw_message);
			$mlw_message = str_replace( "<br/>" , "<br>", $mlw_message);
			$mlw_message = str_replace( "<br />" , "<br>", $mlw_message);
			$mlw_headers = 'From: '.$mlw_quiz_options->email_from_text.' <'.$mlw_quiz_options->admin_email.'>' . "\r\n";
			wp_mail($mlw_quiz_options->admin_email, "Quiz Results For ".$mlw_quiz_options->quiz_name, $mlw_message, $mlw_headers);
		}
		
		//Remove HTML type for emails
		remove_filter( 'wp_mail_content_type', 'mlw_qmn_set_html_content_type' );

		//Save the results into database
		$mlw_quiz_results_array = array( intval($mlw_qmn_timer), $mlw_qmn_answer_array, htmlspecialchars(stripslashes($mlw_qm_quiz_comments), ENT_QUOTES));
		$mlw_quiz_results = serialize($mlw_quiz_results_array);
		
		global $wpdb;
		$table_name = $wpdb->prefix . "mlw_results";
		$results = $wpdb->query( $wpdb->prepare( "INSERT INTO " . $table_name . " (result_id, quiz_id, quiz_name, quiz_system, point_score, correct_score, correct, total, name, business, email, phone, time_taken, time_taken_real, quiz_results, deleted) VALUES (NULL, %d, '%s', %d, %d, %d, %d, %d, '%s', '%s', '%s', '%s', '%s', '%s', '%s', 0)", $mlw_quiz_id, $mlw_quiz_options->quiz_name, $mlw_quiz_options->system, $mlw_points, $mlw_total_score, $mlw_correct, $mlw_total_questions, $mlw_user_name, $mlw_user_comp, $mlw_user_email, $mlw_user_phone, date("h:i:s A m/d/Y"), date("Y-m-d H:i:s"), $mlw_quiz_results) );
		}
		else
		{
			if (!$mlw_qmn_isAllowed)
			{
				$mlw_display .= htmlspecialchars_decode($mlw_quiz_options->total_user_tries_text, ENT_QUOTES);
			}
			else { $mlw_display .= "Thank you.";	}
		}
	}
return $mlw_display;
}


/*
This function displays fields to ask for contact information
*/
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
			$mlw_contact_display .= "<span style='font-weight:bold;';>".htmlspecialchars_decode($mlw_quiz_options->name_field_text, ENT_QUOTES)."</span><br />";
			$mlw_contact_display .= "<input type='text' x-webkit-speech name='mlwUserName' value='".$current_user->display_name."' />";
			$mlw_contact_display .= "<br /><br />";

		}
		if ($mlw_quiz_options->user_comp != 2)
		{
			$mlw_contact_display .= "<span style='font-weight:bold;';>".htmlspecialchars_decode($mlw_quiz_options->business_field_text, ENT_QUOTES)."</span><br />";
			$mlw_contact_display .= "<input type='text' x-webkit-speech name='mlwUserComp' value='' />";
			$mlw_contact_display .= "<br /><br />";
		}
		if ($mlw_quiz_options->user_email != 2)
		{
			$mlw_contact_display .= "<span style='font-weight:bold;';>".htmlspecialchars_decode($mlw_quiz_options->email_field_text, ENT_QUOTES)."</span><br />";
			$mlw_contact_display .= "<input type='text' x-webkit-speech name='mlwUserEmail' value='".$current_user->user_email."' />";
			$mlw_contact_display .= "<br /><br />";
		}
		if ($mlw_quiz_options->user_phone != 2)
		{
			$mlw_contact_display .= "<span style='font-weight:bold;';>".htmlspecialchars_decode($mlw_quiz_options->phone_field_text, ENT_QUOTES)."</span><br />";
			$mlw_contact_display .= "<input type='text' x-webkit-speech name='mlwUserPhone' value='' />";
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
			$mlw_contact_display .= "<span style='font-weight:bold;';>".htmlspecialchars_decode($mlw_quiz_options->name_field_text, ENT_QUOTES)."</span><br />";
			$mlw_contact_display .= "<input type='text' x-webkit-speech name='mlwUserName' value='' />";
			$mlw_contact_display .= "<br /><br />";
		}
		if ($mlw_quiz_options->user_comp != 2)
		{
			$mlw_contact_display .= "<span style='font-weight:bold;';>".htmlspecialchars_decode($mlw_quiz_options->business_field_text, ENT_QUOTES)."</span><br />";
			$mlw_contact_display .= "<input type='text' x-webkit-speech name='mlwUserComp' value='' />";
			$mlw_contact_display .= "<br /><br />";
		}
		if ($mlw_quiz_options->user_email != 2)
		{
			$mlw_contact_display .= "<span style='font-weight:bold;';>".htmlspecialchars_decode($mlw_quiz_options->email_field_text, ENT_QUOTES)."</span><br />";
			$mlw_contact_display .= "<input type='text' x-webkit-speech name='mlwUserEmail' value='' />";
			$mlw_contact_display .= "<br /><br />";
		}
		if ($mlw_quiz_options->user_phone != 2)
		{
			$mlw_contact_display .= "<span style='font-weight:bold;';>".htmlspecialchars_decode($mlw_quiz_options->phone_field_text, ENT_QUOTES)."</span><br />";
			$mlw_contact_display .= "<input type='text' x-webkit-speech name='mlwUserPhone' value='' />";
			$mlw_contact_display .= "<br /><br />";
		}
	}
	return $mlw_contact_display;
}

/*
This function helps set the email type to HTML
*/
function mlw_qmn_set_html_content_type() {

	return 'text/html';
}
?>