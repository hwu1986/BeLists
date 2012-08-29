<?php
/*
Template Name: Membership Confirm
*/

/**
 * This script is the landing page after payment has been processed
 * by PayPal or other gateways. It is expecting a unique ad id which
 * was randomly generated during the ad submission. It is stored in
 * the colabs_sys_ad_conf_id custom field. If this page is loaded and no
 * matching ad id is found or the ad is already published then
 * show an error message instead of doing any db updates
 *
 * @package Classifier
 * @author ColorLabs
 * @version 1.0.0
 *
 */

// if not logged in, redirect to login page
auth_redirect_login();

//otherwise load step functions file with functions required to process the order
include_once (TEMPLATEPATH . '/includes/forms/step-functions.php');

global $wpdb, $current_user;

$order = get_user_orders($current_user->ID, $_REQUEST['oid']);
//if the order was found by OID, setup the order details into the $order variable
if(isset($order) && $order) $order = get_option($order);

//make sure the order sent from payment gateway is logged in the database and that the current user created it
if(isset($order['order_id']) && $order['order_id'] == $_REQUEST['oid'] && $order['user_id'] == $current_user->ID) {
	$order_processed = colabsthemes_process_membership_order($current_user, $order);
}
else {
	$order_processed = false;
	// check and make sure this transaction hasn't already been added
    $sql = "SELECT * "
         . "FROM " . $wpdb->prefix . "colabs_order_info "
         . "WHERE custom = '".$wpdb->escape(colabsthemes_clean($_REQUEST['oid']))."' LIMIT 1";

    $results = $wpdb->get_row($sql);

	if($results) $order_processed = 'IPN';
	
}


?>

<?php get_header(); ?>


<?php if ( function_exists('colabs_cl_breadcrumb') ) colabs_cl_breadcrumb(); ?>

<!-- #Main Starts -->
<?php colabs_main_before(); ?>
    <div class="row main-container">

        <div class="main-content col9">

            
            <header class="entry-header">
			  <h2><?php the_title(); ?></h2>
			</header>
			
			<div <?php post_class(); ?>>

            <div id="step3"></div>

			<?php
                // already processed order, most likely processed by IPN
                if($order_processed == 'IPN') { 
			?>

                  <h2 class="dotted"><?php _e('Thank You!','colabsthemes'); ?></h2>

                  <div class="thankyou">

                    <h3><?php _e('Your payment has been processed and your membership status should now be active.','colabsthemes'); ?></h3>

                    <p><?php echo sprintf(__('Visit your <a href="%1$s">dashboard</a> to review your membership status details.','colabsthemes'), CL_DASHBOARD_URL); ?></p>

                 </div>

            <?php
				}
                // only proceed is order was processed correctly
                elseif($order_processed) { 
				
		        if (file_exists(TEMPLATEPATH . '/includes/gateways/process.php'))
		            include_once (TEMPLATEPATH . '/includes/gateways/process.php');
			?>

                  <h2><?php _e('Thank You!','colabsthemes'); ?></h2>

                  <div class="thankyou">

                    <h3><?php _e('Your payment has been processed and your membership status should now be active.','colabsthemes') ?></h3>

                    <p><?php _e('Visit your dashboard to review your membership status details.','colabsthemes') ?></p>
                    
                    <ul class="membership-pack">
                        <li><strong><?php _e('Membership Pack','colabsthemes')?>:</strong> <?php echo stripslashes($order_processed['pack_name']); ?></li>
                        <li><strong><?php _e('Membership Expires','colabsthemes')?>:</strong> <?php echo colabsthemes_display_date($order_processed['updated_expires_date']); ?></li>
                        <li><a href="<?php echo CL_MEMBERSHIP_PURCHASE_URL; ?>"><?php _e('Renew or Extend Your Membership Pack','colabsthemes'); ?></a></li>
                    </ul>

                  </div>

            <?php } else { ?>

                  <h2 ><?php _e('An Error Has Occurred','colabsthemes') ?></h2>

                  <div class="thankyou">

                      <p><?php _e('There was a problem processing your membership or payment was not successful. This error can also occur if you refresh the payment confirmation page. If you believe your order was not completed successfully, please contact the site administrator.','colabsthemes') ?></p>

                 </div>

            <?php } ?>


            </div>
       
        </div><!-- /.main-content -->  
		
		<?php get_sidebar('user'); ?>
		
    </div><!-- /.main-container -->
	
<?php colabs_main_after(); ?>
  
<?php get_footer(); ?>

