<?php
/*
* @package bp-group-home-widgets
*/

if(!defined('ABSPATH')) {
	exit;
}


//AJAX add video
function bpghw_moveable_widgets() {
	
	wp_verify_nonce( $_POST['security'], 'bpghw-nonce');
	
	$widget_positions = $_POST['positions'];

	if ( ! is_array( $widget_positions ) || empty( $widget_positions ) ) {
		
		esc_attr_e('Input data incorrect', 'bp-group-home-widgets');
		die();
		
	}

	$user_id = get_current_user_id();
	$group_id = bp_get_current_group_id();
	
	if ( !$group_id ) return false;
	
	$group = groups_get_group( $group_id );
	$group_creator_id = $group->creator_id;
	
	if ( $user_id != $group_creator_id ) {
		echo esc_attr__( 'Not correct user', 'bp-group-home-widgets' );
		die();
	}

	$old_widget_data = groups_get_groupmeta( $group_id, 'bpghw_widget_data' );
	$widgets = array( 'video_1', 'video_2', 'text_1', 'text_2', 'video_1', 'video_2', 'admin', 'members', 'activity', 'comments', 'mention_us' );
	foreach ( $widget_positions as $widget ) {
		$widget_name = esc_attr($widget[0]);
		$widget_position = esc_attr($widget[1]);
		if ( in_array ( $widget_name, $widgets ) ) {
			$old_widget_data[$widget_name]['position'] = $widget_position;
		}
	}
	
	$update = groups_update_groupmeta( $group_id, 'bpghw_widget_data', $old_widget_data );
	
	if ( $update ) {
		
		esc_attr_e('Success', 'bp-group-home-widgets');
	
	} else {
		
		esc_attr_e('Failed', 'bp-group-home-widgets');
	
	}

	die();

}

add_action( 'wp_ajax_bpghw_moveable_widgets', 'bpghw_moveable_widgets');

function bpghw_reset_widget() {
	
	wp_verify_nonce( $_POST['security'], 'bpghw-nonce');
	
	$user_id = bp_loggedin_user_id();;
	
	$widget_defaults = bpghw_get_defaults();

	$group_id = bp_get_current_group_id();
	
	if ( !$group_id ) return false;
	
	$group = groups_get_group( $group_id );
	$group_creator_id = $group->creator_id;
	
	if ( $user_id != $group_creator_id ) {
		echo esc_attr__( 'Not correct user', 'bp-group-home-widgets' );
		die();
	}
	
	if ( isset( $group_id ) && is_numeric( $group_id ) ) {
		
		$update = groups_update_groupmeta( $group_id, 'bpghw_widget_data', $widget_defaults );
		
	} else {
		
		$update = 0;
		
	}

	echo esc_attr($update);

	die();

}

add_action( 'wp_ajax_bpghw_reset_widget', 'bpghw_reset_widget');

// Clear widget
function bpghw_clear_widget() {
	
	wp_verify_nonce( $_POST['security'], 'bpghw-nonce');
	
	$user_id = bp_loggedin_user_id();
	$widget_name = sanitize_text_field($_POST['name']);
	$defaults = bpghw_get_defaults();

	$group_id = bp_get_current_group_id();
	$old_widget_data = groups_get_groupmeta( $group_id, 'bpghw_widget_data' );
	
	if ( !$group_id ) return false;
	
	$group = groups_get_group( $group_id );
	$group_creator_id = $group->creator_id;
	
	if ( $user_id != $group_creator_id ) {
		echo esc_attr__( 'Not correct user', 'bp-group-home-widgets' );
		die();
	}
	
	if ( isset( $group_id ) && is_numeric( $group_id ) && isset( $widget_name ) ) {
		$video_widgets = bpghw_get_widgets( 'video');
		$text_widgets = bpghw_get_widgets( 'text');
		$buddypress_widgets = bpghw_get_widgets( 'buddypress');
		if ( in_array ( $widget_name, $video_widgets ) ) {
			$old_widget_data[$widget_name]['link'] = '';
			$old_widget_data[$widget_name]['title'] = $defaults[$widget_name]['title'];
			$old_widget_data[$widget_name]['visibility'] = 'none';
			$update = groups_update_groupmeta( $group_id, 'bpghw_widget_data', $old_widget_data );
		} else if ( in_array( $widget_name, $text_widgets ) ) {
			$old_widget_data[$widget_name]['content'] = '';
			$old_widget_data[$widget_name]['visibility'] = 'none';
			$old_widget_data[$widget_name]['title'] = $defaults[$widget_name]['title'];
			$update = groups_update_groupmeta( $group_id, 'bpghw_widget_data', $old_widget_data );
		} else if ( in_array( $widget_name, $buddypress_widgets ) ) {
			$old_widget_data[$widget_name]['visibility'] = 'none';
			$old_widget_data[$widget_name]['title'] = $defaults[$widget_name]['title'];
			$update = groups_update_groupmeta( $group_id, 'bpghw_widget_data', $old_widget_data );
		}
		
	} else {
		
		$update = 0;
		
	}

	echo esc_attr($update);

	die();

}

add_action( 'wp_ajax_bpghw_clear_widget', 'bpghw_clear_widget');

//AJAX add video
function bpghw_add_video() {
	
	wp_verify_nonce( $_POST['security'], 'bpghw-nonce');
	
	$group_id = bp_get_current_group_id();
	
	if ( !$group_id ) return false;
	
	$user_id = bp_loggedin_user_id();
	$group = groups_get_group( $group_id );
	$group_creator_id = $group->creator_id;
	
	if ( $user_id != $group_creator_id ) {
		echo esc_attr__( 'Not correct user', 'bp-group-home-widgets' );
		die();
	}

	global $bp;
	//$group_id = sanitize_text_field($_POST['groupId']);
	$widget_name = sanitize_text_field($_POST['name']);
	$video_url = esc_url_raw($_POST['videoURL']);
	$widget_title = sanitize_text_field($_POST['title']);
	
	$old_widget_data = groups_get_groupmeta( $group_id, 'bpghw_widget_data' );
	$old_widget_data = $old_widget_data;

	if ( isset( $group_id ) && is_numeric( $group_id ) && isset( $widget_name ) && isset( $video_url ) && bpghw_check_url( $video_url ) ) {
		
		$old_widget_data[$widget_name]['link'] = $video_url;
		$old_widget_data[$widget_name]['title'] = $widget_title;
		$old_widget_data[$widget_name]['visibility'] = 'block';
		$update = groups_update_groupmeta( $group_id, 'bpghw_widget_data', $old_widget_data );
		
	} else {
		
		$update = 0;
		
	}
	
	if ( $update ) {
		$output = bpghw_get_video_content( $group_id, $video_url, $widget_name, 600 );
	} else {
		$output = esc_attr__( 'Video not saved', 'bp-group-home-widgets' );
	}

	echo esc_attr($output);

	die();

}

add_action( 'wp_ajax_bpghw_add_video', 'bpghw_add_video');

//AJAX add text and make clickable
function bpghw_add_text() {
	
	wp_verify_nonce( $_POST['security'], 'bpghw-nonce');
	
	global $bp;
	
	$user_id = bp_loggedin_user_id();
	$widget_name = sanitize_text_field($_POST['name']);
	$widget_title = sanitize_text_field($_POST['title']);
	$text_content = wp_filter_post_kses( $_POST['content'] );
	$text_content = nl2br( make_clickable( $text_content ) );

	$group_id = bp_get_current_group_id();
	
	if ( !$group_id ) return false;

	$group = groups_get_group( $group_id );
	$group_creator_id = $group->creator_id;
	
	if ( $user_id != $group_creator_id ) {
		echo esc_attr__( 'Not correct user', 'bp-group-home-widgets' );
		die();
	}

	$old_widget_data = groups_get_groupmeta( $group_id, 'bpghw_widget_data' );
	if ( isset( $user_id ) && is_numeric( $user_id ) && isset( $widget_name ) && isset( $text_content ) ) {
		
		$old_widget_data[$widget_name]['content'] = $text_content;
		$old_widget_data[$widget_name]['title'] = $widget_title;
		$old_widget_data[$widget_name]['visibility'] = 'block';
		$update = groups_update_groupmeta( $group_id, 'bpghw_widget_data', $old_widget_data );
		
	} else {
		
		$update = 0;
		
	}

	if ( $update ) {
		$output = do_shortcode( str_replace( "\'", "'", $text_content ) ); 
	} else {
		$output = '';
	}

	echo $output;

	die();

}

add_action( 'wp_ajax_bpghw_add_text', 'bpghw_add_text');

//AJAX add follow
function bpghw_add_widget() {
	
	wp_verify_nonce( $_POST['security'], 'bpghw-nonce');
	
	$user_id = bp_loggedin_user_id();
	$group_id = bp_get_current_group_id();
	
	if ( !$group_id ) return false;

	$group = groups_get_group( $group_id );
	$group_creator_id = $group->creator_id;
	
	if ( $user_id != $group_creator_id ) {
		echo esc_attr__( 'Not correct user', 'bp-group-home-widgets' );
		die();
	}

	$widget_name = esc_attr($_POST['name']);
	$widget_title = esc_attr($_POST['title']);
	$max = esc_attr($_POST['max']);
	$maxposts = esc_attr($_POST['maxPosts']);
	$avatar_img_size = esc_attr($_POST['avatarSize']);
	$old_widget_data = groups_get_groupmeta( $group_id, 'bpghw_widget_data' );

	if ( isset( $group_id ) && is_numeric( $group_id ) && isset( $widget_name ) ) {
		
		if ( $widget_name == 'members' || $widget_name == 'admin' ) {
			$old_widget_data[$widget_name]['max_users'] = $max;
			$old_widget_data[$widget_name]['image_size'] = $avatar_img_size;
			} else if ( $widget_name == 'activity' || $widget_name == 'comments' ) {
			$old_widget_data[$widget_name]['max_posts'] = $maxposts;
		}
		$old_widget_data[$widget_name]['title'] = $widget_title;
		$old_widget_data[$widget_name]['visibility'] = 'block';
		$update = groups_update_groupmeta( $group_id, 'bpghw_widget_data', $old_widget_data );
		
	} else {
		
		$update = 0;
		
	}

	if ( $update && $widget_name == 'members') {
		$output = bpghw_get_members_output( $group_id, $max, $avatar_img_size ); 
	} else if ( $update && $widget_name == 'admin') {
		$output = bpghw_get_admin_output( $group_id, $max, $avatar_img_size ); 
	} else if ( $update && $widget_name == 'activity') {
		$output = bpghw_get_activity_output( $group_id, $maxposts, 'activity' ); 
	} else if ( $update && $widget_name == 'comments') {
		$output = bpghw_get_activity_output( $group_id, $maxposts, 'comments' ); 
	} else if ( $update && $widget_name == 'mention_us') {
		$output = bpghw_get_mention_us_output(); 
	} else {
		$output = esc_attr__( 'The selected widget does not seem to exist!', 'bp-group-home-widgets' );
	}

	echo $output;

	die();

}

add_action( 'wp_ajax_bpghw_add_widget', 'bpghw_add_widget');

// Clear text
function bpghw_clear_text() {
	
	wp_verify_nonce( $_POST['security'], 'bpghw-nonce');
	
	$user_id = bp_loggedin_user_id();
	$group_id = bp_get_current_group_id();
	
	if ( !$group_id ) return false;

	$group = groups_get_group( $group_id );
	$group_creator_id = $group->creator_id;
	
	if ( $user_id != $group_creator_id ) {
		echo esc_attr__( 'Not correct user', 'bp-group-home-widgets' );
		die();
	}

	$widget_name = sanitize_text_field($_POST['name']);
	
	$old_widget_data = groups_get_groupmeta( $group_id, 'bpghw_widget_data' );
	//$old_widget_data = $old_widget_data[0];

	$defaults = bpghw_get_defaults();

	if ( isset( $group_id ) && is_numeric( $group_id ) && isset( $widget_name ) ) {
		
		$old_widget_data[$widget_name]['content'] = '';
		$old_widget_data[$widget_name]['visibility'] = 'none';
		$old_widget_data[$widget_name]['title'] = $defaults[$widget_name]['title'];
		$update = groups_update_groupmeta( $group_id, 'bpghw_widget_data', $old_widget_data );
		
	} else {
		
		$update = 0;
		
	}

	echo esc_attr($update);

	die();

}

add_action( 'wp_ajax_bpghw_clear_text', 'bpghw_clear_widget');


//Check submitted URL for correct formatting
function bpghw_check_url( $url ) {
	
    $path = parse_url($url, PHP_URL_PATH);
    $encoded_path = array_map('urlencode', explode('/', $path));
    $url = str_replace($path, implode('/', $encoded_path), $url);

    return filter_var($url, FILTER_VALIDATE_URL) ? true : false;
}
	
function bpghw_update_presets() {
	
	wp_verify_nonce( $_POST['security'], 'bpghw-nonce');
	$action = sanitize_text_field( $_POST['update'] );
	
	$user_id = bp_loggedin_user_id();
	$group_id = bp_get_current_group_id();
	
	if ( !$group_id ) return false;

	$group = groups_get_group( $group_id );
	$group_creator_id = $group->creator_id;
	
	if ( $user_id != $group_creator_id ) {
		echo esc_attr__( 'Not correct user', 'bp-group-home-widgets' );
		die();
	}
	
	
	if ( isset( $action ) && $action === 'save' ) {
		if ( current_user_can( 'manage_options' ) ) {
			
			$user_id = bp_loggedin_user_id();
			$presets = groups_get_groupmeta( $group_id, 'bpghw_widget_data');
			update_option( 'bpghw_presets', $presets );
			echo 1;
			die();
		
		}
	
	} else if ( isset( $action ) && $action === 'clear' ) {
		
		if ( current_user_can( 'manage_options' ) ) {
			
			delete_option( 'bpghw_presets' );
			echo 1;
			die();
		
		}
	
	}
	
	echo 0;
	die();
	
}

add_action( 'wp_ajax_bpghw_update_presets', 'bpghw_update_presets');
	
	