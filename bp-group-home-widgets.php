<?php

/*
Plugin Name: BP Group Home Widgets
Plugin URI: https://buddyuser.com/plugin-bp-group-home-widgets
Description: BP Group Home Widgets adds group admin customizable widgets to the BP Nouveau Group Home page, allowing group admin to create text, video, members, activity and comments widgets specific to their home page. This plugin requires BuddyPress.
Version: 1.1.0
Text Domain: bp-group-home-widgets
Domain Path: /langs
Author: Venutius
Author URI: https://buddyuser.com
License: GPLv2

**************************************************************************

  Copyright (C) 2018 BuddyPress User

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General License for more details.

  You should have received a copy of the GNU General License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.

**************************************************************************


*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bpghw_enqueue_scripts() {
	wp_register_script( 'bpghw-translation', plugins_url( 'js/bpghw-group-widgets-front-end.js', __FILE__ ), array( 'jquery' ), '1.0.1', array('in_footer' => true) );
	wp_enqueue_style( 'bpghw_jquery_style', plugins_url( 'vendor/jquery/jquery-ui.css', __FILE__ ), array( ), '1.0.0' );
	
	$translation_array = array(
		'video_1'				=> esc_attr__( 'Video/Audio 1', 'bp-group-home-widgets'),
		'video_2'				=> esc_attr__( 'Video/Audio 2', 'bp-group-home-widgets'),
		'text_1'				=> esc_attr__( 'Text 1', 'bp-group-home-widgets'),
		'text_2'				=> esc_attr__( 'Text 2', 'bp-group-home-widgets'),
		'members'				=> esc_attr__( 'Our Members', 'bp-group-home-widgets'),
		'admin'					=> esc_attr__( 'Our Admin', 'bp-group-home-widgets'),
		'activity'				=> esc_attr__( 'Our Activity', 'bp-group-home-widgets'),
		'comments'				=> esc_attr__( 'Our Comments', 'bp-group-home-widgets'),
		'mention_us'			=> esc_attr__( 'Mention Us', 'bp-group-home-widgets'),
		'resetWidget'			=> esc_attr__( 'Resetting to defaults...', 'bp-group-home-widgets'),
		'submit'				=> esc_attr__( 'Submit', 'bp-group-home-widgets' ),
		'add'					=> esc_attr__( 'Add Widget', 'bp-group-home-widgets'),
		'change' 				=> esc_attr__( 'Change', 'bp-group-home-widgets' ),
		'cancel'				=> esc_attr__( 'Cancel', 'bp-group-home-widgets'),
		'success'				=> esc_attr__( 'Success!', 'bp-group-home-widgets'),
		'successRefresh'		=> esc_attr__( 'Success! Please refresh the window to see.', 'bp-group-home-widgets'),
		'tryAgain'				=> esc_attr__( 'Please try again...', 'bp-group-home-widgets' ),
		'enterVideo'			=> esc_attr__( 'Please paste a URL', 'bp-group-home-widgets' ),
		'addingVideo'			=> esc_attr__( 'Adding Video ...', 'bp-group-home-widgets'),
		'addingMembers'			=> esc_attr__( 'Adding Members ...', 'bp-group-home-widgets'),
		'addingAdmin'			=> esc_attr__( 'Adding Members ...', 'bp-group-home-widgets'),
		'addingActivity'		=> esc_attr__( 'Adding Admin ...', 'bp-group-home-widgets'),
		'addingComments'		=> esc_attr__( 'Adding Comments ...', 'bp-group-home-widgets'),
		'addingMentionUs'		=> esc_attr__( 'Adding Mention Us ...', 'bp-group-home-widgets'),
		'deleting'				=> esc_attr__( 'Deleting...', 'bp-group-home-widgets'),
		'clearPreset'			=> esc_attr__( 'Clear Preset', 'bp-group-home-widgets'),
		'savePreset'			=> esc_attr__( 'Save as Preset', 'bp-group-home-widgets'),
		'clearingPreset'		=> esc_attr__( 'Clearing Preset...', 'bp-group-home-widgets'),
		'savingPreset'			=> esc_attr__( 'Saving as Preset...', 'bp-group-home-widgets'),
		'addingText'			=> esc_attr__( 'Adding Text ...', 'bp-group-home-widgets')
		);
	
	wp_localize_script( 'bpghw-translation', 'bpghw_translate', $translation_array );
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'jquery-ui-core' );
    wp_enqueue_script( 'jquery-ui-widget' );
    wp_enqueue_script( 'jquery-ui-sortable' );
    wp_enqueue_script( 'jquery-ui-draggable' );
    wp_enqueue_script( 'jquery-ui-droppable' );
	wp_enqueue_script( 'bpghw-translation');

	wp_localize_script( 'bpghw-translation', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php'), 'check_nonce' => wp_create_nonce('bpghw-nonce') ) );
	wp_enqueue_style( 'bpghw_style', plugins_url( 'vendor/jquery/jquery-ui.css', __FILE__ ), array(), '1.0.0' );
	wp_enqueue_style( 'bpghw_style', plugin_dir_path( 'css/bpghw.css', __FILE__), array(), '1.0.0' );

}
add_action( 'wp_enqueue_scripts', 'bpghw_enqueue_scripts' );

// Localization
function bpghw_localization() {

	load_plugin_textdomain('bp-group-home-widgets', false, dirname(plugin_basename( __FILE__ ) ).'/langs/' );
	
}
 
add_action('init', 'bpghw_localization');

// Load Ajax

include_once( plugin_dir_path(__FILE__) . '/includes/bpghw-ajax.php' );

// Load Widget Class

include_once( plugin_dir_path(__FILE__) . '/includes/bpghw-widget-class.php' );

// Load Functions

include_once( plugin_dir_path(__FILE__) . '/includes/bpghw-functions.php' );

// Register Widget

function bpghw_register_widget() {
	
	register_widget( 'BP_Group_Home_Widgets' );
	
}

add_action( 'widgets_init', 'bpghw_register_widget' );


?>