<?php 
global $colabs_options; $count = 0;

$colabs_slider_pos = $colabs_options['colabs_slider_image'];
$featposts = $colabs_options['colabs_featured_entries']; // Number of featured entries to be shown

$GLOBALS['feat_tags_array'] = explode(',',get_option('colabs_featured_tags')); // Tags to be shown
foreach ($GLOBALS['feat_tags_array'] as $tags){
	$tag = get_term_by( 'name', trim($tags), COLABS_TAX_TAG, 'ARRAY_A' );
	if ( $tag['term_id'] > 0 ){ $tag_array[] = $tag['term_id']; }
}
$slides = get_posts(
    array(
        'post_type' => COLABS_POST_TYPE ,
        'numberposts' => $featposts,
        'tax_query' => array(
            array(  'taxonomy' => COLABS_TAX_TAG,
                    'field' => 'id',
                    'terms' => $tag_array
                    ))
        ));
?>

    <div class="featured-listing">
      <div class="inner-container">
      <h3 class="heading"><?php if( !empty($colabs_options['colabs_featured_header']) ) { echo stripslashes($colabs_options['colabs_featured_header']); }else{ _e('Featured Listing','colabsthemes'); } ?></h3>

    <?php if (!empty($slides)) { ?>

      <ul class="slides">

		<?php foreach($slides as $post) : setup_postdata($post); $count++; ?>
		
		<?php 
    	    //Meta Data
    	    $custom_field = 'colabs_discount';
    	    $listing_discount = get_post_meta($post->ID,$custom_field,true);
            
            //Post Image
            $colabs_image = colabs_image('return=true');
            if( empty($colabs_image) ) continue;
    	?>
        <li class="listing-slide slide-<?php echo $count; ?> post-slide-<?php echo $post->ID; ?>">
          <header>
            <h1><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
            <p class="slide-excerpt"><?php colabs_custom_excerpt(15,'',false); ?></p>
            <?php if( !empty($listing_discount) ){ ?><div class="sale-price"> <?php echo $listing_discount; ?>% <strong><?php _e('Sale','colabsthemes'); ?></strong></div><?php } ?>
          </header>
          
            <?php colabs_image('width=743&height=481&size=featured-main'); ?>          
          
        </li>

        <?php endforeach; ?>

      </ul>

	<?php if (get_option('colabs_exclude') <> $GLOBALS['shownposts']) update_option("colabs_exclude", $GLOBALS['shownposts']); ?>

    <?php } else { ?>    
	<p class="colabs-sc-box note"><?php _e('Please setup Featured Panel tag(s) in your options panel. You must setup tags that are used on active posts.','colabsthemes'); ?></p>
	<?php } wp_reset_query(); ?>
    
      </div><!-- .inner-container -->
    </div>
    <!-- /.featured-listing -->

