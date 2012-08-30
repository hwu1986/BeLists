<?php 
/*	// WP 3.0 PAGED BUG FIX
	if ( get_query_var('paged') )
		$paged = get_query_var('paged');
	elseif ( get_query_var('page') ) 
		$paged = get_query_var('page');
	else 
		$paged = 1;
	//$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
	 
     query_posts("post_type=post&paged=$paged"); */
?>

<?php if (have_posts()) : $count = 0; ?>

    <header class="entry-header">
        <h2><?php _e( 'All Listings', 'colabsthemes' );?></h2>
    </header>

    <div class="tab-panel">
    
<?php while (have_posts()) : the_post(); $count++; ?>
                                                            
    <!-- Post Starts -->
    <div <?php post_class('post-block'); ?>>

        <?php colabs_post_inside_before(); ?>
        
        <figure class="post-image">
        <?php colabs_image('width=239&height=143&size=thumbnail'); ?> 
        </figure>
        
        <header class="post-title">
        <h4><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4>
        </header>

        <div class="post-content">
            <?php global $more; $more = 0; ?>
            <p><?php echo get_the_excerpt(); ?></p>
            <a href="<?php echo get_permalink($post->ID); ?>" title="<?php echo get_the_title($post->ID); ?>"><?php _e('Read More','colabsthemes'); ?></a>
        </div>
        
        <?php colabs_post_meta(); ?>
        
    </div><!-- /.post -->
                                        
<?php endwhile; ?>
    
    </div><!-- /.tab-panel -->
    
<?php else: ?>

    <div class="post-block">
        <p><?php _e('Sorry, no posts matched your criteria.', 'colabsthemes') ?></p>
    </div><!-- /.post -->
    
<?php endif; ?>  


		<?php /* if (have_posts()) : $count = 0; ?>
        

        	<div class="more-listings">
        	
            <?php while (have_posts()) : the_post(); $count++; ?>
                                                                        
                <div class="block">
    
                    <?php colabs_image('key=image&width=296&height=174');
    
                    //Meta Data
                    global $post;
            		$custom_field = $colabs_options['colabs_custom_field_image'];
            		$listing_image_caption = get_post_meta($post->ID,$custom_field,true);
    				if ($listing_image_caption != '' && $custom_field == 'colabs_price') { $listing_image_caption = number_format($listing_image_caption , 0 , '.', ','); }
    
                    if ($listing_image_caption != '') { ?><span class="price"><?php if ($custom_field == 'price') { echo $colabs_options['colabs_curr_symbol']; } echo ''.$listing_image_caption ?></span><?php } ?>
                    
                    <h2 class="cufon"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
            		
            		<p><?php echo get_the_excerpt(); ?></p>
            		
            		<span class="more"><a href="<?php echo get_permalink($post->ID); ?>" title="<?php echo get_the_title($post->ID); ?>"><?php _e('More Info', 'colabsthemes'); ?></a></span>
            	
                </div><!-- /.block -->
                
            <?php endwhile; ?>
        
        	</div><!-- /.more-listings -->
        
        <?php else: ?>
        
            <div class="post">
                <p><?php _e('Sorry, no posts matched your criteria.', 'colabsthemes') ?></p>
            </div><!-- /.post -->
        
        <?php endif; */ ?> 
        