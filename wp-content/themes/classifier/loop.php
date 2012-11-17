<?php 
	// WP 3.0 PAGED BUG FIX
	if ( get_query_var('paged') )
		$paged = get_query_var('paged');
	elseif ( get_query_var('page') ) 
		$paged = get_query_var('page');
	else 
		$paged = 1;
	//$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
	 
     query_posts("post_type=post&paged=$paged"); 
?>

<?php if (have_posts()) : $count = 0; ?>


	<header class="entry-header"><h2><?php the_title(); ?></h2></header>

    
	<div class="tab-panel">
		
		<?php while (have_posts()) : the_post(); $count++; ?>
																	
			<!-- Post Starts -->
			<?php get_template_part('content','post'); ?>
			<!-- /.post -->
												
		<?php endwhile;?>
		
	</div>
	
<?php else:?>

    <div class="post-block">
        <p><?php _e('Sorry, no posts matched your criteria.', 'colabsthemes') ?></p>
    </div><!-- /.post -->
	
<?php endif; ?>  