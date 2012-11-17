<?php

/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

- colabs_get_search_catid
- colabs_cat_menu_drop_down
- colabs_edit_term_delete_transient
- colabs_get_price_legacy
- colabs_get_price
- colabs_pos_price
- colabs_pos_currency
- colabs_cl_breadcrumb
- colabs_timeleft
- colabs_dropdown_categories_prices
- colabs_category_dropdown_tree
- colabs_cat_menu_drop_down

-----------------------------------------------------------------------------------*/


/*-----------------------------------------------------------------------------------*/
/*  get category id for search form */
/*-----------------------------------------------------------------------------------*/
function colabs_get_search_catid() {
    global $post;

    if(is_tax(COLABS_TAX_CAT)){ 
      $ad_cat_array = get_term_by( 'slug', get_query_var(COLABS_TAX_CAT), COLABS_TAX_CAT, ARRAY_A );
      $catid = $ad_cat_array['term_id'];
    } else if (is_singular(COLABS_POST_TYPE)) {
      $term = wp_get_object_terms($post->ID, COLABS_TAX_CAT);
      if($term)
        $catid = $term[0]->term_id;
    } else if (is_search()) {
      $catid = get_query_var('scat');
    }

    if(!isset($catid) || !is_numeric($catid))
      $catid = 0;

    return $catid;
}


// category menu drop-down display
if (!function_exists('colabs_cat_menu_drop_down')) {
    function colabs_cat_menu_drop_down($subs = 0) {
		global $wpdb;

        $cols = 3; 

		// get any existing copy of our transient data
		if (false === ($colabs_cat_menu = get_transient('colabs_cat_menu'))) {

			// put all options into vars so we don't have to waste resources by calling them each time from within the loops
			$colabs_cat_parent_count = get_option('colabs_cat_parent_count');
			$colabs_cat_child_count = get_option('colabs_cat_child_count');
			$colabs_cat_hide_empty = get_option('colabs_cat_hide_empty');
			$colabs_cat_orderby = get_option('colabs_cat_orderby');
      $colabs_cat_nocatstext = get_option('colabs_cat_strip_nocatstext');

			// get all cats for the taxonomy ad_cat
			$cats = get_terms(COLABS_TAX_CAT, 'hide_empty=0&hierarchical=1&pad_counts=1&show_count=1&orderby='.$colabs_cat_orderby.'&order=ASC');
            $subcats = array();
            $cats = apply_filters('cat_menu_drop_down',$cats);
            
      if ( count($cats) > 0 ){
  			 //remove all sub cats from the array and create new array with sub cats
  			foreach ($cats as $key => $value)
  				if ($value->parent != 0) {
            $subcats[$key] = $cats[$key];
            unset($cats[$key]);
          }

  			$i = 0;
  			$cat_cols = $cols; // change this to add/remove columns
  			$total_main_cats = count($cats); // total number of parent cats

  			// loop through all the sub
  			foreach($cats as $cat) :

                $colabs_cat_menu .= '<div class="category-block">';

  				// only show the total count if option is set
  				if($colabs_cat_parent_count == 'true')
  					$show_count = '<span class="count">('. $cat->count .')</span>';

  				$colabs_cat_menu .= '<h3 class="category-title cat-item-'. $cat->term_id .'">';
                
                $tax_meta = get_tax_meta( $cat->term_id , 'colabs_cat_icon_id');
                if( !empty($tax_meta) ) $colabs_cat_menu .= '<img src="'. $tax_meta['src'] .'" class="category-icon">';
                
  				$colabs_cat_menu .= '<a href="'. get_term_link( $cat, COLABS_TAX_CAT ) .'" title="'. esc_attr( $cat->description ) .'">'. $cat->name .'</a> '.$show_count.'</h3>';
                $colabs_cat_menu .= '<ul>';
                
  				// don't show any sub cats
  				if($subs <> 999) :
                
                    $cat_children = get_term_children( $cat->term_id, COLABS_TAX_CAT );
                    $subs_count = 0;
                    if ( count($subcats) > 0 ){
                			foreach($subcats as $subcat) {
                        // skip sub cats from other cats
                        if ($subcat->parent != $cat->term_id && !in_array($subcat->parent, $cat_children))
                          continue;
                        // hide empty sub cats if option is set
                        if($colabs_cat_hide_empty == 'true' && $subcat->count == 0)
                          continue;
                        // limit quantity of sub cats as user set
                        if($subs_count >= $subs && $subs != 0)
                          continue;
                        
                				// only show the total count if option is set
                				if($colabs_cat_child_count == 'true')
                					$show_child_count = '<span class="count">('. $subcat->count .')</span>';
                        else
                          $show_child_count = '';
                  
                				$colabs_cat_menu .= '<li class="cat-item cat-item-'. $subcat->term_id .'"><a href="'. get_term_link( $subcat, COLABS_TAX_CAT ) .'" title="'. esc_attr( $subcat->description ) .'">'. $subcat->name .'</a> '.$show_child_count.'</li>';
                        $subs_count++;
                      }
                    }
                    if($colabs_cat_nocatstext != '' && $subs_count == 0)
                      $colabs_cat_menu .= '<li>'.__('No categories', 'colabsthemes').'</li>';

  				endif;

  				$colabs_cat_menu .= '</ul>';

                $colabs_cat_menu .= '</div><!-- /category-block -->';

  				$i++;

  			endforeach;

  			return $colabs_cat_menu;

  			// set transient
  			set_transient('colabs_cat_menu', $colabs_cat_menu, get_option('colabs_cache_expires'));
      
      }// end if count cats 

		} else {

			// must already be transient data so use that
			return get_transient('colabs_cat_menu');

		}

    }
}

// delete transient to refresh cat menu
function colabs_edit_term_delete_transient() {
     delete_transient('colabs_cat_menu');
}

// runs when categories/tags are edited
add_action('edit_term', 'colabs_edit_term_delete_transient');


/*-----------------------------------------------------------------------------------*/
/*  legacy function used on CP 2.9.3 and earlier        */
/*  get the ad price and position the currency symbol   */
/*-----------------------------------------------------------------------------------*/

function colabs_get_price_legacy($postid) {
	$colabs_abbr = 'colabs';
    
    if(get_post_meta($postid, 'colabs_price', true)) {
        $price_out = get_post_meta($postid, 'colabs_price', true);

        // uncomment the line below to change price format
        //$price_out = number_format($price_out, 2, ',', '.');

        if(get_option('colabs_curr_symbol_pos') == 'right'){
            $price_out = $price_out . get_option($colabs_abbr.'_curr_symbol');
        } else {
            $price_out = get_option($colabs_abbr.'_curr_symbol') . $price_out;
        }
        
    } else {
        $price_out = '&nbsp;';
    }

    echo $price_out;

}


// get the ad price and position the currency symbol
if (!function_exists('colabs_get_price')) {
    function colabs_get_price($postid, $meta_field) {

        if(get_post_meta($postid, $meta_field, true)) {
            $price_out = get_post_meta($postid, $meta_field, true);

            // uncomment the line below to change price format
            //$price_out = number_format($price_out, 2, '.', ',');

            $price_out = colabs_pos_currency($price_out, 'ad');

        } else {
            if( get_option('colabs_force_zeroprice') == 'true' )
                $price_out = colabs_pos_currency(0, 'ad');
            else
                $price_out = '&nbsp;';
        }

        echo $price_out;
    }
}


// pass in the price and get the position of the currency symbol
function colabs_pos_price($numout, $price_type = '') {
    $numout = colabs_pos_currency($numout, $price_type);
    echo $numout;
}

// figure out the position of the currency symbol and return it with the price
function colabs_pos_currency($price_out, $price_type = '') {
	$colabs_abbr = 'colabs';

	//if its set to the ad type, display the currency symbol option related to ad currency
	if($price_type == 'ad') $curr_symbol = get_option('colabs_curr_symbol');
	//if price_type not set use the currency type of the payment gateway currency type
	else $curr_symbol = get_option($colabs_abbr.'_curr_symbol');

	//possition the currency symbol
    if (get_option('colabs_curr_symbol_pos') == 'left')
        $price_out = $curr_symbol . $price_out;
    elseif (get_option('colabs_curr_symbol_pos') == 'left_space')
        $price_out = $curr_symbol . '&nbsp;' . $price_out;
    elseif (get_option('colabs_curr_symbol_pos') == 'right')
        $price_out = $price_out . $curr_symbol;
    else
        $price_out = $price_out . '&nbsp;' . $curr_symbol;

    return $price_out;
}

/*-----------------------------------------------------------------------------------*/
/*  Breadcrumb for the top of pages        */
/*-----------------------------------------------------------------------------------*/
function colabs_cl_breadcrumb($args = null) {
	global $post;

    $colabs_abbr = get_option('colabs_shortname');

	$delimiter = '&raquo;';
	$currentBefore = '<span class="current">';
	$currentAfter = '</span>';

	/* Set up the default arguments for the breadcrumb. */
	$defaults = array(
		'delimiter' => '&raquo;',
		'before' => '<div class="row breadcrumbs"><div class="breadcrumb-inner">',
		'after' => '</div></div>',
		'echo' => true, 
	);

	/* Apply filters to the arguments. */
	$args = apply_filters( 'colabs_cl_breadcrumb_args', $args );

	/* Parse the arguments and extract them for easy variable naming. */
	extract( wp_parse_args( $args, $defaults ) );

	if ( !is_home() || !is_front_page() || is_paged() ) :
		$flag = 1;
		echo $before;
		echo '<a href="' . get_bloginfo('url') . '">' . __('Home', 'colabsthemes') . '</a> ' . $delimiter . ' ';
        
        /* Get some taxonomy and term variables. */
        if( is_tax() ){
            $taxonomy = get_taxonomy( get_query_var( 'taxonomy' ) );
            $taxonomy_name = $taxonomy->name;
        }
		// figure out what to display
		switch ($flag) :

			case is_tax(COLABS_TAX_TAG):
				echo $currentBefore . __('Ads tagged with', 'colabsthemes') .' &#39;' . single_tag_title('', false) . '&#39;' . $currentAfter;
			break;

			case is_tax():
				// get the current ad category
				$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
				// get the current ad category parent id
				$parent = $term->parent;
				// WP doesn't have a function to grab the top-level term id so we need to
				// climb up the tree and create a list of all the ad cat parents
				while ($parent):
					$parents[] = $parent;
					$new_parent = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ));
					$parent = $new_parent->parent;
				endwhile;

				// if parents are found display them
				if(!empty($parents)):
					// flip the array over so we can print out descending
					$parents = array_reverse($parents);
					// for each parent, create a breadcrumb item
					foreach ($parents as $parent):
						$item = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ));
						$url = get_term_link( $item->slug, $taxonomy_name );
						echo '<a href="'.$url.'">'.$item->name.'</a> ' . $delimiter . ' ';
					endforeach;
				endif;
				echo $currentBefore . $term->name . $currentAfter;
			break;

			case is_singular(COLABS_POST_TYPE):
				// get the ad category array
				$term = wp_get_object_terms($post->ID, $taxonomy_name);
        if(!empty($term)):
  				// get the first ad category parent id
  				$parent = $term[0]->parent;
  				// get the first ad category id and put into array
  				$parents[] = $term[0]->term_id;
  				// WP doesn't have a function to grab the top-level term id so we need to
  				// climb up the tree and create a list of all the ad cat parents
  				while ($parent):
  					$parents[] = $parent;
  					$new_parent = get_term_by( 'id', $parent, $taxonomy_name );
  					$parent = $new_parent->parent;
  				endwhile;
  				// if parents are found display them
  				if(!empty($parents)):
  					// flip the array over so we can print out descending
  					$parents = array_reverse($parents);
  					// for each parent, create a breadcrumb item
  					foreach ($parents as $parent):
  						$item = get_term_by( 'id', $parent, $taxonomy_name );
  						$url = get_term_link( $item->slug, $taxonomy_name );

  						echo '<a href="'.$url.'">'.$item->name.'</a> ' . $delimiter . ' ';
  					endforeach;
  				endif;
        endif;
				echo $currentBefore . the_title() . $currentAfter;
			break;

			case is_single():
				$cat = get_the_category();
				$cat = $cat[0];
				echo get_category_parents($cat, TRUE, " $delimiter ");
				echo $currentBefore . the_title() . $currentAfter;
			break;

			case is_category():
				global $wp_query;
				$cat_obj = $wp_query->get_queried_object();
				$thisCat = $cat_obj->term_id;
				$thisCat = get_category($thisCat);
				$parentCat = get_category($thisCat->parent);
				if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
				echo $currentBefore . single_cat_title() . $currentAfter;
			break;

			case is_page():
				// get the parent page id
				$parent_id  = $post->post_parent;
				$breadcrumbs = array();
				if ($parent_id > 0 ) :
					// now loop through and put all parent pages found above current one in array
					while ($parent_id) {
						$page = get_page($parent_id);
						$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
						$parent_id  = $page->post_parent;
					}
					$breadcrumbs = array_reverse($breadcrumbs);
					foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
				endif;
				echo $currentBefore . the_title() . $currentAfter;
			break;

			case is_search():
				echo $currentBefore . __('Search results for', 'colabsthemes') .' &#39;' . get_search_query() . '&#39;' . $currentAfter;
			break;

			case is_tag():
				echo $currentBefore . __('Posts tagged with', 'colabsthemes') .' &#39;' . single_tag_title('', false) . '&#39;' . $currentAfter;
			break;

			case is_author():
				global $author;
				$userdata = get_userdata($author);
				echo $currentBefore . __('About', 'colabsthemes') .'&nbsp;' . $userdata->display_name . $currentAfter;
			break;

			case is_day():
				echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
				echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
				echo $currentBefore . get_the_time('d') . $currentAfter;
			break;

			case is_month():
				echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
				echo $currentBefore . get_the_time('F') . $currentAfter;
			break;

			case is_year():
				echo $currentBefore . get_the_time('Y') . $currentAfter;
			break;

			case is_archive():
        if( !empty($_GET['sort']) && $_GET['sort'] == 'random' )
  				  echo $currentBefore . __('Random Ads', 'colabsthemes') . $currentAfter;
        elseif( !empty($_GET['sort']) && $_GET['sort'] == 'popular' )
  				  echo $currentBefore . __('Popular Ads', 'colabsthemes') . $currentAfter;
				else
  				  echo $currentBefore . __('Latest Ads', 'colabsthemes') . $currentAfter;
			break;

			case is_404():
				echo $currentBefore . __('Page not found', 'colabsthemes') . $currentAfter;
			break;

		endswitch;

		if ( get_query_var('paged') ) {
		  if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() || is_archive() || is_tax() ) echo ' (';
			echo __('Page', 'colabsthemes') . ' ' . get_query_var('paged');
		  if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() || is_archive() || is_tax() ) echo ')';
		}

		echo $after;

	endif;

}

/*-----------------------------------------------------------------------------------*/
/*  Shows how much time is left before the ad expires        */
/*-----------------------------------------------------------------------------------*/
function colabs_timeleft($theTime) {
	$now = strtotime("now");
	$timeLeft = $theTime - $now;

    $days_label = __('days','colabsthemes');
    $day_label = __('day','colabsthemes');
    $hours_label = __('hours','colabsthemes');
    $hour_label = __('hour','colabsthemes');
    $mins_label = __('mins','colabsthemes');
    $min_label = __('min','colabsthemes');
    $secs_label = __('secs','colabsthemes');
    $r_label = __('remaining','colabsthemes');
    $expired_label = __('This ad has expired','colabsthemes');

    if($timeLeft > 0)
    {
    $days = floor($timeLeft/60/60/24);
    $hours = $timeLeft/60/60%24;
    $mins = $timeLeft/60%60;
    $secs = $timeLeft%60;

    if($days == 01) {$d_label=$day_label;} else {$d_label=$days_label;}
    if($hours == 01) {$h_label=$hour_label;} else {$h_label=$hours_label;}
    if($mins == 01) {$m_label=$min_label;} else {$m_label=$mins_label;}

    if($days){$theText = $days . " " . $d_label;
    if($hours){$theText .= ", " .$hours . " " . $h_label;}}
    elseif($hours){$theText = $hours . " " . $h_label;
    if($mins){$theText .= ", " .$mins . " " . $m_label;}}
    elseif($mins){$theText = $mins . " " . $m_label;
    if($secs){$theText .= ", " .$secs . " " . $secs_label;}}
    elseif($secs){$theText = $secs . " " . $secs_label;}}
    else{$theText = $expired_label;}
    return $theText;
}


// show category with price dropdown
if (!function_exists('colabs_dropdown_categories_prices')) {
	function colabs_dropdown_categories_prices( $args = '' ) {
		$defaults = array( 'show_option_all' => '', 'show_option_none' => '','orderby' => 'ID', 'order' => 'ASC','show_last_update' => 0, 'show_count' => 0,'hide_empty' => 1, 'child_of' => 0,'exclude' => '', 'echo' => 1,'selected' => 0, 'hierarchical' => 0,'name' => 'cat', 'class' => 'postform required','depth' => 0, 'tab_index' => 0 );

		$defaults['selected'] = ( is_category() ) ? get_query_var( 'cat' ) : 0;
		$r = wp_parse_args( $args, $defaults );
		$r['include_last_update_time'] = $r['show_last_update'];
		extract( $r );

		$tab_index_attribute = '';
		if ( (int) $tab_index > 0 )
			$tab_index_attribute = " tabindex=\"$tab_index\"";
		$categories = get_categories( $r );
		$output = '';
		if ( ! empty( $categories ) ) {
			$output = "<select name='$name' id='$name' class='$class' $tab_index_attribute>\n";

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

			$output .= colabs_category_dropdown_tree( $categories, $depth, $r );
			$output .= "</select>\n";
		}

		$output = apply_filters( 'wp_dropdown_cats', $output );

		if ( $echo )
			echo $output;

		return $output;
	}
}

// needed for the colabs_dropdown_categories_prices function
function colabs_category_dropdown_tree() {
    $args = func_get_args();
    if ( empty($args[2]['walker']) || !is_a($args[2]['walker'], 'Walker') )
        $walker = new colabs_CategoryDropdown;
    else
        $walker = $args[2]['walker'];
    return call_user_func_array(array( &$walker, 'walk' ), $args );
}

// needed for the colabs_category_dropdown_tree function
class colabs_CategoryDropdown extends Walker {
    var $tree_type = 'category';
    var $db_fields = array ('parent' => 'parent', 'id' => 'term_id');
    function start_el(&$output, $category, $depth, $args) {
		global $colabs_abbr;
        $pad = str_repeat('&nbsp;', $depth * 3);
        $cat_name = apply_filters('list_cats', $category->name, $category);
        $output .= "\t<option class=\"level-$depth\" value=\"".$category->term_id."\">";
        $output .= $pad.$cat_name;
        $output .= ' - ' . get_option($colabs_abbr.'_curr_pay_type_symbol') . get_tax_meta( $category->cat_ID , 'colabs_cat_price_id') . '</option>'."\n";;
    }
}


// category menu drop-down display

/**
* return all the order values we plan on using as hidden payment fields
*
* @since 3.1
*
*/
function colabs_get_order_vals( $order_vals ) {
    // figure out the number of days this ad was listed for
    if ( get_post_meta( $order_vals['post_id'], 'colabs_sys_ad_duration', true ) )
        $order_vals['prune_period'] = get_post_meta( $order_vals['post_id'], 'colabs_sys_ad_duration', true );
	else
	    $order_vals['prune_period'] = get_option( 'colabs_prun_period' );

	$order_vals['item_name'] = sprintf( __( 'Classified ad listing on %s for %s days', 'colabsthemes' ), get_bloginfo('name'), $order_vals['prune_period'] );
    $order_vals['item_number'] = get_post_meta( $order_vals['post_id'], 'colabs_sys_ad_conf_id', true );
    $order_vals['item_amount'] =  get_post_meta( $order_vals['post_id'], 'colabs_sys_total_ad_cost', true );
    $order_vals['notify_url'] = get_bloginfo( 'url' ) . '/index.php?invoice=' . get_post_meta( $order_vals['post_id'], 'colabs_sys_ad_conf_id', true ) . '&amp;aid=' . $order_vals['post_id'];
    $order_vals['return_url'] = CL_ADD_NEW_CONFIRM_URL . '?pid=' . get_post_meta( $order_vals['post_id'], 'colabs_sys_ad_conf_id', true ) . '&amp;aid=' . $order_vals['post_id'];
    $order_vals['return_text'] = __( 'Click here to publish your ad on', 'colabsthemes' ) . ' ' . get_bloginfo( 'name' );

    return $order_vals;
}


/**
* return all the order pack values we plan on using as hidden payment fields
*
* @since 3.1
*
*/
function colabs_get_order_pack_vals( $order_vals ) {
    // lookup the pack info
    $pack = get_pack( $order_vals['pack'] );

    // figure out the number of days this ad was listed for
    // not needed? keeping for safety
    $order_vals['prune_period'] = get_option( 'colabs_prun_period' );

	//setup variables depending on the purchase type
	if ( isset( $pack->pack_name ) && stristr( $pack->pack_status, 'membership' ) ) {

	    $order_vals['item_name'] = sprintf( __( 'Membership on %s for %s days', 'colabsthemes' ), get_bloginfo( 'name' ), $pack->pack_duration );
		$order_vals['item_number'] = stripslashes($pack->pack_name);
		$order_vals['item_amount'] = $pack->pack_membership_price;
		$order_vals['notify_url'] = get_bloginfo( 'url' ) . '/index.php?invoice=' . $order_vals['oid'];
		$order_vals['return_url'] = CL_MEMBERSHIP_PURCHASE_CONFIRM_URL . '?oid=' . $order_vals['oid'];
		$order_vals['return_text'] = __( 'Click here to complete your purchase on', 'colabsthemes' ) . ' ' . get_bloginfo( 'name' );

    } else {

        _e( "Sorry, but there's been an error.", 'colabsthemes' );
        die;

    }

    return $order_vals;
}



//function retreives the membership pack name given a membership pack ID
function get_pack($theID, $type = '', $return = '') {
	global $wpdb, $the_pack;

	if ( stristr($theID, 'pend') )
	    $theID = get_pack_id($theID);

	//if the type is dashboard or ad, then get the assume the ID sent is the postID and packID needs to be obtained
	if ( $type == 'ad' || $type == 'dashboard' )
		$theID = get_pack_id( $theID, $type );

	//make sure the value is a proper MySQL int value
	$theID = intval($theID);

	if ( $theID > 0 )
		$the_pack = $wpdb->get_row( 'SELECT * FROM '.$wpdb->prefix.'colabs_ad_packs WHERE pack_id = '.$theID.'' );

	if ( !empty($return) && !empty($the_pack)) {
		$the_pack = (array)$the_pack;

		if ( $return == 'array' )
		    return $the_pack;
		else
		    return $the_pack[$return];
	}

	return $the_pack;
}

//function send a string and attempt to filter out and return only the actual packID
function get_pack_id($active_pack, $type = '') {
	if ( !empty($type) ) { /*TODO LOOKUP PACK ID FROM POST - Will be possible once pack is stored with posts*/	}
	preg_match('/^pend(?P<pack_id>\w+)-(?P<order_id>\w+)/', $active_pack, $matches);

	if ($matches)
	    return $matches['pack_id'];
	else
	    return $active_pack;
}

//function send a string and attempt to filter out and return only the private order ID
function get_order_id($active_pack) {
	//attempt to match based on "pend" prefix
	preg_match('/^pend(?P<membership_pack_id>\w+)-(?P<private_order_id>\w+)/', $active_pack, $matches);

	//if order id is not foundyet, attempt to match based on option_name prefix
	if ( !isset($matches['private_order_id']) )
		preg_match('/^colabs_order_(?P<user_id>\w+)_(?P<private_order_id>\w+)/', $active_pack, $matches);

	return $matches['private_order_id'];
}

//function send a string and attempt to filter out and return only the user ID from the order
function get_order_userid($active_pack) {
	//attempt to match based on "pend" prefix
	preg_match('/^pend(?P<membership_pack_id>\w+)-(?P<private_order_id>\w+)/', $active_pack, $matches);

	//if order id is not foundyet, attempt to match based on option_name prefix
	if ( !isset($matches['private_order_id']) )
		preg_match('/^colabs_order_(?P<user_id>\w+)_(?P<private_order_id>\w+)/', $active_pack, $matches);

	return $matches['user_id'];
}

//function that retreives a users pending orders
function get_user_orders($user_id = '', $oid = '') {
	global $wpdb;
	$lookup = 'colabs_order';

	if (!empty($user_id))
	    $lookup = 'colabs_order_'.$user_id;

	if (!empty($oid))
	    $lookup = $oid;

	$orders = $wpdb->get_results("SELECT option_name FROM ".$wpdb->prefix."options WHERE option_name LIKE '%".$lookup."%'");

	//currently only expecting 1 order to be available, but programmed to enable easy expansion
	if (isset($orders[0])) {
		//if the order ID is passed, we always return the option string related tothe order
		if (!empty($oid)) return $orders[0]->option_name;
		//if the order ID is not passed, send back an array of all the "orders" for the user
		else return array($orders[0]->option_name);
	}

	//if not returning yet, this value is most likely just "false"
	return $orders;
}

//function that takes a membership pack and returns the proper benefit explanation
function get_pack_benefit($membership, $returnTotal = false) {
	$benefitHTML = '';

	switch ( $membership->pack_type ) {
		case 'percentage':
			if($returnTotal) return number_format(($returnTotal * ($membership->pack_price / 100)),2);
			$benefitHTML .= preg_replace('/.00$/', '', $membership->pack_price).'% '.__('of price','colabsthemes'); //remove decimal when decimal is .00
			break;
		case 'discount':
			if($returnTotal) return number_format(($returnTotal - ($membership->pack_price*1)),2);
			$benefitHTML .= colabs_pos_currency($membership->pack_price).__('\'s less per ad','colabsthemes');
			break;
		case 'required_static':
			if($returnTotal) return number_format(($membership->pack_price*1),2);
			if((float)$membership->pack_price == 0) $benefitHTML .= __('Free Posting','colabsthemes');
			else $benefitHTML .= colabs_pos_currency($membership->pack_price).__(' per ad','colabsthemes');
			$benefitHTML .= ' ('.__('required to post','colabsthemes').')';
			break;
		case 'required_discount':
			if($returnTotal) return number_format(($returnTotal - ($membership->pack_price*1)),2);
			if($membership->pack_price > 0) $benefitHTML .= colabs_pos_currency($membership->pack_price).__('\'s less per ad','colabsthemes');
			$benefitHTML .= ' ('.__('required to post','colabsthemes').')';
			break;
		case 'required_percentage':
			if($returnTotal) return number_format(($returnTotal * ($membership->pack_price / 100)),2);
			if($membership->pack_price < 100) $benefitHTML .= preg_replace('/.00$/', '', $membership->pack_price).'% '.__('of price','colabsthemes'); //remove decimal when decimal is .00
			$benefitHTML .= ' ('.__('required to post','colabsthemes').')';
			break;
		default: //likely 'static'
			if($returnTotal) return number_format(($membership->pack_price*1), 2);
			if((float)$membership->pack_price == 0) $benefitHTML .= __('Free Posting','colabsthemes');
			else $benefitHTML .= colabs_pos_currency($membership->pack_price).__(' per ad','colabsthemes');
	}

	return $benefitHTML;
}

function get_membership_requirement($catID) {
	//if all posts require "required" memberships
	if ( get_option('colabs_required_membership_type') == 'all' ) { return 'all'; }
	//if post requirements are based on category specific requirements
	elseif ( get_option('colabs_required_membership_type') == 'category' ) {
		//check if catID option exists to determine if its a required to post category
		$required_categories = get_option('colabs_required_categories');
		if ( isset($required_categories[$catID])) return $catID;
	}
	//no requirements active
	else return false;
}

//pass the function the MySQL standardized date and retreive a date relative to wordpress GMT and wordpress date and time display options
function colabsthemes_display_date($mysqldate) {
	$display_date = date_i18n( get_option('date_format').' '.get_option('time_format'),strtotime($mysqldate), get_option('gmt_offset') );
	return $display_date;
}

//pass the function a UNIX TIMESTAMP or "Properly Formated Date/Time" and retreive a date formated for MySQL database date field type
//optionally pass a number of days to return a time XX days before or after the date/time sent
function colabsthemes_mysql_date($time, $days = 0) {
	$seconds = 60*60*24*$days;
	$unix_time = strtotime($time)+$seconds;
	$mysqldate = date( 'Y-m-d H:i:s', $unix_time);
	return $mysqldate;
}

function colabsthemes_seconds_to_days($seconds) {
	return ($seconds / 24 / 60 / 60);
}

function colabsthemes_days_between_dates($date1, $date2 = '', $precision = '1') {
	if (empty($date2))
	    $date2 = current_time('mysql');

	//setup the times based on string dates, if dates are not strings return false.
	if ( is_string($date1) )
	    $date1 = strtotime($date1);
	else
	    return false;

	if ( is_string($date2) )
	    $date2 = strtotime($date2);
	else
	    return false;

	$days = round( colabsthemes_seconds_to_days($date1 - $date2), $precision );
	return $days;
}

// checks if a user is logged in, if not redirect them to the login page
function auth_redirect_login() {
    $user = wp_get_current_user();
    if ( $user->ID == 0 ) {
        nocache_headers();
        wp_redirect(get_option('siteurl') . '/wp-login.php?redirect_to=' . urlencode($_SERVER['REQUEST_URI']));
        exit();
    }
}


//setup function to stop function from failing if sm debug bar is not installed
//this allows for optional use of sm debug bar plugin
if (!function_exists('dbug')) { function dbug($args) {} }

// Facebook logout has a bug and it encodes the & to &amp; and WordPress thinks it's s failed nonce.
function colabs_fix_facebook_bug() {
	global $pagenow;
	if ( 'wp-login.php' == $pagenow && isset( $_GET['action'] ) && 'logout' == $_GET['action'] && isset( $_GET['amp;_wpnonce'] ) ) {
		wp_redirect( site_url("/wp-login.php?action=logout&_wpnonce={$_GET['amp;_wpnonce']}") );
		exit;
	}
}
add_action( 'init', 'colabs_fix_facebook_bug' );

function colabs_update_geocode( $post_id, $cat, $lat, $lng ) {
	global $wpdb;

	if ( !$lat || !$lng || !$cat || !$post_id )
		return false;

	$post_id = absint( $post_id );

	$table = $wpdb->prefix . 'colabs_ad_geocodes';

	if ( ! $wpdb->get_var( $wpdb->prepare(
		"SELECT COUNT(*) FROM $table WHERE post_id = %d AND category = %s",
		$post_id, $cat ) ) )
		return colabs_add_geocode( $post_id, $cat, $lat, $lng );

	$lat = floatval( $lat );
	$lng = floatval( $lng );

	$wpdb->update(
		$table,
		array (
			'lat' => $lat,
			'lng' => $lng
		),
		array(
			'post_id' => $post_id,
			'category'     => $cat
		)
	);
	return true;
}

function colabs_add_geocode( $post_id, $cat, $lat, $lng ) {
	global $wpdb;
	$table = $wpdb->prefix . 'colabs_ad_geocodes';
	$post_id = intval( $post_id );
	$lat = floatval( $lat );
	$lng = floatval( $lng );

	if ( $wpdb->get_var( $wpdb->prepare(
		"SELECT COUNT(*) FROM $table WHERE post_id = %d AND category = %s",
		$post_id, $cat ) ) )
		return false;

	$wpdb->insert( $table, array(
		'post_id' => $post_id,
		'category' => $cat,
		'lat' => $lat,
		'lng' => $lng
	) );
	return true;
}

function colabs_get_geocode( $post_id, $cat = '' ) {
	global $wpdb;
	$suppress = $wpdb->suppress_errors();
	if ( $cat )
		$row = $wpdb->get_row( $wpdb->prepare( "SELECT lat, lng FROM %s WHERE post_id = %d AND category = %s LIMIT 1", $wpdb->prefix . 'colabs_ad_geocodes', $post_id, $cat ) );
	else
		$row = $wpdb->get_row( $wpdb->prepare( "SELECT lat, lng FROM %s WHERE post_id = %d LIMIT 1", $wpdb->prefix . 'colabs_ad_geocodes', $post_id ) );

	$wpdb->suppress_errors( $suppress );
	if ( is_object( $row ) )
		$value = array( 'lat' => $row->lat, 'lng' => $row->lng );
	else
		return false;
}

function colabs_do_update_geocode( $meta_id, $post_id, $meta_key, $meta_value ) {
	global $wpdb;
	if ( in_array( $meta_key, array( 'colabs_city', 'colabs_country', 'colabs_state', 'colabs_street' ) ) ) {
		$result = $wpdb->get_results( $wpdb->prepare( "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id = %d AND meta_key IN ('colabs_street','colabs_city','colabs_state','colabs_zipcode','colabs_country')", $post_id ), OBJECT_K );
		$address = '';
		foreach( $result as $cur ) {
			if ( ! empty( $cur->meta_key ) )
				$address .= "{$cur->meta_value}, ";
		}
		$address = rtrim( $address, ', ' );
		if ( $address ) {
			$region = get_option( 'colabs_gmaps_region', 'us' );
			$address = urlencode( $address );
			$geocode = json_decode( wp_remote_retrieve_body( wp_remote_get( "http://maps.googleapis.com/maps/api/geocode/json?address=$address&sensor=false&region=$region" ) ) );
			if ( 'OK' == $geocode->status ) {
				$category = get_the_terms( $post_id, 'ad_cat' );
				colabs_update_geocode( $post_id, $category[0]->name, $geocode->results[0]->geometry->location->lat, $geocode->results[0]->geometry->location->lng );
			}
		}
	}

}
add_action( 'added_post_meta', 'colabs_do_update_geocode', 10, 4 );
add_action( 'updated_post_meta', 'colabs_do_update_geocode', 10, 4 );


//ajax header javascript builder for child categories AJAX dropdown builder
function colabs_ajax_addnew_js_header() {
	global $colabs_abbr;
	$parentPosting = get_option($colabs_abbr.'_ad_parent_posting');
	// Define custom JavaScript function
?>
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function() {
	//if on page load the parent category is already selected, load up the child categories
	jQuery('#catlvl0').attr('level', 0);
	if (jQuery('#catlvl0 #cat').val() > 0) {
		js_colabs_getChildrenCategories(jQuery(this),'catlvl-', 1, '<?php echo $parentPosting; ?>');
	}
	//bind the ajax lookup event to #cat object
	jQuery('#cat').live('change', function(){
		currentLevel = parseInt(jQuery(this).parent().attr('level'));
		js_colabs_getChildrenCategories(jQuery(this), 'catlvl', currentLevel+1, '<?php echo $parentPosting; ?>');

		//rebuild the entire set of dropdowns based on which dropdown was changed
		jQuery.each(jQuery(this).parent().parent().children(), function(childLevel, childElement) {
			if(currentLevel+1 < childLevel) jQuery(childElement).remove();
			if(currentLevel+1 == childLevel) jQuery(childElement).removeClass('hasChild');
			//console.log(childElement);
		});

		//adcategories - find the deepest selected category and assign the value to the "chosenCateory" field
		if(jQuery(this).val() > 0) jQuery('#chosenCategory input:first').val(jQuery(this).val());
		else if(jQuery('#catlvl'+(currentLevel-1)+' select').val() > 0) jQuery('#chosenCategory input:first').val(jQuery('#catlvl'+(currentLevel-1)+' select').val());
		else jQuery('#chosenCategory input:first').val('-1');
	});

	//if on page load the parent category is already selected, load up the child categories
	jQuery('#loclvl0').attr('level', 0);
	if (jQuery('#loclvl0 #loc').val() > 0) {
		js_colabs_getChildrenCategories(jQuery(this),'catlvl-', 1, '<?php echo $parentPosting; ?>');
	}
	//bind the ajax lookup event to #loc object
	jQuery('#loc').live('change', function(){
		//adlocation - find the deepest selected category and assign the value to the "chosenCateory" field
		if(jQuery(this).val() > 0) jQuery('#chosenCategory input#loc').val(jQuery(this).val());
		else if(jQuery('#loclvl'+(currentLevel-1)+' select').val() > 0) jQuery('#chosenCategory input#loc').val(jQuery('#loclvl'+(currentLevel-1)+' select').val());
		else jQuery('#chosenCategory input#loc').val('-1');
    });
});

function js_colabs_getChildrenCategories(dropdown, results_div_id, level, allow_parent_posting) {
	parent_dropdown = jQuery(dropdown).parent();
	category_ID = jQuery(dropdown).val();
	results_div = results_div_id+level;
	if(!jQuery(parent_dropdown).hasClass('hasChild'))
		jQuery(parent_dropdown).addClass('hasChild').parent().append('<div id="'+results_div+'" level="'+level+'" class="childCategory"></div>')

  	jQuery.ajax({
		type: "post",url: "<?php bloginfo( 'wpurl' ); ?>/wp-admin/admin-ajax.php",
		data: {
			action: 'colabs_getChildrenCategories',
			//_ajax_nonce: '<?php //echo $nonce; ?>',
			catID : category_ID
		},
		beforeSend: function() { jQuery('#step1').hide();jQuery('#ad-form-input').addClass('ui-autocomplete-loading').slideDown("fast");}, //show loading just when dropdown changed
		complete: function() {jQuery('#ad-form-input').removeClass('ui-autocomplete-loading'); }, //stop showing loading when the process is complete
		success: function(html){ //so, if data is retrieved, store it in html
			//if no categories are found
			if(html == "") { jQuery('#'+results_div).slideUp("fast"); whenEmpty = true; }
			//child categories found so build and display them
			else {
				jQuery('#'+results_div).html(html).slideDown("fast"); //build html from ajax post
				/* FANCY SELECT BOX ACTIVATOR - UNCOMMENT ONCE ITS READY
				jQuery('#'+results_div+" #cat").selectBox({ menuTransition: 'fade', menuSpeed: 'fast' });
				*/
				jQuery('#'+results_div+" a").fadeIn(); //fade in the new dropdown (selectBox converts to <a>
				whenEmpty = false;
			}

			//always check if go button should be on or off, jQuery parent is used for traveling backup the category heirarchy
			if( (allow_parent_posting == 'true' &&  jQuery('#chosenCategory input:first').val() > 0) ){ jQuery('#step1').fadeIn(); }
			//check for empty category option
			else if(whenEmpty && allow_parent_posting == 'whenEmpty' && jQuery('#chosenCategory input:first').val() > 0) { jQuery('#step1').fadeIn(); }
			//if child category exists, is set, and allow_parent_posting not set to "when empty"
			else if(jQuery('#'+results_div_id+(level-1)).hasClass('childCategory') && jQuery(dropdown).val() > -1 && allow_parent_posting == 'false') { jQuery('#step1').fadeIn(); }
			else {jQuery('#step1').fadeOut(); }

		}
	}); //close jQuery.ajax(
} // end of JavaScript function js_colabs_getChildrenCategories
//]]>
</script>
<?php
} // end of PHP function colabs_ajax_addnew_js_header

if ( !function_exists('colabs_getChildrenCategories') ) :
function colabs_getChildrenCategories() {
	$parentCat = $_POST['catID'];
	$result = '';
	if($parentCat < 1) die($result);
	//$result .= '<!-- Looking for child categories for category ID: '.$parentCat.' -->'.PHP_EOL;

	if(get_categories('taxonomy='.COLABS_TAX_CAT.'&child_of='.$parentCat.'&hide_empty=0')) {
		if (get_option('colabs_price_scheme') == 'category' && get_option('colabs_enable_paypal') == 'true'
	 	&& get_option('colabs_ad_parent_posting') != 'false') {
			$result .= colabs_dropdown_categories_prices('show_option_none='.__('Select one','colabsthemes').'&class=dropdownlist&orderby=name&order=ASC&hide_empty=0&hierarchical=1&taxonomy='.COLABS_TAX_CAT.'&depth=1&echo=0&child_of='.$parentCat);
		}
		else {
			$result .= wp_dropdown_categories('show_option_none='.__('Select one','colabsthemes').'&class=dropdownlist&orderby=name&order=ASC&hide_empty=0&hierarchical=1&taxonomy='.COLABS_TAX_CAT.'&depth=1&echo=0&child_of='.$parentCat)."\n".'<div style="clear:both;">';
		}
	}//end if child categories are found

	//returning empty html response tells our javascript that it failed to find child categories
	else {
		die('');
	}

	//return the result to the ajax post
	die($result);
}
endif;

// add AJAX functions
add_action( 'wp_ajax_nopriv_ajax-tag-search-front', 'colabs_suggest' );
add_action( 'wp_ajax_ajax-tag-search-front', 'colabs_suggest' );
add_action( 'wp_ajax_nopriv_colabs_getChildrenCategories', 'colabs_getChildrenCategories'); //keep for people who allow post before registration
add_action( 'wp_ajax_colabs_getChildrenCategories', 'colabs_getChildrenCategories');


// ajax auto-complete search
function colabs_suggest() {
    global $wpdb;

	$s = $_GET['term']; // is this slashed already?

    if ( isset($_GET['tax']) )
            $taxonomy = sanitize_title($_GET['tax']);
    else
            die('no taxonomy');

    if ( false !== strpos( $s, ',' ) ) {
        $s = explode( ',', $s );
        $s = $s[count( $s ) - 1];
    }
    $s = trim( $s );
    if ( strlen( $s ) < 2 ) {
        die(__('need at least two characters', 'colabsthemes')); // require 2 chars for matching
	}

	$terms = $wpdb->get_col( "
		SELECT t.slug FROM ".$wpdb->prefix."term_taxonomy AS tt INNER JOIN ".
		$wpdb->prefix."terms AS t ON tt.term_id = t.term_id ".
		"WHERE tt.taxonomy = '$taxonomy' ".
		"AND t.name LIKE (
			'%$s%'
		)" .
		"LIMIT 50"
		);
	if(empty($terms)){
		//$results[0] = {"name":"no results"};
		echo json_encode($terms);
		die;
	}else{
		$i = 0;
		foreach ($terms as $term) {
			$results[$i] = get_term_by( 'slug', $term, $taxonomy );
			$i++;
		}
		echo json_encode($results);
		die;
	}
}

// update the image alt and title text on edit ad page. since v3.0.5
function colabs_update_alt_text() {
	foreach ($_POST['attachments'] as $attachment_id => $attachment) :
		if (isset($attachment['image_alt'])) {
			$image_alt = esc_html(get_post_meta($attachment_id, '_wp_attachment_image_alt', true));

			if ($image_alt != esc_html($attachment['image_alt'])) {
				$image_alt = wp_strip_all_tags(esc_html($attachment['image_alt']), true);

        $image_data = & get_post($attachment_id);
          if($image_data):
  				// update the image alt text for based on the id
  				update_post_meta($attachment_id, '_wp_attachment_image_alt', addslashes($image_alt));

  				// update the image title text. it's stored as a post title so it's different to update
  				$post = array();
  				$post['ID'] = $attachment_id;
  				$post['post_title'] = $image_alt;
  				wp_update_post($post);
        endif;
			}
		}
	endforeach;
}

// on ad submission form, check images for valid file size and type
function colabs_validate_image() {
    $error_msg  = array();
    $max_size = (get_option('colabs_max_image_size') * 1024); // 1024 K = 1 MB. convert into bytes so we can compare file size to max size. 1048576 bytes = 1MB.

    while(list($key,$value) = each($_FILES['image']['name'])) {
        $value = strtolower($value); // added for 3.0.1 to force image names to lowercase. some systems throw an error otherwise
        if(!empty($value)) {
            if ($max_size < $_FILES['image']['size'][$key]) {
                $size_diff = number_format(($_FILES['image']['size'][$key] - $max_size)/1024);
                $max_size_fmt = number_format(get_option('colabs_max_image_size'));
                $error_msg[] = '<strong>'.$_FILES['image']['name'][$key].'</strong> '. sprintf( __('exceeds the %s KB limit by %s KB. Please go back and upload a smaller image.', 'colabsthemes'), $max_size_fmt, $size_diff );
            }
            elseif (!colabs_file_is_image($_FILES['image']['tmp_name'][$key])) {
                $error_msg[] = '<strong>'.$_FILES['image']['name'][$key].'</strong> '. __('is not a valid image type (.gif, .jpg, .png). Please go back and upload a different image.', 'colabsthemes');
            }
        }
    }
    return $error_msg;
}

// gives us a count of how many images are associated to an ad
function colabs_count_ad_images($ad_id) {
    $args = array('post_type' => 'attachment', 'numberposts' => -1, 'post_status' => null, 'post_parent' => $ad_id, 'order' => 'ASC', 'orderby' => 'ID');

    // get all the images associated to this ad
    $images = get_posts($args);

    // get the total number of images already on this ad
    // we need it to figure out how many upload fields to show
    $imagecount = count($images);

    // returns a count of array keys so we know how many images currently
    // are being used with this ad.
    return $imagecount;
}

// process each image that's being uploaded
function colabs_process_new_image() {
    global $wpdb;
    $postvals = '';

    for($i=0; $i < count($_FILES['image']['tmp_name']);$i++) {
        if (!empty($_FILES['image']['tmp_name'][$i])) {
            // rename the image to a random number to prevent junk image names from coming in
            $renamed = mt_rand(1000,1000000).".".colabsthemes_find_ext($_FILES['image']['name'][$i]);

            //Hack since WP can't handle multiple uploads as of 2.8.5
            $upload = array( 'name' => $renamed,'type' => $_FILES['image']['type'][$i],'tmp_name' => $_FILES['image']['tmp_name'][$i],'error' => $_FILES['image']['error'][$i],'size' => $_FILES['image']['size'][$i] );

            // need to set this in order to send to WP media
            $overrides = array('test_form' => false);

            // check and make sure the image has a valid extension and then upload it
            $file = colabs_image_upload($upload);

            if ($file) // put all these keys into an array and session so we can associate the image to the post after generating the post id
                $postvals['attachment'][$i] = array( 'post_title' => $renamed,'post_content' => '','post_excerpt' => '','post_mime_type' => $file['type'],'guid' => $file['url'], 'file' => $file['file'] );
        }
    }
    return $postvals;
}

// make sure it's an image file and then upload it
function colabs_image_upload($upload) {
    if (colabs_file_is_image($upload['tmp_name'])) {
        $overrides = array('test_form' => false);
        // move image to the WP defined upload directory and set correct permissions
        $file = wp_handle_upload($upload, $overrides);
    }
    return $file;
}


// delete the image from WordPress
function colabs_delete_image() {
    foreach( (array) $_POST['image'] as $img_id_del ) {
        $img_del = & get_post($img_id_del);

        if ( $img_del->post_type == 'attachment' )
            if ( !wp_delete_attachment($img_id_del, true) )
                wp_die( __('Error in deleting the image.', 'colabsthemes') );
    }
}

// get the uploaded file extension and make sure it's an image
function colabs_file_is_image($path) {
    $info = @getimagesize($path);
    if (empty($info))
        $result = false;
    elseif (!in_array($info[2], array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG)))
        $result = false;
    else
        $result = true;

    return apply_filters('colabs_file_is_image', $result, $path);
}


//get a list of coupons, or details about a single coupon if an Coupon Code is passed
function colabs_get_coupons($couponCode = '') {
    global $wpdb;
    $sql = "SELECT * "
    . "FROM " . $wpdb->prefix . "colabs_coupons ";
    if($couponCode != '')
    $sql .= "WHERE coupon_code='$couponCode' ";
    $sql .= "ORDER BY coupon_id desc";

    $results = $wpdb->get_results($sql);
    return $results;
}

//check coupon code against coupons in the database and return the discount
function colabs_check_coupon_discount($couponCode) {
	//stop if no coupon code is passed or passed empty
	if($couponCode == '') return false;

	//get the coupon
	$results = colabs_get_coupons($couponCode);

	//stop if result is empty or inactive
	if(!$results) return false;
	if($results[0]->coupon_status != 'active') return false;
	if(($results[0]->coupon_use_count >= $results[0]->coupon_max_use_count) && ($results[0]->coupon_max_use_count != 0)) return false;
	if(strtotime($results[0]->coupon_expire_date) < strtotime(date("Y-m-d"))) return false;
	if(strtotime($results[0]->coupon_start_date) > strtotime(date("Y-m-d"))) return false;

	//if coupon exists and is not inactive then return the discount
	return $results[0];
}

//function uses a coupon code by incrimenting its value in the database
function colabs_use_coupon($couponCode) {
	global $wpdb;
        $update =   'UPDATE ' . $wpdb->prefix . 'colabs_coupons ' .
                    "SET coupon_use_count = coupon_use_count + 1 " .
                    "WHERE coupon_code = '$couponCode'";
	$results = $wpdb->query($update);
}


// if an ad is created and doesn't have an expiration date,
// make sure to insert one based on the Ad Listing Period option.
// all ads need an expiration date otherwise they will automatically
// expire. this is common when customers manually create an ad through
// the WP admin new post or when using an automated scrapper script
function colabs_check_expire_date($post_id) {
	global $wpdb;

	// we don't want to add the expires date to blog posts
	if ( get_post_type() != COLABS_POST_TYPE )  {

		// do nothing

	} else {

		// add default expiration date if the expired custom field is blank or empty
		if ( !get_post_meta($post_id, 'colabs_sys_expire_date', true) || (get_post_meta($post_id, 'colabs_sys_expire_date', true) == '') ) :
			$ad_length = get_option('colabs_prun_period');
			if ( !$ad_length ) $ad_length = '365'; // if the prune days is empty, set it to one year
			$ad_expire_date = date_i18n( 'm/d/Y H:i:s', strtotime('+' . $ad_length . ' days') ); // don't localize the word 'days'
			add_post_meta( $post_id, 'colabs_sys_expire_date', $ad_expire_date, true );
		endif;

	}

}

/**
 * RENEW AD LISTINGS : @SC - Allowing free ads to be relisted, call this
 * function and send the ads post id. We will check to make sure its free
 * and relist the ad for the same duration it
 */
if ( !function_exists('colabs_renew_ad_listing') ) :
function colabs_renew_ad_listing ( $ad_id ) {
	$listfee = (float)get_post_meta($ad_id, 'colabs_sys_total_ad_cost', true);

	// protect against false URL attempts to hack ads into free renewal
	if ( $listfee == 0 )	{
		$ad_length = get_post_meta($ad_id, 'colabs_sys_ad_duration', true);
		if ( isset($ad_length) )
			$ad_length = $ad_length;
		else
			$ad_length = get_option('colabs_prun_period');

		// set the ad listing expiration date
		$ad_expire_date = date('m/d/Y H:i:s', strtotime('+' . $ad_length . ' days')); // don't localize the word 'days'

		//now update the expiration date on the ad
		update_post_meta($ad_id, 'colabs_sys_expire_date', $ad_expire_date);
		wp_update_post( array('ID' => $ad_id, 'post_date' => date('Y-m-d H:i:s'), 'edit_date' => true) );
		return true;
	}

	//attempt to relist a paid ad
	else {	return false;	}
}
endif;


// processes the entire ad thumbnail logic for featured ads
if ( !function_exists('colabs_ad_featured_thumbnail') ) :
	function colabs_ad_featured_thumbnail() {
		global $post;

		// go see if any images are associated with the ad
		$images = get_children( array('post_parent' => $post->ID, 'post_status' => 'inherit', 'numberposts' => 1, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'ID') );

		// set the class based on if the hover preview option is set to "true"
		if (get_option('colabs_ad_image_preview') == 'true')	$prevclass = 'preview'; else $prevclass = 'nopreview';

		if ($images) {

			// move over bacon
			$image = array_shift($images);

			// get 50x50 v3.0.5+ image size
			$adthumbarray = wp_get_attachment_image($image->ID, 'sidebar-thumbnail');

			// grab the large image for onhover preview
			$adlargearray = wp_get_attachment_image_src($image->ID, 'large');
			$img_large_url_raw = $adlargearray[0];

			// must be a v3.0.5+ created ad
			if($adthumbarray) {
				echo '<a href="'. get_permalink() .'" title="'. the_title_attribute('echo=0') .'" class="'.$prevclass.'" rel="'.$img_large_url_raw.'">'.$adthumbarray.'</a>';

			// maybe a v3.0 legacy ad
			} else {
				$adthumblegarray = wp_get_attachment_image_src($image->ID, 'thumbnail');
				$img_thumbleg_url_raw = $adthumblegarray[0];
				echo '<a href="'. get_permalink() .'" title="'. the_title_attribute('echo=0') .'" class="'.$prevclass.'" rel="'.$img_large_url_raw.'">'.$adthumblegarray.'</a>';
			}

		// no image so return the placeholder thumbnail
		} else {
			echo '<a href="'. get_permalink() .'" title="'. the_title_attribute('echo=0') .'"><img class="attachment-sidebar-thumbnail" alt="" title="" src="'. get_bloginfo('template_url') .'/images/no-thumb-sm.jpg" /></a>';
		}

	}
endif;


// saves the ad on the template-edit-item.php page template
function colabs_update_listing() {
    global $wpdb;

    // check to see if html is allowed
    if ( get_option('colabs_allow_html') != 'true' )
        $post_content = colabsthemes_filter($_POST['post_content']);
    else
        $post_content = $_POST['post_content'];

    // keep only numeric, commas or decimal values
    if ( !empty($_POST['colabs_price']) )
        $_POST['colabs_price'] = colabsthemes_clean_price( $_POST['colabs_price'] );

    // keep only values and insert/strip commas if needed and put into an array
    if ( !empty($_POST['tags_input']) ) {
        $_POST['tags_input'] = colabsthemes_clean_tags( $_POST['tags_input'] );
        $new_tags = explode( ',', $_POST['tags_input'] );
	}

    // put all the ad elements into an array
    // these are the minimum required fields for WP (except tags)
    $update_ad                      = array();
    $update_ad['ID']                = trim( $_POST['ad_id'] );
    $update_ad['post_title']        = colabsthemes_filter( $_POST['post_title'] );
    $update_ad['post_content']      = trim( $post_content );

    // make sure the WP sanitize_post function doesn't strip out embed & other html
    if ( get_option('colabs_allow_html') == 'true' )
        $update_ad['filter'] = true;

    //print_r($update_ad).' <- new ad array<br>'; // for debugging

    // update the ad and return the ad id
    $post_id = wp_update_post( $update_ad );


    if ( $post_id ) {

		//update post custom taxonomy "ad_tags"
		// keep only values and insert/strip commas if needed and put into an array
		if ( !empty($_POST['tags_input']) ) {
            $_POST['tags_input'] = colabsthemes_clean_tags( $_POST['tags_input'] );
            $new_tags = explode( ',', $_POST['tags_input'] );
            $settags = wp_set_object_terms( $post_id, $new_tags, COLABS_TAX_TAG );
            //echo 'Update Tags or Erro:'.print_r($settags, true);
		}

        // assemble the comma separated hidden fields back into an array so we can save them.
        $metafields = explode( ',', $_POST['custom_fields_vals'] );

      	// loop through all custom meta fields and update values
      	foreach ( $metafields as $name ) {
		
      		if ( !isset($_POST[$name]) ) {
            delete_post_meta($post_id, $name);
          } else if ( is_array($_POST[$name]) ) {
        		delete_post_meta($post_id, $name);
            foreach ( $_POST[$name] as $checkbox_value )
              add_post_meta( $post_id, $name, $checkbox_value );
          } else {
        		update_post_meta( $post_id, $name, $_POST[$name] );
          }
          
      	}	


        $errmsg = '<div class="box-yellow"><b>' . __('Your ad has been successfully updated.','colabsthemes') . '</b> <a href="' . CL_DASHBOARD_URL . '">' . __('Return to my dashboard','colabsthemes') . '</a></div>';

    } else {
        // the ad wasn't updated so throw an error
        $errmsg = '<div class="box-red"><b>' . __('There was an error trying to update your ad.','colabsthemes') . '</b></div>';

    }

    return $errmsg;

}

// display all the custom fields on the single ad page, by default they are placed in the list area
if (!function_exists('colabs_get_ad_details')) {
    function colabs_get_ad_details($postid, $catid, $locationOption = 'list') {
        global $wpdb;
        //$all_custom_fields = get_post_custom($post->ID);
        // see if there's a custom form first based on catid.
        $fid = colabs_get_form_id($catid);

        // if there's no form id it must mean the default form is being used
        if(!($fid)) {

			// get all the custom field labels so we can match the field_name up against the post_meta keys
			$sql = $wpdb->prepare("SELECT field_label, field_name, field_type FROM ". $wpdb->prefix . "colabs_ad_fields");

        } else {

            // now we should have the formid so show the form layout based on the category selected
            $sql = $wpdb->prepare("SELECT f.field_label, f.field_name, f.field_type, m.field_pos "
                     . "FROM ". $wpdb->prefix . "colabs_ad_fields f "
                     . "INNER JOIN ". $wpdb->prefix . "colabs_ad_meta m "
                     . "ON f.field_id = m.field_id "
                     . "WHERE m.form_id = %s "
                     . "ORDER BY m.field_pos asc",
                     $fid);

        }

        $results = $wpdb->get_results($sql);

        if($results) {
            if($locationOption == 'list') {
                    foreach ($results as $result) :
                        // now grab all ad fields and print out the field label and value
                        $post_meta_val = get_post_meta($postid, $result->field_name, true);
                        if (!empty($post_meta_val))
                            if($result->field_type == "checkbox"){
                                $post_meta_val = get_post_meta($postid, $result->field_name, false);
                                echo '<li id="'. $result->field_name .'"><span>' . $result->field_label . ':</span> ' . colabsthemes_make_clickable(implode(", ", $post_meta_val)) .'</li>'; // make_clickable is a WP function that auto hyperlinks urls}
                            }elseif($result->field_name != 'colabs_price' && $result->field_type != "text area"){
                                echo '<li id="'. $result->field_name .'"><span>' . $result->field_label . ':</span> ' . colabsthemes_make_clickable($post_meta_val) .'</li>'; // make_clickable is a WP function that auto hyperlinks urls
                            }
                    endforeach;
                }
                elseif($locationOption == 'content')
                {
                    foreach ($results as $result) :
                        // now grab all ad fields and print out the field label and value
                        $post_meta_val = get_post_meta($postid, $result->field_name, true);
                        if (!empty($post_meta_val))
                            if($result->field_name != 'colabs_price' && $result->field_type == 'text area')
                                echo '<div id="'. $result->field_name .'" class="custom-text-area dotted"><h3>' . $result->field_label . '</h3>' . colabsthemes_make_clickable($post_meta_val) .'</div>'; // make_clickable is a WP function that auto hyperlinks urls

                    endforeach;
                }
                else
                {
                        // uncomment for debugging
                        // echo 'Location Option Set: ' . $locationOption;
                }

        } else {

          echo __('No ad details found.', 'colabsthemes');

        }
    }
}


// give us the custom form id based on category id passed in
// this is used on the single-default.php page to display the ad fields
function colabs_get_form_id($catid) {
    global $wpdb;
    $fid = ''; // set to nothing to make WP notice happy

    // we first need to see if this ad is using a custom form
    // so lets search for a catid match and return the id if found
    $sql = "SELECT ID, form_cats FROM ". $wpdb->prefix . "colabs_ad_forms WHERE form_status = 'active'";

    $results = $wpdb->get_results($sql);

    if($results) {

        foreach ($results as $result) :

            // put the form_cats into an array
            $catarray = unserialize($result->form_cats);

            // now search the array for the ad catid
            if (in_array($catid, $catarray))
                $fid = $result->ID; // when there's a catid match, grab the form id

        endforeach;

        // kick back the form id
        return $fid;

    }

}


// builds the edit ad form on the template-edit-item.php page template
function colabs_edit_ad_formbuilder($results, $getad) {
    global $wpdb;

    // create array before adding custom fields
    $custom_fields_array = array();

    foreach ($results as $result) :

        // get all the custom fields on the post and put into an array
        $custom_field_keys = get_post_custom_keys($getad->ID);

        if(!$custom_field_keys) continue;
            // wp_die('Error: There are no custom fields');

        // we only want key values that match the field_name in the custom field table or core WP fields.
        $field_req = array(
                        'post_content',
                        'post_title',
                        'tags_input',
                        'colabs_location',
                        'colabs_zipcode',
                        'colabs_email',
                        'colabs_website',
                        'colabs_phone',
                    );
        
        //if (in_array($result->field_name, $custom_field_keys) || ($result->field_name == 'post_content') || ($result->field_name == 'post_title') || ($result->field_name == 'tags_input') || $result->field_type == 'checkbox' ) :
        if ( in_array($result->field_name, $custom_field_keys) || in_array($result->field_name, $field_req) || $result->field_type == 'checkbox' ) :
                
            // add each custom field name to an array so we can save them correctly later
            if ( colabsthemes_str_starts_with($result->field_name, 'colabs_'))
              $custom_fields_array[] = $result->field_name;

            // we found a match so go fetch the custom field value
            $post_meta_val = get_post_meta($getad->ID, $result->field_name, true);

            // now loop through the form builder and make the proper field and display the value
            switch($result->field_type) {

            case 'text box':
            ?>
                <li id="list_<?php echo $result->field_name; ?>">
                    <div class="labelwrapper">
                    	<label><?php if ($result->field_tooltip) : ?><a href="#" tip="<?php esc_attr_e($result->field_tooltip); ?>" tabindex="999"><div class="helpico"></div></a><?php endif; ?><?php esc_html_e($result->field_label);?>: <?php if ($result->field_req) echo '<span class="colour">*</span>' ?></label><br />
                        <label class="invalid" for="<?php echo $result->field_name; if(stristr($result->field_name, 'checkbox')) echo '_list' ?>"><?php _e('This field is required.','colabsthemes');?></label>
					</div>
                    <input name="<?php esc_attr_e($result->field_name); ?>" id="<?php esc_attr_e($result->field_name); ?>" type="text" class="text<?php if ($result->field_req) echo ' required'; ?>" style="min-width:200px;" value="<?php if ($result->field_name == 'post_title') { esc_attr_e($getad->post_title); } elseif ($result->field_name == 'tags_input') { echo rtrim(trim(colabs_get_the_term_list($getad->ID, COLABS_TAX_TAG)), ','); } else { esc_attr_e($post_meta_val); } ?>" />
                    <div class="clr"></div>
                </li>
            <?php
            break;

            case 'drop-down':
            ?>
				<li id="list_<?php echo $result->field_name; ?>">
					<div class="labelwrapper">
						<label><?php if ($result->field_tooltip) : ?><a href="#" tip="<?php esc_attr_e($result->field_tooltip); ?>" tabindex="999"><div class="helpico"></div></a><?php endif; ?><?php esc_html_e($result->field_label);?>: <?php if ($result->field_req) echo '<span class="colour">*</span>' ?></label><br />
                        <label class="invalid" for="<?php esc_attr_e($result->field_name); if(stristr($result->field_name, 'checkbox')) echo '_list' ?>"><?php _e('This field is required.','colabsthemes');?></label>
					</div>
                    <select name="<?php esc_attr_e($result->field_name); ?>" class="dropdownlist<?php if ($result->field_req) echo ' required'; ?>">
					<?php if (!$result->field_req) : ?><option value="">-- <?php _e('Select', 'colabsthemes') ?> --</option><?php endif; ?>
                    <?php
                    $options = explode(',', $result->field_values);

                    foreach ($options as $option) :
                    ?>

                        <option style="min-width:177px" <?php if ($post_meta_val == trim($option)) echo 'selected="true"'; ?> value="<?php echo trim(esc_attr($option)); ?>"><?php echo trim(esc_attr($option));?></option>

                    <?php endforeach; ?>

                    </select>
                    <div class="clr"></div>
                </li>

            <?php
            break;

            case 'text area':

            ?>
                <li id="list_<?php echo $result->field_name; ?>">
					<div class="labelwrapper">
                    	<label><?php if ($result->field_tooltip) : ?><a href="#" tip="<?php esc_attr_e($result->field_tooltip); ?>" tabindex="999"><div class="helpico"></div></a><?php endif; ?><?php esc_html_e($result->field_label); ?>: <?php if ($result->field_req) echo '<span class="colour">*</span>' ?></label><br />
                        <label class="invalid" for="<?php esc_attr_e($result->field_name); if (stristr($result->field_name, 'checkbox')) echo '_list' ?>"><?php _e('This field is required.','colabsthemes');?></label>
					</div>
                    <div class="clr"></div>
                    <textarea rows="4" cols="23" class="<?php if ($result->field_req) echo ' required'; ?>" name="<?php esc_attr_e($result->field_name); ?>" id="<?php esc_attr_e($result->field_name); ?>"><?php if ($result->field_name == 'post_content') esc_html_e($getad->post_content); else echo esc_html_e($post_meta_val); ?></textarea>
					<div class="clr"></div>

					<?php if (get_option('colabs_allow_html') == 'true') : ?>
						<script type="text/javascript"> <!--
						tinyMCE.execCommand('mceAddControl', false, '<?php esc_attr_e($result->field_name); ?>');
						--></script>
					<?php endif; ?>

                </li>
            <?php
            break;

			case 'radio':
					$options = explode(',', $result->field_values);
					?>
				<li id="list_<?php esc_attr_e($result->field_name); ?>">
					<div class="labelwrapper">
                    	<label><?php if ($result->field_tooltip) : ?><a href="#" tip="<?php esc_attr_e($result->field_tooltip); ?>" tabindex="999"><div class="helpico"></div></a><?php endif; ?><?php esc_html_e($result->field_label); ?>: <?php if ($result->field_req) echo '<span class="colour">*</span>'; ?></label>
					</div>

					<ol class="radios">

						<?php if(!$result->field_req): ?>
							<li>
								<input type="radio" name="<?php esc_attr_e($result->field_name); ?>" id="<?php esc_attr_e($result->field_name); ?>" class="radiolist" <?php if( (trim($post_meta_val) == trim($option)) || !$post_meta_val ) { echo 'checked="checked"'; } ?> value="">
								<?php echo __('None'); ?>
							</li>
						<?php
						endif;

						foreach ($options as $option) {
						?>
							<li>
								<input type="radio" name="<?php esc_attr_e($result->field_name); ?>" id="<?php esc_attr_e($result->field_name); ?>" value="<?php esc_html_e($option); ?>" class="radiolist <?php if ($result->field_req) echo 'required' ?>" <?php if ( trim($post_meta_val) == trim($option) ) echo 'checked="checked"'; ?>>&nbsp;&nbsp;<?php esc_html_e(trim($option)); ?>
							</li> <!-- #radio-button -->
						<?php
						}
						?>

					</ol>

					<div class="clr"></div>
				</li>



			<?php
			break;

			case 'checkbox':
				$options = explode(',', $result->field_values); 
        // fetch the custom field values as array
        $post_meta_val = get_post_meta($getad->ID, $result->field_name, false);
      ?>

				<li id="list_<?php esc_attr_e($result->field_name); ?>">
					<div class="labelwrapper">
						<label><?php if ($result->field_tooltip) : ?><a href="#" tip="<?php esc_attr_e($result->field_tooltip); ?>" tabindex="999"><div class="helpico"></div></a><?php endif; ?><?php esc_html_e($result->field_label); ?>: <?php if ($result->field_req) echo '<span class="colour">*</span>' ?></label>
					</div>

					<ol class="checkboxes">

						<?php
						$optionCursor = 1;
						foreach ($options as $option) {
						?>
							<li>
								<input type="checkbox" name="<?php esc_attr_e($result->field_name); ?>[]" id="<?php esc_attr_e($result->field_name); echo '_'.$optionCursor++; ?>" value="<?php esc_attr_e($option); ?>" class="checkboxlist <?php if ($result->field_req) echo 'required' ?>" <?php if (is_array($post_meta_val) && in_array(trim($option), $post_meta_val)) echo 'checked="checked"'; ?> />&nbsp;&nbsp;&nbsp;<?php echo trim(esc_html($option)); ?>
							</li> <!-- #checkbox -->
						<?php
						}
						?>

					</ol>

					<div class="clr"></div>
				</li>

			<?php
			break;

            }

        endif;

    endforeach;

	// put all the custom field names into an hidden field so we can process them on save
	$custom_fields_vals = implode( ',', $custom_fields_array );
	?>
	
	<input type="hidden" name="custom_fields_vals" value="<?php echo $custom_fields_vals; ?>" />
	
<?php	
}

// gets the image link for each ad. used in the edit-ads page template
function colabs_get_attachment_link($id = 0, $size = 'thumbnail', $permalink = false, $icon = false, $text = false) {
	$id = intval($id);
	$_post = & get_post( $id );

	// print_r($_post);

	if ( ('attachment' != $_post->post_type) || !$url = wp_get_attachment_url($_post->ID) )
		return __('Missing Attachment', 'colabsthemes');

	if ( $permalink )
		$url = get_attachment_link($_post->ID);

	$post_title = esc_attr($_post->post_title);

	if ( $text ) {
		$link_text = esc_attr($text);
	} elseif ( ( is_int($size) && $size != 0 ) or ( is_string($size) && $size != 'none' ) or $size != false ) {
		$link_text = wp_get_attachment_image($id, $size, $icon);
	} else {
		$link_text = '';
	}

	if( trim($link_text) == '' )
		$link_text = $_post->post_title;

	return apply_filters( 'colabs_get_attachment_link', "<a target='_blank' href='$url' alt='' class='post-gallery' rel='colorbox' title='$post_title'>$link_text</a>", $id, $size, $permalink, $icon, $text );
}

// this ties the uploaded files to the correct ad post and creates the multiple image sizes.
function colabs_associate_images($post_id,$file,$print = false) {
	$image_count = count($file);
	if($image_count > 0 && $print) echo __('Your ad images are now being processed...','colabsthemes').'<br />';
    for ($i=0; $i < count($file);$i++ ) {
        $post_title = esc_attr( get_the_title( $post_id ) );
        $attachment = array( 'post_title' => $post_title, 'post_content' => $file[$i]['post_content'], 'post_excerpt' => $file[$i]['post_excerpt'], 'post_mime_type' => $file[$i]['post_mime_type'], 'guid' => $file[$i]['guid'] );
        $attach_id = wp_insert_attachment( $attachment, $file[$i]['file'], $post_id );

        // create multiple sizes of the uploaded image via WP controls
        wp_update_attachment_metadata( $attach_id, wp_generate_attachment_metadata($attach_id, $file[$i]['file']) );

		if($print) echo sprintf(__('Image number %1$d of %2$s has been processed.','colabsthemes'), $i+1, $image_count).'<br />';

        // this only does a specific resize.
        // image_make_intermediate_size($file, $width, $height, $crop=false)
        // $crop Optional, default is false. Whether to crop image to specified height and width or resize.
        //wp_update_attachment_metadata($attach_id, image_make_intermediate_size($file[$i]['file'], 50, 50, true));
        //wp_update_attachment_metadata($attach_id, image_make_intermediate_size($file[$i]['file'], 25, 25, true));
    }
}

// get all the images associated to the ad and display the
// thumbnail with checkboxes for deleting them
// used on the ad edit page
if (!function_exists('colabs_get_ad_images')) {
    function colabs_get_ad_images($ad_id) {
        $args = array('post_type' => 'attachment', 'numberposts' => -1, 'post_status' => null, 'post_parent' => $ad_id, 'order' => 'ASC', 'orderby' => 'ID');

        // get all the images associated to this ad
        $images = get_posts($args);

        // print_r($images); // for debugging

        // get the total number of images already on this ad
        // we need it to figure out how many upload fields to show
        $imagecount = count($images);

        // make sure we have images associated to the ad
        if ($images) :

            $i = 1;
            $media_dims = '';
            foreach ($images as $image) :

				// go get the width and height fields since they are stored in meta data
				$meta = wp_get_attachment_metadata( $image->ID );
				if (is_array($meta) && array_key_exists('width', $meta) && array_key_exists('height', $meta))
					$media_dims = "<span id='media-dims-".$image->ID."'>{$meta['width']}&nbsp;&times;&nbsp;{$meta['height']}</span> ";
            ?>
				<li class="images">
					<div class="labelwrapper">
                    	<label><?php _e('Image', 'colabsthemes'); ?> <?php echo $i ?>:</label>
					</div>

					<div class="thumb-wrap-edit">
						<?php echo colabs_get_attachment_link($image->ID); ?>
					</div>

					<div class="image-meta">
						<p class="image-delete"><input class="checkbox" type="checkbox" name="image[]" value="<?php echo $image->ID; ?>">&nbsp;<?php _e('Delete Image', 'colabsthemes') ?></p>
						<p class="image-meta"><strong><?php _e('Upload Date:', 'colabsthemes') ?></strong> <?php echo mysql2date( get_option('date_format'), $image->post_date); ?></p>
						<p class="image-meta"><strong><?php _e('File Info:', 'colabsthemes') ?></strong> <?php echo $media_dims ?> <?php echo $image->post_mime_type; ?></p>
					</div>

					<div class="clr"></div>

					<?php // get the alt text and print out the field
						 $alt = get_post_meta($image->ID, '_wp_attachment_image_alt', true); ?>
					<p class="alt-text">
						<div class="labelwrapper">
                        	<label><?php _e('Alt Text:','colabsthemes') ?></label>
						</div>
						<input type="text" class="text" name="attachments[<?php echo $image->ID; ?>][image_alt]" id="image_alt" value="<?php if(count($alt)) echo esc_attr(stripslashes($alt)); ?>" />
					</p>

					<div class="clr"></div>
				</li>
            <?php
            $i++;
			endforeach;

        endif;

        // returns a count of array keys so we know how many images currently
        // are being used with this ad. this value is needed for colabs_ad_edit_image_input_fields()
        return $imagecount;
    }
}


// calculates total number of image input upload boxes
// minus the number of existing images
function colabs_ad_edit_image_input_fields($imagecount) {
    $disabled = '';

    // get the max number of images allowed option
    $maximages = get_option('colabs_num_images');

    // figure out how many image upload fields we need
    $imageboxes = ($maximages - $imagecount);

    // now loop through and print out the upload fields
    for ( $i = 0; $i < $imageboxes; $i++ ) :
		$next = $i + 1;
		if ( $i > 0 ) $disabled = 'disabled="disabled"';
    ?>
        <li>
            <div class="labelwrapper">
				<label><?php _e('Add Image','colabsthemes') ?>:</label>
			</div>
				<?php echo "<input type=\"file\" name=\"image[]\" id=\"upload$i\" class=\"fileupload\" onchange=\"enableNextImage(this,$next)\" $disabled" . ' />'; ?>
            <div class="clr"></div>
        </li>
    <?php
    endfor;
    ?>

    <p class="small"><?php printf(__('You are allowed %s image(s) per ad.','colabsthemes'), $maximages) ?> <?php echo get_option('colabs_max_image_size') ?><?php _e('KB max file size per image.','colabsthemes') ?> <?php _e('Check the box next to each image you wish to delete.','colabsthemes') ?></p>
    <div class="clr"></div>

<?php
}

// gets the ad tags
function colabs_get_the_term_list( $id = 0, $taxonomy, $before = '', $sep = '', $after = '' ) {
    $terms = get_the_terms( $id, $taxonomy );

    if (is_wp_error($terms))
        return $terms;

    if (empty($terms))
        return false;

    foreach ($terms as $term) {
        $link = get_term_link($term, $taxonomy);
        if (is_wp_error($link))
            return $link;
        $term_links[] = $term->name . ', ';
    }

    $term_links = apply_filters( "term_links-$taxonomy", $term_links );

    return $before . join( $sep, $term_links ) . $after;
}

?>