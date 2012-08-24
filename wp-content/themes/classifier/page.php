<?php get_header(); ?>

<?php if ( function_exists('colabs_cl_breadcrumb') ) colabs_cl_breadcrumb(); ?>

<!-- #Main Starts -->
<?php colabs_main_before(); ?>
    <div class="row main-container">

        <div class="main-content <?php if(get_post_meta($post->ID,'layout',true)=='one-col'){echo 'col12';}else{?>col9<?php }?>">

            <!-- #Content Starts -->
            <?php colabs_content_before(); ?>
            
            <header class="entry-header">
			  <h2><?php the_title(); ?></h2>
			</header>
			
			<?php while (have_posts()) : the_post();?>
			<div <?php post_class(); ?>>
				<?php colabs_post_inside_before(); ?>
				
				<div class="entry-content">

					<?php the_content(); ?>
					
				</div>
				<!-- /.entry-content -->
					
            </div>
			<?php endwhile;?>
			
            <?php colabs_content_after(); ?>
            
        </div><!-- /.main-content -->  
		
		<?php if(get_post_meta($post->ID,'layout',true)!='one-col'){get_sidebar('page');} ?>
		
    </div><!-- /.main-container -->
	
<?php colabs_main_after(); ?>
  
<?php get_footer(); ?>