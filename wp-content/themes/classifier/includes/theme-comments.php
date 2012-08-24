<?php
/*-----------------------------------------------------------------------------------*/
/* CoLabs - List Comment */
/*-----------------------------------------------------------------------------------*/

// Custom callback to list comments
function colabs_custom_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment;
   $GLOBALS['comment_depth'] = $depth;
   switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
?>
            <li class="pingback">
                    <?php comment_author_link(); ?>
            <?php
			break;
		default :
	    ?>

            <li <?php comment_class(); ?>>
            <div class="comment-entry">
              <div class="comment-author">
                
                <a name="comment-<?php comment_ID() ?>"></a>
                <?php if ( get_comment_type() == 'comment' ) { commenter_avatar(); } ?>                
                
                <span class="author-name"><?php commenter_link() ?></span>
                
                <?php if ( get_comment_type() == 'comment' ) { ?>
                <span class="comment-meta"><?php echo get_comment_date( get_option('date_format') ) ?><?php echo get_comment_time( get_option('time_format') ); ?></span> <?php edit_comment_link(__('Edit', 'colabsthemes'), ' <span class="edit-link">(', ')</span>'); ?>
                <?php }?>
                
              </div>
              
              <div class="comment-content" id="comment-<?php comment_ID(); ?>" >

                <?php comment_text() ?>

                <?php if ( $comment->comment_approved == '0' ) { ?>

                        <p class='unapproved'><?php _e('Your comment is awaiting moderation.','colabsthemes') ?></p>
            
                <?php } ?>
                
              </div>
              
              <?php comment_reply_link( array_merge( $args, array( 'reply_text' => __('Reply','colabsthemes'),
                                                                            'login_text' => __('Log in to reply.','colabsthemes'),
                                                                            'depth' => $depth,
                                                                            'max_depth' => $args['max_depth'],
                                                                            'before' => '',
                                                                            'after' => '',
                                                                            ) ) ); ?>

            </div><!-- /comment-entry -->
            
            <?php 
            break;
        endswitch;
}


// list out comments 
function colabs_list_comments() {
    global $post;

    wp_list_comments( array( 'callback' => 'colabs_custom_comment', 'type' => 'comment' ) );
    
}
add_action('colabs_list_comments', 'colabs_list_comments');
add_action('colabs_list_blog_comments', 'colabs_list_comments');
add_action('colabs_list_page_comments', 'colabs_list_comments');


// list out pings 
function colabs_list_pings() {
    global $post;

    wp_list_comments( array( 'callback' => 'colabs_custom_comment', 'type' => 'pings' ) );
    
}
add_action('colabs_list_pings', 'colabs_list_pings');
add_action('colabs_list_blog_pings', 'colabs_list_pings');
add_action('colabs_list_page_pings', 'colabs_list_pings');


// main comments form 
function colabs_main_comment_form() {
    global $post;
?>

    <script type="text/javascript">
    <!--//--><![CDATA[//><!--
    jQuery(document).ready(function($) {
        /* initialize the form validation */
        $(function() {
            $("#commentform").validate({
                errorClass: "invalid",
                errorElement: "div",
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                   }
            });
	    $("#commentform").fadeIn();
        });
        
    });
    //-->!]]>
    </script>

    <div id="respond">
    
        <h3 id="reply-title"><?php comment_form_title( __('Add Comment','colabsthemes'), __('Leave a Reply to %s','colabsthemes') ); ?></h3>

        <div class="cancel-comment-reply"><small><?php cancel_comment_reply_link(); ?></small></div>

        <?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>

            <p><?php printf( __("You must be <a href='%s'>logged in</a> to post a comment.", 'colabsthemes'), get_option('siteurl').'/wp-login.php?redirect_to='.urlencode( get_permalink() ) ); ?></p>

        <?php else : ?>
        
        <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform" class="commentform">
        
            <?php if ( is_user_logged_in() ) : global $user_identity; ?>
            
            <p><?php _e('Logged in as','colabsthemes'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(); ?>" title="<?php _e('Logout of this account','colabsthemes'); ?>"><?php _e('Logout','colabsthemes'); ?> &raquo;</a></p>
            
            <?php else : ?>
            
            <?php 
                $commenter = wp_get_current_commenter();
                $req = get_option( 'require_name_email' ); 
            ?>        

            <p class="comment-form-author">
                <label for="author"><?php _e('Name','colabsthemes'); ?> <?php if ( $req ){ ?><span class="required">*</span><?php } ?></label>
                <input type="text" name="author" id="author" class="text required" value="<?php echo esc_attr( $commenter['comment_author'] ); ?>" size="22" tabindex="1" />
            </p>
            
            <p class="comment-form-email">
                <label for="email"><?php _e('Email (will not be visible)','colabsthemes'); ?> <?php if ( $req ){ ?><span class="required">*</span><?php } ?></label>
                <input type="text" name="email" id="email" class="text required email" value="<?php echo esc_attr(  $commenter['comment_author_email'] ); ?>" size="22" tabindex="2" />                                
            </p>
            
            <p class="comment-form-url">
                <label for="url"><?php _e('Website','colabsthemes'); ?></label>
                <input type="text" name="url" id="url" class="text" value="<?php echo esc_attr( $commenter['comment_author_url'] ); ?>" size="22" tabindex="3" />
            </p>

            <?php endif; ?>

            <!--<li><small><strong>XHTML:</strong> You can use these tags: <?php echo allowed_tags(); ?></small><div class="clr"></div></li>-->

            <p class="comment-form-comment">
             
                <textarea placeholder="Type your comment here*" name="comment" rows="7" cols="" id="comment" class="required" tabindex="4"></textarea>
            </p>

            <p class="comments">
                <input name="submit" type="submit" id="submit" tabindex="5" class="btn btn-primary" value="<?php _e('Post Comment','colabsthemes'); ?>" />
                <input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
            </p>

            <?php comment_id_fields(); ?>
            <?php do_action( 'comment_form', $post->ID ); ?>

        </form>
        
        <?php endif; // if logged in ?>
    
    </div><!--/#respond-->
    
<?php
}
add_action('colabs_comments_form', 'colabs_main_comment_form');
add_action('colabs_blog_comments_form', 'colabs_main_comment_form');
add_action('colabs_page_comments_form', 'colabs_main_comment_form');


function commenter_link() {
    $commenter = get_comment_author_link();

    if ( strstr( ']* class=[^>]+>', $commenter ) ) {
        $commenter = str_replace( '(]* class=[\'"]?)', '\\1url ' , $commenter );

    } else {

        $commenter = str_replace( '(<a )/', '\\1class="url "' , $commenter );
    }

    echo $commenter;
}

function commenter_avatar() {
    $avatar_email = get_comment_author_email();
    $avatar = str_replace( 'class="avatar', 'class="photo avatar', get_avatar( $avatar_email, 60 ) );

    echo $avatar;
}

?>