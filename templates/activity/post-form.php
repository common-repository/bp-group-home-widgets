<?php
/**
 * BP Group Home Widgets - Group Comment Post Form
 */

?>

<form action="<?php bp_activity_post_form_action(); ?>" method="post" id="whats-new-form" name="whats-new-form">

	<?php

	do_action( 'bpghw_before_group_comment_post_form' ); ?>



	<p class="activity-comment"><?php if ( bp_is_group() )
		printf( esc_attr__( "Write a comment to the group, %s", 'bp-group-home-widgets' ), esc_attr(bp_get_user_firstname( bp_get_loggedin_user_fullname())) );
	?></p>

	<div id="group-comment-content">
		<div id="group-comment-textarea">
			<label for="whats-new" class="bp-screen-reader-text"><?php
				/* translators: accessibility text */
				esc_attr_e( 'Post what\'s new', 'bp-group-home-widgets' );
			?></label>
			<textarea class="bp-suggestions" name="whats-new" id="whats-new" cols="50" rows="2"
				<?php if ( bp_is_group() ) : ?>data-suggestions-group-id="<?php echo esc_attr( (int) bp_get_current_group_id() ); ?>" <?php endif; ?>
			><?php if ( isset( $_GET['r'] ) ) : ?>@<?php echo esc_textarea( $_GET['r'] ); ?> <?php endif; ?></textarea>
		</div>

		<div id="whats-new-options">
			<div id="whats-new-submit">
				<input type="submit" name="aw-whats-new-submit" id="aw-whats-new-submit" value="<?php esc_attr_e( 'Post Update', 'bp-group-home-widgets' ); ?>" />
			</div>

			<?php if ( bp_is_group_activity() ) : ?>

				<input type="hidden" id="whats-new-post-object" name="whats-new-post-object" value="groups" />
				<input type="hidden" id="whats-new-post-in" name="whats-new-post-in" value="<?php bp_group_id(); ?>" />

			<?php endif; ?>

		</div><!-- #whats-new-options -->
	</div><!-- #whats-new-content -->

	<?php wp_nonce_field( 'post_update', '_wpnonce_post_update' ); ?>
	<?php

	/**
	 * Fires after the activity post form.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bpphw_after_group_comment_post_form' ); ?>

</form><!-- #whats-new-form -->
