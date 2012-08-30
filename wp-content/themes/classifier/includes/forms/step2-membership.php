<?php
global $wpdb, $current_user;

$coupon = array();
/* WAITING FOR COUPONS TO BE UPGRADED FOR USE ON MEMBERSHIPS
//check if coupon code was entered, then check if coupon exists and is active
if(isset($_POST['colabs_coupon_code'])) {
	$coupon = colabs_check_coupon_discount($_POST['colabs_coupon_code']);
	
	//if $coupon has any results
	if($coupon) {
		$postvals['colabs_coupon_type'] = $coupon->coupon_discount_type;
		$postvals['colabs_coupon'] = $coupon->coupon_discount;
	}
	//if coupon is entered but not valid, display proper error.
	elseif($_POST['colabs_coupon_code'] != '') {
		$postvals['colabs_coupon_type'] = '';
		$coupon_code_name = $_POST['colabs_coupon_code'];
		$postvals['colabs_coupon'] = '<span class="error-coupon">'. __("Coupon code '$coupon_code_name' is not active or does not exist.", 'colabsthemes') . '</span>';
	}
}
*/

?>

    <?php colabsthemes_before_submit_membership(); ?>
    
    <div id="step2"></div>

      <h3><?php _e('Review Your Membership Purchase','colabsthemes');?></h3>

				<?php if(!isset($active_membership->pack_id) || empty($active_membership->pack_id)): $extend = ''; ?>
				<?php elseif($active_membership->pack_id == $membership->pack_id): $extend = __('more','colabsthemes').' '; ?>
				<?php else: $extend = ''; ?>
				<div class="error" style="text-align:center;">
					<?php printf( __('Your Current Membership (%1$s)  will be canceled upon purchase. This membership still has %2$s days remaining.','colabsthemes'), stripslashes($active_membership->pack_name), colabsthemes_days_between_dates($current_user->membership_expires) ); ?>
                </div>
                <?php endif; ?>

            <form name="mainform" id="mainform" class="form_step" action="" method="post" enctype="multipart/form-data">

                <ol>
                
                <li>
                	<div class="labelwrapper"><label><strong><?php _e('Membership','colabsthemes'); if($extend) echo ' '.__('Renewal','colabsthemes'); ?>:</strong></label></div>
	                <div id="active_membership_pack"><?php echo $membership->pack_name; ?></div>
                    <div class="clr"></div>
                </li>
                <li>
                	<div class="labelwrapper"><label><strong><?php _e('Membership Benefit:', 'colabsthemes'); ?></strong></label></div>
	                <div id="active_membership_pack"><?php echo get_pack_benefit($membership); ?></div>
                    <div class="clr"></div>
                </li>
                <li>
                	<div class="labelwrapper"><label><strong><?php _e('Membership Length:', 'colabsthemes'); ?></strong></label></div>
	                <div id="active_membership_pack"><?php echo $membership->pack_duration.' '.$extend.__('days','colabsthemes'); ?></div>
                    <div class="clr"></div>
                </li>
                
                <?php if($extend): ?>
                <li>
                	<div class="labelwrapper"><label><strong><?php _e('Previous Expiration:', 'colabsthemes'); ?></strong></label></div>
	                <div id="active_membership_pack"><?php echo colabsthemes_display_date($current_user->membership_expires); ?></div>
                    <div class="clr"></div>
                </li>
                <li>
                	<div class="labelwrapper"><label><strong><?php _e('New Expiration:', 'colabsthemes'); ?></strong></label></div>
	                <div id="active_membership_pack">
					<?php 
						if ($membership->pack_membership_price > 0) 
							echo colabsthemes_display_date(colabsthemes_mysql_date($current_user->membership_expires, $membership->pack_duration)); 
						else 
							echo colabsthemes_display_date(colabsthemes_mysql_date(current_time('mysql'), $membership->pack_duration)); 
					?></div>
                    <div class="clr"></div>
                </li>                
                <?php endif; ?>
                
                <li>
                    <div class="labelwrapper">
                        <label><?php _e('Membership Purchase Fee','colabsthemes');?>:</label>
                    </div>
                    <div id="review"><?php if ($membership->pack_membership_price > 0) { echo colabs_pos_price($membership->pack_membership_price); } else { echo __('FREE', 'colabsthemes'); } ?></div>
                    <div class="clr"></div>
                </li>
            
                <?php if(isset($postvals['colabs_coupon_type'])) : ?>
                    <li>
                        <div class="labelwrapper">
                            <label><?php _e('Coupon','colabsthemes');?>:</label>
                        </div>
                        <?php if($postvals['colabs_coupon_type'] != '%') : ?>
                        <div id="review"><?php echo $postvals['colabs_coupon_type'] . $postvals['colabs_coupon']; ?></div>
                        <?php else : ?>
                        <div id="review"><?php echo str_replace('.00','',$postvals['colabs_coupon']) . $postvals['colabs_coupon_type']; ?></div>
                        <?php endif; ?>
                        <div class="clr"></div>
                    </li>
                <?php endif; ?>
            
                <hr class="bevel-double" />
                <div class="clr"></div>
            
                <li>
                    <div class="labelwrapper">
                        <label><?php _e('Total Amount Due','colabsthemes');?>:</label>
                    </div>
                    <div id="review"><strong>
                        <?php
                        // if it costs to post an ad OR its free and someone selected a featured ad price
                        if ($total_cost > 0) echo colabs_pos_price($total_cost); else echo __('--');
                        ?>
                    </strong></div>
                    <div class="clr"></div>
                </li>

                    
                <li>
                <?php if($total_cost > 0) : ?>
                <div class="labelwrapper">
                	<label><?php _e('Payment Method','colabsthemes'); ?>:</label>
                </div>
                <select name="colabs_payment_method" class="dropdownlist required">
                    <?php if(get_option('colabs_enable_paypal') == 'true') { ?><option value="paypal"><?php echo _e('PayPal', 'colabsthemes') ?></option><?php } ?>
                    <?php if(get_option('colabs_enable_bank') == 'true') { ?><option value="banktransfer"><?php echo _e('Bank Transfer', 'colabsthemes') ?></option><?php } ?>
                    <?php if(get_option('colabs_enable_gcheckout') == 'true') { ?><option value="gcheckout"><?php echo _e('Google Checkout', 'colabsthemes') ?></option><?php } ?>
                    <?php if(get_option('colabs_enable_2checkout') == 'true') { ?><option value="2checkout"><?php echo _e('2Checkout', 'colabsthemes') ?></option><?php } ?>
                    <?php if(get_option('colabs_enable_authorize') == 'true') { ?><option value="authorize"><?php echo _e('Authorize.net', 'colabsthemes') ?></option><?php } ?>
                    <?php if(get_option('colabs_enable_chronopay') == 'true') { ?><option value="chronopay"><?php echo _e('Chronopay', 'colabsthemes') ?></option><?php } ?>
                    <?php if(get_option('colabs_enable_mbookers') == 'true') { ?><option value="mbookers"><?php echo _e('MoneyBookers', 'colabsthemes') ?></option><?php } ?>
                </select>
                <?php endif; ?>
                <div class="clr"></div>
                </li>
                </ol>
                <div class="pad10"></div>


		<div class="license">

                    <?php echo get_option('colabs_ads_tou_msg'); ?>

		</div>

                <div class="clr"></div>


                <p class="terms"><?php _e('By clicking the proceed button below, you agree to our terms and conditions.','colabsthemes'); ?>
                <br />
                <?php _e('Your IP address has been logged for security purposes:','colabsthemes'); ?> <?php echo colabsthemes_get_ip(); ?>
				</p>



                <p class="btn2">
                    <input type="button" name="goback" class="btn_orange" value="<?php _e('Go back','colabsthemes') ?>" onclick="history.back()" />
                    <input type="submit" name="step2" id="step2" class="btn_orange" value="<?php _e('Proceed ','colabsthemes'); ?> &rsaquo;&rsaquo;" />
                </p>

                    <input type="hidden" id="oid" name="oid" value="<?php echo $_POST['oid']; ?>" />
                    <input type="hidden" id="pack" name="pack" value="<?php echo $_POST['pack']; ?>" />
                    <input type="hidden" id="total_cost" name="total_cost" value="<?php echo $total_cost; ?>" />
                    <input type="hidden" id="colabs_sys_userIP" name="colabs_sys_userIP" value="<?php echo colabsthemes_get_ip(); ?>" />
                    

	    </form>

		<div class="clear"></div>
        
    <?php colabsthemes_after_submit_membership(); ?>