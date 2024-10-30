<?php

/*
BP Group Home Widgets Functions

Text Domain: bp-group-home-widgets

*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// Load widget settings for user
function bpghw_get_widget_data( $displayed_group_id = '' ) {
	
	$widget_default_data = bpghw_get_defaults();
	
	if ( $displayed_group_id == '' ) {
	
		$displayed_group_id = bp_get_current_group_id();
		
	}
	
	$widget_data = groups_get_groupmeta( $displayed_group_id, 'bpghw_widget_data');

	// If first time access of the profile page, user settings need to be created
	if ( ! $widget_data ) {
		
		groups_update_groupmeta( $displayed_group_id, 'bpghw_widget_data', $widget_default_data );
		$widget_data = groups_get_groupmeta( $displayed_group_id, 'bpghw_widget_data');
		//$widget_data = $widget_data[0];
		
	} else {
		
		$widget_data = array_merge ( $widget_default_data, $widget_data );
		groups_update_groupmeta( $displayed_group_id, 'bpghw_widget_data', $widget_data );
		
	}
	
	return $widget_data;
	
}

// Establish global user defaults for user widgets
function bpghw_get_defaults() {
	
	$widget_default_data = Array (
		'video_1' => Array (
			'name' 			=> 'video_1',
			'title'			=> esc_attr__('Video/Audio Player 1', 'bp-group-home-widgets' ),
			'visibility'	=> 'none',
			'link'			=> '',
			'index' 		=> 1,
			'position' 		=> 1 ),
		'video_2' => Array (
			'name' 			=> 'video_2',
			'title'			=> esc_attr__('Video/Audio Player 2', 'bp-group-home-widgets' ),
			'visibility' 	=> 'none',
			'link'			=> '',
			'index' 		=> 2,
			'position' 		=> 2 ),
		'text_1' => Array (
			'name' 			=> 'text_1',
			'title'			=> esc_attr__('Text Widget 1', 'bp-group-home-widgets' ),
			'visibility' 	=> 'none',
			'content'		=> '',
			'index' 		=> 3,
			'position' 		=> 3 ),
		'text_2' => Array (
			'name' 			=> 'text_2',
			'title'			=> esc_attr__('Text Widget 2', 'bp-group-home-widgets' ),
			'visibility' 	=> 'none',
			'content'		=> '',
			'index' 		=> 4,
			'position' 		=> 4 ),
		'admin' => Array (
			'name' 			=> 'admin',
			'title'			=> esc_attr__('Our Admin', 'bp-group-home-widgets' ),
			'visibility' 	=> 'none',
			'max_users'		=> 5,
			'image_size'	=> 'thumb',
			'index' 		=> 5,
			'position' 		=> 5 ),	
		'members' => Array (
			'name' 			=> 'members',
			'title'			=> esc_attr__('Our Members', 'bp-group-home-widgets' ),
			'visibility' 	=> 'none',
			'max_users'		=> 10,
			'image_size'	=> 'thumb',
			'index' 		=> 6,
			'position' 		=> 6 ),
	
		'activity' => Array (
			'name' 			=> 'activity',
			'title'			=> esc_attr__('Our Activity', 'bp-group-home-widgets' ),
			'visibility' 	=> 'none',
			'max_posts'		=> 5,
			'index' 		=> 7,
			'position' 		=> 7 ),
		'mention_us' => Array (
			'name' 			=> 'mention_us',
			'title'			=> esc_attr__('Mention Us', 'bp-group-home-widgets' ),
			'visibility' 	=> 'none',
			'index' 		=> 8,
			'position' 		=> 8 ),
		'comments' => Array (
			'name' 			=> 'comments',
			'title'			=> esc_attr__('Our Comments', 'bp-group-home-widgets' ),
			'visibility' 	=> 'none',
			'max_posts'		=> 5,
			'index' 		=> 9,
			'position' 		=> 9 )
	);
	
	$presets = get_option( 'bpghw_presets' );
	//$presets = $presets;
	
	if ( isset( $presets ) && is_array( $presets ) ) {
		
		return $presets;
		
	}

	
	return $widget_default_data;
	
}

function bpghw_get_widgets( $type ) {
	
	switch ( $type ) {
	
		case 'text' :
			$response = array( 'text_1', 'text_2' );
			break;

		case 'video' : 
			$response = array( 'video_1', 'video_2' );
			break;
		
		case 'buddypress' :
			$response = array( 'admin', 'members', 'activity', 'comments', 'mention_us' );
			break;
			
	}
	
	return $response;
}

function bpghw_get_mention_us_output() {
	
	$template = plugin_dir_path( __DIR__ ) . 'templates/activity/post-form.php';
	ob_start();
	load_template( $template, false );
	$content = ob_get_contents();
	ob_end_clean();
	echo $content;
	
}

function bpghw_activity_get_comments( $args = '' ) {
	global $activities_template;

	if ( empty( $activities_template->activity->children ) ) {
		return false;
	}

	bpghw_activity_recurse_comments( $activities_template->activity );
}

function bpghw_activity_recurse_comments( $comment ) {
	global $activities_template;

	/**
	 * Filters the opening tag for the template that lists activity comments.
	 *
	 * @since 1.6.0
	 *
	 * @param string $value Opening tag for the HTML markup to use.
	 */
	echo apply_filters( 'bp_activity_recurse_comments_start_ul', '<ul>' );
	foreach ( (array) $comment->children as $comment_child ) {

		// Put the comment into the global so it's available to filters.
		$activities_template->activity->current_comment = $comment_child;

		$template = plugin_dir_path( __DIR__ ) . 'templates/activity/comment.php';

		load_template( $template, false );

		unset( $activities_template->activity->current_comment );
	}
}

function bpghw_get_activity_output( $group_id, $max_posts, $type = 'activity' ) {
	

	if ( $type == 'activity' ) {
		$qstring = '&per_page=' . $max_posts;
	} else if ( $type == 'comments' ) {
		$qstring = '&per_page=' . $max_posts . '&action=activity_update';
	}
	
	if ( bp_has_activities( bp_ajax_querystring( 'activity' ) . $qstring ) ) : 
		if ( bp_get_theme_package_id() == 'nouveau' ) :
		bp_nouveau_before_loop(); ?>
		
		<?php if ( empty( $_POST['page'] ) || 1 === (int) $_POST['page'] ) : ?>
			<ul class="activity-list item-list bp-list">
		<?php endif; ?>

		<?php
		while ( bp_activities() ) :
			bp_the_activity();
		?>

			<li class="<?php esc_attr(bp_activity_css_class()); ?>" id="activity-<?php bp_activity_id(); ?>" data-bp-activity-id="<?php bp_activity_id(); ?>" data-bp-timestamp="<?php bp_nouveau_activity_timestamp(); ?>">

				<div class="activity-avatar item-avatar">

					<a href="<?php bp_activity_user_link(); ?>">

						<?php bp_activity_avatar( array( 'type' => 'full' ) ); ?>

					</a>

				</div>

				<div class="activity-content">

					<div class="activity-header">

						<?php bp_activity_action(); ?>

					</div>

					<?php if ( bp_nouveau_activity_has_content() ) : ?>

						<div class="activity-inner">

							<?php bp_nouveau_activity_content(); ?>

						</div>

					<?php endif; ?>

					<?php if ( bp_activity_get_comment_count() || ( is_user_logged_in() && ( bp_activity_can_comment() || bp_is_single_activity() ) ) ) : ?>

						<div class="activity-comments">

							<?php bpghw_activity_get_comments(); ?>

						</div>

					<?php endif; ?>

				</div>

			</li>
		
		<?php endwhile; ?>

			</ul>
		<?php elseif ( bp_get_theme_package_id() == 'legacy' ) :
				if ( empty( $_POST['page'] ) ) : ?>

				<ul id="activity-stream" class="activity-list item-list">

			<?php endif; ?>

			<?php
			while ( bp_activities() ) : bp_the_activity();

				bpghw_legacy_entry(); ?>

			<?php endwhile; ?>

			<?php if ( empty( $_POST['page'] ) ) : ?>

				</ul>

			<?php endif; ?>

			<?php else : ?>

				<div id="message" class="info">
					<p><?php esc_attr_e( 'Sorry, there was no activity found. Please try a different filter.', 'bp-group-home-widgets' ); ?></p>
				</div>

		<?php endif;
		endif;
	
}

// BP Legacy activity entry
function bpghw_legacy_entry() {
	
	
	?>

	<li class="<?php bp_activity_css_class(); ?>" id="activity-<?php bp_activity_id(); ?>">
		<div class="activity-avatar">
			<a href="<?php bp_activity_user_link(); ?>">

				<?php bp_activity_avatar(); ?>

			</a>
		</div>

		<div class="activity-content">

			<div class="activity-header">

				<?php bp_activity_action(); ?>

			</div>

			<?php if ( bp_activity_has_content() ) : ?>

				<div class="activity-inner">

					<?php bp_activity_content_body(); ?>

				</div>

			<?php endif; ?>

			<div class="activity-meta">

				<?php if ( bp_get_activity_type() == 'activity_comment' ) : ?>

					<a href="<?php bp_activity_thread_permalink(); ?>" class="button view bp-secondary-action"><?php esc_attr_e( 'View Conversation', 'bp-group-home-widgets' ); ?></a>

				<?php endif; ?>

			</div>

		</div>

		<?php if ( ( bp_activity_get_comment_count() || bp_activity_can_comment() ) || bp_is_single_activity() ) : ?>

			<div class="activity-comments">

				<?php bp_activity_comments(); ?>

			</div>

		<?php endif; ?>

	</li>

<?php
	
}

// Get oembed Iframe for videos
function bpghw_get_video_content( $group_id, $url, $name, $width )	{
	$width = (int)$width;
	$service = $multiplier = $height = $control = '';

	// Determine the height from the width in the Widget options

	$multiplier = .75; 
	
	if ( !empty( $width ) && !empty( $multiplier ) ) {
		
		if ( !empty( $url ) ) {

			$host 		= parse_url( $url, PHP_URL_HOST );
			$exp		= explode( '.', $host );
			$service 	= ( count( $exp ) >= 3 ? $exp[1] : $exp[0] );
			
		 } // End of $url check
			
		$control 	= ( $service == 'youtube' || $service == 'youtu' ? 25 : 0 );
		$height 	= ( ( $width * $multiplier ) + $control );
	
	
	} // End of empty checks

	if ( empty( $url ) || empty( $service ) ) {

	} else {

		$oembed = bpghw_oembed_transient( $url, $service, $width, $height );

		if ( !$oembed && $service == 'facebook' ) {
		
			// Input Example: https://www.facebook.com/photo.php?v=10201027508430408

			$explode = explode( '=', $url );
			$videoID = end( $explode );


			?><iframe src="https://www.facebook.com/video/embed?video_id=<?php echo esc_attr($videoID); ?>" width="<?php echo esc_attr($width); ?>" height="<?php echo esc_attr($height); ?>" frameborder="0"></iframe><?php
					
		} else if ( ( !$oembed && $service == 'youtube' ) || ( !$oembed && $service == 'youtu' ) ) {
		
			// Input Example: https://www.facebook.com/photo.php?v=10201027508430408

			$explode = explode( '=', $url );
			$videoID = end( $explode );


			?><iframe width='<?php echo esc_attr($width); ?>' height='<?php echo esc_attr($height); ?>' src='//www.youtube.com/embed/<?php echo esc_attr($videoID); ?>&loop=0&rel=0' frameborder='0' allowfullscreen></iframe><?php
					
		} else {

			// Input Examples: 
			// 		http://www.youtube.com/watch?v=YYYJTlOYdm0
			// 		http://youtu.be/YYYJTlOYdm0
			// 		https://vimeo.com/37708663
			// 		http://www.flickr.com/photos/riotking/2550468661
			// 		http://blip.tv/juliansmithtv/julian-smith-lottery-6362952
			// 		http://www.dailymotion.com/video/xull3h_monster-roll_shortfilms
			// 		http://www.ustream.tv/channel/3777978
			// 		http://www.ustream.tv/recorded/32219761
			// 		http://www.funnyordie.com/videos/5764ccf637/daft-punk-andrew-the-pizza-guy?playlist=featured_videos
			// 		http://www.hulu.com/watch/486928
			// 		http://revision3.com/destructoid/bl2-dlc-leak-tiny-tinas-assault-on-dragon-keep
			// 		http://www.viddler.com/v/bdce8c7
			// 		http://qik.com/video/38782012
			// 		http://home.wistia.com/medias/e4a27b971d
			// 		http://wordpress.tv/2013/10/26/chris-wilcoxson-how-to-build-your-first-widget/
	
			echo $oembed;

		} // End of embed codes

	} // End of $url & $service check
	
}

function bpghw_oembed_transient( $url, $service = '', $width = '', $height = '' ) {

	//require_once( ABSPATH . WPINC . '/class-oembed.php' );

	if ( empty( $url ) ) { return FALSE; }

	$key 	= md5( $url );
	//$oembed = get_transient( 'bpphw_' . $key );

	if ( $url ) {

		$oembed = wp_oembed_get( $url );

		if ( !$oembed ) { return FALSE; }

		//set_transient( 'bpphw_' . $key, $oembed, HOUR_IN_SECONDS );

	}

	return $oembed;

} // End of oembed_transient()

// Get members widget output
function bpghw_get_members_output( $group_id, $max_users, $avatar_size = 'full' ) {

	if ( empty( $max_users ) ) {
		$max_users = 10;
	}
	if ( empty( $group_id ) ) {
		$group_id = bp_current_group_id();
	}
	if ( bp_group_has_members( array( 'per_page' => $max_users, 'group_role' => array( 'member', 'mod', 'admin' ), 'group_id' => $group_id ) ) ) {

?>

		<div class="avatar-block" style="display: flex; margin-left:auto;margin-right:auto;">
			<?php while ( bp_group_members() ) : bp_group_the_member() ?>
				<div style="display:grid;margin-left:auto;margin-right:auto;text-align:center;" class="avatar-grid">
					<div style="margin-left:5px; margin-right:5px;">
						<div style="margin-left:auto; margin-right:auto;" class="item-avatar">
							<a href="<?php bp_group_member_url() ?>" title="<?php bp_group_member_name() ?>"><?php bp_group_member_avatar_thumb( array('type' => $avatar_size )) ?></a>
						</div>
					</div>
					<div style="margin-left:5px; margin-right:5px;">
						<div  style="margin-left:auto; margin-right:auto;" class="item-username">
							<a href="<?php bp_group_member_url() ?>" title="<?php bp_group_member_name() ?>"><?php bp_group_member_name() ?></a>
						</div>
					</div>
				</div>
			<?php endwhile; ?>
		</div>

<?php
	}
}

// Get friends widget output
function bpghw_get_admin_output( $group_id, $max_users, $avatar_size = 'full' ) {

	if ( empty( $max_users ) ) {
		$max_users = 5;
	}
	if ( empty( $group_id ) ) {
		$group_id = bp_current_group_id();
	}
	if ( bp_group_has_members( array( 'per_page' => $max_users, 'group_role' => array( 'mod', 'admin' ), 'group_id' => $group_id ) ) ) {

?>

		<div class="avatar-block" style="display: flex; margin-left:auto;margin-right:auto;">
			<?php while ( bp_group_members() ) : bp_group_the_member() ?>
				<div style="display:grid;margin-left:auto;margin-right:auto;text-align:center;" class="avatar-grid">
					<div style="margin-left:5px; margin-right:5px;">
						<div style="margin-left:auto; margin-right:auto;" class="item-avatar">
							<a href="<?php bp_group_member_url() ?>" title="<?php bp_group_member_name() ?>"><?php bp_group_member_avatar_thumb( array('type' => $avatar_size )) ?></a>
						</div>
					</div>
					<div style="margin-left:5px; margin-right:5px;">
						<div  style="margin-left:auto; margin-right:auto;" class="item-username">
							<a href="<?php bp_group_member_url() ?>" title="<?php bp_group_member_name() ?>"><?php bp_group_member_name() ?></a>
						</div>
					</div>
				</div>
			<?php endwhile; ?>
		</div>

<?php
	}
}

//Add Profile page widget areas
function bpghw_activate_legacy_widget_areas() {
	
	if ( bp_get_theme_package_id() == 'legacy' ) {
		
		if ( function_exists('register_sidebar') ) {

			register_sidebar(array(
				'name' 			=> esc_attr__('BP Group Activity Top', 'bp-group-home-widgets'),
				'id' 			=> 'bpghw-activity-top-sidebar',
				'before_widget' => '<div class = "widgetizedArea">',
				'after_widget' 	=> '</div>',
				'before_title' 	=> '<h3>',
				'after_title' 	=> '</h3>',
			));
			
			register_sidebar(array(
				'name' 			=> esc_attr__('BP Group Activity Bottom', 'bp-group-home-widgets'),
				'id' 			=> 'bpghw-activity-bottom-sidebar',
				'before_widget' => '<div class = "widgetizedArea">',
				'after_widget' 	=> '</div>',
				'before_title' 	=> '<h3>',
				'after_title' 	=> '</h3>',
			));
			
		}
		
	}
		
}

add_action( 'widgets_init', 'bpghw_activate_legacy_widget_areas' );

// Implement profile page widget area
function bpghw_add_widget_to_activity_bottom() {
	
	if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("bpghw-activity-bottom-sidebar") ) {
		echo '<div class="bp-group-activity-bottom-sidebar">';
			dynamic_sidebar('bpghw-activity-bottom-sidebar');
		echo '</div>';
	}
	
}

function bpghw_add_widget_to_activity_top() {
	
	if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("bpghw-activity-top-sidebar") ) {
		echo '<div class="bp-group-activity-top-sidebar">';
			dynamic_sidebar('bpghw-activity-top-sidebar');
		echo '</div>';
	}

}

add_action( 'bp_after_group_activity_content', 'bpghw_add_widget_to_activity_bottom' );
add_action( 'bp_before_group_activity_content', 'bpghw_add_widget_to_activity_top' );



