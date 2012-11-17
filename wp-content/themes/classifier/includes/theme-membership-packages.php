<?php
$options_new_ad_pack = array (


    array( 'type' => 'notab'),

	array(	'name' => __('Ad Pack Details','colabsthemes'),
                'type' => 'title',
                'desc' => '',
             ),

		array(  'name' => __('Name', 'colabsthemes'),
                        'desc' => '',
                        'tip' => __('Create a name that best describes this ad package. (i.e. 30 days for only $5) This will be visible on your new ad listing submission page.','colabsthemes'),
                        'id' => 'pack_name',
                        'css' => 'min-width:400px;',
                        'type' => 'text',
                        'vis' => '',
                        'req' => '1',
                        'min' => '5',
                        'std' => ''),

               array(  'name' => __('Description','colabsthemes'),
                        'desc' => '',
                        'tip' => __('Enter a description of your new ad package. It will not be visible on your site.','colabsthemes'),
                        'id' => 'pack_desc',
                        'css' => 'width:400px;height:100px;',
                        'type' => 'textarea',
                        'req' => '1',
                        'min' => '5',
                        'std' => ''),

                array(  'name' => __('Price Per Listing', 'colabsthemes'),
                        'desc' => '',
                        'tip' => __('Enter a numeric value for this package (do not enter a currency symbol or commas). For ad packs, this will be the price to post an ad.','colabsthemes'),
                        'id' => 'pack_price',
                        'css' => 'width:75px;',
                        'type' => 'text',
                        'vis' => '',
                        'req' => '1',
                        'min' => '1',
                        'std' => ''),

                array(  'name' => __('Ad Duration', 'colabsthemes'),
                        'desc' => '',
                        'tip' => __('Enter a numeric value to specify the number of days for this ad package (i.e. 30, 60, 90, 120).','colabsthemes'),
                        'id' => 'pack_duration',
                        'css' => 'width:75px;',
                        'type' => 'text',
                        'vis' => '',
                        'req' => '1',
                        'min' => '1',
                        'std' => ''),

                array(  'name' => __('Package Status', 'colabsthemes'),
                        'desc' => '',
                        'tip' => __('If you do not want this ad package live on your site, select inactive.','colabsthemes'),
                        'id' => 'pack_status',
                        'css' => 'min-width:100px;',
                        'std' => '',
                        'js' => '',
                        'req' => '1',
                        'type' => 'select',
                        'options' => array( 'active'   => __('Active', 'colabsthemes'),
											'inactive' => __('Inactive', 'colabsthemes') )),

     array( 'type' => 'notabend'),


);

$options_new_membership_pack = array (


    array( 'type' => 'notab'),

	array(	'name' => __('Membership Pack Details','colabsthemes'),
                'type' => 'title',
                'desc' => '',
             ),

		array(  'name' => __('Name', 'colabsthemes'),
                        'desc' => '',
                        'tip' => __('Create a name that best describes this membership package. (i.e. 30 days unlimited posting for only $25) This will be visible on your ad listing submission page.','colabsthemes'),
                        'id' => 'pack_name',
                        'css' => 'min-width:400px;',
                        'type' => 'text',
                        'vis' => '',
                        'req' => '1',
                        'min' => '5',
                        'std' => ''),

               array(  'name' => __('Description','colabsthemes'),
                        'desc' => '',
                        'tip' => __('Enter a description of your new membership package. It will not be visible on your site.','colabsthemes'),
                        'id' => 'pack_desc',
                        'css' => 'width:400px;height:100px;',
                        'type' => 'textarea',
                        'req' => '1',
                        'min' => '5',
                        'std' => ''),
                        
                array(  'name' => __('Package Type', 'colabsthemes'),
                        'desc' => '',
                        'tip' => __('Select which package type to change how the pack affects the price of the ad during the posting process.','colabsthemes'),
                        'id' => 'pack_type',
                        'css' => 'min-width:100px;',
                        'std' => '',
                        'js' => '',
                        'req' => '1',
                        'type' => 'select',
                        'options' => array( 'static'     => __('Static Price', 'colabsthemes'),
											'discount'   => __('Discounted Price', 'colabsthemes'),
											'percentage' => __('% Discounted Price', 'colabsthemes'), )),     
											
                array(  'name' => __('Membership Price', 'colabsthemes'),
                        'desc' => '',
                        'tip' => __('The price this membership will cost your customer to purchase. Enter a numeric value (do not enter a currency symbol or commas).','colabsthemes'),
                        'id' => 'pack_membership_price',
                        'css' => 'width:75px;',
                        'type' => 'text',
                        'vis' => '',
                        'req' => '1',
                        'min' => '1',
                        'std' => '1.00'),
        
                array(  'name' => __('Membership Duration', 'colabsthemes'),
                        'desc' => __('Enter a numeric value for the number of days','colabsthemes'),
                        'tip' => __('The length of time in days this membership lasts. Enter a numeric value (i.e. 30, 60, 90, 120).','colabsthemes'),
                        'id' => 'pack_duration',
                        'css' => 'width:75px;',
                        'type' => 'text',
                        'vis' => '',
                        'req' => '1',
                        'min' => '1',
                        'std' => ''),

                array(  'name' => __('Price Modifier <br /> (How a membership <br /> affects the price of an ad)', 'colabsthemes'),
                        'desc' => __('Enter #.## for currency (i.e. 2.25 for $2.25), ### for percentage (i.e. 50 for 50%).','colabsthemes'),
                        'tip' => __('Enter a numeric value (do not enter a currency symbol or commas). This will modify the checkout price based on the selected package type.','colabsthemes'),
                        'id' => 'pack_price',
                        'css' => 'width:75px;',
                        'type' => 'text',
                        'vis' => '',
                        'req' => '1',
                        'min' => '1',
                        'std' => ''),

				
                array(  'name' => __('Satisfies Membership Requirement', 'colabsthemes'),
                        'desc' => sprintf( __("If the &quot;<a href='%s'>Are Membership Packs Required to Purchase Ads</a>&quot; option under the Membership tab is set to yes, you should select yes.",'colabsthemes'), 'admin.php?page=pricing' ),
                        
                        
                        __('If the &quot;Are Membership Packs Required to Purchase Ads&quot; option is set to yes, you should select yes.','colabsthemes'),
                        
                        'tip' => __('Selecting no means that this membership does not allow the customer to post to categories requiring membership. You would select no if you wanted to separate memberships that are required to post versus memberships that simply affect the final price.','colabsthemes'),
                        'id' => 'pack_satisfies_required',
                        'css' => 'min-width:100px;',
                        'std' => '',
                        'js' => '',
                        'req' => '',
                        'type' => 'select',
                        'options' => array( 'required_'   => __('Yes', 'colabsthemes'),
											         ''   => __('No', 'colabsthemes'), )),


                array(  'name' => __('Package Status', 'colabsthemes'),
                        'desc' => '',
                        'tip' => __('Allows you to temporarily turn off this package instead of deleting it. Please note that existing active memberships will still be able to list ads at their discounted price unless memberships are turned off globally from the Pricing => Membership tab.','colabsthemes'),
                        'id' => 'pack_status',
                        'css' => 'min-width:100px;',
                        'std' => '',
                        'js' => '',
                        'req' => '1',
                        'type' => 'select',
                        'options' => array( 'active_membership'   => __('Active', 'colabsthemes'),
                                            'inactive_membership' => __('Inactive', 'colabsthemes'), )),

     array( 'type' => 'notabend'),


);

add_action('admin_menu', 'register_my_custom_submenu_page');

function register_my_custom_submenu_page() {
	add_submenu_page( 'colabsthemes', __('Packages','colabsthemes'), __('Packages','colabsthemes'), 'manage_options', 'packages', 'colabs_ad_packs' );
}

function colabs_ad_packs() {
    global $colabs_abbr, $wpdb, $current_user;

    $current_user = wp_get_current_user();

    // check to prevent php "notice: undefined index" msg
    if(isset($_GET['action'])) $theswitch = $_GET['action']; else $theswitch ='';
	?>

	<script type="text/javascript">jQuery(document).ready(function(a){a("#mainform").validate({errorClass:"invalid"})});</script>

	<?php
	if(isset($_GET['type']) && $_GET['type'] == 'membership')
		$options_new_pack = $GLOBALS['options_new_membership_pack'];
	else
		$options_new_pack = $GLOBALS['options_new_ad_pack'];

    switch ( $theswitch ) {

    case 'addpack':
    ?>

        <div class="wrap">
            <div class="icon32" id="icon-themes"><br/></div>
            <h2><?php if($_GET['type'] == 'membership') _e('New Membership Pack','colabsthemes'); else _e('New Ad Pack','colabsthemes'); ?></h2>


        <?php
        // check and make sure the form was submitted
        if ( isset($_POST['submitted']) ) {
			
			//setup optional variables for the package
			if(isset($_POST['pack_satisfies_required'])) $post_pack_satisfies_required = $_POST['pack_satisfies_required']; else $post_pack_satisfies_required = '';
			if(isset($_POST['pack_type'])) $post_pack_type = $post_pack_satisfies_required.$_POST['pack_type']; else $post_pack_type = '';
			if(isset($_POST['pack_membership_price'])) $post_pack_membership_price = $_POST['pack_membership_price']; else $post_pack_membership_price = '';

			$values = array(
				"pack_name" => $_POST['pack_name'],
				"pack_desc" => $_POST['pack_desc'],
				"pack_price" => $_POST['pack_price'],
				"pack_duration" => $_POST['pack_duration'],
				"pack_status" => $_POST['pack_status'],
				"pack_type" => $post_pack_type,
				"pack_membership_price" => $_POST['pack_membership_price'],
				"pack_owner" => $_POST['pack_owner'],
				"pack_modified" => gmdate('Y-m-d H:i:s'),
			);

			$results = $wpdb->insert( $wpdb->prefix . "colabs_ad_packs", $values);

			
            if ($results !== false) :
            ?>

                <p style="text-align:center;padding-top:50px;font-size:22px;"><?php _e('Creating your ad package.....','colabsthemes') ?><br /><br /><img src="<?php echo bloginfo('template_directory') ?>/images/loader.gif" alt="" /></p>
                <meta http-equiv="refresh" content="0; URL=?page=packages">

            <?php
            endif;

        } else {
        ?>

			<form method="post" id="mainform" action="">

				<?php cl_admin_fields($options_new_pack) ?>

				<p class="submit"><input class="btn button-primary" name="save" type="submit" value="<?php _e('Create New Ad Package','colabsthemes') ?>" />&nbsp;&nbsp;&nbsp;
					<input name="cancel" type="button" onClick="location.href='?page=packages'" value="<?php _e('Cancel','colabsthemes') ?>" /></p>
				<input name="submitted" type="hidden" value="yes" />
				<input name="pack_owner" type="hidden" value="<?php echo $current_user->user_login ?>" />

			</form>

        <?php
        }
        ?>

        </div><!-- end wrap -->

    <?php
    break;

    case 'editpack':
    ?>

        <div class="wrap">
            <div class="icon32" id="icon-themes"><br/></div>
            <h2><?php _e('Edit Ad Package','colabsthemes') ?></h2>


        <?php
        if ( isset($_POST['submitted']) && $_POST['submitted'] == 'yes' ) {

	    $values = array(
		    "pack_name" => $_POST['pack_name'],
		    "pack_desc" => $_POST['pack_desc'],
		    "pack_price" => $_POST['pack_price'],
		    "pack_duration" => $_POST['pack_duration'],
		    "pack_status" => $_POST['pack_status'],
		    "pack_type" => $_POST['pack_satisfies_required'].$_POST['pack_type'],
		    "pack_membership_price" => $_POST['pack_membership_price'],
		    "pack_owner" => $_POST['pack_owner'],
		    "pack_modified" => gmdate('Y-m-d H:i:s'),
	    );

	    $where = array(
		    "pack_id" => $_GET['id']
	    );

            $wpdb->update( $wpdb->prefix . "colabs_ad_packs", $values, $where);

            ?>

            <p style="text-align:center;padding-top:50px;font-size:22px;"><?php _e('Saving your changes.....','colabsthemes') ?><br /><br /><img src="<?php echo bloginfo('template_directory') ?>/images/loader.gif" alt="" /></p>
            <meta http-equiv="refresh" content="0; URL=?page=packages">

        <?php
        } else {
        ?>


            <form method="post" id="mainform" action="">

            <?php
		    cl_admin_db_fields($options_new_pack, 'colabs_ad_packs', 'pack_id');
	    ?>

                <p class="submit">
                    <input class="btn button-primary" name="save" type="submit" value="<?php _e('Save changes','colabsthemes') ?>" />&nbsp;&nbsp;&nbsp;
                    <input name="cancel" type="button" onClick="location.href='?page=packages'" value="<?php _e('Cancel','colabsthemes') ?>" />
                    <input name="submitted" type="hidden" value="yes" />
                    <input name="pack_owner" type="hidden" value="<?php echo $current_user->user_login ?>" />
                </p>

            </form>

        <?php } ?>

        </div><!-- end wrap -->

    <?php
    break;

    case 'delete':

        $wpdb->query( $wpdb->prepare( "DELETE FROM " . $wpdb->prefix . "colabs_ad_packs WHERE pack_id = %s", $_GET['id'] ) );
    ?>

        <p style="text-align:center;padding-top:50px;font-size:22px;"><?php _e('Deleting ad package.....','colabsthemes') ?><br /><br /><img src="<?php echo bloginfo('template_directory') ?>/images/loader.gif" alt="" /></p>
        <meta http-equiv="refresh" content="0; URL=?page=packages">

    <?php
    break;

    default:
		//echo $wpdb->prefix;
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "colabs_ad_packs ORDER BY pack_id desc" ) );

    ?>

        <div class="wrap">
        <div class="icon32" id="icon-themes"><br/></div>
        <h2><?php _e('Ad Packs','colabsthemes') ?>&nbsp;<a class="button add-new-h2" href="?page=packages&amp;action=addpack&amp;type=ad"><?php _e('Add New','colabsthemes') ?></a></h2>

        <?php if ( get_option( $colabs_abbr.'_price_scheme') != 'single' ) { ?>
	        <div class="error"><p><?php printf(__('Ad Packs are disabled. Change the <a href="%1$s">pricing model</a> to enable Ad Packs.', 'colabsthemes'), 'admin.php?page=pricing#tab1' ); ?></p></div>
        <?php } ?>

        <p class="admin-msg"><?php _e('Ad Packs allow you to create bundled listing options for your customers to choose from. For example, instead of only offering a set price for xx days (30 days for $5), you could also offer discounts for longer terms (60 days for $7). These only work if you are selling ads and using the "Fixed Price Per Ad" price model.','colabsthemes') ?></p>

        <table id="tblspacer" class="widefat fixed">

            <thead>
                <tr>
					<th scope="col" style="width:35px;">&nbsp;</th>
                    <th scope="col"><?php _e('Name','colabsthemes') ?></th>
                    <th scope="col"><?php _e('Description','colabsthemes') ?></th>
                    <th scope="col"><?php _e('Price Per Ad','colabsthemes') ?></th>
                    <th scope="col"><?php _e('Duration','colabsthemes') ?></th>
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
	                if ( $result->pack_status == 'active' || $result->pack_status == 'inactive' ) :
                	$rowclass = 'even' == $rowclass ? 'alt' : 'even';
              ?>

                <tr class="<?php echo $rowclass ?>">
                    <td style="padding-left:10px;"><?php echo $i++; ?>.</td>
                    <td><a href="?page=packages&amp;action=editpack&amp;type=ad&amp;id=<?php echo $result->pack_id ?>"><strong><?php echo stripslashes($result->pack_name); ?></strong></a></td>
                    <td><?php echo $result->pack_desc ?></td>
                    <td><?php echo colabs_pos_price( $result->pack_price ) ?></td>
                    <td><?php echo $result->pack_duration ?>&nbsp;<?php _e('days','colabsthemes') ?></td>
                    <td><?php echo mysql2date( get_option('date_format') .' '. get_option('time_format'), $result->pack_modified ) ?> <?php _e('by','colabsthemes') ?> <?php echo $result->pack_owner; ?></td>
                    <td><?php echo ucwords( $result->pack_status ) ?></td>
                    <td style="text-align:center">
                        <a href="?page=packages&amp;action=editpack&amp;type=ad&amp;id=<?php echo $result->pack_id ?>"><img src="<?php echo bloginfo('template_directory') ?>/images/pencil.png" alt="<?php echo  _e('Edit ad package','colabsthemes') ?>" title="<?php echo _e('Edit ad package','colabsthemes') ?>" /></a>&nbsp;&nbsp;&nbsp;
                        <a onclick="return confirmBeforeDelete();" href="?page=packages&amp;action=delete&amp;id=<?php echo $result->pack_id ?>"><img src="<?php echo bloginfo('template_directory') ?>/images/close.png" alt="<?php echo _e('Delete ad package','colabsthemes') ?>" title="<?php echo _e('Delete ad package','colabsthemes') ?>" /></a>
                    </td>
                </tr>

              <?php
				endif; //end if('active' || 'inactive')

                } // end for each
				unset($i);
              ?>

              </tbody>

            <?php

            } else {

            ?>

                <tr>
                    <td colspan="7"><?php _e('No ad packs found.','colabsthemes') ?></td>
                </tr>

            <?php
            } // end $results
            ?>

            </table>


        </div><!-- end wrap for ad packs -->

        <div id="membership-packs" class="wrap">
        <div class="icon32" id="icon-themes"><br/></div>
        <h2><?php _e('Membership Packs','colabsthemes') ?>&nbsp;<a class="button add-new-h2" href="?page=packages&amp;action=addpack&amp;type=membership"><?php _e('Add New','colabsthemes') ?></a></h2>

        <p class="admin-msg"><?php printf(__('Membership Packs allow you to setup subscription-based pricing packages. This enables your customers to post unlimited ads for a set period of time or until the membership becomes inactive. These memberships affect pricing regardless of the ad packs or pricing model you have set as long as you have the <a href="%1$s">enable membership packs</a> option set to yes.','colabsthemes'), 'admin.php?page=colabsthemes#colabs-option-pricingconfiguration'); ?></p>

        <table id="tblspacer" class="widefat fixed">

            <thead>
                <tr>
					<th scope="col" style="width:35px;">&nbsp;</th>
                    <th scope="col"><?php _e('Name','colabsthemes') ?></th>
                    <th scope="col"><?php _e('Description','colabsthemes') ?></th>
                    <th scope="col"><?php _e('Price Modifier','colabsthemes') ?></th>
                    <th scope="col"><?php _e('Terms','colabsthemes') ?></th>
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
	                if ( $result->pack_status == 'active_membership' || $result->pack_status == 'inactive_membership' ) :
	                $rowclass = 'even' == $rowclass ? 'alt' : 'even';
            ?>

                <tr class="<?php echo $rowclass ?>">
                    <td style="padding-left:10px;"><?php echo $i++ ?>.</td>
                    <td><a href="?page=packages&amp;action=editpack&amp;type=membership&amp;id=<?php echo $result->pack_id; ?>"><strong><?php echo stripslashes($result->pack_name); ?></strong></a></td>
                    <td><?php echo $result->pack_desc; ?></td>
                    <td>
						<?php switch ($result->pack_type) {
							case 'percentage':
								echo preg_replace('/.00$/', '', $result->pack_price).'% '.__('of price','colabsthemes'); //remove decimal when decimal is .00
								break;
							case 'discount':
								echo colabs_pos_price($result->pack_price).__('\'s less per ad','colabsthemes');
								break;
							case 'required_static':
								if ( (float)$result->pack_price == 0 ) echo __('Free','colabsthemes');
								else echo colabs_pos_price( $result->pack_price ).__(' per ad','colabsthemes');
								echo ' ('.__('required to post','colabsthemes').')';
								break;
							case 'required_discount':
								echo colabs_pos_price( $result->pack_price ).__('\'s less per ad','colabsthemes');
								echo ' ('.__('required to post','colabsthemes').')';
								break;
							case 'required_percentage':
								echo preg_replace( '/.00$/', '', $result->pack_price ).'% '.__('of price','colabsthemes'); //remove decimal when decimal is .00
								echo ' ('.__('required to post','colabsthemes').')';
								break;
							default: //likely 'static'
								if ( (float)$result->pack_price == 0 ) echo __('Free','colabsthemes');
								else echo colabs_pos_price( $result->pack_price ).__(' per ad','colabsthemes');
						}
						?>
                    </td>
                    <td><?php echo colabs_pos_price( $result->pack_membership_price ).' / '.$result->pack_duration.' '.__('days','colabsthemes'); ?></td>
                    <td><?php echo mysql2date( get_option('date_format') .' '. get_option('time_format'), $result->pack_modified ) ?> <?php _e('by','colabsthemes') ?> <?php echo $result->pack_owner; ?></td>
                    <td><?php echo ucwords(preg_replace('/\_(.*)/', '', $result->pack_status)) ?></td>
                    <td style="text-align:center">
                        <a href="?page=packages&amp;action=editpack&amp;type=membership&amp;id=<?php echo $result->pack_id ?>"><img src="<?php echo bloginfo('template_directory'); ?>/images/pencil.png" alt="<?php echo  _e('Edit ad package','colabsthemes'); ?>" title="<?php echo _e('Edit ad package','colabsthemes') ?>" /></a>&nbsp;&nbsp;&nbsp;
                        <a onclick="return confirmBeforeDelete();" href="?page=packages&amp;action=delete&amp;id=<?php echo $result->pack_id ?>"><img src="<?php echo bloginfo('template_directory') ?>/images/close.png" alt="<?php echo _e('Delete ad package','colabsthemes'); ?>" title="<?php echo _e('Delete ad package','colabsthemes') ?>" /></a>
                    </td>
                </tr>

              <?php
				endif; //end if('active_membership' || 'inactive_membership')

                } // end for each
				unset($i);
              ?>

              </tbody>

            <?php

            } else {

            ?>

                <tr>
                    <td colspan="7"><?php _e('No ad packs found.','colabsthemes') ?></td>
                </tr>

            <?php
            } // end $results
            ?>

            </table>


        </div><!-- end wrap for membership packs-->

    <?php
    } // end switch
    ?>
    <script type="text/javascript">
        /* <![CDATA[ */
            function confirmBeforeDelete() { return confirm("<?php _e('Are you sure you want to delete this ad package?', 'colabsthemes'); ?>"); }
        /* ]]> */
    </script>

<?php

}

function cl_admin_db_fields($options, $cl_table, $cl_id) {
    global $wpdb;

    // gat all the admin fields
    $results = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ". $wpdb->prefix . $cl_table . " WHERE ". $cl_id ." = %d", $_GET['id'] ) );

    // If the pack has a type, check if it satisfies.
    if( isset( $results->pack_type ) && strpos( $results->pack_type, "required_" ) === 0 ){
    	$results->pack_satisfies_required = "required_";
      $results->pack_type = mb_substr($results->pack_type, 9, strlen($results->pack_type));
    }else{
    	$results->pack_satisfies_required = "";
    }

    ?>

    <table class="widefat fixed" id="tblspacer" style="width:850px;">

    <?php

    foreach ( $options as $value ) {

      if ( $results ) {

          // foreach ($results as $result):

          // check to prevent "Notice: Undefined property: stdClass::" error when php strict warnings is turned on
          if ( !isset($results->field_type) ) $field_type = ''; else $field_type = $results->field_type;
          if ( !isset($results->field_perm) ) $field_perm = ''; else $field_perm = $results->field_perm;

          switch($value['type']) {

            case 'title':
            ?>

                <thead>
                    <tr>
                        <th scope="col" width="200px"><?php echo $value['name'] ?></th><th scope="col">&nbsp;</th>
                    </tr>
                </thead>

            <?php

            break;

            case 'text':

            ?>

	       <tr id="<?php echo $value['id'] ?>_row" <?php if ($value['vis'] == '0') echo ' style="display:none;"'; ?>>
                    <td class="titledesc"><?php if ($value['tip']) { ?><a href="#" tip="<?php echo $value['tip'] ?>" tabindex="99"><div class="helpico"></div></a><?php } ?><?php echo $value['name'] ?>:</td>
                    <td class="forminp"><input name="<?php echo $value['id'] ?>" id="<?php echo $value['id'] ?>" type="<?php echo $value['type'] ?>" style="<?php echo $value['css'] ?>" value="<?php echo $results->$value['id'] ?>" <?php if ($value['req']) { ?> class="required <?php if (!empty($value['altclass'])) echo $value['altclass'] ?>" <?php } ?><?php if ($value['min']) ?> minlength="<?php echo $value['min'] ?>" <?php if($value['id'] == 'field_name') { ?>readonly="readonly"<?php } ?> /><br /><small><?php echo $value['desc'] ?></small></td>
                </tr>

            <?php

            break;

            case 'select':

            ?>

               <tr id="<?php echo $value['id'] ?>_row">
                   <td class="titledesc"><?php if ($value['tip']) { ?><a href="#" tip="<?php echo $value['tip'] ?>" tabindex="99"><div class="helpico"></div></a><?php } ?><?php echo $value['name'] ?>:</td>
                   <td class="forminp"><select <?php if ($value['js']) echo $value['js']; ?> <?php if(($field_perm == 1) || ($field_perm == 2)) { ?>DISABLED<?php } ?> name="<?php echo $value['id'] ?>" id="<?php echo $value['id'] ?>" style="<?php echo $value['css'] ?>">

                       <?php foreach ( $value['options'] as $key => $val ) { ?>

                             <option value="<?php echo $key ?>"<?php if (isset($results->$value['id']) && $results->$value['id'] == $key) { ?> selected="selected" <?php $field_type_out = $field_type; } ?>><?php echo $val; ?></option>

                       <?php } ?>

                       </select><br />
                       <small><?php echo $value['desc'] ?></small>

                       <?php
                       // have to submit this field as a hidden value if perms are 1 or 2 since the DISABLED option won't pass anything into the $_POST
                       if ( ($field_perm == 1) || ($field_perm == 2) ) { ?><input type="hidden" name="<?php echo $value['id'] ?>" value="<?php echo $field_type_out; ?>" /><?php } ?>

                   </td>
               </tr>

            <?php

            break;

            case 'textarea':

            ?>

               <tr id="<?php echo $value['id'] ?>_row"<?php if($value['id'] == 'field_values') { ?> style="display: none;" <?php } ?>>
                   <td class="titledesc"><?php if ($value['tip']) { ?><a href="#" tip="<?php echo $value['tip'] ?>" tabindex="99"><div class="helpico"></div></a><?php } ?><?php echo $value['name'] ?>:</td>
                   <td class="forminp"><textarea <?php if((($field_perm == 1) || ($field_perm == 2)) && ($value['id'] != 'field_tooltip') && $value['id'] != 'field_values') { ?>readonly="readonly"<?php } ?> name="<?php echo $value['id']?>" id="<?php echo $value['id'] ?>" style="<?php echo $value['css'] ?>"><?php echo $results->$value['id'] ?></textarea>
                       <br /><small><?php echo $value['desc'] ?></small></td>
               </tr>

            <?php

            break;

            case 'checkbox':
            ?>

                <tr id="<?php echo $value['id'] ?>_row">
                    <td class="titledesc"><?php if ($value['tip']) { ?><a href="#" tip="<?php echo $value['tip'] ?>" tabindex="99"><div class="helpico"></div></a><?php } ?><?php echo $value['name'] ?>:</td>
                    <td class="forminp"><input type="checkbox" name="<?php echo $value['id'] ?>" id="<?php echo $value['id'] ?>" value="1" style="<?php echo $value['css']?>" <?php if($results->$value['id']) { ?>checked="checked"<?php } ?> />
                        <br /><small><?php echo $value['desc'] ?></small>
                    </td>
                </tr>

            <?php
            break;

            case 'cat_checklist':

            ?>

               <tr id="<?php echo $value['id'] ?>_row">
                   <td class="titledesc"><?php if ($value['tip']) { ?><a href="#" tip="<?php echo $value['tip'] ?>" tabindex="99"><div class="helpico"></div></a><?php } ?><?php echo $value['name'] ?>:</td>
                   <td class="forminp">
                       <div id="categorydiv">
                           <div class="tabs-panel" id="categories-all" style="<?php echo $value['css'] ?>">
                               <ul class="list:category categorychecklist form-no-clear" id="categorychecklist">

                                   <?php echo cl_category_checklist( unserialize($results->form_cats),(cl_exclude_cats($results->id)) ); ?>

                               </ul>
                           </div>
                       </div>
                       <br /><small><?php echo $value['desc'] ?></small>
                   </td>
               </tr>

            <?php

            break;


        } // end switch

      } // end $results

    } // endforeach

    ?>

    </table>

<?php
}

function cl_admin_fields($options) {
	global $shortname, $colabs_abbr;
?>


<div id="tabs-wrap">


    <?php

    // first generate the page tabs
    $counter = 0;

    echo '<ul class="tabs">'. "\n";
    foreach ( $options as $value ) {

        if ( in_array('tab', $value) ) :
            echo '<li><a href="#'.$value['type'].$counter.'">'.$value['tabname'].'</a></li>'. "\n";
            $counter = $counter + 1;
        endif;

    }
    echo '</ul>'. "\n\n";


     // now loop through all the options
    $counter = 0;
    $table_width = get_option('cl_table_width');

    foreach ( $options as $value ) {

        switch ( $value['type'] ) {

            case 'tab':

                echo '<div id="'.$value['type'].$counter.'">'. "\n\n";
                echo '<table class="widefat fixed" style="width:'.$table_width.'; margin-bottom:20px;">'. "\n\n";

            break;

            case 'notab':

                echo '<table class="widefat fixed" style="width:'.$table_width.'; margin-bottom:20px;">'. "\n\n";

            break;

            case 'title':
            ?>

                <thead><tr><th scope="col" width="200px"><?php echo $value['name'] ?></th><th scope="col"><?php if ( isset( $value['desc'] ) ) echo $value['desc'] ?>&nbsp;</th></tr></thead>

            <?php
            break;

            case 'text':
            ?>

            <?php if ( $value['id'] <> 'field_name' ) { // don't show the meta name field used by WP. This is automatically created by CP. ?>
                <tr <?php if ($value['vis'] == '0') { ?>id="<?php if ( !empty($value['visid']) ) { echo $value['visid']; } else { echo 'field_values'; } ?>" style="display:none;"<?php } else { ?>id="<?php echo $value['id'] ?>_row"<?php } ?>>
                    <td class="titledesc"><?php if ($value['tip']) { ?><a href="#" tip="<?php echo $value['tip'] ?>" tabindex="99"><div class="helpico"></div></a><?php } ?><?php echo $value['name'] ?>:</td>
                    <td class="forminp"><input name="<?php echo $value['id'] ?>" id="<?php echo $value['id'] ?>" type="<?php echo $value['type'] ?>" style="<?php echo $value['css'] ?>" value="<?php if (get_option( $value['id'])) echo get_option( $value['id'] ); else echo $value['std'] ?>"<?php if ($value['req']) { ?> class="required <?php if ( !empty($value['altclass']) ) echo $value['altclass'] ?>" <?php } ?> <?php if ( $value['min'] ) { ?> minlength="<?php echo $value['min'] ?>"<?php } ?> /><br /><small><?php echo $value['desc'] ?></small></td>
                </tr>
            <?php } ?>

            <?php
            break;

            case 'select':
            ?>

                <tr id="<?php echo $value['id'] ?>_row">
                    <td class="titledesc"><?php if ($value['tip']) { ?><a href="#" tip="<?php echo $value['tip'] ?>" tabindex="99"><div class="helpico"></div></a><?php } ?><?php echo $value['name'] ?>:</td>
                    <td class="forminp"><select <?php if ( !empty( $value['js'] ) ) echo $value['js']; ?> name="<?php echo $value['id'] ?>" id="<?php echo $value['id'] ?>" style="<?php echo $value['css'] ?>"<?php if ( $value['req'] ) { ?> class="required"<?php } ?>>

                        <?php
                        foreach ($value['options'] as $key => $val) {
                        ?>

                            <option value="<?php echo $key ?>" <?php if ( get_option($value['id']) == $key ) { ?> selected="selected" <?php } ?>><?php echo ucfirst($val) ?></option>

                        <?php
                        }
                        ?>

                       </select><br /><small><?php echo $value['desc'] ?></small>
                    </td>
                </tr>

            <?php
            break;

            case 'checkbox':
            ?>

                <tr id="<?php echo $value['id'] ?>_row">
                    <td class="titledesc"><?php if ($value['tip']) { ?><a href="#" tip="<?php echo $value['tip'] ?>" tabindex="99"><div class="helpico"></div></a><?php } ?><?php echo $value['name'] ?>:</td>
                    <td class="forminp"><input type="checkbox" name="<?php echo $value['id'] ?>" id="<?php echo $value['id'] ?>" value="true" style="<?php echo $value['css']?>" <?php if(get_option($value['id'])) { ?>checked="checked"<?php } ?> />
                        <br /><small><?php echo $value['desc'] ?></small>
                    </td>
                </tr>

            <?php
            break;

            case 'textarea':
            ?>
                <tr id="<?php echo $value['id'] ?>_row"<?php if ( $value['id'] == 'field_values' ) { ?> style="display: none;" <?php } ?>>
                    <td class="titledesc"><?php if ( $value['tip'] ) { ?><a href="#" tip="<?php echo $value['tip'] ?>" tabindex="99"><div class="helpico"></div></a><?php } ?><?php echo $value['name'] ?>:</td>
                    <td class="forminp">
                        <textarea name="<?php echo $value['id'] ?>" id="<?php echo $value['id'] ?>" style="<?php echo $value['css'] ?>" <?php if ($value['req']) { ?> class="required" <?php } ?><?php if ( $value['min'] ) { ?> minlength="<?php echo $value['min'] ?>"<?php } ?>><?php if ( get_option($value['id']) ) echo stripslashes( get_option($value['id']) ); else echo $value['std']; ?></textarea>
                        <br /><small><?php echo $value['desc'] ?></small>
                    </td>
                </tr>

            <?php
            break;

            case 'cat_checklist':
            ?>

                <tr id="<?php echo $value['id'] ?>_row">
                    <td class="titledesc"><?php if ($value['tip']) { ?><a href="#" tip="<?php echo $value['tip'] ?>" tabindex="99"><div class="helpico"></div></a><?php } ?><?php echo $value['name'] ?>:</td>
                    <td class="forminp">
                        <div id="categorydiv">
                            <div class="tabs-panel" id="categories-all" style="<?php echo $value['css'] ?>">
                                <ul class="list:category categorychecklist form-no-clear" id="categorychecklist">
                                <?php $catcheck = cl_category_checklist(0,cl_exclude_cats()); ?>
                                <?php if($catcheck) echo $catcheck; else wp_die( '<p style="color:red;">' .__('All your categories are currently being used. You must remove at least one category from another form layout before you can continue.','colabsthemes') .'</p>' ); ?>
                                </ul>
                            </div>
                        </div>
                        <br /><small><?php echo $value['desc'] ?></small>
                    </td>
                </tr>

            <?php
            break;

			case 'upload':
			?>
				<tr>
					<td class="titledesc"><?php if ($value['tip']) { ?><a href="#" tip="<?php echo $value['tip'] ?>" tabindex="99"><div class="helpico"></div></a><?php } ?><?php echo $value['name'] ?>:</td>
					<td class="forminp">
						<input id="<?php echo $value['id'] ?>" class="upload_image_url" type="text" style="<?php echo $value['css'] ?>" name="<?php echo $value['id'] ?>" value="<?php if (get_option( $value['id'])) echo get_option( $value['id'] ); else echo $value['std'] ?>" />
						<input id="upload_image_button" class="upload_button button" rel="<?php echo $value['id'] ?>" type="button" value="<?php _e('Upload Image', 'colabsthemes') ?>" />
						<?php if (get_option( $value['id'])){ ?>
						    <input name="<?php echo $value['id'] ?>" value="Clear Image" id="delete_image_button" class="delete_button button" rel="<?php echo $value['id'] ?>" type="button" />
						<?php } ?>
						<br /><small><?php echo $value['desc'] ?></small>
						<div id="<?php echo $value['id'] ?>_image" class="<?php echo $value['id'] ?>_image upload_image_preview"><?php if (get_option( $value['id'])) echo '<img src="' .get_option( $value['id'] ) . '" />'; ?></div>

					</td>
                </tr>

			<?php
			break;

            case 'logo':
            ?>
                <tr id="<?php echo $value['id'] ?>_row">
                    <td class="titledesc"><?php echo $value['name'] ?></td>
                    <td class="forminp">&nbsp;</td>
                </tr>

            <?php
            break;

            case 'price_per_cat':
            ?>
                <tr id="<?php echo $value['id'] ?>_row"  class="cat-row">
                    <td class="titledesc"><?php if ($value['tip']) { ?><a href="#" tip="<?php echo $value['tip'] ?>" tabindex="99"><div class="helpico"></div></a><?php } ?><?php echo $value['name'] ?>:</td>

                    <td class="forminp">

                        <table style="width:100%;">

                        <?php

                        $categories = get_categories('orderby=name&order=asc&hide_empty=0&taxonomy='.COLABS_TAX_CAT);
                        $i = 0;

                        foreach ($categories as $cat) {

                            if (($i % 2) == 0) { ?>
                                <tr>
                            <?php
                            }

                            // if the category price is empty, put a zero in it so it doesn't error out
                            $cat_price = get_option('cl_cat_price_'.$cat->cat_ID);
                            if ($cat_price == '') {
                                $cat_price = '0';
                            }
                            ?>

                            <td nowrap style="padding-top:15px; text-align: right;"><?php echo $cat->cat_name; ?>:</td>
                            <td nowrap style="color:#bbb;"><input name="catarray[cl_cat_price_<?php echo $cat->cat_ID; ?>]" type="text" size="10" maxlength="100" value="<?php echo $cat_price ?>" />&nbsp;<?php echo get_option($colabs_abbr.'_curr_pay_type') ?></td>
                            <td cellspan="2" width="100">&nbsp;</td>

                            <?php
                            if (($i % 2) != 0) { ?>
                                </tr>
                            <?php
                            }

                            $i++;

                        } // end foreach
                        ?>

                        </table>

                    </td>
                </tr>


            <?php
            break;

			case 'required_per_cat':
            ?>
                <tr id="<?php echo $value['id'] ?>_row"  class="cat-row">
                    <td class="titledesc"><?php if ($value['tip']) { ?><a href="#" tip="<?php echo $value['tip'] ?>" tabindex="99"><div class="helpico"></div></a><?php } ?><?php echo $value['name'] ?>:</td>

                    <td class="forminp">

                        <table style="width:100%;">

                        <?php

                        $categories = get_categories('orderby=name&order=asc&hide_empty=0&taxonomy='.COLABS_TAX_CAT);
						$required_categories = get_option('cl_required_categories');
                        $i = 0;

                        foreach ($categories as $cat) {

                            if (($i % 2) == 0) { ?>
                                <tr>
                            <?php
                            }

                            ?>

                            <td nowrap style="padding-top:15px; text-align: right;"><?php echo $cat->cat_name; ?>:</td>
                            <td nowrap style="color:#bbb;"><input name="catreqarray[cl_cat_req_<?php echo $cat->cat_ID; ?>]" type="checkbox" value="<?php echo $cat->cat_ID; ?>" <?php if(isset($required_categories[$cat->cat_ID])) echo 'checked="checked"'; ?> /></td>
                            <td cellspan="2" width="100">&nbsp;</td>

                            <?php
                            if (($i % 2) != 0) { ?>
                                </tr>
                            <?php
                            }

                            $i++;

                        } // end foreach
                        ?>

                        </table>

                    </td>
                </tr>


            <?php
            break;

            case 'tabend':

                echo '</table>'. "\n\n";
                echo '</div> <!-- #tab'.$counter.' -->'. "\n\n";
                $counter = $counter + 1;

            break;

            case 'notabend':

                echo '</table>'. "\n\n";

            break;

        } // end switch

    } // end foreach
    ?>

   </div> <!-- #tabs-wrap -->

<?php
}
?>
