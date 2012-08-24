<div id="comments" class="columns">

	<?php if ( have_comments() ) : ?>
		<h6><?php _e( 'Comments To This Entry', 'colabsthemes' ); ?></h6>

		<ol class="commentlist">
			<?php wp_list_comments( array(
				'type'			=> 'comment',
				'callback' 	=> 'colabs_list_comments',
				'max_depth'	=> 1
			) ); ?>
		</ol>
		
	<?php
		/* If there are no comments and comments are closed, let's leave a little note, shall we?
		 * But we don't want the note on pages or post types that do not support comments.
		 */
		elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="nocomments"><?php _e( 'Comments are closed.', 'colabsthemes' ); ?></p>
	<?php endif; ?>

	<?php //comment_form(); ?>

	<?php
	// Custom comment form
	$fields = array(
		'author'	=> '<p class="comment-form-author">
									<input id="author" name="author" type="text" placeholder="Name" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />' . ( $req ? '' : '' ) .
									'</p>',
		'email'		=> '<p class="comment-form-email">
									<input id="email" name="email" type="text" placeholder="Email" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' />' . ( $req ? '' : '' ) .
									'</p>',
		'url'			=> '<p class="comment-form-url">
									<input id="url" name="url" type="text" placeholder="Website" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" />
									</p>'
	);

	comment_form(array(
		'comment_field'        => '<p class="comment-form-comment"><textarea placeholder="Message" id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
		'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
		'comment_notes_before' => '',
		'comment_notes_after' => '',
		'title_reply'          => __( 'Leave a comment' ),
		'title_reply_to'       => __( '' ),
		'label_submit'         => __( 'Post Comment' ),
		'cancel_reply_link'    => __( 'Cancel' ),
	));
	?>
	
</div><!-- #comments -->
