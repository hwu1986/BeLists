<?php
/*
Template Name: Left Sidebar
*/
?>
<?php get_header(); ?>

<?php if ( function_exists('colabs_cl_breadcrumb') ) colabs_cl_breadcrumb(); ?>

  <div class="row main-container leftsidebar">

    <div class="main-content col9">
      <article class="entry">       
        <header class="entry-header">
          <h2><?php the_title(); ?></h2>
        </header>

        <div class="entry-images">
          <div class="entry-slides">
            <?php colabs_image(); ?>
          </div>
        </div>

        <div class="entry-content">

            <?php the_content(); ?>
            
        </div>
        <!-- /.entry-content -->

      </article>
      <!-- /.post -->
    </div>
    <!-- .main-content -->

    <?php get_sidebar('page'); ?>

  </div>
  <!-- /.main-container -->

<?php get_footer(); ?>
