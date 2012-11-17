<?php if (have_posts()) : $count = 0; ?>
<?php while (have_posts()) : the_post(); $count++; ?>
                                                            
    <!-- Post Starts -->
	<?php get_template_part('content','post'); ?>
    <!-- /.post -->
                                        
<?php endwhile; else: ?>
    <div class="post-block">
        <p><?php _e('Sorry, no posts matched your criteria.', 'colabsthemes') ?></p>
    </div><!-- /.post -->
<?php endif; ?>  

