<?php

/**
 * Add footer elements via the wp_footer hook
 *
 * Anything you add to this file will be dynamically
 * inserted in the footer of your theme
 *
 * @since 1.0.0
 * @uses colabs_footer
 *
 */
 
/**
 * add footer sidebar before the footer 
 * @since 1.0.0
 */
function cp_before_footer() {
?>

</div>
<!-- /.container -->
    
<?php if (colabs_active_sidebar('footer-sidebar')) : ?>

<section class="container footer-widgets">
    <div class="row">
    
        <?php colabs_sidebar('footer-sidebar'); ?>		          
         
    </div>
</section>
<!-- /.footer-widgets -->

<?php 
endif; 
}
add_action('colabs_footer_before', 'cp_before_footer'); 

/**
 * add the footer contents to the bottom of the page 
 * @since 1.0.0
 */
function cp_do_footer() {
    global $colabs_options;
?> 

<footer class="container section-footer">
  <div class="row">

    <div class="footer-logo">
    
        <?php
        $site_url = home_url('/');
        $site_title = get_bloginfo( 'name' );
		echo '<h4><a href="' . $site_url . '">' . $site_title . '</a></h4>';
        ?>
    
    </div>
    <!-- /.footer-logo -->

    <p class="copyright">
        <?php 
        $colabs_credits = $colabs_options['colabs_footer_credit'];
        $colabs_credits_txt = $colabs_options['colabs_footer_credit_txt'];
        if( $colabs_credits != 'true' ){ ?>
        
            <?php _e('Copyright', 'colabsthemes'); ?> &copy; <?php echo date_i18n('Y'); ?> <a href="<?php echo home_url( '/' ); ?>"><?php bloginfo('name'); ?></a> <?php _e('by', 'colabsthemes'); ?> <a href="http://colorlabsproject.com">ColorLabs &amp; Company</a>. <?php _e('All Rights Reserved', 'colabsthemes'); ?>.
        
        <?php 
        }else{ echo $colabs_credits_txt; }
        ?>
    </p>

    <div class="social-button">
      <ul>
        <?php if( !empty( $colabs_options['colabs_social_facebook'] ) ){ ?><li class="fb-btn"><a href="<?php echo $colabs_options['colabs_social_facebook']; ?>">Facebook</a></li><?php } ?>
        <?php if( !empty( $colabs_options['colabs_social_twitter'] ) ){ ?><li class="twit-btn"><a href="<?php echo $colabs_options['colabs_social_twitter']; ?>">Twitter</a></li><?php } ?>
        <?php if( !empty( $colabs_options['colabs_social_gplus'] ) ){ ?><li class="gplus-btn"><a href="<?php echo $colabs_options['colabs_social_gplus']; ?>">Google Plus</a></li><?php } ?>
        <?php if( !empty( $colabs_options['colabs_social_youtube'] ) ){ ?><li class="youtb-btn"><a href="<?php echo $colabs_options['colabs_social_youtube']; ?>">Youtube</a></li><?php } ?>
        <?php if( !empty( $colabs_options['colabs_social_tumblr'] ) ){ ?><li class="tumblr-btn"><a href="<?php echo $colabs_options['colabs_social_tumblr']; ?>">Tumblr</a></li><?php } ?>
        <?php if( !empty( $colabs_options['colabs_social_linkedin'] ) ){ ?><li class="linkedin-btn"><a href="<?php echo $colabs_options['colabs_social_linkedin']; ?>">LinkedIn</a></li><?php } ?>
        <?php if( !empty( $colabs_options['colabs_social_vimeo'] ) ){ ?><li class="vimeo-btn"><a href="<?php echo $colabs_options['colabs_social_vimeo']; ?>">Vimeo</a></li><?php } ?>
        <?php if( !empty( $colabs_options['colabs_social_flickr'] ) ){ ?><li class="flickr-btn"><a href="<?php echo $colabs_options['colabs_social_flickr']; ?>">Flickr</a></li><?php } ?>
      </ul>
    </div>
    <!-- /.social-button -->

  </div>
</footer>
<!-- /.section-footer -->

<?php
}
add_action('colabs_foot', 'cp_do_footer'); 


// enable the gravatar hovercards in footer
function cp_gravatar_hovercards() {
	global $colabs_abbr;

    if ( get_option($colabs_abbr.'_use_hovercards') == 'true' )
		wp_enqueue_script( 'gprofiles', 'http://s.gravatar.com/js/gprofiles.js', array( 'jquery' ), '1.0', true );

}
//add_action('wp_enqueue_scripts', 'cp_gravatar_hovercards');


?>