<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
* This function shows the about page. It also shows the changelog information.
*
* @return void
* @since 4.4.0
*/

function mlw_generate_about_page()
{
	global $mlwQuizMasterNext;
	$mlw_quiz_version = $mlwQuizMasterNext->version;
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-dialog' );
	wp_enqueue_script( 'jquery-ui-button' );
	wp_enqueue_script( 'jquery-effects-blind' );
	wp_enqueue_script( 'jquery-effects-explode' );
	wp_enqueue_style( 'qmn_admin_style', plugins_url( '../css/qmn_admin.css' , __FILE__ ) );
	wp_enqueue_script('qmn_admin_js', plugins_url( '../js/admin.js' , __FILE__ ));
	?>
	<style>
		div.mlw_qmn_icon_wrap
		{
			background: <?php echo 'url("'.plugins_url( 'images/quiz_icon.png' , __FILE__ ).'")'; ?> no-repeat;
		}
	</style>
	<div class="wrap about-wrap">
		<h1><?php _e('Welcome To Quiz Master Next', 'quiz-master-next'); ?></h1>
		<div class="about-text"><?php _e('Thank you for updating!', 'quiz-master-next'); ?></div>
		<div class="mlw_qmn_icon_wrap"><?php echo $mlw_quiz_version; ?></div>
		<h2 class="nav-tab-wrapper">
			<a href="javascript:qmn_select_tab(1, 'mlw_quiz_what_new');" id="mlw_qmn_tab_1" class="nav-tab nav-tab-active">
				<?php _e("What's New!", 'quiz-master-next'); ?></a>
			<a href="javascript:qmn_select_tab(2, 'mlw_quiz_changelog');" id="mlw_qmn_tab_2" class="nav-tab">
				<?php _e('Changelog', 'quiz-master-next'); ?></a>
			<a href="javascript:qmn_select_tab(3, 'qmn_contributors');" id="mlw_qmn_tab_3" class="nav-tab">
				<?php _e('People Who Make QMN Possible', 'quiz-master-next'); ?></a>
		</h2>
		<div id="mlw_quiz_what_new" class="qmn_tab">
			<h2 style="margin: 1.1em 0 .2em;font-size: 2.4em;font-weight: 300;line-height: 1.3;text-align: center;">Added ability to reorder questions by drag and drop!</h2>
			<p style="text-align: center;">When editing your questions you can now drag and drop your questions to reorganize them. </p>
			<br />
			<h2 style="margin: 1.1em 0 .2em;font-size: 2.4em;font-weight: 300;line-height: 1.3;text-align: center;">Quiz Results are now searchable!</h2>
			<p style="text-align: center;">You can now search quiz results based on several different criteria as well as sort the results.</p>
			<br />
                        <h2 style="margin: 1.1em 0 .2em;font-size: 2.4em;font-weight: 300;line-height: 1.3;text-align: center;">Added new primary template</h2>
			<p style="text-align: center;">Many versions ago, I added a new template system that would allow me to include different styles to choose from. This update adds the first template.</p>

			<h2 style="margin: 1.1em 0 .2em;font-size: 2.4em;font-weight: 300;line-height: 1.3;text-align: center;">This Plugin Is Now Translation Ready!</h2>
			<p style="text-align: center;">For those who wish to assist in translating, you can find the POT in the languages folder. If you do not know what that is, feel free to contact me and I will assist you with it.</p>
			<br />
			<hr />
			<h2 style="margin: 1.1em 0 .2em;font-size: 2.4em;font-weight: 300;line-height: 1.3;text-align: center;">For Developers:</h2>
			<br />
                        <h2 style="margin: 1.1em 0 .2em;font-size: 2.4em;font-weight: 300;line-height: 1.3;text-align: center;">New Template API</h2>
			<p style="text-align: center;">A new template API has been created to help with creating CSS templates. </p>
                         <br />
                         <h2 style="margin: 1.1em 0 .2em;font-size: 2.4em;font-weight: 300;line-height: 1.3;text-align: center;">Changes to Question API</h2>
			<p style="text-align: center;">Changes were made to the Question API to only show relevant fields for each question type. </p>
                        <br />
			<h2 style="margin: 1.1em 0 .2em;font-size: 2.4em;font-weight: 300;line-height: 1.3;text-align: center;">We Are On GitHub Now</h2>
			<p style="text-align: center;">We love github and use it for all of our plugins! Be sure to <a href="https://github.com/fpcorso/quiz_master_next/">make suggestions or contribute</a> to our Quiz Master Next repository.</p>
			<br />
		</div>
		<div id="mlw_quiz_changelog" class="qmn_tab" style="display: none;">
			<h2>Changelog</h2>
			<h3><?php echo $mlw_quiz_version; ?> (August 7, 2015)</h3>
			<ul class="changelog">
				<!--
				Examples:
				<li class="add"><div class="two">Add</div>Some feature was added</li>
				<li class="fixed"><div class="two">Fixed</div>Fixed some bug</li>
				-->
				<li class="add"><div class="two">Add</div>Added a brand new log system to track errors in the plugin.</li>
                                <li class="add"><div class="two">Add</div>Added the ability to drag and drop questions when creating your quiz.</li>
                                <li class="add"><div class="two">Add</div>Added new CSS template system for quizzes.</li>
                                <li class="add"><div class="two">Add</div>Added new classes for Correct/Incorrect Answers.</li>
                                <li class="add"><div class="two">Add</div>Added ability to search quiz results and sort the results.</li>
                                <li class="add"><div class="two">Add</div>Added the ability to search quiz results by quiz name, score, time taken, and completion time.</li>
                                <li class="add"><div class="two">Add</div>Added the ability for developers to show only relevant fields when adding/editing questions on different question types.</li>
                                <li class="add"><div class="two">Add</div>Added new register template functions to the API.</li>
                                <li class="add"><div class="two">Add</div>Added a new contributor tab to the credits page.</li>
                                <li class="add"><div class="two">Add</div>Added a checkbox to the quiz results page to delete results.</li>
                                <li class="add"><div class="two">Add</div>Added a brand new review message system.</li>   
				<li class="fixed"><div class="two">Fixed</div>Fixed a bug where slashes were appearing with the category name.</li>
				<li class="fixed"><div class="two">Fixed</div>Fixed a timer bug that prevented the timer form working when using pagination.</li>
                                
                               
			</ul>
		</div>
		<div id="qmn_contributors" class="qmn_tab" style="display:none;">
			<h2>GitHub Contributors</h2>
			<?php
			$contributors = get_transient( 'qmn_contributors' );
			if ( false === $contributors ) {
				$response = wp_remote_get( 'https://api.github.com/repos/fpcorso/quiz_master_next/contributors', array( 'sslverify' => false ) );
				if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {
					$contributors = array();
				} else {
					$contributors = json_decode( wp_remote_retrieve_body( $response ) );
				}
			}
			if ( is_array( $contributors ) & ! empty( $contributors ) ) {
				set_transient( 'qmn_contributors', $contributors, 3600 );
				$contributor_list = '<ul class="wp-people-group">';
				foreach ( $contributors as $contributor ) {
					$contributor_list .= '<li class="wp-person">';
					$contributor_list .= sprintf( '<a href="%s" title="%s">',
						esc_url( 'https://github.com/' . $contributor->login ),
						esc_html( sprintf( __( 'View %s', 'edd' ), $contributor->login ) )
					);
					$contributor_list .= sprintf( '<img src="%s" width="64" height="64" class="gravatar" alt="%s" />', esc_url( $contributor->avatar_url ), esc_html( $contributor->login ) );
					$contributor_list .= '</a>';
					$contributor_list .= sprintf( '<a class="web" href="%s" target="_blank">%s</a>', esc_url( 'https://github.com/' . $contributor->login ), esc_html( $contributor->login ) );
					$contributor_list .= '</a>';
					$contributor_list .= '</li>';
				}
				$contributor_list .= '</ul>';
				echo $contributor_list;
			}
			?>
			<a href="https://github.com/fpcorso/quiz_master_next" target="_blank" class="button-primary">View GitHub Repo</a>
		</div>
	</div>
<?php
}
?>
