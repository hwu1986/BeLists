<?php
/**
 * This is step 2 of 3 for the ad submission form
 * 
 * @package ClassiPress
 * @subpackage New Ad
 * @author AppThemes
 *
 * here we are processing the images and gathering all the post values.
 * using sessions would be the optimal way but WP doesn't play nice so instead
 * we take all the form post values and put them into an associative array
 * and then store it in the wp_options table as a serialized array. essentially
 * we are using the wp_options table as our session holder and can access
 * the keys and values later and process the ad in step 3
 *
 */

global $current_user, $wpdb;

// check to see if there are images included
// then valid the image extensions
if ( !empty($_FILES['image']) ) 
    $error_msg = colabs_validate_image();

// check to see is ad pack specified for fixed price option
if ( get_option('colabs_charge_ads') == 'true' && get_option('colabs_price_scheme') == 'single' && !isset($_POST['ad_pack_id']) ) 
    $error_msg[] = __('Error: no ad pack has been defined. Please contact the site administrator.', 'colabsthemes');
?>

<div class="row main-container">

<?php

// images are valid
if ( !$error_msg ) {

    // create the array that will hold all the post values
    $postvals = array();

    // upload the images and put into the new ad array
    if ( !empty($_FILES['image']) ) 
        $postvals = colabs_process_new_image();

    // put all the posted form values into an array
    foreach ( $_POST as $key => $value ) 
        if(!is_array($_POST[$key]))
            $postvals[$key] = colabsthemes_clean($value);
        else
            $postvals[$key] = $value;

    // keep only numeric, commas or decimal values
    if ( !empty($_POST['colabs_price']) ) {
        $postvals['colabs_price'] = colabsthemes_clean_price($_POST['colabs_price']);
		$_POST['colabs_price'] = $postvals['colabs_price'];
	}
    
    // keep only values and insert/strip commas if needed
    if ( !empty($_POST['tags_input']) ) {
        $postvals['tags_input'] = colabsthemes_clean_tags($_POST['tags_input']);
		$_POST['tags_input'] = $postvals['tags_input'];
	}

    // store the user IP address, ID for later
    $postvals['colabs_sys_userIP'] = colabsthemes_get_ip();
    $postvals['user_id'] = $current_user->ID;

    // see if the featured ad checkbox has been checked
    if ( isset($_POST['featured_ad']) ) {
        $postvals['featured_ad'] = $_POST['featured_ad'];
        // get the featured ad price into the array
        $postvals['colabs_sys_feat_price'] = get_option('colabs_sys_feat_price');
    }

    // calculate the ad listing fee and put into a variable
	if(isset($_POST['ad_pack_id'])) $post_ad_pack_id = $_POST['ad_pack_id']; else $post_ad_pack_id = '';
    if ( get_option('colabs_charge_ads') == 'true' )
        $postvals['colabs_sys_ad_listing_fee'] = colabs_ad_listing_fee($_POST['cat'], $post_ad_pack_id, $_POST['colabs_price']);

    // check to prevent "Notice: Undefined index:" on php strict error checking. get ad pack id and lookup length
    $adpackid = '';
    if ( isset($_POST['ad_pack_id']) ) {
        $adpackid = $_POST['ad_pack_id'];
        $postvals['pack_duration'] = colabs_get_ad_pack_length($adpackid);
    }

	//check if coupon code was entered, then check if coupon exists and is active
	if ( isset($_POST['colabs_coupon_code']) ) {
		$coupon = colabs_check_coupon_discount($_POST['colabs_coupon_code']);
		
		//if $coupon has any results
		if ( $coupon ) {
			$postvals['colabs_coupon_type'] = $coupon->coupon_discount_type;
			$postvals['colabs_coupon'] = $coupon->coupon_discount;
		}
		//if coupon is entered but not valid, display proper error.
		elseif ( $_POST['colabs_coupon_code'] != '' ) {
			$postvals['colabs_coupon_type'] = '';
			$coupon_code_name = $_POST['colabs_coupon_code'];
			$postvals['colabs_coupon'] = '<span class="error-coupon">'. __("Coupon code '$coupon_code_name' is not active or does not exist.", 'colabsthemes') . '</span>';
		}

	}
	//if coupon was not entered, leave array unset
	else {
		$coupon = array();
	}
	
    // calculate the total cost of the ad
	if ( isset($postvals['colabs_sys_feat_price']) )
    	$postvals['colabs_sys_total_ad_cost'] = colabs_calc_ad_cost($_POST['cat'], $adpackid, $postvals['colabs_sys_feat_price'], $_POST['colabs_price'], $coupon);
	else $postvals['colabs_sys_total_ad_cost'] = colabs_calc_ad_cost($_POST['cat'], $adpackid, 0, $_POST['colabs_price'], $coupon);
	
	//UPDATE TOTAL BASED ON MEMBERSHIP
	//check for current users active membership pack and that its not expired
	if ( !empty($current_user->active_membership_pack) && colabsthemes_days_between_dates($current_user->membership_expires) > 0 ) {
		$postvals['colabs_membership_pack'] = get_pack($current_user->active_membership_pack);
		//update the total cost based on the membership pack ID and current total cost
		$postvals['colabs_sys_total_ad_cost'] = get_pack_benefit($postvals['colabs_membership_pack'], $postvals['colabs_sys_total_ad_cost']);
    //add featured cost to static pack type
    if ( isset($postvals['colabs_sys_feat_price']) && in_array($postvals['colabs_membership_pack']->pack_type, array('required_static', 'static')) )
      $postvals['colabs_sys_total_ad_cost'] += $postvals['colabs_sys_feat_price'];
	}
	
  // prevent from minus prices if bigger discount applied	
	if ( $postvals['colabs_sys_total_ad_cost'] < 0 )
    $postvals['colabs_sys_total_ad_cost'] = '0.00';
	
	
    
    // Debugging section
    //echo '$_POST ATTACHMENT<br/>';
    //print_r($postvals['attachment']);

    //echo '$_POST PRINT<br/>';
    //print_r($_POST);

    //echo '<br/><br/>$postvals PRINT<br/>';
    //print_r($postvals);

    // now put the array containing all the post values into the database
    // instead of passing hidden values which are easy to hack and so we
    // can also retrieve it on the next step
    $option_name = 'colabs_'.$postvals['oid'];
    update_option( $option_name, $postvals );

    ?>

    <?php colabsthemes_before_submit(); ?>
    
    <div id="step2">
    
    <div class="entry-content">
      <h4><?php _e('Review Your Order','colabsthemes');?></h4>
      <p></p>
    </div>
    
    <div class="row form-review-listing">
    
    <form name="mainform" id="mainform" class="form_step" action="" method="post" enctype="multipart/form-data">
        
        <div class="form-details">
        <?php echo '<hr class="bevel" /><h5><span>' . __('Listing Details','colabsthemes') . '</span>'; ?>
        <?php echo '<span>' . __('Payment Details','colabsthemes') . '</span></h5><hr class="bevel" />'; ?>
        <?php echo colabs_show_review( $postvals ); ?>
        </div><!--/.form-details-->
        
        <div class="pad10"></div>
    
        <div class="license"><?php echo get_option('colabs_ads_tou_msg'); ?></div>
        
            <div class="input-bordered">
                <label><?php _e('Rules &amp; Guidelines','colabsthemes');?></label>
                <input id="term_condition" name="term_condition" value="1" type="checkbox" />
                <?php _e('By ticking this, you agree to our terms and conditions.','colabsthemes'); ?><br/>
                <?php _e('Your IP address has been logged for security purposes:','colabsthemes'); ?> <?php echo $postvals['colabs_sys_userIP']; ?>
            </div>
              
            <p class="btn2">
                <input type="button" name="goback" class="btn btn-primary" value="<?php _e('Go back','colabsthemes') ?>" onclick="history.back()" />
                <input type="submit" name="step2" id="step2" class="btn btn-primary" value="<?php _e('Proceed ','colabsthemes'); ?> &rsaquo;&rsaquo;" style="display: none;" />
            </p>
            
        <input type="hidden" id="oid" name="oid" value="<?php echo $postvals['oid']; ?>" />

    </form><!--/#mainform-->

    </div>
    <!-- /.form-submit-listing -->
    
    <?php colabsthemes_after_submit(); ?>
    
    </div><!--/#step2-->
    
<?php

} else {

?>
    <header class="entry-header">
      <h2 class="dotted"><?php _e('An Error Has Occurred', 'colabsthemes') ?></h2>
      <p></p>
    </header>
    
    <div class="thankyou">
        <p><?php echo colabsthemes_error_msg( $error_msg ); ?></p>
        <input type="button" name="goback" class="btn_orange" value="&lsaquo;&lsaquo; <?php _e('Go Back', 'colabsthemes') ?>" onclick="history.back()" />
    </div>

<?php
}
?>

</div>
<!-- /.main-container -->

