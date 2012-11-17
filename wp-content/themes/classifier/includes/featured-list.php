<?php
/**
 * The featured slider on the home page
 *
 */

global $colabs_options;

if ( $colabs_options['colabs_enable_featured'] == 'true' ) : ?>

    <?php query_posts( array('post__in' => get_option('sticky_posts'), 'post_type' => COLABS_POST_TYPE, 'post_status' => 'publish', 'orderby' => 'rand') ); ?>

        <?php if ( have_posts() ) : ?>

          <section class="section-carousel row">
            <h3><?php _e('More Featured Listing','colabsthemes'); ?></h3>
        
            <div class="carousel">
    
                <?php while ( have_posts() ) : the_post(); ?>
    
                  <div class="carousel-item">
                    <div class="carousel-image">
                      <?php colabs_image('width=222&height=241&size=featured-list'); ?>
                    </div>
                    <div class="carousel-desc">
                      <h4><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h4>
                      <span class="price"><?php if ( get_post_meta($post->ID, 'colabs_price', true) ) colabs_get_price_legacy($post->ID); else colabs_get_price($post->ID, 'colabs_price'); ?></span>
                    </div>
                  </div>
                  
                <?php endwhile; ?>
        
            </div>
            <!-- /.carousel -->
        
          </section>
          <!-- /.items-carousel -->

        <?php endif; ?>

        <?php wp_reset_query(); ?>

<?php endif; // end feature ad slider check ?>
				