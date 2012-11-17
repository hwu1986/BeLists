<?php
/**
 * @package WordPress
 * @subpackage Classifier
 * 
 */
      
      
// Do not delete these lines
if ( !empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']) )
        die ( __('Please do not load this page directly.', 'colabsthemes') );

if ( post_password_required() ) { ?>

        <p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.','colabsthemes'); ?></p>
<?php
        return;
}

global $commentDivsExist;
?>


<?php colabs_before_comments(); ?>

<?php if ( have_comments() ) : ?>

    <?php $commentDivsExist = true; ?>

        <div id="comments">
        
        <header class="comment-header">
        
            <h3><?php comments_number(__('No Comments','colabsthemes'), __('One Comment','colabsthemes'), __('% Comments','colabsthemes') );?></h3>
            
        </header>
        
        <ol class="commentlist">
        
            <?php colabs_list_comments(); ?>
        
        </ol>
        <!-- /.commentlist -->

        <div class="navigation">

            <div class="alignleft"><?php previous_comments_link('&laquo; ' . __('Older Comments', 'colabsthemes'), 0) ?></div>

            <div class="alignright"><?php next_comments_link(__('Newer Comments', 'colabsthemes') . ' &raquo;', 0) ?></div>

        </div><!-- /navigation -->        

        
        <?php colabs_before_pings(); ?>

        <?php $carray = separate_comments( $comments ); // get the comments array to check for pings ?>
        
        <?php if ( !empty( $carray['pings'] ) ) : // pings include pingbacks & trackbacks ?>

            <h2 class="dotted" id="pings"><?php _e('Trackbacks/Pingbacks', 'colabsthemes'); ?></h2>

            <ol class="pinglist">

                <?php colabs_list_pings(); ?>

            </ol>

        <?php endif; ?>
        
        <?php colabs_after_pings(); ?>
        
<?php endif; // have_comments ?>


                    <?php colabs_after_comments(); ?>
                    
                    <?php colabs_before_respond(); ?>
                    
                                
                    <?php if ( 'open' == $post->comment_status ) : ?>
                                    
                        <?php colabs_before_comments_form(); ?>
                                
                        <?php colabs_comments_form(); ?>
                                            
                        <?php colabs_after_comments_form(); ?>               
                    
                    <?php endif; // open ?>
                    
                    
                    <?php colabs_after_respond(); ?>
        
                    
        <?php if ( $commentDivsExist ) : ?>

        </div>
        <!-- #comments -->

        <?php endif; ?>