<?php 
/**
 * Template Name: Sitemap
 *
 *
 * @package CoLabsFramework
 * @subpackage Template
 */

global $colabs_options;
get_header(); ?>

<?php if ( function_exists('colabs_cl_breadcrumb') ) colabs_cl_breadcrumb(); ?>

<!-- #Main Starts -->
<?php colabs_main_before(); ?>
    <div class="row main-container">

        <div class="main-content col9">

            <!-- #Content Starts -->
            <?php colabs_content_before(); ?>
            
            <header class="entry-header">
			  <h2><?php the_title(); ?></h2>
			</header>
			
			<div class="entry">
				<h3><?php _e('Pages:');?></h3>
				<ul >
				<?php wp_list_pages('title_li='); ?>
				</ul>
			</div>
			
			<div class="entry">
				<h3><?php _e('Post Categories:');?></h3>
				<ul >
				<?php wp_list_categories('title_li=&hierarchical=0&show_count=1') ?>
				</ul>
			</div>
			
			<div class="entry">
				<h3><?php _e('Monthly Archives:');?></h3>
				<ul >
				<?php wp_get_archives('type=monthly'); ?>
				</ul>
			</div>
			
			<div class="entry">
				<h3><?php _e('RSS Feed :');?></h3>
				<ul>
					<li><a href="<?php bloginfo('rdf_url'); ?>" title="RDF/RSS 1.0 feed"><acronym title="Resource Description Framework">RDF</acronym>/<acronym title="Really Simple Syndication">RSS</acronym> 1.0 feed</a></li>
					<li><a href="<?php bloginfo('rss_url'); ?>" title="RSS 0.92 feed"><acronym title="Really Simple Syndication">RSS</acronym> 0.92 feed</a></li>
					<li><a href="<?php bloginfo('rss2_url'); ?>" title="RSS 2.0 feed"><acronym title="Really Simple Syndication">RSS</acronym> 2.0 feed</a></li>
					<li><a href="<?php bloginfo('atom_url'); ?>" title="Atom feed">Atom feed</a></li>
				</ul>
			</div>
			
			<?php 
				$taxonomy     = 'ad_cat';
				$orderby      = 'name'; 
				$show_count   = 1;      // 1 for yes, 0 for no
				$pad_counts   = 1;      // 1 for yes, 0 for no
				$hierarchical = 1;      // 1 for yes, 0 for no
				$title        = '';
				
				$args = array(
					'taxonomy'     => $taxonomy,
					'orderby'      => $orderby,
					'show_count'   => $show_count,
					'pad_counts'   => $pad_counts,
					'hierarchical' => $hierarchical,
					'title_li'     => $title
				);
			if (get_categories( $args ))	{	
				?>
			<div class="entry">
				
				<h3><?php _e('Ad Categories:');?></h3>
				<ul >
				<?php wp_list_categories( $args ); ?>
				</ul>
			</div>
			<?php }?>
			
			<div class="entry">
				
				<h3><?php _e('Ad Tags:');?></h3>
				<p>
				<?php 
				wp_tag_cloud( array( 'taxonomy=ad_tag&smallest=12&largest=18' ) );
				?>
				</p>
			</div>
			
			<?php 
				$taxonomy     = 'ad_location';
				$orderby      = 'name'; 
				$show_count   = 1;      // 1 for yes, 0 for no
				$pad_counts   = 1;      // 1 for yes, 0 for no
				$hierarchical = 1;      // 1 for yes, 0 for no
				$title        = '';
				
				$args = array(
					'taxonomy'     => $taxonomy,
					'orderby'      => $orderby,
					'show_count'   => $show_count,
					'pad_counts'   => $pad_counts,
					'hierarchical' => $hierarchical,
					'title_li'     => $title
				);
			if (get_categories( $args ))	{	
				?>
			<div class="entry">
				
				<h3><?php _e('Ad Locations:');?></h3>
				<ul >
				<?php wp_list_categories( $args ); ?>
				</ul>
			</div>
			<?php }?>
			
            <?php colabs_content_after(); ?>
            
        </div><!-- /.main-content -->  
		
		<?php get_sidebar(); ?>
		
    </div><!-- /.main-container -->
	
<?php colabs_main_after(); ?>
  
<?php get_footer(); ?>

