<?php get_header(); global $colabs_options;

// Global query variable
global $wp_query; 
// Get taxonomy query object
$taxonomy_archive_query_obj = $wp_query->get_queried_object();
// Taxonomy term name
$taxonomy_term_nice_name = $taxonomy_archive_query_obj->name;
// Taxonomy term id
$term_id = $taxonomy_archive_query_obj->term_id;
// Get taxonomy object
$taxonomy_short_name = $taxonomy_archive_query_obj->taxonomy;
$taxonomy_raw_obj = get_taxonomy($taxonomy_short_name);
// You can alternate between these labels: name, singular_name
$taxonomy_full_name = $taxonomy_raw_obj->labels->name;
?>

<?php 
    if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<div id="breadcrumb"><p>','</p></div>'); }else{ 
    if ( function_exists('colabs_cl_breadcrumb') ) colabs_cl_breadcrumb(); } 
?>
    
<div class="row main-container">

	<div class="main-content col9">
    
    <div class="content-tab">
    
    <?php if (have_posts()) : $count = 0; ?>

        <header class="entry-header">
            <h2><span class="fl cat"><?php echo stripslashes( $colabs_options['colabs_archive_listings_header'] );  ?> | <?php echo $taxonomy_term_nice_name;?></span> <span class="fr catrss"><a href="<?php echo get_term_feed_link( $term_id, $taxonomy_short_name, ''); ?>"><?php _e("RSS feed for this section", "colabsthemes"); ?></a></span></h2>
        </header>
        
        <div class="tab-panel">

        <?php while (have_posts()) : the_post(); $count++; ?>
                                                                    
            <div <?php post_class('post-block'); ?>>
    
                <?php colabs_image('key=image&width=296&height=174'); ?>
                
                <?php //Meta Data
                global $post;
                $colabs_price = get_post_meta($post->ID, 'colabs_price', true); ?>
                <span class="price"><?php if ( !empty($colabs_price) ) colabs_get_price_legacy($post->ID); else colabs_get_price($post->ID, 'colabs_price'); ?></span>

                <header class="post-title">
                <h4><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4>
                </header>

                <div class="post-content">
                    <?php global $more; $more = 0; ?>
                    <p><?php echo get_the_excerpt(); ?></p>
                    <a href="<?php echo get_permalink($post->ID); ?>" title="<?php echo get_the_title($post->ID); ?>"><?php _e('Read More','colabsthemes'); ?></a>
                </div>
                
                <?php colabs_post_meta(); ?>
                
            </div><!-- /.block -->
            
        <?php endwhile; ?>
            
        </div><!-- /.tab-panel -->
            
            <?php else: ?>
            
                <div class="post">
                    <p><?php _e('Sorry, no posts matched your criteria.', 'colabsthemes') ?></p>
                </div><!-- /.post -->
            
            <?php endif; ?>
    
		<?php colabs_pagination(); ?>
        
    </div><!-- /.content-tab -->
            
	</div><!-- /.main-content -->

    <?php get_sidebar('ad'); ?>

</div><!-- /.main-container -->

<?php get_footer(); ?>