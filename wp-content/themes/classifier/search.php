<?php get_header(); global $colabs_options; ?>
    <?php      
        if ( function_exists('colabs_cl_breadcrumb') ) colabs_cl_breadcrumb(); 
    ?>

    <div class="row main-container">

		<div class="main-content col9">
        
        <div class="content-tab">
            <?php if (have_posts()) :  ?>
				<h1 class="single dotted">
						<?php 
							$searchTxt = trim( strip_tags( esc_attr( get_search_query() ) ) );
							if ( $searchTxt ==  __('What are you looking for?','colabsthemes') ) $searchTxt = '*';
							printf( __("Search for '%s' returned %s results",'colabsthemes'), $searchTxt, $wp_query->found_posts ); 
						?>
                        </h1>							
				<!-- Post Starts -->
				<?php get_template_part('loop','ad'); ?>
				<!-- /.post -->

			<?php else:?>

				<div class="post-block">
					<p><?php _e('Sorry, no posts matched your criteria.', 'colabsthemes') ?></p>
				</div><!-- /.post -->
				
			<?php endif; ?> 
            
			<?php colabs_pagination(); ?>
        
        </div><!-- /.content-tab -->
        
		</div><!-- /.main-content -->
        
        <?php get_sidebar(); ?>

    </div><!-- /.main-container -->
		
<?php get_footer(); ?>