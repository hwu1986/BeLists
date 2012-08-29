<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7 ie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8 ie7" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9 ie8" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <title><?php colabs_title(); ?></title>

<?php 
	if ( function_exists( 'colabs_meta') ) colabs_meta();
	if ( function_exists( 'colabs_meta_head') )colabs_meta_head(); 
    global $colabs_options;    
?>

  <!-- CSS -->
  <link href="http://fonts.googleapis.com/css?family=Lato:400,700|Armata" rel="stylesheet" type="text/css">
  <link href="<?php bloginfo('template_url'); ?>/includes/css/colabs-css.css" rel="stylesheet" type="text/css" />
  <link href="<?php bloginfo('template_url'); ?>/includes/css/plugins.css" rel="stylesheet" type="text/css" />
  <link href="<?php bloginfo('stylesheet_url'); ?>" rel="stylesheet" type="text/css" />

<?php 
    if ( function_exists( 'colabs_head') ) colabs_head();
    wp_head(); 
	$site_title = get_bloginfo( 'name' );
	$site_url = home_url( '/' );
	$site_description = get_bloginfo( 'description' ); 
?>
  <?php if(get_option('colabs_disable_mobile')=='false') : ?>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <?php endif; ?>
</head>
<body <?php body_class(); ?>>

<?php colabs_header_before(); ?>

<section class="container section-header">
  <div class="row">

    <header class="logo col4">
	  <?php
			if (get_option('colabs_logotitle')=='logo'){
			if ( isset($colabs_options['colabs_logo']) && $colabs_options['colabs_logo'] ) {
				echo '<div id="logo"><a href="' . $site_url . '" title="' . $site_description . '"><img src="' . $colabs_options['colabs_logo'] . '" alt="' . $site_title . '" /></a></div>';
			} 
			}else {
				echo '<h1><a href="' . $site_url . '">' . $site_title . '</a></h1>';
			} // End IF Statement
      ?>    
      <hgroup class="tagline">
        <?php
			if ( $site_description ) { echo '<h3>' . $site_description . '</h3>'; }
		?>      
      </hgroup>
    </header>
    <!-- /.logo -->

    <form action="<?php bloginfo('url'); ?>" method="get" id="searchform" class="form_search advance-search col8">
      <p class="search-where">
        <!--label><?php _e('Where','colabsthemes'); ?></label>
        <input type="text" placeholder="<?php _e('Zip Code, Address or City','colabsthemes'); ?>"-->
        <input type="submit"  id="go" name="sa" value="<?php _e('Search','colabsthemes'); ?>">
      </p>
      <p class="search-categories">
        <label><?php _e('In','colabsthemes'); ?></label>
        <?php wp_dropdown_categories('show_option_all='.__('All Categories', 'colabsthemes').'&title_li=&use_desc_for_title=1&tab_index=2&name=scat&selected='.colabs_get_search_catid().'&class=custom-select&taxonomy='.COLABS_TAX_CAT.'&echo=false');
        ?>
      </p>
      <p class="search-what">
        <label><?php _e('What','colabsthemes'); ?></label>
        <input name="s" type="text" <?php if(get_search_query()) { echo 'value="'.trim(strip_tags(esc_attr(get_search_query()))).'"'; } else { ?> value="<?php _e('What are you looking for?','colabsthemes'); ?>" onfocus="if (this.value == '<?php _e('What are you looking for?','colabsthemes'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('What are you looking for?','colabsthemes'); ?>';}" <?php } ?>>
      </p>
    </form>
    <!-- .advance-search -->
    
  </div>
</section>
<!-- /.section-header -->

<div class="container main-nav">
  <div class="row">
    <?php 
    $arr = array(
        'theme_location' => 'main-menu',
        'container' => 'div',
        'container_class' => 'main-menu',
    );
    wp_nav_menu($arr); ?>
  </div>
</div>
<!-- /.main-nav -->

<?php if( is_home() ){ ?>
<section class="container section-featured">
  <div class="row">

    <?php get_template_part('includes/featured','main'); ?>

    <?php 
    $arr = array(
        'theme_location' => 'secondary-menu',
        'container' => 'div',
        'container_class' => 'category-listing',
        'fallback_cb' => 'secondarymenu',
    );
    wp_nav_menu($arr); ?>

  </div>
</section>
<!-- /.section-featured -->
<?php } wp_reset_query(); ?>

<div class="container">  
