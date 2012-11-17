<?php
/*-----------------------------------------------------------------------------------
TABLE OF CONTENTS
- Show Options Panel after activate
- Admin Backend
	- Setup Custom Navigation
- Output HEAD - colabsthemes_wp_head()
	- Output alternative stylesheet
	- Output custom favicon
	- Load textdomains
	- Output CSS from standarized styling options
	- Output shortcodes stylesheet
	- Output custom.css
- Post Images from WP2.9+ integration
- Enqueue comment reply script
-----------------------------------------------------------------------------------*/
define( 'THEME_FRAMEWORK','colabsthemes' );
/*-----------------------------------------------------------------------------------*/
/* Add default options and show Options Panel after activate  */
/*-----------------------------------------------------------------------------------*/
if (is_admin() && isset($_GET['activated'] ) && $pagenow == "themes.php" ) {
	//Call action that sets
	add_action( 'admin_head','colabs_option_setup' );
	//Do redirect
	header( 'Location: '.admin_url().'admin.php?page=colabsthemes' ) ;
}
if (!function_exists( 'colabs_option_setup')) {
	function colabs_option_setup(){
		//Update EMPTY options
		$colabs_array = array();
		add_option( 'colabs_options',$colabs_array);
		$template = get_option( 'colabs_template' );
		$saved_options = get_option( 'colabs_options' );
		foreach ($template as $option) {
			if ($option['type'] != 'heading'){
				$id = $option['id'];
				$std = $option['std'];
				$db_option = get_option($id);
				if (empty($db_option)){
					if (is_array($option['type'])) {
						foreach ($option['type'] as $child){
							$c_id = $child['id'];
							$c_std = $child['std'];
							$db_option = get_option($c_id);
							if (!empty($db_option)){
								update_option($c_id,$db_option);
								$colabs_array[$id] = $db_option;
							} else {
								$colabs_array[$c_id] = $c_std;
							}
						}
					} else {
						update_option($id,$std);
						$colabs_array[$id] = $std;
					}
				} else { //So just store the old values over again.
					$colabs_array[$id] = $db_option;
				}
			}
		}
		update_option( 'colabs_options',$colabs_array);
	}
}
/*-----------------------------------------------------------------------------------*/
/* Admin Backend */
/*-----------------------------------------------------------------------------------*/
/*if (!function_exists( 'colabsthemes_admin_head')) {
	function colabsthemes_admin_head() {
	    //Setup Custom Navigation Menu
		if (function_exists( 'colabs_custom_navigation_setup')) {
			colabs_custom_navigation_setup();
		}
	}
}
add_action( 'admin_head', 'colabsthemes_admin_head' );
*/
/*-----------------------------------------------------------------------------------*/
/* Output HEAD - colabsthemes_wp_head */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'colabsthemes_wp_head')) {
	function colabsthemes_wp_head() {
		// Output alternative stylesheet
		//if ( function_exists( 'colabs_output_alt_stylesheet' ) )
			//colabs_output_alt_stylesheet();
		// Output custom favicon
		if ( function_exists( 'colabs_output_custom_favicon' ) )
			colabs_output_custom_favicon();
		// Load textdomains
		if ( function_exists( 'colabs_load_textdomain' ) )
			colabs_load_textdomain();
		// Output CSS from standarized styling options
		//if ( function_exists( 'colabs_head_css' ) )
		//	colabs_head_css();
		// Output shortcodes stylesheet
		if ( function_exists( 'colabs_shortcode_stylesheet' ) )
			colabs_shortcode_stylesheet();
		// Output custom.css
		//if ( function_exists( 'colabs_output_custom_css' ) )
		//	colabs_output_custom_css();
	}
}
add_action( 'wp_head', 'colabsthemes_wp_head' );
/*-----------------------------------------------------------------------------------*/
/* Output alternative stylesheet - colabs_output_alt_stylesheet */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'colabs_output_alt_stylesheet')) {
	function colabs_output_alt_stylesheet() {
		$style = '';
		if ( isset( $_REQUEST['style'] ) ) {
			// Sanitize requested value.
			$requested_style = strtolower( strip_tags( trim( $_REQUEST['style'] ) ) );
			$style = $requested_style;
		}
		echo "<!-- Alt Stylesheet -->\n";
		if ($style != '') {
			echo '<link href="'. get_bloginfo( 'template_directory') .'/styles/'. $style . '.css" rel="stylesheet" type="text/css" />'."\n\n";
		} else {
			$style = get_option( 'colabs_alt_stylesheet' );
			if( $style != '' ) {
				// Sanitize value.
				$style = strtolower( strip_tags( trim( $style ) ) );
				echo '<link href="'. get_bloginfo( 'template_directory') .'/styles/'. $style .'" rel="stylesheet" type="text/css" />'."\n\n";
			} else {
				echo '<link href="'. get_bloginfo( 'template_directory') .'/styles/default.css" rel="stylesheet" type="text/css" />'."\n\n";
			}
		}
	}
}
/*-----------------------------------------------------------------------------------*/
/* Output favicon link - colabs_custom_favicon() */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'colabs_output_custom_favicon')) {
	function colabs_output_custom_favicon() {
		// Favicon
		if(get_option( 'colabs_custom_favicon') != '') {
			echo "<!-- Custom Favicon -->\n";
	        echo '<link rel="shortcut icon" href="'.  get_option( 'colabs_custom_favicon')  .'"/>'."\n\n";
	    }
	}
}
/*-----------------------------------------------------------------------------------*/
/* Load textdomain - colabs_load_textdomain() */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'colabs_load_textdomain')) {
	function colabs_load_textdomain() {
		load_theme_textdomain( 'colabsthemes' );
		load_theme_textdomain( 'colabsthemes', get_template_directory() . '/lang' );
		if ( function_exists( 'load_child_theme_textdomain') )
			load_child_theme_textdomain( 'colabsthemes' );
	}
}
/*-----------------------------------------------------------------------------------*/
/* Output CSS from standarized options */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'colabs_head_css')) {
	function colabs_head_css() {
		$output = '';
		$text_title = get_option( 'colabs_texttitle' );
	    $custom_css = get_option( 'colabs_custom_css' );
		$template = get_option( 'colabs_template' );
		if (is_array($template)) {
			foreach($template as $option){
				if(isset($option['id'])){
					if($option['id'] == 'colabs_texttitle') {
						// Add CSS to output
						if ($text_title == "true") {
							$output .= '#logo img { display:none; } #logo .site-title, #logo .site-description { display:block; }' . "\n";
						}
					}
				}
			}
		}
		if ($custom_css <> '') {
			$output .= $custom_css . "\n";
		}
		// Output styles
		if ($output <> '') {
			$output = strip_tags($output);
			echo "<!-- Options Panel Custom CSS -->\n";
			$output = "<style type=\"text/css\">\n" . $output . "</style>\n\n";
			echo $output;
		}
	}
}
/*-----------------------------------------------------------------------------------*/
/* Output custom.css - colabs_custom_css() */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'colabs_output_custom_css')) {
	function colabs_output_custom_css() {
		// Custom.css insert
		echo "<!-- Custom Stylesheet -->\n";
		echo '<link href="'. get_bloginfo( 'template_directory') .'/custom.css" rel="stylesheet" type="text/css" />'."\n";
	}
}
/*-----------------------------------------------------------------------------------*/
/* Post Images from WP2.9+ integration /*
/*-----------------------------------------------------------------------------------*/
if(function_exists( 'add_theme_support')){
	if(get_option( 'colabs_post_image_support') == 'true'){
		add_theme_support( 'post-thumbnails' );
		// set height, width and crop if dynamic resize functionality isn't enabled
		if ( get_option( 'colabs_pis_resize') <> "true" ) {
			$thumb_width = get_option( 'colabs_thumb_w' );
			$thumb_height = get_option( 'colabs_thumb_h' );
			$single_width = get_option( 'colabs_single_w' );
			$single_height = get_option( 'colabs_single_h' );
			$hard_crop = get_option( 'colabs_pis_hard_crop' );
			if($hard_crop == 'true') {$hard_crop = true; } else { $hard_crop = false;}
			set_post_thumbnail_size($thumb_width,$thumb_height, $hard_crop); // Normal post thumbnails
			add_image_size( 'single-post-thumbnail', $single_width, $single_height, $hard_crop );
		}
	}
}
/*-----------------------------------------------------------------------------------*/
/* Enqueue comment reply script */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'colabs_comment_reply')) {
	function colabs_comment_reply() {
		if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'get_header', 'colabs_comment_reply' );
?>