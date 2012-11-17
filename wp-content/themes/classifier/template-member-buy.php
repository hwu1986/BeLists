<?php
/*
Template Name: Membership Pack Purchases
*/	

// if not logged in and trying to purchase, redirect to login page
//otherwise private cache is needed for IE to work with the go back button
if(isset($_POST['step1'])) auth_redirect_login();
else header("Cache-control: private");

// include all the functions needed for this form
include_once (TEMPLATEPATH . '/includes/forms/step-functions.php');

//get the membership information based on selected memebership pack ID
global $current_user;
$current_user = wp_get_current_user();

if(isset($current_user->active_membership_pack)) $active_membership = get_pack($current_user->active_membership_pack);
else $active_membership = false;

if(isset($_POST['coupon'])) $coupon = $_POST['coupon']; else $coupon = false;
if(isset($_POST['pack'])) {
	$membership = get_pack($_POST['pack']);
	$total_cost = colabs_calc_membership_cost($_POST['pack'], $coupon);
}

//get any existing orders
$colabs_user_orders = get_user_orders($current_user->ID);
if(isset($colabs_user_orders) && $colabs_user_orders) $colabs_user_recent_order = $colabs_user_orders[0];

// load up the relavent javascript
add_action('wp_print_scripts', 'colabs_load_form_scripts');

//Build the HTML Header
?>

<?php get_header(); ?>
<script type='text/javascript'>
// <![CDATA[
/* setup the tooltip */
jQuery(document).ready(function(){jQuery("#mainform a").easyTooltip();});
// ]]>
</script>

<!-- #Main Starts -->
<?php colabs_main_before(); ?>

    <div class="row main-container">

        <?php colabsthemes_before_member(); ?>

        <div class="main-content col9">

            <header class="entry-header">
			  <h2><?php the_title(); ?></h2>
			</header>

			<div <?php post_class(); ?>>
				<?php if(get_option('colabs_enable_membership_packs') == 'true') : ?>
				
					<?php
    
					// check and make sure the form was submitted from step1 and the session value exists
					if(isset($_POST['step1'])) {
		
						include_once(TEMPLATEPATH . '/includes/forms/step2-membership.php');
		
					} elseif(isset($_POST['step2'])) {
						//now put the array containing all the post values into the database
						//DO NOTE USE POST VARS execpt as the relate to options selected. All POST vars are insecure.
						$membership = get_pack($_POST['pack']);
						$order = array();
						$order['user_id'] = $current_user->ID;
						$order['order_id'] = $_POST['oid'];
						$order['option_order_id'] = 'colabs_order_'.$current_user->ID.'_'.$_POST['oid'];
						$order['pack_type'] = 'membership';
						$order['total_cost'] = $total_cost;
						
						//Check for coupon and use to to reduce total price and total number of coupon uses left
						if(isset($_POST['colabs_coupon_code'])) { 
							$order['colabs_coupon_code'] = $_POST['colabs_coupon_code'];				
							//incriment coupon code count only if total ad price was not zero
							if(colabs_check_coupon_discount($order['colabs_coupon_code'])) {
								colabs_use_coupon($order['colabs_coupon_code']);
								//TODO - handle discounting of total cost
							}
						}
						
						$order = array_merge($order, (array)$membership);
						//save the order for use when payment is completed
						if(add_option($order['option_order_id'], $order)) {
							$colabs_user_orders = get_user_orders($current_user->ID);
							if(isset($colabs_user_orders) && $colabs_user_orders) $colabs_user_recent_order = $colabs_user_orders[0];
						}
						else { $order_already_exists = true; }
						include_once(TEMPLATEPATH . '/includes/forms/step3-membership.php');
		
					} else {
		
						// create a unique ID for this new ad order
						// uniqid requires a param for php 4.3 or earlier. added for 3.0.1
						if(empty($colabs_user_recent_order)) {
							$order_id  = uniqid(rand(10,1000), false);
						}
						else {
							$order_id = get_order_id($colabs_user_recent_order);
						}
						include_once(TEMPLATEPATH . '/includes/forms/step1-membership.php');
		
					} ?> 
				
				<?php else : ?>
                <h3><?php _e('Membership Not Enabled','colabsthemes') ?></h3>
                <p><?php _e('Memberships has been disabled. Please try again later.','colabsthemes') ?></p>
				<?php endif; //endif ?>	
            </div>
            
        </div><!-- /.main-content -->  
		
		<?php get_sidebar('user'); ?>

        <?php colabsthemes_after_member(); ?>
        
    </div><!-- /.main-container -->
	
<?php colabs_main_after(); ?>
  
<?php get_footer(); ?>