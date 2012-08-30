<?php
$options_coupon = array (


	array( 'type' => 'notab' ),

	array(	'name' => __('Coupon Details','colabsthemes'),
                'type' => 'title',
                'desc' => ''
             ),

		array(  'name' => __('Code', 'colabsthemes'),
                        'desc' => '',
                        'tip' => __('Create a coupon code that you wish to use. This will be the actual coupon that you pass out to your customers.','colabsthemes'),
                        'id' => 'coupon_code',
                        'css' => 'min-width:400px;',
                        'type' => 'text',
                        'vis' => '',
                        'req' => '1',
                        'min' => '3',
                        'std' => ''),

               array(  'name' => __('Description','colabsthemes'),
                        'desc' => '',
                        'tip' => __('Enter a description of your new coupon. It will not be visible on your site.','colabsthemes'),
                        'id' => 'coupon_desc',
                        'css' => 'width:400px;height:100px;',
                        'type' => 'textarea',
                        'req' => '1',
                        'min' => '5',
                        'std' => ''),

                array(  'name' => __('Discount', 'colabsthemes'),
                        'desc' => '',
                        'tip' => __('Enter a numeric value for this coupon (do not enter a currency symbol or commas).','colabsthemes'),
                        'id' => 'coupon_discount',
                        'css' => 'width:75px;',
                        'type' => 'text',
                        'vis' => '',
                        'req' => '1',
                        'min' => '1',
                        'std' => ''),

                array(  'name' => __('Type of Discount', 'colabsthemes'),
                        'desc' => '',
                        'tip' => __('Either a fixed price or percentage discount.','colabsthemes'),
                        'id' => 'coupon_discount_type',
                        'css' => 'min-width:100px;',
                        'std' => '',
                        'js' => '',
                        'req' => '1',
                        'type' => 'select',
                        'options' => array( '%'   => '%',
                                            get_option($colabs_abbr.'_curr_pay_type_symbol') => get_option($colabs_abbr.'_curr_pay_type_symbol'))),
											
                array(  'name' => __('Max Usage', 'colabsthemes'),
                        'desc' => '',
                        'tip' => __('Enter a numeric value for the times you wish this coupon to be usable. Enter 0 for unlimited usage.','colabsthemes'),
                        'id' => 'coupon_max_use_count',
                        'css' => 'width:75px;',
                        'type' => 'text',
                        'vis' => '',
                        'req' => '1',
                        'min' => '1',
                        'std' => ''),	

                array(  'name' => __('Start Date', 'colabsthemes'),
                        'desc' => '',
                        'tip' => __('Enter the start date for this coupon to begin working.','colabsthemes'),
                        'id' => 'coupon_start_date',
                        'css' => 'width:150px;',
                        'type' => 'text',
                        'vis' => '',
                        'req' => '1',
                        'min' => '1',
                        'std' => '',
                        'altclass' => 'datepicker'),

                array(  'name' => __('Expire Date', 'colabsthemes'),
                        'desc' => '',
                        'tip' => __('Enter the expiration date for this coupon to stop working.','colabsthemes'),
                        'id' => 'coupon_expire_date',
                        'css' => 'width:150px;',
                        'type' => 'text',
                        'vis' => '',
                        'req' => '1',
                        'min' => '1',
                        'std' => '',
                        'altclass' => 'datepicker'),

                array(  'name' => __('Coupon Status', 'colabsthemes'),
                        'desc' => '',
                        'tip' => __('If you do not want this coupon available for use, select inactive.','colabsthemes'),
                        'id' => 'coupon_status',
                        'css' => 'min-width:100px;',
                        'std' => '',
                        'js' => '',
                        'req' => '1',
                        'type' => 'select',
                        'options' => array( 'active'   => __('Active', 'colabsthemes'),
                                            'inactive' => __('Inactive', 'colabsthemes'))),

    array( 'type' => 'notabend'),

);

add_action('admin_menu', 'register_coupon_submenu_page');
function register_coupon_submenu_page() {
	add_submenu_page( 'colabsthemes', __('Coupons','colabsthemes'), __('Coupons','colabsthemes'), 'manage_options', 'coupons', 'colabs_coupons' );
}

function colabs_coupons() {
    global $options_coupon, $wpdb, $current_user, $colabs_version;

    $current_user = wp_get_current_user();

    // check to prevent php "notice: undefined index" msg
    if(isset($_GET['action'])) $theswitch = $_GET['action']; else $theswitch ='';
	?>

	<script type="text/javascript">
		//<![CDATA[
		/* initialize the datepicker feature */
		jQuery(document).ready(function($) {
			/* initialize the form validation */
			$("#mainform").validate({errorClass: "invalid"});

			$('form#mainform .datepicker').datepicker({
				showOn: 'button',
				dateFormat: 'yy-mm-dd',
				minDate: 0,
				buttonImageOnly: true,
				buttonText: '',
				buttonImage: '../wp-includes/images/blank.gif' // calling the real calendar image in the admin-style.css. need a blank placeholder image b/c of IE.
			});
		});
		//]]>
	</script>

	<?php
    switch ( $theswitch ) {

    case 'addcoupon':
    ?>

        <div class="wrap">
            <div class="icon32" id="icon-edit-pages"><br/></div>
            <h2><?php _e('New Coupon','colabsthemes') ?></h2>
          

          

        <?php
        // check and make sure the form was submitted
        if ( isset($_POST['submitted']) ) {

		//echo $_POST['coupon_expire_date'] . '<-- expire date';

	    // @todo Switch to
            // adding $wpdb->prepare causes the query to be empty for some reason
            $insert = "INSERT INTO " . $wpdb->prefix . "colabs_coupons" .
            " (coupon_code, coupon_desc, coupon_discount, coupon_discount_type, coupon_start_date, coupon_expire_date, coupon_status, coupon_max_use_count, coupon_owner, coupon_created, coupon_modified) " .
            "VALUES ('" .
                    $wpdb->escape(colabsthemes_clean($_POST['coupon_code'])) . "','" .
                    $wpdb->escape(colabsthemes_clean($_POST['coupon_desc'])) . "','" .
                    $wpdb->escape(colabsthemes_clean($_POST['coupon_discount'])) . "','" .
                    $wpdb->escape(colabsthemes_clean($_POST['coupon_discount_type'])) . "','" .
                    $wpdb->escape(colabsthemes_clean($_POST['coupon_start_date'])) . "','" .
                    $wpdb->escape(colabsthemes_clean($_POST['coupon_expire_date'])) . "','" .
                    $wpdb->escape(colabsthemes_clean($_POST['coupon_status'])) . "','" .
                    $wpdb->escape(colabsthemes_clean($_POST['coupon_max_use_count'])) . "','" .
                    $wpdb->escape(colabsthemes_clean($_POST['coupon_owner'])) . "','" .
                    gmdate('Y-m-d H:i:s') . "','" .
                    gmdate('Y-m-d H:i:s') .
                    "')";

            $results = $wpdb->query( $insert );


            if ( $results ) :
            ?>

                <p style="text-align:center;padding-top:50px;font-size:22px;"><?php _e('Creating your coupon.....','colabsthemes') ?><br /><br /><img src="<?php echo bloginfo('template_directory') ?>/images/loader.gif" alt="" /></p>
                <meta http-equiv="refresh" content="0; URL=?page=coupons">

            <?php
            endif;


        } else {
        ?>

                <form method="post" id="mainform" action="">

                    <?php cl_admin_fields($options_coupon) ?>

                    <p class="submit"><input class="btn button-primary" name="save" type="submit" value="<?php _e('Create New Coupon','colabsthemes') ?>" />&nbsp;&nbsp;&nbsp;
                    <input name="cancel" type="button" onClick="location.href='?page=coupons'" value="<?php _e('Cancel','colabsthemes') ?>" /></p>
                    <input name="submitted" type="hidden" value="yes" />
                    <input name="coupon_owner" type="hidden" value="<?php echo $current_user->user_login ?>" />

                </form>

        <?php
        }
        ?>

        </div><!-- end wrap -->

    <?php
    break;

    case 'editcoupon':
    ?>

        <div class="wrap">
            <div class="icon32" id="icon-themes"><br/></div>
            <h2><?php _e('Edit Coupon','colabsthemes') ?></h2>

            

        <?php
        if ( isset($_POST['submitted']) && $_POST['submitted'] == 'yes' ) {

             // adding $wpdb->prepare causes the query to be empty for some reason
            $update = "UPDATE " . $wpdb->prefix . "colabs_coupons SET" .
                    " coupon_code = '" . $wpdb->escape(colabsthemes_clean($_POST['coupon_code'])) . "'," .
                    " coupon_desc = '" . $wpdb->escape(colabsthemes_clean($_POST['coupon_desc'])) . "'," .
                    " coupon_discount = '" . $wpdb->escape(colabsthemes_clean($_POST['coupon_discount'])) . "'," .
                    " coupon_discount_type = '" . $wpdb->escape(colabsthemes_clean($_POST['coupon_discount_type'])) . "'," .
                    " coupon_start_date = '" . $wpdb->escape(colabsthemes_clean($_POST['coupon_start_date'])) . "'," .
                    " coupon_expire_date = '" . $wpdb->escape(colabsthemes_clean($_POST['coupon_expire_date'])) . "'," .
                    " coupon_status = '" . $wpdb->escape(colabsthemes_clean($_POST['coupon_status'])) . "'," .
                    " coupon_max_use_count = '" . $wpdb->escape(colabsthemes_clean($_POST['coupon_max_use_count'])) . "'," .
                    " coupon_owner = '" . $wpdb->escape(colabsthemes_clean($_POST['coupon_owner'])) . "'," .
                    " coupon_modified = '" . gmdate('Y-m-d H:i:s') . "'" .
                    " WHERE coupon_id ='" . $wpdb->escape($_GET['id']) ."'";

            $results = $wpdb->get_row( $update );
            ?>

            <p style="text-align:center;padding-top:50px;font-size:22px;"><?php _e('Saving your changes.....','colabsthemes') ?><br /><br /><img src="<?php echo bloginfo('template_directory') ?>/images/loader.gif" alt="" /></p>
            <meta http-equiv="refresh" content="0; URL=?page=coupons">

        <?php
        } else {
        ?>


            <form method="post" id="mainform" action="">

            <?php cl_admin_db_fields($options_coupon, 'colabs_coupons', 'coupon_id') ?>

                <p class="submit">
                    <input class="btn button-primary" name="save" type="submit" value="<?php _e('Save changes','colabsthemes') ?>" />&nbsp;&nbsp;&nbsp;
                    <input name="cancel" type="button" onClick="location.href='?page=coupons'" value="<?php _e('Cancel','colabsthemes') ?>" />
                    <input name="submitted" type="hidden" value="yes" />
                    <input name="coupon_owner" type="hidden" value="<?php echo $current_user->user_login ?>" />
                </p>

            </form>

        <?php } ?>

        </div><!-- end wrap -->

    <?php
    break;

    case 'delete':

        $wpdb->query( $wpdb->prepare( "DELETE FROM " . $wpdb->prefix . "colabs_coupons WHERE coupon_id = %s", $_GET['id'] ) );
    ?>

        <p style="text-align:center;padding-top:50px;font-size:22px;"><?php _e('Deleting coupon.....','colabsthemes') ?><br /><br /><img src="<?php echo bloginfo('template_directory') ?>/images/loader.gif" alt="" /></p>
        <meta http-equiv="refresh" content="0; URL=?page=coupons">

    <?php
    break;

    default:

		$results = cl_get_coupons();

    ?>

        <div class="wrap">
        <div class="icon32" id="icon-edit-pages"><br/></div>
        <h2><?php _e('Coupons','colabsthemes') ?>&nbsp;<a class="button add-new-h2" href="?page=coupons&amp;action=addcoupon"><?php _e('Add New','colabsthemes') ?></a></h2>

      


        <p class="admin-msg"><?php _e('Create coupons to offer special discounts to your customers.','colabsthemes') ?></p>

        <table id="tblspacer" class="widefat fixed">

            <thead>
                <tr>
                    <th scope="col" style="width:35px;">&nbsp;</th>
                    <th scope="col"><?php _e('Code','colabsthemes') ?></th>
                    <th scope="col"><?php _e('Description','colabsthemes') ?></th>
                    <th scope="col"><?php _e('Discount','colabsthemes') ?></th>
					<th scope="col"><?php _e('Usage','colabsthemes') ?></th>
					<th scope="col"><?php _e('Valid','colabsthemes') ?></th>
                    <th scope="col"><?php _e('Expires','colabsthemes') ?></th>
                    <th scope="col" style="width:150px;"><?php _e('Modified','colabsthemes') ?></th>
                    <th scope="col" style="width:75px;"><?php _e('Status','colabsthemes') ?></th>
                    <th scope="col" style="text-align:center;width:100px;"><?php _e('Actions','colabsthemes') ?></th>
                </tr>
            </thead>

            <?php
            if ( $results ) {
                $rowclass = '';
                $i=1;
            ?>

              <tbody id="list">

            <?php
                foreach ( $results as $result ) {

                $rowclass = 'even' == $rowclass ? 'alt' : 'even';
              ?>

                <tr class="<?php echo $rowclass ?>">
                    <td style="padding-left:10px;"><?php echo $i ?>.</td>
                    <td><a href="?page=coupons&amp;action=editcoupon&amp;id=<?php echo $result->coupon_id ?>"><strong><?php echo $result->coupon_code ?></strong></a></td>
                    <td><?php echo $result->coupon_desc ?></td>
                    <td><?php if (($result->coupon_discount_type) == '%') echo number_format($result->coupon_discount,0) . '%'; else echo cl_pos_price($result->coupon_discount); ?></td>
					<td><?php echo $result->coupon_use_count ?><?php if (($result->coupon_max_use_count) <> 0) echo '/' . $result->coupon_max_use_count ?></td>
					<td><?php echo mysql2date(get_option('date_format') .' '. get_option('time_format'), $result->coupon_start_date) ?></td>
					<td><?php echo mysql2date(get_option('date_format') .' '. get_option('time_format'), $result->coupon_expire_date) ?></td>
                    <td><?php echo mysql2date(get_option('date_format') .' '. get_option('time_format'), $result->coupon_modified) ?> <br /><?php _e('by','colabsthemes') ?> <?php echo $result->coupon_owner; ?></td>
                    <td><?php echo ucfirst($result->coupon_status) ?></td>
                    <td style="text-align:center">
                        <a href="?page=coupons&amp;action=editcoupon&amp;id=<?php echo $result->coupon_id ?>"><img src="<?php echo bloginfo('template_directory') ?>/images/pencil.png" alt="<?php echo  _e('Edit coupon','colabsthemes') ?>" title="<?php echo _e('Edit coupon','colabsthemes') ?>" /></a>&nbsp;&nbsp;&nbsp;
                        <a onclick="return confirmBeforeDelete();" href="?page=coupons&amp;action=delete&amp;id=<?php echo $result->coupon_id ?>"><img src="<?php echo bloginfo('template_directory') ?>/images/close.png" alt="<?php echo _e('Delete coupon','colabsthemes') ?>" title="<?php echo _e('Delete coupon','colabsthemes') ?>" /></a>
                    </td>
                </tr>

              <?php

                $i++;

                } // end for each
              ?>

              </tbody>

            <?php

            } else {

            ?>

                <tr>
                    <td>&nbsp;</td><td colspan="8"><?php _e('No coupons found.','colabsthemes') ?></td>
                </tr>

            <?php
            } // end $results
            ?>

            </table>


        </div><!-- end wrap -->

    <?php
    } // end switch
    ?>
    <script type="text/javascript">
        /* <![CDATA[ */
            function confirmBeforeDelete() { return confirm("<?php _e('Are you sure you want to delete this coupon?', 'colabsthemes'); ?>"); }
        /* ]]> */
    </script>

<?php

}

function cl_get_coupons($couponCode = '') {
    global $wpdb;
    $sql = "SELECT * "
    . "FROM " . $wpdb->prefix . "colabs_coupons ";
    if($couponCode != '')
    $sql .= "WHERE coupon_code='$couponCode' ";
    $sql .= "ORDER BY coupon_id desc";

    $results = $wpdb->get_results($sql);
    return $results;
}
?>