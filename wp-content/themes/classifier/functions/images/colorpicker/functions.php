<?php
/*-----------------------------------------------------------------------------------*/
/* Start ColorLabs Functions - Please refrain from editing this section */
/*-----------------------------------------------------------------------------------*/
error_reporting(0);

// Set path to ColorLabs Framework and theme specific functions
$functions_path = TEMPLATEPATH . '/functions/';
$includes_path = TEMPLATEPATH . '/includes/';

// ColorLabs Admin
require_once ($functions_path . 'admin-init.php');			// Admin Init

// Theme specific functionality
require_once ($includes_path . 'theme-functions.php'); 		// Custom theme functions
require_once ($includes_path . 'theme-options.php'); 		// Custom theme options
require_once ($includes_path . 'theme-plugins.php');		// Theme specific plugins integrated in a theme
require_once ($includes_path . 'theme-actions.php');		// Theme actions & user defined hooks
require_once ($includes_path . 'theme-comments.php'); 		// Custom comments/pingback loop
require_once ($includes_path . 'theme-js.php');				// Load javascript in wp_head
require_once ($includes_path . 'theme-sidebar-init.php');   // Initialize widgetized areas
require_once ($includes_path . 'theme-widgets.php');		// Theme widgets
require_once ($includes_path . 'theme-custom-type.php');	// Theme custom post type

/*-----------------------------------------------------------------------------------*/
/* Custom */
/*-----------------------------------------------------------------------------------*/
if ( ! isset( $content_width ) )
	$content_width = 600;

?>