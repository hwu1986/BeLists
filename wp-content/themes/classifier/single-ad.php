<?php get_header(); ?>

<script type='text/javascript'>
// <![CDATA[
/* setup the form validation */
jQuery(document).ready(function ($) {
    $('#mainform').validate({
        errorClass: 'invalid'
    });
});
// ]]>
</script>

<?php if ( function_exists('colabs_cl_breadcrumb') ) colabs_cl_breadcrumb(); ?>

  <div class="row main-container">

    <div class="main-content col9">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
      <article <?php post_class('entry'); ?>>
      
        <?php colabsthemes_before_post_content(); ?>
		<?php colabs_stats_update( $post->ID ); //records the page hit ?>
        <header class="entry-header">
          <h2><?php the_title(); ?></h2>
          <span class="price-tag"><?php if ( get_post_meta($post->ID, 'colabs_price', true) ) colabs_get_price_legacy($post->ID); else colabs_get_price($post->ID, 'colabs_price'); ?></span>
		  
        </header>

        <div class="entry-images">
          <div class="entry-slides">
			<?php 
			  $custom_field = 'colabs_discount';
			  $listing_discount = get_post_meta($post->ID,$custom_field,true);
			  if( !empty($listing_discount) ){ ?><div class="sale-price"> <?php echo $listing_discount; ?>% <strong><?php _e('Sale','colabsthemes'); ?></strong></div><?php } ?>
            <?php colabs_image('width=724&height=478&size=single-ad&link=img'); ?>
          </div>
          
            <!--div id="gallery">
            	<?php 
            	$gallery = do_shortcode('[gallery size="thumbnail" columns="4"]');
            	if (!$gallery) {
            		include('includes/gallery.php'); // Photo gallery
            	} else {
            		// echo 'no-gallery'; 
            	}
            	?>
            </div-->
          
        </div>

        <div class="entry-content">

            <?php the_content(); ?>
            
        </div>
        <!-- /.entry-content -->

        <?php colabsthemes_after_post_content(); ?>

      </article>

      <!-- Moving contacts info under each ads post -->

      <div class="listing-info listing-details">
        <style>.inquiry-form {width: 80% !important;}</style>
        <h4><?php _e('Contact','colabsthemes'); ?></h4>
        <p class="contact_msg"><?php _e('To inquire about this ad listing, complete the form below to send a message to the ad poster.', 'colabsthemes') ?></p>
            <?php include_once(TEMPLATEPATH . '/includes/sidebar-contact.php'); ?>
      </div>

      <!-- End of Contact Info -->


	  <?php endwhile;endif;?>
      <!-- /.entry -->
        
        <?php comments_template( '/comments-ad.php' ); ?>

    </div>
    <!-- .main-content -->

    <?php get_sidebar('ad'); ?>

  </div>
  <!-- /.main-container -->

<?php get_footer(); ?>