<?php
/**
 * This is step 3 of 3 for the ad submission form
 * 
 * @package ClassiPress
 * @subpackage New Ad
 * @author AppThemes
 *
 *
 */


global $current_user, $wpdb;

// now get all the ad values which we stored in an associative array in the db
// first we do a check to make sure this db session still exists and then we'll
// use this option array to create the new ad below
$advals = get_option( 'colabs_'.$_POST['oid'] );

if ( isset( $_POST['colabs_payment_method'] ) ) 
    $advals['colabs_payment_method'] = $_POST['colabs_payment_method'];
else 
    $advals['colabs_payment_method'] = 'banktransfer';

?>

<div class="row main-container">

<?php

// check and make sure the form was submitted from step 2 and the hidden oid matches the oid in the db
// we don't want to create duplicate ad submissions if someone reloads their browser
if ( isset( $_POST['step2'] ) && isset( $advals['oid'] ) && ( strcasecmp( $_POST['oid'], $advals['oid'] ) == 0 ) ) {
?>

    <?php colabsthemes_before_submit(); ?>
    
    <div id="step3"></div><!-- /#step3 -->
    
    <div class="entry-content">
      <h4>
       <?php 
        if ( get_option('colabs_charge_ads') == 'true' ) 
            _e('Make Payment', 'colabsthemes'); 
        else 
            _e('Ad Listing Received', 'colabsthemes'); 
        ?>      
      </h4>
      <p></p>
    </div>

    
    <div class="processlog">
    <?php 
        // insert the ad and get back the post id
    	$post_id = colabs_add_new_listing( $advals );
    ?>
    </div>
    <div class="thankyou">
    
    
    <?php
    
    //incriment coupon code count only if total ad price was not zero
    if (isset($advals['colabs_coupon_code']) && colabs_check_coupon_discount($advals['colabs_coupon_code']) )
    	colabs_use_coupon($advals['colabs_coupon_code']);
    	
    // call in the selected payment gateway as long as the price isn't zero
    if ( (get_option('colabs_charge_ads') == 'true') && ($advals['colabs_sys_total_ad_cost'] != 0) ) {
    	
    	//load payment gateway page to process checkout
        include_once ( TEMPLATEPATH . '/includes/gateways/gateway.php' );
    
    } else {
    
    // otherwise the ad was free and show the thank you page.
        // get the post status
        $the_post = get_post( $post_id ); 
    
        // check to see what the ad status is set to
        if ( $the_post->post_status == 'pending' ) {
    
            // send ad owner an email
            colabs_owner_new_ad_email( $post_id );
    
        ?>
    
            <h3><?php _e('Thank you! Your ad listing has been submitted for review.','colabsthemes') ?></h3>
            <p><?php _e('You can check the status by viewing your dashboard.','colabsthemes') ?></p>
    
        <?php } else { ?>
    
            <h3><?php _e('Thank you! Your ad listing has been submitted and is now live.','colabsthemes') ?></h3>
            <p><?php _e('Visit your dashboard to make any changes to your ad listing or profile.','colabsthemes') ?></p>
            <a href="<?php echo get_permalink($post_id); ?>"><?php _e('View your new ad listing.','colabsthemes') ?></a>
    
        <?php } ?>
    
    
    </div> <!-- /thankyou -->
    
    <?php
    }
        
    // send new ad notification email to admin
    if ( get_option('colabs_new_ad_email') == 'true' || $advals['colabs_payment_method'] == 'banktransfer' )
        colabs_new_ad_email( $post_id );
    
    // remove the temp session option from the database
    delete_option( 'colabs_'.$_POST['oid'] );
    
    colabsthemes_after_submit();
    
    } else {
    ?>
    
    <header class="entry-header">
      <h2 class="dotted"><?php _e('An Error Has Occurred', 'colabsthemes') ?></h2>
      <p></p>
    </header>
    
    <div class="thankyou">
        <p><?php _e('Your session has expired or you are trying to submit a duplicate ad. Please start over.','colabsthemes') ?></p>
    </div>
    
    <?php } ?>
    
    <div class="pad100"></div>
  
  </div>
  <!-- /.main-container -->