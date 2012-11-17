<?php

// Register widgetized areas

if (!function_exists('the_widgets_init')) {
	function the_widgets_init() {
	    if ( !function_exists('register_sidebars') )
	        return;
    //Sidebar
    register_sidebar(array(
        'name' => 'Sidebar',
        'id' => 'sidebar',
        'description' => 'This is your main Sidebar',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4> ',
    ));
    
    //Ad Sidebar
    register_sidebar(array(
        'name'          => 'Ad Sidebar',
        'id'            => 'sidebar_ad',
        'description'   => 'This is your Classifier single ad sidebar',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4> ',
    ));

    //Page Sidebar
    register_sidebar(array(
        'name'          => __('Page Sidebar','colabsthemes'),
        'id'            => 'sidebar_page',
        'description'   => __('This is your Classifier page sidebar.','colabsthemes'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4> ',
    ));
    
    //Footer
    register_sidebar(array(
        'name' => 'Footer Widgets',
        'id' => 'footer-sidebar',
        'description' => '',
        'before_widget' => '<div id="%1$s" class="col3 widget %2$s"><div class="widget-inner-content">',
        'after_widget' => '</div></div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4> ',
    ));

    }
}

add_action( 'init', 'the_widgets_init' );


    
?>