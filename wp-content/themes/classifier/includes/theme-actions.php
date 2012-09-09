<?php 

/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

1. Add specific IE hacks to HEAD
1. Add custom styling to HEAD
2. Add custom typograhpy to HEAD

-----------------------------------------------------------------------------------*/


add_action('wp_head','colabs_IE_head');					// Add specific IE styling/hacks to HEAD
add_filter( 'body_class','colabs_layout_body_class', 10 );					// Add layout to body_class output
add_action('colabs_head','colabs_custom_styling');			// Add custom styling to HEAD



/*-----------------------------------------------------------------------------------*/
/* 1. Add specific IE hacks to HEAD */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('colabs_IE_head')) {
	function colabs_IE_head() {
?>
<!--[if IE 6]>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/pngfix.js"></script>
<![endif]-->	
<?php
	}
}

/*-----------------------------------------------------------------------------------*/
/* 2. Add Custom Styling to HEAD */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('colabs_custom_styling')) {
	function colabs_custom_styling() {
		
		$output = '';
		// Get options
		$general_color = get_option('colabs_general_color');
		$hover_color = get_option('colabs_hover_color');
			
		// Add CSS to output
		if ($general_color){
			$output .= '.featured-listing .sale-price, .entry-slides .sale-price, .category-listing ul ul a:hover, .category-listing > ul > li:hover:before, .pagination .page-numbers:hover, .pagination .current, .account-bar a:hover:before, .dk_toggle:after, .btn-primary, .submit-steps .current-step span:before, .widget_colabs_taxonomy_categories li a:hover, .colabsTabs a.selected, .entry-header .price-tag, .listing-info h4 .price-tag {background-color:'.$general_color.'}' . "\n";
			$output .= '.nav-menu a:hover, .tab-nav a:hover, .nav-menu .selected a, .tab-nav .selected a, .nav-menu .current-menu-item a, .tab-nav .current-menu-item a, .nav-menu .current_page_item a, .tab-nav 				.current_page_item a {background-color:'.$general_color.'}' . "\n";
			$output .= '.nav-menu:first-child, .tab-nav:first-child, .colabsTabs:first-child {border-bottom-color:'.$general_color.'}' . "\n";
			$output .= 'a, .category-block .count, .account-bar a:hover,.featured-listing nav a:hover, .featured-listing nav a.selected, .table-my-ads .ad-status, .form-submit-listing .input-bordered label, .form-review-listing .input-bordered label, .main-nav .menu > li.current-menu-item > a, .tagcloud a:hover, .carousel-desc .price, .widget-item-list .price, .widget-item-list .more-link {color:'.$general_color.'}' . "\n";
			$output .= '.footer-widgets .widget-title, .main-nav .menu > li > a:hover, .main-nav ul .children, .main-nav ul .sub-menu {border-top-color:'.$general_color.'}' . "\n";
			$output .= '.footer-widgets .widget li:before, .featured-listing nav a:hover, .featured-listing nav a.selected, input, textarea, select, .pagination .page-numbers:hover, .pagination .current, .search-categories .dk_toggle, .main-nav .menu > li.current-menu-item > a, .submit-steps .current-step span:before, .widget_colabs_taxonomy_categories li a:hover {border-color:'.$general_color.'}' . "\n";

		}
		
		if ($hover_color){
			$output .= 'a:hover {color:'.$hover_color.'}' . "\n";
			$output .= '.btn-primary:hover, .btn-primary:active, .btn-primary.active, .btn-primary.disabled, .btn-primary[disabled] {background-color:'.$hover_color.'}' . "\n";
		}
		
		// Output styles
		if (isset($output) && $output != '') {
			$output = strip_tags($output);
			$output = "<!-- ColorLabs Custom Styling -->\n<style type=\"text/css\">\n" . $output . "</style>\n";
			echo $output;
		}
			
	}
} 



// Output stylesheet and custom.css after custom styling
remove_action('wp_head', 'colabsthemes_wp_head');
add_action('colabs_head', 'colabsthemes_wp_head');

/*-----------------------------------------------------------------------------------*/
/* END */
/*-----------------------------------------------------------------------------------*/

/**
 * add the stats after the ad listing and blog post content 
 * @since 1.0.0
 */
function colabs_do_loop_stats() {
	if ( is_page() || is_singular( array( 'post', COLABS_POST_TYPE ) ) ) return; // don't do on pages
	global $post;
?>		
	<p class="stats"><?php if ( get_option('colabs_ad_stats_all') == 'true' ) colabsthemes_get_stats( $post->ID ); ?></p>
<?php
}
add_action( 'colabsthemes_after_post_content', 'colabs_do_loop_stats' );
add_action( 'colabsthemes_after_blog_post_content', 'colabs_do_loop_stats' );

/**
 * add the ad reference ID after the ad listing content 
 * @since 1.0.0
 */
function colabs_do_ad_ref_id() {
	if ( !is_singular( COLABS_POST_TYPE ) ) return;
	global $post;
?>		
	<div class='note'><strong><?php _e( 'Ad Reference ID:', 'colabsthemes' ); ?></strong> <?php if ( get_post_meta( $post->ID, 'colabs_sys_ad_conf_id', true ) ) echo get_post_meta( $post->ID, 'colabs_sys_ad_conf_id', true ); else echo __( 'N/A', 'colabsthemes' ); ?></div>
<?php
}
add_action( 'colabsthemes_after_post_content', 'colabs_do_ad_ref_id' );

/**
 * add the blog post meta footer content 
 * @since 1.0.0
 */
function colabs_blog_post_meta_footer() {
    if ( !is_singular( array( 'post', COLABS_POST_TYPE ) ) ) return;
	global $post;
?>		
	<div class="prdetails">
	    <?php if ( is_singular( 'post' ) ) { ?>
        <p class="tags"><?php if ( get_the_tags() ) echo the_tags( '<strong>Tags : </strong>', ', ', '&nbsp;' ); else echo __( 'No Tags', 'colabsthemes' ); ?></p>
        <?php } else { ?>
        <p class="tags"><?php if ( get_the_term_list( $post->ID, COLABS_TAX_TAG ) ) echo get_the_term_list( $post->ID, COLABS_TAX_TAG, '<strong>Tags : </strong>', ', ', '&nbsp;' ); else echo __('No Tags', 'colabsthemes'); ?></p>
        <?php } ?>
        <?php if ( get_option( 'colabs_ad_stats_all') == 'true' ) { ?><p class="stats"><?php colabsthemes_stats_counter( $post->ID ); ?></p> <?php } ?>
        <?php edit_post_link( '<p class="edit">'.__( 'Edit Post', 'colabsthemes' ), '', '' ).'</p>'; ?>
    </div>
   
    <?php 
}

//add_action('colabsthemes_after_blog_post_content', 'colabs_blog_post_meta_footer');
//add_action('colabsthemes_after_post_content', 'colabs_blog_post_meta_footer');


/**
 * add additional category tab lists
 * @since 1.0.0
 */
if (!function_exists('colabs_topcat_menu_drop_down')) {
    function colabs_topcat_menu_drop_down() {
    
        $cats = get_terms(COLABS_TAX_CAT, 'hide_empty=1&hierarchical=1&pad_counts=1&show_count=1&orderby=count&order=DESC');
        
        return $cats;
        
    }
}

function cat_tab_list(){
?>
<li><a href="#top-categories"><?php _e('Top Categories','colabsthemes'); ?></a></li>
<?php
}

function cat_tab_content(){
?>
<div class="tab-panel" id="top-categories">

    <?php 
    add_filter('cat_menu_drop_down','colabs_topcat_menu_drop_down');
    //variable
    $cat_top_number = get_option('colabs_topcat_number');
    if( empty($cat_top_number) ) $cat_top_number = '5';
        
    echo colabs_cat_menu_drop_down( $cat_top_number ); 
    ?>
  
</div>
<?php
}
add_action('colabs_cat_tab_list', 'cat_tab_list');
add_action('colabs_cat_tab_content', 'cat_tab_content');

/*-----------------------------------------------------------------------------------*/
/* Add layout to body_class output */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'colabs_layout_body_class' ) ) {
	function colabs_layout_body_class( $classes ) {
	
		$layout = '';
		// Set main layout
		if ( is_singular() ) {
			global $post;
			$layout = get_post_meta($post->ID, 'layout', true); }

        
        //set $colabs_option
        if ( $layout != '' ) {
			global $colabs_options;
            $colabs_options['colabs_layout'] = $layout; } else {
                $layout = get_option( 'colabs_layout' );
				if ( $layout == '' ) $layout = "two-col-left";
            }
                
		
		// Add classes to body_class() output 
		$classes[] = $layout;
        
		return apply_filters('colabs_layout_body_class', $classes);
	}
}

/*-----------------------------------------------------------------------------------*/
/* Add header to listing submission template */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'colabs_submit_order' ) ) {
	function colabs_submit_order() {
    
    $step1 = '';
    $step2 = '';
    $step3 = '';
    
    // check and make sure the form was submitted from step1 and the session value exists
    if(isset($_POST['step1'])) {
        $step2 = 'current-step';
    } elseif(isset($_POST['step2'])) {
        $step3 = 'current-step';
    }else {
        $step1 = 'current-step'; 
    }
    ?>
    <ul class="submit-steps">
        <li><h3><?php _e('Submit Your Listing','colabsthemes');?></h3></li>
        <li class="<?php echo $step1; ?> step-one"><span><?php _e('Fill The Form','colabsthemes');?></span></li>
        <li class="<?php echo $step2; ?> step-two"><span><?php _e('Review Your Order','colabsthemes');?></span></li>
        <li class="<?php echo $step3; ?> step-three"><span><?php _e('Make Payment','colabsthemes');?></span></li>
    </ul>
    <!-- /.submit-steps -->
    <?php
    }
}
add_action('colabsthemes_before_submit', 'colabs_submit_order');

/*-----------------------------------------------------------------------------------*/
/* Add header to listing submission template */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'colabs_submit_member' ) ) {
	function colabs_submit_member() {
	   
    if(!is_page_template('template-member-buy.php')) return;
    
    $step1 = '';
    $step2 = '';
    $step3 = '';
    
    // check and make sure the form was submitted from step1 and the session value exists
    if(isset($_POST['step1'])) {
        $step2 = 'current-step';
    } elseif(isset($_POST['step2'])) {
        $step3 = 'current-step';
    }else {
        $step1 = 'current-step'; 
    }
    ?>
    <div class="row">
    <ul class="submit-steps">
        <li><h3><?php _e('Purchase A Membership Pack','colabsthemes');?></h3></li>
        <li class="<?php echo $step1; ?> step-one"><span><?php _e('Choose Pack','colabsthemes');?></span></li>
        <li class="<?php echo $step2; ?> step-two"><span><?php _e('Review Pack','colabsthemes');?></span></li>
        <li class="<?php echo $step3; ?> step-three"><span><?php _e('Make Payment','colabsthemes');?></span></li>
    </ul>
    <!-- /.submit-steps -->
    </div>
    <?php
    }
}
add_action('colabs_main_before', 'colabs_submit_member');

?>