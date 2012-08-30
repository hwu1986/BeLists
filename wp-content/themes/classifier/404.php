<?php get_header(); ?>

<?php if ( function_exists('colabs_cl_breadcrumb') ) colabs_cl_breadcrumb(); ?>

  <div class="row main-container">

    <div class="main-content col9">
      <article class="entry">       
        <header class="entry-header">
          <h2><?php _e('Error 404 - Page not found!', 'colabsthemes') ?></h2>
        </header>

        <div class="entry-content">

            <p><?php _e('The page you trying to reach does not exist, or has been moved. Please use the menus or the search box to find what you are looking for.', 'colabsthemes') ?></p>
            
        </div>
        <!-- /.entry-content -->

      </article>
      <!-- /.post -->
    </div>
    <!-- .main-content -->

    <?php get_sidebar(); ?>

  </div>
  <!-- /.main-container -->

<?php get_footer(); ?>