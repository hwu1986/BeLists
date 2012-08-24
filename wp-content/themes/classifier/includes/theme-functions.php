<?php 

/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

- Excerpt
- Page navigation
- CoLabsTabs - Popular Posts
- CoLabsTabs - Latest Posts
- CoLabsTabs - Latest Comments
- Post Meta
- Dynamic Titles
- WordPress 3.0 New Features Support
- using_ie - Check IE
- post-thumbnail - WP 3.0 post thumbnails compatibility
- automatic-feed-links Features
- colabs_link - Alternate Link & RSS URL
- Open Graph Meta Function
- colabs_share - Twitter, FB & Google +1
- Post meta Portfolio

-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* SET GLOBAL CoLabs VARIABLES
/*-----------------------------------------------------------------------------------*/

// Slider Tags
	$GLOBALS['slide_tags_array'] = array();
// Duplicate posts 
	$GLOBALS['shownposts'] = array();

// set global path variables
global $colabs_theme, $colabs_abbr, $colabs_version, $colabs_edition, $colabs_rss_feed, $colabs_twitter_rss_feed, $colabs_forum_rss_feed, $colabs_db_tables, $colabs_transients;

// current  version
$colabs_theme = 'Classifier';
$colabs_abbr = 'colabs';
$colabs_version = '1.0.0';
$colabs_edition = 'Premium Theme';

// define rss feed urls
$colabs_rss_feed = 'http://feeds2.feedburner.com/colorlabs';
$colabs_twitter_rss_feed = 'http://twitter.com/statuses/user_timeline/colorlabs.rss';
$colabs_forum_rss_feed = 'http://colorlabsproject.com/forum/forums/affiliate-testimonials.42/index.rss'; //http://colorlabsproject.com/forum/forums/affiliate-testimonials.42/index.rss

// define the db tables we use
$colabs_db_tables = array($colabs_abbr.'_ad_forms', $colabs_abbr.'_ad_meta', $colabs_abbr.'_ad_fields', $colabs_abbr.'_ad_pop_daily', $colabs_abbr.'_ad_pop_total' , $colabs_abbr.'_ad_packs', $colabs_abbr.'_order_info');

// define the transients we use
$colabs_transients = array($colabs_abbr.'_cat_menu');


define('CL_DASHBOARD_URL', get_bloginfo('url').'/'.get_option($colabs_abbr.'_dashboard_url').'/');
define('CL_PROFILE_URL', get_bloginfo('url').'/'.get_option($colabs_abbr.'_profile_url').'/');
define('CL_EDIT_URL', get_bloginfo('url').'/'.get_option($colabs_abbr.'_edit_item_url').'/');
define('CL_ADD_NEW_URL', get_bloginfo('url').'/'.get_option($colabs_abbr.'_add_new_url').'/');
define('CL_ADD_NEW_CONFIRM_URL', get_bloginfo('url').'/'.get_option($colabs_abbr.'_add_new_confirm_url').'/');
define('COLABS_MEMBERSHIP_BUY_URL', get_permalink(get_option($colabs_abbr.'_membership_buy')));
define('COLABS_MEMBERSHIP_AUTH_URL', get_permalink(get_option($colabs_abbr.'_membership_auth')));

/*-----------------------------------------------------------------------------------*/
/* Excerpt
/*-----------------------------------------------------------------------------------*/

//Add excerpt on pages
if(function_exists('add_post_type_support'))
add_post_type_support('page', 'excerpt');

/** Excerpt character limit */
/* Excerpt length */
function colabs_excerpt_length($length) {
if( get_option('colabs_excerpt_length') != '' ){
        return get_option('colabs_excerpt_length');
    }else{
        return 45;
    }
}
add_filter('excerpt_length', 'colabs_excerpt_length');

/** Remove [..] in excerpt */
function colabs_trim_excerpt($text) {
  return rtrim($text,'[...]');
}
add_filter('get_the_excerpt', 'colabs_trim_excerpt');

/** Add excerpt more */
function colabs_excerpt_more($more) {
    global $post;
	//return '<span class="more"><a href="'. get_permalink($post->ID) . '">'. __( 'Read more', 'colabsthemes' ) . '&hellip;</a></span>';
}
add_filter('excerpt_more', 'colabs_excerpt_more');

// Shorten Excerpt text for use in theme
function colabs_excerpt($text, $chars = 120) {
	$text = $text." ";
	$text = substr($text,0,$chars);
	$text = substr($text,0,strrpos($text,' '));
	$text = $text."...";
	return $text;
}



// get_the_excerpt filter
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'custom_trim_excerpt');

function custom_trim_excerpt($text) { // Fakes an excerpt if needed
global $post;
	if ( '' == $text ) {
		$text = get_the_content('');

		$text = strip_shortcodes( $text );

		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]&gt;', $text);
		$text = strip_tags($text);
		$excerpt_length = apply_filters('excerpt_length', 45);
		$words = explode(' ', $text, $excerpt_length + 1);
		if (count($words) > $excerpt_length) {
			array_pop($words);
            $excerpt_more = apply_filters('excerpt_more', '...');
			array_push($words, '...');
            array_push($words, $excerpt_more);
			$text = implode(' ', $words);
		}
	}
	return $text;
}

//Custom Excerpt Function
function colabs_custom_excerpt($limit,$more,$container = true) {
	global $post;
	if ($limit=='')$limit=35;
	if($container) $print_excerpt = '<p>';
	$output = $post->post_excerpt;
	if ($output!=''){
	$print_excerpt .= $output;
	}else{
	$content = get_the_content('');
	$content = strip_shortcodes( $content );
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	$content = strip_tags($content);	
	$excerpt = explode(' ',$content, $limit);
	array_pop($excerpt);
	$print_excerpt .= implode(" ",$excerpt).$more;
	}
	if($container) $print_excerpt .= '</p>';
	echo $print_excerpt;
}

/*-----------------------------------------------------------------------------------*/
/* Breadcrumbs */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('colabs_breadcrumb')){
function colabs_breadcrumb() {
     
  $delimiter = '&raquo;';
  $home = 'Home'; // text for the 'Home' link
  $before = '<span class="current">'; // tag before the current crumb
  $after = '</span>'; // tag after the current crumb
 
  if ( !is_home() && !is_front_page() || is_paged() ) {
 
    //echo '<div id="crumbs">';
 
    global $post;
    $homeLink = get_bloginfo('url');
    echo '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
 
    if ( is_category() || is_tax() ) {
      global $wp_query;
      $cat_obj = $wp_query->get_queried_object();
      $thisCat = $cat_obj->term_id;
      $thisCat = get_category($thisCat);
      $parentCat = get_category($thisCat->parent);
      if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
      echo $before . single_cat_title('', false) . $after;
	
	} elseif ( is_day() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('d') . $after;
 
    } elseif ( is_month() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('F') . $after;
 
    } elseif ( is_year() ) {
      echo $before . get_the_time('Y') . $after;
 
    } elseif ( is_single() && !is_attachment() ) {
      if ( get_post_type() != 'post' ) {
        $post_type = get_post_type_object(get_post_type());
        $slug = $post_type->rewrite;
        //echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
        echo $before . get_the_title() . $after;
      } else {
        $cat = get_the_category(); $cat = $cat[0];
        echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
        echo $before . get_the_title() . $after;
      }
 
    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
      $post_type = get_post_type_object(get_post_type());
      echo $before . $post_type->labels->singular_name . $after;
 
    } elseif ( is_attachment() ) {
      $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
      echo $before . get_the_title() . $after;
 
    } elseif ( is_page() && !$post->post_parent ) {
      echo $before . get_the_title() . $after;
 
    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
      echo $before . get_the_title() . $after;
 
    } elseif ( is_search() ) {
      echo $before . 'Search results for "' . get_search_query() . '"' . $after;
 
    } elseif ( is_tag() ) {
      echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
 
    } elseif ( is_author() ) {
       global $author;
      $userdata = get_userdata($author);
      echo $before . 'Articles posted by ' . $userdata->display_name . $after;
 
    } elseif ( is_404() ) {
      echo $before . 'Error 404' . $after;
    } 
 
    // if ( get_query_var('paged') ) {
    //  if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
    //  echo __('Page') . ' ' . get_query_var('paged');
    //  if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    //} 
 
    //echo '</div>';
 
  }
}}

/*End of Breadcrumbs*/

/*-----------------------------------------------------------------------------------*/
/* Page navigation */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('colabs_pagenav')) {
	function colabs_pagenav() {   
	    
			 if ( get_next_posts_link() || get_previous_posts_link() ) { ?>

                <div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&laquo;</span> Previous Entries', 'colabsthemes' ) ); ?></div>
                <div class="nav-next"><?php previous_posts_link( __( 'Next Entries <span class="meta-nav">&raquo;</span>', 'colabsthemes' ) ); ?></div>

			<?php } ?>

		<?php 
	}
}

if (!function_exists('colabs_postnav')) {
	function colabs_postnav() {
		?>
    <div class="navigation">
        <div class="navleft fl"><?php next_post_link('%link','&laquo; Prev') ;?></div>
		<div class="navcenter gohome"><a href="<?php echo get_option('home');?>">Back to home</a></div>
        <div class="navright fr"><?php previous_post_link('%link','Next &raquo;'); ?></div>
        
    </div><!--/.navigation-->
		<?php 
	}
}

/*-----------------------------------------------------------------------------------*/
/* CoLabsTabs - Popular Posts */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('colabs_404')) {
	function colabs_404(){

        echo "<p>It seems that page you were looking for doesn't exist.Try searching the site.</p>";
   
	}
}
/*-----------------------------------------------------------------------------------*/
/* CoLabsTabs - Popular Posts */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('colabs_tabs_popular')) {
	function colabs_tabs_popular( $posts = 5, $size = 35 ) {
		global $post;
		$popular = get_posts('caller_get_posts=1&orderby=comment_count&showposts='.$posts);
		foreach($popular as $post) :
			setup_postdata($post);
	?>
	<li>
		<?php if ($size <> 0) colabs_image('height='.$size.'&width='.$size.'&class=thumbnail&single=true'); ?>
		<a title="<?php the_title(); ?>" href="<?php the_permalink() ?>"><?php the_title(); ?></a>
		<span class="meta"><?php the_time( get_option( 'date_format' ) ); ?></span>
		<div class="fix"></div>
	</li>
	<?php endforeach;
	}
}

/*-----------------------------------------------------------------------------------*/
/* CoLabsTabs - Latest Posts */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('colabs_tabs_latest')) {
	function colabs_tabs_latest( $posts = 5, $size = 35 ) {
		global $post;
		$latest = get_posts('caller_get_posts=1&showposts='. $posts .'&orderby=post_date&order=desc');
		foreach($latest as $post) :
			setup_postdata($post);
	?>
	<li>
		<?php if ($size <> 0) colabs_image('height='.$size.'&width='.$size.'&class=tabs_thumbnail&single=true'); ?>
		<a title="<?php the_title(); ?>" href="<?php the_permalink() ?>"><?php the_title(); ?></a>
		<span class="meta"><?php the_time( get_option( 'date_format' ) ); ?></span>
		<div class="fix"></div>
	</li>
	<?php endforeach; 
	}
}

/*-----------------------------------------------------------------------------------*/
/* CoLabsTabs - Latest Comments */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('colabs_tabs_comments')) {
	function colabs_tabs_comments( $posts = 5, $size = 35 ) {
		global $wpdb;
		$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID,
		comment_post_ID, comment_author, comment_author_email, comment_date_gmt, comment_approved,
		comment_type,comment_author_url,
		SUBSTRING(comment_content,1,50) AS com_excerpt
		FROM $wpdb->comments
		LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID =
		$wpdb->posts.ID)
		WHERE comment_approved = '1' AND comment_type = '' AND
		post_password = ''
		ORDER BY comment_date_gmt DESC LIMIT ".$posts;
		
		$comments = $wpdb->get_results($sql);
		
		foreach ($comments as $comment) {
		?>
		<li>
			<?php echo get_avatar( $comment, $size ); ?>
		
			<a href="<?php echo get_permalink($comment->ID); ?>#comment-<?php echo $comment->comment_ID; ?>" title="<?php _e('on ', 'colabsthemes'); ?> <?php echo $comment->post_title; ?>">
                <span class="author"><?php echo strip_tags($comment->comment_author); ?></span></a>: <span class="comment"><?php echo strip_tags($comment->com_excerpt); ?>...</span>
			
			<div class="fix"></div>
		</li>
		<?php 
		}
	}
}



/*-----------------------------------------------------------------------------------*/
/* Dynamic Titles */
/*-----------------------------------------------------------------------------------*/
// This sets your <title> depending on what page you're on, for better formatting and for SEO

function dynamictitles() {
	
	if ( is_single() ) {
      wp_title('');
     
 
} else if ( is_page() || is_paged() ) {
      
      echo (''.__('Archive for','colabsthemes').'');
 
} else if ( is_author() ) {
     
      wp_title(''.__('Author','colabsthemes').'');	  
	  
} else if ( is_category() ) {
      
      wp_title(''.__('Category for','colabsthemes').'');
      

} else if ( is_tag() ) {
      
      wp_title(''.__('Tag archive for','colabsthemes').'');

} else if ( is_archive() ) {
      
      echo (''.__('Archive for','colabsthemes').'');
     

} else if ( is_search() ) {
      
      echo (''.__('Search Results for ','colabsthemes').'');
		the_search_query();
} else if ( is_404() ) {
      
      echo (''.__('404 Error (Page Not Found)','colabsthemes').'');
	  
} else if ( is_home() ) {
      bloginfo('name');
      echo (' | ');
      bloginfo('description');
 
} else {
      bloginfo('name');
      echo (' | ');
      echo (''.$blog_longd.'');
}
}

/*-----------------------------------------------------------------------------------*/
/* WordPress 3.0 New Features Support */
/*-----------------------------------------------------------------------------------*/

if ( function_exists('register_nav_menus') ) {
	add_theme_support( 'nav-menus' );
    register_nav_menus( array(
        'main-menu' => __( 'Main Menu','colabsthemes' ),
        'secondary-menu' => __( 'Secondary Menu','colabsthemes' ),
));    
}

/* CallBack functions for menus in case of earlier than 3.0 Wordpress version or if no menu is set yet*/
function secondarymenu(){
    //list terms in a given taxonomy using wp_list_categories (also useful as a widget if using a PHP Code plugin)
    
    $taxonomy     = COLABS_TAX_CAT;
    $orderby      = 'name';
    $show_count   = 0;      // 1 for yes, 0 for no
    $pad_counts   = 0;      // 1 for yes, 0 for no
    $hierarchical = 1;      // 1 for yes, 0 for no
    $title        = '';
    $empty        = 0;
    
    $args = array(
      'taxonomy'     => $taxonomy,
      'orderby'      => $orderby,
      'show_count'   => $show_count,
      'pad_counts'   => $pad_counts,
      'hierarchical' => $hierarchical,
      'title_li'     => $title,
      'hide_empty'   => $empty
    );
    ?>
    <div class="category-listing">
        <ul>
        <?php wp_list_categories( $args ); ?>
        </ul>
    </div>
<?php }


if (!function_exists('colabs_nav_fallback')) {
function colabs_nav_fallback($div_id){
    if (is_array($div_id)){ $div_id = $div_id['theme_location']; }
    if ( $div_id == 'main-menu' ){
		add_filter('wp_page_menu','add_mainmenuclass');
        wp_page_menu('depth=0&title_li=&menu_class=');
		remove_filter('wp_page_menu','add_mainmenuclass');
    };
}}

function add_mainmenuclass($ulclass) {
    return preg_replace('/<ul>/', '<ul class="menu">', $ulclass, 1);
}
function add_footermenuclass($ulclass) {
    return preg_replace('/<ul>/', '<ul class="footul">', $ulclass, 1);
}


/*-----------------------------------------------------------------------------------*/
/* using_ie - Check IE */
/*-----------------------------------------------------------------------------------*/
//check IE
function using_ie()
{
    if (isset($_SERVER['HTTP_USER_AGENT']) && 
    (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
        return true;
    else
        return false;    
}

/*-----------------------------------------------------------------------------------*/
/*  WP 3.0 post thumbnails compatibility */
/*-----------------------------------------------------------------------------------*/
if(function_exists( 'add_theme_support')){
	//if(get_option( 'colabs_post_image_support') == 'true'){
    if( get_option('colabs_post_image_support') ){
        add_theme_support( 'post-thumbnails' );		
		// set height, width and crop if dynamic resize functionality isn't enabled
		if ( get_option( 'colabs_pis_resize') <> "true" ) {
			$hard_crop = get_option( 'colabs_pis_hard_crop' );
			if($hard_crop == 'true') {$hard_crop = true; } else { $hard_crop = false;} 
			add_image_size( 'featured-main', 743, 494, $hard_crop);
            add_image_size( 'featured-list', 222, 241, $hard_crop );
			add_image_size( 'thumbnail', 239, 143, $hard_crop );
			add_image_size( 'single-ad', 724, 478, $hard_crop );
            add_image_size( 'tabs_thumbnail', 35, 35, $hard_crop );
		}
	}
} 

/*-----------------------------------------------------------------------------------*/
/*  automatic-feed-links Features  */
/*-----------------------------------------------------------------------------------*/
if ( function_exists( 'add_theme_support' ) && get_option('colabs_feedlinkurl') == '' ) {
    add_theme_support( 'automatic-feed-links' );
}

/*-----------------------------------------------------------------------------------*/
/*  colabs_share - Twitter, FB & Google +1    */
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'colabs_share' ) ) {
function colabs_share() {
    
$return = '';


$colabs_share_twitter = get_option('colabs_single_share_twitter');
$colabs_share_fblike = get_option('colabs_single_share_fblike');
$colabs_share_fb = get_option('colabs_single_share_fb');
$colabs_share_google_plusone = get_option('colabs_single_share_google_plusone');


    //Share Button Functions 
    global $colabs_options;
    $url = get_permalink();
    $share = '';
    
    //Twitter Share Button
    if(function_exists('colabs_shortcode_twitter') && $colabs_share_twitter == "true"){
        $tweet_args = array(  'url' => $url,
   							'style' => 'horizontal',
   							'source' => ( $colabs_options['colabs_twitter_username'] )? $colabs_options['colabs_twitter_username'] : '',
   							'text' => '',
   							'related' => '',
   							'lang' => '',
   							'float' => 'left'
                        );

        $share .= colabs_shortcode_twitter($tweet_args);
    }
    
   
        
    //Google +1 Share Button
    if( function_exists('colabs_shortcode_google_plusone') && $colabs_share_google_plusone == "true"){
        $google_args = array(
						'size' => 'medium',
						'language' => '',
						'count' => '',
						'href' => $url,
						'callback' => '',
						'float' => 'left',
						'annotation' => 'bubble'
					);        

        $share .= colabs_shortcode_google_plusone($google_args);       
    }
	
	 //Facebook Like Button
    if(function_exists('colabs_shortcode_fblike') && $colabs_share_fblike == "true"){
    $fblike_args = 
    array(	
        'float' => 'left',
        'url' => '',
        'style' => 'button_count',
        'showfaces' => 'false',
        'width' => '80',
        'height' => '',
        'verb' => 'like',
        'colorscheme' => 'light',
        'font' => 'arial'
        );
        $share .= colabs_shortcode_fblike($fblike_args);    
    }
    
    $return .= '<div class="social_share clearfloat">'.$share.'</div><div class="clear"></div>';
    
    return $return;
}
}
/*-----------------------------------------------------------------------------------*/
/* colabs_link - Alternate Link & RSS URL */
/*-----------------------------------------------------------------------------------*/
add_action( 'wp_head', 'colabs_link' );
if (!function_exists('colabs_link')) {
function colabs_link(){ 
?>	
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php if ( get_option('colabs_feedlinkurl') ) { echo get_option('colabs_feedlinkurl'); } else { echo get_bloginfo_rss('rss2_url'); } ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
	
<?php 
}}

/*-----------------------------------------------------------------------------------*/
/*  Open Graph Meta Function    */
/*-----------------------------------------------------------------------------------*/
function colabs_meta_head(){
    do_action( 'colabs_meta' );
}
add_action( 'colabs_meta', 'og_meta' );  

if (!function_exists('og_meta')) {
function og_meta(){ ?>
	<?php if ( is_home() && get_option( 'colabs_og_enable' ) == '' ) { ?>
	<meta property="og:title" content="<?php echo bloginfo('name');; ?>" />
	<meta property="og:type" content="author" />
	<meta property="og:url" content="<?php echo get_option('home'); ?>" />
	<meta property="og:image" content="<?php echo get_option('colabs_og_img'); ?>"/>
	<meta property="og:site_name" content="<?php echo get_option('colabs_og_sitename'); ?>" />
	<meta property="fb:admins" content="<?php echo get_option('colabs_og_admins'); ?>" />
	<meta property="og:description" content="<?php echo get_option('blogdescription '); ?>" />
	<?php } ?>
	
	<?php if ( ( is_page() || is_single() ) && get_option( 'colabs_og_enable' ) == '' ) { ?>
	<meta property="og:title" content="<?php the_title(); ?>" />
	<meta property="og:type" content="article" />
	<meta property="og:url" content="<?php echo get_post_meta($post->ID, 'yourls_shorturl', true) ?>" />
	<meta property="og:image" content="<?php $values = get_post_custom_values("Image"); ?><?php echo get_option('home'); ?>/<?php echo $values[0]; ?>"/>
	<meta property="og:site_name" content="<?php echo get_option('colabs_og_sitename'); ?>" />
	<meta property="fb:admins" content="<?php echo get_option('colabs_og_admins'); ?>" />
	<?php } ?>
    
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<?php
}}
	
/*-----------------------------------------------------------------------------------*/	
/* Search Form*/
/*-----------------------------------------------------------------------------------*/
function custom_search( $form ) {

    $form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
    <input type="text" value="' . get_search_query() . '" name="s" id="s" />
    <input type="submit" id="searchsubmit" class="btn btn-primary" value="'. esc_attr__('Search') .'" />
    </form>';

    return $form;
}

add_filter( 'get_search_form', 'custom_search' );

/*-----------------------------------------------------------------------------------*/
/* CoLabs- Flickr */
/*-----------------------------------------------------------------------------------*/    

require_once ($includes_path . 'theme-flickr.php');

function getmyflickr($account,$count){

$flickr_url= 'http://api.flickr.com/services/feeds/photos_public.gne?id=';

$flickr_url.= $account ;

$flickr_url.= '&display=latest&lang=en-us&format=rss_200';

$flickr = new FlickrImages( $flickr_url );

	$title = $flickr->getTitle();

	$url = $flickr->getProfileLink();

	$images = $flickr->getImages();

	$i=1;$j=1;	

	

	$output = '<div class="flickr"><ul >';

	foreach( $images as $img ) {

		if ($i<=$count){

		$output .= '<li>';

		$output .= '<a href="' . $img[ 'link' ] . '">';

		$output .=  $img[ 'thumb' ] ;

		$output .= '</a></li>';

		}

		$i++;$j++;

	}

	$output .= '</ul></div>';

	echo $output;



}

/*-----------------------------------------------------------------------------------*/
/*  colabs_googlemap - Google Maps Function   */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('colabs_googlemap')) {
function colabs_googlemap($latlong, $address, $zoom, $type, $content, $directionsto) {
	
	if (!$latlong) {$latlong = '0';}
	if (!$zoom) {$zoom = 12;} // 1-19
	if (!$type) {$type = 'ROADMAP';} // ROADMAP, SATELLITE, HYBRID, TERRAIN
	if (!$content) {$content = '';}
	if (!$address) {$address = '';}
	
	$content = str_replace('&lt;', '<', $content);
	$content = str_replace('&gt;', '>', $content);
	$content = mysql_escape_string($content);
	if ($directionsto) { $directionsForm = "<form method=\"get\" action=\"http://maps.google.com/maps\"><input type=\"hidden\" name=\"daddr\" value=\"".$directionsto."\" /><input type=\"text\" class=\"text\" name=\"saddr\" /><input type=\"submit\" class=\"submit\" value=\"Directions\" /></form>"; }

	if ($latlong!='0') {	
		return "
		<script type='text/javascript' src='http://maps.google.com/maps/api/js?sensor=false'></script>
		<script type='text/javascript'>
			function makeMap(target) {
				var latlng = new google.maps.LatLng(".$latlong.");
				
				var myOptions = {
					zoom: ".$zoom.",
					center: latlng,
					mapTypeControl: true,
					mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
					navigationControl: true,
					navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
					mapTypeId: google.maps.MapTypeId.".$type."
				};
				var map = new google.maps.Map(document.getElementById(target), myOptions);
				
				var contentString = '<div class=\"infoWindow\">".$content.$directionsForm."</div>';
				var infowindow = new google.maps.InfoWindow({
					content: contentString
				});
				
				var marker = new google.maps.Marker({
					position: latlng,
					map: map,
					title: ''
				});
				
				google.maps.event.addListener(marker, 'click', function() {
				  infowindow.open(map,marker);
				});
			}
			window.onload = function(){
        makeMap('colabsgoogle');
      }
		</script>
		
		<div id='colabsgoogle' class='mapbox coordinate'></div>
		";
	}else{
		return "
		<script type='text/javascript' src='http://maps.google.com/maps/api/js?sensor=false'></script>
		<script>
		/* <![CDATA[ */
		function setMapAddress(address, target)
		{
			var geocoder = new google.maps.Geocoder();

			geocoder.geocode( { address : address }, function( results, status ) {
				if( status == google.maps.GeocoderStatus.OK ) {
					var latlng = results[0].geometry.location;
					var options = {
						zoom: 15,
						center: latlng,
						mapTypeId: google.maps.MapTypeId.ROADMAP, 
						streetViewControl: true
					};

					var mymap = new google.maps.Map( document.getElementById( target ), options );
					
					var marker = new google.maps.Marker({
					map: mymap, 
					position: results[0].geometry.location
				});
					
				}
			} );
		}

		setMapAddress( '".$address."', 'colabsgoogle');

		/* ]]> */
		</script>
		<div id='colabsgoogle' class='mapbox location'></div>
		";
	}
}} 

/*-----------------------------------------------------------------------------------*/
/* CoLabs - Add User Meta */
/*-----------------------------------------------------------------------------------*/ 
function new_user_meta( $contactmethods ) {

$contactmethods['twitter'] = 'Twitter';

$contactmethods['facebook'] = 'Facebook';

return $contactmethods;
}
//add_filter('user_contactmethods','new_user_meta',10,1);



/*-----------------------------------------------------------------------------------*/
/* CoLabs - Footer Credit */
/*-----------------------------------------------------------------------------------*/
function colabs_credit(){
global $themename,$colabs_options;
if( $colabs_options['colabs_footer_credit'] != 'true' ){ ?>
            Copyright &copy; 2011 <a href="http://colorlabsproject.com/themes/<?php echo get_option('colabs_themename'); ?>/" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php echo get_option('colabs_themename'); ?></a> by <a href="http://colorlabsproject.com/" title="ColorLabs">ColorLabs</a>. All rights reserved.
<?php }else{ echo stripslashes( $colabs_options['colabs_footer_credit_txt'] ); } 
}


/*-----------------------------------------------------------------------------------*/
/*  is_mobile - Check Mobile Version */
/*-----------------------------------------------------------------------------------*/
if(!function_exists('is_mobile')){
function is_mobile(){
	$regex_match="/(nokia|iphone|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|";
	$regex_match.="htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|";
	$regex_match.="blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|";	
	$regex_match.="symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|";
	$regex_match.="jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220";
	$regex_match.=")/i";		
	return isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE']) or preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT']));
}}

/*-----------------------------------------------------------------------------------*/
/*  Colabs Post Meta - colabs_post_meta */
/*-----------------------------------------------------------------------------------*/
function colabs_post_meta(){

    if( is_tax() ){ ?>

    <div class="post-meta">
      <span class="comment-count"><?php comments_popup_link(__('0', 'colabsthemes'), __('1', 'colabsthemes'), __('%', 'colabsthemes')); ?></span>
      <time><?php the_time( get_option( 'date_format' ) ); ?></time>
      <p><?php _e('Posted by','colabsthemes'); ?>&nbsp;<a href="#"><?php the_author_posts_link(); ?></a><br/><?php _e('In','colabsthemes'); ?>&nbsp;<?php echo get_the_term_list( $post->ID, COLABS_TAX_CAT, '' , ', ', '' );  ?></p><?php edit_post_link( __('{ Edit }', 'colabsthemes'), '<span class="small">', '</span>' ); ?>
    </div>
    
<?php
    }elseif( is_archive() || is_home() ){
?>

    <div class="post-meta">
      <span class="comment-count"><?php comments_popup_link(__('0', 'colabsthemes'), __('1', 'colabsthemes'), __('%', 'colabsthemes')); ?></span>
      <time><?php the_time( get_option( 'date_format' ) ); ?></time>
      <p><?php _e('Posted by','colabsthemes'); ?>&nbsp;<a href="#"><?php the_author_posts_link(); ?></a><br/><?php _e('In','colabsthemes'); ?>&nbsp;<?php the_category(', ') ?></p><?php edit_post_link( __('{ Edit }', 'colabsthemes'), '<span class="small">', '</span>' ); ?>
    </div>
    
<?php }



}

function colabs_display_date($mysqldate) {
	$display_date = date_i18n( get_option('date_format').' '.get_option('time_format'),strtotime($mysqldate), get_option('gmt_offset') );
	return $display_date;
}

function colabs_ref_id($post_id) {
	global $post;
	if ( !$cp_id = get_post_meta($post->ID, 'colabs_sys_ad_conf_id', true) ) {	
		$ref_id = uniqid( rand(10,1000), false );
		add_post_meta( $post_id, 'colabs_sys_ad_conf_id', $ref_id, true );
	}
	if ( !$cp_tcount = get_post_meta( $post->ID, 'colabs_total_count', true) ) {
		add_post_meta( $post_id, 'colabs_total_count', '0', true );
	}
}
add_action('save_post', 'colabs_ref_id');

function colabs_stats_update($post_id) {
	global $wpdb;
	$nowisnow = date('Y-m-d', current_time('timestamp'));

	$thepost = get_post($post_id);

	//if ($thepost->post_author==get_current_user_id()) return;

	// first try and update the existing total post counter
	$results = $wpdb->query( $wpdb->prepare("UPDATE $wpdb->colabs_pop_total SET postcount = postcount+1 WHERE postnum = %s LIMIT 1", $post_id) );
	// if it doesn't exist, then insert two new records
	// one in the total views, another in today's views
	if ($results == 0) {
		$wpdb->insert($wpdb->colabs_pop_total, array(
			"postnum" => $post_id,
			"postcount" => 1
		));
		$wpdb->insert($wpdb->colabs_pop_daily, array(
			"time" => $nowisnow,
			"postnum" => $post_id,
			"postcount" => 1
		));
	// post exists so let's just update the counter
	} else {
		$results2 = $wpdb->query( $wpdb->prepare("UPDATE $wpdb->colabs_pop_daily SET postcount = postcount+1 WHERE time = %s AND postnum = %s LIMIT 1", $nowisnow, $post_id) );

		// insert a new record since one hasn't been created for current day
		if ($results2 == 0){
			$wpdb->insert($wpdb->colabs_pop_daily, array(
				"time" => $nowisnow,
				"postnum" => $post_id,
				"postcount" => 1
			));
		}
	}

	// get all the post view info so we can update meta fields
	$sql = $wpdb->prepare("
		SELECT t.postcount AS total, d.postcount AS today
		FROM $wpdb->colabs_pop_total AS t
		INNER JOIN $wpdb->colabs_pop_daily AS d ON t.postnum = d.postnum
		WHERE t.postnum = %s AND d.time = %s
	", $post_id, $nowisnow);

	$row = $wpdb->get_row($sql);
	// add the counters to temp values on the post so it's easy to call from the loop
	update_post_meta($post_id, 'colabs_total_count', $row->total);

}

function colabs_delete_ad ($postid) {
  global $wpdb;

	$attachments_query = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_parent = %d AND post_type='attachment'", $postid);
	$attachments = $wpdb->get_results($attachments_query);

  // delete all associated attachments
  if($attachments)
    foreach($attachments as $attachment)
      wp_delete_attachment( $attachment->ID, true );
  
  // delete post and it's revisions, comments, meta
  if( wp_delete_post( $postid, true ) )
    return true;
  else
    return false;
}

function colabs_add_query_vars() {
	global $wp;
	$wp->add_query_var( 'scat' );
}
add_filter('init', 'colabs_add_query_vars');

function custom_search_groupby($groupby) {
	global $wpdb, $wp_query;

	$groupby = "$wpdb->posts.ID";

    return $groupby;
}
// search on custom fields
function custom_search_join($join) {
	global $wpdb, $wp_query;

	if ( is_search() && isset($_GET['s']) ) {

		$join  = " INNER JOIN $wpdb->term_relationships AS r ON ($wpdb->posts.ID = r.object_id) ";
		$join .= " INNER JOIN $wpdb->term_taxonomy AS x ON (r.term_taxonomy_id = x.term_taxonomy_id) ";
		$join .= " AND (x.taxonomy = '".APP_TAX_TAG."' OR x.taxonomy = '".COLABS_TAX_CAT."' OR 1=1) ";


		// if an ad category is selected, limit results to that cat only
		$catid = get_query_var('scat');

		if ( !empty($catid) ) :

			// put the catid into an array
			(array) $include_cats[] = $catid;

			// get all sub cats of catid and put them into the array
			$descendants = get_term_children( (int) $catid, COLABS_TAX_CAT );

			foreach ( $descendants as $key => $value )
				$include_cats[] = $value;

			// take catids out of the array and separate with commas
			$include_cats = "'" . implode( "', '", $include_cats ) . "'";

			// add the category filter to show anything within this cat or it's children
			$join .= " AND x.term_id IN ($include_cats) ";

		endif; // end category filter


		$join .= " INNER JOIN $wpdb->postmeta AS m ON ($wpdb->posts.ID = m.post_id) ";
		$join .= " INNER JOIN $wpdb->terms AS t ON x.term_id = t.term_id ";

    }

    return $join;
}

// search on custom fields
function custom_search_where($where) {
    global $wpdb, $wp_query;
    $old_where = $where; // intercept the old where statement
    if ( is_search() && isset($_GET['s']) ) {

        $query = '';

		$var_q = stripslashes($_GET['s']);
		//empty the s parameter if set to default search text
		if ( __('What are you looking for?','colabsthemes') == $var_q ) {
			$var_q = '';
		}

        if ( isset($_GET['sentence']) || $var_q == '' ) {
            $search_terms = array($var_q);
        }
        else {
            preg_match_all('/".*?("|$)|((?<=[\\s",+])|^)[^\\s",+]+/', $var_q, $matches);
            $search_terms = array_map(create_function('$a', 'return trim($a, "\\"\'\\n\\r ");'), $matches[0]);
        }

        if (!isset($_GET['exact']) ) $_GET['exact'] = '';

        $n = ( $_GET['exact'] ) ? '' : '%';

        $searchand = '';

        foreach ( (array)$search_terms as $term ) {
            $term = addslashes_gpc($term);

            $query .= "{$searchand}(";
            $query .= "($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
            $query .= " OR ($wpdb->posts.post_content LIKE '{$n}{$term}{$n}')";
            $query .= " OR ((t.name LIKE '{$n}{$term}{$n}')) OR ((t.slug LIKE '{$n}{$term}{$n}'))";

            if(isset($customs)){
              foreach ( $customs as $custom ) {
                $query .= " OR (";
                $query .= "(m.meta_key = '$custom')";
                $query .= " AND (m.meta_value  LIKE '{$n}{$term}{$n}')";
                $query .= ")";
              }
            }

            $query .= ")";
            $searchand = ' AND ';
        }

        $term = $wpdb->escape($var_q);

        if ( !isset($_GET['sentence']) && count($search_terms) > 1 && $search_terms[0] != $var_q ) {
            $query .= " OR ($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
            $query .= " OR ($wpdb->posts.post_content LIKE '{$n}{$term}{$n}')";
        }

        if ( !empty($query) ) {

            $where = " AND ({$query}) AND ($wpdb->posts.post_status = 'publish') ";

            // setup the array for post types
            $post_type_array = array();

            // always include the ads post type
            $post_type_array[] = 'ad';

            // check to see if we include blog posts
            if (get_option('colabs_search_ex_blog') == 'no')
                $post_type_array[] = 'post';

            // check to see if we include pages
            if (get_option('colabs_search_ex_pages') == 'no')
                $post_type_array[] = 'page';

            // build the post type filter sql from the array values
            $post_type_filter = "'" . implode("','",$post_type_array). "'";

            // return the post type sql to complete the where clause
            $where .= " AND ($wpdb->posts.post_type IN ($post_type_filter)) ";

        }
    }

    return( $where );
}
if(!is_admin()) {
	add_filter('posts_join', 'custom_search_join');
	add_filter('posts_where', 'custom_search_where');
	add_filter('posts_groupby', 'custom_search_groupby');
}
function colabs_dropdown_location( $args = '' ) {
	$defaults = array(
		'show_option_all' => '', 'show_option_none' => '',
		'orderby' => 'id', 'order' => 'ASC',
		'show_count' => 0,
		'hide_empty' => 1, 'child_of' => 0,
		'exclude' => '', 'echo' => 1,
		'selected' => 0, 'hierarchical' => 1,
		'name' => 'cat', 'id' => '',
		'class' => 'postform', 'depth' => 0,
		'tab_index' => 0, 'taxonomy' => 'category',
		'hide_if_empty' => false
	);

	$defaults['selected'] = ( is_category() ) ? get_query_var( 'cat' ) : 0;

	// Back compat.
	if ( isset( $args['type'] ) && 'link' == $args['type'] ) {
		_deprecated_argument( __FUNCTION__, '3.0', '' );
		$args['taxonomy'] = 'link_category';
	}

	$r = wp_parse_args( $args, $defaults );

	if ( !isset( $r['pad_counts'] ) && $r['show_count'] && $r['hierarchical'] ) {
		$r['pad_counts'] = true;
	}

	extract( $r );

	$tab_index_attribute = '';
	if ( (int) $tab_index > 0 )
		$tab_index_attribute = " tabindex=\"$tab_index\"";

	$categories = get_terms( $taxonomy, $r );
	$name = esc_attr( $name );
	$class = esc_attr( $class );
	$id = $id ? esc_attr( $id ) : $name;

	if ( ! $r['hide_if_empty'] || ! empty($categories) )
		$output = "<select name='$name' id='$id' class='$class' $tab_index_attribute>\n";
	else
		$output = '';

	if ( empty($categories) && ! $r['hide_if_empty'] && !empty($show_option_none) ) {
		$show_option_none = apply_filters( 'list_cats', $show_option_none );
		$output .= "\t<option value='-1' selected='selected'>$show_option_none</option>\n";
	}

	if ( ! empty( $categories ) ) {

		if ( $show_option_all ) {
			$show_option_all = apply_filters( 'list_cats', $show_option_all );
			$selected = ( '0' === strval($r['selected']) ) ? " selected='selected'" : '';
			$output .= "\t<option value='0'$selected>$show_option_all</option>\n";
		}

		if ( $show_option_none ) {
			$show_option_none = apply_filters( 'list_cats', $show_option_none );
			$selected = ( '-1' === strval($r['selected']) ) ? " selected='selected'" : '';
			$output .= "\t<option value='-1'$selected>$show_option_none</option>\n";
		}

		if ( $hierarchical )
			$depth = $r['depth'];  // Walk the full depth.
		else
			$depth = -1; // Flat.

		$output .= walk_location_dropdown_tree( $categories, $depth, $r );
	}

	if ( ! $r['hide_if_empty'] || ! empty($categories) )
		$output .= "</select>\n";

	$output = apply_filters( 'wp_dropdown_cats', $output );

	if ( $echo )
		echo $output;

	return $output;
}

function walk_location_dropdown_tree() {
	$args = func_get_args();
	// the user's options are the third parameter
	if ( empty($args[2]['walker']) || !is_a($args[2]['walker'], 'Walker') )
		$walker = new Walker_LocationDropdown;
	else
		$walker = $args[2]['walker'];

	return call_user_func_array(array( &$walker, 'walk' ), $args );
}
class Walker_LocationDropdown extends Walker {
	/**
	 * @see Walker::$tree_type
	 * @since 2.1.0
	 * @var string
	 */
	var $tree_type = 'category';

	/**
	 * @see Walker::$db_fields
	 * @since 2.1.0
	 * @todo Decouple this
	 * @var array
	 */
	var $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

	/**
	 * @see Walker::start_el()
	 * @since 2.1.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $category Category data object.
	 * @param int $depth Depth of category. Used for padding.
	 * @param array $args Uses 'selected' and 'show_count' keys, if they exist.
	 */
	function start_el( &$output, $category, $depth, $args, $id = 0 ) {
		$pad = str_repeat('&nbsp;', $depth * 3);

		$cat_name = apply_filters('list_cats', $category->name, $category);
		$output .= "\t<option class=\"level-$depth\" value=\"".$category->slug."\"";
		if ( $category->term_id == $args['selected'] )
			$output .= ' selected="selected"';
		$output .= '>';
		$output .= $pad.$cat_name;
		if ( $args['show_count'] )
			$output .= '&nbsp;&nbsp;('. $category->count .')';
		$output .= "</option>\n";
	}
}
?>