<?php get_header(); ?>

  <?php get_template_part('includes/featured','list'); ?>

  <div class="row main-container">

    <div class="main-content col9">

      <div class="content-tab">
        <ul class="tab-nav">
          <li><a href="#ad-categories"><?php _e('Ad Categories','colabsthemes'); ?></a></li>
          <?php colabs_cat_tab_list(); ?>
        </ul>
        <!-- .tab-nav -->

        <div class="tab-panel" id="ad-categories">
        
            <?php echo colabs_cat_menu_drop_down( get_option('colabs_dir_sub_num') ); ?>
          
        </div>
        <!-- /#ad-categories -->

        <?php colabs_cat_tab_content(); ?>
        
      </div>
      <!-- /.content-tab -->

    <div class="content-tab">
        <ul class="tab-nav">
          <li><a href="#latest-news"><?php _e('Latest Posts','colabsthemes'); ?></a></li>
          <li><a href="#popular-post"><?php _e('Popular Posts','colabsthemes'); ?></a></li>
        </ul>
        
        <div class="tab-panel" id="latest-news">
        
		<?php 
			// WP 3.0 PAGED BUG FIX
			if ( get_query_var('paged') )
				$paged = get_query_var('paged');
			elseif ( get_query_var('page') ) 
				$paged = get_query_var('page');
			else 
				$paged = 1;
			 
			 query_posts("post_type=post&paged=$paged"); 
		?>
		
        <?php get_template_part('loop','index'); ?>

        <?php colabs_pagination(); ?>
        
        </div>
        <!-- /#latest-news -->
		
		<div class="tab-panel" id="popular-post">
        
		<?php 
			// WP 3.0 PAGED BUG FIX
			if ( get_query_var('paged') )
				$paged = get_query_var('paged');
			elseif ( get_query_var('page') ) 
				$paged = get_query_var('page');
			else 
				$paged = 1;
			 
			 query_posts("post_type=post&paged=$paged&orderby=comment_count"); 
		?>
		
        <?php get_template_part('loop','index'); ?>
        
        <?php colabs_pagination(); ?>
        
        </div>
        <!-- /#latest-news -->
        
    </div>
    <!-- .content-tab -->    
    
    </div>
    <!-- .main-content -->

    <?php get_sidebar(); ?>

  </div>
  <!-- /.main-container -->

<?php get_footer(); ?>