<?php
/*
BP Group Home Widgets - Widget Functions

Text Domain: bp-group-home-widgets

*/

class BP_Group_Home_Widgets extends WP_Widget {



	/**
	 * Sets up the widgets name etc
	 */
	 
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'bp_group_home_widgets',
			'description' => esc_attr__( 'Adds sortable and selectable widgets to BP Nouveau Group Home page', 'bp-group-home-widgets' ),
		);
		parent::__construct( 'bp_group_home_widgets', esc_attr__( 'BP Group Home Widgets', 'bp-group-home-widgets' ), $widget_ops );
	}


	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		
		$group_id = bp_get_current_group_id();
		$group = groups_get_group( $group_id );
		$group_creator_id = $group->creator_id;
		
		$logged_in_user_id = bp_loggedin_user_id();
		$title_text = esc_attr__( 'Title:', 'bp-group-home-widgets' );
		$video_widgets = bpghw_get_widgets( 'video' );
		$text_widgets = bpghw_get_widgets( 'text' );
		$buddypress_widgets = bpghw_get_widgets( 'buddypress' );
		$displayed = 0;
		$presets = get_option( 'bpghw_presets' );
		
		if ( bp_is_group() ) {
			echo $args['before_widget'];
			if ( ! empty( $instance['title'] && $logged_in_user_id == $group_creator_id ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}
			

			$widget_data = bpghw_get_widget_data( $group_id );
			
			if ( !$widget_data && isset( $presets ) && is_array( $presets) ) $widget_data = $presets;
			
			echo '<ul id="sortable-ghw" style="margin-left: 0px;">';
			
			For ( $n = 1; $n <= 12; $n++ ) {
				foreach ( $widget_data as $widget ) {
					
					if ( $widget['position'] == $n ) {
						if ( ( in_array( $widget['name'], $video_widgets ) && ! $instance['disable_videos'] ) ||  ( in_array( $widget['name'], $text_widgets ) && ! $instance['disable_text'] ) || ( $widget['name'] == 'admin' && ! $instance['disable_buddypress'] && bp_is_active( 'groups' ) ) || ( $widget['name'] == 'members' && ! $instance['disable_buddypress'] && bp_is_active( 'groups' ) ) || ( ( $widget['name'] == 'activity' || $widget['name'] == 'comments' || $widget['name'] == 'mention_us' ) && ! $instance['disable_buddypress'] && bp_is_active( 'activity' ) ) ) {
							
							// Create widget structure
							echo '<li id="bpghw_' . $widget['name'] . '" data-name="' . $widget['name'] . '" data-position="' . $widget['position'] . '" data-index="' . $widget['index'] . '" style="display: ' . $widget['visibility'] . '; text-align: left;" class="bpghw_li_class "><span class="ui-icon"></span>';
							echo '<hr>';
							echo '<h3 style="text-align: center;" class="ui-icon-arrowthick-2-n-s" id="bpghw_desc_' . $widget['name'] . '">' . $widget['title'] . '</h3>';
							
							// Display widget content
							// Video Widget
							if ( in_array( $widget['name'], $video_widgets ) ) {
								echo '<div id="bpghw_display_' . $widget['name'] . '" >';
								if( $widget['link'] != '' ) {
									echo '<div class="bpghw-video-wrapper">';
										bpghw_get_video_content( $group_id, $widget['link'], $widget['name'], 1000 );
									echo '</div>';
									$displayed = 1;
								}
								echo '</div>';
							}
							// Text Widget
							if ( in_array( $widget['name'], $text_widgets ) ) {
								echo '<div id="bpghw_display_' . $widget['name'] . '" class="bpghw-text-widget" data-name="' . $widget['name'] . '">';
								if( $widget['content'] != '' ) {
									echo do_shortcode( $widget['content'] );
									$displayed = 1;
								}
								echo '</div>';
							}
							// Admin Widget
							if ( $widget['name'] == 'admin' ) {
								echo '<div id="bpghw_display_' . $widget['name'] . '" class="bpghw-members-widget" data-name="' . $widget['name'] . '">';
								if( $widget['visibility'] != 'none' ) {
									if ( ! isset($widget['image_size']) ) $widget['image_size'] = 'thumb';
									bpghw_get_admin_output( $group_id, $widget['max_users'], $widget['image_size'] );
									$displayed = 1;
								}
								echo '</div>';
							}
							// Members Widget
							if ( $widget['name'] == 'members' ) {
								echo '<div id="bpghw_display_' . $widget['name'] . '" class="bpghw-members-widget" data-name="' . $widget['name'] . '">';
								if( $widget['visibility'] != 'none' ) {
									if ( ! isset($widget['image_size']) ) $widget['image_size'] = 'thumb';
									bpghw_get_members_output( $group_id, $widget['max_users'], $widget['image_size'] );
									$displayed = 1;
								}
								echo '</div>';
							}
							// Activity Widget
							if ( $widget['name'] == 'activity' ) {
								echo '<div id="bpghw_display_' . $widget['name'] . '" class="bpghw-' . $widget['name'] . '-widget" data-name="' . $widget['name'] . '">';
								if( $widget['visibility'] != 'none' ) {
									bpghw_get_activity_output( $group_id, $widget['max_posts'], 'activity' );
									$displayed = 1;
								}
								echo '</div>';
							}
							// Comments Widget
							if ( $widget['name'] == 'comments' ) {
								echo '<div id="bpghw_display_' . $widget['name'] . '" class="bpghw-' . $widget['name'] . '-widget" data-name="' . $widget['name'] . '">';
								if( $widget['visibility'] != 'none' ) {
									bpghw_get_activity_output( $group_id, $widget['max_posts'], 'comments' );
									$displayed = 1;
								}
								echo '</div>';
							}
							// Mention Us Widget
							if ( $widget['name'] == 'mention_us' ) {
								echo '<div id="bpghw_display_' . $widget['name'] . '" class="bpghw-' . $widget['name'] . '-widget" data-name="' . $widget['name'] . '">';
								if( $widget['visibility'] != 'none' ) {
									bpghw_get_mention_us_output();
									$displayed = 1;
								}
								echo '</div>';
							}
							
							// Set up widget edit fields
							if ( $group_creator_id == $logged_in_user_id ) {
								//Video Widgets
								if ( in_array( $widget['name'], $video_widgets ) ) {
									
									if ( ! empty($widget['link']) ) {
										
										echo '<small><input type="button" value="' . esc_attr__( 'Change Video', 'bp-group-home-widgets' ) . '" data-name="' . $widget['name'] . '" id="bpghw_add_' . $widget['name'] . '" style="display: none;" class="bpghw_add">
										
										<input type="button" value="' . esc_attr__( 'Clear Video', 'bp-group-home-widgets' ) . '" data-group="' . $group_id . '" data-name="' . $widget['name'] . '" id="bpghw_clear_' . $widget['name'] . '" style="display: none;" class="bpghw_clear_video_button"></small>';
									
									} else {
										
										echo '<small><input type="button" value="' . esc_attr__( 'Add a Video', 'bp-group-home-widgets' ) . '" data-name="' . $widget['name'] . '" id="bpghw_add_' . $widget['name'] . '" style="display: none;" class="bpghw_add"></small>';
									
									}
								}
								// Text Widgets
								if ( in_array( $widget['name'], $text_widgets ) ) {
									if ( ! empty($widget['content']) ) {
										echo '<small><input type="button" value="' . esc_attr__( 'Change Text', 'bp-group-home-widgets' ) . '" data-name="' . $widget['name'] . '" id="bpghw_add_' . $widget['name'] . '" style="display: none;" class="bpghw_add">

										<input type="button" value="' . esc_attr__( 'Clear Text', 'bp-group-home-widgets' ) . '" data-group="' . $group_id . '" data-name="' . $widget['name'] . '" id="bpghw_clear_' . $widget['name'] . '" style="display: none;" class="bpghw_clear_text_button"></small>';
									
									} else {
										
										echo '<small>
											<input type="button" value="' . esc_attr__( 'Add Text', 'bp-group-home-widgets' ) . '" data-name="' . $widget['name'] . '" id="bpghw_add_' . $widget['name'] . '" style="display: none;" class="bpghw_add">
											</small>';
									}
								}
								// BP Widgets
								if ( in_array( $widget['name'], $buddypress_widgets ) ) {
									
									if ( $widget['visibility'] != 'none' ) {
										
										echo '<small><input type="button" value="' . esc_attr__( 'Change', 'bp-group-home-widgets' ) . '" data-name="' . $widget['name'] . '" id="bpghw_add_' . $widget['name'] . '" style="display: none;" class="bpghw_add">
										
										<input type="button" value="' . esc_attr__( 'Hide Widget', 'bp-group-home-widgets' ) . '" data-group="' . $group_id . '" data-name="' . $widget['name'] . '" id="bpghw_clear_' . $widget['name'] . '" style="display: none;" class="bpghw_clear_' . $widget['name'] . '_button"></small>';
									
									} else {
										
										echo '<small><input type="button" value="' . esc_attr__( 'Add Widget', 'bp-group-home-widgets' ) . '" data-name="' . $widget['name'] . '" id="bpghw_add_' . $widget['name'] . '" style="display: none;" class="bpghw_add"></small>';
									
									}
								}
								// Generic setting up edit form
								echo '<div id="bpghw_form_' . $widget['name'] . '" style="display: none;">
								<p style="text-align: left;">' . esc_attr($title_text) . '</p>
								<input type="text" placeholder="' . esc_attr($title_text) . '" id="bpghw_title_' . $widget['name'] . '" value="' . $widget['title'] . '"></br>';
								// Widget type specific fields
								// Video Widget
								if ( in_array( $widget['name'], $video_widgets ) ) {
									echo '<input type="text" placeholder="' . esc_attr__( 'Paste Video URL here', 'bp-group-home-widgets' ) . '" id="bpghw_url_' . $widget['name'] . '" ';
									if ( ! empty( $widget['link'] ) ) {
										echo 'value="' . $widget['link'] . '"';
									}
									echo '>
									</br>
									<input type="button" value="' . esc_attr__( 'Submit', 'bp-group-home-widgets' ) . '" class="bpghw_submit_video" id="bpghw_submit_' . $widget['name'] . '" data-group="' . $group_id . '" data-name="' . $widget['name'] . '" >
									</div>';
								}
								// Text Widget
								if ( in_array( $widget['name'], $text_widgets ) ) {
									echo '<div id="bpghw_content_input_' . $widget['name'] . '" >';

									$content = html_entity_decode($widget['content'] );
									$editor = 'bpghw_content_' . $widget['name'];
									$settings = array(
										'textarea_rows' => 4,
										'media_buttons' => true,
										'teeny'			=> false,
									);

									wp_editor( $content, $editor, $settings);

									
									echo '</div>
										<input type="button" value="' . esc_attr__( 'Submit', 'bp-group-home-widgets' ) . '" class="bpghw_submit_text" id="bpghw_submit_' . $widget['name'] . '" data-group="' . $group_id . '" data-name="' . $widget['name'] . '" >
									</div>';
								}
								//  BP Widgets
								if ( in_array( $widget['name'], $buddypress_widgets ) ) {
									// Admin Widget
									if ( $widget['name'] == 'admin' ) {
										echo '<label for ="bpghw_max_users_' . $widget['name'] . '">' . esc_attr__( ' Max Admin Members to show: ', 'bp-group-home-widgets' ) . '</label>';
										echo '<input type="text" style="width: 25%; display: inline-block;" placeholder="' . esc_attr__( 'Max Admin Members', 'bp-group-home-widgets' ) . '" id="bpghw_max_users_' . $widget['name'] . '" name="bpghw_max_users_' . $widget['name'] . '" ';
										if ( ! empty( $widget['max_users'] ) ) {
											echo 'value="' . $widget['max_users'] . '"';
										}
										echo '></br>';
										echo '
										<label for="bpghw_admin_avater_size">' . esc_attr__( 'Avatar Size:', 'bp-group-home-widgets') . '</label>
										<select id="bpghw_admin_avater_size" name="bpghw_admin_avater_size" style="width: 25%;">
											<option value="full" ';
											if ( $widget['image_size'] == 'full' ) { echo 'selected = "selected"'; }
											echo '>'. esc_attr__('Full', 'bp-group-home-widgets' ) . '</option>
											<option value="thumb" ';
											if ( $widget['image_size'] == 'thumb' ) { echo 'selected = "selected"'; }
											echo '>' . esc_attr__('Thumb', 'bp-group-home-widgets' ) . '</option>
										  </select></br>';
									}
									// Members Widget
									if ( $widget['name'] == 'members' ) {
										echo '<label for ="bpghw_max_users_' . $widget['name'] . '">' . esc_attr__( ' Max Members to show: ', 'bp-group-home-widgets' ) . '</label>';
										echo '<input type="text" style="width: 25%; display: inline-block;" placeholder="' . esc_attr__( 'Max Members', 'bp-group-home-widgets' ) . '" id="bpghw_max_users_' . $widget['name'] . '" name="bpghw_max_users_' . $widget['name'] . '" ';
										if ( ! empty( $widget['max_users'] ) ) {
											echo 'value="' . $widget['max_users'] . '"';
										}
										echo '></br>';
										echo '
										<label for="bpghw_members_avater_size">' . esc_attr__( 'Avatar Size:', 'bp-group-home-widgets') . '</label>
										<select id="bpghw_members_avater_size" name="bpghw_members_avater_size" style="width: 25%;">
											<option value="full" ';
											if ( $widget['image_size'] == 'full' ) { echo 'selected = "selected"'; }
											echo '>'. esc_attr__('Full', 'bp-group-home-widgets' ) . '</option>
											<option value="thumb" ';
											if ( $widget['image_size'] == 'thumb' ) { echo 'selected = "selected"'; }
											echo '>' . esc_attr__('Thumb', 'bp-group-home-widgets' ) . '</option>
										  </select></br>';
									}
									// Activity Widget
									if ( $widget['name'] == 'activity' || $widget['name'] == 'comments' ) {
										echo '<input type="text" style="width: 25%; display: inline-block;" placeholder="' . esc_attr__( 'Max Items', 'bp-group-home-widgets' ) . '" id="bpghw_max_posts_' . $widget['name'] . '" ';
										if ( ! empty( $widget['max_posts'] ) ) {
											echo 'value="' . $widget['max_posts'] . '"';
										}
										echo '><small><span style="display; inline-block;">' . esc_attr__( ' Max Items to show', 'bp-group-home-widgets' ) . '</span></small></br>';
									}
									// Mention Us widget
									if ( $widget['name'] == 'mention_us' ) {
										echo '<select id="bpghw_mention_us_enable" style="width: 35%; display: inline-block;">
											<option value="display"';
											if ( $widget['visibility'] != 'none' ) {
												echo ' selected="selected"';
											}
											echo '>' . esc_attr__( 'Display Comment Us comment box', 'bp-group-home-widgets' ) . '</option>
											<option value="hide"';
											if ( $widget['visibility'] == 'none' ) {
												echo ' selected="selected"';
											}
											echo '>' . esc_attr__( 'Hide Comment Us comment box', 'bp-group-home-widgets' ) . '</option>
										</select>';
									}
									echo '<input type="button" value="' . esc_attr__( 'Submit', 'bp-group-home-widgets' ) . '" class="bpghw_submit_widget" id="bpghw_submit_' . $widget['name'] . '" data-group="' . $group_id . '" data-name="' . $widget['name'] . '" >
									</div>';
								}
								
								// Feedback field
								echo '<p id="bpghw_feedback_' . $widget['name'] . '" style="display: none;"></p>';
							
							}
							echo '</li>';
						}
					}
				}
			}
			echo '</ul>';
			if ( $group_creator_id == bp_loggedin_user_id() ) {
				echo '<small><input type="button" value="';

				if ( $displayed == 1 ) { 
					
					echo esc_attr__( 'Update Widgets', 'bp-group-home-widgets' );
				
				} else {
					
					echo esc_attr__( 'Add a Widget', 'bp-group-home-widgets' );
				
				}
				
				echo '" id="bpghw-add-widget" class="bpghw-add-widget-button"></small>';

				if ( current_user_can( 'manage_options' ) ) {
					
					echo '<small><input type="button" value="';

					if ( isset( $presets ) && $presets != false ) { 
						
						echo esc_attr__( 'Clear Preset', 'bp-group-home-widgets' ) . '" name="clear';
					
					} else {
						
						echo esc_attr__( 'Save as Preset', 'bp-group-home-widgets' ) . '" name="save';
					
					}
					echo '" id="bpghw-update-preset" class="bpghw-update-presets-button"></small>';
				}
				
				echo '<small><input type="button" data-user="' . $group_id . '" value="' . esc_attr__( 'Clear All', 'bp-group-home-widgets' ) . '" id="bpghw-reset-widget" class="bpghw-reset-widget-button" style="display: none;"></small>
				
				<div id="bpghw-widget-form" style="display: none;">
					<small><p id="bpghw_info">' . esc_attr__( 'Select an empty widget and input the required info' , 'bp-group-home-widgets' ) . '</p></small>
				</div>';
			
			}
			echo $args['after_widget'];
		}


	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Group Home Widgets', 'bp-group-home-widgets' );
		$disable_videos = ! empty( $instance['disable_videos'] ) ? $instance['disable_videos'] : 0;
		$disable_text = ! empty( $instance['disable_text'] ) ? $instance['disable_text'] : 0;
		$disable_buddypress = ! empty( $instance['disable_buddypress'] ) ? $instance['disable_buddypress'] : 0;
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'bp-group-home-widgets' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'disable_videos' ) ); ?>"><?php esc_attr_e( 'Disable Video/Audio Widgets:', 'bp-group-home-widgets' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'disable_videos' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'disable_videos' ) ); ?>" type="checkbox" value="0" <?php if ( $disable_videos ): echo 'checked="checked"'; endif; ?>>
		</p>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'disable_text' ) ); ?>"><?php esc_attr_e( 'Disable Text Widgets:', 'bp-group-home-widgets' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'disable_text' ) ); ?>" name="<?php echo esc_attr($this->get_field_name( 'disable_text' ) ); ?>" type="checkbox" value="<?php echo esc_attr( $disable_text ); ?>" <?php if ( $disable_text ): echo 'checked="checked"'; endif; ?>>
		</p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'disable_buddypress' ) ); ?>"><?php esc_attr_e( 'Disable BuddyPress (Our Members, Our Activity, Our Comments )  Widgets:', 'bp-group-home-widgets' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'disable_buddypress' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'disable_buddypress' ) ); ?>" type="checkbox" value="<?php echo esc_attr( $disable_buddypress ); ?>" <?php if ( $disable_buddypress ): echo 'checked="checked"'; endif; ?>>
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? esc_attr( $new_instance['title'] ) : '';
		$instance['disable_videos'] = ( isset( $new_instance['disable_videos'] ) ? 1 : 0 );
		$instance['disable_text'] = ( isset( $new_instance['disable_text'] ) ? 1 : 0 );
		$instance['disable_buddypress'] = ( isset( $new_instance['disable_buddypress'] ) ? 1 : 0 );

		return $instance;
	}
	

}