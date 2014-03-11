<?php
/*
This page allows for the viewing of the quiz results.
*/
/* 
Copyright 2013, My Local Webstop (email : fpcorso@mylocalwebstop.com)
*/

function mlw_generate_result_details()
{
	$mlw_result_id = $_GET["result_id"];
	if ($mlw_result_id != "")
	{
		global $wpdb;
		
		//Check if user wants to create certificate
		if (isset($_POST["create_certificate"]) && $_POST["create_certificate"] == "confirmation")
		{
			$mlw_certificate_id = intval($_GET["result_id"]);
			$mlw_quiz_results = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."mlw_results WHERE result_id=%d", $mlw_certificate_id ) );
			
			$mlw_certificate_results = $wpdb->get_var( $wpdb->prepare( "SELECT certificate_template FROM ".$wpdb->prefix."mlw_quizzes WHERE quiz_id=%d", $mlw_quiz_results->quiz_id ) );
			
			//Prepare Certificate
			$mlw_certificate_options = unserialize($mlw_certificate_results);
			if (!is_array($mlw_certificate_options)) {
		        // something went wrong, initialize to empty array
		        $mlw_certificate_options = array('Enter title here', 'Enter text here', '', '');
		    }
			$mlw_message_certificate = $mlw_certificate_options[1];
			$mlw_message_certificate = str_replace( "%POINT_SCORE%" , $mlw_quiz_results->point_score, $mlw_message_certificate);
			$mlw_message_certificate = str_replace( "%AVERAGE_POINT%" , $mlw_quiz_results->point_score/$mlw_quiz_results->total, $mlw_message_certificate);
			$mlw_message_certificate = str_replace( "%AMOUNT_CORRECT%" , $mlw_quiz_results->correct, $mlw_message_certificate);
			$mlw_message_certificate = str_replace( "%TOTAL_QUESTIONS%" , $mlw_quiz_results->total, $mlw_message_certificate);
			$mlw_message_certificate = str_replace( "%CORRECT_SCORE%" , $mlw_quiz_results->correct_score, $mlw_message_certificate);
			$mlw_message_certificate = str_replace( "%QUIZ_NAME%" , $mlw_quiz_results->quiz_name, $mlw_message_certificate);
			$mlw_message_certificate = str_replace( "%USER_NAME%" , $mlw_quiz_results->name, $mlw_message_certificate);
			$mlw_message_certificate = str_replace( "%USER_BUSINESS%" , $mlw_quiz_results->business, $mlw_message_certificate);
			$mlw_message_certificate = str_replace( "%USER_PHONE%" , $mlw_quiz_results->email, $mlw_message_certificate);
			$mlw_message_certificate = str_replace( "%USER_EMAIL%" , $mlw_quiz_results->phone, $mlw_message_certificate);
			$mlw_message_certificate = str_replace( "\n" , "<br>", $mlw_message_certificate);
			$mlw_qmn_certifiicate_file = "<?php
			include(\"".plugin_dir_path( __FILE__ )."WriteHTML.php\");
			\$pdf = new PDF_HTML();
			\$pdf->AddPage('L');";
			$mlw_qmn_certifiicate_file .= $mlw_certificate_options[3] != '' ? "\$pdf->Image('".$mlw_certificate_options[3]."',0,0,\$pdf->w, \$pdf->h);" : '';
			$mlw_qmn_certifiicate_file .= "\$pdf->Ln(20);
			\$pdf->SetFont('Arial','B',24);
			\$pdf->MultiCell(280,20,'".$mlw_certificate_options[0]."', 0, 'C');
			\$pdf->Ln(15);
			\$pdf->SetFont('Arial','',16);
			\$pdf->WriteHTML('<p align=\"center\">".$mlw_message_certificate."</p>');";
			$mlw_qmn_certifiicate_file .= $mlw_certificate_options[2] != '' ? "\$pdf->Image('".$mlw_certificate_options[2]."',110,130);" : '';
			$mlw_qmn_certifiicate_file .= "\$pdf->Output('mlw_qmn_certificate.pdf', 'D');
			unlink(__FILE__);
			?>";
			$mlw_qmn_certificate_filename = "../".str_replace(home_url()."/", '', plugin_dir_url( __FILE__ ))."certificates/mlw_qmn_quiz".date("YmdHis")."admin.php";
			file_put_contents($mlw_qmn_certificate_filename, $mlw_qmn_certifiicate_file);
			$mlw_qmn_certificate_filename = plugin_dir_url( __FILE__ )."certificates/mlw_qmn_quiz".date("YmdHis")."admin.php";		
		}
		
		
		//Load Results
		$table_name = $wpdb->prefix . "mlw_results";
		$sql = "SELECT quiz_results FROM " . $wpdb->prefix . "mlw_results WHERE result_id=".$mlw_result_id."";
		$mlw_results_data = $wpdb->get_results($sql);
		
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
		<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.0/jquery.min.js"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>-->
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
				$j("button").button();
			
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
		<h2>Quiz Results<a id="opener" href="">(?)</a></h2>
		<form action="" method="post" name="create_certificate_form">
			<input type="hidden" name="create_certificate" value="confirmation" />
			<input type="submit" value="Create Certificate" />
		</form>
		<?php 
			if (isset($_POST["create_certificate"]) && $_POST["create_certificate"] == "confirmation")
			{
				echo "<a href='".$mlw_qmn_certificate_filename."' style='color: blue;'>Download Certificate Here</a><br />";
			}
			foreach($mlw_results_data as $mlw_results_info) {
			echo htmlspecialchars_decode($mlw_results_info->quiz_results, ENT_QUOTES);
			}
		?>
		<div id="dialog" title="Help">
		<h3><b>Help</b></h3>
		<p>This page shows the results from the taken quiz.</p>
		<p>The top section shows the question, the user's answer, and the correct answer.</p>
		<p>The bottom section shows the text from the comment box if enabled.</p>
		</div>	
		</div>
		</div>
		
<?php
	}
	else
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
		<h2>Quiz Results<a id="opener" href="">(?)</a></h2>
		<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
		<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
		<strong>Hey!</strong> Please go to the Quiz Results page and click on the View link from the result you wish to see.</p>
		</div>
		<div id="dialog" title="Help" style="display:none;">
		<h3><b>Help</b></h3>
		<p>You are getting this error page because this page could not find the results.</p>
		<p>You must go to the Quiz Results page and click on the result you want to see from that table.</p>
		</div>	
		</div>
		</div>
		<?php
	}
}
?>