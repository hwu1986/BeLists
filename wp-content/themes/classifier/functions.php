<?php
/*-----------------------------------------------------------------------------------*/
/* Start ColorLabs Functions - Please refrain from editing this section */
/*-----------------------------------------------------------------------------------*/

if (!defined('COLABS_POST_TYPE'))
    define('COLABS_POST_TYPE', 'ad');

if (!defined('COLABS_TAX_CAT'))
    define('COLABS_TAX_CAT', 'ad_cat');

if (!defined('COLABS_TAX_TAG'))
    define('COLABS_TAX_TAG', 'ad_tag');

if (!defined('COLABS_TAX_LOC'))
    define('COLABS_TAX_LOC', 'ad_location');

error_reporting(0);

// Set path to ColorLabs Framework and theme specific functions
$functions_path = TEMPLATEPATH . '/functions/';
$includes_path = TEMPLATEPATH . '/includes/';

// ColorLabs Admin
require_once ($functions_path . 'admin-init.php');			// Admin Init

// Theme specific functionality
require_once ($includes_path . 'theme-functions.php'); 		// Custom theme functions
require_once ($includes_path . 'theme-functions-cl.php'); 	// Custom classifier functions
require_once ($includes_path . 'colabsthemes-functions.php');   // Colabs Custom Functions
require_once ($includes_path . 'theme-options.php'); 		// Custom theme options
require_once ($includes_path . 'theme-plugins.php');		// Theme specific plugins integrated in a theme
require_once ($includes_path . 'theme-actions.php');		// Theme actions & user defined hooks
require_once ($includes_path . 'theme-comments.php'); 		// Custom comments/pingback loop
require_once ($includes_path . 'theme-js.php');				// Load javascript in wp_head
require_once ($includes_path . 'theme-sidebar-init.php');   // Initialize widgetized areas
require_once ($includes_path . 'theme-widgets.php');		// Theme widgets
require_once ($includes_path . 'theme-custom-type.php');	// Theme custom post type
require_once ($includes_path . 'theme-hooks.php');	        // Theme custom hooks
require_once ($includes_path . 'theme-meta.php');           // Theme custom meta
require_once ($includes_path . 'theme-header.php');         // Theme custom header
require_once ($includes_path . 'theme-footer.php');         // Theme custom footer
require_once ($includes_path . 'theme-emails.php');         // Theme Emails
require_once ($includes_path . 'theme-stats.php');          // Theme Stats
require_once ($includes_path . 'theme-profile.php');        // Theme Profile
require_once ($includes_path . 'theme-membership-packages.php');    
require_once ($includes_path . 'theme-gateways.php');   
require_once ($includes_path . 'theme-transactions.php'); 
require_once ($includes_path . 'theme-coupon.php');

// front-end includes
if ( !is_admin() ) :
    include_once($includes_path . 'theme-login.php');
    include_once($includes_path . 'forms/login/login-form.php');
    include_once($includes_path . 'forms/login/login-process.php');
    include_once($includes_path . 'forms/register/register-form.php');
    include_once($includes_path . 'forms/register/register-process.php');
    include_once($includes_path . 'forms/forgot-password/forgot-password-form.php');
endif;

// admin-only functions
if ( is_admin() ) :
	include_once($includes_path . 'theme-install.php'); // needs to be above admin-options otherwise install/upgrade script doesn't work correctly
endif;

/*
if (file_exists($includes_path . 'gateways/paypal/ipn.php'))
    require_once (TEMPLATEPATH.'gateways/paypal/ipn.php');
*/
/*-----------------------------------------------------------------------------------*/
/* Custom */
/*-----------------------------------------------------------------------------------*/
if ( ! isset( $content_width ) )
	$content_width = 600;

?>