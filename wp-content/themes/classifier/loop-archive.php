<?php global $colabs_options;?>
<?php if (is_tag()) { global $wp_query; query_posts( array_merge(array('post_type' => 'any', 'tag' => single_tag_title('', false)),$wp_query->query) ); } ?>    

<?php if (have_posts()) : $count = 0; ?>

	<?php if (is_category()) { ?>
		<header class="entry-header"><h2><span class="fl cat"><?php echo stripslashes( $colabs_options['colabs_archive_general_header'] ); ?> | <?php echo single_cat_title(); ?></span> <span class="fr catrss"><?php $cat_obj = $wp_query->get_queried_object(); $cat_id = $cat_obj->cat_ID; echo '<a href="'; get_category_rss_link(true, $cat, ''); echo '">'; _e("RSS feed for this section", "colabsthemes"); echo '</a>'; ?></span></h2></header>        		
	<?php } elseif (is_day()) { ?>
		<header class="entry-header"><h2><?php echo stripslashes( $colabs_options['colabs_archive_general_header'] ); ?> | <?php the_time( get_option( 'date_format' ) ); ?></h2></header>		
	<?php } elseif (is_month()) { ?>
		<header class="entry-header"><h2><?php echo stripslashes( $colabs_options['colabs_archive_general_header'] ); ?> | <?php the_time('F, Y'); ?></h2></header>		
	<?php } elseif (is_year()) { ?>
		<header class="entry-header"><h2><?php echo stripslashes( $colabs_options['colabs_archive_general_header'] ); ?> | <?php the_time('Y'); ?></h2></header>		
	<?php } elseif (is_author()) { ?>
		<header class="entry-header"><h2><?php _e('Archive by Author', 'colabsthemes'); ?></h2></header>		
	<?php } elseif (is_tag()) { ?>
		<header class="entry-header"><h2><?php _e('Tag Archives:', 'colabsthemes'); ?> <?php echo single_tag_title('', true); ?></h2></header>	
	<?php } ?>
    
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