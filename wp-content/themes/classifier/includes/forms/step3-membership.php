<?php
global $wpdb, $order, $current_user, $colabs_user_orders, $colabs_user_recent_order;

//check to make sure the user has an order already setup, othrewise the page was refreshed or page hack was attempted
if(count($colabs_user_orders) > 0) {
?>

   <?php colabsthemes_before_submit_membership(); ?>
    
   <div id="step3"></div>

   <h3><?php if ($_POST['total_cost'] > 0) { echo __('Final Step','colabsthemes'); } else { echo __('Membership Updated','colabsthemes'); } ?></h3>

    <?php
    // call in the selected payment gateway as long as the price isn't zero
    if ($order['total_cost'] > 0) :
        include_once (TEMPLATEPATH . '/includes/gateways/gateway.php');
	
	//process the "free" orders on this page, the payment gateway orders will be processed on tpl-membership-purchase.php
	else : 
		$order_processed = colabsthemes_process_membership_order($current_user, $order);
	?>

		<h4><?php _e('Your order has been completed and your membership status should now be active.','colabsthemes') ?></h4>

		<p><?php _e('Visit your dashboard to review your membership status details.','colabsthemes') ?></p>
		
		<ul class="membership-pack">
			<li><strong><?php _e('Membership Pack','colabsthemes')?>:</strong> <?php echo stripslashes($order_processed['pack_name']); ?></li>
			<li><strong><?php _e('Membership Expires','colabsthemes')?>:</strong> <?php echo colabsthemes_display_date($order_processed['updated_expires_date']); ?></li>
		</ul>
        

	<?php do_action('colabsthemes_after_membership_confirmation'); ?>

    <?php
		// remove the order option from the database because the free order was processed
		delete_option($colabs_user_recent_order);
	
    endif;

		// send new membership notification email to admin
		//if (get_option('colabs_new_membership_email') == 'true' || $_POST['colabs_payment_method'] == 'banktransfer')
		//    colabs_new_membership_email($order['order_id']);
		
?>

<?php

} else {

?>

    <h3><?php _e('An Error Has Occurred','colabsthemes') ?></h3>

    <p><?php _e('Your session or order has expired or we cannot cannot find your order in our systems. Please start over to create a valid membership order.','colabsthemes') ?></p>


<?php

}

?>


    <?php colabsthemes_after_submit_membership(); ?>