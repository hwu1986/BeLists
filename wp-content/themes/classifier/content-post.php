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
			<?php the_excerpt(); ?>
			<a href="<?php the_permalink() ?>" title="<?php _e('Read More','colabsthemes'); ?>"><?php _e('Read More','colabsthemes'); ?></a>
		</div>
				
	<?php colabs_post_meta(); ?>
				
</div><!-- /.post -->
												
