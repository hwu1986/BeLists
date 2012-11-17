<?php
add_action('admin_menu', 'register_transaction_submenu_page');
function register_transaction_submenu_page() {
	add_submenu_page( 'colabsthemes', __('Transactions','colabsthemes'), __('Transactions','colabsthemes'), 'manage_options', 'transactions', 'colabs_transactions' );
}

function colabs_transactions() {
    global $wpdb;

    // check to prevent php "notice: undefined index" msg when php strict warnings is on
    if ( isset( $_GET['action'] ) ) $theswitch = $_GET['action']; else $theswitch = '';

    switch ( $theswitch ) {

    // mark transaction as paid
    case 'setPaid':

            $update = "UPDATE " . $wpdb->prefix . "colabs_order_info SET payment_status = 'Completed' WHERE id = '". $_GET['id'] ."'";
            $wpdb->query( $update );
        ?>
        <p style="text-align:center;padding-top:50px;font-size:22px;"><?php _e('Updating transaction entry.....','colabsthemes') ?><br /><br /><img src="<?php echo bloginfo('template_directory') ?>/images/loader.gif" alt="" /></p>
        <meta http-equiv="refresh" content="0; URL=?page=transactions">

    <?php

    break;


    // mark transaction as unpaid
    case 'unsetPaid':

            $update = "UPDATE " . $wpdb->prefix . "colabs_order_info SET payment_status = 'Pending' WHERE id = '". $_GET['id'] ."'";
            $wpdb->query( $update );
        ?>
        <p style="text-align:center;padding-top:50px;font-size:22px;"><?php _e('Updating transaction entry.....','colabsthemes') ?><br /><br /><img src="<?php echo bloginfo('template_directory') ?>/images/loader.gif" alt="" /></p>
        <meta http-equiv="refresh" content="0; URL=?page=transactions">

    <?php

    break;


    // delete transaction entry
    case 'delete':

            $delete = "DELETE FROM " . $wpdb->prefix . "colabs_order_info WHERE id = '". $_GET['id'] ."'";
            $wpdb->query( $delete );
        ?>
        <p style="text-align:center;padding-top:50px;font-size:22px;"><?php _e('Deleting transaction entry.....','colabsthemes') ?><br /><br /><img src="<?php echo bloginfo('template_directory') ?>/images/loader.gif" alt="" /></p>
        <meta http-equiv="refresh" content="0; URL=?page=transactions">

    <?php

    break;


    // activate membership, update transaction entry
    case 'activateMembership':

            include_once (TEMPLATEPATH . '/includes/forms/step-functions.php');
            $orders = get_user_orders('',$_GET['oid']);
            if(!empty($orders)){
                $order_id = get_order_id($orders);
                $storedOrder = get_option($orders);
                $user_id = get_order_userid($orders);
                $the_user = get_userdata($user_id);
                //activate membership
                $order_processed = colabsthemes_process_membership_order($the_user, $storedOrder);
                //send email to user
                if($order_processed)
                    cl_owner_activated_membership_email($the_user, $order_processed);
                //update transaction entry
                $update = "UPDATE " . $wpdb->prefix . "colabs_order_info SET payment_status = 'Completed' WHERE custom = '". $_GET['oid'] ."'";
                $wpdb->query( $update );
            }
        ?>
        <p style="text-align:center;padding-top:50px;font-size:22px;"><?php _e('Activating membership plan.....','colabsthemes') ?><br /><br /><img src="<?php echo bloginfo('template_directory') ?>/images/loader.gif" alt="" /></p>
        <meta http-equiv="refresh" content="0; URL=?page=transactions">

    <?php

    break;


    // show the table of all transactions
    default:
?>
    <div class="wrap">
        <div class="icon32" id="icon-themes"><br /></div>
        <h2><?php _e('Order Transactions','colabsthemes') ?></h2>

        <table id="tblspacer" class="widefat fixed">

            <thead>
                <tr>
                    <th scope="col" style="width:35px;">&nbsp;</th>
                    <th scope="col"><?php _e('Payer Name','colabsthemes') ?></th>
                    <th scope="col" style="text-align: center;"><?php _e('Payer Status','colabsthemes') ?></th>
                    <th scope="col"><?php _e('Ad Title','colabsthemes') ?></th>
                    <th scope="col"><?php _e('Item Description','colabsthemes') ?></th>
                    <th scope="col" style="width:125px;"><?php _e('Transaction ID','colabsthemes') ?></th>
                    <th scope="col"><?php _e('Payment Type','colabsthemes') ?></th>
                    <th scope="col"><?php _e('Payment Status','colabsthemes') ?></th>
                    <th scope="col"><?php _e('Total Amount','colabsthemes') ?></th>
                    <th scope="col" style="width:150px;"><?php _e('Date Paid','colabsthemes') ?></th>
                    <th scope="col" style="text-align:center;width:100px;"><?php _e('Actions','colabsthemes') ?></th>
                </tr>
            </thead>

    <?php
        // must be higher than personal edition so let's query the db
        $sql = "SELECT o.*, p.post_title "
             . "FROM " . $wpdb->prefix . "colabs_order_info o, $wpdb->posts p "
             . "WHERE o.ad_id = p.id "
             . "ORDER BY o.id desc";

        $results = $wpdb->get_results( $sql );

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

                    <td><strong><?php echo $result->first_name ?> <?php echo $result->last_name ?></strong><br /><a href="mailto:<?php echo $result->payer_email ?>"><?php echo $result->payer_email ?></a></td>
                    <td style="text-align: center;">
                        <?php if ($result->payer_status == 'verified') { ?><img src="<?php bloginfo('template_directory'); ?>/images/paypal_verified.gif" alt="" title="" /><br /><?php } ?>
                        <?php echo ucfirst($result->payer_status) ?>
                    </td>
                    <td><a href="post.php?action=edit&post=<?php echo $result->ad_id ?>"><?php echo $result->post_title ?></a></td>
                    <td><?php echo $result->item_name ?></td>
                    <td><?php echo $result->txn_id ?></td>
                    <td><?php echo ucfirst($result->payment_type) ?></td>
                    <td><?php echo ucfirst($result->payment_status) ?></td>
                    <td><?php echo $result->mc_gross ?> <?php echo $result->mc_currency ?></td>
                    <td><?php echo mysql2date(get_option('date_format') .' '. get_option('time_format'), $result->payment_date) ?></td>
                    <td style="text-align:center">
                      <?php
                        echo '<a onclick="return confirmBeforeDelete();" href="?page=transactions&amp;action=delete&amp;id='. $result->id .'" title="'. __('Delete', 'colabsthemes') .'"><img src="'. get_bloginfo('template_directory') .'/images/close.png" alt="'. __('Delete', 'colabsthemes') .'" /></a>&nbsp;&nbsp;&nbsp;';
                        if(strtolower($result->payment_status) == 'completed')
                          echo '<br /><a href="?page=transactions&amp;action=unsetPaid&amp;id='. $result->id .'" title="'. __('Mark as Unpaid', 'colabsthemes') .'">'. __('Unmark Paid', 'colabsthemes') .'</a>';
                        else
                          echo '<br /><a href="?page=transactions&amp;action=setPaid&amp;id='. $result->id .'" title="'. __('Mark as Paid', 'colabsthemes') .'">'. __('Mark Paid', 'colabsthemes') .'</a>';
                      ?>
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
                <td>&nbsp;</td><td colspan="10"><?php _e('No transactions found.','colabsthemes') ?></td>
            </tr>

        <?php
        } // end $results
        ?>

        </table> <!-- this is ok -->


        <div class="icon32" id="icon-themes"><br /></div>
        <h2><?php _e('Membership Orders','colabsthemes') ?></h2>
        <table id="tblspacer" class="widefat fixed">

            <thead>
                <tr>
                    <th scope="col" style="width:35px;">&nbsp;</th>
                    <th scope="col"><?php _e('Payer Name','colabsthemes') ?></th>
                    <th scope="col" style="text-align: center;"><?php _e('Payer Status','colabsthemes') ?></th>
                    <th scope="col"><?php _e('Item Description','colabsthemes') ?></th>
                    <th scope="col" style="width:125px;"><?php _e('Transaction ID','colabsthemes') ?></th>
                    <th scope="col"><?php _e('Payment Type','colabsthemes') ?></th>
                    <th scope="col"><?php _e('Payment Status','colabsthemes') ?></th>
                    <th scope="col"><?php _e('Total Amount','colabsthemes') ?></th>
                    <th scope="col" style="width:150px;"><?php _e('Date Paid','colabsthemes') ?></th>
                    <th scope="col" style="text-align:center;width:100px;"><?php _e('Actions','colabsthemes') ?></th>
                </tr>
            </thead>


		<?php
        // seperate table for membership orders
        $sql = "SELECT * "
             . "FROM " . $wpdb->prefix . "colabs_order_info "
             . "WHERE ad_id = 0 "
             . "ORDER BY id desc";

        $results = $wpdb->get_results($sql);

            if ($results) {
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
					<?php $payer = get_user_by('email', $result->payer_email); ?>
                    <?php //TODO - LOOKUP CUSTOMER BY PAYPAL EMAIL CUSTOM PROFILE FIELD ?>
                    <td><strong><?php echo $result->first_name ?> <?php echo $result->last_name ?></strong><br /><a href="<?php if(isset($payer->ID) && $payer) echo get_bloginfo('url').'/wp-admin/user-edit.php?user_id='.$payer->ID; else echo 'mailto:'.$result->payer_email; ?>"><?php echo $result->payer_email ?></a></td>
                    <td style="text-align: center;">
                        <?php if ($result->payer_status == 'verified') { ?><img src="<?php bloginfo('template_directory'); ?>/images/paypal_verified.gif" alt="" title="" /><br /><?php } ?>
                        <?php echo ucfirst($result->payer_status) ?>
                    </td>
                    <td><?php echo $result->item_name ?></td>
                    <td><?php echo $result->txn_id ?></td>
                    <td><?php echo ucfirst($result->payment_type) ?></td>
                    <td><?php echo ucfirst($result->payment_status) ?></td>
                    <td><?php echo $result->mc_gross ?> <?php echo $result->mc_currency ?></td>
                    <td><?php echo mysql2date(get_option('date_format') .' '. get_option('time_format'), $result->payment_date) ?></td>
                    <td style="text-align:center">
                      <?php
                        echo '<a onclick="return confirmBeforeDelete();" href="?page=transactions&amp;action=delete&amp;id='. $result->id .'" title="'. __('Delete', 'colabsthemes') .'"><img src="'. get_bloginfo('template_directory') .'/images/close.png" alt="'. __('Delete', 'colabsthemes') .'" /></a>&nbsp;&nbsp;&nbsp;';
                        if(strtolower($result->payment_status) == 'completed')
                          echo '<br /><a href="?page=transactions&amp;action=unsetPaid&amp;id='. $result->id .'" title="'. __('Mark as Unpaid', 'colabsthemes') .'">'. __('Unmark Paid', 'colabsthemes') .'</a>';
                        else
                          echo '<br /><a href="?page=transactions&amp;action=setPaid&amp;id='. $result->id .'" title="'. __('Mark as Paid', 'colabsthemes') .'">'. __('Mark Paid', 'colabsthemes') .'</a>';
                      ?>
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
                    <td>&nbsp;</td><td colspan="9"><?php _e('No transactions found.','colabsthemes') ?></td>
                </tr>

            <?php
            } // end $results
            ?>

            </table> <!-- this is ok -->


        </div><!-- end wrap -->

    <?php
    } // endswitch
    ?>



    <script type="text/javascript">
        /* <![CDATA[ */
            function confirmBeforeDelete() { return confirm("<?php _e('WARNING: Are you sure you want to delete this transaction entry?? (This cannot be undone)', 'colabsthemes'); ?>"); }
        /* ]]> */
    </script>

<?php

}
?>