<?php
/*
Template Name: Add New Listing Confirm
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

//print_r($_SERVER);
//print_r($_POST);

// get the unique ad listing id and activate the ad
if ( !empty( $_GET['pid'] ) ) :
    global $wpdb;

    // Only Accept People coming from Paypal (Paypal sends us info)
    if( empty( $_POST['payer_email']) ){
	return;
    }

    $pid = trim( $_GET['pid'] );
    // $aid = trim($_GET['aid']);

    $sql = $wpdb->prepare( "SELECT p.ID, p.post_status
            FROM $wpdb->posts p, $wpdb->postmeta m
            WHERE p.ID = m.post_id
            AND p.post_status <> 'publish'
            AND m.meta_key = 'colabs_sys_ad_conf_id'
            AND m.meta_value = '%s'
            ", $pid );

    $newadid = $wpdb->get_row( $sql );
	
	$the_post = get_post( $_GET['aid'] );
	
	if ( $the_post->post_status == 'publish' )
		header( 'Location: ' . get_permalink( $_GET['aid'] ) );

    // if the ad is found, then publish it
    if ( $newadid ) {
		//if published already, take the user to there dashboard
		
		$the_ad = array();
		
        $the_ad['ID'] = $newadid->ID;
        $the_ad['post_status'] = 'publish';
        
        colabs_add_new_confirm_before_update();
        
        $ad_id = wp_update_post( $the_ad );

        // now we need to update the ad expiration date so they get the full length of time
        // sometimes they didn't pay for the ad right away or they are renewing

        // first get the ad duration and first see if ad packs are being used
        // if so, get the length of time in days otherwise use the default
        // prune period defined on the CP settings page

        $ad_length = get_post_meta( $ad_id, 'colabs_sys_ad_duration', true );

        if ( isset( $ad_length ) )
            $ad_length = $ad_length;
        else
            $ad_length = get_option('colabs_prun_period');

        // set the ad listing expiration date
        $ad_expire_date = date_i18n( 'm/d/Y H:i:s', strtotime( '+' . $ad_length . ' days' ) ); // don't localize the word 'days'

        //now update the expiration date on the ad
        update_post_meta( $ad_id, 'colabs_sys_expire_date', $ad_expire_date );
        
        colabs_add_new_confirm_after_update();

        // send the permalink to the page
        $new_ad_url = '<a href="' . get_permalink( $ad_id ) . '">'. __('View your new ad', 'colabsthemes') .'</a>';


        if ( file_exists(TEMPLATEPATH . '/includes/gateways/process.php') )
            include_once (TEMPLATEPATH . '/includes/gateways/process.php');
    }


endif;

?>


<?php get_header(); ?>


<!-- CONTENT -->
  <div class="content">

    <div class="content_botbg">

      <div class="content_res">

        <!-- full block -->
        <div class="shadowblock_out">

          <div class="shadowblock">

            <div id="step3"></div>

            <?php
                // see if the ad id is valid
                if ( $newadid ) { ?>

                  <h2 class="dotted"><?php _e('Thank You!','colabsthemes'); ?></h2>

                  <div class="thankyou">

                    <h3><?php _e('Your payment has been processed and your ad listing should now be live.','colabsthemes') ?></h3>

                    <p><?php _e('Visit your dashboard to make any changes to your ad listing or profile.','colabsthemes') ?></p>
                    <?php echo $new_ad_url ?>

                    <div class="pad50"></div>

                 </div>

            <?php } else { ?>

                  <h2 class="dotted"><?php _e('An Error Has Occurred','colabsthemes') ?></h2>

                  <div class="thankyou">

                      <p><?php _e('This ad has already been published or you do not have permission to activate this ad. Please contact the site admin if you are experiencing any issues.','colabsthemes') ?></p>

                      <div class="pad50"></div>

                 </div>

            <?php } ?>


            </div><!-- /shadowblock -->

        </div><!-- /shadowblock_out -->

        <div class="clr"></div>

      </div><!-- /content_res -->

    </div><!-- /content_botbg -->

  </div><!-- /content -->
	
   
<?php get_footer(); ?>

