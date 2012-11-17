<?php
/**
 * Reserved for any theme-specific hooks
 *
 * @since 1.0.0
 * @uses add_action() calls to trigger the hooks.
 *
 */
 
/*-----------------------------------------------------------------------------------*/
/* Theme Hook Definitions */
/*-----------------------------------------------------------------------------------*/

// index.php
function colabs_cat_tab_list() { colabs_do_atomic( 'colabs_cat_tab_list' ); }					
function colabs_cat_tab_content() { colabs_do_atomic( 'colabs_cat_tab_content' ); }	

//function colabs_comment_before() { colabs_do_atomic( 'colabs_comment_before' ); }
//function colabs_comment_after() { colabs_do_atomic( 'colabs_comment_after' ); }

// comments.php
function colabs_before_blog_comments() { colabs_do_atomic( 'colabs_before_blog_comments' ); }
function colabs_before_blog_pings() { colabs_do_atomic( 'colabs_before_blog_pings' ); }
function colabs_after_blog_pings() { colabs_do_atomic( 'colabs_after_blog_pings' ); }
function colabs_after_blog_comments() { colabs_do_atomic( 'colabs_after_blog_comments' ); }
function colabs_before_blog_respond() { colabs_do_atomic( 'colabs_before_blog_respond' ); }
function colabs_before_blog_comments_form() { colabs_do_atomic( 'colabs_before_blog_comments_form' ); }
function colabs_blog_comments_form() { colabs_do_atomic( 'colabs_blog_comments_form' ); }
function colabs_after_blog_comments_form() { colabs_do_atomic( 'colabs_after_blog_comments_form' ); }
function colabs_after_blog_respond() { colabs_do_atomic( 'colabs_after_blog_respond' ); }

// comments-ad.php
function colabs_before_comments() { colabs_do_atomic( 'colabs_before_comments' ); }
function colabs_before_pings() { colabs_do_atomic( 'colabs_before_pings' ); }
function colabs_after_pings() { colabs_do_atomic( 'colabs_after_pings' ); }
function colabs_after_comments() { colabs_do_atomic( 'colabs_after_comments' ); }
function colabs_before_respond() { colabs_do_atomic( 'colabs_before_respond' ); }
function colabs_before_comments_form() { colabs_do_atomic( 'colabs_before_comments_form' ); }
function colabs_comments_form() { colabs_do_atomic( 'colabs_comments_form' ); }
function colabs_after_comments_form() { colabs_do_atomic( 'colabs_after_comments_form' ); }
function colabs_after_respond() { colabs_do_atomic( 'colabs_after_respond' ); }

//single-ad.php
function colabsthemes_before_post_content() { colabs_do_atomic( 'colabsthemes_before_post_content' ); }
function colabsthemes_after_post_content() { colabs_do_atomic( 'colabsthemes_after_post_content' ); }

function colabsthemes_before_blog_post_content() { colabs_do_atomic( 'colabsthemes_before_blog_post_content' ); }
function colabsthemes_after_blog_post_content() { colabs_do_atomic( 'colabsthemes_after_blog_post_content' ); }

//Template
//template-add-new.php
function colabsthemes_before_submit() { colabs_do_atomic( 'colabsthemes_before_submit' ); }
function colabsthemes_after_submit() { colabs_do_atomic( 'colabsthemes_after_submit' ); }

//template-member-buy.php
function colabsthemes_before_member() { colabs_do_atomic( 'colabsthemes_before_member' ); }
function colabsthemes_after_member() { colabs_do_atomic( 'colabsthemes_after_member' ); }

function colabsthemes_before_submit_membership() { colabs_do_atomic( 'colabsthemes_before_submit_membership' ); }
function colabsthemes_after_submit_membership() { colabs_do_atomic( 'colabsthemes_after_submit_membership' ); }

/** 
 * called in gateway.php to process the payment
 *
 * @since 1.0.0
 * @param array $order_vals
 *
 */ 
function colabs_action_gateway( $order_vals ) { 
	do_action( 'colabs_action_gateway', $order_vals ); 
}

 
/** 
 * called in step2.php to hook into the payment dropdown
 *
 * @since 1.0.0
 *
 */ 
function colabs_action_payment_method() {
    do_action( 'colabs_action_payment_method' ); 
}


/** 
 * called in theme-gateways.php to hook into the admin gateway options
 *
 * @since 1.0.0
 *
 */ 
function colabs_action_gateway_values() {
    do_action( 'colabs_action_gateway_values' ); 
}


/** 
 * called in template-add-new-confirm.php before update to hook into the confirmation page
 *
 * @since 1.0.0
 *
 */ 
function colabs_add_new_confirm_before_update() {
    do_action( 'colabs_add_new_confirm_before_update' ); 
}


/** 
 * called in template-add-new-confirm.php after update to hook into the confirmation page
 *
 * @since 1.0.0
 *
 */ 
function colabs_add_new_confirm_after_update() {
    do_action( 'colabs_add_new_confirm_after_update' ); 
}


/** 
 * called in process.php to hook into db transaction process
 *
 * @since 1.0.0
 *
 */ 
function colabs_process_transaction_entry() {
    do_action( 'colabs_process_transaction_entry' ); 
}

?>