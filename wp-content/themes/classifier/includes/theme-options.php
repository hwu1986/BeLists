<?php

//Enable CoLabsSEO on these custom Post types
//$seo_post_types = array('post','page');
//define("SEOPOSTTYPES", serialize($seo_post_types));

//Global options setup
add_action('init','colabs_global_options');
function colabs_global_options(){
	// Populate CoLabsThemes option in array for use in theme
	global $colabs_options;
	$colabs_options = get_option('colabs_options');
}

add_action('admin_head','colabs_options');  
if (!function_exists('colabs_options')) {
function colabs_options(){
	
// VARIABLES
$themename = "Classifier";
$manualurl = 'http://colorlabsproject.com';
$shortname = "colabs";

//Access the WordPress Categories via an Array
$colabs_categories = array();  
$colabs_categories_obj = get_categories('hide_empty=0');
foreach ($colabs_categories_obj as $colabs_cat) {
    $colabs_categories[$colabs_cat->cat_ID] = $colabs_cat->cat_name;}
//$categories_tmp = array_unshift($colabs_categories, "Select a category:");

//Access the WordPress Pages via an Array
$colabs_pages = array();
$colabs_pages_obj = get_pages('sort_column=post_parent,menu_order');    
foreach ($colabs_pages_obj as $colabs_page) {
    $colabs_pages[$colabs_page->ID] = $colabs_page->post_name; }
//$colabs_pages_tmp = array_unshift($colabs_pages, "Select a page:");

//Stylesheets Reader
$alt_stylesheet_path = TEMPLATEPATH . '/styles/';
$alt_stylesheets = array();
if ( is_dir($alt_stylesheet_path) ) {
    if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) {
        while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
            if(stristr($alt_stylesheet_file, ".css") !== false) {
                $alt_stylesheets[] = $alt_stylesheet_file;
            }
        }
    }
}

//Access the Category Event via an Array
$colabs_cat_ad = array();  
$colabs_cat_ad_obj = get_categories('hide_empty=0&taxonomy=ad_category');
foreach ($colabs_cat_ad_obj as $colabs_cat_ads) {
    $colabs_cat_ad[$colabs_cat_ads->cat_ID] = $colabs_cat_ads->cat_name;}


$images_dir =  get_template_directory_uri() . '/functions/images/';	
//More Options
$other_entries = array("Select a number:","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20");

$other_entries_10 = array("Select a number:","1","2","3","4","5","6","7","8","9","10");

$other_entries_4 = array("Select a number:","1","2","3","4");

// THIS IS THE DIFFERENT FIELDS
$options = array();

// General Settings
$options[] = array( "name" => __("General Settings", "colabsthemes" ),
					"type" => "heading",
					"icon" => "general");

$options[] = array( "name" => __( "Use for blog title/logo", "colabsthemes" ),
					"desc" => __( "Select title or logo for your blog.", "colabsthemes" ),
					"id" => $shortname."_logotitle",
					"std" => "logo",
					"type" => "select2",
					"options" => array( "logo" => __( "Logo", "colabsthemes" ), "title" => __( "Title", "colabsthemes" ) ) ); 					

$options[] = array( "name" => __("Custom Logo", "colabsthemes" ),
					"desc" => __("Upload a logo for your theme, or specify an image URL directly. Best image size in 260x60 px", "colabsthemes" ),
					"id" => $shortname."_logo",
					"std" => trailingslashit( get_bloginfo('template_url') ) . "images/logo.png",
					"type" => "upload");

$options[] = array( "name" => __("Custom Favicon", "colabsthemes" ),
					"desc" => __("Upload a 16x16px ico image that will represent your website's favicon. Favicon/bookmark icon will be shown at the left of your blog's address in visitor's internet browsers.", "colabsthemes" ),
					"id" => $shortname."_custom_favicon",
					"std" => trailingslashit( get_bloginfo('template_url') ) . "images/favicon.png",
					"type" => "upload"); 
					

$options[] = array( "name" => __("Post Submit Status", "colabsthemes" ),
					"desc" => __("You can assign which status to be submit a post. ", "colabsthemes" ),
					"id" => $shortname."_post_status",
                    "std" => "pending",
					"type" => "select2",
					"options" => array('pending' =>'Moderate','publish'=>'Publish') );
					
$options[] = array( "name" => __("Cache Expires", "colabsthemes" ),
					"desc" => __("To speed up page loading on your site, Classifier uses a caching mechanism on certain features (i.e. category drop-down, home page). The cache automatically gets flushed whenever a category has been added/modified, however this value sets the frequency your cache is regularly emptied. We recommend keeping this at the default (every hour = 3600 seconds). This number is in seconds so one day equals 86400 seconds (60 seconds * 60 minutes * 24 hours). Do not enter any commas.", "colabsthemes" ),
					"id" => $shortname."_cache_expires",
					"std" => "3600",
					"type" => "text");					

$options[] = array( "name" => __("Post Content", "colabsthemes" ),
					"desc" => __("Select if you want to show the full content or the excerpt on posts. ", "colabsthemes" ),
					"id" => $shortname."_post_content",
					"type" => "select2",
					"options" => array("excerpt" => "The Excerpt", "content" => "Full Content" ) );

$options[] = array( "name" => __( 'General Layout', 'colabsthemes' ),
                    "desc" => __( 'Select main content and sidebar alignment. Choose between left or right sidebar layout.', 'colabsthemes' ),
                    "id" => $shortname . "_layout", //colabs_layout
                    "std" => "two-col-left",
                    "type" => "images",
                    "options" => array(                           
                                'two-col-left' => $images_dir . '2cl.png',
                                'two-col-right' => $images_dir . '2cr.png')
                    );
					
$options[] = array( "name" => "Disable Responsive",
          "desc" => "You can disable responsive module for your site.",
          "id" => $shortname."_disable_mobile",
          "std" => "false",
          "type" => "checkbox");
                    
// Ads Settings
$options[] = array( "name" => __("Ads Settings", "colabsthemes" ),
					"type" => "heading",
					"icon" => "home");

$options[] = array( "name" => __("Allow Ad Editing", "colabsthemes" ),
					"desc" => __("Allows the ad owner to edit and republish their existing ads from their dashboard.", "colabsthemes" ),
					"id" => $shortname."_ad_edit",
					"std" => "true",
					"type" => "checkbox");

$options[] = array( "name" => __("Show Ad Views Counter", "colabsthemes" ),
					"desc" => __("This will show a 'total views' and 'today's views' at the bottom of each ad listing and blog post.", "colabsthemes" ),
					"id" => $shortname."_ad_stats_all",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => __("Ad Listing Period", "colabsthemes" ),
					"desc" => __("Number of days each ad will be listed on your site. This option is overridden by ad packs if you are charging for ads and using the Fixed Price Per Ad option. ", "colabsthemes" ),
					"id" => $shortname."_prun_period",
					"std" => "30",
					"type" => "text");

$options[] = array( "name" => __("New Ad Status", "colabsthemes" ),
					"desc" => __("Note: If you have the 'Charge for Listing Ads' option set to 'Yes', then each ad will automatically be set to Pending Review until payment is made (regardless of this options value.). <i>Pending Review</i> - You have to manually approve and publish each ad. <br /><i>Published</i> - Ad goes live immediately without any approvals unless it has not been paid for.", "colabsthemes" ),
					"id" => $shortname."_post_status",
					"type" => "select2",
					"options" => array( 'pending' => __('Pending Review', 'colabsthemes'),
                                        'publish' => __('Published', 'colabsthemes') ) 
                    );

$options[] = array( "name" => __("Allow Parent Category Posting", "colabsthemes" ),
					"desc" => __("Allows ad poster to post to top-level categories. If set to &quot;When Empty&quot;, it allows posting to top-level categories only if they have no child categories.", "colabsthemes" ),
					"id" => $shortname."_ad_parent_posting",
					"type" => "select2",
					"options" => array( 'true' => __('Yes', 'colabsthemes'),
                                        'whenEmpty' => __('When Empty', 'colabsthemes'),
                                        'false'  => __('No', 'colabsthemes')) );

$options[] = array( "name" => __("Allow Ad Images", "colabsthemes" ),
					"desc" => __("Allows the ad owner to upload and use images on their ad. Note: This will disable display of most ad images across the entire site but some images may still display.", "colabsthemes" ),
					"id" => $shortname."_ad_images",
					"std" => "true",
					"type" => "checkbox");
                    
$options[] = array( "name" => __("Max Images Per Ad", "colabsthemes" ),
					"desc" => __("The number of images the ad owner can upload with each of their ads.", "colabsthemes" ),
					"id" => $shortname."_num_images",
                    "std" => "4",
					"type" => "select2",
					"options" => $other_entries_10 );

$options[] = array( "name" => __("Max Size Per Image", "colabsthemes" ),
					"desc" => __("The maximum image size (per image) the ad owner can upload with each of their ads.", "colabsthemes" ),
					"id" => $shortname."_max_image_size",
                    "std" => "",
					"type" => "select2",
					"options" => array( '100'  => __('100 KB', 'colabsthemes'),
                                            '250'  => __('250 KB', 'colabsthemes'),
                                            '500'  => __('500KB', 'colabsthemes'),
                                            '1024' => __('1MB', 'colabsthemes'),
                                            '2048' => __('2MB', 'colabsthemes'),
                                            '5120' => __('5MB', 'colabsthemes')) );

// Global Path Variables
$options[] = array( "name" => __("Global Path Settings", "colabsthemes" ),
					"type" => "heading",
					"icon" => "home");

$options[] = array( "name" => __("Dashboard Path", "colabsthemes" ),
					"desc" => sprintf( __("This is the URL path to your customers dashboard page. Do not change unless you know what you are doing. IMPORTANT: You must also change the corresponding <a target='_blank' href='%s'>page template slug</a> for this to work.",'colabsthemes'), 'edit.php?post_type=page' ),
					"id" => $shortname."_dashboard_url",
					"std" => "dashboard",
					"type" => "text");

$options[] = array( "name" => __("User Profile Path", "colabsthemes" ),
					"desc" => sprintf( __("This is the URL path to your customers user profile page. Do not change unless you know what you are doing. IMPORTANT: You must also change the corresponding <a target='_blank' href='%s'>page template slug</a> for this to work.",'colabsthemes'), 'edit.php?post_type=page' ),
					"id" => $shortname."_profile_url",
					"std" => "profile",
					"type" => "text");

$options[] = array( "name" => __("Edit Ad Path", "colabsthemes" ),
					"desc" => sprintf( __("This is the URL path to your customers edit ad page. Do not change unless you know what you are doing. IMPORTANT: You must also change the corresponding <a target='_blank' href='%s'>page template slug</a> for this to work.",'colabsthemes'), 'edit.php?post_type=page' ),
					"id" => $shortname."_edit_item_url",
					"std" => "edit-item",
					"type" => "text");

$options[] = array( "name" => __("Add New Path", "colabsthemes" ),
					"desc" => sprintf( __("This is the URL path to your add new ad listing page. Do not change unless you know what you are doing. IMPORTANT: You must also change the corresponding <a target='_blank' href='%s'>page template slug</a> for this to work.",'colabsthemes'), 'edit.php?post_type=page' ),
					"id" => $shortname."_add_new_url",
					"std" => "add-new",
					"type" => "text");

$options[] = array( "name" => __("Add New Confirm Path", "colabsthemes" ),
					"desc" => sprintf( __("This is the URL path to your add new ad listing confirmation page. Do not change unless you know what you are doing. IMPORTANT: You must also change the corresponding <a target='_blank' href='%s'>page template slug</a> for this to work.",'colabsthemes'), 'edit.php?post_type=page' ),
					"id" => $shortname."_add_new_confirm_url",
					"std" => "add-new-confirm",
					"type" => "text");

$options[] = array( "name" => __("Add New Membership Path", "colabsthemes" ),
					"desc" => sprintf( __("This is the URL path to your add new membership page. Do not change unless you know what you are doing. IMPORTANT: You must also change the corresponding <a target='_blank' href='%s'>page template slug</a> for this to work.",'colabsthemes'), 'edit.php?post_type=page' ),
					"id" => $shortname."_membership_purchase_url",
					"std" => "membership",
					"type" => "text");

$options[] = array( "name" => __("Add New Membership Confirm Path", "colabsthemes" ),
					"desc" => sprintf( __("This is the URL path to your add new membership confirmation page. Do not change unless you know what you are doing. IMPORTANT: You must also change the corresponding <a target='_blank' href='%s'>page template slug</a> for this to work.",'colabsthemes'), 'edit.php?post_type=page' ),
					"id" => $shortname."_membership_purchase_confirm_url",
					"std" => "membership-confirm",
					"type" => "text");

// Search Options
$options[] = array( "name" => __("Search Settings", "colabsthemes" ),
					"type" => "heading",
					"icon" => "home");

$options[] = array( "name" => __("Enable Listings Options by Location", "colabsthemes" ),
					"desc" => __("Add options on Header Bar to filter listings based on location (Ad Location).", "colabsthemes" ),
					"id" => $shortname."_ad_location_filter",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => __("Exclude Pages", "colabsthemes" ),
					"desc" => __("Set this option to yes if you do not want your pages to show up in your website search results.", "colabsthemes" ),
					"id" => $shortname."_search_ex_pages",
					"std" => "true",
					"type" => "checkbox");

$options[] = array( "name" => __("Exclude Blog Posts", "colabsthemes" ),
					"desc" => __("Set this option to yes if you do not want your blog posts to show up in your website search results.", "colabsthemes" ),
					"id" => $shortname."_search_ex_blog",
					"std" => "true",
					"type" => "checkbox");

// Pricing configuration
$options[] = array( "name" => __("Pricing Configuration", "colabsthemes" ),
					"type" => "heading",
					"icon" => "home");
                    
$options[] = array( "name" => __("Display Zero for Empty Prices", "colabsthemes" ),
					"desc" => __("This will force any ad without a price to display a currency of zero for the price.", "colabsthemes" ),
					"id" => $shortname."_force_zeroprice",
					"std" => "true",
					"type" => "checkbox");
					
$options[] = array( "name" => __("Currency Symbol", "colabsthemes" ),
					"desc" => __("Enter the currency symbol you want to appear next to prices on your classified ads (i.e. $, &euro;, &pound;, &yen;)", "colabsthemes" ),
					"id" => $shortname."_curr_symbol",
					"std" => "$",
					"type" => "text");
                    
$options[] = array( "name" => __( "Symbol Position", "colabsthemes" ),
					"desc" => __( "Some currencies place the symbol on the right side vs the left. Select how you would like your currency symbol to be displayed.", "colabsthemes" ),
					"id" => $shortname."_curr_symbol_pos",
					"std" => "left",
					"type" => "select2",
					"options" => array( 'left'         => __('Left of Currency ($100)', 'colabsthemes'),
                                        'left_space'   => __('Left of Currency with Space ($ 100)', 'colabsthemes'),
                                        'right'        => __('Right of Currency (100$)', 'colabsthemes'),
                                        'right_space'  => __('Right of Currency with Space (100 $)', 'colabsthemes')) ); 					

$options[] = array( "name" => __( "Collect Payments in", "colabsthemes" ),
					"desc" => sprintf( __("This is the currency you want to collect payments in. It applies mainly to PayPal payments since other payment gateways accept more currencies. If your currency is not listed then PayPal currently does not support it. See the list of supported <a target='_new' href='%s'>PayPal currencies</a>.", 'colabsthemes'), 'https://www.paypal.com/cgi-bin/webscr?cmd=p/sell/mc/mc_intro-outside' ),
					"id" => $shortname."_curr_pay_type",
					"std" => "USD",
					"type" => "select2",
					"options" => array( 'USD' => __('US Dollars (&#36;)', 'colabsthemes'),
                                            'EUR' => __('Euros (&euro;)', 'colabsthemes'),
                                            'GBP' => __('Pounds Sterling (&pound;)', 'colabsthemes'),
                                            'AUD' => __('Australian Dollars (&#36;)', 'colabsthemes'),
                                            'BRL' => __('Brazilian Real (&#36;)', 'colabsthemes'),
                                            'CAD' => __('Canadian Dollars (&#36;)', 'colabsthemes'),
                                            'CZK' => __('Czech Koruna (K&#269;)', 'colabsthemes'),
                                            'DKK' => __('Danish Krone (kr)', 'colabsthemes'),
                                            'HKD' => __('Hong Kong Dollar (&#36;)', 'colabsthemes'),
                                            'HUF' => __('Hungarian Forint (Ft)', 'colabsthemes'),
                                            'ILS' => __('Israeli Shekel (&#8362;)', 'colabsthemes'),
                                            'JPY' => __('Japanese Yen (&yen;)', 'colabsthemes'),
                                            'MYR' => __('Malaysian Ringgits (RM)', 'colabsthemes'),
                                            'MXN' => __('Mexican Peso (&#36;)', 'colabsthemes'),
                                            'NZD' => __('New Zealand Dollar (&#36;)', 'colabsthemes'),
                                            'NOK' => __('Norwegian Krone (kr)', 'colabsthemes'),
                                            'PHP' => __('Philippine Pesos (P)', 'colabsthemes'),
                                            'PLN' => __('Polish Zloty (z&#322;)', 'colabsthemes'),
                                            'SGD' => __('Singapore Dollar (&#36;)', 'colabsthemes'),
                                            'SEK' => __('Swedish Krona (kr)', 'colabsthemes'),
                                            'CHF' => __('Swiss Franc (Fr)', 'colabsthemes'),
                                            'TWD' => __('Taiwan New Dollar (&#36;)', 'colabsthemes'),
                                            'THB' => __('Thai Baht (&#3647;)', 'colabsthemes'),
                                            'YTL' => __('Turkish Lira (&#8356;)', 'colabsthemes'),
                                        ) ); 					

$options[] = array( "name" => __( "Charge for Listing Ads", "colabsthemes" ),
					"desc" => __( "This option activates the payment system so you can start charging for ad listings on your site.", "colabsthemes" ),
					"id" => $shortname."_charge_ads",
					"std" => "true",
					"type" => "checkbox" ); 					

$options[] = array( "name" => __("Featured Ad Price", "colabsthemes" ),
					"desc" => __("This is the additional amount you will charge visitors to post a featured ad on your site. A featured ad appears at the top of the category. Leave this blank if you do not want to offer featured ads. Only enter numeric values or decimal points. Do not include a currency symbol or commas.", "colabsthemes" ),
					"id" => $shortname."_sys_feat_price",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => __( "Price Model", "colabsthemes" ),
					"desc" => sprintf( __( "This option defines the pricing model for selling ads on your site. If you want to provide free and paid ads then select the &quot;Price Per Category&quot; option. If you select the 'Fixed Price Per Ad' option, you must have at least one active <a href='%s'>ad pack</a> setup.", "colabsthemes" ), 'admin.php?page=packages' ),
					"id" => $shortname."_price_scheme",
					"std" => "",
					"type" => "select2",
					"options" => array( 'single'     => __('Fixed Price Per Ad', 'colabsthemes'),
                                        'category'   => __('Price Per Category', 'colabsthemes'),
                                        'percentage' => __('% of Sellers Ad Price', 'colabsthemes'),
										'featured'   => __('Only Charge for Featured Ads', 'colabsthemes')) ); 					

$options[] = array( "name" => __("% of Sellers Ad Price", "colabsthemes" ),
					"desc" => __("If you selected the &quot;% of Sellers Ad Price&quot; price model, enter your percentage here. Numbers only. No percentage symbol or commas.", "colabsthemes" ),
					"id" => $shortname."_percent_per_ad",
					"std" => "",
					"type" => "text",
                    "visid" => "percentage" );

// FrontPage Options
$options[] = array( "name" => __("FrontPage Settings", "colabsthemes" ),
					"type" => "heading",
					"icon" => "home");

$options[] = array( "name" => __("Featured Panel Title", "colabsthemes" ),
                    "desc" => __("Include a short title for your featured panel on the home page, e.g. Featured Listings.", "colabsthemes" ),
                    "id" => $shortname."_featured_header",
                    "std" => "Featured Listings",
                    "type" => "text");

$options[] = array( "name" => __("Featured Tag", "colabsthemes" ),
                    "desc" => __("Add comma separated list for the tags that you would like to have displayed in the featured section on your homepage. For example, if you add 'tag1, tag3' here, then all properties tagged with either 'tag1' or 'tag3' will be shown in the featured area.", "colabsthemes" ),
                    "id" => $shortname."_featured_tags",
                    "std" => "",
                    "type" => "text");

$options[] = array( "name" => __("Featured Entries", "colabsthemes" ),
                    "desc" => __("Select the number of listing entries that should appear in the Featured panel.", "colabsthemes" ),
                    "id" => $shortname."_featured_entries",
                    "std" => "3",
                    "type" => "select",
                    "options" => $other_entries);

$options[] = array( "name" => __("Show More Featured Ads Slider", "colabsthemes" ),
					"desc" => __("This option turns on the home page more featured ads slider. Usually you charge extra for this space but it is not required. To manually make an ad appear here, check the &quot;stick this post to the front page&quot; box on the WordPress edit post page under &quot;Visibility&quot;.", "colabsthemes" ),
					"id" => $shortname."_enable_featured",
					"std" => "true",
					"type" => "checkbox");

// Category Options
$options[] = array( "name" => __("Category Options", "colabsthemes" ),
					"type" => "heading",
					"icon" => "home");

$options[] = array( "name" => __("Show Parent Ad Count", "colabsthemes" ),
					"desc" => __("This will show an ad total next to each top-level parent category name in the category drop-down and directory-style home page layout.", "colabsthemes" ),
					"id" => $shortname."_cat_parent_count",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => __("Show Child Ad Count", "colabsthemes" ),
					"desc" => __("This will show an ad total next to each child category name in the category drop-down and directory-style home page layout.", "colabsthemes" ),
					"id" => $shortname."_cat_child_count",
					"std" => "true",
					"type" => "checkbox");

$options[] = array( "name" => __("Category Depth (Sub Categories)", "colabsthemes" ),
					"desc" => __("This sets the number of sub-categories shown under each parent on the directory-style home page layout. ", "colabsthemes" ),
					"id" => $shortname."_dir_sub_num",
                    "std" => "5",
					"type" => "select2",
					"options" => $other_entries_10 );

$options[] = array( "name" => __("Hide Empty Child Categories", "colabsthemes" ),
					"desc" => __("This will hide any empty child categories within the category drop-down and directory-style home page layout.", "colabsthemes" ),
					"id" => $shortname."_cat_hide_empty",
					"std" => "true",
					"type" => "checkbox");

$options[] = array( "name" => __("Hide No Categories Text", "colabsthemes" ),
					"desc" => __("This will hide the (No Categories) text from appearing when a parent category has no child categories.", "colabsthemes" ),
					"id" => $shortname."_cat_strip_nocatstext",
                    "std" => "",
					"type" => "select2",
					"options" => array( '' => __('Yes', 'colabsthemes'),
                                        __('No categories') => __('No', 'colabsthemes')) );
					
$options[] = array( "name" => __("Category Order By", "colabsthemes" ),
					"desc" => __("This will display the categories in ascending order either by name or ID.", "colabsthemes" ),
					"id" => $shortname."_cat_orderby",
                    "std" => "id",
					"type" => "select2",
					"options" => array( 'id'   => __('ID', 'colabsthemes'),
                                        'name' => __('Name', 'colabsthemes')) );
                                        
$options[] = array( "name" => __("Category Depth (Top Categories)", "colabsthemes" ),
					"desc" => __("This sets the number of top-categories shown under each parent on the top-directory-style home page layout. ", "colabsthemes" ),
					"id" => $shortname."_topcat_number",
                    "std" => "5",
					"type" => "select2",
					"options" => $other_entries_10 );
					
// Taxonomy Options
$options[] = array( "name" => __("Taxonomy URLs", "colabsthemes" ),
					"type" => "heading",
					"icon" => "home");

$options[] = array( "name" => __("Ad Listing Base URL", "colabsthemes" ),
					"desc" => sprintf( __("IMPORTANT: You must <a target='_blank' href='%s'>re-save your permalinks</a> for this change to take effect. This controls the base name of your ad listing urls. The default is ads and will look like this: http://www.yoursite.com/ads/ad-title-here/. Do not include any slashes. This should only be alpha and/or numeric values. You should not change this value once you have launched your site otherwise you risk breaking urls of other sites pointing to yours, etc.",'colabsthemes'), 'options-permalink.php' ),
					"id" => $shortname."_post_type_permalink",
					"std" => "ads",
					"type" => "text");

$options[] = array( "name" => __("Ad Category Base URL", "colabsthemes" ),
					"desc" => sprintf( __("IMPORTANT: You must <a target='_blank' href='%s'>re-save your permalinks</a> for this change to take effect. This controls the base name of your ad category urls. The default is ad-category and will look like this: http://www.yoursite.com/ad-category/category-name/. Do not include any slashes. This should only be alpha and/or numeric values. You should not change this value once you have launched your site otherwise you risk breaking urls of other sites pointing to yours, etc.",'colabsthemes'), 'options-permalink.php' ),
					"id" => $shortname."_ad_cat_tax_permalink",
					"std" => "ad-category",
					"type" => "text");
                    
$options[] = array( "name" => __("Ad Tag Base URL", "colabsthemes" ),
					"desc" => sprintf( __("IMPORTANT: You must <a target='_blank' href='%s'>re-save your permalinks</a> for this change to take effect. This controls the base name of your ad tag urls. The default is ad-tag and will look like this: http://www.yoursite.com/ad-tag/tag-name/. Do not include any slashes. This should only be alpha and/or numeric values. You should not change this value once you have launched your site otherwise you risk breaking urls of other sites pointing to yours, etc.",'colabsthemes'), 'options-permalink.php' ),
					"id" => $shortname."_ad_tag_tax_permalink",
					"std" => "ad-tag",
					"type" => "text");

$options[] = array( "name" => __("Ad Location Base URL", "colabsthemes" ),
					"desc" => sprintf( __("IMPORTANT: You must <a target='_blank' href='%s'>re-save your permalinks</a> for this change to take effect. This controls the base name of your location tag urls. The default is ad-location and will look like this: http://www.yoursite.com/ad-location/location-name/. Do not include any slashes. This should only be alpha and/or numeric values. You should not change this value once you have launched your site otherwise you risk breaking urls of other sites pointing to yours, etc.",'colabsthemes'), 'options-permalink.php' ),
					"id" => $shortname."_ad_loc_tax_permalink",
					"std" => "ad-location",
					"type" => "text");

// Membership
$options[] = array( "name" => __("Membership Settings", "colabsthemes" ),
					"icon" => "general",
					"type" => "heading");

$options[] = array( "name" => __("Enable Membership Packs", "colabsthemes" ),
					"desc" => __("Enable or disable the membership system.", "colabsthemes" ),
					"id" => $shortname."_enable_membership_packs",
					"class"=> "collapsed",
					"type" => "checkbox",
                    "std" => "false");		

$options[] = array( "name" => __("Membership Buy Page", "colabsthemes" ),
					"desc" => __("Select your page for redirection of membership buy page.", "colabsthemes" ),
					"id" => $shortname."_membership_buy",
					"type" => "select2",
					"class"=> "hidden",
					"options" => $colabs_pages );

$options[] = array( "name" => __("Membership Authentification Page", "colabsthemes" ),
					"desc" => __("Select your page for redirection of membership authentification after user buy.", "colabsthemes" ),
					"id" => $shortname."_membership_auth",
					"type" => "select2",
					"class"=> "hidden last",
					"options" => $colabs_pages );					
					
// General Labels
$options[] = array( "name" => __("Labels & Messages", "colabsthemes" ),
					"icon" => "general",
					"id" => $shortname."_labels_general_heading",
					"type" => "heading");

$options[] = array( "name" => __("Archive Text : Listings", "colabsthemes" ),
                    "desc" => __("Specify the default text for the listings archive page headers.", "colabsthemes" ),
                    "id" => $shortname."_archive_listings_header",
                    "std" => "Listings Archive",
                    "type" => "text");

$options[] = array( "name" => __("Archive Text : General", "colabsthemes" ),
                    "desc" => __("Specify the default text for the general archive page headers.", "colabsthemes" ),
                    "id" => $shortname."_archive_general_header",
                    "std" => "Archive",
                    "type" => "text");

$options[] = array( "name" => __("Home Page Message", "colabsthemes" ),
                    "desc" => __("This welcome message will appear in the sidebar of your home page. (HTML is allowed)", "colabsthemes" ),
                    "id" => $shortname."_ads_welcome_msg",
                    "std" => "",
                    "type" => "textarea");

$options[] = array( "name" => __("New Ad Message", "colabsthemes" ),
                    "desc" => __("This message will appear at the top of the classifier ads listing page. (HTML is allowed)", "colabsthemes" ),
                    "id" => $shortname."_ads_form_msg",
                    "std" => "",
                    "type" => "textarea");

$options[] = array( "name" => __("Membership Purchase Message", "colabsthemes" ),
                    "desc" => __("This message will appear at the top of the classifier ads listing page. (HTML is allowed)", "colabsthemes" ),
                    "id" => $shortname."_membership_form_msg",
                    "std" => "",
                    "type" => "textarea");

$options[] = array( "name" => __("Terms of Use", "colabsthemes" ),
                    "desc" => __("This message will appear on the last step of your classifier ad listing page. This is usually your legal disclaimer or rules for posting new ads on your site. (HTML is allowed)", "colabsthemes" ),
                    "id" => $shortname."_ads_tou_msg",
                    "std" => "",
                    "type" => "textarea");

/* //Social Settings	 */
$options[] = array( "name" => __("Social Networking", "colabsthemes" ),
					"icon" => "misc",
					"type" => "heading");

$options[] = array( "name" => __("Twitter", "colabsthemes" ),
					"desc" => __("Enter your Twitter URL. ex: http://twitter.com/colorlabs", "colabsthemes" ),
					"id" => $shortname."_social_twitter",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => __("Facebook", "colabsthemes" ),
					"desc" => __("Enter your Facebook profile URL. ex: http://www.facebook.com/colorlabs", "colabsthemes" ),
					"id" => $shortname."_social_facebook",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => __("Flickr", "colabsthemes" ),
					"desc" => __("Enter your Flickr URL. ex: http://www.flickr.com/photos/username", "colabsthemes" ),
					"id" => $shortname."_social_flickr",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => __("Google Plus", "colabsthemes" ),
					"desc" => __("Enter your Google Plus profile URL. ex: http://plus.google.com/", "colabsthemes" ),
					"id" => $shortname."_social_gplus",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => __("Youtube", "colabsthemes" ),
					"desc" => __("Enter your Youtube profile URL. ex: http://www.youtube.com/user/", "colabsthemes" ),
					"id" => $shortname."_social_youtube",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => __("Tumblr", "colabsthemes" ),
					"desc" => __("Enter your Tumblr profile URL. ex: http://username.tumblr.com/", "colabsthemes" ),
					"id" => $shortname."_social_tumblr",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => __("LinkedIn", "colabsthemes" ),
					"desc" => __("Enter your Facebook profile URL. ex: http://www.linkedin.com/", "colabsthemes" ),
					"id" => $shortname."_social_linkedin",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => __("Vimeo", "colabsthemes" ),
					"desc" => __("Enter your Viemo profile URL. ex: http://www.vimeo.com/", "colabsthemes" ),
					"id" => $shortname."_social_vimeo",
					"std" => "",
					"type" => "text");
/*
$options[] = array( "name" => "Enable/Disable Social Share Button",
					"desc" => "Select which social share button you would like to enable on single post & pages.",
					"id" => $shortname."_single_share",
					"std" => array("fblike","twitter","google_plusone"),
					"type" => "multicheck2",
                    "class" => "",
					"options" => array(
                                    "fblike" => "Facebook Like Button",                                    
                                    "twitter" => "Twitter Share Button",
                                    "google_plusone" => "Google +1 Button",
                                )
                    );*/

// Open Graph Settings
$options[] = array( "name" => __("Open Graph Settings", "colabsthemes" ),
					"type" => "heading",
					"icon" => "graph");

$options[] = array( "name" => __("Open Graph", "colabsthemes" ),
					"desc" => __("Enable or disable Open Graph Meta tags.", "colabsthemes" ),
					"id" => $shortname."_og_enable",
					"type" => "select2",
                    "std" => "",
                    "class" => "collapsed",
					"options" => array("" => "Enable", "disable" => "Disable") );

$options[] = array( "name" => __("Site Name", "colabsthemes" ),
					"desc" => __("Open Graph Site Name ( og:site_name ).", "colabsthemes" ),
					"id" => $shortname."_og_sitename",
					"std" => "",
                    "class" => "hidden",
					"type" => "text");

$options[] = array( "name" => __("Admin", "colabsthemes" ),
					"desc" => __("Open Graph Admin ( fb:admins ).", "colabsthemes" ),
					"id" => $shortname."_og_admins",
					"std" => "",
                    "class" => "hidden",
					"type" => "text");

$options[] = array( "name" => __("Image", "colabsthemes" ),
					"desc" => __("You can put the url for your Open Graph Image ( og:image ).", "colabsthemes" ),
					"id" => $shortname."_og_img",
					"std" => "",
                    "class" => "hidden last",
					"type" => "text");

//Dynamic Images 					                   
$options[] = array( "name" => __("Thumbnail Settings", "colabsthemes" ),
					"type" => "heading",
					"icon" => "image");
                    
$options[] = array( "name" => __("WordPress Featured Image", "colabsthemes" ),
					"desc" => __("Use WordPress Featured Image for post thumbnail.", "colabsthemes" ),
					"id" => $shortname."_post_image_support",
					"std" => "true",
					"class" => "collapsed",
					"type" => "checkbox");

$options[] = array( "name" => __("WordPress Featured Image - Dynamic Resize", "colabsthemes" ),
					"desc" => __("Resize post thumbnail dynamically using WordPress native functions (requires PHP 5.2+).", "colabsthemes" ),
					"id" => $shortname."_pis_resize",
					"std" => "true",
					"class" => "hidden",
					"type" => "checkbox");
                    
$options[] = array( "name" => __("WordPress Featured Image - Hard Crop", "colabsthemes" ),
					"desc" => __("Original image will be cropped to match the target aspect ratio.", "colabsthemes" ),
					"id" => $shortname."_pis_hard_crop",
					"std" => "true",
					"class" => "hidden last",
					"type" => "checkbox");
                    
$options[] = array( "name" => __("TimThumb Image Resizer", "colabsthemes" ),
					"desc" => __("Enable timthumb.php script which dynamically resizes images added thorugh post custom field.", "colabsthemes" ),
					"id" => $shortname."_resize",
					"std" => "true",
					"type" => "checkbox");
                    
$options[] = array( "name" => __("Automatic Thumbnail", "colabsthemes" ),
					"desc" => __("Generate post thumbnail from the first image uploaded in post (if there is no image specified through post custom field or WordPress Featured Image feature).", "colabsthemes" ),
					"id" => $shortname."_auto_img",
					"std" => "true",
					"type" => "checkbox");
                    
$options[] = array( "name" => __("Thumbnail Image in RSS Feed", "colabsthemes" ),
					"desc" => __("Add post thumbnail to RSS feed article.", "colabsthemes" ),
					"id" => $shortname."_rss_thumb",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => __("Thumbnail Image Dimensions", "colabsthemes" ),
					"desc" => __("Enter an integer value i.e. 250 for the desired size which will be used when dynamically creating the images.", "colabsthemes" ),
					"id" => $shortname."_image_dimensions",
					"std" => "",
					"type" => array( 
									array(  'id' => $shortname. '_thumb_w',
											'type' => 'text',
											'std' => 239,
											'meta' => 'Width'),
									array(  'id' => $shortname. '_thumb_h',
											'type' => 'text',
											'std' => 143,
											'meta' => 'Height')
								  ));

$options[] = array( "name" => __("Custom Field Image", "colabsthemes" ),
					"desc" => __("Enter your custom field image name to change the default name (default name: image).", "colabsthemes" ),
					"id" => $shortname."_custom_field_image",
					"std" => "",
					"type" => "text");
					
// Analytics ID, RSS feed
$options[] = array( "name" => __("Analytics ID, RSS feed", "colabsthemes" ),
					"type" => "heading",
					"icon" => "statistics");

$options[] = array( "name" => __("GoSquared Token", "colabsthemes" ),
					"desc" => __("ou can use <a href=\"http://www.gosquared.com/livestats/?ref=11674\">GoSquared</a> real-time web analytics. Enter your <strong>GoSquared Token</strong> here (ex. GSN-8923821-D).", "colabsthemes" ),
					"id" => $shortname."_gosquared",
					"std" => "",
					"type" => "text");	
                    
$options[] = array( "name" => __("Google Analytics", "colabsthemes" ),
					"desc" => __("Manage your website statistics with Google Analytics, put your Analytics Code here. ", "colabsthemes" ),
					"id" => $shortname."_google_analytics",
					"std" => "",
					"type" => "textarea");

$options[] = array( "name" => __("Feedburner URL", "colabsthemes" ),
					"desc" => __("Feedburner URL. This will replace RSS feed link. Start with http://.", "colabsthemes" ),
					"id" => $shortname."_feedlinkurl",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => __("Feedburner Comments URL", "colabsthemes" ),
					"desc" => __("Feedburner URL. This will replace RSS comment feed link. Start with http://.", "colabsthemes" ),
					"id" => $shortname."_feedlinkcomments",
					"std" => "",
					"type" => "text");
					
// Footer Settings
$options[] = array( "name" => __("Footer Settings", "colabsthemes" ),
					"type" => "heading",
					"icon" => "footer");    

$options[] = array( "name" => __("Enable / Disable Custom Credit (Right)", "colabsthemes" ),
					"desc" => __("Activate to add custom credit on footer area.", "colabsthemes" ),
					"id" => $shortname."_footer_credit",
					"class" => "collapsed",
					"std" => "false",
					"type" => "checkbox");    

$options[] = array( "name" => __("Footer Credit", "colabsthemes" ),
                    "desc" => __("You can customize footer credit on footer area here.", "colabsthemes" ),
                    "id" => $shortname."_footer_credit_txt",
                    "std" => "",
					"class" => "hidden last",                    
                    "type" => "textarea");
					
/* //Contact Form */
$options[] = array( "name" => __("Contact Form", "colabsthemes" ),
					"type" => "heading",
					"icon" => "general");    
$options[] = array( "name" => __("Destination Email Address", "colabsthemes" ),
					"desc" => __("All inquiries made by your visitors through the Contact Form page will be sent to this email address.", "colabsthemes" ),
					"id" => $shortname."_contactform_email",
					"std" => "",
					"type" => "text"); 

/* //Color Settings */
$options[] = array( "name" => __("Color Settings", "colabsthemes" ),
					"type" => "heading",
					"icon" => "general"); 
					
$options[] = array( "name" => __("General Color", "colabsthemes" ),
					"desc" => __("Select any color do you want to set all general color of your theme. (Default.#00BB64)", "colabsthemes" ),
					"id" => $shortname."_general_color",
					"std" => "#00BB64",
					"type" => "color");

$options[] = array( "name" => __("Hover Link Color", "colabsthemes" ),
					"desc" => __("Select any color do you want to set all hover link color of your theme. (Default.#006E3B)", "colabsthemes" ),
					"id" => $shortname."_hover_color",
					"std" => "#006E3B",
					"type" => "color");
					
// Add extra options through function
if ( function_exists("colabs_options_add") )
	$options = colabs_options_add($options);

if ( get_option('colabs_template') != $options) update_option('colabs_template',$options);      
if ( get_option('colabs_themename') != $themename) update_option('colabs_themename',$themename);   
if ( get_option('colabs_shortname') != $shortname) update_option('colabs_shortname',$shortname);
if ( get_option('colabs_manual') != $manualurl) update_option('colabs_manual',$manualurl);


// CoLabs Metabox Options
// Start name with underscore to hide custom key from the user
$colabs_metaboxes = array();
$colabs_metabox_settings = array();
global $post;

    //Metabox Settings
    $colabs_metabox_settings['post'] = array(
                                'id' => 'colabsthemes-settings',
								'title' => 'ColorLabs' . __( ' Image/Video Settings', 'colabsthemes' ),
								'callback' => 'colabsthemes_metabox_create',
								'page' => 'post',
								'context' => 'normal',
								'priority' => 'high',
                                'callback_args' => ''
								);
                                    
    $colabs_metabox_settings["page"] = array(
                                "id" => "colabsthemes-settings",
								"title" => "ColorLabs " . __( "Custom Meta Settings", "colabsthemes" ),
								"callback" => "colabsthemes_metabox_create",
								"page" => "page",
								"context" => "normal",
								"priority" => "high",
                                "callback_args" => ""
								);

	
	$colabs_metabox_settings['ad'] = array(
                                'id' => 'colabsthemes-settings',
								'title' => 'ColorLabs' . __( ' Ad Detail Settings', 'colabsthemes' ),
								'callback' => 'colabsthemes_metabox_create',
								'page' => 'ad',
								'context' => 'normal',
								'priority' => 'high',
                                'callback_args' => ''
								);
									


								
if ( ( get_post_type() == 'post') || ( !get_post_type() ) ) {
	$colabs_metaboxes[] = array (  "name"  => $shortname."_single_top",
					            "std"  => "Image",
					            "label" => "Item to Show",
					            "type" => "radio",
					            "desc" => "Choose Image/Embed Code to appear at the single top.",
								"options" => array(	"none" => "None",
													"single_image" => "Image",
													"single_video" => "Embed" ));
	$colabs_metaboxes[] = array (	"name" => "image",
								"label" => "Post Custom Image",
								"type" => "upload",
                                "class" => "single_image",
								"desc" => "Upload an image or enter an URL.");
	
	$colabs_metaboxes[] = array (  "name"  => $shortname."_embed",
					            "std"  => "",
					            "label" => "Video Embed Code",
					            "type" => "textarea",
                                "class" => "single_video",
					            "desc" => "Enter the video embed code for your video (YouTube, Vimeo or similar)");
								
	$url =  get_template_directory_uri() . "/functions/images/";
	$colabs_metaboxes[] = array (	"name" => "layout",
								"label" => __( "Layout", "colabsthemes" ),
								"type" => "images",
								"desc" => __( "Select a specific layout for this post/page. Overrides default site layout.", "colabsthemes" ),
								"options" => array(	"" => $url . "layout-off.png",
													"one-col" => $url . "1c.png",
													"two-col-left" => $url . "2cl.png",
													"two-col-right" => $url . "2cr.png"));							
					            
} // End post

//page
if ( ( get_post_type() == "page") || ( !get_post_type() ) ) {

	$url =  get_template_directory_uri() . "/functions/images/";
	$colabs_metaboxes[] = array (	"name" => "layout",
								"label" => __( "Layout", "colabsthemes" ),
								"type" => "images",
								"desc" => __( "Select a specific layout for this post/page. Overrides default site layout.", "colabsthemes" ),
								"options" => array(	"" => $url . "layout-off.png",
													"one-col" => $url . "1c.png",
													"two-col-left" => $url . "2cl.png",
													"two-col-right" => $url . "2cr.png"));

} // End page

if ( ( get_post_type() == 'ad') || ( !get_post_type() ) ) {
	$colabs_metaboxes[] = array (  "name"  => $shortname."_single_top",
					            "std"  => "Image",
					            "label" => "Item to Show",
					            "type" => "radio",
					            "desc" => "Choose Image/Embed Code to appear at the single top.",
								"options" => array(	"none" => "None",
													"single_image" => "Image",
													"single_video" => "Embed" ));
	$colabs_metaboxes[] = array (	"name" => "image",
								"label" => "Post Custom Image",
								"type" => "upload",
                                "class" => "single_image",
								"desc" => "Upload an image or enter an URL.");
	
	$colabs_metaboxes[] = array (  "name"  => $shortname."_embed",
					            "std"  => "",
					            "label" => "Video Embed Code",
					            "type" => "textarea",
                                "class" => "single_video",
					            "desc" => "Enter the video embed code for your video (YouTube, Vimeo or similar)");
	
	$colabs_metaboxes[] = array( "name" => $shortname."_email",
                            "type" => "text",
                            "std" => "",
                            "label" => "Email",
                            "desc" => "");
							
	$colabs_metaboxes[] = array( "name" => $shortname."_price",
                            "type" => "text",
                            "std" => "",
                            "label" => "Price",
                            "desc" => "");

	$colabs_metaboxes[] = array( "name" => $shortname."_discount",
                            "type" => "text",
                            "std" => "",
                            "label" => "Sale Discount",
                            "desc" => "Enter percentage of discount here (eg. 30 - refer to 30%). Leave it empty if there are no discount for this listing.");
							
	$colabs_metaboxes[] = array( "name" => $shortname."_sys_expire_date",
								"type" => "calendar",
								"std" => "",
								"label" => "Expired in",
								"desc" => "");						
	
	$colabs_metaboxes[] = array( "name" => $shortname."_phone",
								"type" => "text",
								"std" => "",
								"label" => "Phone",
								"desc" => "");
								
	$colabs_metaboxes[] = array( "name" => $shortname."_site",
								"type" => "text",
								"std" => "",
								"label" => "Ad Website",
								"desc" => "");

	$colabs_metaboxes[] = array( "name" => $shortname."_location",
								"type" => "text",
								"std" => "",
								"label" => "Address",
								"desc" => "");		
	
	$colabs_metaboxes[] = array( "name" => $shortname."_zipcode",
								"type" => "text",
								"std" => "",
								"label" => "Zip/Postal Code",
								"desc" => "");
                                
	$colabs_metaboxes[] = array( "name" => $shortname."_sys_userIP",
								"type" => "info2",
								"std" => "",
								"label" => "Submitted from this IP",
								"desc" => "This is a short description.");
}


// Add extra metaboxes through function
if ( function_exists("colabs_metaboxes_add") ){
	$colabs_metaboxes = colabs_metaboxes_add($colabs_metaboxes);
    }
if ( get_option('colabs_custom_template') != $colabs_metaboxes){
    update_option('colabs_custom_template',$colabs_metaboxes);
    }
if ( get_option('colabs_metabox_settings') != $colabs_metabox_settings){
    update_option('colabs_metabox_settings',$colabs_metabox_settings);
    }
     
}
}



?>