<?php

global $colabs_abbr;
scb_register_table( 'colabs_pop_daily', $colabs_abbr . '_ad_pop_daily' );
scb_register_table( 'colabs_pop_total', $colabs_abbr . '_ad_pop_total' );

/**
 * Register a table with $wpdb
 *
 * @param string $key The key to be used on the $wpdb object
 * @param string $name The actual name of the table, without $wpdb->prefix
 */
function scb_register_table( $key, $name = false ) {
	global $wpdb;

	if ( !$name )
		$name = $key;

	$wpdb->tables[] = $name;
	$wpdb->$key = $wpdb->prefix . $name;
}

function scb_install_table( $key, $columns, $upgrade_method = 'dbDelta' ) {
	global $wpdb;

	$full_table_name = $wpdb->$key;

	$charset_collate = '';
	if ( $wpdb->has_cap( 'collation' ) ) {
		if ( ! empty( $wpdb->charset ) )
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		if ( ! empty( $wpdb->collate ) )
			$charset_collate .= " COLLATE $wpdb->collate";
	}

	if ( 'dbDelta' == $upgrade_method ) {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( "CREATE TABLE $full_table_name ( $columns ) $charset_collate" );
		return;
	}

	if ( 'delete_first' == $upgrade_method )
		$wpdb->query( "DROP TABLE IF EXISTS $full_table_name;" );

	$wpdb->query( "CREATE TABLE IF NOT EXISTS $full_table_name ( $columns ) $charset_collate;" );
}

function scb_uninstall_table( $key ) {
	global $wpdb;

	$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->$key );
}

/**
 * Module for gathering post view statistics.
 * TODO: cleanup
 */

// get the local time based off WordPress setting
$nowisnow = date('Y-m-d', current_time('timestamp'));

// get the total page views and daily page views for a post
function colabsthemes_stats_counter($post_id) {
	global $wpdb, $nowisnow;

	// get all the post view info to display
	$sql = $wpdb->prepare("
		SELECT t.postcount AS total, count(d.postcount) AS today
		FROM $wpdb->colabs_pop_total AS t
		INNER JOIN $wpdb->colabs_pop_daily AS d ON t.postnum = d.postnum
		WHERE t.postnum = %d AND d.time = %s GROUP BY total
	", $post_id, $nowisnow);

	$results = $wpdb->get_row($sql);

	if($results)
		echo number_format($results->total) . '&nbsp;<strong>' .__('total views', 'colabsthemes') . '</strong>, ' . number_format($results->today) . '&nbsp;<strong>' .__('today', 'colabsthemes') .'</strong>';
	else
		echo '<strong>' . __('No views yet', 'colabsthemes') . '</strong>';
}

// record the page view
function colabsthemes_stats_update($post_id) {
	global $wpdb, $colabs_abbr, $nowisnow;

	$thepost = get_post($post_id);

	if ($thepost->post_author==get_current_user_id()) return;

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
	update_post_meta($post_id, $colabs_abbr.'_daily_count', $row->today);
	update_post_meta($post_id, $colabs_abbr.'_total_count', $row->total);
}

/**
 *
 * Keeps track of ad views for daily and total
 * @author ColorLabs
 *
 *
 */

// sidebar widget showing overall popular ads
function colabs_todays_overall_count_widget($post_type, $limit) {
    global $wpdb, $nowisnow;

	// get all the post view info to display
	$sql = $wpdb->prepare( "SELECT t.postcount, p.ID, p.post_title
			FROM $wpdb->colabs_pop_total AS t
			INNER JOIN $wpdb->posts AS p ON p.ID = t.postnum
			WHERE t.postcount > 0
			AND p.post_status = 'publish' AND p.post_type = %s
			ORDER BY t.postcount DESC LIMIT %d", $post_type, $limit );

	$results = $wpdb->get_results($sql);

	//echo $sql;

    echo '<ul class="pop">';

	// must be overall views
	if ($results) {

        foreach ($results as $result)
			echo '<li><a href="'.get_permalink($result->ID).'">'.$result->post_title.'</a> ('.number_format($result->postcount).'&nbsp;'.__('views', 'colabsthemes') .')</li>';

    } else {

		echo '<li>' . __('No ads viewed yet.', 'colabsthemes') . '</li>';

	}

	echo '</ul>';
}

// sidebar widget showing today's popular ads
function colabs_todays_count_widget($post_type, $limit) {
    global $wpdb, $nowisnow;

	// get all the post view info to display
	$sql = $wpdb->prepare( "SELECT t.postcount, p.ID, p.post_title
			FROM $wpdb->colabs_pop_daily AS t
			INNER JOIN $wpdb->posts AS p ON p.ID = t.postnum
			WHERE time = %s
			AND t.postcount > 0 AND p.post_status = 'publish' AND p.post_type = %s
			ORDER BY t.postcount DESC LIMIT %d", $nowisnow, $post_type, $limit );

	$results = $wpdb->get_results($sql);

	echo '<ul class="pop">';

	// must be views today
    if ($results) {

        foreach ($results as $result)
			echo '<li><a href="'.get_permalink($result->ID).'">'.$result->post_title.'</a> ('.number_format($result->postcount).'&nbsp;'.__('views', 'colabsthemes') .')</li>';

    } else {

			echo '<li>' . __('No ads viewed yet.', 'colabsthemes') . '</li>';
	}

	echo '</ul>';

}

?>
