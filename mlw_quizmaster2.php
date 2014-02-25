<?php

/*
Plugin Name: Quiz Master Next
Description: Use this plugin to add multiple quizzes, tests, or surveys to your website.
Version: 1.5.1
Author: Frank Corso
Author URI: http://www.mylocalwebstop.com/
Plugin URI: http://www.mylocalwebstop.com/
*/

/* 
Copyright 2014, My Local Webstop (email : fpcorso@mylocalwebstop.com)

Disclaimer of Warranties. 

The plugin is provided "as is". My Local Webstop and its suppliers and licensors hereby disclaim all warranties of any kind, 
express or implied, including, without limitation, the warranties of merchantability, fitness for a particular purpose and non-infringement. 
Neither My Local Webstop nor its suppliers and licensors, makes any warranty that the plugin will be error free or that access thereto will be continuous or uninterrupted.
You understand that you install, operate, and unistall the plugin at your own discretion and risk.
*/


///Files to Include
include("includes/mlw_quiz.php");
include("includes/mlw_dashboard.php");
include("includes/mlw_quiz_admin.php");
include("includes/mlw_quiz_options.php");
include("includes/mlw_quiz_install.php");
include("includes/mlw_results.php");
include("includes/mlw_results_details.php");
include("includes/mlw_tools.php");
include("includes/mlw_leaderboard.php");
include("includes/mlw_help.php");
include("includes/mlw_update.php");
include("includes/mlw_qmn_widgets.php");


///Activation Actions
add_action('admin_menu', 'mlw_add_menu');
add_action('init', 'mlw_quiz_update');
add_action('widgets_init', create_function('', 'return register_widget("Mlw_Qmn_Leaderboard_Widget");'));
add_shortcode('mlw_quizmaster', 'mlw_quiz_shortcode');
add_shortcode('mlw_quizmaster_leaderboard', 'mlw_quiz_leaderboard_shortcode');
register_activation_hook( __FILE__, 'mlw_quiz_activate');
register_deactivation_hook( __FILE__, 'mlw_quiz_deactivate');

//Setup Translations
function mlw_qmn_translation_setup() {
  load_plugin_textdomain( 'mlw_qmn_text_domain', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
}
add_action('plugins_loaded', 'mlw_qmn_translation_setup');


///Create Admin Pages
function mlw_add_menu()
{
	if (function_exists('add_menu_page'))
	{
		add_menu_page('Quiz Master Next', 'Quiz Dashboard', 'manage_options', __FILE__, 'mlw_generate_quiz_dashboard', 'dashicons-feedback');
		add_submenu_page(__FILE__, 'Quizzes', 'Quizzes', 'manage_options', 'mlw_quiz_admin', 'mlw_generate_quiz_admin');
		add_submenu_page(__FILE__, 'Quiz Options', 'Quiz Options', 'manage_options', 'mlw_quiz_options', 'mlw_generate_quiz_options');
		add_submenu_page(__FILE__, 'Quiz Results', 'Quiz Results', 'manage_options', 'mlw_quiz_results', 'mlw_generate_quiz_results');
		add_submenu_page(__FILE__, 'Quiz Result Details', 'Quiz Result Details', 'manage_options', 'mlw_quiz_result_details', 'mlw_generate_result_details');
		add_submenu_page(__FILE__, 'Tools', 'Tools', 'manage_options', 'mlw_quiz_tools', 'mlw_generate_quiz_tools');
		add_submenu_page(__FILE__, 'How-To', 'How-To', 'manage_options', 'mlw_how_to', 'mlw_generate_help_page');
	}
}
/*


*/
?>