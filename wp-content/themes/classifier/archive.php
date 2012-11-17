<?php get_header(); global $colabs_options; ?>
    <?php 
        if ( function_exists('colabs_cl_breadcrumb') ) colabs_cl_breadcrumb();  
    ?>

    <div class="row main-container">

		<div class="main-content col9">
        
        <div class="content-tab">
            
            <?php get_template_part('loop','archive'); ?>
            
			<?php colabs_pagination(); ?>
        
        </div><!-- /.content-tab -->
        
		</div><!-- /.main-content -->
        
        <?php get_sidebar(); ?>

    </div><!-- /.main-container -->
		
<?php get_footer(); ?>