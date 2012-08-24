<?php

/**
 * Bank transfer payment gateway script
 *
 * @package Classifier
 * @author ColorLabs
 * @version 1.0.0
 *
 * @param int $post_id
 * @param text $type 
 *
 */

// payment processing script that is used on the new ad confirmation page
// and also the ad dashboard so ad owners can pay for unpaid ads
function banktransfer_gateway_process( $order_vals ) {
    global $gateway_name, $colabs_abbr, $ref_val;
    
    // if gateway wasn't selected then exit
    if ( $order_vals['colabs_payment_method'] != 'banktransfer' ) 
        return;
        
    // ad listing or membership    
    if ( !empty( $order_vals['post_id'] ) ) {
        $ref_val = $order_vals['post_id'];
        $info_message = __('Please include the following details when sending the bank transfer. Once your transfer has been verified, we will then approve your ad listing.', 'colabsthemes');
        colabs_bank_owner_new_ad_email($ref_val);
    } else {
        $ref_val = $order_vals['oid'];
        $info_message = __('Please include the following details when sending the bank transfer. Once your transfer has been verified, we will then activate your membership.', 'colabsthemes');
        colabs_new_membership_email($ref_val);
        colabs_bank_owner_new_membership_email($ref_val);
    }

    // regardless of what happens, log the transaction
    if (file_exists(TEMPLATEPATH . '/includes/gateways/process.php')){
      include_once (TEMPLATEPATH . '/includes/gateways/process.php');

      $trdata = colabs_prepare_transaction_entry($order_vals);
      if($trdata)
        $tr_id = colabs_add_transaction_entry($trdata);
    }
?>

<h3><?php _e('Your Unique Ad Details', 'colabsthemes') ?></h3>

<p><?php echo $info_message; ?></p>

<p>
    <strong><?php _e('Transaction ID:', 'colabsthemes') ?></strong> <?php echo esc_html( $order_vals['item_number'] ); ?><br />
    <strong><?php _e('Reference #:', 'colabsthemes') ?></strong> <?php echo esc_attr( $ref_val ); ?><br />
    <strong><?php _e('Total Amount:', 'colabsthemes') ?></strong> <?php echo esc_html( $order_vals['item_amount'] ); ?> (<?php echo get_option('colabs_curr_pay_type'); ?>)<br />

</p>

<br /><br />

<h3><?php _e('Bank Transfer Instructions', 'colabsthemes') ?></h3>

<p><?php echo stripslashes( colabsthemes_nl2br( get_option('colabs_bank_instructions') ) ); ?></p>

<p><?php _e('For questions or problems, please contact us directly at', 'colabsthemes') ?> <?php echo get_option('admin_email'); ?></p>


<?php
}
add_action( 'colabs_action_gateway', 'banktransfer_gateway_process', 10, 1 );
?>


