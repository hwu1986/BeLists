<?php
/**
 * Custom post types and taxonomies
 *
 *
 * @version 1.0.0
 * @author ColorLabs & Company
 * @package Classifier
 *
 */


// create the custom post type and category taxonomy for ad listings
function colabs_ad_listing_post_type() {
    $colabs_abbr = 'colabs';global $wp_rewrite;

    // get the slug value for the ad custom post type & taxonomies
    if(get_option($colabs_abbr.'_post_type_permalink')) $post_type_base_url = get_option($colabs_abbr.'_post_type_permalink'); else $post_type_base_url = 'ads';
    if(get_option($colabs_abbr.'_ad_cat_tax_permalink')) $cat_tax_base_url = get_option($colabs_abbr.'_ad_cat_tax_permalink'); else $cat_tax_base_url = 'ad-category';
    if(get_option($colabs_abbr.'_ad_tag_tax_permalink')) $tag_tax_base_url = get_option($colabs_abbr.'_ad_tag_tax_permalink'); else $tag_tax_base_url = 'ad-tag';
    if(get_option($colabs_abbr.'_ad_loc_tax_permalink')) $tag_loc_base_url = get_option($colabs_abbr.'_ad_loc_tax_permalink'); else $tag_loc_base_url = 'ad-location';

    // register the new post type
    register_post_type( COLABS_POST_TYPE,
        array( 
            'labels' => array(
                'name' => __( 'Ads', 'colabsthemes' ),
                'singular_name' => __( 'Ad', 'colabsthemes' ),
                'add_new' => __( 'Add New', 'colabsthemes' ),
                'add_new_item' => __( 'Create New Ad', 'colabsthemes' ),
                'edit' => __( 'Edit', 'colabsthemes' ),
                'edit_item' => __( 'Edit Ad', 'colabsthemes' ),
                'new_item' => __( 'New Ad', 'colabsthemes' ),
                'view' => __( 'View Ads', 'colabsthemes' ),
                'view_item' => __( 'View Ad', 'colabsthemes' ),
                'search_items' => __( 'Search Ads', 'colabsthemes' ),
                'not_found' => __( 'No ads found', 'colabsthemes' ),
                'not_found_in_trash' => __( 'No ads found in trash', 'colabsthemes' ),
                'parent' => __( 'Parent Ad', 'colabsthemes' ),
                ),
            'description' => __( 'This is where you can create new classified ads on your site.', 'colabsthemes' ),
            'public' => true,
            'show_ui' => true,
	    'has_archive' => true,
            'capability_type' => 'post',
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'menu_position' => 8,
            //'menu_icon' => FAVICON,
            'hierarchical' => false,
            'rewrite' => array( 'slug' => $post_type_base_url, 'with_front' => false ),
            'query_var' => true,
            'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'sticky' ),
            )
);

    // register the new ad category taxonomy
    register_taxonomy( COLABS_TAX_CAT,
            array(COLABS_POST_TYPE),
            array('hierarchical' => true,
                  'labels' => array(
                        'name' => __( 'Ad Categories', 'colabsthemes'),
                        'singular_name' => __( 'Ad Category', 'colabsthemes'),
                        'search_items' =>  __( 'Search Ad Categories', 'colabsthemes'),
                        'all_items' => __( 'All Ad Categories', 'colabsthemes'),
                        'parent_item' => __( 'Parent Ad Category', 'colabsthemes'),
                        'parent_item_colon' => __( 'Parent Ad Category:', 'colabsthemes'),
                        'edit_item' => __( 'Edit Ad Category', 'colabsthemes'),
                        'update_item' => __( 'Update Ad Category', 'colabsthemes'),
                        'add_new_item' => __( 'Add New Ad Category', 'colabsthemes'),
                        'new_item_name' => __( 'New Ad Category Name', 'colabsthemes')
                    ),
                    'show_ui' => true,
                    'query_var' => true,
					'update_count_callback' => '_update_post_term_count',
                    'rewrite' => array( 'slug' => $cat_tax_base_url, 'with_front' => false, 'hierarchical' => true ),
            )
    );

    // register the new ad tag taxonomy
    register_taxonomy( COLABS_TAX_TAG,
            array(COLABS_POST_TYPE),
            array('hierarchical' => false,
                  'labels' => array(
                        'name' => __( 'Ad Tags', 'colabsthemes'),
                        'singular_name' => __( 'Ad Tag', 'colabsthemes'),
                        'search_items' =>  __( 'Search Ad Tags', 'colabsthemes'),
                        'all_items' => __( 'All Ad Tags', 'colabsthemes'),
                        'parent_item' => __( 'Parent Ad Tag', 'colabsthemes'),
                        'parent_item_colon' => __( 'Parent Ad Tag:', 'colabsthemes'),
                        'edit_item' => __( 'Edit Ad Tag', 'colabsthemes'),
                        'update_item' => __( 'Update Ad Tag', 'colabsthemes'),
                        'add_new_item' => __( 'Add New Ad Tag', 'colabsthemes'),
                        'new_item_name' => __( 'New Ad Tag Name', 'colabsthemes')
                    ),
                    'show_ui' => true,
                    'query_var' => true,
					'update_count_callback' => '_update_post_term_count',
                    'rewrite' => array( 'slug' => $tag_tax_base_url, 'with_front' => false ),
            )
    );
    
    // register the new ad location taxonomy
    register_taxonomy( COLABS_TAX_LOC,
            array(COLABS_POST_TYPE),
            array('hierarchical' => false,
                  'labels' => array(
                        'name' => __( 'Ad Location', 'colabsthemes'),
                        'singular_name' => __( 'Ad Location', 'colabsthemes'),
                        'search_items' =>  __( 'Search Ad Location', 'colabsthemes'),
                        'all_items' => __( 'All Ad Location', 'colabsthemes'),
                        'parent_item' => __( 'Parent Ad Location', 'colabsthemes'),
                        'parent_item_colon' => __( 'Parent Ad Location:', 'colabsthemes'),
                        'edit_item' => __( 'Edit Ad Location', 'colabsthemes'),
                        'update_item' => __( 'Update Ad Location', 'colabsthemes'),
                        'add_new_item' => __( 'Add New Ad Location', 'colabsthemes'),
                        'new_item_name' => __( 'New Ad Location Name', 'colabsthemes')
                    ),
                    'hierarchical' => true,                                        
                    'show_ui' => true,
                    'query_var' => true,
					'update_count_callback' => '_update_post_term_count',
                    'rewrite' => array( 'slug' => $tag_loc_base_url, 'with_front' => $wp_rewrite->using_index_permalinks() ),
            )
    );
    
    // this needs to happen once after install script first runs
    /*if (get_option('colabs_rewrite_flush_flag') == 'true') {
        flush_rewrite_rules();
        delete_option('colabs_rewrite_flush_flag');
    }*/
    flush_rewrite_rules();

}

// activate the custom post type
add_action( 'init', 'colabs_ad_listing_post_type', 0 );


// add the custom edit ads page columns
function colabs_edit_ad_columns($columns){
    $columns = array(
            'cb' => "<input type=\"checkbox\" />",
            'title' => __('Title', 'colabsthemes'),
            'author' => __('Author', 'colabsthemes'),
            COLABS_TAX_CAT => __('Category', 'colabsthemes'),
            COLABS_TAX_TAG => __('Tags', 'colabsthemes'),
            COLABS_TAX_LOC => __('Location', 'colabsthemes'),
            'colabs_price' => __('Price', 'colabsthemes'),
            'colabs_daily_count' => __('Views Today', 'colabsthemes'),
            'colabs_total_count' => __('Views Total', 'colabsthemes'),
            'colabs_sys_expire_date' => __('Expires', 'colabsthemes'),
            'comments' => '<div class="vers"><img src="' . esc_url( admin_url( 'images/comment-grey-bubble.png' ) ) . '" /></div>',
            'date' => __('Date', 'colabsthemes'),
    );
    return $columns;
}
add_filter('manage_edit-'.COLABS_POST_TYPE.'_columns', 'colabs_edit_ad_columns');

// register the columns as sortable
function colabs_ad_column_sortable($columns) {
	$columns['colabs_price'] = 'colabs_price'; 
	$columns['colabs_daily_count'] = 'colabs_daily_count'; 
	$columns['colabs_total_count'] = 'colabs_total_count'; 
	$columns['colabs_sys_expire_date'] = 'colabs_sys_expire_date'; 
	return $columns;
}
add_filter('manage_edit-'.COLABS_POST_TYPE.'_sortable_columns', 'colabs_ad_column_sortable');


// how the custom columns should sort
function colabs_ad_column_orderby($vars) {
	
    if ( isset( $vars['orderby'] ) && 'colabs_price' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array(
            'meta_key' => 'colabs_price',
            'orderby' => 'meta_value_num',
        ) );
    }
    
    if ( isset( $vars['orderby'] ) && 'colabs_daily_count' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array(
            'meta_key' => 'colabs_daily_count',
            'orderby' => 'meta_value_num',
        ) );
    }
    
    if ( isset( $vars['orderby'] ) && 'colabs_total_count' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array(
            'meta_key' => 'colabs_total_count',
            'orderby' => 'meta_value_num',
        ) );
    }
    

    return $vars;
}
add_filter('request', 'colabs_ad_column_orderby');


// add the custom edit ads page column values
function colabs_custom_columns($column){
	global $post;
	$custom = get_post_custom();

	switch ($column) :

		case 'colabs_sys_expire_date':
			if ( isset($custom['colabs_sys_expire_date'][0]) && !empty($custom['colabs_sys_expire_date'][0]) )
				echo $custom['colabs_sys_expire_date'][0];
		break;

		case 'colabs_price':
			if ( isset($custom['colabs_price'][0]) && !empty($custom['colabs_price'][0]) )
				echo $custom['colabs_price'][0];
		break;
		
		case 'colabs_daily_count':
			if ( isset($custom['colabs_daily_count'][0]) && !empty($custom['colabs_daily_count'][0]) )
				echo $custom['colabs_daily_count'][0];
		break;
		
		case 'colabs_total_count':
			if ( isset($custom['colabs_total_count'][0]) && !empty($custom['colabs_total_count'][0]) )
				echo $custom['colabs_total_count'][0];
		break;

		case COLABS_TAX_TAG :
			echo get_the_term_list($post->ID, COLABS_TAX_TAG, '', ', ','');
		break;

		case COLABS_TAX_CAT :
			echo get_the_term_list($post->ID, COLABS_TAX_CAT, '', ', ','');
		break;

		case COLABS_TAX_LOC :
			echo get_the_term_list($post->ID, COLABS_TAX_LOC, '', ', ','');
		break;

	endswitch;
}
add_action('manage_posts_custom_column',  'colabs_custom_columns');


// add the custom edit ad categories page columns
function colabs_edit_ad_cats_columns($columns){
    $columns = array(
            'cb' => "<input type=\"checkbox\" />",
            'name' => __('Name', 'colabsthemes'),
            'description' => __('Description', 'colabsthemes'),
            'slug' => __('Slug', 'colabsthemes'),
            'num' => __('Ads', 'colabsthemes'),
    );
    return $columns;
}

// don't enable this yet. see wp-admin function _tag_row for main code
//add_filter('manage_edit-'.COLABS_TAX_CAT.'_columns', 'colabs_edit_ad_cats_columns');

// hack until WP supports custom post type sticky feature
function colabs_sticky_option() {
	global $post;
	
	//if post is a custom post type and only during the first execution of the action quick_edit_custom_box
	if ($post->post_type == COLABS_POST_TYPE && did_action('quick_edit_custom_box') === 1): ?>
	
	<fieldset class="inline-edit-col-right">
		<div class="inline-edit-col">
			<label class="alignleft">
				<input type="checkbox" name="sticky" value="sticky" />
				<span class="checkbox-title"><?php _e('Featured Ad (sticky)', 'colabsthemes'); ?></span>
			</label>
		</div>	
	</fieldset>
<?php
	endif;
}
//add the sticky option to the quick edit area
add_action('quick_edit_custom_box', 'colabs_sticky_option');


// custom user page columns
function colabs_manage_users_columns( $columns ) {
	$newcol = array_slice( $columns, 0, 1 );
	$newcol = array_merge( $newcol, array( 'id' => __('Id', 'colabsthemes') ) );
	$columns = array_merge( $newcol, array_slice( $columns, 1 ) );

    $columns['colabs_ads_count'] = __('Ads', 'colabsthemes');
	$columns['last_login'] = __('Last Login', 'colabsthemes');
	$columns['registered'] = __('Registered', 'colabsthemes');
    return $columns;
}
add_action('manage_users_columns', 'colabs_manage_users_columns');


// register the columns as sortable
function colabs_users_column_sortable( $columns ) {
	$columns['id'] = 'id';
	return $columns;
}
add_filter('manage_users_sortable_columns', 'colabs_users_column_sortable');


// display the coumn values for each user
function colabs_manage_users_custom_column( $r, $column_name, $user_id ) {
	switch ( $column_name ) {
		case 'colabs_ads_count' :
			global $colabs_counts;

			if ( !isset( $colabs_counts ) )
				$colabs_counts = colabs_count_ads();

			if ( !array_key_exists( $user_id, $colabs_counts ) )
				$colabs_counts = colabs_count_ads();

			if ( $colabs_counts[$user_id] > 0 ) {
				$r .= "<a href='edit.php?post_type=" . COLABS_POST_TYPE . "&author=$user_id' title='" . esc_attr__( 'View ads by this author', 'colabsthemes' ) . "' class='edit'>";
				$r .= $colabs_counts[$user_id];
				$r .= '</a>';
			} else {
				$r .= 0;
			}
		break;
	
		case 'last_login' :
			$r = get_user_meta($user_id, 'last_login', true);
		break;

		case 'registered' :
			$user_info = get_userdata($user_id);
			$r = $user_info->user_registered;
			//$r = colabsthemes_get_reg_date($reg_date);
		break;

		case 'id' :
			$r = $user_id;
	}

	return $r;
}
//Display the ad counts for each user
add_action( 'manage_users_custom_column', 'colabs_manage_users_custom_column', 10, 3 );


// count the number of ad listings for the user
function colabs_count_ads() {
	global $wpdb, $wp_list_table;

	$users = array_keys( $wp_list_table->items );
	$userlist = implode( ',', $users );
	$result = $wpdb->get_results( "SELECT post_author, COUNT(*) FROM $wpdb->posts WHERE post_type = '" . COLABS_POST_TYPE . "' AND post_author IN ($userlist) GROUP BY post_author", ARRAY_N );
	foreach ( $result as $row ) {
		$count[ $row[0] ] = $row[1];
	}

	foreach ( $users as $id ) {
		if ( ! isset( $count[ $id ] ) )
			$count[ $id ] = 0;
	}

	return $count;
}


// add a drop-down post type selector to the edit post/ads admin pages
function colabs_post_type_changer() {
    global $post;

    // disallow things like attachments, revisions, etc
    $safe_filter = array('public' => true, 'show_ui' => true);

    // allow this to be filtered
    $args = apply_filters('colabs_post_type_changer', $safe_filter);

    // get the post types
    $post_types = get_post_types((array)$args);

    // get the post_type values
    $cur_post_type_object = get_post_type_object($post->post_type);

    $cur_post_type = $cur_post_type_object->name;

    // make sure the logged in user has perms
    $can_publish = current_user_can($cur_post_type_object->cap->publish_posts);
	?>
	
	<?php if ( $can_publish ) : /* ?>
	
	<!--div class="misc-pub-section misc-pub-section-last post-type-switcher">
	
		<label for="pts_post_type"><?php _e('Post Type:', 'colabsthemes'); ?></label>
	
		<span id="post-type-display"><?php echo $cur_post_type_object->label; ?></span>
	
		<a href="#pts_post_type" class="edit-post-type hide-if-no-js"><?php _e('Edit', 'colabsthemes'); ?></a>
		<div id="post-type-select" class="hide-if-js">
	
			<select name="pts_post_type" id="pts_post_type">
	            <?php foreach ( $post_types as $post_type ) {
				$pt = get_post_type_object( $post_type );
	
				if ( current_user_can( $pt->cap->publish_posts ) ) : ?>
	
					<option value="<?php echo $pt->name; ?>"<?php if ( $cur_post_type == $post_type ) : ?>selected="selected"<?php endif; ?>><?php echo $pt->label; ?></option>
	
				<?php
				endif;
			}
	            ?>
			</select>
	
			<input type="hidden" name="hidden_post_type" id="hidden_post_type" value="<?php echo $cur_post_type; ?>" />
	
			<a href="#pts_post_type" class="save-post-type hide-if-no-js button"><?php _e('OK', 'colabsthemes'); ?></a>
			<a href="#pts_post_type" class="cancel-post-type hide-if-no-js"><?php _e('Cancel', 'colabsthemes'); ?></a>
		</div>	
		
	</div-->
	<?php */ ?>
	<div class="misc-pub-section misc-pub-section-last post-type-switcher">
		<span id="sticky"><input id="sticky" name="sticky" type="checkbox" value="sticky" <?php checked(is_sticky($post->ID)); ?> tabindex="4" /> <label for="sticky" class="selectit"><?php _e('Featured Ad (sticky)', 'colabsthemes') ?></label><br /></span>
	</div>

<?php
	endif;
}
// add this option to the edit post submit box
add_action('post_submitbox_misc_actions', 'colabs_post_type_changer');

?>